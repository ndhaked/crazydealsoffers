<?php

namespace Modules\Products\Entities;

use Illuminate\Database\Eloquent\Model;

class CommentsLikeDislikes extends Model
{
    protected $table = "comments_like_dislikes";

    protected $fillable = ['user_id', 'comment_id', 'product_id', 'like', 'updated_at', 'created_at','like', 'dislike','device_uid'];

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

    public function comment()
    {
        return $this->belongsTo(ProductComment::class, 'comment_id');
    }
}
