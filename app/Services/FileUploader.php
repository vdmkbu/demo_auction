<?php


namespace App\Services;


use Illuminate\Http\UploadedFile;

class FileUploader
{
    public const FOLDER = 'public';

    public function upload(UploadedFile $file)
    {
        return $file->store(self::FOLDER);
    }
}
