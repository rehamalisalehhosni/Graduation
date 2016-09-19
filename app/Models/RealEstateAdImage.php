<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RealEstateAdImage
 */
class RealEstateAdImage extends Model
{
    protected $table = 'real_estate_ad_image';

    protected $primaryKey = 'real_estate_ad_image_id';

	public $timestamps = false;

    protected $fillable = [
        'image',
        'is_primary',
        'real_estate_ad_id'
    ];

    protected $guarded = [];

    public function realEstateAd()
    {
        return $this->belongsTo('App\Models\RealEstateAd' , 'real_estate_ad_id');
    } 
}