<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DiscussionTopic
 */
class DiscussionTopic extends Model
{
    protected $table = 'discussion_topic';

    protected $primaryKey = 'discussion_topic_id';

	public $timestamps = false;

    protected $fillable = [
        'title_ar',
        'title_en',
        'place'
    ];

    protected $guarded = [];
    
    public function discussions() {
        return $this->hasMany("App\Models\Discussion");
    }

        
}