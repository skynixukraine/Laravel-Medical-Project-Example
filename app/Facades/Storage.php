<?php

declare(strict_types=1);

namespace App\Facades;

use App\Services\EncryptStorageService;
use Illuminate\Support\Facades\Facade;

class Storage extends Facade
{
    protected static function getFacadeAccessor(): string 
    {
        return EncryptStorageService::class;
    }
}