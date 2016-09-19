<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ReportReason
 */
class ReportReason extends Model {

    protected $table = 'report_reason';
    protected $primaryKey = 'report_reason_id';
    public $timestamps = false;
    protected $fillable = [
        'title_en',
        'title_ar'
    ];
    protected $guarded = [];

    public function reports() {
        return $this->hasMany("App\Models\Report");
    }

}
