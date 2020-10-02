<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Crypt;
use Event;
use DateTime;
use Helpers;
use Session;
use Redirect;
use Socialite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Inv\Repositories\Contracts\UserInterface as InvUserRepoInterface;

class LoginController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Login Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles authenticating users for the application and
      | redirecting them to your home screen. The controller uses a trait
      | to conveniently provide its functionality to your applications.
      |
     */

use AuthenticatesUsers;

    /**
     * User repository
     *
     * @var object
     */
    protected $userRepo;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //  protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(InvUserRepoInterface $user) {
        $this->middleware('guest')->except('logout', 'logoutchangepassword');
        $this->userRepo = $user;
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request) {


                
        try {

            //Validation for request
            // $this->validateLogin($request);
            //echo "debug"; exit;
            // If the class is using the ThrottlesLogins trait, we can automatically throttle
            // the login attempts for this application. We'll key this by the username and
            // the IP address of the client making these requests into this application.
            if ($this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);

                return $this->sendLockoutResponse($request);
            }
            $userName = $request['username'];

            $userInfo = $this->userRepo->getUserByUserName($userName);

            /*echo "<pre>";
            print_r($userInfo); exit;*/
            if (!empty($userInfo)) {
                //Checking User is frontend user
                if (!$this->isFrontendUser($userInfo)) {
                    Session::flash('error', trans('error_messages.creadential_not_valid'));
                    return redirect()->route('login_open');
                }

                //Checking User Active Status
                if (!$this->isAccountBlocked($userInfo)) {
                
                    Session::flash('error', trans('error_messages.creadential_not_valid'));
                    return redirect()->route('login_open');
                }
            } else {
                Session::flash('error', trans('error_messages.creadential_not_valid'));
                return redirect()->route('login_open');
            }

            


            if ($this->attemptLogin($request)) {
                //return $this->sendLoginResponse($request);//get response to redirect home page
                //check is_pwd_changed
                $is_pwd_changed = $userInfo->is_pwd_changed;
                if ($is_pwd_changed == 0) {
                    $this->guard()->logout();
                    $request->session()->invalidate();
                    return redirect(route('change_autogenerate_password', ['token' => Crypt::encrypt($userInfo->email)]));
                }
                if ($userInfo->user_type == 1) {

                    return redirect()->route('profile');
                }

                if ($userInfo->user_type == 2) {

                    return redirect()->route('company_profile');
                }
            } else {
                Session::flash('error', trans('error_messages.creadential_not_valid'));
                return redirect()->route('login_open');
            }
            // If the login attempt was unsuccessful we will increment the number of attempts
            // to login and redirect the user back to the login form. Of course, when this
            // user surpasses their maximum number of attempts they will get locked out.
            $this->incrementLoginAttempts($request);

            // return $this->sendFailedLoginResponse($request);
            //return Redirect::route('dashboard');
        } catch (Exception $ex) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($ex));
        }
    }

   
    

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request) {
        $this->validate($request, [
            $this->username() => 'required|string',
            'password' => 'required|string',
                ], [
            $this->username() . ".required" => trans('error_messages.req_user_name'),
            'password.required' => trans('error_messages.req_password')
                ]
        );
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request) {

        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect('/');
    }

    public function logoutchangepassword(Request $request) {

        $this->guard()->logout();

        $request->session()->invalidate();
        Session::flash('success', trans('success_messages.change_password_successfully'));
        //Session::flash('success', trans('success_messages.update_personal_successfully'));
        return redirect('/login');
    }

    
    
    /**
     * Check If retailer account is blocked
     *
     * @param object $request
     * @param object $user
     * @return boolean
     */
    protected function isAccountBlocked($user) {

        if (!empty($user) && $user->is_active == 1) {
            return true;
        }
        return false;
    }

    /**
     * Check If user is front-end user or not
     *
     * @param object $user
     * @return boolean
     */
    protected function isFrontendUser($user) {
        if (!empty($user) && ($user->user_type == 1 || $user->user_type == 2)) {

            return true;
        }
        return false;
    }

   

    /**
     * Redirect the user to the google authentication page.
     *
     * @return Response
     */
    public function redirectToProvider($provider) {
        return Socialite::driver($provider)->redirect();
    }

    public function welcomePage(Request $request) {
        
        $token = $request->get('token');
        if($token!="") {
            $email = Crypt::decrypt($token);
        } else {
            $email="";
        }
        Session::forget('uId');
        Session::forget('rId');
        Session::forget('go_on_right');
        return view('welcome',compact('email'));
    }

    public function verifyEmailExpire() {

        return view('auth.verify_email_expire');
    }

}
