<?php

namespace App\Http\Controllers\Api\Auth;

use File;
use Crypt;
use Hash;
use Event;
use Helpers;
use Session;
use DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Http\Requests\RegistrationFormRequest;
use App\Inv\Repositories\Contracts\UserInterface as InvUserRepoInterface;


class ApiController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Register Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles the registration of new users as well as their
      | validation and creation. By default this controller uses a trait to
      | provide this functionality without requiring any additional code.
      |
     */

use RegistersUsers;
   

    /**
     * User repository
     *
     * @var object
     */
    protected $userRepo;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
   // protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(InvUserRepoInterface $user) {
 
        $this->userRepo = $user;
    
    }

   

////// Api user createion


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    public function kycCompleteApproved(Request $request) {

        try {
        $userDataArray = $this->userRepo->getTradingkycCompletedApproved();
        return response()->json(['userDataArray' => $userDataArray]);
        } catch(Exception $ex) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($ex));
        }
        
    }

    public function apiuseremail(Request $request) {

        try {
            $data = $request->all();
            $arrData = [];
            $arrData['email'] = $data['email'];
            $userDataArray = $this->userRepo->getUserByEmailforAPI($arrData);
//echo "<pre>";
//print_r($userDataArray);
//exit;
            if($userDataArray) {
                $result['status'] = "false";
            } else {
                $result['status'] ="true";       
            }
//echo "<pre>";
//print_r($result);
//exit;

            return response()->json($result);
        } catch(Exception $ex) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($ex));
        }
        
    }


}
