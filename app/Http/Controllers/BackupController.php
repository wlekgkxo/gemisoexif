<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class BackupController extends Controller
{
    
    public function testImageMetaData($image)
    {
        $thumb_name = basename(preg_replace('/\.[^.]+$/', '', $image->path)).".jpg";

        $thumb = Image::make('.'.$image->storage_path)->resize(500, 500, function ($constraint) {
            $constraint->aspectRatio(); // 원본 비율 유지
            $constraint->upsize();      // 이미지 확대 방지
        });

        // 썸네일 저장
        $thumb->save(storage_path()
        .DIRECTORY_SEPARATOR."app"
        .DIRECTORY_SEPARATOR."public"
        .DIRECTORY_SEPARATOR."upload"
        .DIRECTORY_SEPARATOR."thumbs"
        .DIRECTORY_SEPARATOR.$thumb_name);

        $img_data = Image::make($image->storage_path)->exif();

        $img_data['original'] = $image->original_name;

        if(array_key_exists('GPSLatitude', $img_data) && array_key_exists('GPSLongitude', $img_data)) {
            $img_data['latitude'] = $this->getGps($img_data["GPSLatitude"], $img_data['GPSLatitudeRef']);
            $img_data['longitude'] = $this->getGps($img_data["GPSLongitude"], $img_data['GPSLongitudeRef']);
            $img_data['address'] = $this->getKrLocation($img_data['longitude'], $img_data['latitude'], 'json');
        }

        $img_data['custom_width'] = $img_data['ImageWidth'] ?? $img_data['COMPUTED']['Width'] ?? '정보없음';
        $img_data['custom_height'] = $img_data['ImageLength'] ?? $img_data['COMPUTED']['Height'] ?? '정보없음';

        $img_data['custom_datetime'] = array_key_exists('DateTime', $img_data) ? $img_data['DateTime'] : array_key_exists('FileDateTime', $img_data) ? date('Y-m-d H:i:s', $img_data['FileDateTime']) : '정보없음';

        $img_data['thumb_path'] = "/storage/upload/thumbs/".$thumb_name;
        // $php_exif = exif_read_data($image);
        // echo "<pre>";
        // var_dump($php_exif);
        // echo "</pre>";

        // dd($img_data);

        // $datetime = date('Y-m-d H:i:s', $img_data['FileDateTime']); 시간 안 넘어오면 변환해서라고 쓰려고 놔둠

        // dd($this->getLocation('37.599894', '126.93128', 'json')); 영어 주소
        // dd($this->getKrLocation('126.93128', '37.599894', 'json')); // 한글 주소

        $img_data['type'] = 'image';

        return response()->json(['meta' => $img_data]);
    }

    public function testRawImage1Meta($raw)
    {
        $public_path = public_path();
        $public_path = preg_replace('/\\\\/', '/', $public_path);

        if (!file_exists($raw->storage_path)) {
            throw new Exception('File not found: ' . $raw->storage_path);
        }
        try {
            $imgck = new \Imagick($public_path.$raw->storage_path);
            $meta_data = $imgck->getImageProperties();

            dd($meta_data);
        } catch (\ImagickException $e) {
            echo 'Imagick Error: ' . $e->getMessage();
        } catch (\Exception $e) {
            echo 'General Error: ' . $e->getMessage();
        }

        dd('done');
        // try {
        //     if (!file_exists($raw->storage_path)) {
        //         throw new Exception('File not found: ' . $raw->storage_path);
        //     }

        //     $imgck = new \Imagick($raw->storage_path);
        //     $metaData = $imgck->getImageProperties();

        //     dd($metaData);
        // } catch (\ImagickException $e) {
        //     dd($e->getMessage());
        // }

        return response()->json(['meta' => $meta_data]);
    }

    private function getGps($exifCoord, $hemi)
    {
        // 도, 분, 초를 소수로 변환
        $degrees = count($exifCoord) > 0 ? $this->gps2Num($exifCoord[0]) : 0;
        $minutes = count($exifCoord) > 1 ? $this->gps2Num($exifCoord[1]) : 0;
        $seconds = count($exifCoord) > 2 ? $this->gps2Num($exifCoord[2]) : 0;
    
        // 위도 또는 경도가 남쪽이나 서쪽인 경우 부호를 음수로 설정
        $flip = ($hemi == 'S' || $hemi == 'W') ? -1 : 1;
    
        return $flip * ($degrees + ($minutes / 60) + ($seconds / 3600));
    }
}
