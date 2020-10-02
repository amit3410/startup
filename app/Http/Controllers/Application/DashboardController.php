<?php

namespace App\Http\Controllers\Application;

use Auth;
use Helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Inv\Repositories\Contracts\Traits\StorageAccessTraits;
use App\Inv\Repositories\Contracts\UserInterface as InvUserRepoInterface;
use App\Inv\Repositories\Contracts\ApplicationInterface;

class DashboardController extends Controller
{
    
    /**
     * User repository
     *
     * @var object
     */
    protected $userRepo;
    protected $application;

    use StorageAccessTraits;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(InvUserRepoInterface $user,
                                ApplicationInterface $application)
    {
        $this->middleware('auth');
        $this->userRepo    = $user;
        $this->application = $application;
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        try {

            $is_pwd_changed = Auth::user()->is_pwd_changed;
            if($is_pwd_changed == 0) {
                return redirect(route('changepassword'));
            }
            /*

            if(Auth()->user()->user_type==config('inv_common.USER_TYPE.FRONTEND')){
                 return redirect()->route('profile');
            }

            if(Auth()->user()->user_type==config('inv_common.USER_TYPE.COMPANY')){
                 return redirect()->route('company_profile');
            }
            
             */

            $corp_user_id = $request->get('corp_user_id');
            $user_kyc_id  =  $request->get('user_kyc_id');

            $recentRights = [];
            $benifinary = [];
            $userPersonalData = [];
            $userDocumentType = [];
            $userSocialMedia = [];
       
            if ($corp_user_id > 0 && $user_kyc_id > 0) {

                $benifinary['user_kyc_id'] = (int) $user_kyc_id;
                $userKycid=$benifinary['corp_user_id'] = (int) $corp_user_id;
                $benifinary['is_by_company'] = 1;
                $userKycId = (int) $user_kyc_id;
                $userId = null;
            } else {
                $userId = (int) Auth::user()->user_id;
                $userKycId = $this->application->getUserKycid(Auth()->user()->user_id);
                $userKycid=$benifinary['user_kyc_id'] = $userKycId;
                $benifinary['corp_user_id'] = 0;
                $benifinary['is_by_company'] = 0;
                
            }
            $benifinary['user_type'] = (int) Auth::user()->user_type;

            return view('frontend.dashboard',compact('benifinary','userKycid'));
        } catch (Exception $ex) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($ex))->withInput();
        }
    }
 }