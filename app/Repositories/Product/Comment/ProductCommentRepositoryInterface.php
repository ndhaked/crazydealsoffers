<?php

namespace App\Repositories\Product\Comment;


interface ProductCommentRepositoryInterface
{
    public function addComment($request);
    
    public function getUsersListForTag($request);

    public function addCommentLikeDislike($request);

    public function addCommentReply($request);
}