<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcMedia extends Model
{
	protected $table = 'ac_media';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id',
        'media_type',
        'thumbnail',
        'original_name',
        'path',
        'storage_path',
        'extention',
        'upload_user',
        'created_at'
    ];

    public static function setRecord($record)
    {
        return self::insertGetId($record);
    }
}
