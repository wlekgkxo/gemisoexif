<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DownFiles extends Model
{
	protected $table = 'down_files';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'file_root',
        'file_path',
        'file_name',
        'file_ext',
        'trg_file_name',
        'file_size',
        'expired_at',
        'media_id',
        'content_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'user_id',
        'own_uuid',
        'req_id',
        'is_complete',
        'down_started_at',
        'down_completed_at',
        'down_bytes'
    ];
}
