<?php

namespace App\Repositories\Blog;


interface BlogRepositoryInterface
{
    public function getBlogList($request);

    public function getBlogDetail($request);
}