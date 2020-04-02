<?php

declare(strict_types=1);

namespace App\Exceptions;

use Throwable;

class CantSaveFileException extends \Exception
{
    public function __construct(string $path, $code = 0, Throwable $previous = null)
    {
        parent::__construct(__('The file with the path \'' . $path . '\'can not be saved.'), $code, $previous);
    }
}