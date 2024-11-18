<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="referrer" content="no-referrer-when-downgrade" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>연예인 인식</title>
</head>
<body>
    <div>
        해외 연예인들만 인식이 되고 그마저도 제대로 인식 못할 때가 많음.
    </div>
    <form method="POST" id="celebrity_found" onsubmit="return false;" enctype="multipart/form-data">
        <!-- <input type="file" name="celebrity_image" id="celebrity_image" /> -->
        <input type="text" name="celebrity_image_url" id="celebrity_image_url">
        <button type="submit">이사람 누구야?</button>
    </form>

    <script>
        window.onload = function() {
            // document.getElementById("celebrity_image").addEventListener("change", (e) => {
            document.getElementById("celebrity_found").addEventListener("submit", (e) => {

                let celebrity_image = document.getElementById("celebrity_found"),
                    form_data = new FormData(celebrity_image);

                let req = new XMLHttpRequest();

                req.onreadystatechange = () => {
                    if(req.readyState === XMLHttpRequest.DONE) {
                        if(req.status === 200) {
                            let result = JSON.parse(req.response);

                            console.log(result);
                        } else {
                            console.log('request error');
                        }
                    } 
                }

                req.open("POST", "whosthatperson", true);
                req.responseType = "json";
                req.send(form_data);
            });
        }
    </script>
</body>
</html>