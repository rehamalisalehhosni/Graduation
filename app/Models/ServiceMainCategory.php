<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ServiceMainCategory
 */
class ServiceMainCategory extends Model
{
    protected $table = 'service_main_category';

    protected $primaryKey = 'service_main_category_Id';

	public $timestamps = false;

    protected $fillable = [
        'title_ar',
        'title_en',
        'main_category'
    ];

    protected $guarded = [];

    public function servicePlaces() {
        return $this->hasMany("App\Models\servicePlace");
    }

      public function serviceMainCategory() {
        return $this->hasMany("App\Models\ServiceMainCategory");
    }
          public function serviceMainCategory_child() {
        return $this->belongsTo("App\Models\ServiceMainCategory",'main_category');
    }
       
        
}