<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RealEstateAd
 */
class RealEstateAd extends Model
{
    protected $table = 'real_estate_ad';

    protected $primaryKey = 'real_estate_ad_id';

	public $timestamps = true;

    protected $fillable = [
        'title',
        'description',
        'location',
        'type',
        'number_of_rooms',
        'number_of_bathrooms',
        'price',
        'area',
        'longitude',
        'latitude',
        'language',
        'is_hide',
        'user_id',
        'owner_name',
        'owner_mobile',
        'owner_email',
        'on_home',
        'cover_image',
        'unit_type_id',
        'neighbarhood_id',
        'is_featured',
        'amenities_id'
    ];

    protected $guarded = [];

    public function unitType()
    {
        return $this->belongsTo('App\Models\UnitType' , 'unit_type_id');
    }
    public function User()
    {
        return $this->belongsTo('App\Models\User' , 'user_id');
    }
    public function neighbarhood()
    {
        return $this->belongsTo('App\Models\Neighbarhood' , 'neighbarhood_id');
    }
    public function amenity()
    {
        return $this->belongsTo('App\Models\Amenity' , 'amenities_id');
    }
    public function realEstateAdImage() {
        return $this->hasMany("App\Models\RealEstateAdImage");
    }
    public function realEstateAdComment() {
        return $this->hasMany("App\Models\RealEstateAdComment");
    }
    public function favoriteRealEstateAd() {
        return $this->hasMany("App\Models\FavoriteRealEstateAd");
    }

}
