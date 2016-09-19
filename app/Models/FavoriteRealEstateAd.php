<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FavoriteRealEstateAd
 */
class FavoriteRealEstateAd extends Model
{
    protected $table = 'favorite_real_estate_ad';

    protected $primaryKey = 'favorite_real_estate_ad_id';

	public $timestamps = false;

    protected $fillable = [
        'user_id',
        'real_estate_ad_id'
    ];

    protected $guarded = [];

    public function getRealEstateInfo() {
        return $this->hasMany("App\Models\RealEstateAd");
    }
    public function realEstateAd()
    {
        return $this->belongsTo('App\Models\RealEstateAd' , 'real_estate_ad_id');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User' , 'user_id');
    }
}
