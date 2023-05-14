<?php

namespace App\Http\Controllers\Api\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\BaseController;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\Product\ProductRepositoryInterface as ProductListRepo;

class ProductController extends BaseController
{
    /**
     * Create a Product Controller instance.
     *
     * @return void
     */
    public function __construct(ProductListRepo $ProductRepo,Request $request) {
        $this->ProductRepo = $ProductRepo;
        if($request->headers->get('IsGguest')=='false')
        $this->middleware('auth:api')->except('setCronmakeStatusExpiredDeals');
        return auth()->shouldUse('api');
    }

    /**
     * Get the Product List.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function productList(Request $request) {
        $response = $this->ProductRepo->getProductList($request);
        return $response;
    }

    /**
     * Get the Product Detail.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function productDetail(Request $request) {
        $response = $this->ProductRepo->getProductDetail($request);
        return $response;
    }

    /**
     * get Deal Off The Day List.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function dealOffTheDayList(Request $request) {
        $response = $this->ProductRepo->getDealOffTheDayList($request);
        return $response;
    }


    /**
     * Get the Categories List.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function categoryList(Request $request) {
        $response = $this->ProductRepo->getCategoryList($request);
        return $response;
    }

    /**
     * Get the setCronmakeStatusExpiredDeals .
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function setCronmakeStatusExpiredDeals(Request $request) {
        $response = $this->ProductRepo->setCronmakeStatusExpiredDeals();
        return $response;
    }
}