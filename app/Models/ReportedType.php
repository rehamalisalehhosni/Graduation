<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ReportedType
 */
class ReportedType extends Model {

    protected $table = 'reported_type';
    protected $primaryKey = 'reported_type_id';
    public $timestamps = false;
    protected $fillable = [
        'key',
        'title_ar',
        'title_en'
    ];
    protected $guarded = [];

    public function reports() {
        return $this->hasMany("App\Models\Report");
    }

}
