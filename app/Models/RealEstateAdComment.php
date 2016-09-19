<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RealEstateAdComment
 */
class RealEstateAdComment extends Model
{
    protected $table = 'real_estate_ad_comment';

    protected $primaryKey = 'real_estate_ad_comment_id';

	public $timestamps = true;

    protected $fillable = [
        'comment',
        'real_estate_ad_id',
        'user_id',
        'is_hide'
    ];

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Models\User' , 'user_id');
    }  
    public function realEstateAd()
    {
        return $this->belongsTo('App\Models\RealEstateAd' , 'real_estate_ad_id');
    }

}