<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentMapping extends Model
{
	protected $table = 'tb_content_mapping';

	protected $primaryKey = 'content_id';

    public $timestamps = false;
    const CREATED_AT = null;
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $fillable = [
        'pgm_id',
        'pgm_sno',
        'mstrng_no',
        'media_id',
        'ref_id',
        'media_fg_cd',
        'content_id',
        'rgtmng_album_cd',
        'movie_id',
        'rgtmng_song_cd',
        'visual_id',
	    'tb_movie_id',
        'gemstone_album_id',
        'webcms_id'
    ];
    
    public static function setRecord($record)
    {
        return self::insertGetId($record);
    }
}
