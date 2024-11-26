<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scene extends Model
{
	protected $table = 'bc_scene';

	protected $primaryKey = 'scene_id';

    public $timestamps = false;
    const CREATED_AT = null;
    const UPDATED_AT = null;
    const DELETED_AT = null;
}
