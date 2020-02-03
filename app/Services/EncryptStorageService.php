<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\CantSaveFileException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Mime\MimeTypes;

class EncryptStorageService extends StorageService
{
    public function saveEncryptedFile(UploadedFile $file, string $path, string $name): string
    {
        $path = Str::lower(trim($path));
        $name = $this->getUniqueFileName($path, Str::slug($name), 'dat');
        $path .= '/' . $name . '.dat';

        throw_if(!Storage::put($path, encrypt($file->get())), new CantSaveFileException($path));

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

    public function saveEncryptedBinaryFile(string $file, string $path, string $name)
    {
        $path = Str::lower(trim($path));
        $name = $this->getUniqueFileName($path, Str::slug($name), 'dat');

        $path .= '/' . $name . '.dat';

        throw_if(!Storage::put($path, encrypt($file)), new CantSaveFileException($path));

        return $path;
    }

    public function saveMessageEnquiryAttachment(string $attachment, string $name, string $extension = null): string
    {
        return $this->saveEncryptedBinaryFile(
            $attachment,
            self::ENQUIRE_MESSAGE_ATTACHMENTS_DIR . date('/Y/m/d'),
            $name
        );
    }
}