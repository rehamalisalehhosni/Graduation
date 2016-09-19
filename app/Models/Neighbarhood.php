<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Neighbarhood
 */
class Neighbarhood extends Model {

    protected $table = 'neighbarhood';
    protected $primaryKey = 'neighbarhood_id';
    public $timestamps = false;
    protected $fillable = [
        'title_ar',
        'title_en',
        'is_hiden'
    ];
    protected $guarded = [];

    public function userconfigs() {
        return $this->hasMany("App\Models\UserConfig");
    }
    public function realEstateAd()
    {
            return $this->hasMany('App\RealEstateAd');
    }  

    public function servicePlace()
    {
            return $this->hasMany('App\ServicePlace');
    }   

}
