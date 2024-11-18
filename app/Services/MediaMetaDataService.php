<?php

namespace App\Services;

/**
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

use Exception;
use Intervention\Image\Facades\Image;
use GuzzleHttp\Client;

use FFMpeg\FFMpeg;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Exception\RuntimeException;

use PHPExif\Reader\Reader;

class MediaMetaDataService
{
    public function getVideoMetaData($video) {
        $vdo_data = [];
        $thumb_sec = 5;

        try {
            $ffmpeg = FFMpeg::create([
                'ffmpeg.binaries' => 'C:/Servers/ffmpeg-7.1-full_build-shared/bin/ffmpeg.exe', // 설치된 ffmpeg 경로
                'ffprobe.binaries' => 'C:/Servers/ffmpeg-7.1-full_build-shared/bin/ffprobe.exe', // 설치된 ffprobe 경로
                'timeout' => 3600, // 초 단위 시간 제한
                'ffmpeg.threads' => 12, // 쓰레드 개수
            ]);

            $ffmpeg_open = $ffmpeg->open($video->storage_path);
            
            $thumb_name = basename(preg_replace('/\.[^.]+$/', '', $video->path)).".jpg";
            $ffmpeg_open->frame(TimeCode::fromSeconds($thumb_sec))
            ->save(storage_path()
            .DIRECTORY_SEPARATOR."app"
            .DIRECTORY_SEPARATOR."public"
            .DIRECTORY_SEPARATOR."upload"
            .DIRECTORY_SEPARATOR."thumbs"
            .DIRECTORY_SEPARATOR.$thumb_name);

            $video_format = $ffmpeg_open->getFormat();
            $video_streams = $ffmpeg_open->getStreams()->videos()->first();

            $vdo_data['original'] = $video->original_name;
            $vdo_data['format_name'] = $video_format->get('format_name');
            $vdo_data['format_long_name'] = $video_format->get('format_long_name');
            // $vdo_data['start_time'] = $video_format->get('start_time');
            $vdo_data['duration'] = $video_format->get('duration');
            $vdo_data['size'] = $video_format->get('size');
            $vdo_data['width'] = $video_streams->get('width');
            $vdo_data['height'] = $video_streams->get('height');
            $vdo_data['coded_width'] = $video_streams->get('coded_width');
            $vdo_data['coded_height'] = $video_streams->get('coded_height');
            $vdo_data['bit_rate'] = $video_streams->get('bit_rate');
            $vdo_data['codec_name'] = $video_streams->get('codec_name');
            $vdo_data['codec_long_name'] = $video_streams->get('codec_long_name');
            $vdo_data['nb_streams'] = $video_format->get('nb_streams');
            $vdo_data['format_tag'] = $video_format->get('tags');
            $vdo_data['stream_tag'] = $video_streams->get('tags');
            $vdo_data['sample_aspect_ratio'] = $video_streams->get('sample_aspect_ratio');
            $vdo_data['display_aspect_ratio'] = $video_streams->get('display_aspect_ratio');
            $vdo_data['probe_score'] = $video_format->get('probe_score');
            $vdo_data['thumb_path'] = "/storage/upload/thumbs/".$thumb_name;

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
                $vdo_data['latitude'] = $this->convertDMSToDecimal($matches[1]);
                $vdo_data['longitude'] = $this->convertDMSToDecimal($matches[2]);
                $vdo_data['address'] = $this->getKrLocation($vdo_data['longitude'], $vdo_data['latitude'], 'json');
            }
            
            $vdo_data['json_string'] = ['video_format' => $video_format->all(), 'video_streams' => $video_streams->all()];
            
            $vdo_data['type'] = 'video';

            return response()->json(['meta' => $vdo_data]);
        } catch (RuntimeException $e) {
            return response()->json(['error_message' => $e->getMessage()]);
        }
    }

    public function getImageMetaData($image)
    {
        $thumb_name = basename(preg_replace('/\.[^.]+$/', '', $image->path)).".jpg";

        $thumb = Image::make($image->storage_path)->resize(500, 500, function ($constraint) {
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

    public function getRawImage1Meta($raw_image)
    {
        // $imagick = new Imagick($raw_image->storage_path);
        

        // return response()->json(['meta' => $metadata]);
    }
    
    public function getRawImage2Meta($raw_image)
    {
        try {
            $command = "exiftool -j " . escapeshellarg(storage_path()
            .DIRECTORY_SEPARATOR."app"
            .DIRECTORY_SEPARATOR."public"
            .DIRECTORY_SEPARATOR.str_replace('/', '\\', $raw_image->path));
            $output = shell_exec($command);
            $metadata_j = json_decode($output, true);
            
            $metadata = $metadata_j[0];

            $metadata['original'] = $raw_image->original_name;
            $metadata['type'] = 'raw';
    
            //dd($command);
            dd($metadata);
        } catch(Exception $e) {
            dd($e->getMessage());
            return response()->json(['error_message' => $e->getMessage()]);
        }
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