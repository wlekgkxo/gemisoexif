<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;

use App\Services\FileUploadService;
use App\Services\MediaMetaService;

use App\Models\AcMedia;

class UploaderController extends Controller
{
    protected $mediaMetaService;
    protected $fileUploadService;

    public function __construct(FileUploadService $fileUploadService, MediaMetaService $mediaMetaService)
    {
        $this->fileUploadService = $fileUploadService;
        $this->mediaMetaService = $mediaMetaService;
    }

    public function list(Request $request)
    {
        $get_query = AcMedia::query()->orderByDesc('id');

        if($request->has('media_type')) $get_query->where('media_type', $request->media_type);

        if($request->has('filter') && $request->has('order')) $get_query->orderBy($request->filter, $request->order);

        $list = $get_query->get();

        return json_encode($list);
    }

    public function upload(Request $request)
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
                /* $file_info = [];
                $file_info['file'] = 
                $file_info['meta'] = $this->mediaMetaService->getImageMeta($file_info['file']); */
                $file_info = $this->fileUploadService->uploadMedia($file, 'upload/images');
                $this->mediaMetaService->getImageMeta($file_info);
                $results[] = $file_info;
            } elseif (preg_match($videoPattern, $extension)) {
                /* $file_info = [];
                $file_info['file'] = $this->fileUploadService->uploadMedia($file, 'upload/videos');
                $file_info['meta'] =  $this->mediaMetaService->getVideoMetaData($file_info['file']); */
                $file_info = $this->fileUploadService->uploadMedia($file, 'upload/videos');
                $this->mediaMetaService->getVideoMetaData($file_info);
                $results[] = $file_info;
            } elseif (preg_match($RawImagePattern1, $extension)) {
                /* $file_info = [];
                $file_info['file'] = $this->fileUploadService->uploadMedia($file, 'upload/raws');
                $file_info['meta'] = $this->mediaMetaService->getRawMeta($file_info['file']); */
                $file_info = $this->fileUploadService->uploadMedia($file, 'upload/raws');
                $this->mediaMetaService->getRawMeta($file_info);
                $results[] = $file_info;
            } else {
                throw new Exception('This extension file is not supported');
            }
        }
        // array_reverse($results);
        return json_encode($results);
    }
}
