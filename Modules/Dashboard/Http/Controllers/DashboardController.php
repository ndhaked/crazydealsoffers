<?php

namespace Modules\Dashboard\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Dashboard\Repositories\DashboardRepositoryInterface as DashboardRepo;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(DashboardRepo $DashboardRepo)
    {
      $this->middleware(['ability','auth']);
      $this->DashboardRepo = $DashboardRepo;
    }

    public function index()
    {
        $usersCount = $this->DashboardRepo->getUserCount();
        $subAdminCount = $this->DashboardRepo->getSubAdminCount();
        $adminCount = $this->DashboardRepo->getAdminCount();
        $productCount = $this->DashboardRepo->getProductCount();
        $blogCount = $this->DashboardRepo->getBlogCount();
        $advertisementCount = $this->DashboardRepo->getAdvertisementCount();
        return view('dashboard::index', compact('usersCount', 'subAdminCount', 'adminCount', 'productCount', 'blogCount', 'advertisementCount'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('dashboard::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('dashboard::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('dashboard::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
