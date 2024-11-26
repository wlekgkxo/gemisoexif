<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/*
    bs_content_id = 518 이게 이미지임
    bc_ud_content = 14 이게 부가자료임
*/

class Content extends Model
{
	protected $table = 'bc_content';
    protected $primaryKey = 'content_id';

	public $sortable = ['content_id'];

    protected $fillable = [
        'category_id',
        'category_full_path',
        'bs_content_id',
        'ud_content_id',
        'title',
        'is_deleted',
        'is_hidden',
        'reg_user_id',
        'expired_date',
        'last_modified_date',
        'created_date',
        'status',
        'readed',
        'last_accessed_date',
        'parent_content_id',
        'manager_status',
        'is_group',
        'group_count',
        'state',
        'archive_date',
        'del_status',
        'del_yn',
        'restore_date',
        'uan',
        'thumbnail_content_id',
        'sequence_id',
        'is_archive',
        'approval_yn',
        'created_at',
        'updated_at',
        'type_mc',
        'scn_meta_yn',
        'publish_yn',
        'live_yn',
        'ai_yn',
        'analytics_yn',
        'smr_yn',
        'seg_id',
        'mstrng_no',
        'hs_no',
        'use_yn',
        'version',
        'vod_edit_user_id',
        'chnl',
        'media_id',
        'media_fg_cd',
        'dcw_mcti_id',
        'cont_use_yn',
        'cont_open_yn',
        'content_seq',
        'rmk',
        'related_content',
        'track_id',
        'sound_quality',
        'archone_yn',
        'sd_yn'
    ];

    public static function getNidx():int
    {
        return self::selectOne("SELECT nextval('SEQ_CONTENT_ID') AS nidx")->nidx;
    }

    public static function setRecord($record):int
    {
        return self::insertGetId($record);
    }
}
