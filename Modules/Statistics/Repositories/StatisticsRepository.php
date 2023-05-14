<?php

namespace Modules\Statistics\Repositories;

use DB;
use App\Models\User;
use Carbon\Carbon;
use Modules\Products\Entities\Products;
use Modules\Loans\Entities\ProjectRepaymentsHistory;
use Modules\Loans\Entities\ProjectRepaymentInvestmentReturn;
use Illuminate\Database\Eloquent\Builder;
//use App\Repositories\Common\CommonRepositoryInterface as CommonRepo;

class StatisticsRepository implements StatisticsRepositoryInterface {

    function __construct(User $User,Products $Products) {
        $this->User = $User;
        $this->Products = $Products;
    }

    public function getStaticsGraphData($request)
    {
        $data['countTotalProducts']  = json_encode($this->getTotalProducts(),JSON_NUMERIC_CHECK);
        $data['countTotalUsers']  = json_encode($this->getTotalUsers(),JSON_NUMERIC_CHECK);
        $data['top5Users']  = json_encode($this->top5Users($request),JSON_NUMERIC_CHECK);
        $data['top5Products']   = json_encode($this->top5Products($request),JSON_NUMERIC_CHECK);
        return $data;
    }

    public function getTotalProducts()
    {
       return Products::count();
    }

    public function getTotalUsers()
    {
      return User::whereHas('roles', function ($query) {
            return $query->where('slug', 'customer');
        })->count();
    }

    public function top5Users($request)
    {
        if($request->search =='yearly'){
            return $this->User->select('name','id as y','name as drilldown')
            ->where('status',1)
            ->whereYear('created_at', Carbon::now()->year)
            ->limit(5)->get()->toArray();
            
        }
        if($request->search =='monthly'){
            return $this->User->select('name','id as y','name as drilldown')
            ->where('status',1)
            ->whereMonth('created_at', Carbon::now()->month)
            ->limit(5)->get()->toArray();
        }
        if($request->search =='daily'){
            return $this->User->select('name','id as y','name as drilldown')
            ->where('status',1)
            ->whereDay('created_at', Carbon::now()->day)
            ->limit(5)->get()->toArray();
        }
        return $this->User->select('name','id as y','name as drilldown')
                ->where('status',1)
                ->limit(5)->get()->toArray();
    }

    public function top5Products($request)
    {
        if($request->search =='yearly'){
            return $this->Products->select('name','price as y','name as drilldown')
            ->where('status','active')
            ->whereYear('created_at', Carbon::now()->year)
            ->limit(5)->get()->toArray();
        }
        if($request->search =='monthly'){
            return $this->Products->select('name','price as y','name as drilldown')
            ->where('status','active')
            ->whereMonth('created_at', Carbon::now()->month)
            ->limit(5)->get()->toArray();
        }
        if($request->search =='daily'){
            return $this->Products->select('name','price as y','name as drilldown')
            ->where('status','active')
            ->whereDay('created_at', Carbon::now()->day)
            ->limit(5)->get()->toArray();
        }
        return $this->Products->select('name','price as y','name as drilldown')
                ->where('status','active')
                ->limit(5)->get()->toArray();
    }
}