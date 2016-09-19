<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Lookup
 */
class Lookup extends Model
{
    protected $table = 'lookup';

    protected $primaryKey = 'lookup_id';

	public $timestamps = false;

    protected $fillable = [
        'lookup_code',
        'lookup_key',
        'look_value'
    ];

    protected $guarded = [];

        
}