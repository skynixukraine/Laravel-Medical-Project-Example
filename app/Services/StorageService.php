<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Doctor;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StorageService
{
    private const DOCTORS_PHOTO_DIR = 'doctors/photos';
    private const DOCTORS_MEDICAL_DEGREES_DIR = 'doctors/medical_degrees';
    private const DOCTORS_BOARD_CERTIFICATION_DIR = 'doctors/board_certification';

    /**
     * @param UploadedFile $photo
     * @return string
     */
    public function saveDoctorPhoto(?UploadedFile $photo): ?string
    {
        return $photo ? $this->saveFile(
            $photo,
            self::DOCTORS_PHOTO_DIR . '/' . date('Y/m/d'),
            $photo->hashName()
        ) : $photo ;
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    public function saveDoctorMedicalDegree(?UploadedFile $file): ?string
    {
        return $file ? $this->saveFile(
            $file,
            self::DOCTORS_MEDICAL_DEGREES_DIR . '/' . date('Y/m/d'),
            $file->hashName()
        ): null;
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    public function saveDoctorCertificate(?UploadedFile $file): ?string
    {
        return $file ? $this->saveFile(
            $file,
            self::DOCTORS_BOARD_CERTIFICATION_DIR . '/' . date('Y/m/d'),
            $file->hashName()
        ) : null;
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