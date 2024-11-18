<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;


class FileUploadService
{
    public function uploadMedia(UploadedFile $file, $directory = 'upload')
    {
        $results = [ 'original_name' => $file->getClientOriginalName(),
                     'path' => $file->store($directory, 'public'),
                     'storage_path' => './storage/'.$file->store($directory, 'public') ];
        return (object) $results;
    }
}