<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/uploader.css') }}">
    <title>Uploader</title>
</head>
<body>
    <div class="warp">
        <div class="side-container"></div>
        <div class="uploader-header">
            <div class="left-side">
                <progress id="progress_bar" value="0" max="100"></progress>
                <h3 id="status"></h3>
                <p id="loaded_n_total"></p>
            </div>
            <div class="right-side">
                <form method="POST" name="file_uploader" id="file_uploader" enctype="multipart/form-data">
                    @csrf
                    <label for="upload_media">File Upload</label>
                    <input type="file" id="upload_media" name="upload_media[]" multiple />
                </form>
            </div>
        </div>
        <div id="uploader_body" class="uploader-body">
            <div id="selected_box" class="selected-box">
            </div>
            <div class="preview-box"></div>
        </div>
    </div>
    <script>
        document.getElementById('upload_media').addEventListener('change', (e) => {
            let files = event.target.files,
            form_data = new FormData();

            for(let i = 0; i < 5; i++) {
                form_data.append('files[]', files[i]);
            }

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

                        setCard(result);
                    } else {
                        console.log('request error');
                    }
                } 
            }

            req.open('POST', 'upload', true);
            // req.responseType = 'json';
            req.send(form_data);

            return false;
        });

        // document.getElementById('uploader_body').addEventListener('dragenter', function(e) {});
        
        document.getElementById('uploader_body').addEventListener('dragover', function(e) {
            e.preventDefault();

            this.style.backgroundColor = '#c1c1c1';
        });
        
        document.getElementById('uploader_body').addEventListener('dragleave', function(e) {
            console.log('dragleave');

            this.style.backgroundColor = 'white';
        });

        document.getElementById('uploader_body').addEventListener('drop', function(e) {
            e.preventDefault();

            // console.log('drop');
            // this.style.backgroundColor = 'white';

            // console.dir(e.dataTransfer);

            // var data = e.dataTransfer.files;
            // console.dir(data);

            let files = e.dataTransfer.files;
            form_data = new FormData();

            for(let i = 0; i < files.length; i++) {
                form_data.append('files[]', files[i]);
            }

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

                        setCard(result);
                    } else {
                        console.log('request error');
                    }
                } 
            }

            req.open('POST', 'upload', true);
            // req.responseType = "json";
            req.send(form_data);

            return false;
        });

        document.addEventListener('DOMContentLoaded', function(){
            let req = new XMLHttpRequest();
            
            req.onreadystatechange = () => {
                if(req.readyState === XMLHttpRequest.DONE) {
                    if(req.status === 200) {
                        let result = JSON.parse(req.response);

                        setCard(result);
                    } else {
                        console.log('request error');
                    }
                } 
            }

            req.open('GET', 'list', true);
            req.send();
        });

        function setCard(data) {
            let card_list = '';
            for(let i = 0; i < data.length; i++) {
                let media_type = data[i].media_type === 1 ? '이미지' : '비디오';
                card_list += '<div class="select-card">'
                                + '<span>'
                                    + '<img src="'+data[i].thumbnail+'" alt="'+data[i].original_name+'">'
                                + '</span>'
                                + '<ul>'
                                    + '<li>'+media_type+'</li>'
                                    + '<li>'+data[i].original_name+'</li>'
                                    + '<li>'+data[i].upload_user+'</li>'
                                    + '<li>'+data[i].created_at+'</li>'
                                + '<ul>'
                            + '</div>';
            }
            document.getElementById('selected_box').insertAdjacentHTML('afterbegin', card_list);
        }

        function progressHandler(event) {
            let percent = (event.loaded / event.total) * 100;
            document.getElementById('progress_bar').value = Math.round(percent);
            document.getElementById('status').innerHTML = Math.round(percent) + "% uploaded... please wait";
        }
        function completeHandler(event) {
            document.getElementById('status').innerHTML = '';
            document.getElementById('progress_bar').value = 0;
        }
        function errorHandler(event) {
            document.getElementById('status').innerHTML = "Upload Failed";
        }
        function abortHandler(event) {
            document.getElementById('status').innerHTML = "Upload Aborted";
        }
    </script>
</body>
</html>