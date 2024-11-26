<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Scene;

class Media extends Model
{
    protected $table = 'bc_media';

    protected $primaryKey = 'media_id';
    
    public $timestamps = false;
    protected $dateFormat = 'Y/m/d';
    const CREATED_AT = 'created_date';
    const UPDATED_AT = null;

    public $sortable = ['media_id'];

    protected $fillable = [
        'content_id',
        'media_id',
        'storage_id',
        'media_type',
        'path',
        'filesize',
        'created_date',
        'reg_type',
        'status',
        'delete_date',
        'flag',
        'delete_status',
        'vr_start',
        'vr_end',
        'expired_date',
        'memo'
    ];

    /**
     * 카탈로그
     *
     * @return \App\Models\Scene
     */
    public function cataloges()
    {
        return $this->hasMany(Scene::class,
                                'media_id',
                                'media_id');
    }
}