<?php

namespace Modules\Products\Entities;

use Illuminate\Database\Eloquent\Model;


class LikeDislikes extends Model
{
    protected $table = "like_dislikes";

    protected $fillable = ['product_id','user_id', 'like', 'dislike','device_uid'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at','updated_at'
    ];


    /*public function setCreatedAtAttribute()
    {
        $this->attributes['created_at'] = utctodtc_4now();
    }

    public function setUpdatedAtAttribute()
    {
        $this->attributes['updated_at'] = utctodtc_4now();
    }*/
    
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
