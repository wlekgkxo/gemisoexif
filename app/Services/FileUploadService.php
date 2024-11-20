<?php

namespace App\Services;

use Exception;
use Illuminate\Http\UploadedFile;

class FileUploadService
{
    public function uploadMedia(UploadedFile $file, $directory = 'upload')
    {
        $results = [ 'original_name' => $file->getClientOriginalName(),
                     'path' => $file->store($directory, 'public')];

        $results['storage_path'] = '/storage/'.$results['path'];

        if($directory === 'upload/images' || $directory === 'upload/raws') {
            $results['thumbnail'] = $this->uploadImageThumb($results);
        }

        return (object) $results;
    }

    public function uploadImageThumb($image)
    {
        try {
            $public_path = public_path();
            $public_path = preg_replace('/\\\\/', '/', $public_path);
            $imgck = new \Imagick($public_path.$image['storage_path']);
    
            $imgck->setImageFormat('jpeg');
            $imgck->thumbnailImage(200, 0);
    
            $thumb_name = basename(preg_replace('/\.[^.]+$/', '', $image['path'])).".jpg";

            $rep = $imgck->writeImage(storage_path()
            .DIRECTORY_SEPARATOR."app"
            .DIRECTORY_SEPARATOR."public"
            .DIRECTORY_SEPARATOR."upload"
            .DIRECTORY_SEPARATOR."thumbs"
            .DIRECTORY_SEPARATOR.$thumb_name);

            return "/storage/upload/thumbs/".$thumb_name;
        } catch(Exception $e) {
            return null;
        }
    }
}