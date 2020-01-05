<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StorageService
{
    private const DOCTORS_PHOTO_DIR = 'doctors/photos';
    private const DOCTORS_MEDICAL_DEGREES_DIR = 'doctors/medical_degrees';
    private const DOCTORS_BOARD_CERTIFICATION_DIR = 'doctors/board_certification';

    private const ENQUIRE_IMAGES_DIR = 'enquires/images';

    /**
     * @param UploadedFile $photo
     * @return string
     */
    public function saveDoctorsPhoto(UploadedFile $photo): string
    {
        return $this->saveFile(
            $photo,
            self::DOCTORS_PHOTO_DIR . '/' . date('Y/m/d'),
            $photo->hashName()
        );
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    public function saveDoctorsMedicalDegree(UploadedFile $file): string
    {
        return $this->saveFile(
            $file,
            self::DOCTORS_MEDICAL_DEGREES_DIR . '/' . date('Y/m/d'),
            $file->hashName()
        );
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    public function saveDoctorsBoardCertification(UploadedFile $file): string
    {
        return $this->saveFile(
            $file,
            self::DOCTORS_BOARD_CERTIFICATION_DIR . '/' . date('Y/m/d'),
            $file->hashName()
        );
    }

    public function saveEnquireImage(UploadedFile $image): string
    {
        return $this->saveFile(
            $image,
            self::ENQUIRE_IMAGES_DIR . '/' . date('Y/m/d'),
            $image->hashName()
        );
    }

    /**
     * @param UploadedFile $file
     * @param string $path
     * @param string $name
     * @return string
     */
    public function saveFile(UploadedFile $file, string $path, string $name): string
    {
        $path = Str::lower(trim($path));
        $name = $this->getUniqueFileName($path, Str::slug(trim($name)), $file->extension());

        return Storage::putFileAs($path, $file, $name . '.' . $file->extension());
    }

    public function removeFile(string $file)
    {
        return Storage::delete($file);
    }

    /**
     * @param string $path
     * @param string $name
     * @param string $extension
     * @param int $number
     * @return string
     */
    public function getUniqueFileName(string $path, string $name, string $extension, int $number = 0): string
    {
        $numbered = $name . ($number ? ('-' . $number) : '');

        if (Storage::exists($path . '/' . $numbered . '.' . $extension)) {
            return $this->getUniqueFileName($path, $name, $extension, ++$number);
        }

        return $numbered;
    }
}