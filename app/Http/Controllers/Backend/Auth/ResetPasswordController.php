<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Inv\Repositories\Models\User;
use App\Inv\Repositories\Models\ResetPasswordToken;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Inv\Repositories\Contracts\Traits\ResetsPasswords;
use Illuminate\Support\Facades\Password;
use Session;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use Event;
use App\Inv\Repositories\Contracts\UserInterface as InvUserRepoInterface;
class ResetPasswordController extends Controller
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
    const INVALID_TOKEN = 'passwords.token';
    protected $userRepo;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(InvUserRepoInterface $user)
    {
        $this->userRepo = $user;
        $this->middleware('guest');
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
    public function adminshowResetForm(Request $request, $token = null)
    {


        //$token = $request;
       $token = key($request->query());
       $token_res = ResetPasswordToken::getUserTokenDetails($token)->toArray();


       if(count($token_res) > 0) {
           $token_res =(object) $token_res[0];
         //  dd($token_res);

        if(isset($token_res->is_reset) && $token_res->is_reset == 0) {
            return view('auth.passwords.reset')->with(
                ['token' => $token, 'email' => Session::get('token-email')]
            );
        } else {
             die('Token Expired');
        }
       }else if(count($token_res) == 0) {
        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => Session::get('token-email')]
        );
       } else {
         die('Token Expired');
       }
    }



       /**
     *
     * @param Request $request
     * @return type
     */
    public function reset(Request $request)
    {
        $this->validate($request,
            [
            'token' => 'required',
            'password' => 'required|min:8|max:15',
            'password_confirmation' => 'required|same:password',
            ],
            [
            'password.required' => Lang::get('error_messages.req_password').'.',
            'password.min' => 'Password should be minimum 8 characters.',
            'password.max' => 'Password should be maximum 15 characters.',
            'password_confirmation.same' => Lang::get('error_messages.admin.same_confirm_password').'.',
            'password_confirmation.required' => Lang::get('error_messages.admin.req_confirm_password').'.',
            ]
        );

      

        $response = $this->broker()->reset(
            $this->credentials($request),
            function ($user, $password) {
            //dd($password);
            $this->resetPassword($user, $password);
        }
        );
        //dd($response);

       $response == Password::PASSWORD_RESET ? $this->sendResetResponse($request,$response)
                    : $this->sendResetFailedResponse($request, $response);


        if ($response == "passwords.token") {
            $request->session()->flash('alert-danger',
                Lang::get('success_messages.ResetLinkExpired').'.');
            return redirect()->back()->withErrors(['resetError' => Lang::get('success_messages.ResetLinkExpired').'.'])->withInput();
        }


        if($response == "passwords.user") {
           $msgshow = $request->get('email'). " is not registerd with us.";
            Session::flash('message_div',$msgshow);
                return redirect()->back()->withErrors(['resetError' => $msgshow.'.'])->withInput();
        }
        if ($response == "passwords.reset") {



            //Mail to User
            $user    = User::getUserByemail($request->email);

            if($user->is_pwd_changed == 0) {
                //$date = new DateTime;
               // $currentDate = $date->format('Y-m-d H:i:s');
                //$userArr['is_otp_verified'] = 1;

                $userArr['is_active'] = 1;
                $userArr['is_pwd_changed'] = 1;
                $this->userRepo->save($userArr, $user->user_id);
            }

            $arrData['user_id'] = $user->user_id;
            $arrData['token'] = $request->token;
            $arrData['is_reset'] = 1;
            $insert_res = ResetPasswordToken::saveUserTokenDetails($arrData);

            //template data
            if($insert_res)  {
                $userMailArr = [];
                $userMailArr['email'] = $user->email;
                $userMailArr['name'] = ucfirst($user->f_name)." ".$user->l_name;
                $userMailArr['username'] = $user->username;
                Event::dispatch("RESET_PASSWORD_SUCCESSS", serialize($userMailArr));
                $request->session()->flash('alert-success',
                    Lang::get('success_messages.PasswordResetSuccessfully').'.');
                return view('backend.auth.reset_success');
            }
        }

    }

}