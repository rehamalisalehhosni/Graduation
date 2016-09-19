<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ServicePlace
 */
class ServicePlace extends Model
{
    protected $table = 'service_place';

    protected $primaryKey = 'service_place_id';

    public $timestamps = true;

    protected $fillable = [
        'title',
        'description',
        'address',
        'longitude',
        'latitude',
        'logo',
        'mobile_1',
        'mobile_2',
        'mobile_3',
        'is_approved',
        'is_hide',
        'service_main_category_id',
        'service_sub_category_id',
        'user_id',
        'on_home',
        'cover_image',
        'total_rate',
        'neighbarhood_id',
        'is_featured',
        'opening_hours'
    ];

    protected $guarded = [];


    public function servicePlaceImage() {
        return $this->hasMany("App\Models\ServicePlaceImage");
    }

    public function servicePlaceReview() {
        return $this->hasMany("App\Models\ServicePlaceReview");
    } 
    public function favoriteServicePlace() {
        return $this->hasMany("App\Models\FavoriteServicePlace");
    }
     public function User()
    {
        return $this->belongsTo('App\Models\User' , 'user_id');
    }

    public function mainCat() {
        return $this->belongsTo('App\Models\ServiceMainCategory' , 'service_main_category_id');
    }

    public function subMainCat() {
        return $this->belongsTo('App\Models\ServiceMainCategory' , 'service_sub_category_id');
    }

    public function neighberhood() {
        return $this->belongsTo('App\Models\Neighbarhood' , 'neighbarhood_id');
    }

        
}