<?php

namespace Modules\Contactus\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Contactus extends Model
{
    use Sluggable;

	  protected $table = "contact_us";

  	protected $fillable = ['slug','name','email','message','created_at'];

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
                'method'=>function ($string, $separator) {
                        return md5(microtime());
                    },
                'onUpdate'=> false
            ]
        ];
    }

    public function setCreatedAtAttribute()
    {
        $this->attributes['created_at'] = utctodtc_4now();
    }

    public function setUpdatedAtAttribute()
    {
        $this->attributes['updated_at'] = utctodtc_4now();
    }
    
    static public function findBySlug($slug)
    {
        return static::where('slug',$slug)->first();
    }
}
