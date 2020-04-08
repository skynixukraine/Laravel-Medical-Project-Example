<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;

class SvgService
{

    public $color = 'red';
    
    public function setColorByIds(string $fileName, array $ids): string
    {
        $frontBody = file_get_contents(public_path() . '/images/' . $fileName);

        $frontBody = str_replace($ids, '" fill="' . $this->color . '' , $frontBody);
        
        return base64_encode($frontBody);
    }
    

}