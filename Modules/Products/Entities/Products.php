<?php

namespace Modules\Products\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Support\Facades\Storage;
use App\Models\PushNotifications;

class Products extends Model
{
    use SoftDeletes,Sluggable,SluggableScopeHelpers;
    use Sortable;

    public $table = 'products';

    protected $fillable = [
        'slug',
    	'name',
        'category_id',
        'coupon_code',
        'image',
        'price',
        'off_on_product',
        'expiry_date',
        'item_purchase_link',
        'description',
        'tag',
        'delete_status',
        'deal_of_the_day',
        'status',
        'user_id',
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name',
                'onUpdate'=>false
            ]
        ];
    }

    public $sortable = ['id','name','coupon_code','created_at'];

    protected $dates = [ 'deleted_at','expiry_date'];

    public function setCreatedAtAttribute()
    {
        $this->attributes['created_at'] = utctodtc_4now();
    }

    public function setUpdatedAtAttribute()
    {
        $this->attributes['updated_at'] = utctodtc_4now();
    }

    public function setDeletedAtAttribute($value)
    {
        $this->attributes['deleted_at'] = utctodtc_4now();
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function getPublisherNameAttribute()
    {
        return ucfirst($this->user->name);
    }

    public function getPublisherImageAttribute()
    {
        return $this->user->S3Url;
    }

    public function getS3UrlAttribute()
    {
        return Storage::disk('s3')->url('images/products/'.$this->image);
    }

    public function user_favorite_products()
   {
       return $this->hasMany(UserFavoriteProducts::class, 'product_id');
   }  

   public function userIsFavProduct()
   {
       return $this->hasOne(UserFavoriteProducts::class, 'product_id')->where('user_id',auth()->user()->id);
   }   

   public function getIsFavProductAttribute()
   {
        return (auth()->user()) ? ($this->userIsFavProduct) ? true : false : false;
   } 

   public function GuestIsFavProduct($product_id,$device_uid=NULL)
   {
        $isExists = UserFavoriteProducts::where('device_uid',$device_uid)->where('product_id',$product_id)->exists();
        return ($isExists) ? true : false ;
   } 

   public function userIsCommentProduct()
   {
       return $this->hasOne(ProductComment::class, 'product_id')->where('user_id',auth()->user()->id);
   }   

   public function getIsCommentProductAttribute()
   {
        return (auth()->user()) ? ($this->userIsCommentProduct) ? true : false : false;
   } 

   public function GuestIsCommentProduct($product_id,$device_uid=NULL)
   {
        $isExists = ProductComment::where('device_uid',$device_uid)->where('product_id',$product_id)->exists();
        return ($isExists) ? true : false ;
   } 

    public function category()
   {
       return $this->belongsTo('Modules\Categories\Entities\Categories', 'category_id');
   }

   // Likes
    public function likes(){
        return (int) $this->hasMany(LikeDislikes::class,'product_id')->sum('like');
    }
    // Dislikes
    public function dislikes(){
        return (int) $this->hasMany(LikeDislikes::class,'product_id')->sum('dislike');
    }

    public function UserLikeProduct()
    {
        return $this->hasOne(LikeDislikes::class,'product_id')->where('like',1)->where('user_id',auth()->user()->id);
    }

    public function getIsUserLikeAttribute()
    {
        return (auth()->user()) ? ($this->UserLikeProduct) ? true : false : false;
    } 

     public function UserDisLikeProduct()
    {
        return $this->hasOne(LikeDislikes::class,'product_id')->where('dislike',1)->where('user_id',auth()->user()->id);
    }

    public function getIsUserDisLikeAttribute()
    {
        return (auth()->user()) ? ($this->UserDisLikeProduct) ? true : false : false;
    }

    public function getMetaTitleAttribute()
    {
        return ucfirst($this->name).' at a discounted price of $'.number_format($this->price,2);
    }

    public function getMetaDescriptionAttribute()
    {
        return 'Hurry up Get a great deal at CN Deals and Coupons on '.$this->MetaTitle;
    }

    public function comments()
    {
        return $this->hasMany(ProductComment::class,'product_id')->whereNull('parent_id')->whereNotNull('user_id')->orderBy('id','desc');
    }

    public function commentsLatestTwo()
    {
        return $this->hasMany(ProductComment::class,'product_id')->whereNull('parent_id')->whereNotNull('user_id')->orderBy('id','desc')->limit(2);
    }

    public function notifications()
    {
        return $this->hasMany(PushNotifications::class,'product_id');
    }
}
