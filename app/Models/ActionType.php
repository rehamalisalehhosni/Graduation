<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ActionType
 */
class ActionType extends Model {

    protected $table = 'action_type';
    protected $primaryKey = 'action_type_id';
    public $timestamps = false;
    protected $fillable = [
        'key',
        'title_ar',
        'title_en'
    ];
    protected $guarded = [];

    public function userlogs() {
        return $this->hasMany("App\Models\UserLog");
    }

}
