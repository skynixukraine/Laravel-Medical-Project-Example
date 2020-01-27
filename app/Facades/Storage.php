<?php

declare(strict_types=1);

namespace App\Facades;

use App\Services\StorageService;
use Illuminate\Support\Facades\Facade;

class Storage extends Facade
{
    protected static function getFacadeAccessor()
    {
        return StorageService::class;
    }
}