<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Discussion
 */
class Discussion extends Model {

    protected $table = 'discussion';
    protected $primaryKey = 'discussion_id';
    public $timestamps = true;
    protected $fillable = [
        'title',
        'details',
        'user_id',
        'neighbarhood_id',
        'topics_id',
        'is_hide',
        'on_home',
        'cover_image'
    ];
    protected $guarded = [];

    public function favoriteDiscussion() {
        return $this->hasMany("App\Models\FavoriteDiscussion");
    }

    public function discussionComment() {
        return $this->hasMany("App\Models\DiscussionComment");
    }

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function neighbarhood() {
        return $this->belongsTo('App\Models\Neighbarhood', 'neighbarhood_id');
    }

    public function discussionTopic() {
        return $this->belongsTo('App\Models\DiscussionTopic', 'topics_id');
    }

    public function comments() {
        return $this->hasMany("App\Models\DiscussionComment");
    }
    
    public function images() {
        return $this->hasMany("App\Models\DiscussionImage");
    }

}
