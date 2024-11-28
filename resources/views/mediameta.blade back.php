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
                <div class="progress-box">
                    <progress id="progress_bar" value="0" max="100"></progress>
                </div>
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
    <button type="button" class="top-btn">top</button>
    <script>
        // document.getElementById('checked_all').addEventListener('change', (e) => {});

        // document.getElementById('ingest_request_call').addEventListener('submit', (e) => {
        //     // 인제스트 요청 버튼 클릭 시, 이미지 정보 Json 파일로 생성 하는 api call
        //     let datas = localStorage.getItem('ingest_media_assets'),
        //         form_data = new FormData();

        //     form_data.append('datas', datas);

        //     console.log(datas);
            
            // media upload 및 정보 가져오기
            // let req = new XMLHttpRequest();

            // req.onreadystatechange = () => {
            //     if(req.readyState === XMLHttpRequest.DONE) {
            //         if(req.status === 200) {
            //             let result = JSON.parse(req.response);

            //             console.log(result);
            //         } else {
            //             console.log('request error');
            //         }
            //     } 
            // }

            // req.open('POST', 'requestingest', true);
            // // req.responseType = "json";
            // req.send(form_data);
        // });

        // 클릭 -> 업로드
        document.getElementById('upload_media').addEventListener('change', (e) => {
            let files = event.target.files,
            form_data = new FormData();

            if(files.length > 50) alert('파일은 최대 50개만 가능합니다.');

            // console.log(document.getElementById('prepend_row').children().length);

            for(let i = 0; i < files.length; i++) {
                form_data.append('files[]', files[i]);
            }

            callUploadMedia(form_data);
        });

        // document.getElementById('drag_media').addEventListener('dragenter', (e) => {});
        document.getElementById('drag_media').addEventListener('dragover', (e) => {
            e.preventDefault();

            this.style.backgroundColor = '#c1c1c1';
        });
        
        document.getElementById('drag_media').addEventListener('dragleave', (e) => {
            console.log('dragleave');

            this.style.backgroundColor = 'white';
        });

        // 드래그&드롭 -> 업로드
        document.getElementById('drag_media').addEventListener('drop', (e) => {
            e.preventDefault();

            // console.log('drop');
            // this.style.backgroundColor = 'white';

            // console.dir(e.dataTransfer);

            // var data = e.dataTransfer.files;
            // console.dir(data);

            let files = e.dataTransfer.files,
            form_data = new FormData();

            if(files.length > 50) alert('파일은 최대 50개만 가능합니다.');

            for(let i = 0; i < files.length; i++) {
                form_data.append('files[]', files[i]);
            }

            callUploadMedia(form_data)

            this.style.backgroundColor = 'white';
        });

        // document ready
        document.addEventListener('DOMContentLoaded', (e) => {
            let progress_request = localStorage.getItem('ingest_media_assets');

            if(progress_request) {
                if(progress_request !== '') {
                    if(!confirm('진행하시던 인제스트 요청 작업이 있습니다. 이어서 하시겠습니까?')) {
                        removeProgressIngest(progress_request);
                    } else {
                        stillProgressIngest(progress_request);
                    }
                }
            } else {
                localStorage.setItem('ingest_media_assets', '');
            }
        });

        function callUploadMedia(form_data) {
            // media upload 및 정보 가져오기
            let req = new XMLHttpRequest();

            req.upload.addEventListener('progress', progressHandler, false);
            req.upload.addEventListener("progress", progressHandler, false);
            req.addEventListener("load", completeHandler, false);
            req.addEventListener("error", errorHandler, false);
            req.addEventListener("abort", abortHandler, false);

            req.onreadystatechange = () => {
                if(req.readyState === XMLHttpRequest.DONE) {
                    if(req.status === 200) {
                        let result = JSON.parse(req.response);

                        if(!localStorage.getItem('ingest_media_assets')) {
                            localStorage.setItem('ingest_media_assets', JSON.stringify(result));
                            pushRecord(result);
                        } else {
                            let storage_assets = localStorage.getItem('ingest_media_assets'),
                                assets = JSON.parse(storage_assets);
                                
                            localStorage.setItem('ingest_media_assets', JSON.stringify([...result, ...assets]));
                            pushRecord(result);
                        }
                    } else {
                        console.log('request error');
                    }
                } 
            }

            req.open('POST', 'mediaupload', true);
            // req.responseType = "json";
            req.send(form_data);
        }

        /* progress bar 관련 */
        function progressHandler(event) {
            let percent = (event.loaded / event.total) * 100;
            document.getElementById('progress_bar').value = Math.round(percent);
        }
        function completeHandler(event) {
            document.getElementById('progress_bar').value = 0;
        }
        function errorHandler(event) {}
        function abortHandler(event) {}
        /* progress bar 관련 */

        /* DOMContentLoaded 에서 사용되는 함수들*/
        function stillProgressIngest() {
            // 이어서 정보 입력하기
            let progress_request = localStorage.getItem('ingest_media_assets'),
                datas = JSON.parse(progress_request);

                pushRecord(datas);
        }

        function removeProgressIngest(datas) {
            // 이어서 정보 입력 안함
            let form_data = new FormData();

            form_data.append('datas', datas);

            let req = new XMLHttpRequest();

            req.onreadystatechange = () => {
                if(req.readyState === XMLHttpRequest.DONE) {
                    if(req.status === 200) {
                        let result = JSON.parse(req.response);

                        localStorage.removeItem('ingest_media_assets');
                    } else {
                        console.log('request error');
                    }
                }
            }

            req.open('POST', 'removemedia', true);
            // req.responseType = "json";
            req.send(form_data);
        }
        /* callUploadMedia 에서 사용되는 함수들*/

        /* DOM 생성 관련 함수 callUploadMedia 에서 사용 */
        function pushRecord(datas) {
            let rows = '';
            for(let i = 0; i < datas.length; i++) {
                rows += createRecord(datas[i]);
            }

            document.getElementById('prepend_row').insertAdjacentHTML('afterbegin', rows);
            
            let tags = document.querySelectorAll('.insert-tag-input');

            tags.forEach(tag => {
                tag.addEventListener('keydown', (e) => {
                    if (event.key === 'Enter') {
                        let media_id = parseInt(event.target.parentElement.dataset.media);
                        let tag_box = event.target.parentElement.querySelector('.tag-insert-box');

                        if(addDynamicArrayData(media_id, 'tags', event.target.value)) {
                            document.getElementById('media_tags_'+media_id).insertAdjacentHTML('afterbegin', '<option value="'+event.target.value+'" selected>'+event.target.value+'</option>');
                            tag_box.insertAdjacentHTML('afterbegin', '<div class="tag-blocks">'+event.target.value+'<button type="button" onclick="javascript:removeTag('+media_id+', '+"'"+event.target.value+"'"+');">X</button></div>');
                        }

                        event.target.value = '';
                    }
                });
            });
        }

        function removeTag(media_id, rmtag) {
            let tags = document.getElementById('media_tags_'+media_id).children;

            if(removeDynamicArrayData(media_id, 'tags', rmtag)) {
                for(let i = 0; i < tags.length; i++) {
                    if(tags[i].value === rmtag) tags[i].remove();
                    event.target.parentElement.remove();
                }
            }
        }

        function addDynamicArrayData(media_id, name, value) {
            // Array 형식 동적 데이터 추가
            let progress_request = localStorage.getItem('ingest_media_assets'),
                datas = JSON.parse(progress_request),
                idx = datas.findIndex(element => element.media.media_id === media_id),
                data = datas.find(element => element.media.media_id === media_id);

            if(!('dynamic' in data)) data.dynamic = {};
            if(!(name in data.dynamic)) data.dynamic[name] = [];

            data.dynamic[name].push(value);

            datas[idx] = data;
            localStorage.setItem('ingest_media_assets', JSON.stringify(datas));

            return true;
        }

        function removeDynamicArrayData(media_id, name, value) {
            // Array 형식 동적 데이터 삭제
            let progress_request = localStorage.getItem('ingest_media_assets'),
                datas = JSON.parse(progress_request),
                idx = datas.findIndex(element => element.media.media_id === media_id),
                data = datas.find(element => element.media.media_id === media_id);

            if(!('dynamic' in data)) return false;
            if(!(name in data.dynamic)) return false;
            
            data.dynamic[name] = data.dynamic[name].filter(element => element !== value);

            datas[idx] = data;
            localStorage.setItem('ingest_media_assets', JSON.stringify(datas));

            return true;
        }

        function createRecord(data) {
            let media = data.media,
                media_id = media.media_id;

            let record = '<tr>'
            + '<td><input type="checkbox" name="check_media_id[]" value="'+media_id+'" /></td>'
            + '<td>'
            + '<img src="'+media.thumbnail+'" />'
            + '</td>'
            + '<td>'
            + '<p>[필수 입력항목]</p>'
            + '<ul>'
            + '<li>'
            + '<span>파일명</span>'
            + '<span><input type="text" name="file_name_'+media_id+'" value="'+media.original_name+'" /></span>'
            + '</li>'
            + '<li>'
            + '<span>사진분류</span>'
            + '<span>'
            + '<select name="media_class_'+media_id+'">'
            + '<option>선택 ▼</option>'
            + '<option value="0">미분류</option>'
            + '<option value="1">인물사진</option>'
            + '<option value="2">풍경사진</option>'
            + '<option value="3">무대사진</option>'
            + '</select>'
            + '</span>'
            + '</li>'
            + '<li>'
            + '<span>카테고리</span>'
            + '<span>'
            + '<label><input type="checkbox" name="media_categories_'+media_id+'[]" value="인물" />인물</label>'
            + '<label><input type="checkbox" name="media_categories_'+media_id+'[]" value="동물" />동물</label>'
            + '<label><input type="checkbox" name="media_categories_'+media_id+'[]" value="식음료" />식음료</label>'
            + '<label><input type="checkbox" name="media_categories_'+media_id+'[]" value="건물/랜드마크" />건물/랜드마크</label>'
            + '<label><input type="checkbox" name="media_categories_'+media_id+'[]" value="뷰티/패션" />뷰티/패션</label>'
            + '<br/>'
            + '<label><input type="checkbox" name="media_categories_'+media_id+'[]" value="공연/콘서트" />공연</label>'
            + '<label><input type="checkbox" name="media_categories_'+media_id+'[]" value="운동/스포츠" />운동</label>'
            + '<label><input type="checkbox" name="media_categories_'+media_id+'[]" value="풍경/자연" />풍경</label>'
            + '<label><input type="checkbox" name="media_categories_'+media_id+'[]" value="문화/예술" />문화</label>'
            + '<label><input type="checkbox" name="media_categories_'+media_id+'[]" value="도심" />도심</label>'
            + '<br/>'
            + '<label><input type="checkbox" name="media_categories_'+media_id+'[]" value="비즈니스" />비즈니스</label>'
            + '<label><input type="checkbox" name="media_categories_'+media_id+'[]" value="일러스트/클립아트" />일러스트/클립아트</label>'
            + '<label><input type="checkbox" name="media_categories_'+media_id+'[]" value="기타" />기타</label>'
            + '</span></li>'
            + '<li>'
            + '<span>태그'
            + '<select name="media_tags_'+media_id+'[]" id="media_tags_'+media_id+'" multiple style="display:none;">';
            
            if('dynamic' in data) {
                if('tags' in data.dynamic) {
                    data.dynamic.tags.forEach(tag => {
                        record += '<option value="'+tag+'" selected>'+tag+'</option>';
                    });
                }
            }

            record += '</select>'
            + '</span>'
            + '<span class="sh-world">#</span>'
            + '<span class="tag-insert-box" data-media="'+media_id+'">'
            + '<div class="tag-blocks">';

            if('dynamic' in data) {
                if('tags' in data.dynamic) {
                    data.dynamic.tags.forEach(tag => {
                        record += '<span>'
                        + tag
                        +'<button type="button" onclick="javascript:removeTag('+media_id+', '+"'"+tag+"'"+');">X</button></span>'
                    });
                }
            }

            record += '<span><input type="text" class="insert-tag-input" /></span></div>'
            + '</span></li>'
            + '<li>'
            + '<span>사진설명</span>'
            + '<span class="textarea-box">'
            + '<textarea name="media_description_'+media_id+'" placeholder="ex) 2022 MAMA에서 수상소감을 이야기하는 블랙핑크"></textarea>'
            + '</span></li>'
            + '</ul>'
            + '</td>'
            + '</tr>';

            return record;
        }


        /* DOM 생성 관련 함수 callUploadMedia 에서 사용 */
    </script>
</body>
</html>