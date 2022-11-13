<?php

namespace App\Services;

use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UserService
{
    public function __construct(
        private ImageService $imageService
    )
    {
    }

    /**
     * Обновляет профиль
     *
     * @param string $id
     * @param array $data
     * @return User
     */
    public function update(string $id, array $data = []): User
    {
        $user = User::findOrFail($id);

        /** @var UploadedFile $photo */
        if ($photo = Arr::get($data, 'photo')) {
            $pathToSave = 'user/photos/';

            $fileName = $this->imageService->uploadImageWithThumbnail(
                $photo,
                $pathToSave
            );

            if ($fileName !== false) {
                $data['photo'] = $fileName;

                // Удаляем фотографию, если ранее был добавлен
                if ($user->photo) {
                    Storage::disk('public')->delete([
                        $pathToSave . $user->photo,
                        $pathToSave . 'thumbnails/' . $user->photo
                    ]);
                }
            }
        }

        $user->update($data);

        return $user;
    }

    /**
     * Обновляет счетчики рейтинга у пользователя
     *
     * @param string $userId
     * @return User
     */
    public function recalculateReviewCounts(string $userId): User
    {
        $review = Review::select(DB::raw('user_id, AVG(rating) as rating, COUNT(id) as reviews'))
            ->where('user_id', $userId)
            ->groupBy('user_id')
            ->first();

        $user = User::findOrFail($userId);

        if ($review) {
            $user->update([
                'review_rating' => $review->rating ?? 0,
                'review_total' => $review->reviews ?? 0,
            ]);
        }

        return $user;
    }
}
