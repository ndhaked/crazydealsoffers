<?php

namespace Modules\Blogs\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Support\Facades\Storage;

class Blogs extends Model
{
    use SoftDeletes,Sluggable,SluggableScopeHelpers;
    use Sortable;

    public $table = 'blogs';

    protected $fillable = [
        'slug',
    	'title',
        'description',
        'image_1',
        'image_2',
        'video_link',
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
                'source' => 'title',
                'onUpdate'=>false
            ]
        ];
    }

    protected $dates = [ 'deleted_at' ];
    
    public $sortable = ['id','title','created_at'];

    public function getMetaTitleAttribute()
    {
        return $this->title.' - OLE Rooms';
    }
    
    public function getS3UrlAttribute()
    {
        return Storage::disk('s3')->url('images/blogs/'.$this->image_1);
    }

    public function getS3UrlImage2Attribute()
    {
        return ($this->image_2) ? Storage::disk('s3')->url('images/blogs/'.$this->image_2) : "";
    }

}
