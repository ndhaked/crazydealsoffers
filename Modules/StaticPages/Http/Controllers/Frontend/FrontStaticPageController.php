<?php

namespace Modules\StaticPages\Http\Controllers\Frontend;

use Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\StaticPages\Repositories\Frontend\FrontendStaticPagesRepositoryInterface as FrontStaticPagesRepo;

class FrontStaticPageController extends Controller
{
    
    public function __construct(FrontStaticPagesRepo $FrontStaticPagesRepo)
    {
        $this->FrontStaticPagesRepo = $FrontStaticPagesRepo;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function faq(Request $request)
    {
        $pageInfo = $this->FrontStaticPagesRepo->getRecordBySlug('faq');
        if ( is_null($pageInfo) )
            // use either one of the two lines below. I prefer the second now
            // return Event::first('404');
            App::abort(404);
        
        return view('staticpages::frontend.show',compact('pageInfo'));
        //return view('staticpages::frontend.how_it_works',compact('pageInfo'));
    }
     public function aboutus(Request $request)
    {
        $pageInfo = $this->FrontStaticPagesRepo->getRecordBySlug('aboutus');
        if ( is_null($pageInfo) )
            // use either one of the two lines below. I prefer the second now
            // return Event::first('404');
            App::abort(404);

        return view('staticpages::frontend.show',compact('pageInfo'));
    }

    public function howItWork(Request $request)
        {
            $pageInfo = $this->FrontStaticPagesRepo->getRecordBySlug('how-it-works');
            if ( is_null($pageInfo) )
            // use either one of the two lines below. I prefer the second now
            // return Event::first('404');
            App::abort(404);

            return view('staticpages::frontend.show',compact('pageInfo'));
        }

    public function privacyPolicy(Request $request)
        {
            $pageInfo = $this->FrontStaticPagesRepo->getRecordBySlug('privacy-policy');
            if ( is_null($pageInfo) )
            // use either one of the two lines below. I prefer the second now
            // return Event::first('404');
            App::abort(404);

            return view('staticpages::frontend.show',compact('pageInfo'));
        }

    public function termAndConditions(Request $request)
        {
            $pageInfo = $this->FrontStaticPagesRepo->getRecordBySlug('terms-and-condition');
            if ( is_null($pageInfo) )
            // use either one of the two lines below. I prefer the second now
            // return Event::first('404');
            App::abort(404);

            return view('staticpages::frontend.show',compact('pageInfo'));
        }


    public function show($slug,Request $request)
    {
        $pageInfo = $this->FrontStaticPagesRepo->getRecordBySlug($slug);
        if ( is_null($pageInfo) )
            // use either one of the two lines below. I prefer the second now
            // return Event::first('404');
            App::abort(404);

        return view('staticpages::frontend.show',compact('pageInfo'));
    }
}
