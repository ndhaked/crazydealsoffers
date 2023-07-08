<?php

namespace Modules\Platform\Entities;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Modules\Products\Entities\Products;

class Platform extends Model
{
    use Sluggable,SluggableScopeHelpers;
    use SoftDeletes;
    
	protected $table = "platforms";

    protected $fillable = ['slug','name','description','status','image','platform_order','created_at'];

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
                return Storage::disk('s3')->url('images/platform/'.$this->image);
        }else{
            return \URL::to('images/platform/'.$this->image);
        }
    } 

    public function getS3UrlAttribute()
    {
        if(\config::get('custom.image-upload-on')=='s3'){
                return Storage::disk('s3')->url('images/platform/'.$this->image);
        }else{
            return \URL::to('images/platform/'.$this->image);
        }
    }

    public function products(){
        return $this->hasMany(Products::class,'platform_id');
    }
}
