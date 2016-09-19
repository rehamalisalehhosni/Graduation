<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ServicePlaceReview
 */
class ServicePlaceReview extends Model {

    protected $table = 'service_place_review';
    protected $primaryKey = 'service_place_review_id';
    public $timestamps = true;
    protected $fillable = [
        'review',
        'is_hide',
        'service_place_id',
        'user_id',
        'rating'
    ];
    protected $guarded = [];

    public function servicePlace() {
        return $this->belongsTo('App\Models\ServicePlace', 'service_place_id');
    }

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

}
