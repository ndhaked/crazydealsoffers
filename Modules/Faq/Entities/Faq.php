<?php

namespace Modules\Faq\Entities;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use Sluggable;
    
    protected $table = "faqs";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = ['slug','status','question','answer'];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' =>'question',
                'onUpdate'=>true
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

    /**
     * The function that should be find the slug for this model.
     * 
     * @param array $slug 
     */
    static public function findBySlug($slug)
    {
        return static::where('slug',$slug)->first();
    }

    static public function findById($id)
    {
        return static::where('id',$id)->first();
    }
}
