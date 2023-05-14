<?php

namespace App\Http\Controllers;

use Session,View,Response,config;
use Illuminate\Http\Request;
use Modules\Products\Entities\Products;
use Modules\Configuration\Entities\Configuration;
use Modules\Categories\Entities\Categories;
use Modules\Blogs\Entities\Blogs;
use Modules\Faq\Entities\Faq;
use Modules\StaticPages\Entities\StaticPages;
use Modules\AdvertiseAffiliated\Entities\AdvertiseAffiliated;
use Modules\Slider\Entities\Slider;
use Spatie\Newsletter\NewsletterFacade as Newsletter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Builder;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Products $Products)
    {
       $this->Products = $Products;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //-----------Deal of the day section-----------//
        $dealOfday = Products::where('status','active')->where('deal_of_the_day',1)->orderBy('id', 'DESC')->where('expiry_date', '>', utctodtc_4now())->limit(10)->get();
        
        //--------------All Deals section--------------//
        $allDeals  = Products::where('status','active')->orderBy('id', 'DESC')->where('expiry_date', '>', utctodtc_4now())->limit(10)->get();
        
        //-----------fetch slider image---------------------//
        $sliders = Slider::where('status',1)->orderBy('slider_order')->get();
                
        return view('welcome',compact('dealOfday','allDeals','sliders'));
    }    

    public function staticPages($slug)
    {
        $data = StaticPages::where('slug',$slug)->first();
        if($data){
            $data['image'] = ($data->banner_image!='')?$data->BannerPath:'';
            return view('staticpage',compact('data'));
        }else{
            abort(404);
        }
    }

    public function advertiseAffiliated($slug)
    {
        $affiliated = AdvertiseAffiliated::where('slug',$slug)->first();
        if($affiliated){
            return view('advertiseaffiliated',compact('affiliated'));
        }else{
            abort(404);
        }
    }
    
    public function faqs()
    {
        $faq = Faq::where('status',1)->get();
        return view('faq',compact('faq'));
    }
    
    public function blog(Request $request)
    {
        if($request->ajax()){
            $blogs = Blogs::where('status',1)->orderBy('id','desc')->paginate(6);
           return Response::json(array('apppendid'=>'result','body' =>json_encode(View::make('ajax_blog_listing',compact('blogs'))->render())));
        }
        $blogs = Blogs::where('status',1)->orderBy('id','desc')->paginate(6);
        return view('blog',compact('blogs'));
    }
    
    public function blogDetails($slug)
    {
        $blog = Blogs::where('slug',$slug)->first();
        if($blog){   
            return view('blog_details',compact('blog'));
        }else{
            abort(404);
        }
    }
    
    public function productListing(Request $request,$category=NULL)
    {
        if($request->ajax()){
            return $this->ajaxFilterProducts($request,$category);
        }
        //-----------social & app url-----------//
        $search = ($request->search)?$request->search:'';  
        $products  = Products::where('status','active')
                        ->orderBy('id', 'DESC')
                        ->where('expiry_date', '>', utctodtc_4now())
                        ->whereHas('category', function(Builder $query) use($category,$search) {
                            if($category){
                                $query->where('slug',$category);
                            }
                        })
                        ->where(function($query) use ($search)
                        {
                            if($search){
                                $query->where('name', 'like', '%' . $search . '%');
                            }
                        })->paginate(12);      
        $categories = Categories::where('status',1)
                                ->whereHas('product')
                                ->select('name','id','slug')->get();
        $fullHeading = 'All Products';
        $metaTitle = 'Deal of the day: Grab the best coupons deals & offers every day';
        $metaDescription = "Get the top deals every day on every featured product, check today's eye-catching discount deals here. Don't forget to check these coupon codes, discounted deals, and offers before shopping online.";
        if($category){
            $fullHeading = '';
            $metaTitle = ucfirst($category).' Discount Offers - CN Deals & Coupons';
            $metaDescription = "Best ".ucfirst($category)." coupons and discounts | Find the best discount deals on ".ucfirst($category)." at CN Deals and Coupons.";
            $cat= Categories::where('slug',$category)->first();
            if($cat){
                $metaTitle = $cat->MetaTitle;
                $metaDescription = $cat->MetaDescription;
                $fullHeading = $cat->FullHeading;
            }
        }
        return view('product_listing',compact('categories','search','products','category','metaTitle','metaDescription','fullHeading'));
    }

    public function ajaxFilterProducts($request,$category)
    {
        $search = ($request->search)?$request->search:'';  
        $products  = Products::where('status','active')
                        ->orderBy('id', 'DESC')
                        ->where('expiry_date', '>', utctodtc_4now())
                        ->whereHas('category', function(Builder $query) use($category,$search) {
                            if($category){
                                $query->where('slug',$category);
                            }
                        })
                        ->where(function($query) use ($search)
                        {
                            if($search){
                                $query->where('name', 'like', '%' . $search . '%');
                            }
                        })
                        ->paginate(12);
            $fullHeading = 'All Products';
            $metaTitle = 'Deal of the day: Grab the best coupons deals & offers every day';
            $metaDescription = "Get the top deals every day on every featured product, check today's eye-catching discount deals here. Don't forget to check these coupon codes, discounted deals, and offers before shopping online.";
            if($category){
                $metaTitle = ucfirst($category).' Discount Offers - CN Deals & Coupons';
                $metaDescription = "Best ".ucfirst($category)." coupons and discounts | Find the best discount deals on ".ucfirst($category)." at CN Deals and Coupons.";
                $cat= Categories::where('slug',$category)->first();
                if($cat){
                    $metaTitle = $cat->MetaTitle;
                    $metaDescription = $cat->MetaDescription;
                    $fullHeading = $cat->FullHeading;
                }
            }
        return Response::json(array('fullHeading'=>$fullHeading,'metaTitle'=>$metaTitle,'metaDescription'=>$metaDescription,'apppendid'=>'result','body' =>json_encode(View::make('ajax_product_listing',compact('products','category','search','metaDescription','metaTitle','fullHeading'))->render())));
    }

    
    public function productDetails($slug)
    {
        $product = $this->Products->with('category')->where('slug',$slug)->first();
        if($product){
            return view('product_details',compact('product'));            
        }
        else{
            abort(404);
        }
    }

    public function subscrivedMailchimp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        $status=[];
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }        
        if ( ! Newsletter::isSubscribed($request->input('email')) ) {
            $result = Newsletter::subscribe($request->input('email'));
            if($result){
                $status['status'] = 'success';
                $status['message'] = trans('flash.success.subscribe_success');
            }else{
                $status['status'] = 'error';
                $status['message'] = trans('flash.error.subscribe_failed');
            }
        }else{
            $status['status'] = 'error';
            $status['message'] = trans('flash.error.already_subscribe');
        }
        return $status;
    }
    
}
