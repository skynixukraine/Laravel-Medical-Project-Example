<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\File;

class DecodeBase64Files
{
    public function handle(Request $request, Closure $next, ...$attributes)
    {
        foreach ($attributes as $attribute) {
            if (!$request->has($attribute)) {
                continue;
            }

            $fileData = base64_decode(preg_replace('#^data:[a-z/-]+;base64,#i', '', $request->{$attribute}));

            if (!$fileData) {
                continue;
            }

            $tmpFilePath = sys_get_temp_dir() . '/' . Str::uuid()->toString();

            file_put_contents($tmpFilePath, $fileData);

            $tmpFile = new File($tmpFilePath);

            $request->files->set($attribute, new UploadedFile(
                $tmpFile->getPathname(),
                $tmpFile->getFilename(),
                $tmpFile->getMimeType(),
                0, true
            ));

            unset($request[$attribute]);
        }

        return $next($request);
    }
}