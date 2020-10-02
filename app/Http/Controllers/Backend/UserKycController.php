<?php

namespace App\Http\Controllers\Backend;

use Session;
use Validator;
use Redirect;
use Route;
use Auth;
use Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileFormRequest;
use App\Http\Requests\ChangePasswordFormRequest;
use App\Http\Requests\BackendUserFormRequest;
use App\Inv\Repositories\Models\Master\Country;
use App\Inv\Repositories\Contracts\Traits\ApiAccessTrait;
use App\Inv\Repositories\Contracts\UserInterface as InvUserRepoInterface;

class UserKycController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(InvUserRepoInterface $user)
    {
        $this->userRepo = $user;
    }
    
     use ApiAccessTrait;

     
    /**
     * User repository
     *
     * @var object
     */
    protected $userRepo;
    
    /**
     * individualKycList
     * show individual KYC Users List
     *
     * @var object
     */
    public function individualKycList(){
        $kyc_type=1;
        $userType=1;
        $usersList = $this->userRepo->getAllUsersKycPaginate($kyc_type,$userType);
     
        return view('backend.users_kyc',['usersList' => $usersList]);
    }
    
    /** end */
    
    /**
     * corporateKycList
     * show individual KYC Users List
     *
     * @var object
     */
    public function corporateKycList(){
        $kyc_type=2;
        $userType=2;
        $usersList = $this->userRepo->getAllUsersKycPaginate($kyc_type,$userType);
        
        return view('backend.users_kyc',['usersList' => $usersList]);
    }
    
    /** end */
    
    /**
     * corporateKycList
     * show individual KYC Users List
     *
     * @var object
     */
    public function beneficiaryKycList(){
        $kyc_type=3;
        $userType=2;
        $usersList = $this->userRepo->getAllUsersKycPaginate($kyc_type,$userType);
        return view('backend.users_kyc',['usersList' => $usersList]);
    }
    
    /** end */
}