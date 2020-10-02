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


class RegisterController extends Controller {
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
    public function apiusercreate(Request $request) {

        $data = $request->all();
        //dd($data);
        //$userId  = Session::has('userId') ? Session::get('userId') : null;
        $arrData = [];
      
        $arrData['f_name'] = $data['first_name'];
        $arrData['m_name'] = $data['middle_name'];
        $arrData['username'] = $data['email'];
        $arrData['l_name'] = $data['last_name'];
        $arrData['email'] = $data['email'];
        $arrData['is_active'] = 0;
       
        $userDataArray = $this->userRepo->saveapiUser($arrData);
        return response()->json(['userDataArray' => $userDataArray]);
        
    }

    


}
