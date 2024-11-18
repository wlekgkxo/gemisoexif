<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="referrer" content="no-referrer-when-downgrade" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>이미지 메타 정보 보기</title>

    <script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=68bf2b98401d4ce55159f1328c7a1928"></script>
    <link rel="stylesheet" href="{{ asset('css/test.css') }}">
</head>
<body>
    <div class="test-form">
        <form method="POST" id="get_test_meta" onsubmit="return false;" enctype="multipart/form-data">
            <label for="test_meta">여기를 눌러 이미지, 동영상 파일만 선택해주세요.</label>
            <input type="file" id="test_meta" name="test_meta" accept="image/*, video/*, cr2" />
            <!-- <button type="submit">테스트 시작</button> -->
        </form>
    </div>
    <div class="wrap">
        <div class="container">
            <div class="box">
                <div class="drawer">
                    <div id="map" style="height:26em;"></div>
                    <div class="no-meta-coordinate">
                        <div>좌표 정보가 없어 지도를 표시할 수 없습니다.</div>
                    </div>
                </div>
                <div class="drawer">
                    <table class="compass-table">
                        <tbody>
                            <tr>
                                <th rowspan="2">주소</th>
                                <th>도로명 주소</th>
                                <td colspan="3" id="road_address"></td>
                            </tr>
                            <tr>
                                <th>지번 주소</th>
                                <td colspan="3" id="zip_address"></td>
                            </tr>
                            <tr>
                                <th rowspan="2">좌표</th>
                                <th>위도</th>
                                <td id="latitude"></td>
                                <th>경도</th>
                                <td id="longitude"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="box">
                <div class="meta-drawer">
                    <div id="image_meta_table">
                        <table class="meta-table">
                            <tbody>
                                <tr>
                                    <th colspan="4">파일명</th>
                                </tr>
                                <tr>
                                    <td colspan="4" id="original_file_name">
                                        파일을 선택해 주십시오.
                                    </td>
                                </tr>
                                <tr>
                                    <th>촬영일시</th>
                                    <td colspan="3" id="custom_datetime"></td>
                                </tr>
                                <tr>
                                    <th>파일크기</th>
                                    <td colspan="3" id="file_size"></td>
                                </tr>
                                <tr>
                                    <th>파일형식</th>
                                    <td id="file_type"></td>
                                    <th>MIME 유형</th>
                                    <td id="mime_type"></td>
                                </tr>
                                <tr>
                                    <th>이미지 너비 (픽셀)</th>
                                    <td id="image_width"></td>
                                    <th>이미지 높이 (픽셀)</th>
                                    <td id="image_length"></td>
                                </tr>
                                <tr>
                                    <th>가로 해상도 (dpi)</th>
                                    <td id="x_resolution"></td>
                                    <th>세로 해상도 (dpi)</th>
                                    <td id="y_resolution"></td>
                                </tr>
                                <tr>
                                    <th>카메라 제조사</th>
                                    <td id="camera_make"></td>
                                    <th>카메라 모델</th>
                                    <td id="camera_model"></td>
                                </tr>
                                <tr>
                                    <th>소프트웨어 정보</th>
                                    <td colspan="3" id="software"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="video_meta_table">
                        <table class="meta-table">
                            <tbody>
                                <tr>
                                    <th colspan="4">파일명</th>
                                </tr>
                                <tr>
                                    <td colspan="4" id="video_original_name"></td>
                                </tr>
                                <tr>
                                    <th>포멧명</th>
                                    <td id="video_format_name" style="text-align: left;"></td>
                                    <th>식별 신뢰도</th>
                                    <td id="video_probe_score"></td>
                                </tr>
                                <tr>
                                    <th>포멧상세</th>
                                    <td colspan="3" id="video_format_long_name" style="text-align: left;"></td>
                                </tr>
                                <tr>
                                    <th>총 길이</th>
                                    <td id="video_duration"></td>
                                    <th>파일크기</th>
                                    <td id="video_size"></td>
                                </tr>
                                <tr>
                                    <th>해상도폭</th>
                                    <td id="video_width"></td>
                                    <th>해상도높이</th>
                                    <td id="video_height"></td>
                                </tr>
                                <tr>
                                    <th>인코딩 해상도폭</th>
                                    <td id="video_coded_width"></td>
                                    <th>인코딩 해상도높이</th>
                                    <td id="video_coded_height"></td>
                                </tr>
                                <tr>
                                    <th>비트 전송률</th>
                                    <td id="video_bit_rate"></td>
                                    <th>스트림코덱명</th>
                                    <td id="video_codec_name"></td>
                                </tr>
                                <tr>
                                    <th>코덱상세</th>
                                    <td colspan="3" id="video_codec_long_name" style="text-align: left;"></td>
                                </tr>
                                <tr>
                                    <th>샘플 종횡비</th>
                                    <td id="video_sample_aspect_ratio"></td>
                                    <th>표시 종횡비</th>
                                    <td id="video_display_aspect_ratio"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="meta-drawer">
                    <pre id="full_meta"></pre>
                </div>
            </div>
        </div>
    </div>

    <div class="blind-area"></div>
    <div class="modal-container">
        <input type="hidden" id="full_mode_toggle" value="0" />
        <div class="modal-head">
            <button type="button" onclick="javascript:downPreview();">_</button>
            <button type="button" onclick="javascript:fullModePreview();">□</button>
            <button type="button" onclick="javascript:exitPreview()">X</button>
        </div>
        <div class="modal-body">
            <!-- <button type="button" class="video-play-btn"></button> -->
            <img id="preview" class="limit-size" />
        </div>
    </div>

    <button type="button" class="show-preview-btn" onclick="javascript:showPreview();">Preview</button>

    <script>
        var container = document.getElementById('map'); //지도를 담을 영역의 DOM 레퍼런스
        var options = { //지도를 생성할 때 필요한 기본 옵션
            center: new kakao.maps.LatLng(33.450701, 126.570667), //지도의 중심좌표.
            level: 3 //지도의 레벨(확대, 축소 정도)
        };
        var marker = new kakao.maps.Marker();

        var map = new kakao.maps.Map(container, options); //지도 생성 및 객체 리턴

        window.onload = function() {
            // document.getElementById("get_test_meta").addEventListener("submit", (e) => {
            document.getElementById("get_test_meta").addEventListener("change", (e) => {
                let test_form = document.getElementById("get_test_meta"),
                    file = event.target.files[0],
                    form_data = new FormData(test_form);

                const raw_pattern = /\.(cr2|cr3|arw)$/i;
                
                /* 기본 썸네일
                const reader = new FileReader();
                reader.onload = ({ target }) => {
                    document.getElementById("preview").src = target.result;
                };
                reader.readAsDataURL(file);

                showPreview(); */

                if(!(file.type.startsWith('image/') || file.type.startsWith('video/') || raw_pattern.test(file.name))) {
                    alert('이미지, 동영상 파일만 업로드할 수 있습니다.');
                    return false;
                }

                let req = new XMLHttpRequest();

                req.onreadystatechange = () => {
                    if(req.readyState === XMLHttpRequest.DONE) {
                        if(req.status === 200) {
                            let result = JSON.parse(req.response).original.meta;
                            let json_str = result.json_string ?? result;

                            document.getElementById("full_meta").innerText = JSON.stringify(json_str, undefined, 2);
                            document.getElementById("full_meta").style.display = 'block';

                            document.getElementById("preview").src = result.thumb_path;
                            showPreview();

                            if(result.type === 'image') {
                                setImageMeta(result);
                            } else if(result.type === 'video') {
                                setVideoMeta(result);
                            } else {
                                setRawImageMeta();
                            }

                            if(result.latitude && result.longitude) {
                                let add = result.address;

                                let road_add_name = add.road_address ? add.road_address.address_name : '',
                                    zip_add_name = add.address ? add.address.address_name : '';

                                document.querySelector(".no-meta-coordinate").style.display = "none";
                                document.querySelector(".compass-table").style.display = "block";
                                document.getElementById("road_address").innerText = road_add_name;
                                document.getElementById("zip_address").innerText = zip_add_name;
                                
                                document.getElementById("latitude").innerText = result.latitude.toFixed(6);
                                document.getElementById("longitude").innerText = result.longitude.toFixed(6);

                                panTo(result.latitude, result.longitude);
                            }
                        } else {
                            console.log('request error');
                        }
                    } 
                }

                req.open("POST", "file", true);
                // req.responseType = "json";
	            req.send(form_data);
            });
        }

        function panTo(lon, lat) {
            var moveLatLon = new kakao.maps.LatLng(lon, lat);
            map.panTo(moveLatLon);
            marker.setMap(null);
            marker = new kakao.maps.Marker({ 
                // 지도 중심좌표에 마커를 생성합니다 
                position: map.getCenter() 
            }); 
            marker.setMap(map);
        }

        function resetAddMeta() {
            document.getElementById("road_address").innerText = "";
            document.getElementById("zip_address").innerText = "";
            document.getElementById("latitude").innerText = "";
            document.getElementById("longitude").innerText = "";
        }

        function resetImageTables() {
            resetAddMeta();

            document.getElementById("original_file_name").innerText = "";
            document.getElementById("custom_datetime").innerText = "";
            document.getElementById("file_size").innerText = "";
            document.getElementById("file_type").innerText = "";
            document.getElementById("mime_type").innerText = "";
            document.getElementById("image_width").innerText = "";
            document.getElementById("image_length").innerText = "";
            document.getElementById("x_resolution").innerText = "";
            document.getElementById("y_resolution").innerText = "";
            document.getElementById("camera_make").innerText = "";
            document.getElementById("camera_model").innerText = "";
            document.getElementById("software").innerText = "";

            document.querySelector(".compass-table").style.display = "none";
            document.querySelector(".no-meta-coordinate").style.display = "block";
            document.getElementById('video_meta_table').style.display = "none";
            document.getElementById('image_meta_table').style.display = "block";
        }

        function resetVideoTables() {
            resetAddMeta();

            document.getElementById("video_original_name").innerText = "";
            document.getElementById("video_format_name").innerText = "";
            document.getElementById("video_probe_score").innerText = "";
            document.getElementById("video_format_long_name").innerText = "";
            document.getElementById("video_duration").innerText = "";
            document.getElementById("video_size").innerText = "";
            document.getElementById("video_width").innerText = "";
            document.getElementById("video_height").innerText = "";
            document.getElementById("video_coded_width").innerText = "";
            document.getElementById("video_coded_height").innerText = "";
            document.getElementById("video_bit_rate").innerText = "";
            document.getElementById("video_codec_name").innerText = "";
            document.getElementById("video_codec_long_name").innerText = "";
            document.getElementById("video_sample_aspect_ratio").innerText = "";
            document.getElementById("video_display_aspect_ratio").innerText = "";

            document.getElementById('video_meta_table').style.display = "block";
            document.getElementById('image_meta_table').style.display = "none";

            document.querySelector(".compass-table").style.display = "none";
            document.querySelector(".no-meta-coordinate").style.display = "block";
        }

        function downPreview() {
            document.querySelector(".show-preview-btn").style.display = "block";
            document.querySelector(".blind-area").style.display = "none";
            document.querySelector(".modal-container").style.display = "none";
        }

        function fullModePreview() {
            document.querySelector(".show-preview-btn").style.display = "none";
            document.querySelector(".blind-area").style.display = "block";

            if(parseInt(document.getElementById('full_mode_toggle').value) === 0) {
                document.querySelector(".modal-container").style.width = "99%";
                document.querySelector(".modal-container").style.height = "99%";
                document.getElementById("preview").style.maxHeight = "99%";
                document.getElementById('full_mode_toggle').value = 1;
            } else {
                document.querySelector(".modal-container").style.width = "auto";
                document.querySelector(".modal-container").style.height = "auto";
                document.getElementById("preview").style.maxHeight = "41em";
                document.getElementById('full_mode_toggle').value = 0;
            }
        }

        function exitPreview() {
            document.querySelector(".show-preview-btn").style.display = "none";
            document.querySelector(".blind-area").style.display = "none";
            document.querySelector(".modal-container").style.display = "none";
        }

        function showPreview() {
            document.querySelector(".show-preview-btn").style.display = "none";
            document.querySelector(".blind-area").style.display = "block";
            document.querySelector(".modal-container").style.display = "block";
        }
        
        function setImageMeta(data) {
            resetImageTables();

            document.getElementById("original_file_name").innerText = data.original;
            document.getElementById("custom_datetime").innerText = data.custom_datetime;
            document.getElementById("file_size").innerText = data.FileSize;
            document.getElementById("file_type").innerText = data.FileType;
            document.getElementById("mime_type").innerText = data.MimeType;
            document.getElementById("image_width").innerText = data.custom_width;
            document.getElementById("image_length").innerText = data.custom_height;
            document.getElementById("x_resolution").innerText = data.XResolution;
            document.getElementById("y_resolution").innerText = data.YResolution;
            document.getElementById("camera_make").innerText = data.Make;
            document.getElementById("camera_model").innerText = data.Model;
            document.getElementById("software").innerText = data.Software;

            document.getElementById('image_meta_table').style.display = "block";
            document.getElementById('video_meta_table').style.display = "none";
        }

        function setVideoMeta(data) {
            resetVideoTables();

            document.getElementById("video_original_name").innerText = data.original;
            document.getElementById("video_format_name").innerText = data.format_name;
            document.getElementById("video_probe_score").innerText = data.probe_score;
            document.getElementById("video_format_long_name").innerText = data.format_long_name;
            document.getElementById("video_duration").innerText = data.duration;
            document.getElementById("video_size").innerText = data.size;
            document.getElementById("video_width").innerText = data.width;
            document.getElementById("video_height").innerText = data.height;
            document.getElementById("video_coded_width").innerText = data.coded_width;
            document.getElementById("video_coded_height").innerText = data.coded_height;
            document.getElementById("video_bit_rate").innerText = data.bit_rate;
            document.getElementById("video_codec_name").innerText = data.codec_name;
            document.getElementById("video_codec_long_name").innerText = data.codec_long_name;
            document.getElementById("video_sample_aspect_ratio").innerText = data.sample_aspect_ratio;
            document.getElementById("video_display_aspect_ratio").innerText = data.display_aspect_ratio;

            document.getElementById('video_meta_table').style.display = "block";
            document.getElementById('image_meta_table').style.display = "none";
        }

        function setRawImageMeta(data) {
            resetImageTables();

            document.getElementById("original_file_name").innerText = data.original;
            document.getElementById("custom_datetime").innerText = data.custom_datetime;
            document.getElementById("file_size").innerText = data.FileSize;
            document.getElementById("file_type").innerText = data.FileType;
            document.getElementById("mime_type").innerText = data.MimeType;
            document.getElementById("image_width").innerText = data.custom_width;
            document.getElementById("image_length").innerText = data.custom_height;
            document.getElementById("x_resolution").innerText = data.XResolution;
            document.getElementById("y_resolution").innerText = data.YResolution;
            document.getElementById("camera_make").innerText = data.Make;
            document.getElementById("camera_model").innerText = data.Model;
            document.getElementById("software").innerText = data.Software;

            document.getElementById('image_meta_table').style.display = "block";
            document.getElementById('video_meta_table').style.display = "none";
        }
    </script>
</body>
</html>