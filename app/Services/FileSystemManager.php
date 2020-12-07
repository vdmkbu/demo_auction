<?php


namespace App\Services;


use Illuminate\Support\Facades\Storage;

class FileSystemManager
{
    public function path($path)
    {
        return Storage::path($path);
    }

    public function delete($path)
    {
        return Storage::delete($path);
    }
}
