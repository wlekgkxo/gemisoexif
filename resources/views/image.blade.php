<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/image.css') }}">
    <title>이미지 업로드</title>
</head>
<body>
    <form id="representative_form" class="representative-form" onsubmit="return false;">
        <input type="file" name="representative_image_0" id="representative_image_0" />
    </form>
    <div id="additional_upload_box">
        <form id="additional_form_0" class="additional-form" onsubmit="return false;">
            <input type="file" id="additional_file_0" name="additional_file_0" multiple />
        </form>
    </div>
    <div class="warp">
        <div class="content-box">
            <div class="content-title">
                <h3>콘텐츠 등록 총 ?개</h3>
                <button type="button" onclick="javascript:addRecord();">+ 추가</button>
            </div>
            <form id="content_form" onsubmit="return false;">
                <table data-tidx="0">
                    <tbody>
                        <tr>
                            <th>자료구분</th>
                            <td><input type="text" name="file_category_0" /></td>
                            <th>콘텐츠 구분</th>
                            <td>
                                <select name="category_selected_0" id="category_selected_0">
                                    <option value="">선택 ▼</option>
                                    <option value="icon">아이콘</option>
                                    <option value="logo">로고</option>
                                    <option value="thumb">썸네일</option>
                                    <option value="editor">편집파일</option>
                                </select>
                            </td>
                            <td rowspan="3" id="remove_row_zero" class="remove-row">
                                <span class="trash-icons"></span>
                            </td>
                        </tr>
                        <tr>
                            <th>썸네일</th>
                            <td colspan="3">
                                <div class="representative-place">
                                    <div>
                                        <label for="representative_image" class="representative-image"></label>
                                    </div>
                                    <div class="representative-description">
                                        <div>
                                            <button type="button">삭제</button><button type="button">변경</button>
                                        </div>
                                        <div>
                                            <ul>
                                                <li>3MB 이하의 jpg/jpeg/png 파일만 등록할 수 있습니다.</li>
                                                <li>썸네일은 사진/이미지 메뉴에서 목록 출력 및 다운로드 용도로 사용됩니다.</li>
                                                <li>썸네일을 등록하지 않은 경우, 첫번째 등록한 디자인 파일의 첫 화면을 썸네일로 사용합니다.</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>디자인 파일</th>
                            <td colspan="3">
                                <label class="upload-file-btn" for="additional_file">파일업로드</label>
                                <div id="upload_file_box_0" class="upload-file-box"></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('remove_row_zero').addEventListener('click', () => {
            // form 초기화
        });

        function addRecord() {
            let idx = document.getElementById('content_form').childElementCount,
                content_selector = [{nm: '아이콘', val: 'icon'},
                                    {nm: '로고', val: 'logo'},
                                    {nm: '썸네일', val: 'thumb'},
                                    {nm: '편집파일', val: 'editor'}];

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
                    + '<div><label for="representative_image_'+idx+'" class="representative-image"></label></div>'
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

            document.getElementById('content_form').insertAdjacentHTML('beforeend', record);
            
            document.getElementById('remove_row_'+idx).addEventListener('click', () => {
                let parent = event.target.closest('table'),
                    idx = parseInt(parent.dataset.tidx);

                parent.remove();
            });
        }

        function addFileRow(idx) {
            let row = '<div>'
                    + '<div>장사천재 백사장_로고가이드.ai</div>'
                    + '<div>883.9MB</div>'
                    + '<div><progress value="0" max="100"></progress></div>'
                    + '<button type="button">X</button>'
                    + '</div>';

            document.getElementById('upload_file_box_'+idx).insertAdjacentHTML('beforeend', row);
        }

        function addFileFrom(idx) {
            let from = '<form id="additional_form_'+idx+'" class="additional-form" onsubmit="return false;">'
                    + '<input type="file" id="additional_file_'+idx+'" name="additional_file" multiple /></form>';

            document.getElementById('additional_upload_box').insertAdjacentHTML('beforeend', from);
        }

        function rmRepresentative(idx) {
            console.log(idx);
        }

        function edRepresentative(idx) {
            console.log(idx);
        }
    </script>
</body>
</html>