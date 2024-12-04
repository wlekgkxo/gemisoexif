<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/mediameta.css') }}">
    <title>이미지 정적, 동적 메타</title>
</head>
<body>
    <div class="warp">
        <div class="header-container"></div>
        <div class="body-container">
            <form method="POST" name="file_upload" id="file_upload" enctype="multipart/form-data">
                @csrf
                <input type="file" id="upload_media" name="upload_media[]" multiple style="display: none;" />
            </form>
            <!-- <form method="POST" id="ingest_request_call" onsubmit="return false;"> -->
            <div class="title-box"></div>
            <div class="ingest-box">
                <h3>인제스트 타입 선택</h3>
                <table>
                    <tbody>
                        <tr>
                            <th>인제스트 타입</th>
                            <td>
                                <select disabled>
                                    <option>사진</option>
                                    <option>동영상</option>
                                </select>
                            </td>
                            <th>메뉴</th>
                            <td>
                                <select disabled>
                                    <option>사진/이미지</option>
                                    <option>IC</option>
                                    <option>PSD</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>프로그램명</th>
                            <td>
                                <input type="text"  disabled />
                                <button type="button" disabled>프로그램 정보</button>
                                <button type="button" disabled>프로그램 변경</button>
                            </td>
                            <th>프로그램 ID</th>
                            <td>
                                <input type="text"  disabled />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <p class="notice-text">추가 메타 정보 V</p>
            <div class="client-box">
                <h3>요청사항 작성</h3>
                <table class="client-table">
                    <tbody>
                        <tr>
                            <th>요청명</th>
                            <td>
                                <input type="text" />
                            </td>
                        </tr>
                        <tr>
                            <th>요청내역</th>
                            <td>
                                <input type="text" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="upload-box">
                <h3>사진 업로드 & 메타데이터 입력 <button>공통 메타데이터 입력</button></h3>
                <label for="upload_media" id="drag_media">
                    <p>업로드 할 사진을 선택하세요.</p>
                    <p>30MB 이하의 jpg 파일을 최대 50장까지 등록할 수 있습니다.</p>
                    <br />
                    <p>파일 업로드</p>
                </label>
            </div>
            <div class="meta-box">
                <div class="btn-box">
                    <button type="submit">인제스트 승인 요청</button>
                </div>
                <table class="meta-table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="checked_all"></th>
                            <th>사진</th>
                            <th>메타데이터</th>
                        </tr>
                    </thead>
                    <tbody id="prepend_row"></tbody>
                </table>
            </div>
            <!-- </form> -->
        </div>
    </div>
    <!-- <div id="bland_box" class="bland-box"></div>
    <div id="loading_circle" class="loading-circle"><img src="/assets/load-35_128.gif" alt="now loading..."></div> -->
    <button type="button" class="top-btn">top</button>
    <script src="{{ asset('js/mediameta-new.js') }}"></script>
    <script src="{{ asset('js/mediameta-modal.js') }}"></script>
</body>
</html>