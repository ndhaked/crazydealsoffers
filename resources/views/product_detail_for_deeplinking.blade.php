<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="no-js" style="display: block;">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name='description' itemprop='description' content='{!! strip_tags($product->description) !!}' />
    <meta property='product:published_time' content='{{$product->created_at}}' />
    <meta property='product:section' content='product' />
    <meta property="og:description" content="{!! strip_tags($product->description) !!}" />
    <meta property="og:title" content="{{$product->name}}" />
    <meta property="og:url" content="{{url()->current()}}" />
    <meta property="og:type" content="product" />
    <meta property="og:locale" content="en-us" />
    <meta property="og:locale:alternate" content="en-us" />
    <meta property="og:site_name" content="{{env('APP_NAME', 'CN Deals & Coupons')}}" />
    <meta property="og:image" content="{{$product->S3Url}}" />

    <meta property="og:image:url" content="{{$product->S3Url}}" />
    <meta property="og:image:size" content="300" />

    <meta name="twitter:card" content="summary" />
    <meta name="twitter:title" content="{{$product->name}}" />

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/favicon-32x32.png') }}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Scripts -->
</head>
<body>
<?php
$isMobile = false;
$isAndroid = false;
$isIos = false;
$IosUrl = 'https://apps.apple.com/us/app/cn-deals-and-coupons/id1582437492';
$AndroidUrl = 'market://details?id=com.dealscoupons';
$AndroidUrlWeb = 'https://play.google.com/store/apps/details?id=com.dealscoupons';
$websiteTurl = env('APP_URL');


$iPod = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");
$iPhone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
$iPad = strpos($_SERVER['HTTP_USER_AGENT'],"iPad");
$android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
    
if($iPad||$iPhone||$iPod || $android){
    $isMobile = true;
}   

if($iPad||$iPhone||$iPod){
    $isIos = true;
}elseif($android){
    $isAndroid = true;
}  

if( $isMobile ){
  
    if( !empty($product->id) && $product->expiry_date > utctodtc_4now() ){ 
         $urlApp = "cndealsandcoupons://DealDetail/".$product->slug."/".$product->id."/push";
    }else{
        $urlApp = "cndealsandcoupons://Home"; // so goes to app if exist
    }
 ?>
    <script> 
        var now = new Date().valueOf();  
    </script>  
<?php  if( $isAndroid ){ ?> 
        <script> 
            setTimeout(function () {
                 if (new Date().valueOf() - now > 500) return;
                 window.location.replace("<?php echo $AndroidUrl ?>");  
            }, 100);  
            window.location.replace("<?php echo $urlApp; ?>" );  
        </script>  
<?php }elseif( $isIos ){ ?>
    <!-- <script>
        setTimeout(function () {
            if (new Date().valueOf() - now > 500) return;
             window.location.replace("<?php echo $IosUrl ?>");  
        }, 100); 
        window.location.replace("<?php echo $urlApp; ?>" );   
    </script>   -->
    <script>
        window.location.replace("<?php echo $urlApp; ?>");
        setTimeout(() => {
            window.location.href =  "<?php echo $IosUrl ?>";
        }, 3000);
    </script>
<?php } 
}else{ ?>
    <script>
      window.location.replace("<?php echo $websiteTurl ?>");  
    </script>  
<?php } ?>
</body>
</html>
