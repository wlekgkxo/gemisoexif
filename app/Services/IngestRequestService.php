<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

use App\Services\ContentService;
use App\Models\ContentMapping; // 콘텐츠와 프로그램 매핑
use App\Services\FileUploadService;

use App\Models\IngestRequest;
use App\Models\IngestContentMapping; // 인제스트와 콘텐츠 매핑

class IngestRequestService
{
    private $contentService;

    public function __construct(ContentService $contentService)
    {
        $this->contentService = $contentService;
    }

    public function ingest(Request $request, UploadedFile $file)
    {
        $files = $request->file('files');
        $content = $request->get('contents');

        $content_id = $this->contentService->createContent($content);

        // 프로그램을 참조할 때 아래 매핑
        if($request->has('pgm_id')) {
            $c_mapping = (object) [];
            $c_mapping->content_id = $content_id;
            $c_mapping->pgm_id = $request->get('pgm_id');
            if($request->has('pgm_sno')) $c_mapping->pgm_sno = $request->get('pgm_sno');
            if($request->has('pgm_sno')) $c_mapping->pgm_sno = $request->get('pgm_sno');
            $c_mapping->media_id = 158;
            
            $c_mapping_id = ContentMapping::setRecord($c_mapping);
        }

        $ingest = (object) [];
        $ingest->req_title = $request->get('req_title');
        if($request->has('req_content')) $ingest->req_content = $request->get('req_content');
        if($request->has('req_info')) $ingest->req_info = $request->get('req_info');
        if($request->has('ingest_type')) $ingest->ingest_type = $request->get('ingest_type');
        if($request->has('menu')) $ingest->menu = $request->get('menu'); // menu : IMAGE
        if($request->has('category')) $ingest->category = $request->get('category');
        if($request->has('pgm_id')) $ingest->pgm_id = $request->get('pgm_id');
        if($request->has('pgm_nm')) $ingest->pgm_nm = $request->get('pgm_nm');
        // if($request->has('content_num')) $ingest->content_num = $request->get('content_num'); // 컨텐츠 등록 개수임 ㅋ
        if($request->has('req_id')) $ingest->req_id = $request->get('req_id'); // 요청자임
        if($request->has('admin_id')) $ingest->admin_id = $request->get('admin_id'); // 담당자

        $ingest_id = IngestRequest::setRecord($ingest);

        $i_mapping = (object) [];

        $i_mapping->ingest_id = $ingest_id;
        $i_mapping->content_id = $content_id;
        $i_mapping->is_group = 'S';
        $i_mapping->status = '000';
        $i_mapping_id = IngestContentMapping::setRecord($i_mapping);


    }

    public function update()
    {

    }
}