<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IngestContentMapping extends Model
{
    // content 랑 ingest 매핑

    protected $table = 'tb_ingest_content_mapping';
    protected $primaryKey = 'id';

    protected $fillable = [
        'ingest_id',
        'content_id',
        'is_group',
        'status',
    ];
}