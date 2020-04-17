<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;

class ImageService
{

    /**
     * @param UploadedFile $image
     * @param string $width
     * @param null $height
     * @return UploadedFile
     */
    public function makeThumb(UploadedFile $file, string $width, $height = null) : UploadedFile
    {

        if (!$this->isImage($file)) {
            return $file;
        }

        $height = $height ?: $width;

        /**
         * Resize aspect ratio
         */
        $image_thumb = Image::make($file->getRealPath())->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });

        /**
         * Replace the original file with a thumb
         */
        $image_thumb->save($file->getRealPath());

        return new UploadedFile($image_thumb->basePath(), $image_thumb->stream()->__toString());

    }
    
    public function orientate(UploadedFile $file)
    {
        $image = Image::make($file->getRealPath());

        $orientation = $image->exif('Orientation');

        if (empty($orientation)) {
            return $file;
        }

        $image = $image->orientate();
        $image->save($file->getRealPath());

        return new UploadedFile($image->basePath(), $image->stream()->__toString());
    }

    /**
     * @param UploadedFile $file
     * @return bool
     */
    public function isImage(UploadedFile $file) : bool
    {
        return in_array($file->getMimeType(), ['image/png', 'image/jpeg', 'image/jpg']);
    }

}