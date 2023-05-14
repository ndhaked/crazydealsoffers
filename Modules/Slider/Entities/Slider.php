<?php

namespace Modules\Slider\Entities;

use URL;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Support\Facades\Storage;

class Slider extends Model
{
    use SoftDeletes,Sluggable,SluggableScopeHelpers;
    use Sortable;

	protected $table = "slider";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug','title','description','slider_order','banner_image','url','status','created_at'
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
                'source' => 'title',
                'method'=>function ($string, $separator) {
                        return md5(microtime());
                    },
                'onUpdate'=> false
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

	//Getting picture path
	// public function getPicturePathAttribute()
    // {
    //     return ($this->banner_image) ? URL::to('storage/app/public/slider/'.$this->banner_image) : NULL;
    // }

    public function getS3UrlAttribute()
    {
        return Storage::disk('s3')->url('images/slider/'.$this->banner_image);
    }
}
