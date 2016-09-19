<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AppConfig
 */
class AppConfig extends Model
{
    protected $table = 'app_config';

    protected $primaryKey = 'app_config_id';

	public $timestamps = false;

    protected $fillable = [
        'key',
        'value'
    ];

    protected $guarded = [];

        
}