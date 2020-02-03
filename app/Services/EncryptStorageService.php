<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Mime\MimeTypes;

class EncryptStorageService extends StorageService
{
    public function saveEncryptedFile(UploadedFile $file, string $path, string $name): string
    {
        $path = Str::lower(trim($path));
        $name = $this->getUniqueFileName($path, Str::slug($name), $file->extension());
        $path .= '/' . $name . '.dat';

        if (!Storage::put($path, encrypt($file->get()))) {
            throw new \Exception();
        }

        return $path;
    }

    public function saveDoctorsMedicalDegree(UploadedFile $file): string
    {
        return $this->saveEncryptedFile(
            $file,
            self::DOCTORS_MEDICAL_DEGREES_DIR . '/' . date('Y/m/d'),
            $file->hashName());
    }

    public function saveDoctorsBoardCertification(UploadedFile $file): string
    {
        return $this->saveEncryptedFile(
            $file,
            self::DOCTORS_BOARD_CERTIFICATION_DIR . '/' . date('Y/m/d'),
            $file->hashName());
    }

    public function getDecryptedBase64Uri(string $file): string
    {
        $decryptedContent = $this->getDecryptedContent($file);

        $tmpFilePath = sys_get_temp_dir() . '/' . Str::uuid()->toString();

        file_put_contents($tmpFilePath, $decryptedContent);

        $type = MimeTypes::getDefault()->guessMimeType($tmpFilePath);

        unlink($tmpFilePath);

        return 'data:' . $type . ';base64,' . base64_encode($decryptedContent);
    }

    public function getDecryptedContent(string $file): string
    {
        return decrypt(Storage::get($file));
    }
}