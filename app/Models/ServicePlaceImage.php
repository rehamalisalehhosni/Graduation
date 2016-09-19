<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ServicePlaceImage
 */
class ServicePlaceImage extends Model
{
    protected $table = 'service_place_image';

    protected $primaryKey = 'service_place_image_id';

	public $timestamps = false;

    protected $fillable = [
        'image',
        'service_place_id'
    ];

    protected $guarded = [];

    public function servicePlace() {
    	return $this->belongsTo('App\Models\ServicePlace' , 'service_place_id');
    }
}