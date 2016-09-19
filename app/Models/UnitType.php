<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UnitType
 */
class UnitType extends Model
{
    protected $table = 'unit_type';

    protected $primaryKey = 'unit_type_id';

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