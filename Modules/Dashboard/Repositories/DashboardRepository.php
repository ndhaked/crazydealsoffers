<?php

namespace Modules\Dashboard\Repositories;

use DB,Mail,Session;
use Illuminate\Support\Facades\Input;
use App\Models\User;
use Modules\Products\Entities\Products;
use Modules\Blogs\Entities\Blogs;
use Modules\Advertisements\Entities\Advertisements;

class DashboardRepository implements DashboardRepositoryInterface {

    public function getUserCount(){
        return User::whereHas('roles', function ($query) {
            return $query->where('slug', 'customer');
        })->get()->count();
    }

    public function getSubAdminCount(){
    	return User::whereHas('roles', function ($query) {
            return $query->where('slug', 'subadmin');
        })->get()->count();
    }

    public function getAdminCount(){
    	return User::whereHas('roles', function ($query) {
            return $query->where('slug', 'admin');
        })->get()->count();
    }

    public function getProductCount(){
        return Products::all()->count();
    }

    public function getBlogCount(){
        return Blogs::all()->count();
    }

    public function getAdvertisementCount(){
        return Advertisements::all()->count();
    }
}