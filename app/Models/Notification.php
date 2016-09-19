<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Notification
 */
class Notification extends Model {

    protected $table = 'notifications';
    protected $primaryKey = 'notifications_id';
    public $timestamps = false;
    protected $fillable = [
        'owner_id',
        'sender_id',
        'type',
        'type_id',
        'read_at',
        'seen'
    ];
    protected $guarded = [];

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

}

