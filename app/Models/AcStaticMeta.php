<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcStaticMeta extends Model
{
	protected $table = 'ac_static_meta';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id',
        'media_id',
        'shooting_date',
        'media_width',
        'media_height',
        'file_size',
        'extension_nm',
        'location_x',
        'location_y',
        'country',
        'address1',
        'address2',
        'maker',
        'tools',
        'dpi_x',
        'dpi_y',
        'equipment',
        'format_name',
        'format_long_name',
        'duration',
        'coded_dpi_x',
        'coded_dpi_y',
        'bit_rate',
        'codec_name',
        'codec_long_name',
        'nb_streams',
        'probe_score',
        'sample_aspect_ratio',
        'display_aspect_ratio',
        'created_at'
    ];

    public static function setRecord($record)
    {
        return self::insertGetId($record);
    }
}
