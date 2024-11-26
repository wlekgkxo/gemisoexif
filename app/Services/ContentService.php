<?php

namespace App\Services;

use App\Models\Content;

class ContentService
{   
    public function setEmptyContent():int
    {
        $insert = (object) [];
        $insert->category_id = 1;
        $insert->bs_content_id = 518;
        $insert->ud_content_id = 0;
        $insert->content_id = Content::getNidx();
        $insert->reg_user_id = "archone_test";
        $insert->scn_meta_yn = '';
        $insert->publish_yn = '';
        $insert->live_yn = '';
        $insert->ai_yn = '';
        $insert->analytics_yn = '';
        $insert->cont_use_yn = 'Y';
        $insert->cont_open_yn = 'Y';
        $insert->title = "Empty";

        return Content::setRecord($insert);
    }

    public function createContent($content):int
    {
        $insert = (object) [];
        // bc_content의 다음 숫자를 가져옴
        $insert->content_id = Content::getNidx();
        $insert->title = $content['title'];
        $insert->rmk = $content['rmk'];
        $insert->category_id = 2;
        $insert->category_full_path = 2;
        $insert->category_full_path = '/0/100';
        $insert->reg_user_id = '';
        $insert->bs_content_id = 518;
        $insert->ud_content_id = 0;
        $insert->expired_date = '9999-12-31';
        $insert->is_group = 'G'; // 세그먼츠가 아니기 때문에 그룹으로
        $insert->status = '-3'; // 아마 승인요청 중.. 상태

        return Content::setRecord($insert);
    }
}