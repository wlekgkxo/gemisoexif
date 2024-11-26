<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use App\Services\FileUploadService;
use App\Services\MediaMetaService;
use App\Services\JsonCreateService;

class IngestRequestController extends Controller
{
    protected $mediaMetaService;
    protected $fileUploadService;
    protected $jsonCreateService;

    public function __construct(FileUploadService $fileUploadService,
                                MediaMetaService $mediaMetaService,
                                JsonCreateService $jsonCreateService)
    {
        $this->fileUploadService = $fileUploadService;
        $this->mediaMetaService = $mediaMetaService;
        $this->jsonCreateService = $jsonCreateService;
    }

    public function mediaUpload(Request $request)
    {
        $results = [];
        $files = $request->file('files');
        
        $imagePattern = '/^(jpg|jpeg|png|gif|bmp)$/i';
        $RawImagePattern1 = '/^(cr2|arw|cr3)$/i';
        $videoPattern = '/^(mp4|avi|mpeg|mov|wmv|flv|webm)$/i';

        $results = [];
        foreach($files as $file) {
            $extension = $file->getClientOriginalExtension();
            
            if (preg_match($imagePattern, $extension)) {
                $file_info = $this->fileUploadService->uploadMedia($file);
                // $file_info = $this->fileUploadService->uploadMedia($file, 'upload/images');
                $results[] = ['media' => $file_info, 'static' => $this->mediaMetaService->getImageMeta($file_info)];
            } elseif (preg_match($videoPattern, $extension)) {
                $file_info = $this->fileUploadService->uploadMedia($file);
                // $file_info = $this->fileUploadService->uploadMedia($file, 'upload/videos');
                $results[] = ['media' => $file_info, 'static' => $this->mediaMetaService->getVideoMetaData($file_info)];
            } elseif (preg_match($RawImagePattern1, $extension)) {
                $file_info = $this->fileUploadService->uploadMedia($file);
                // $file_info = $this->fileUploadService->uploadMedia($file, 'upload/raws');
                $results[] = ['media' => $file_info, 'static' => $this->mediaMetaService->getRawMeta($file_info)];
            } else {
                throw new Exception('This extension file is not supported');
            }
        }
        // array_reverse($results);
        return json_encode($results);
    }

    public function requestIngest(Request $request)
    {
        $datas = $request->get('datas');

        return $this->jsonCreateService->makingJson($datas);
    }

    public function ingestQuit(Request $request)
    {
        $files = json_decode($request->datas);

        return json_encode($this->fileUploadService->removeMedia($files));
    }
}
