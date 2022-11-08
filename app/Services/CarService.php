<?php

namespace App\Services;

use App\Models\Car;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CarService
{
    public const PHOTOS_DIR = 'car/photos';

    public function __construct(
        private ImageService $imageService
    )
    {
    }

    /**
     * Добавляет новое авто
     *
     * @param array $data
     * @return Car
     */
    public function store(array $data): Car
    {
        /** @var UploadedFile $photo */
        if ($photo = Arr::get($data, 'photo')) {
            $data['photo'] = $this->imageService->uploadImageWithThumbnail(
                $photo,
                self::PHOTOS_DIR
            ) ?: null;
        }

        return Car::create(['user_id' => auth()->id()] + $data);
    }

    /**
     * Обновляет данные авто
     *
     * @param string $id
     * @param array $data
     * @return Car
     */
    public function update(string $id, array $data): Car
    {
        $car = Car::forUser(Arr::get($data, 'user_id'))->findOrFail($id);

        /** @var UploadedFile $photo */
        if ($photo = Arr::get($data, 'photo')) {

            $fileName = $this->imageService->uploadImageWithThumbnail(
                $photo,
                self::PHOTOS_DIR
            );

            if ($fileName !== false) {
                $data['photo'] = $fileName;

                // Удаляем фотографию, если ранее был добавлен
                if ($car->photo) {
                    $this->clearPhotos(self::PHOTOS_DIR, $car->photo);
                }
            }
        }

        $car->update($data);

        return $car;
    }

    /**
     * Удаляет авто
     *
     * @param string $id
     * @param array $data
     * @return void
     */
    public function destroy(string $id, array $data = []): void
    {
        $car = Car::forUser(Arr::get($data, 'user_id'))->findOrFail($id);

        $car->delete();
    }

    /**
     * Чистит фотографии из директории
     *
     * @param $path
     * @param string $photo
     * @return void
     */
    public function clearPhotos($path, string $photo): void
    {
        $path = rtrim($path, '/') . DIRECTORY_SEPARATOR;

        Storage::disk('public')->delete([
            $path . $photo,
            $path . 'thumbnails/' . $photo
        ]);
    }
}
