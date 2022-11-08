<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageService
{
    /**
     * Создает миниатюру по размеру
     *
     * @param string $path path of thumbnail
     * @param int $width
     * @param int $height
     * @param string|null $savePath
     */
    public function createThumbnail(string $path, int $width, int $height, string $savePath = null): void
    {
        $img = Image::make($path)->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });

        $img->save($savePath ?? $path);
    }

    /**
     * Загружает фотографию и создает миниатюру
     *
     * @param UploadedFile $file
     * @param string $path
     * @param array $options
     * @return false|string
     */
    public function uploadImageWithThumbnail(UploadedFile $file, string $path, array $options = []): bool|string
    {
        $disk = Arr::get($options, 'disk', 'public');
        $path = rtrim($path, '/') . DIRECTORY_SEPARATOR;

        $extension = $file->getClientOriginalExtension();
        $photoName = sprintf('%s.%s', Str::uuid(), $extension);

        $isStored = Storage::disk($disk)->putFileAs($path, $file, $photoName);

        if (!$isStored) {
            return false;
        }

        $this->createThumbnail(
            Storage::disk($disk)->path($path . $photoName),
            Arr::get($options, 'thumbnails.width', 240),
            Arr::get($options, 'thumbnails.height', 240),
            Storage::disk($disk)->path($path . 'thumbnails/' . $photoName)
        );

        return $photoName;
    }
}
