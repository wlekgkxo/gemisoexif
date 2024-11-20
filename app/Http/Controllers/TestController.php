<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MediaMetaDataService;
use App\Services\FileUploadService;
use Exception;

class TestController extends Controller
{
    protected $mediaMetaDataService;
    protected $fileUploadService;

    public function __construct(FileUploadService $fileUploadService, MediaMetaDataService $mediaMetaDataService)
    {
        $this->mediaMetaDataService = $mediaMetaDataService;
        $this->fileUploadService = $fileUploadService;
    }

    public function getFileMeta(Request $request):String {
        $imagePattern = '/^(jpg|jpeg|png|gif|bmp)$/i';
        $RawImagePattern1 = '/^(cr2|arw|cr3)$/i';
        $videoPattern = '/^(mp4|avi|mpeg|mov|wmv|flv|webm)$/i';
        $file = $request->file('test_meta');

        $extension = $file->getClientOriginalExtension();
        
        if (preg_match($imagePattern, $extension)) {
            // 파일 업로드
            $filePath = $this->fileUploadService->uploadMedia($file, 'upload/images');
            $results = $this->mediaMetaDataService->getImageMeta($filePath);
        } elseif (preg_match($videoPattern, $extension)) {
            $filePath = $this->fileUploadService->uploadMedia($file, 'upload/videos');
            $results = $this->mediaMetaDataService->getVideoMetaData($filePath);
        } elseif (preg_match($RawImagePattern1, $extension)) {
            $filePath = $this->fileUploadService->uploadMedia($file, 'upload/raws');
            $results = $this->mediaMetaDataService->getRawMeta($filePath);
        } else {
            throw new Exception('This extension file is not supported');
        }

        return json_encode($results);
    }

}
