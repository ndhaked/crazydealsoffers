<?php
namespace Modules\Configuration\Entities;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
	use Sluggable,SoftDeletes,SluggableScopeHelpers;

	protected $table = "configuration";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['slug', 'config_title', 'config_value', 'created_at', 'updated_at'];
    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'config_title',
                'onUpdate'=>false
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
}
