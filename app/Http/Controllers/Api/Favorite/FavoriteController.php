<?php

namespace App\Http\Controllers\Api\Favorite;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\BaseController;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\Favorite\FavoriteRepositoryInterface as FavoriteRepo;

class FavoriteController extends BaseController
{
    /**
     * Create a Favorite Controller instance.
     *
     * @return void
     */
    public function __construct(FavoriteRepo $FavoriteRepo,Request $request) {
        $this->FavoriteRepo = $FavoriteRepo;
        if($request->headers->get('IsGguest')=='false')
        $this->middleware('auth:api');
        return auth()->shouldUse('api');
    }

    /**
     * Add the Favorite.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addFavoriteUnfavroite(Request $request) {
        $response = $this->FavoriteRepo->addFavoriteUnfavroite($request);
        return $response;
    }

    /**
     * List the Favorite.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function listFavorite(Request $request) {
        $response = $this->FavoriteRepo->listFavoriteProduct($request);
        return $response;
    }
}