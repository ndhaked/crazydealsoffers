<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Model;

class GuestUsers extends Model
{
    protected $table = "guest_users";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'device_token','device_uid','is_unread','created_at','notification_status'
    ];

    public function setCreatedAtAttribute()
    {
        $this->attributes['created_at'] = utctodtc_4now();
    }
}
