<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DiscussionImage
 */
class DiscussionImage extends Model
{
    protected $table = 'discussion_image';

    protected $primaryKey = 'discussion_image_id';

	public $timestamps = false;

    protected $fillable = [
        'image',
        'discussion_id'
    ];

    protected $guarded = [];
    
    public function discussion() {
        return $this->belongsTo('App\Models\Discussion' , 'discussion_id');
    }
        
}