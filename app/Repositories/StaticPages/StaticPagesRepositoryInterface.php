<?php

namespace App\Repositories\StaticPages;


interface StaticPagesRepositoryInterface
{
    public function getCmsPagesData($slug,$request);
    
    public function getSocialLinksData($request);
    
    public function getPlaystoreLinks($request);
}