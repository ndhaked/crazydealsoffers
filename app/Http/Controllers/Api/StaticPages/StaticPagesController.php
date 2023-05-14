<?php

namespace App\Http\Controllers\Api\StaticPages;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use App\Repositories\StaticPages\StaticPagesRepositoryInterface as PagesList;

class StaticPagesController extends BaseController
{
	public function __construct(PagesList $PagesRepo){
         $this->PagesRepo = $PagesRepo;
    }

	public function getCmsPages($slug,Request $request) {
     	return $this->PagesRepo->getCmsPagesData($slug,$request);
    }

    public function getSocialLinks(Request $request) {
     	return $this->PagesRepo->getSocialLinksData($request);
    }

    public function getPlaystoreLinks(Request $request) {
        return $this->PagesRepo->getPlaystoreLinks($request);
    }
}