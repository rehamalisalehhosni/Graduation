<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Directory
 */
class Directory extends Model
{
    protected $table = 'directory';

    protected $primaryKey = 'directory_id';

	public $timestamps = false;

    protected $fillable = [
        'key',
        'path'
    ];

    protected $guarded = [];

        
}