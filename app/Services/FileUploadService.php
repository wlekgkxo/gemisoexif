<?php

namespace App\Services;

use Exception;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use Intervention\Image\Facades\Image;

use App\Models\Media;

use Carbon\Carbon;


class FileUploadService
{
    public function uploadMedia($file, $type)
    {
        // $media = (object) [];

        // $media->content_id = $this->contentService->setEmptyContent();
        // $media->storage_id = 139;
        // $media->media_type = 'ingest_pub';
        // $media->status = 0;

        // $media->created_date = Carbon::now()->format('YmdHis');
        // $media->expired_date = Carbon::now()->addMonth()->format('YmdHis');
        // $media->is_file = 1;

            // path
        // filesize
        // getClientOriginalName
        $results = (object) [];
        $results->original_name = $file->getClientOriginalName();
        $file_path = env('STORAGE_UPLOAD').'/'.$type;
        $file_name = uniqid() . '.' . $file->getClientOriginalExtension();

        $file_info = $file->move($file_path, $file_name);

        $results->path = $file_info->getPathname();
        $file_name = $file_info->getFilename();

        if($type === 'image') $results->thumbnail = $this->makeThumbnail($results->path, $file_name);

        // $success['exif'] = $this->getExif($path);

        if ($results) {
            return $results;
        } else {
            return response()->json(['error' => 'Failed to save image'], 500);
        }
    }

    public function uploadFile($file, $type)
    {
        $results = (object) [];
        $results->original_name = $file->getClientOriginalName();
        $file_path = env('STORAGE_UPLOAD').'/'.$type;
        $file_name = uniqid() . '.' . $file->getClientOriginalExtension();

        $file_info = $file->move($file_path, $file_name);

        $results->path = $file_info->getPathname();
        $file_name = $file_info->getFilename();
        $results->size = $file_info->getSize();

        $results->thumbnail = env('STORAGE_ROOT').'/file/'.$file_name;

        if ($results) {
            return $results;
        } else {
            return response()->json(['error' => 'Failed to save image'], 500);
        }
    }

    public function makeThumbnail($path, $file_name)
    {
        // $thumb_file = public_path('storage').'\\upload\\thumb\\'.$file_name;
        // $command = "exiftool -b -ThumbnailImage ".$path." > ".$thumb_file;
        // $output = shell_exec($command);
        // $thumb_j = json_decode($output, true);
        // $thumb = $thumb_j[0];
        // return '/storage/upload/thumb/'.$file_name;

        $thumb_path = public_path('storage/upload/thumb');

        $thumb = Image::make($path)->resize(200, 200, function ($constraint) {
            $constraint->aspectRatio(); // 원본 비율 유지
            $constraint->upsize();      // 이미지 확대 방지
        });

        // 썸네일 저장
        $thumb->save($thumb_path.'/'.$file_name);

        return env('STORAGE_ROOT').'/thumb/'.$file_name;
    }

    public function getExif($path)
    {
        $command = "exiftool -j " . escapeshellarg($path);
        $output = shell_exec($command);
        $metadata_j = json_decode($output, true);

        $metadata = $metadata_j[0];

        $results = (object) [];

        return $metadata;
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
    
    public function uploadVideoThumbAndMeta($video)
    {
        $thumb_sec = 5; // 영상 썸네일 구간
        try {
            $ffmpeg = FFMpeg::create([
                'ffmpeg.binaries' => 'C:/Servers/ffmpeg-7.1-full_build-shared/bin/ffmpeg.exe', // 설치된 ffmpeg 경로
                'ffprobe.binaries' => 'C:/Servers/ffmpeg-7.1-full_build-shared/bin/ffprobe.exe', // 설치된 ffprobe 경로
                'timeout' => 3600, // 초 단위 시간 제한
                'ffmpeg.threads' => 12, // 쓰레드 개수
            ]);
            
            $ffmpeg_open = $ffmpeg->open('.'.$video['storage_path']);

            $thumb_name = basename(preg_replace('/\.[^.]+$/', '', $video['path'])).".jpg";
            $ffmpeg_open->frame(TimeCode::fromSeconds($thumb_sec))
            ->save(storage_path()
            .DIRECTORY_SEPARATOR."app"
            .DIRECTORY_SEPARATOR."public"
            .DIRECTORY_SEPARATOR."upload"
            .DIRECTORY_SEPARATOR."thumbs"
            .DIRECTORY_SEPARATOR.$thumb_name);
            
            $video_format = $ffmpeg_open->getFormat();
            $video_streams = $ffmpeg_open->getStreams()->videos()->first();

            return ['thumbs' => "/storage/upload/thumbs/".$thumb_name, 'video_format' => $video_format, 'video_streams' =>$video_streams];
        } catch(Exception $e) {
            return null;
        }
    }

    public function removeMedia($files)
    {
        try {
            $success = [];
            if($files) {
                foreach($files as $file) {
                    if(unlink($file->media->path)) {
                        unlink('.'.$file->media->thumbnail);
                        $success[] = $file->media->path;
                        // AcMedia::find($file->media->media_id)->delete();
                    }
                }

                return $success;
            }
        } catch(Exception $e) {
            dd($e->getMessage());
        }
    }

    public function deleteFile($path)
    {
        try {
            $success = [];
            if($path) {
                if(unlink($path)) {
                    $success[] = $path;
                }
            }

            return $success;
        } catch(Exception $e) {
            dd($e->getMessage());
        }
    }
}