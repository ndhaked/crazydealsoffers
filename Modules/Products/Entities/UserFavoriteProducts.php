<?php

namespace Modules\Products\Entities;

use Illuminate\Database\Eloquent\Model;

class UserFavoriteProducts extends Model
{
    protected $table = "user_favorite_products";

  	protected $fillable = ['product_id','user_id','device_uid'];

    public function setCreatedAtAttribute()
    {
        $this->attributes['created_at'] = utctodtc_4now();
    }

    public function setUpdatedAtAttribute()
    {
        $this->attributes['updated_at'] = utctodtc_4now();
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function products()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }
}
