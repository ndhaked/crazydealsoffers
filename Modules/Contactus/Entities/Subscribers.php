<?php

namespace Modules\Contactus\Entities;

use Illuminate\Database\Eloquent\Model;

class Subscribers extends Model
{
    protected $table = "subscribers";

  	protected $fillable = ['name','email','created_at'];
    
}
