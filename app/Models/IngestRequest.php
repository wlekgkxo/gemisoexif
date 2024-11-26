<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IngestRequest extends Model
{
    protected $table = 'tb_ingest_temp';

    protected $fillable = [
        'id',
        'req_title',
        'req_content',
        'req_info',
        'status',
        'created_at',
        'ingest_type',
        'menu',
        'category',
        'pgm_id',
        'pgm_nm',
        'content_num',
        'req_id',
        'updated_at'
    ];
    
	protected static function boot()    
    {
        parent::boot();

        static::creating(function ($model) {
            $model->status = '001'; 
        });
    }
}