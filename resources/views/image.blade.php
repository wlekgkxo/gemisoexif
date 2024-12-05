<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/image.css') }}">
    <title>이미지 업로드</title>
</head>
<body>
    <div id="representative_image_box"></div>
    <div id="additional_upload_box"></div>
    <div class="warp">
        <div class="content-box">
            <div class="content-title">
                <h3>콘텐츠 등록 총 ?개</h3>
                <button type="button" onclick="javascript:addRecord();">+ 추가</button>
            </div>
            <form id="content_form" onsubmit="return false;">
                @csrf
            </form>
        </div>
    </div>
    <script>
        function addRecord(id = 0, data = {}) {
            // 레코드 추가
            const idx = id === 0 ? new Date().getTime() : id,
                content_selector = [{nm: '아이콘', val: 'icon'},
                                    {nm: '로고', val: 'logo'},
                                    {nm: '썸네일', val: 'thumb'},
                                    {nm: '편집파일', val: 'editor'}];

            let thumb = '';

            if('representative' in data) thumb = ('thumbnail' in data.representative) ? data.representative.thumbnail : '';

            let record = '<table data-tidx="'+idx+'"><tbody><tr>'
                    + '<th>자료구분</th><td><input type="text" name="file_category_'+idx+'" /></td>'
                    + '<th>콘텐츠 구분</th>'
                    + '<td><select name="category_selected_'+idx+'" id="category_selected_'+idx+'">'
                    + '<option value="">선택 ▼</option>';

                for(let i = 0; i < content_selector.length; i++) {
                    record += '<option value="'+content_selector[i].val+'">'+content_selector[i].nm+'</option>';
                }

                record += '</select></td>'
                    + '<td rowspan="3" id="remove_row_'+idx+'" class="remove-row"><span class="trash-icons"></span></td>'
                    + '</tr><tr><th>썸네일</th>'
                    + '<td colspan="3"><div class="representative-place">'
                    + '<div>'
                    + '<div class="representative-upload-thumb"></div>'
                    + '<progress id="progress_bar_r_'+idx+'_0" value="0" max="100"></progress>'
                    + '<img src="'+thumb+'" id="representative_thumb_'+idx+'" />'
                    + '<label for="representative_image_'+idx+'" id="representative_image_drop_'+idx+'" class="representative-image"></label>'
                    + '</div>'
                    + '<div class="representative-description"><div>'
                    + '<button type="button" onclick="javascript:rmRepresentative('+idx+')">삭제</button>'
                    + '<button type="button" onclick="javascript:edRepresentative('+idx+')">변경</button>'
                    + '</div><div><ul>'
                    + '<li>3MB 이하의 jpg/jpeg/png 파일만 등록할 수 있습니다.</li>'
                    + '<li>썸네일은 사진/이미지 메뉴에서 목록 출력 및 다운로드 용도로 사용됩니다.</li>'
                    + '<li>썸네일을 등록하지 않은 경우, 첫번째 등록한 디자인 파일의 첫 화면을 썸네일로 사용합니다.</li>'
                    + '</ul></div></div></div></td></tr>'
                    + '<tr><th>디자인 파일</th>'
                    + '<td colspan="3">'
                    + '<label for="additional_file_'+idx+'" class="upload-file-btn">파일업로드</label>'
                    + '<div id="upload_file_box_'+idx+'" class="upload-file-box">'
                    + '</div></td></tr></tbody></table>';

            /* 레코드 생성 관련 */
            document.getElementById('content_form').insertAdjacentHTML('beforeend', record);

            if('design' in data) {
                const bytes_to_mb = (bytes) => bytes / (1024 * 1024);

                for(let i = 0; i < data.design.length; i++) {
                    addFileRow(idx, data.design[i].original_name, bytes_to_mb(data.design[i].size), i, 100);
                }
            }

            addRepresentative(idx);
            addFileFrom(idx);
            if(id === 0) {
                // localstorage에 key값 넣기
                let content_storage = localStorage.getItem('content_assets'),
                    contents = JSON.parse(content_storage);

                contents[idx] = {};
                localStorage.setItem('content_assets', JSON.stringify(contents));
            }
            /* 레코드 생성 관련 끝 */

            /* 레코드 삭제 */
            document.getElementById('remove_row_'+idx).addEventListener('click', () => {
                let parent = event.target.closest('table'),
                    idx = parseInt(parent.dataset.tidx);
                
                removeContentAssets(idx);

                parent.remove(); // 콘텐츠 등록 폼 삭제
                removeRepresentativeForm(idx); // 대표이미지 폼 삭제
                deleteFileFrom(idx); // 파일 업로드 폼 삭제

                // 리스트에 아무것도 없을 시 콘텐츠 등록 카드 하나 생성
                if(document.getElementById('content_form').childElementCount === 1) addRecord();
            });
            /* 레코드 삭제 끝 */

            /* 디자인 파일 업로드 관련 */
            document.getElementById('additional_file_'+idx).addEventListener('change', (e) => {
                const bytes_to_mb = (bytes) => bytes / (1024 * 1024);
                let files = e.target.files,
                form_data = new FormData();

                for(let i = 0; i < files.length; i++) {
                    let num = document.getElementById('upload_file_box_'+idx).childElementCount;
                    addFileRow(idx, files[i].name, bytes_to_mb(files[i].size), num);
                    form_data.append('file', files[i]);
                    fileUpload(form_data, idx, 'a', num);
                }

            });
            /* 디자인 파일 업로드 관련 끝 */
        }

        function removeContentAssets(idx) {
            let content_storage = localStorage.getItem('content_assets'),
                contents = JSON.parse(content_storage);
            
            delete contents[idx];

            localStorage.setItem('content_assets', JSON.stringify(contents));
        }

        /* 대표 이미지 삽입, 변경, 삭제 관련 */
        function addRepresentative(idx) {
            let row = '<form id="representative_form_'+idx+'" class="representative-form" onsubmit="return false;">@csrf'
                    + '<input type="file" name="representative_image_'+idx+'" id="representative_image_'+idx+'" />'
                    + '</form>';

            document.getElementById('representative_image_box').insertAdjacentHTML('beforeend', row);
            /* 드래그&드롭 -> 업로드 */
            document.getElementById('representative_image_drop_'+idx).addEventListener('drop', (e) => {
                e.preventDefault();
                let file = e.dataTransfer.files[0],
                form_data = new FormData();

                form_data.append('file', file);

                mediaUpload(form_data, idx);
            });
            // Drag over 상태
            document.getElementById('representative_image_drop_'+idx).addEventListener('dragover', (e) => {
                e.preventDefault();
            });
            // Drag leave 상태
            document.getElementById('representative_image_drop_'+idx).addEventListener('dragleave', (e) => {});
            /* 드래그&드롭 -> 업로드 끝 */
            /* 파일 클릭 시 */
            document.getElementById('representative_image_'+idx).addEventListener('change', (e) => {
                let file = e.target.files[0],
                form_data = new FormData();

                form_data.append('file', file);

                mediaUpload(form_data, idx);
            });
            /* 파일 클릭 시 끝 */
        }
        function rmRepresentative(idx) {
            // 삭제
            fileDelete(idx, 'representative');
            document.getElementById('representative_image_'+idx).value = '';
            document.getElementById('representative_thumb_'+idx).src = '';

            // 로컬스토리지 삭제
            removeObjectValue(idx, 'representative');
        }
        function edRepresentative(idx) {
            // 변경
            console.log(idx);
        }
        function removeRepresentativeForm(idx) {
            document.getElementById('representative_form_'+idx).remove();
        }
        /* 대표 이미지 삽입, 변경, 삭제 관련 끝 */

        /* 디자인 파일 업로드 관련 */
        function addFileFrom(idx) {
            let from = '<form id="additional_form_'+idx+'" class="additional-form" onsubmit="return false;">'
                    + '<input type="file" id="additional_file_'+idx+'" name="additional_file" multiple /></form>';

            document.getElementById('additional_upload_box').insertAdjacentHTML('beforeend', from);
        }
        function deleteFileFrom(idx) {
            document.getElementById('additional_form_'+idx).remove();
        }
        /* 디자인 파일 업로드 관련 끝 */

        /* 공통 파일 업로드 */
        function fileUpload(form_data, idx, type, num) {
            let req = new XMLHttpRequest();
            req.upload.addEventListener('progress', (e) => {
                progressHandler(e, idx, type, num);
            }, false);
            req.addEventListener('load', (e) => {
                completeHandler(e, idx, type, num);
            }, false);
            req.addEventListener('error', errorHandler, false);
            req.addEventListener('abort', abortHandler, false);

            req.onreadystatechange = () => {
                if(req.readyState === XMLHttpRequest.DONE) {
                    if(req.status === 200) {
                        let result = JSON.parse(req.response);

                        if(type === 'a') aCompleteResult(result, idx, num);
                    } else {
                        console.log('request error');
                    }
                }
            }

            req.open('POST', 'file_upload', true);
            // req.responseType = "json";
            req.send(form_data);
        }
        function mediaUpload(form_data, idx, type = 'r', num = 0) {
            // media upload 및 정보 가져오기
            let req = new XMLHttpRequest();
            req.upload.addEventListener('progress', (e) => {
                progressHandler(e, idx, type, num);
            }, false);
            req.addEventListener('load', (e) => {
                completeHandler(e, idx, type, num);
            }, false);
            req.addEventListener('error', errorHandler, false);
            req.addEventListener('abort', abortHandler, false);

            req.onreadystatechange = () => {
                if(req.readyState === XMLHttpRequest.DONE) {
                    if(req.status === 200) {
                        let result = JSON.parse(req.response);
                        rCompleteResult(result, idx);
                    } else {
                        console.log('request error');
                    }
                }
            }

            req.open('POST', 'image_upload', true);
            // req.responseType = "json";
            req.send(form_data);
        }

        function fileDelete(idx, obj, type = 'r', num) {
            let content_storage = localStorage.getItem('content_assets'),
                contents = JSON.parse(content_storage),
                form_data = new FormData();

            if(type === 'r') {
                form_data.append('path', contents[idx][obj].path);
            } else {
                form_data.append('path', contents[idx][obj][num].path);
            }

            let req = new XMLHttpRequest();

            req.onreadystatechange = () => {
                if(req.readyState === XMLHttpRequest.DONE) {
                    if(req.status === 200) {
                        let result = JSON.parse(req.response);
                    } else {
                        console.log('request error');
                    }
                }
            }

            req.open('POST', 'file_delete', true);
            // req.responseType = "json";
            req.send(form_data);
        }

        // 대표 이미지 업데이트
        function rCompleteResult(data, idx) {
            document.getElementById('representative_thumb_'+idx).src = data.thumbnail;

            let content_storage = localStorage.getItem('content_assets'),
                contents = JSON.parse(content_storage);

            if(!('representative' in contents[idx])) contents[idx].representative = {};

            contents[idx].representative = data;

            localStorage.setItem('content_assets', JSON.stringify(contents));
        }
        
        // 파일 업로드 시 진행상황 보여주는 row 생성
        function addFileRow(idx, file_nm, file_size, num, process = 0) {
            let row = '<div data-fidx="'+num+'">'
                    + '<div>'+file_nm+'</div>'
                    + '<div>'+file_size.toLocaleString()+'MB</div>'
                    + '<div><progress id="progress_bar_a_'+idx+'_'+num+'" value="'+process+'" max="100">'
                    + '</progress><span class="progress-percent" id="perc_'+idx+'_'+num+'">'+process+'%</span></div>'
                    + '<button type="button" onclick="javascript:removeFileRow('+idx+', '+num+')">X</button>'
                    + '</div>';

            document.getElementById('upload_file_box_'+idx).insertAdjacentHTML('beforeend', row);
        }

        function removeFileRow(idx, num) {
            fileDelete(idx, 'design', 'a', num);

            let content_storage = localStorage.getItem('content_assets'),
                contents = JSON.parse(content_storage);

            delete contents[idx].design[num];

            // contents[idx].design = contents[idx].design.filter((element, i) => i !== num);

            localStorage.setItem('content_assets', JSON.stringify(contents));

            event.target.closest('[data-fidx]').remove();

        }

        // 파일 업로드 버튼 아래 리스트 업데이트
        function aCompleteResult(data, idx, num) {
            let content_storage = localStorage.getItem('content_assets'),
                contents = JSON.parse(content_storage);

            if(!('design' in contents[idx])) contents[idx].design = [];

            contents[idx].design[num] = data;

            localStorage.setItem('content_assets', JSON.stringify(contents));
        }
        /* 공통 파일 업로드 끝 */

        /* progress bar 관련 */
        function progressHandler(event, idx, type, num = 0) {
            let percent = (event.loaded / event.total) * 100;
            document.getElementById('progress_bar_'+type+'_'+idx+'_'+num).value = Math.round(percent);
            if(type === 'a') document.getElementById('perc_'+idx+'_'+num).innerHTML = Math.round(percent)+'%';
            if(type === 'r') document.getElementById('progress_bar_'+type+'_'+idx+'_'+num).style.display = 'block';
        }
        function completeHandler(event, idx, type, num = 0) {
            if(type === 'r') {
                document.getElementById('progress_bar_'+type+'_'+idx+'_'+num).value = 0;
                document.getElementById('progress_bar_'+type+'_'+idx+'_'+num).style.display = 'none';
            }
        }
        function errorHandler(event) {}
        function abortHandler(event) {}
        /* progress bar 관련 */

        function removeObjectValue(idx, obj) {
            let content_storage = localStorage.getItem('content_assets'),
                contents = JSON.parse(content_storage);

            contents[idx][obj] = {};

            localStorage.setItem('content_assets', JSON.stringify(contents));
        }

        // document ready
        document.addEventListener('DOMContentLoaded', (e) => {
            let content_storage = localStorage.getItem('content_assets');

            if(!content_storage) localStorage.setItem('content_assets', '{}');

            if(content_storage && content_storage !== '{}') {
                let contents = JSON.parse(content_storage);

                for (const [key, content] of Object.entries(contents)) {
                    // 업로드 파일 순서 filter
                    if('design' in content) contents[key].design = content.design.filter(element => element !== null);
                    addRecord(key, content);
                }
                localStorage.setItem('content_assets', JSON.stringify(contents));
            } else {
                addRecord();
            }
        });
    </script>
</body>
</html>