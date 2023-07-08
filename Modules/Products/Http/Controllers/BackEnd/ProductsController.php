<?php

namespace Modules\Products\Http\Controllers\BackEnd;

use Session;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use Modules\Products\Http\Requests\CreateProductsRequest;
use Modules\Products\Http\Requests\ProductMediaRequest;
use Modules\Products\Http\Requests\UpdateProductsRequest;
use Modules\Products\Http\Requests\ProductCSVRequest;
use Modules\Products\Repositories\ProductsRepositoryInterface as ProductRepo;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AllProductsExport;
use App\Imports\ProductsImport;

use App\Logic\Providers\FacebookRepository;
use DB;

class ProductsController extends Controller
{
    protected $model = 'Products';
    protected $facebook;
    public function __construct(ProductRepo $ProductRepo)
    {
        $this->middleware(['ability','auth'], ['except' => [
            'product.uploadcsv','product.exportcsv','product.importcsv','product.deal_of_the_day','product.samplecsv','getSuggessionDeals'
        ]]);
        $this->ProductRepo = $ProductRepo;
        $this->facebook = new FacebookRepository();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $products = $this->ProductRepo->getAll($request);
        return view('products::index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $token_error=[];
        //getting validity of facebook and pinterest token
        $facebook_token_validity =DB::table('social_tokens')->where('planform', 'facebook')->first();
        if($facebook_token_validity->expiry<date('Y-m-d')){
            $token_error[]="Facebook/Instagram token"; 
        }
        $pinterest_token_validity =DB::table('social_tokens')->where('planform', 'pinterest')->first();
        if($pinterest_token_validity->expiry<date('Y-m-d')){
            $token_error[]="Pinterest token"; 
        }
        if(count($token_error)>0){
          //Session::flash('error', implode(' and ',$token_error)." expired please renew."); 
          //return redirect()->route('product.index');
        }
        //$groupsList=$this->facebook->getGroupsList($facebook_token_validity->token);
    		$groupsList=[
        		['id'=>425474048538804,'name'=>'Amazing Deals, Coupons & Codes! By: CN Deals & Coupons!'],
        		['id'=>962654337604810,'name'=>'CN Deals, Coupons & Promo Codes!']
    		];
        $categories = $this->ProductRepo->getCategories();
        $platforms  =    $this->ProductRepo->getPlatforms();
        /*$pages=$this->getFacebookPages();
        $pagesInfo=[];
        foreach($pages as $page){
            $pagesInfo[$page->id]=$page->name;
        } */
    		$pagesInfo=[
    		  545781699682971 => 'CN Deals'
    		];
        return view('products::create', compact('categories','platforms','pagesInfo','groupsList'));
    }

    private function getFacebookPages(){
        //getting facebook pages list
        $social_token = DB::table('social_tokens')->where('planform', 'facebook')->first();
        $accessToken=$social_token->token;

       $curl = curl_init();

       curl_setopt_array($curl, array(
       CURLOPT_URL => "https://graph.facebook.com/me/accounts",
       CURLOPT_RETURNTRANSFER => true,
       CURLOPT_ENCODING => "",
       CURLOPT_MAXREDIRS => 10,
       CURLOPT_TIMEOUT => 30,
       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
       CURLOPT_CUSTOMREQUEST => "GET",
       CURLOPT_POSTFIELDS => "link=&title=&description=&alt_text=&board_id=&board_section_id=&media_source=",
       CURLOPT_HTTPHEADER => array(
           "authorization: Bearer $accessToken",
           "cache-control: no-cache",
           "content-type: application/x-www-form-urlencoded",
           "postman-token: 81bcda10-b2d1-500e-2775-ed5100fdc558"
       ),
       ));

       $response = curl_exec($curl);
       $err = curl_error($curl);

       curl_close($curl);

       if ($err) {
       echo "cURL Error #:" . $err;
       } else {
     $pagesInfo=json_decode($response);      
     return $pagesInfo->data;
       }
}

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(CreateProductsRequest $request)
    {
      //  pr($request->all());

        $same_description_fb_pages=$request->get('same_description');
        $same_description_business=$request->get('same_description_business');
        $facebook_pages=$request->get('facebook_pages');
        $facebook_pages_business=$request->get('facebook_pages_business');
        $facebook_descriptions=$request->get('facebook_description');
        $facebook_description_business=$request->get('facebook_description_business');
        $pinterest_selected=$request->get('pinterest');
        $instagram_selected=$request->get('instagram');
       
        
        $description=$request->get('description');

        $product_name=$request->get('name');
        $coupon_code=$request->get('coupon_code');
        $off_on_product=$request->get('off_on_product');
        $expiry_date=date('j M Y',strtotime($request->get('expiry_date')));
        $item_purchase_link=$request->get('item_purchase_link');

        $post_content="Product: $product_name"."\n"."Coupon Code: $coupon_code"."\n"."You will get $off_on_product off"."\n"."Offer valid till $expiry_date"."\n".$item_purchase_link;
        
             
        // $data=file_get_contents($request->profile_pic->path());
        $response = $this->ProductRepo->store($request);
        
        if($request->ajax()){
            return response()->json($response);
        }
        //posting to facebook
        if($facebook_pages!=null){
        $social_token = DB::table('social_tokens')->where('planform', 'facebook')->first();
        $accessToken=$social_token->token;
        foreach($facebook_pages as $fb_page){
          
          if(isset($same_description_fb_pages[$fb_page]))  
          $fb_post_desciption=$description;
          else
          $fb_post_desciption=$facebook_descriptions[$fb_page]!=''?$facebook_descriptions[$fb_page]:$description;
          
          $fb_post_desciption=str_replace('<br />', "\n", $fb_post_desciption);
          $fb_post_desciption=strip_tags($fb_post_desciption);
          $fb_post_desciption=str_replace('\r\n', "\n", $fb_post_desciption);

        //   $fb_post_content=$post_content."\n".$fb_post_desciption."\n"."***As an Affiliate I earn from qualifying purchases. Prices, coupons and codes are valid at the time of posting and can expire at any time ***";
        //   $fb_post_content=strip_tags($fb_post_content);

          $fb_post_desciption=$fb_post_desciption."\n".$item_purchase_link."\n\n"."***As an Affiliate I earn from qualifying purchases. Prices, coupons and codes are valid at the time of posting and can expire at any time ***";
          
          $fb_post_desciption=str_replace('&nbsp;','',$fb_post_desciption);

          //$post_image_id=$this->facebook->post($fb_page,$accessToken,$fb_post_desciption,[$request->profile_pic->path()]);
          $fb_post_desciption=str_replace('&nbsp;',' ',$fb_post_desciption);
          $fb_post_desciption=str_replace(['&#39;','&#169',"&quot;","&ldquo;","&rdquo;","&zwj;"],["'",'(c)','"','“','”',''],$fb_post_desciption);

          $fb_post_desciption=htmlspecialchars_decode($fb_post_desciption);

         
		   try{
						 $this->facebook->post($fb_page,$accessToken,$fb_post_desciption,[env('AWS_S3_BASE')."images/products/".$request->get('image')]);
				}catch (\Exception $e){
						Session::flash('warning', "Facebook: ".$e->getMessage());
           
				}
         
        }
        }

           //posting to facebook business pages.
           if($facebook_pages_business!=null){
            $social_token = DB::table('social_tokens')->where('planform', 'facebook')->first();
            $accessToken=$social_token->token;
            foreach($facebook_pages_business as $fb_page_bussiness){
              
              if(isset($same_description_business[$fb_page_bussiness]))  
              $fb_post_desciption_business=$description;
              else
              $fb_post_desciption_business=$facebook_description_business[$fb_page_bussiness]!=''?$facebook_description_business[$fb_page_bussiness]:$description;
              
              $fb_post_desciption_business=str_replace('<br />', "\n", $fb_post_desciption_business);
              $fb_post_desciption_business=strip_tags($fb_post_desciption_business);
              $fb_post_desciption_business=str_replace('\r\n', "\n", $fb_post_desciption_business);
    
            
              $fb_post_desciption_business=$fb_post_desciption_business."\n".$item_purchase_link."\n\n"."***As an Affiliate I earn from qualifying purchases. Prices, coupons and codes are valid at the time of posting and can expire at any time ***";
              
              $fb_post_desciption_business=str_replace('&nbsp;','',$fb_post_desciption_business);
    
              //$post_image_id=$this->facebook->post($fb_page,$accessToken,$fb_post_desciption_business,[$request->profile_pic->path()]);
              $fb_post_desciption_business=str_replace('&nbsp;',' ',$fb_post_desciption_business);
              $fb_post_desciption_business=str_replace(['&#39;','&#169',"&quot;","&ldquo;","&rdquo;","&zwj;"],["'",'(c)','"','“','”',''],$fb_post_desciption_business);
    
              $fb_post_desciption_business=htmlspecialchars_decode($fb_post_desciption_business);
    
              $this->facebook->post($fb_page_bussiness,$accessToken,$fb_post_desciption_business,[env('AWS_S3_BASE')."images/products/".$request->get('image')],true);
             
            }
            }

        //posting to pinterest
        if($pinterest_selected==1){

            $product_name=$request->get('name');
            $coupon_code=$request->get('coupon_code');
            $off_on_product=$request->get('off_on_product');
            $expiry_date=date('j M Y',strtotime($request->get('expiry_date')));
            $item_purchase_link=$request->get('item_purchase_link');
            $pinterest_description=$request->get('pinterest_description');
            

            $description=str_replace('<br />', "\n", $description);
            $description=strip_tags($description);
            if($pinterest_description=="")  
            $pinterest_description=$description;
            
            
       // $post_content="Coupon Code: $coupon_code"." | "."You will get $off_on_product off"." | "."Offer valid till $expiry_date";
        
       // $pinterest_content=$post_content." | ".$pinterest_description." | "."***As an Affiliate I earn from qualifying purchases. Prices, coupons and codes are valid at the time of posting and can expire at any time ***";
        $pinterest_description=$pinterest_description."           | ".$item_purchase_link." |                         ***As an Affiliate I earn from qualifying purchases. Prices, coupons and codes are valid at the time of posting and can expire at any time***"; 

        // $pinterest_content=strip_tags($pinterest_content);
         $pinterest_description=strip_tags($pinterest_description);
         $pinterest_description=htmlspecialchars_decode($pinterest_description);
         $pinterest_description=str_replace(['&#39;','&#169',"&quot;","&ldquo;","&rdquo;","&zwj;"],["'",'(c)','"','“','”',''],$pinterest_description);
         


        $this->createPinterestPin($pinterest_description,$request->get('image'),$product_name,$item_purchase_link);
        }
        //posting to instagram
        if($instagram_selected==1){

            $product_name=$request->get('name');
            $coupon_code=$request->get('coupon_code');
            $off_on_product=$request->get('off_on_product');
            $expiry_date=date('j M Y',strtotime($request->get('expiry_date')));
            $item_purchase_link=$request->get('item_purchase_link');
            $instagram_description=$request->get('instagram_description');
            

            $description=str_replace('<br />', "\n", $description);
            $description=strip_tags($description);
            if($instagram_description=="")  
            $instagram_description=$description;
            
            
      //  $instagram_post_content="Product: $product_name Coupon Code: $coupon_code"."\n"."You will get $off_on_product off"."\n"."Offer valid till $expiry_date Purchase Link: $item_purchase_link";
        
       // $instagram_content=$instagram_post_content."\n".$instagram_description."\n"."***As an Affiliate I earn from qualifying purchases. Prices, coupons and codes are valid at the time of posting and can expire at any time ***";

       // $instagram_content=strip_tags($instagram_content);
        $instagram_description=strip_tags($instagram_description);
        $instagram_description=$instagram_description."\n".$item_purchase_link."\n\n"."***As an Affiliate I earn from qualifying purchases. Prices, coupons and codes are valid at the time of posting and can expire at any time ***";
        
        $instagram_description=str_replace('&nbsp;',' ',$instagram_description);
        $instagram_description=htmlspecialchars_decode($instagram_description);
        $instagram_description=str_replace(['&#39;','&#169',"&quot;","&ldquo;","&rdquo;","&zwj;"],["'",'(c)','"','“','”',''],$instagram_description);
         
        
		
		 try{
						$instagram_response=$this->publishInstagramImage($instagram_description,$request->get('image'),$request->get('fb_page_id'));
				}catch (\Exception $e){
						Session::flash('warning', "Instagram: ".$e->getMessage());
           
				}
        
        }
        Session::flash($response['type'], $response['message']); 
        return redirect()->route('product.index');
    }
    public function createPinterestPin($description,$image_name,$product_name,$item_purchase_link){
         //pinterest post
         $social_token = DB::table('social_tokens')->where('planform', 'pinterest')->first();
        
         $piAccessToken=$social_token->token;
         $apiEndPoint="https://api.pinterest.com/v5/pins";
         $params=[
         'link'=>$item_purchase_link,   
         'title'=>$product_name,
         "description"=>$description,
         "alt_text"=>null,
         "board_id"=>"184788459649796637",
         "board_section_id"=>null,
         "media_source"=>[
             "source_type"=>"image_url",
             "url"=> env('AWS_S3_BASE')."images/products/".$image_name
         ]];
         //echo json_encode($params);die;
         $ch = curl_init();
         curl_setopt( $ch, CURLOPT_URL, $apiEndPoint );
         curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($params) );
         curl_setopt( $ch, CURLOPT_HTTPHEADER,['Authorization: Bearer '.$piAccessToken,'Content-Type: application/json']);
         curl_setopt( $ch, CURLOPT_POST, 1 );
         curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
         curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
         curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
         //uncomment below line to post on pinterest
         $responsePI = curl_exec( $ch );
         curl_close( $ch );
         
         //refreshing pinterest access token
         $this->refreshPinterestToken($social_token->refresh_token);
    }

    public function publishInstagramImage($description,$image_name,$fb_page_id){
    	$instagram_account_info=$this->getInstagramAccountId($fb_page_id);
    	$instagram_business_account_id=$instagram_account_info['instagram_business_account']['id'];
        $social_token = DB::table('social_tokens')->where('planform', 'facebook')->first();
        $accessToken=$social_token->token;
    	$imageMediaObjectEndpoint = 'https://graph.facebook.com/v13.0/' .$instagram_business_account_id. '/media';
    	$imageMediaObjectEndpointParams = array( // POST 
    		'image_url' => env('AWS_S3_BASE')."images/products/".$image_name,
    		'caption' => $description,
    		'access_token' => $accessToken
    	);
    	$imageMediaObjectResponseArray = $this->makeApiCall( $imageMediaObjectEndpoint, 'POST', $imageMediaObjectEndpointParams );
        if(isset($imageMediaObjectResponseArray['error']) && isset($imageMediaObjectResponseArray['error']['error_user_msg'])){
            Session::flash('error', "Instagram: ".$imageMediaObjectResponseArray['error']['error_user_msg']);
            return;
        }
    	// set status to in progress
    	$imageMediaObjectStatusCode = 'IN_PROGRESS';

    	while( $imageMediaObjectStatusCode != 'FINISHED' ) { // keep checking media object until it is ready for publishing
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
    	$publishImageEndpoint = 'https://graph.facebook.com/v5.0/' .$instagram_business_account_id . '/media_publish';
    	$publishEndpointParams = array(
    		'creation_id' => $imageMediaObjectId,
    		'access_token' => $accessToken
    	);
    	$publishImageResponseArray = $this->makeApiCall( $publishImageEndpoint, 'POST', $publishEndpointParams );
        return $publishImageResponseArray;
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

    /** 
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('products::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $token_error=[];
        //getting validity of facebook and pinterest token
        $facebook_token_validity=DB::table('social_tokens')->where('planform', 'facebook')->first();
        if($facebook_token_validity->expiry<date('Y-m-d')){
            $token_error[]="Facebook/Instagram token"; 
        }
        $pinterest_token_validity=DB::table('social_tokens')->where('planform', 'pinterest')->first();
        if($pinterest_token_validity->expiry<date('Y-m-d')){
            $token_error[]="Pinterest token"; 
        }
        if(count($token_error)>0){
          //Session::flash('error', implode(' and ',$token_error)." expired please renew."); 
          //return redirect()->route('product.index');
        }
        //$groupsList=$this->facebook->getGroupsList($facebook_token_validity->token);
    		$groupsList=[
    	     	['id'=>425474048538804,'name'=>'Amazing Deals, Coupons & Codes! By: CN Deals & Coupons!'],
    		    ['id'=>962654337604810,'name'=>'CN Deals, Coupons & Promo Codes!']
    		];
        /*$pages=$this->getFacebookPages();
        $pagesInfo=[];
        foreach($pages as $page){
            $pagesInfo[$page->id]=$page->name;
        }*/
    		$pagesInfo=[
    		  545781699682971 => 'CN Deals'
    		];
        $categories = $this->ProductRepo->getCategories();
        $products  =  $this->ProductRepo->getRecordBySlug($id);
        $platforms  =    $this->ProductRepo->getPlatforms();
        if($products){
          return view('products::edit',compact('products','categories','platforms','groupsList','pagesInfo'));  
        }
        Session::flash('error', trans('flash.error.reocrd_not_available_now'));
        return redirect()->route('product.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @param  int true or false
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request,$id)
    {   
        $response = $this->ProductRepo->changeStatus($request,$id);
        if($request->ajax()){
            return response()->json($response);
        }
        Session::flash($response['type'], $response['message']);  
        return back();   
    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(UpdateProductsRequest $request, $id)
    {
        $profile_pic=$request->profile_pic;
        
        $data =  $this->ProductRepo->getRecord($id);
        if($data){
            $response = $this->ProductRepo->update($request,$id);
            if($request->ajax()){
                return response()->json($response);
            }
            /*======posting to social media start==========*/
           
        $same_description_fb_pages=$request->get('same_description');
        $same_description_business=$request->get('same_description_business');
        $facebook_pages=$request->get('facebook_pages');
        $facebook_pages_business=$request->get('facebook_pages_business');
        $facebook_descriptions=$request->get('facebook_description');
        $facebook_description_business=$request->get('facebook_description_business');
        $pinterest_selected=$request->get('pinterest');
        $instagram_selected=$request->get('instagram');
        $item_purchase_link=$request->get('item_purchase_link');
        $description=$request->get('description');
            
           
           
           
            if($facebook_pages!=null){
                $social_token = DB::table('social_tokens')->where('planform', 'facebook')->first();
                $accessToken=$social_token->token;
                foreach($facebook_pages as $fb_page){
                  
                  if(isset($same_description_fb_pages[$fb_page]))  
                  $fb_post_desciption=$description;
                  else
                  $fb_post_desciption=$facebook_descriptions[$fb_page]!=''?$facebook_descriptions[$fb_page]:$description;
                  
                  $fb_post_desciption=str_replace('<br />', "\n", $fb_post_desciption);
                  $fb_post_desciption=strip_tags($fb_post_desciption);
                  $fb_post_desciption=str_replace('\r\n', "\n", $fb_post_desciption);
        
                //   $fb_post_content=$post_content."\n".$fb_post_desciption."\n"."***As an Affiliate I earn from qualifying purchases. Prices, coupons and codes are valid at the time of posting and can expire at any time ***";
                //   $fb_post_content=strip_tags($fb_post_content);
        
                  $fb_post_desciption=$fb_post_desciption."\n".$item_purchase_link."\n\n"."***As an Affiliate I earn from qualifying purchases. Prices, coupons and codes are valid at the time of posting and can expire at any time ***";
                  $fb_post_desciption=str_replace('&nbsp;',' ',$fb_post_desciption);
                $fb_post_desciption=htmlspecialchars_decode($fb_post_desciption);
                               
                $fb_post_desciption=str_replace(['&#39;','&#169',"&quot;","&ldquo;","&rdquo;","&zwj;"],["'",'(c)','"','“','”',''],$fb_post_desciption);
               

               
                  
				   try{
						$this->facebook->post($fb_page,$accessToken,$fb_post_desciption,[env('AWS_S3_BASE')."images/products/".$request->get('image')]);
				}catch (\Exception $e){
						Session::flash('warning', "Facebook: ".$e->getMessage());
           
				}	
                }
                }
                 //posting to facebook business pages.
           if($facebook_pages_business!=null){
            $social_token = DB::table('social_tokens')->where('planform', 'facebook')->first();
            $accessToken=$social_token->token;
            foreach($facebook_pages_business as $fb_page_bussiness){
              
              if(isset($same_description_business[$fb_page_bussiness]))  
              $fb_post_desciption_business=$description;
              else
              $fb_post_desciption_business=$facebook_description_business[$fb_page_bussiness]!=''?$facebook_description_business[$fb_page_bussiness]:$description;
              
              $fb_post_desciption_business=str_replace('<br />', "\n", $fb_post_desciption_business);
              $fb_post_desciption_business=strip_tags($fb_post_desciption_business);
              $fb_post_desciption_business=str_replace('\r\n', "\n", $fb_post_desciption_business);
    
            
              $fb_post_desciption_business=$fb_post_desciption_business."\n".$item_purchase_link."\n\n"."***As an Affiliate I earn from qualifying purchases. Prices, coupons and codes are valid at the time of posting and can expire at any time ***";
              
              $fb_post_desciption_business=str_replace('&nbsp;','',$fb_post_desciption_business);
    
              //$post_image_id=$this->facebook->post($fb_page,$accessToken,$fb_post_desciption_business,[$request->profile_pic->path()]);
              $fb_post_desciption_business=str_replace('&nbsp;',' ',$fb_post_desciption_business);
              $fb_post_desciption_business=str_replace(['&#39;','&#169',"&quot;","&ldquo;","&rdquo;","&zwj;"],["'",'(c)','"','“','”',''],$fb_post_desciption_business);
    
              $fb_post_desciption_business=htmlspecialchars_decode($fb_post_desciption_business);
    
              $this->facebook->post($fb_page_bussiness,$accessToken,$fb_post_desciption_business,[env('AWS_S3_BASE')."images/products/".$request->get('image')],true);
             
            }
            }
        
                //posting to pinterest
                if($pinterest_selected==1){
        
                    $product_name=$request->get('name');
                    $coupon_code=$request->get('coupon_code');
                    $off_on_product=$request->get('off_on_product');
                    $expiry_date=date('j M Y',strtotime($request->get('expiry_date')));
                    $item_purchase_link=$request->get('item_purchase_link');
                    $pinterest_description=$request->get('pinterest_description');
                    
        
                    $description=str_replace('<br />', "\n", $description);
                    $description=strip_tags($description);
                    if($pinterest_description=="")  
                    $pinterest_description=$description;
                    
                    
              
                $pinterest_description=$pinterest_description."       | ".$item_purchase_link." |                                           ***As an Affiliate I earn from qualifying purchases. Prices, coupons and codes are valid at the time of posting and can expire at any time***"; 
        
                
                 $pinterest_description=strip_tags($pinterest_description);
                 $pinterest_description=htmlspecialchars_decode($pinterest_description);
                 $pinterest_description=str_replace(['&#39;','&#169',"&quot;","&ldquo;","&rdquo;","&zwj;"],["'",'(c)','"','“','”',''],$pinterest_description);
                    
        
                $this->createPinterestPin($pinterest_description,$request->get('image'),$product_name,$item_purchase_link);
                }
                //posting to instagram
                if($instagram_selected==1){
                    $product_name=$request->get('name');
                    $coupon_code=$request->get('coupon_code');
                    $off_on_product=$request->get('off_on_product');
                    $expiry_date=date('j M Y',strtotime($request->get('expiry_date')));
                    $item_purchase_link=$request->get('item_purchase_link');
                    $instagram_description=$request->get('instagram_description');
        
                    $description=str_replace('<br />', "\n", $description);
                    $description=strip_tags($description);
                    if($instagram_description=="")  
                    $instagram_description=$description;
            
                    $instagram_description=strip_tags($instagram_description);
                    $instagram_description=$instagram_description."\n".$item_purchase_link."\n\n"."***As an Affiliate I earn from qualifying purchases. Prices, coupons and codes are valid at the time of posting and can expire at any time ***";
        
                    $instagram_description=str_replace('&nbsp;',' ',$instagram_description);
                    $instagram_description=htmlspecialchars_decode($instagram_description);
                    $instagram_description=str_replace(['&#39;','&#169',"&quot;","&ldquo;","&rdquo;","&zwj;"],["'",'(c)','"','“','”',''],$instagram_description);
				    try{
						 $instagram_response = $this->publishInstagramImage($instagram_description,$request->get('image'),$request->get('fb_page_id'));
    				}catch (\Exception $e){
    						Session::flash('warning', "Instagram: ".$e->getMessage());
    				}
                }
            /*======posting to social media end==========*/
            
            Session::flash($response['type'], $response['message']); 
            return redirect()->route('product.index');
        }
        Session::flash('error', trans('flash.error.record_not_available_now'));
        return redirect()->route('product.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try{
            $data =  $this->ProductRepo->getRecord($id);
            if($data){
                $this->ProductRepo->destroy($id);
                Session::flash('success', trans('flash.success.product_deleted_successfully'));
                return redirect()->route('product.index');
            }
            Session::flash('error', trans('flash.error.record_not_available_now'));
            return redirect()->route('product.index');
        }catch (QueryException $e){
            Session::flash('warning', trans('flash.error.cant_delete_record_try_later'));
            return redirect()->route('product.index');
        }
    }

    /**
     * upload Category picture with thumb image and original image.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function saveMedia(ProductMediaRequest $request) {
        try {
            $response = $this->ProductRepo->saveProductPictureMedia($request);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }

    /**
     * Export product.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function exportcsv()
    {
        return (new AllProductsExport())->download('products.csv');
        // return redirect()->route('product.index');
    }

    /**
     * Download Sample CSV.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sampleCSV()
    {
        return response()->download(storage_path('app/public/sample.csv'));
        
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function uploadcsv()
    {
        return view('products::addproductwithcsv');
    }

    public function importcsv(ProductCSVRequest $request)
    {
        try{
            if ($request->file('import_file')) {
                Excel::import(new ProductsImport(), request()->file('import_file'));

                Session::flash('success', trans('flash.success.product_csv_uploaded_successfully'));
                return redirect()->route('product.index');
            }
        }catch (QueryException $e){
            Session::flash('warning', trans('flash.error.cant_upload_product_csv'));
            return back();
        }   
    }

    /**
     * Deal of the day.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @param  int true or false
     * @return \Illuminate\Http\Response
     */
    public function dealFTheDay(Request $request,$id,$status)
    {   
        $response = $this->ProductRepo->dealFTheDayStatus($request,$id,$status);
        if($request->ajax()){
            return response()->json($response);
        }
        Session::flash($response['type'], $response['message']);  
        return back();   
    }

    public function getSuggessionDeals(Request $request)
    {
        $response = $this->ProductRepo->getSuggessionDeals($request);
        if($request->ajax()){
            return response()->json($response);
        }
    }
    
    public function removeInactive()
    {
        $response = $this->ProductRepo->getRemoveInactive();
        Session::flash('success', trans('flash.success.remove_inactive_products'));
        return back(); 
    }
    public function refreshPinterestToken($refresh_token){
        $apiEndPoint="https://api.pinterest.com/v5/oauth/token ";
        $params=[
            "refresh_token"=>$refresh_token,
            "grant_type"=>'refresh_token'
        ];
       
        
        $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $apiEndPoint );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $params ) );
			curl_setopt( $ch, CURLOPT_HTTPHEADER,['Authorization: Basic '.base64_encode(env('PINTEREST_APP_ID').':'.env('PINTEREST_APP_SECRET')),'Content-Type: application/x-www-form-urlencoded']);
			curl_setopt( $ch, CURLOPT_POST, 1 );
            curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
		    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		    $response = curl_exec( $ch );
            $accessTokenInfo=json_decode( $response, true );
		    curl_close( $ch );
           
            
            $expiry = date("Y-m-d", time() + $accessTokenInfo["expires_in"]);
            $accessToken=$accessTokenInfo["access_token"];
            
            
        DB::table('social_tokens')
        ->where('planform', 'pinterest')
        ->update(['token' =>$accessToken,'expiry' =>$expiry]);
       
    }
	public function getInstagramAccountId($pageId){
        $social_token = DB::table('social_tokens')->where('planform', 'facebook')->first();
		$accessToken=$social_token->token;
		//$pageId="545781699682971"; //facebook page id
		$instagramAccountEndpoint = 'https://graph.facebook.com/v5.0/' . $pageId;
		$igParams = array(
        'fields' => 'instagram_business_account',
        'access_token' =>$accessToken
		);
        // add params to endpoint
		$instagramAccountEndpoint .= '?' . http_build_query( $igParams );
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $instagramAccountEndpoint );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        // make call and get response
        $response = curl_exec( $ch );
        curl_close( $ch );
        $responseArray = json_decode( $response, true );
        return $responseArray;
    }
}
