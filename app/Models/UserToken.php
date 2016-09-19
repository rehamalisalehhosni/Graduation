<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserToken
 */
class UserToken extends Model {

    protected $table = 'user_token';
    protected $primaryKey = 'user_token_id';
    public $timestamps = false;
    protected $fillable = [
        'reset_password_code',
        'expire_at',
        'is_expired',   
        'created_at',
        'user_id'
    ];
    protected $guarded = [];

    function user() {
        return $this->belongsTo("App\Models\User");
    }

}
