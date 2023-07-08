<?php

namespace Modules\AdvertiseAffiliated\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdvertiseAffiliated extends Model
{
    use HasFactory;
    use SoftDeletes,Sluggable,SluggableScopeHelpers;
    use Sortable;

    public $table = 'advertise_affiliated';

    protected $fillable = [
        'slug',
        'title',
        'banner_image',
        'banner_description',
        'image_1',
        'description_1',
        'image_2',
        'description_2',
        'description',
        'status',
    ];
    

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'page',
                'onUpdate'=>false
            ]
        ];
    }

    protected $dates = [ 'deleted_at' ];

    public $sortable = ['id','page','created_at'];
    
    public function setCreatedAtAttribute()
    {
        $this->attributes['created_at'] = utctodtc_4now();
    }

    public function setUpdatedAtAttribute()
    {
        $this->attributes['updated_at'] = utctodtc_4now();
    }
    
    public function getS3UrlAttribute()
    {
        return Storage::disk('s3')->url('images/advertise_affiliated/'.$this->banner_image);
    }

    public function getS3UrlImage2Attribute()
    {
        return ($this->image_1) ? Storage::disk('s3')->url('images/advertise_affiliated/'.$this->image_1) : "";
    }

    public function getS3UrlImage3Attribute()
    {
        return ($this->image_2) ? Storage::disk('s3')->url('images/advertise_affiliated/'.$this->image_2) : "";
    }

    public function getMetaTitleAttribute()
    {
        if($this->slug == 'affiliate'){
            return "Affiliate Disclosure - Crazy Deals coupons & Offers";
        }
        return 'Advertise With Us - Crazy Deals coupons & Offers';
    }

    public function getMetaDescriptionAttribute()
    {
        if($this->slug == 'affiliate'){
            return "Our platform may contain links to affiliate websites, but please rest assured that our primary goal is to provide you with the best possible deals and savings opportunities.";
        }
        return "At Crazy Deals Coupons & Offers, we take pride in connecting businesses with our unique audience. With our platform, The Local, we offer a comprehensive commercial portfolio that includes various advertising opportunities to suit your needs, ranging from sponsored content and more.";
    } 
}
