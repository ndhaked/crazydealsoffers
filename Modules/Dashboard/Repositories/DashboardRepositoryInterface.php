<?php

namespace Modules\Dashboard\Repositories;


interface DashboardRepositoryInterface
{
    public function getUserCount();

    public function getSubAdminCount();

    public function getAdminCount();

    public function getProductCount();

    public function getBlogCount();

    public function getAdvertisementCount();
}