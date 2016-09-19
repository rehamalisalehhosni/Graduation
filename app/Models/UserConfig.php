<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserConfig
 */
class UserConfig extends Model {

    protected $table = 'user_config';
    protected $primaryKey = 'user_config_id';
    public $timestamps = false;
    protected $fillable = [
        'language',
        'notification',
        'device_token',
        'device_type',
        'neighbarhood_id',
        'user_id',
        'is_logout'
    ];
    protected $guarded = [];

    function neighbarhood() {
        return $this->belongsTo("App\Models\Neighbarhood");
    }

    function user() {
        return $this->belongsTo("App\Models\User");
    }

}
