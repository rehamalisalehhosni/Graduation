<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FavoriteServicePlace
 */
class FavoriteServicePlace extends Model
{
    protected $table = 'favorite_service_place';

    protected $primaryKey = 'favorite_service_place_id';

	public $timestamps = false;

    protected $fillable = [
        'service_place_id',
        'user_id'
    ];

    protected $guarded = [];
    public function servicePlace()
    {
        return $this->belongsTo('App\Models\ServicePlace' , 'service_place_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User' , 'user_id');
    }
        
}