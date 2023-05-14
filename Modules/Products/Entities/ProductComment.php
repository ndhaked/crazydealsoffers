<?php

namespace Modules\Products\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class ProductComment extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = ['user_id', 'product_id', 'parent_id', 'comment','device_uid'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at','updated_at','deleted_at'
    ];

    /*public function setCreatedAtAttribute()
    {
        $this->attributes['created_at'] = utctodtc_4now();
    }

    public function setUpdatedAtAttribute()
    {
        $this->attributes['updated_at'] = utctodtc_4now();
    }*/

    /**
     * The belongs to Relationship
     *
     * @var array
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getPublisherNameAttribute()
    {
        return ($this->user) ? ucfirst($this->user->name) : 'Guest User';
    }

    public function getPublisherImageAttribute()
    {
        return ($this->user) ? $this->user->S3Url : onerrorReturnImage();
    }

    public function getIsAdminWithRoleAttribute()
    {   if($this->user){
            if($this->user->hasRole('admin') || $this->user->hasRole('subadmin')){
                return 'Admin';
             }
        }
        return NULL;
    }

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }

    /**
     * The has Many Relationship
     *
     * @var array
     */
    public function replies()
    {
        return $this->hasMany(ProductComment::class, 'parent_id')->orderBy('id','desc');
    }

    public function userIsReplyOnComment()
    {
       return $this->hasOne(ProductComment::class, 'parent_id')->where('user_id',auth()->user()->id);
    }   

    public function getIsReplyOnCommentAttribute()
    {
        return (auth()->user()) ? ($this->userIsReplyOnComment) ? true : false : false;
    } 

    public function GuestIsReplyOnComment($parent_id,$device_uid=NULL)
    {
        $isExists = ProductComment::where('device_uid',$device_uid)->where('parent_id',$parent_id)->exists();
        return ($isExists) ? true : false ;
    }

     // Likes
    public function likes(){
        return (int) $this->hasMany(CommentsLikeDislikes::class,'comment_id')->sum('like');
    }
    // Dislikes
    public function dislikes(){
        return (int) $this->hasMany(CommentsLikeDislikes::class,'comment_id')->sum('dislike');
    }

    public function UserLikeComment()
    {
        return $this->hasOne(CommentsLikeDislikes::class,'comment_id')->where('like',1)->where('user_id',auth()->user()->id);
    }

    public function getIsUserLikeAttribute()
    {
        return (auth()->user()) ? ($this->UserLikeComment) ? true : false : false;
    }

    public function GuestIsLikeOnComment($comment_id,$device_uid=NULL)
    {
        $isExists = CommentsLikeDislikes::where('device_uid',$device_uid)->where('comment_id',$comment_id)->where('like',1)->exists();
        return ($isExists) ? true : false ;
    }

    public function UserDisLikeComment()
    {
        return $this->hasOne(CommentsLikeDislikes::class,'comment_id')->where('dislike',1)->where('user_id',auth()->user()->id);
    }

    public function getIsUserDisLikeAttribute()
    {
        return (auth()->user()) ? ($this->UserDisLikeComment) ? true : false : false;
    }

    public function GuestIsDisLikeOnComment($comment_id,$device_uid=NULL)
    {
        $isExists = CommentsLikeDislikes::where('device_uid',$device_uid)->where('comment_id',$comment_id)->where('dislike',1)->exists();
        return ($isExists) ? true : false ;
    }

    public function tags()
    {
        return $this->hasMany(CommentTags::class, 'comment_id');
    }
}
