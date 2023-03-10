<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Mime\MimeTypes;
use App\Facades\ImageIntervention;

class StorageService
{
    public const DOCTORS_PHOTO_DIR = 'doctors/photos';
    public const DOCTORS_MEDICAL_DEGREES_DIR = 'doctors/medical_degrees';
    public const DOCTORS_BOARD_CERTIFICATION_DIR = 'doctors/board_certifications';

    public const ENQUIRE_IMAGES_DIR = 'enquires/images';
    public const ENQUIRE_MESSAGE_ATTACHMENTS_DIR = 'enquires_messages/attachments';

    /**
     * @param UploadedFile $photo
     * @return string
     */
    public function saveDoctorsPhoto(UploadedFile $photo): string
    {
        $photo = ImageIntervention::orientate($photo);
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
        $image = ImageIntervention::orientate($image);
        return $this->saveFile(
            $image,
            self::ENQUIRE_IMAGES_DIR . '/' . date('Y/m/d'),
            $image->hashName()
        );
    }

    public function getEnquireImageBase64(string $image): string
    {
        $image = $this->getContent($image);
        $tmpFilePath = sys_get_temp_dir() . '/' . Str::uuid()->toString();

        file_put_contents($tmpFilePath, $image);

        $type = MimeTypes::getDefault()->guessMimeType($tmpFilePath);

        unlink($tmpFilePath);

        return 'data:' . $type . ';base64,' . base64_encode($image);
    }

    public function getContent(string $file): string
    {
        return Storage::get($file);
    }

    public function saveMessageEnquiryAttachment(string $attachment, string $name, string $extension): string
    {
        return $this->saveBinaryFile(
            $attachment,
            self::ENQUIRE_MESSAGE_ATTACHMENTS_DIR . date('/Y/m/d'),
            $name,
            $extension
        );
    }

    public function saveBinaryFile(string $file, string $path, string $name, string $extension)
    {
        $path = Str::lower(trim($path));
        $name = $this->getUniqueFileName($path, Str::slug($name), $extension);

        $path .= '/' . $name . '.' . $extension;

        return Storage::put($path, $file) ? $path : false;
    }

    /**
     * @param UploadedFile $file
     * @param string $path
     * @param string $name
     * @return string|false
     */
    public function saveFile(UploadedFile $file, string $path, string $name)
    {
        $path = Str::lower(trim($path));
        $name = $this->getUniqueFileName($path, Str::slug($name), $file->extension());

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

    public function guessContentExtension(string $file): string
    {
        return MimeTypes::getDefault()->getExtensions($this->guessContentMimeType($file))[0];
    }

    public function guessContentMimeType(string $file): string
    {
        $mimeType = (new \finfo(FILEINFO_MIME_TYPE))->buffer($file);

        if ($pos = strpos($mimeType, ';')) {
            return substr($mimeType, 0, $pos - 1);
        }

        return $mimeType;
    }
}