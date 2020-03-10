<?php

declare(strict_types=1);

namespace App\Facades;

use App\Services\ImageService;
use Illuminate\Support\Facades\Facade;

class ImageIntervention extends Facade
{
    protected static function getFacadeAccessor(): string 
    {
        return ImageService::class;
    }
}