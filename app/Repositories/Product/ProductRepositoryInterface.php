<?php

namespace App\Repositories\Product;


interface ProductRepositoryInterface
{
    public function getProductList($request);

    public function getProductDetail($request);
    
    public function getDealOffTheDayList($request);
    
    public function getCategoryList($request);
    
    public function setCronmakeStatusExpiredDeals();
}