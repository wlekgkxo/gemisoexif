<?php

namespace App\Services;

class JsonCreateService
{
    public function makingJson($data)
    {
        $media = $data->media;

        $file_name = 'media_'.now()->timestamp.'_'.$media->media_id.'.json';
        $file_path = storage_path('app/public/' . $file_name);

        file_put_contents($file_path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return response()->json([
            'message' => 'JSON 파일이 성공적으로 저장되었습니다.',
            'file' => $file_name,
        ]);
    }
}