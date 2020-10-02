<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Session;
use Hash;
use Auth;
use Event;
use App\Inv\Repositories\Contracts\UserInterface as InvUserRepoInterface;
class ChangePasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(InvUserRepoInterface $user)
    {
        $this->middleware('auth');
        $this->userRepo = $user;
    }

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showChangePasswordForm(Request $request, $token = null)
    {
       
        
        return view('auth.changepassword');
    }
    
    /**
     * 
     * @param Request $request
     * @return type
     */
    public function changePassword(Request $request){


        //echo "debug"; exit;
        
        $validatedData = $this->validate(
               $request,
               [
                  'current-password'=> 'required',
                   'new-password'=>'required|min:8|max:15|regex:/^(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[^\w\s]).{8,}$/',
                   'new-password_confirmation'=> 'required|same:new-password',

                ],
               [
                  'password_confirmation.required'=> trans('forms.ChgPass_Form.server_error.cur-req'),
                   'current-password.required' => trans('forms.ChgPass_Form.server_error.cur-req'),
                   'new-password.required'=> trans('forms.ChgPass_Form.server_error.new-req'),
                   'new-password.min'=> trans('forms.ChgPass_Form.server_error.min'),
                   'new-password.max'=> trans('forms.ChgPass_Form.server_error.max'),
                   'new-password.regex'=> trans('forms.ChgPass_Form.server_error.pass_rules'),

                    'new-password_confirmation.required'=> trans('forms.ChgPass_Form.server_error.confirm_req'),
                   'new-password_confirmation.same' => trans('forms.ChgPass_Form.server_error.confirm_pass'),

                ]
            );


        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            // The passwords matches

            return redirect()->back()->with("error","Your current password does not matches with the password you provided. Please try again.");
        }

        if(strcmp($request->get('current-password'), $request->get('new-password')) == 0){
            //Current password and new password are same
            return redirect()->back()->with("error","New Password cannot be same as your current password. Please choose a different password.");
        }


        //Change Password
        $firstTime = '';
        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        if(Auth::user()->is_pwd_changed == 0) {
            $firstTime = 'Y';
            $user->is_pwd_changed = 1;
        }
        $user->save();
        if($firstTime == 'Y') {

        //Password change email  
            
        $userArr = [];
        $userId  = $user->user_id;
        $userArr = $this->userRepo->find($userId, ['email','first_name', 'last_name', 'username']);
        $verifyUserArr = [];
        $verifyUserArr['name'] = $userArr->f_name . ' ' . $userArr->l_name;
        $verifyUserArr['username'] = $userArr->username;
        $verifyUserArr['email'] = $userArr->email;

        Event::dispatch("user.email.firstchangepassword", serialize($verifyUserArr));
            return redirect()->route('frontend_logout');

            /*if($user->user_type==1 ) {

                return redirect()->route('profile');

              }

              if($user->user_type==2 ) {

                return redirect()->route('company_profile');

              } */


        } else {
        return redirect()->route('frontend_logout');
        //return redirect()->back()->with("success","Password changed successfully !");
        }
    }
}
