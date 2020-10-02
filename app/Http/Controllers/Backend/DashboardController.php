<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Inv\Repositories\Contracts\UserInterface as InvUserRepoInterface;

class DashboardController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    
    public function __construct(InvUserRepoInterface $user)
    {
        $this->userRepo = $user;
        $this->middleware('checkBackendLeadAccess');
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $userStatusSummary=$this->userRepo->getUserStatusSummary();
        return view('backend.dashboard',compact('userStatusSummary'));
    }
}