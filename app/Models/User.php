<?php
namespace App\Models;


use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
#use Illuminate\Contracts\Auth\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;



class User extends Model implements
    AuthenticatableContract,
    CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;


    protected $table = 'user';
    protected $primaryKey = 'user_id';
    public $timestamps = false;
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'image',
        'date_of_birth',
        'mobile_number',
        'last_forget_password',
        'facebook_id',
        'verify_email',
        'is_active'

    ];

    protected $hidden = [
        'password','facebook_id',
    ];
    protected $guarded = [];

    public function userconfigs() {
        return $this->hasMany("App\Models\UserConfig");
    }

    public function userlogs() {
        return $this->hasMany("App\Models\UserLog");
    }

    public function usertokens() {
        return $this->hasMany("App\Models\UserToken");
    }

    public function reports() {
        return $this->hasMany("App\Models\Report", 'reporter_id');
    }

    public function realEstateAd() {
        return $this->hasMany('App\RealEstateAd');
    }

    public function realEstateAdComment() {
        return $this->hasMany("App\Models\RealEstateAdComment");
    }

    public function discussion() {
        return $this->hasMany("App\Models\Discussion");
    }

    public function discussionComment() {
        return $this->hasMany("App\Models\DiscussionComment");
    }

    public function favoriteDiscussion() {
        return $this->hasMany("App\Models\FavoriteDiscussion");
    }

    public function servicePlace() {
        return $this->hasMany("App\Models\ServicePlace");
    }

    public function favoriteServicePlace() {
        return $this->hasMany("App\Models\FavoriteServicePlace");
    }
    public function favoriteRealEstateAd() {
        return $this->hasMany("App\Models\FavoriteRealEstateAd");
    }

    public function servicePlaceReviews() {
        return $this->hasMany("App\Models\ServicePlaceReview");
    }
    public function favoriteDiscussions() {
        return $this->hasMany("App\Models\FavoriteDiscussion");
    }
}
