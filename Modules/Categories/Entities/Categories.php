<?php

namespace Modules\Categories\Entities;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Modules\Projects\Entities\Projects;
use Illuminate\Support\Facades\Storage;
use Modules\Products\Entities\Products;

class Categories extends Model
{
    use Sluggable,SluggableScopeHelpers;
    use SoftDeletes;
    
	protected $table = "categories";

    protected $fillable = [ 'parent_id','slug','name','description','status','image','category_order','created_at'];

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
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
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
        return Storage::disk('s3')->url('images/category/'.$this->image);
    } 

     /**
     * The function return the full picture path for thumb by setter attributes.
     * 
     * @param array $slug 
     */
    public function getThumbPicturePathAttribute()
    {
        return Storage::disk('s3')->url('images/category/'.$this->image);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projects()
    {
        return $this->hasMany(Projects::class,'category_id');
    }

    public function getS3UrlAttribute()
    {
        return Storage::disk('s3')->url('images/category/'.$this->image);
    }

    public function product(){
        return $this->hasMany(Products::class,'category_id');
    }

    public function getMetaTitleAttribute()
    {
        return ucfirst($this->name)." Discount Offers - CN Deals & Coupons";
    }

    public function getMetaDescriptionAttribute()
    {
        return  "Best ".ucfirst($this->name)." coupons and discounts | Find the best discount deals on ".ucfirst($this->name)." at CN Deals and Coupons.";
    }

    public function getFullHeadingAttribute()
    {
        return ucfirst($this->name).' Discounts & Deals ';
    } 
}
