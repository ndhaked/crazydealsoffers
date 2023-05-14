<?php

namespace Modules\Advertisements\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Support\Facades\Storage;

class Advertisements extends Model
{
    use SoftDeletes,Sluggable,SluggableScopeHelpers;
    use Sortable;

    public $table = 'advertisements';

    protected $fillable = [
        'slug',
        'page',
        'image',
        'advertisement_link',
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
        return Storage::disk('s3')->url('images/advertisements/'.$this->image);
    }
}
