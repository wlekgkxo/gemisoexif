<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/info', function () {
    phpinfo();
});

Route::get('/file', function () {
    return view('metaset');
});

Route::get('/uploader', function () {
    return view('uploader');
});

Route::get('/meta', function () {
    return view('mediameta');
});

Route::get('/image', function () {
    return view('image');
});

Route::get('/celebrity', function () {
    return view('celebrity');
});

Route::get('/tusd', function () {
    return view('tusd');
});

Route::get('/list', 'UploaderController@list');

Route::post('/file', 'TestController@getFileMeta');

Route::post('/upload', 'UploaderController@upload');

Route::post('/whosthatperson', 'CelebrityController@getWho');

Route::post('/requestingest', 'IngestRequestController@requestIngest');
Route::post('/mediaupload', 'IngestRequestController@mediaUpload');
Route::post('/removemedia', 'IngestRequestController@ingestQuit');

Route::post('/file_upload', 'ImageController@fileUpload');
Route::post('/image_upload', 'ImageController@imageUpload');
Route::post('/file_delete', 'ImageController@fileDelete');

