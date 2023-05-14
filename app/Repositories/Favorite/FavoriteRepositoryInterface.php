<?php

namespace App\Repositories\Favorite;


interface FavoriteRepositoryInterface
{
    public function addFavoriteUnfavroite($request);

    public function listFavoriteProduct($request);
}