<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FavoriteDiscussion
 */
class FavoriteDiscussion extends Model
{
    protected $table = 'favorite_discussion';

    protected $primaryKey = 'favorite_discussion_id';

	public $timestamps = false;

    protected $fillable = [
        'user_id',
        'discussion_id'
    ];

    protected $guarded = [];

    public function discussion() {
    	 $test = $this->belongsTo('App\Models\Discussion' , 'discussion_id')->select("discussion_id" ,'title','cover_image' );
         return $test;
    }
    public function user() {
        return $this->belongsTo('App\Models\User' , 'user_id')->select('user_id' , 'image');
    }
}