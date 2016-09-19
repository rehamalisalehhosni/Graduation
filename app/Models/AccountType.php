<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AccountType
 */
class AccountType extends Model
{
    protected $table = 'account_type';

    protected $primaryKey = 'account_type_id';

	public $timestamps = false;

    protected $fillable = [
        'key',
        'title_ar',
        'title_en'
    ];

    protected $guarded = [];

        
}