<?php

namespace Modules\Products\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CommentTags extends Model
{
    protected $table = "comment_tags";

    protected $fillable = ['user_id','comment_id','username','updated_at', 'created_at'];

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
