<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UserService
{
    public function __construct(
        private ThumbnailService $thumbnailService
    )
    {
    }

    /**
     * Обновляет профиль
     *
     * @param string $id
     * @param array $data
     * @return void
     */
    public function update(string $id, array $data = []): void
    {
        $user = User::findOrFail($id);

        /** @var UploadedFile $photo */
        if ($photo = Arr::get($data, 'photo')) {
            $pathToSave = 'public/user/photos/';

            $extension = $photo->getClientOriginalExtension();

            $photoName = sprintf('%s.%s', Str::uuid(), $extension);

            $data['photo'] = $photoName;

            // Удаляем фотографию, если ранее был добавлен
            if ($user->photo) {
                Storage::disk()->delete([
                    $pathToSave . $user->photo,
                    $pathToSave . '/thumbnails/' . $user->photo
                ]);
            }

            // Сохраняем оригинальное фото
            $photo->storeAs($pathToSave, $photoName);

            $this->thumbnailService->create(
                Storage::disk()->path($pathToSave . $photoName),
                240,
                240,
                Storage::disk()->path($pathToSave . 'thumbnails/' . $photoName)
            );
        }

        $user->update($data);
    }
}
