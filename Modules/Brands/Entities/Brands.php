<?php

namespace Modules\Brands\Entities;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Modules\Products\Entities\Products;

class Brands extends Model
{
    use Sluggable,SluggableScopeHelpers;
    use SoftDeletes;
    
	protected $table = "brands";

    protected $fillable = ['slug','name','description','status','image','brand_order','created_at'];

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

        /**
     * The function return the full picture path by setter attributes.
     * 
     * @param array $slug 
     */
    public function getPicturePathAttribute()
    {
        if(\config::get('custom.image-upload-on')=='s3'){
                return Storage::disk('s3')->url('images/brands/'.$this->image);
        }else{
            return \URL::to('images/brands/'.$this->image);
        }
    } 

    public function getS3UrlAttribute()
    {
        if(\config::get('custom.image-upload-on')=='s3'){
                return Storage::disk('s3')->url('images/brands/'.$this->image);
        }else{
            return \URL::to('images/brands/'.$this->image);
        }
    }

    public function products(){
        return $this->hasMany(Products::class,'brand_id');
    }
}
