<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserLog
 */
class UserLog extends Model
{
    protected $table = 'user_log';

    protected $primaryKey = 'user_log_id';

	public $timestamps = false;

    protected $fillable = [
        'user_id',
        'log_type_id',
        'log_time'
    ];

    protected $guarded = [];

    function user() {
        return $this->belongsTo("App\Models\User");
    }
    
    function actiontype() {
        return $this->belongsTo("App\Models\ActionType");
    }
        
}