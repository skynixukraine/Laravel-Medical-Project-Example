<?php

declare(strict_types=1);

namespace App\Facades;

use App\Services\SvgService;
use Illuminate\Support\Facades\Facade;

class Svg extends Facade
{
    protected static function getFacadeAccessor(): string 
    {
        return SvgService::class;
    }
}