<?php

namespace App\Services;

use Intervention\Image\Facades\Image;

class ThumbnailService
{
    /**
     * Create a thumbnail of specified size
     *
     * @param string $path path of thumbnail
     * @param int $width
     * @param int $height
     * @param string|null $savePath
     */
    public function create(string $path, int $width, int $height, string $savePath = null): void
    {
        $img = Image::make($path)->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });

        $img->save($savePath ?? $path);
    }
}
