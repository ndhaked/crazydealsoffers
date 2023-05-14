<?php

namespace Modules\Notifications\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Products\Entities\Products;

class FcmNotification extends Model
{

    public $table = 'fcm_notification';

    protected $fillable = [
        'title',
        'message',
        'status',
        'product_id',
    ];

    protected $dates = [ 'deleted_at' ];

    public function setCreatedAtAttribute()
    {
        $this->attributes['created_at'] = utctodtc_4now();
    }

    public function setUpdatedAtAttribute()
    {
        $this->attributes['updated_at'] = utctodtc_4now();
    }
  
    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }
}
