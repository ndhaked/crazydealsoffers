<?php

namespace Modules\Statistics\Http\Controllers;

use DB;
use App\Models\User;
use Carbon\Carbon;
use Modules\Products\Entities\Products;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Statistics\Repositories\StatisticsRepositoryInterface as StatisticsRepo;

class StatisticsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(StatisticsRepo $StatisticsRepo)
    {
        $this->middleware(['ability','auth']);
        $this->StatisticsRepo = $StatisticsRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $data = $this->StatisticsRepo->getStaticsGraphData($request);
        return view('statistics::index',compact('data'));
    }
}
