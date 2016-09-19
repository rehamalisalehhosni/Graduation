<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Report
 */
class Report extends Model {

    protected $table = 'report';
    protected $primaryKey = 'report_id';
    public $timestamps = true;
    protected $fillable = [
        'reporter_id',
        'reported_id',
        'reported_type_id',
        'report_reason_id',
        'report_message'
    ];
    protected $guarded = [];

    function user() {
        return $this->belongsTo("App\Models\User", 'reporter_id');
    }

    function reportedtype() {
        return $this->belongsTo("App\Models\ReportedType", 'reported_type_id');
    }

    function reportreason() {
        return $this->belongsTo("App\Models\ReportReason" ,'report_reason_id');
    }

}
