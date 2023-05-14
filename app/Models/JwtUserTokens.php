<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JwtUserTokens extends Model
{
    protected $table = "jwt_user_tokens";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id','token'];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
