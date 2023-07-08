<?php

return [

    /*
    |--------------------------------------------------------------------------
    | custom data records
    |--------------------------------------------------------------------------
    |
    | Laravel's queue API supports an assortment of back-ends via a single
    | API, giving you convenient access to each back-end using the same
    | syntax for every one. Here you may define a default connection.
    |
    */
    'default_date_formate' => 'm/d/Y',
    'backed_campaign_formate' => 'M d, Y',
    'default_date_time_formate' => 'm/d/Y h:i A',
    'default_time_formate' => 'h:i A',
    
    'filter_project_status'  => array('daily' => 'Daily', 'monthly' => 'Monthly','yearly' => 'Yearly'),
    'project_status'  => array('pending' => 'Pending', 'approved' => 'Approved','declined' => 'Declined'),
    'advertisment_page_options'  => array('home' => 'Home','category'=>'Category', 'favorite'=>'Favorite'),
    'deal_tags'  => array('Today Only!' => 'Today Only!','Expired!' => 'Expired!','FREE!' => 'FREE!','Hot Deal!' => 'Hot Deal!','Stock Up!' => 'Stock Up!','N/A' => 'N/A'),
    'deal_tags_color'  => array('Today Only!' => 'today_only.svg','Expired!' => 'expired.svg','FREE!' => 'free.svg','Hot Deal!' => 'hot_deal.svg','Stock Up!' => 'stock_up.svg'),

    'currency-sign' => env('CURRENCY_SIGN', '₦'),
    'image-upload-on' => env('IMAGE_UPLOAD_ON', 'local'), //local,s3

    'token-expire-code' => 433,
];