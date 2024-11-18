<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MediaMetaDataService;
use App\Services\FileUploadService;

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
        $RawImagePattern1 = '/^(cr2|arw)$/i';
        $RawImagePattern2 = '/^(cr3)$/i';
        $videoPattern = '/^(mp4|avi|mpeg|mov|wmv|flv|webm)$/i';
        $file = $request->file('test_meta');

        $extension = $file->getClientOriginalExtension();

        if (preg_match($imagePattern, $extension)) {
            // 파일 업로드
            $filePath = $this->fileUploadService->uploadMedia($file, 'upload/images');
            $results = $this->mediaMetaDataService->getImageMetaData($filePath);
        } elseif (preg_match($videoPattern, $extension)) {
            $filePath = $this->fileUploadService->uploadMedia($file, 'upload/videos');
            $results = $this->mediaMetaDataService->getVideoMetaData($filePath);
        } elseif (preg_match($RawImagePattern1, $extension)) {
            $filePath = $this->fileUploadService->uploadMedia($file, 'upload/raws');
            $results = $this->mediaMetaDataService->getRawImage2Meta($filePath);
        } elseif (preg_match($RawImagePattern2, $extension)) {
            $filePath = $this->fileUploadService->uploadMedia($file, 'upload/raws');
            $results = $this->mediaMetaDataService->getRawImage2Meta($filePath);
        } else {
            return json_encode(['messages' => '이미지나 비디오 확장자만 올려주세요.']);
        }

        return json_encode($results);
    }

}
