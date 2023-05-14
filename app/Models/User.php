<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Cviebrock\EloquentSluggable\Sluggable;
use App\Models\Spatie\Permission\Traits\HasRoles;
use Kyslik\ColumnSortable\Sortable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;
    use Sluggable;
    use HasRoles;
    use Sortable;

    public static $guard_name = "web";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug','username','name', 'email','phone', 'password','image','status','last_login_at','last_login_ip','device_token','is_unread','notification_status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $dates = ['last_login_at'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public $sortable = ['id','name','email','created_at'];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name',
                'onUpdate'=>false
            ],
            'username' => [
                'source' => 'name',
                'onUpdate'=>false
            ],
        ];
    }

    
    /**
     * The function return the full picture path by setter attributes.
     * 
     * @param array $slug 
     */
    public function getPicturePathAttribute()
    {
        return Storage::disk('s3')->url('images/user/'.$this->image);
    } 

     /**
     * The function return the full picture path for thumb by setter attributes.
     * 
     * @param array $slug 
     */
    public function getThumbPicturePathAttribute()
    {
        return Storage::disk('s3')->url('images/user/'.$this->image);
    }

    public function getFullNameAttribute()
    {
        return ucfirst($this->name);
    }

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }

    public function accounts(){
        return $this->hasMany('Modules\SocialLogin\Entities\LinkedSocialAccount');
    }

    public function user_favorite_products()
   {
       return $this->hasMany(UserFavoriteProducts::class, 'user_id');
   }

   public function getS3UrlAttribute()
    {
        return  Storage::disk('s3')->url('images/user/'.$this->image);
    }

    public function userToken()
    {
        return $this->hasOne('App\Models\JwtUserTokens', 'user_id');
    }
}
