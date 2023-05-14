<?php

namespace App\Http\Controllers;

use App\Logic\Providers\FacebookRepository;
use DB;
use WaleedAhmad\Pinterest\Pinterest;

class SocialController extends Controller
{
    protected $facebook;

    public function __construct()
    {
        $this->facebook = new FacebookRepository();
        $this->imagesEndpointFormat = 'https://graph.facebook.com/v5.0/{ig-user-id}/media?image_url={image-url}&caption={caption}&access_token={access-token}';
    }

    public function redirectToProvider()
    {
        return redirect($this->facebook->redirectTo());
    }

    public function handleProviderCallback()
    {
		

      //  if (request('error') == 'access_denied') 
            //handle error

        $accessToken = $this->facebook->handleCallbackCustom(); 
        echo "<h1>Facebook Access Token Updated in database.</h1>";
		echo "<h1><a href='".config('app.url')."admin/products'>Continue to Products</a></h1>";die;
	
    }
    public function socialLogin(){
        $permissions = [
            "instagram_basic","instagram_content_publish","pages_read_engagement"
            
        ];

        $instagram_redirect_uri="https://api.instagram.com/oauth/authorize?client_id=".env('INSTAGRAM_APP_ID')."&redirect_uri=".config('app.url') .'/ig-redirect-uri'."&scope=".implode(',',$permissions)."&response_type=code";
         
        $pinterest=new Pinterest(env('PINTEREST_KEY'),env('PINTEREST_SERCRET'));
        $pinterest_redirect_uri=$pinterest->auth()->getLoginUrl(config('app.url') .'admin/pi-redirect-uri', array('boards:read','pins:read'));
        $pinterest_redirect_uri="https://www.pinterest.com/oauth/?client_id=1476318&redirect_uri=".config('app.url') .'admin/pi-redirect-uri'."&response_type=code&scope=pins:read,pins:write,boards:read,boards:write";
        
        return view('products::social_login')->with(compact('instagram_redirect_uri','pinterest_redirect_uri'));
    }
    function makeApiCall( $endpoint, $type, $params ) {
		$ch = curl_init();

		if ( 'POST' == $type ) {
			curl_setopt( $ch, CURLOPT_URL, $endpoint );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $params ) );
			curl_setopt( $ch, CURLOPT_POST, 1 );
		} elseif ( 'GET' == $type ) {
			curl_setopt( $ch, CURLOPT_URL, $endpoint . '?' . http_build_query( $params ) );
		}

		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

		$response = curl_exec( $ch );
		curl_close( $ch );

		return json_decode( $response, true );
	}
    function publishInstagramImage(){

        $social_token = DB::table('social_tokens')->where('planform', 'facebook')->first();
        $accessToken=$social_token->token;
    
	$imageMediaObjectEndpoint = 'https://graph.facebook.com/v13.0/' .env('INSTAGRAM_ACCOUNT_ID'). '/media';
	$imageMediaObjectEndpointParams = array( // POST 
		'image_url' => 'http://justinstolpe.com/sandbox/ig_publish_content_img.png',
		'caption' => 'This image was posted through the Instagram Graph API with a script I wrote! Go check out the video tutorial on my YouTube channel.
			.
			youtube.com/justinstolpe
			.
			#instagram #graphapi #instagramgraphapi #code #coding #programming #php #api #webdeveloper #codinglife #developer #coder #tech #developerlife #webdev #youtube #instgramgraphapi
		',
		'access_token' => $accessToken
	);
	$imageMediaObjectResponseArray = $this->makeApiCall( $imageMediaObjectEndpoint, 'POST', $imageMediaObjectEndpointParams );
    //pr($imageMediaObjectResponseArray); die;
	// set status to in progress
	$imageMediaObjectStatusCode = 'IN_PROGRESS';

	while( $imageMediaObjectStatusCode != 'FINISHED' ) { echo "IN-Progress....<br>"; // keep checking media object until it is ready for publishing
		$imageMediaObjectStatusEndpoint = 'https://graph.facebook.com/v13.0/' . $imageMediaObjectResponseArray['id'];
		$imageMediaObjectStatusEndpointParams = array( // endpoint params
			'fields' => 'status_code',
			'access_token' => $accessToken
		);
		$imageMediaObjectResponseArray = $this->makeApiCall( $imageMediaObjectStatusEndpoint, 'GET', $imageMediaObjectStatusEndpointParams );
		$imageMediaObjectStatusCode = $imageMediaObjectResponseArray['status_code'];
		sleep( 5 );
	}

	// publish image
	$imageMediaObjectId = $imageMediaObjectResponseArray['id'];
	$publishImageEndpoint = 'https://graph.facebook.com/v5.0/' .env('INSTAGRAM_ACCOUNT_ID') . '/media_publish';
	$publishEndpointParams = array(
		'creation_id' => $imageMediaObjectId,
		'access_token' => $accessToken
	);
	$publishImageResponseArray = $this->makeApiCall( $publishImageEndpoint, 'POST', $publishEndpointParams );
	
    }
    public function piRedirectUri(){
        $pinterest=new Pinterest(env('PINTEREST_KEY'),env('PINTEREST_SERCRET'));
        $token = $pinterest->auth()->getOAuthToken($_GET["code"]);
        DB::table('social_tokens')
        ->where('planform', 'pinterest')
        ->update(['token' => $token->access_token, 'expiry' =>Date('Y-m-d', strtotime('+58 days'))]);
        echo "<h1>Pinterest Access Token Updated in database.";
	}
    public function postPinterestPin(){
        $apiEndPoint="https://api.pinterest.com/v5/pins";
        $params=[
        'link'=>null,   
        'title'=>"Product Title Testing",
        "description"=>'test',
        "alt_text"=>'test',
        "board_id"=>"184788459649796637",
        "board_section_id"=>null,
        "media_source"=>[
            "source_type"=>"image_url",
            "url"=>"https://cndealsdemo.s3.us-west-2.amazonaws.com/images/products/1646381848.jpg"
        ]];
        //echo json_encode($params);die;
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $apiEndPoint );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($params) );
		curl_setopt( $ch, CURLOPT_HTTPHEADER,['Authorization: Bearer pina_AEA55BQWABGQ6AAAGCAJAAXRWLUYO7YBACGSORRCCU5G3UKYN7RO6RTMNH47QNS5ZUQKMFMM5AJOCBHKFFEJNUAIYBDCLIQA','Content-Type: application/json']);
		curl_setopt( $ch, CURLOPT_POST, 1 );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		$response = curl_exec( $ch );
		curl_close( $ch );
        $accessTokenInfo=json_decode( $response, true );
        pr($accessTokenInfo);die;
    }
    public function getInstagramAccountId(){

		$social_token = DB::table('social_tokens')->where('planform', 'facebook')->first();
        $accessToken=$social_token->token;
        // get instagram account id endpoint
	$pageId="545781699682971"; //facebook page id
	$instagramAccountEndpoint = 'https://graph.facebook.com/v5.0/' . $pageId;

	// endpoint params
	$igParams = array(
		'fields' => 'instagram_business_account',
		'access_token' =>$accessToken
	);

	// add params to endpoint
	$instagramAccountEndpoint .= '?' . http_build_query( $igParams );

	// setup curl
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $instagramAccountEndpoint );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

	// make call and get response
	$response = curl_exec( $ch );
	curl_close( $ch );
	$responseArray = json_decode( $response, true );
    pr($responseArray);die;
    }
    
}

?>