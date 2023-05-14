<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Products\Entities\Products;
use Edujugon\PushNotification\PushNotification;
use App\Models\User;
use Kyslik\ColumnSortable\Sortable;

class PushNotifications extends Model
{
    use Sortable;
    
    protected $table = "user_notifications";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['userId','title','body','type','product_id','usertype','notification_type','comment_id','is_read','is_read_date'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at','updated_at'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'userId');
    }

    public function comment()
    {
        return $this->belongsTo('Modules\Products\Entities\ProductComment', 'comment_id');
    }

    public function product()
    {
        return $this->belongsTo(Products::class,'product_id');
    }

    public function productwithTrashed()
    {
        return $this->belongsTo(Products::class,'product_id')->withTrashed();
    }

    public function getCreatedAtTimeAttribute()
    {
        return \Carbon\Carbon::createFromTimestamp(strtotime($this->created_at))
            ->timezone('UTC');
            //->toDateTimeString(); //remove this one if u want to return Carbon object
    }

    public function sendPushNotificationForAllUsers($product)
     { 
        $record =  static::create([
            'title' => $product['title'],
            'body'  => $product['body'],
            'type'  => 'alluser',
            'product_id'  => $product['id'],
         ]);

        $tokens = User::whereNotNull('device_token')->pluck('device_token')->toArray();
        $push = \PushNotification::setService('fcm')
                ->setMessage([
                     'notification' => [
                             'title' => $product['title'],
                             'body'  =>  $product['body'],
                             'sound' => 'default'
                             ],
                       'data' => [
                         'product_id' => $product['id'],
                         'product_slug' => $record->product->slug,
                         'type' => $record->type
                         ]
                     ])
                ->setApiKey(env('FCM_SERVER_KEY'))
                ->setDevicesToken($tokens)
                ->send()
                ->getFeedback();
        User::whereNotNull('device_token')->update(array('is_unread' => 1));
     } 
}
