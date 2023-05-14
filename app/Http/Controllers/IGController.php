<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use WaleedAhmad\Pinterest\Pinterest;


class IGController extends Controller
{

	public $authData;

	public $auth_table = 'ig_auth_data';

	public function __construct(){

	}

	public function generateIGToken($code){

		$ig_atu = "https://api.instagram.com/oauth/access_token";

		$ig_data = [];

		$ig_data['client_id'] = env('INSTAGRAM_APP_ID');//replace with your Instagram app ID

		$ig_data['client_secret'] = env('INSTAGRAM_SECRECT'); //replace with your Instagram app secret

		$ig_data['grant_type'] = 'authorization_code';

		$ig_data['redirect_uri'] = config('app.url'). '/ig-redirect-uri'; //create this redirect uri in your routes web.php file

		$ig_data['code'] = str_replace('#_','',$code); //this is the code you received in step 1 after app authorization

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $ig_atu);

		curl_setopt($ch, CURLOPT_POST, 1);

		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($ig_data));

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$ig_auth_data = curl_exec($ch);

		curl_close ($ch);

		$ig_auth_data = json_decode($ig_auth_data, true);
       
		if (!isset($ig_auth_data['error_message'])) {
           
			$this->authData['access_token'] = $ig_auth_data['access_token'];

			$this->authData['user_id'] = $ig_auth_data['user_id'];


		
            DB::table('social_tokens')
            ->where('planform', 'instagram')
            ->update(['token' => $this->authData['access_token'], 'expiry' =>date('Y-m-d', strtotime('+58 days'))]);

		}

	}


	public function refreshIGToken($short_access_token){

		$client_secret = "63a0fad8218c422116c7f861fbf683a0"; //replace with your Instagram app secret

		$ig_rtu = 'https://graph.instagram.com/access_token?grant_type=ig_exchange_token&client_secret='.$client_secret.'&access_token='.$short_access_token;

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $ig_rtu);
		
		curl_setopt($ch, CURLOPT_HEADER, 0);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$ig_new = curl_exec($ch);

		curl_close ($ch);

		$ig_new = json_decode($ig_new, true);

		if (!isset($ig_new['error'])) {

			$this->authData['access_token'] = $ig_new['access_token'];

			$this->authData['expires_in'] = $ig_new['expires_in'];

			DB::table('social_tokens')
			->where('planform', 'instagram')
			->update([
				'token'	=> $ig_new['access_token'],
				'expiry'	=> $ig_new['expiry'],
				
			]);

		}

	}


	public function getMedia(){

		/*check token available and valid*/

		$igData = DB::table('social_tokens')->where('planform', 'instagram');

		if ($igData->count() > 0) {

			$igDataResult = $igData->first();

			$curTimeStamp = time();

			if (($curTimeStamp-$igDataResult->valid_till) >= $igDataResult->expires_in) {
				
				$this->refreshIGToken($igDataResult->access_token);

			}else{

				$this->authData['access_token'] = $igDataResult->access_token;
				$this->authData['user_id'] = $igDataResult->user_id;

			}

		}else{
			$this->generateIGToken();
		}

		/*check token available and valid*/

		$ig_graph_url = 'https://graph.instagram.com/me/media?fields=id,media_type,media_url,username,timestamp,caption&access_token='.$this->authData['access_token'];

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $ig_graph_url);
		
		curl_setopt($ch, CURLOPT_HEADER, 0);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$ig_graph_data = curl_exec($ch);

		curl_close ($ch);

		$ig_graph_data = json_decode($ig_graph_data, true);

		$ig_photos = [];

		if (!isset($ig_graph_data['error'])) {

			foreach ($ig_graph_data['data'] as $key => $value) {

				if ($value['media_type'] == 'IMAGE') {
					$ig_photos[] = $value['media_url'];
				}
				
			}

		}

		//use this if want json response
		//return response()->json($igPhotos);

		return $igPhotos;

	}

	public function igRedirectUri(){
        $this->generateIGToken($_REQUEST['code']);
        echo "<h1>Instagram Access Token Updated in database.";
	}
    public function piRedirectUri(){ 
        $code=$_REQUEST['code'];
        $piApiEndPoint="https://api.pinterest.com/v5/oauth/token";
        $params=['code'=>$code,'redirect_uri'=>config('app.url') .'admin/pi-redirect-uri','grant_type'=>'authorization_code'];
        

        //getting long lived access token
            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $piApiEndPoint );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $params ) );
			curl_setopt( $ch, CURLOPT_HTTPHEADER,['Authorization: Basic '.base64_encode(env('PINTEREST_APP_ID').':'.env('PINTEREST_APP_SECRET'))]);
			curl_setopt( $ch, CURLOPT_POST, 1 );
            curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
		    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		    $response = curl_exec( $ch );
		    curl_close( $ch );
            $accessTokenInfo=json_decode( $response, true );
			
            $expiry = date("Y-m-d", time() + $accessTokenInfo["expires_in"]);
            $accessToken=$accessTokenInfo["access_token"];
            $refreshToken=$accessTokenInfo["refresh_token"];
            
        DB::table('social_tokens')
        ->where('planform', 'pinterest')
        ->update(['token' =>$accessToken , 'refresh_token' =>$refreshToken ,'expiry' =>$expiry]);
        echo "<h1>Pinterest Access Token Updated in database.</h1>";
		echo "<h1><a href='".config('app.url')."admin/products'>Continue to Products</a></h1>";die;
	}
	public function deauthorize(){
		
	}
	public function dataDelete(){
		
	}

   
}