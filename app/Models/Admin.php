<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Admin
 */
class Admin extends Model
{
    protected $table = 'admin';

    protected $primaryKey = 'admin_id';

	public $timestamps = true;

    protected $fillable = [
        'user_name',
        'email',
        'user_password',
        'is_active',
        'last_login',
        'admin_name',
        'account_type_id'
    ];

    protected $guarded = [];

        
}