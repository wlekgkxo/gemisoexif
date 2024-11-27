<?php

namespace App\Services;

/**
 * use PHPExif\Exif;
 * use Intervention\Image\Facades\Image;
 * 
 * 간략한 설치 가이드
 * 1. https://www.ffmpeg.org/download.html#build-windows 여기서 Windows Builds from gyan.dev 들어간다.
 * 2. release 칸에 있는 ffmpeg-release-full-shared.7z 다운
 * 3. 위 파일 압축 풀고 bin 파일 환경변수에 추가
 * 4. composer require php-ffmpeg/php-ffmpeg
 * 끝
 * 
 * 1. composer require miljar/php-exif
 * 2. use PHPExif\Reader\Reader
 * 끝
 * 
 * 1. https://exiftool.org/ 에서 Windows 64-bit 다운
 * 2. 원하는 곳에 zip 풀기
 * 3. exiftool(-k).exe 에서 (-k) 삭제하기.
 * 4. exiftool.exe가 있는 폴더 환경변수에 등록하기
 * 5. exiftool -GPSLatitudeRef=N -GPSLatitude=37.550987 -GPSLongitudeRef=E -GPSLongitude=126.990905 "파일 경로"
 * 6. 위에 저건 동영상에 좌표 넣는 CMD 명령어임.
 * 끝
 * 
 * 1. composer require guzzlehttp/guzzle
 * 2. use GuzzleHttp\Client;
 * 끝
 * 
 * 1. https://imagemagick.org/script/download.php#windows 여기서 환경에 맞는 것으로 다운로드
 * 2. https://mlocati.github.io/articles/php-windows-imagick.html php 확장 파일임. Threadsafe, NonThreadsafe 잘 구분해서 다운로드
 * 3. php_imagick.dll 는 ext 폴더 안으로 넣고 나머지 .dll은 php.exe 실행 파일이 있는 경로에 다 때려넣자
 * 4. php.ini 파일에서 extension=imagick 넣고 php-cgi 재시작
 * **/

use DateTime;
use Exception;
use GuzzleHttp\Client;

use FFMpeg\Exception\RuntimeException;

use App\Models\AcStaticMeta;

class MediaMetaService
{
    public function getVideoMetaData($video) {
        try {
            $vdo_data = [];
            $vdo_data['media_id'] = $video->media_id;
            $vdo_data['format_name'] = $video->format->get('format_name');
            $vdo_data['format_long_name'] = $video->format->get('format_long_name');
            // $vdo_data['start_time'] = $video->format->get('start_time');
            $vdo_data['duration'] = $video->format->get('duration');
            $vdo_data['file_size'] = $video->format->get('size');

            preg_match('/\.([a-zA-Z0-9]+)$/', $video->original_name, $extension);
            $vdo_data['extension_nm'] = $extension[1];

            $vdo_data['dpi_x'] = $video->streams->get('width');
            $vdo_data['dpi_y'] = $video->streams->get('height');
            $vdo_data['coded_dpi_x'] = $video->streams->get('coded_width');
            $vdo_data['coded_dpi_y'] = $video->streams->get('coded_height');
            $vdo_data['bit_rate'] = $video->streams->get('bit_rate');
            $vdo_data['codec_name'] = $video->streams->get('codec_name');
            $vdo_data['codec_long_name'] = $video->streams->get('codec_long_name');
            $vdo_data['nb_streams'] = $video->format->get('nb_streams');
            $vdo_data['sample_aspect_ratio'] = $video->streams->get('sample_aspect_ratio');
            $vdo_data['display_aspect_ratio'] = $video->streams->get('display_aspect_ratio');
            $vdo_data['probe_score'] = $video->format->get('probe_score');

            $tag = $video->format->get('tag');
            if($tag) {
                if(array_key_exists('creation_time', $tag)) {
                    $set_date = DateTime::createFromFormat('Y:m:d H:i:sP', $tag['creation_time']);
                    $vdo_data['shooting_date'] = $set_date->format('Y-m-d H:i:s');
                }
            }

            // $duration = $video_format->get('duration'); // 동영상 길이 (초 단위)
            // $bitrate = $video_format->get('bit_rate'); // 비트레이트
            // $width = $video_streams->videos()->first()->get('width'); // 가로 해상도
            // $height = $video_streams->videos()->first()->get('height'); // 세로 해상도

            $command = "exiftool -gpslatitude -gpslongitude ".storage_path()."\\app\\public\\".str_replace('/', '\\', $video->path);
            $output = shell_exec($command);

            // dd(sapi_windows_cp_conv(sapi_windows_cp_get('oem'), 65001, $output));

            // 위치 정보가 있는 경우 정규식으로 위도와 경도 추출
            preg_match('/GPS Latitude\s+:\s+([^\n]+)\nGPS Longitude\s+:\s+([^\n]+)/', $output, $matches);

            // 위치 정보가 있을 때만 주소 찾기
            if(isset($matches[1]) && isset($matches[2])) {
                $vdo_data['location_x'] = $this->convertDMSToDecimal($matches[1]);
                $vdo_data['location_y'] = $this->convertDMSToDecimal($matches[2]);
                $vdo_data['address1'] = $this->getKrLocation($vdo_data['location_x'], $vdo_data['location_y'], 'json');
            }

            // $vdo_data['static_meta_id'] = AcStaticMeta::setRecord($vdo_data);

            // return response()->json(['meta' => $vdo_data]);
            return $vdo_data;
        } catch (RuntimeException $e) {
            return response()->json(['error_message' => $e->getMessage()]);
        }
    }

    public function getImageMeta($image)
    {
        try {
            $meta_data = $this->setMeta($image, 'image');

            return $meta_data;
            // return response()->json(['meta' => $meta_data], JSON_PRETTY_PRINT);
        } catch(Exception $e) {
            dd($e->getMessage());
            return response()->json(['error_message' => $e->getMessage()]);
        }
    }

    public function getRawMeta($raw)
    {
        try {
            $meta_data = $this->setMeta($raw, 'raw');

            return $meta_data;
            // return response()->json(['meta' => $meta_data], JSON_PRETTY_PRINT);
        } catch(Exception $e) {
            dd($e->getMessage());
            return response()->json(['error_message' => $e->getMessage()]);
        }
    }
    
    public function setMeta($media, $type)
    {
        // $public_path = public_path();
        // $public_path = preg_replace('/\\\\/', '/', $public_path);
        // $imagick = new \Imagick($public_path.$media->storage_path);
        
        // $resolution = $imagick->getImageResolution();
        // $xResolution = $resolution['x'];
        // $yResolution = $resolution['y'];

        // dd($xResolution);

        // exec("exiftool -XResolution -YResolution " . escapeshellarg(storage_path()
        // .DIRECTORY_SEPARATOR."app"
        // .DIRECTORY_SEPARATOR."public"
        // .DIRECTORY_SEPARATOR.str_replace('/', '\\', $media->path)), $output);

        // dd($output);

        // $img_data = Image::make('.'.$media->storage_path)->exif();
        // dd($img_data);

        // $meta_data_php = exif_read_data('.'.$media->storage_path);

        // dd($meta_data_php);

        $command = "exiftool -j " . escapeshellarg(storage_path()
        .DIRECTORY_SEPARATOR."app"
        .DIRECTORY_SEPARATOR."public"
        .DIRECTORY_SEPARATOR.str_replace('/', '\\', $media->path));
        $output = shell_exec($command);
        $metadata_j = json_decode($output, true);
        
        $meta_data = $metadata_j[0];

        $set_data = [];
        $set_data['media_id'] = $media->media_id;
        
        if(array_key_exists('DateTimeOriginal', $meta_data)) {
            $set_date = DateTime::createFromFormat('Y:m:d H:i:s', $meta_data['DateTimeOriginal']);
            $set_data['shooting_date'] = $set_date->format('Y-m-d H:i:s');
        } elseif(array_key_exists('FileCreateDate', $meta_data)) {
            $set_date = DateTime::createFromFormat('Y:m:d H:i:sP', $meta_data['FileCreateDate']);
            $meta_data['shooting_date'] = $set_date->format('Y-m-d H:i:s');
        }

        $set_data['media_width'] = $meta_data['ImageWidth'] ?? $meta_data['ExifImageWidth'];
        $set_data['media_height'] = $meta_data['ImageHeight'] ?? $meta_data['ExifImageHeight'];

        $set_data['file_size'] = $meta_data['FileSize'];
        $set_data['extension_nm'] = $meta_data['FileTypeExtension'];
        $set_data['mime'] = $meta_data['MIMEType'];

        if(array_key_exists('GPSLatitude', $meta_data) && array_key_exists('GPSLongitude', $meta_data)) {
            $set_data['location_x'] = $this->getGps($meta_data["GPSLatitude"], $meta_data['GPSLatitudeRef']);
            $set_data['location_y'] = $this->getGps($meta_data["GPSLongitude"], $meta_data['GPSLongitudeRef']);
            $set_data['address1'] = $this->getKrLocation($set_data['location_x'], $set_data['location_y'], 'json');
        }

        if(array_key_exists('Make', $meta_data) && array_key_exists('Model', $meta_data)) 
            $set_data['maker'] = $meta_data['Make'].'/'.$meta_data['Model'];

        if(array_key_exists('Software', $meta_data)) $set_data['tools'] = $meta_data['Software'];
        if(array_key_exists('Artist', $meta_data)) $set_data['artist'] = $meta_data['Artist'];
        if(array_key_exists('Copyright', $meta_data)) $set_data['copyright'] = $meta_data['Copyright'];

        if(array_key_exists('XResolution', $meta_data)) $set_data['dpi_x'] = $meta_data['XResolution'];
        if(array_key_exists('YResolution', $meta_data)) $set_data['dpi_y'] = $meta_data['YResolution'];

        // $set_data['static_meta_id'] = AcStaticMeta::setRecord($set_data);

        return $set_data;
    }

    public function getLocation($lat, $lon, $format)
    {
        $client = new Client();
        $url = 'https://us1.locationiq.com/v1/reverse.php';

        try {
            $response = $client->get($url, [
                'query' => [
                    'key' => env('MIX_MAP_API_KEY'),
                    'lat' => $lat,
                    'lon' => $lon,
                    'format' => $format
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            return $data;

        } catch(Exception $e) {
            return response()->json(['error_message' => $e->getMessage()]);
        }
    }

    public function getKrLocation($x, $y, $format)
    {
        $client = new Client();
        $url = 'https://dapi.kakao.com/v2/local/geo/coord2address.'.$format;

        try {
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'KakaoAK '.env('MIX_KAKAO_DEV_KEY')
                ],
                'query' => [
                    'x' => $x,
                    'y' => $y,
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            $result = $data['documents'][0];

            return $result;
        } catch(Exception $e) {
            return response()->json(['error_message' => $e->getMessage()]);
        }
    }

    // 받은 GPS 정보 위도, 경도 소숫점으로 변경
    function getGps($dms, $direction) {
        // DMS 문자열에서 도, 분, 초를 추출
        preg_match('/(\d+) deg (\d+)\' ([\d.]+)"/', $dms, $matches);
    
        if (count($matches) !== 4) {
            throw new Exception("Invalid DMS format");
        }
    
        // 도, 분, 초를 숫자로 변환
        $degrees = (float)$matches[1];
        $minutes = (float)$matches[2];
        $seconds = (float)$matches[3];
    
        // 십진수로 변환
        $decimal = $degrees + ($minutes / 60) + ($seconds / 3600);
    
        // 방향에 따라 음수 처리
        if ($direction === 'W' || $direction === 'S') {
            $decimal *= -1;
        }
    
        return $decimal;
    }
    
    private function gps2Num($coordPart)
    {
        $parts = explode('/', $coordPart);
        if (count($parts) <= 0) return 0;
        if (count($parts) == 1) return $parts[0];
        return floatval($parts[0]) / floatval($parts[1]);
    }

    function convertDMSToDecimal($dms)
    {
        // DMS 문자열에서 도, 분, 초 및 방향 추출
        preg_match('/(\d+) deg (\d+)\' (\d+\.\d+)\" ([NSEW])/', $dms, $matches);

        if (!$matches) {
            throw new Exception("유효하지 않은 DMS 형식입니다.");
        }

        // DMS 요소 분리
        $degrees = (float)$matches[1];
        $minutes = (float)$matches[2];
        $seconds = (float)$matches[3];
        $direction = $matches[4];

        // DMS를 십진수로 변환
        $decimal = $degrees + ($minutes / 60) + ($seconds / 3600);

        // 남반구 또는 서쪽인 경우 부호를 음수로 변환
        if ($direction == 'S' || $direction == 'W') {
            $decimal *= -1;
        }

        return $decimal;
    }
}