<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DiscussionComment
 */
class DiscussionComment extends Model
{
    protected $table = 'discussion_comment';

    protected $primaryKey = 'discussion_comment_id';

	public $timestamps = true;

    protected $fillable = [
        'comment',
        'discussion_id',
        'user_id',
        'is_hide'
    ];

    protected $guarded = [];

    public function discussion() {
        return $this->belongsTo('App\Models\Discussion' , 'discussion_id');
    }

    public function user() {
        return $this->belongsTo('App\Models\User' , 'user_id');
    }

        
}