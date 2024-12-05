<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FileUploadService;
use Exception;

class ImageController extends Controller
{
    protected $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    public function fileUpload(Request $request)
    {
        $file = $request->file('file');

        // array_reverse($results);
        return json_encode($this->fileUploadService->uploadFile($file, 'file'));
    }

    public function fileDelete(Request $request)
    {
        $path = $request->get('path');

        return json_encode($this->fileUploadService->deleteFile($path));
    }
}
