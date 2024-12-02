const tus = require('tus-js-client');

document.getElementById('test_file').addEventListener('change', (e) => {
    // let files = event.target.files,
    //     form_data = new FormData();

    // for(let i = 0; i < files.length; i++) {
    //     form_data.append('files[]', files[i]);
    // }

    let url = document.getElementById('hide_url').value,
        file = event.target.files[0];

    const upload = new tus.Upload(file, {
        endpoint: 'http://localhost:1080/files/',
        // retryDelays: [0, 3000, 5000, 10000, 20000],
        uploadSize: file.size,
        metadata: {
            filename: file.name,
            filetype: file.type
        },
        onError: (error) => {
            console.error('Upload failed:', error);
        },
        onProgress: (bytesUploaded, bytesTotal) => {
            const percentage = ((bytesUploaded / bytesTotal) * 100).toFixed(2);
            console.log(`${bytesUploaded}/${bytesTotal} (${percentage}%)`);
        },
        onSuccess: () => {
            console.log('Upload finished:', upload.url);
        }
    });

    upload.start();
});