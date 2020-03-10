<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\UploadedFile;

class ImageService
{

    /**
     * @param UploadedFile $photo
     * @return UploadedFile
     */
    public function makeThumb(UploadedFile $image) : UploadedFile
    {
        $image_thumb = \Intervention\Image\Facades\Image::make($image->getRealPath())->resize(285, 285, function($constraint)
        {
            $constraint->aspectRatio();
        });

        $image_thumb->save($image->getRealPath());
        return new UploadedFile($image_thumb->basePath(), $image_thumb->stream()->__toString());

    }

}