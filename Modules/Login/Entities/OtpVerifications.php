<?php

namespace Modules\Login\Entities;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;

class OtpVerifications extends Model
{
    use Sluggable,SluggableScopeHelpers;
    
    protected $table = "otp_verifications";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug','email','otp_verification_code','created_at'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function setCreatedAtAttribute()
    {
        $this->attributes['created_at'] = utctodtc_4now();
    }

    public function setUpdatedAtAttribute()
    {
        $this->attributes['updated_at'] = utctodtc_4now();
    }
    
   /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
   public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'email',
                'method'=>function ($string, $separator) {
                        return md5(microtime());
                    },
                'onUpdate'=>false
            ]
        ];
    }

    /**
     * The function that should be find the slug for this model.
     * 
     * @param array $slug 
     */
    static public function findBySlug($slug)
    {
        return static::where('slug',$slug)->first();
    }
}
