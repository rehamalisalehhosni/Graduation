<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Amenity
 */
class Amenity extends Model
{
    protected $table = 'amenities';

    protected $primaryKey = 'amenities_id';

	public $timestamps = false;

    protected $fillable = [
        'title_ar',
        'title_en'
    ];

    protected $guarded = [];
	public function realEstateAd()
	{
	    return $this->hasMany('App\RealEstateAd');
	} 
        
}