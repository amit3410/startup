<?php

namespace App\Http\Controllers\Backend\Auth;
use File;
use Crypt;
use Hash;
use Event;
use Helpers;
use Session;
use DateTime;
use Redirect;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Inv\Repositories\Contracts\UserInterface as InvUserRepoInterface;

class LoginController extends Controller
{
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
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * User repository
     *
     * @var object
     */
    protected $userRepo;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(InvUserRepoInterface $user)
    {
        $this->middleware('guest')->except('logout');
        $this->userRepo = $user;
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('backend.auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        //$this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $userName    = $request['username'];
        $userInfo = $this->userRepo->getUserByUserName($userName);

      

       
        if ($this->attemptLogin($request)) {

             $is_pwd_changed = $userInfo->is_pwd_changed;
                if ($is_pwd_changed == 0) {
                    $this->guard()->logout();
                    $request->session()->invalidate();
                    return redirect(route('adchange_autogenerate_password', ['token' => Crypt::encrypt($userInfo->email)]));
                }
          
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $this->validate($request,
            [
            $this->username() => 'required|string|email',
            'password' => 'required|string',
            ],
            [
            $this->username().".required" => trans('error_messages.req_email'),
            $this->username().".email" => trans('error_messages.invalid_email'),
            'password.required' => trans('error_messages.req_password')
            ]
        );
    }


    public function adminlogoutchangepassword(Request $request) {

        $this->guard()->logout();

        $request->session()->invalidate();
        Session::flash('success', trans('success_messages.change_password_successfully'));
        //Session::flash('success', trans('success_messages.update_personal_successfully'));
         return redirect()->route('backend_login_open');
    }


    public function adminVerifyUser(Request $request)
    {

        try {

            
            $email = Crypt::decrypt($request->get('token'));
            if (isset($email) && !empty($email)) {
                $userCheckArr = $this->userRepo->getuserByEmail($email);
                //verify url expire after 24 hrs.
                $oldtime = $userCheckArr->created_at;
                date_default_timezone_set('Asia/Kolkata');
                $currtime = date('Y-m-d H:i:s');
                $currentTime = strtotime($currtime);
                $old_time = strtotime($oldtime);

                $subtractTime = round(abs($currentTime - $old_time) / 60, 2); // show minute
                //1440=24*60(minutes)
                if ($subtractTime > 1440) {
                    Session::flash('error', trans("forms.reset_Form.server_error.verify_email_msg"));
                    return redirect(route('verify_email_expire'));
                }

                if ($userCheckArr != false) {

                    /*if ($userCheckArr->is_email_verified == 1 && $userCheckArr->is_otp_verified == 1) {
                        Session::flash('error', 'Email Already Verified.');
                        return redirect(route('login_open'));
                    }*/
                    $userId = (int) $userCheckArr->user_id;


                    $this->userEmailConfirmation($userId);

                    $date = new DateTime;
                    $currentDate = $date->format('Y-m-d H:i:s');
                    $date->modify('+30 minutes');
                    $formatted_date = $date->format('Y-m-d H:i:s');

                    $userArr = [];
                    $userArr['is_email_verified'] = 1;
                    $userArr['email_verified_updatetime'] = $currentDate;
                    $this->userRepo->save($userArr, $userId);

                    //save opt
                    // echo "Current Date :->".$date;

                    $userMailArr = [];
                    $otpArr = [];

                    $prev_otp = $this->userRepo->getOtpsbyActive($userId)->toArray();
                    $Otpstring = Helpers::randomOTP();

                    if (isset($prev_otp) && count($prev_otp) == 1) {
                        $arrUpdateOtp = [];
                        $arrUpdateOtp['is_otp_expired'] = 1;
                        $arrUpdateOtp['otp_exp_time'] = $currentDate;
                        //dd($prev_otp[0]['otp_trans_id']);
                        $this->userRepo->updateOtp($arrUpdateOtp, (int) $prev_otp[0]['otp_trans_id']);
                    }
                    $otpArr['otp_no'] = $Otpstring;
                    $otpArr['activity_id'] = 1;
                    $otpArr['user_id'] = $userId;
                    $otpArr['is_otp_expired'] = 0;
                    $otpArr['is_otp_resent'] = 0;
                    $otpArr['otp_exp_time'] = $formatted_date;
                    $otpArr['is_verified'] = 0;
                    $this->userRepo->saveOtp($otpArr);

                        $userMailArr['name'] = $userCheckArr->f_name . ' ' . $userCheckArr->l_name;
                        $userMailArr['email'] = $userCheckArr->email;
                        $userMailArr['userType'] = $userCheckArr->user_type;
                        //$userMailArr['password']   = $string;
                        $userMailArr['otp'] = $Otpstring;

                    //country_code,phone_no

                    // SEND otp register mobile no
                    $recipients   =   '+'.$userCheckArr->country_code.''.$userCheckArr->phone_no;
                    $message    =   'You are receiving this One Time Password (OTP) '.$Otpstring.' to verify your mobile number with us. Please do not share this OTP with anyone.<br> Thank You,<br> Dexter Capital Financial Consultancy';
                    //echo "==>".$message; exit;
                    Helpers::sendSMS($recipients,$message);
                     // Send OTP mail to User
                    //Event::dispatch("user.sendotp", serialize($userMailArr));
                    Session::flash('message_div', trans('success_messages.email_verified_please_login'));

                    $alluserData = $this->userRepo->getUserDetail((int) $userId);
                    //$verifyLink             = route('verify_email', ['token' => Crypt::encrypt($userArr['email'])]);
                    return redirect()->route('otp', ['token' => Crypt::encrypt($userMailArr['email'])]);
                } else {
                    return redirect(route('login_open'))->withErrors(trans('error_messages.invalid_token'));
                }
            } else {
                return redirect(route('login_open'))->withErrors(trans('error_messages.data_not_found'));
            }
        } catch (DecryptException $ex) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($ex));
        }



    }




    /**
     * Show the application OTP page.
     *
     * @return \Illuminate\Http\Response
     */
    public function otpForm($token) {
        $tokenarr = [];
        $userArr = [];
        $date = new DateTime;
        $currentDate = $date->format('Y/m/d H:i:s');
        if (isset($token) && !empty($token)) {
            $email = Crypt::decrypt($token);
            $tokenarr['token'] = $token;
            $userArr = $this->userRepo->getUserByEmailforOtp($email);
        }


        if (isset($userArr)) {

            /* if ($userId > 0) {
              $userArr = $this->userRepo->find($userId);
              } */

            return view('backend.auth.adotp', compact('userArr'), compact('tokenarr'));
        } else {
            return redirect()->route('/adotp');
        }
    }
/**
     * Verifying user OTP
     *
     * @param string $token
     * @return Response
     */
    public function verifyotpUser(Request $request) {
        $otp = $request->get('otp');

        $email = Crypt::decrypt($request->get('token'));
        try {
            if (isset($otp) && !empty($otp)) {

                $userCheckArrOtp = $this->userRepo->getUserByOPT($otp);
                // dd($userCheckArr);
                if ($userCheckArrOtp != false) {
                    /* if ($userCheckArr->status == config('inv_common.USER_STATUS.Active')) {
                      return redirect(route('otp'))->withErrors(trans('error_messages.email_already_verified'));
                      } */
                    //echo $userCheckArr->user_id; exit;


                    $userId = (int) $userCheckArrOtp->user_id;
                    $userCheckArr = $this->userRepo->getfullUserDetail($userId);

                    $this->thanksOtpConfirmation($userId, $userCheckArr->user_type);

                    $userMailArr = [];
                    $userArr = [];
                    $this->expireOTP($userCheckArrOtp->otp_trans_id, $otp);
                    $string = Helpers::randomPassword();
                    $date = new DateTime;
                    $currentDate = $date->format('Y-m-d H:i:s');
                    $userArr['is_otp_verified'] = 1;
                    $userArr['is_otp_resent'] = 0;
                    $userArr['otp_verified_updatetime'] = $currentDate;
                    $userArr['is_active'] = 1;
                    $userArr['password'] = bcrypt($string);





                    $this->userRepo->save($userArr, $userId);
                    $userMailArr['name'] = $userCheckArr->f_name . ' ' . $userCheckArr->l_name;
                    $userMailArr['email'] = $userCheckArr->email;
                    $userMailArr['username'] = $userCheckArr->username;
                    $userMailArr['password'] = $string;
                    $userMailArr['user_type'] = $userCheckArr->user_type;

                    //$userMailArr['password'] = Session::pull('password');
                   
                    // add monitoring utility bill documenttype
                    //////////////////
                    // Send registration mail to user with password
                    if($userCheckArr->is_otp_verified == 0) {

                    Event::dispatch("user.registered", serialize($userMailArr));
                    } else {
                      Event::dispatch("RESET_PASSWORD_SUCCESSS", serialize($userMailArr));
                    }
                    return redirect()->route('otp_thanks');
                    //return redirect()->route('login_open');
                } else {
                    return redirect(route('otp', ['token' => Crypt::encrypt($email)]))->withErrors(trans('error_messages.invalid_otp'));
                }
            } else {
                return redirect(route('otp', ['token' => Crypt::encrypt($email)]))->withErrors(trans('error_messages.data_not_found'));
            }
        } catch (DecryptException $ex) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($ex));
        }
    }

    public function resendotpUser(Request $request) {
        $email = Crypt::decrypt($request->get('token'));
        $userMailArr = [];
        $i = 0;
        $userCheckArr = $this->userRepo->getuserByEmail($email);
        $userId = (int) $userCheckArr->user_id;
        $date = new DateTime;
        $currentDate = $date->format('Y-m-d H:i:s');
        $date->modify('+30 minutes');
        $formatted_date = $date->format('Y-m-d H:i:s');
        $otpArr = [];
        $Otpstring = Helpers::randomOTP();
        $countOtp = $this->userRepo->getOtps($userId)->toArray();
        //dd($countOtp);
        if (isset($countOtp)) {
            $firstData = $countOtp[0]['otp_exp_time'];
            $updatedTime = new DateTime($firstData);
            $currentDate = new DateTime();
            $interval = $updatedTime->diff($currentDate);
            $hours = $interval->format('%h');
            $minutes = $interval->format('%i');
            $expireTime = ($hours * 60 + $minutes);

            if ($expireTime >= 30) {
                $this->userRepo->updateOtpByExpiry(['is_otp_expired' => 1], $userId);
                $this->userRepo->updateOtpByExpiry(['is_otp_resent' => 3], $userId);

                $otpArr['otp_no'] = $Otpstring;
                $otpArr['activity_id'] = 1;
                $otpArr['user_id'] = $userId;
                $otpArr['is_otp_expired'] = 0;
                $otpArr['is_otp_resent'] = 0;
                $otpArr['otp_exp_time'] = $currentDate;
                $otpArr['is_verified'] = 1;
                $this->userRepo->saveOtp($otpArr);

                if ($userCheckArr->user_type == 2) {
                    $userCheckArrData = $this->userRepo->getcorpDetail($userId);
                    $userMailArr['name'] = $userCheckArr->f_name . ' ' . $userCheckArr->l_name;
                    $userMailArr['email'] = $userCheckArr->email;
                    $userMailArr['userType'] = $userCheckArr->user_type;
                    $userMailArr['corp_name'] = $userCheckArrData->corp_name;
                    $userMailArr['otp'] = $Otpstring;
                } else {
                    $userMailArr['name'] = $userCheckArr->f_name . ' ' . $userCheckArr->l_name;
                    $userMailArr['email'] = $userCheckArr->email;
                    $userMailArr['userType'] = $userCheckArr->user_type;
                    //$userMailArr['password']   = $string;
                    $userMailArr['otp'] = $Otpstring;
                }

                // SEND otp register mobile no
                    $recipients   =   '+'.$userCheckArr->country_code.''.$userCheckArr->phone_no;
                    $message    =   'You are receiving this One Time Password (OTP) '.$Otpstring.' to verify your mobile number with us.  Please do not share this OTP with anyone. <br> Thank You,<br> Dexter Capital Financial Consultancy';

                    Helpers::sendSMS($recipients,$message);

                //Event::dispatch("user.sendotp", serialize($userMailArr));
                return redirect(route('otp', ['token' => Crypt::encrypt($email)]))->withErrors(trans('success_messages.otp_sent_messages'));
            } else {
                $countOtp = $this->userRepo->getOtps($userId)->toArray();
                if (isset($countOtp) && count($countOtp) >= 3) {
                    return redirect(route('otp', ['token' => Crypt::encrypt($email)]))->withErrors(trans('success_messages.otp_attempts_finish'));
                } else {
                    $prev_otp = $this->userRepo->getOtpsbyActive($userId)->toArray();
                    if (isset($prev_otp) && count($prev_otp) == 1) {
                        $arrUpdateOtp = [];
                        $arrUpdateOtp['is_otp_expired'] = 1;
                        $arrUpdateOtp['otp_exp_time'] = $currentDate;
                        //dd($prev_otp[0]['otp_trans_id']);
                        $this->userRepo->updateOtp($arrUpdateOtp, (int) $prev_otp[0]['otp_trans_id']);
                        $otpArr['otp_no'] = $Otpstring;
                        $otpArr['activity_id'] = 1;
                        $otpArr['user_id'] = $userId;
                        $otpArr['is_otp_expired'] = 0;
                        $otpArr['is_otp_resent'] = 0;
                        $otpArr['otp_exp_time'] = $currentDate;
                        $otpArr['is_verified'] = 1;
                        $this->userRepo->saveOtp($otpArr);
                    }

                   /*Resend OTP START again and agian CODE */
                    if ($userCheckArr->user_type == 2) {
                    $userCheckArrData = $this->userRepo->getcorpDetail($userId);
                    $userMailArr['name'] = $userCheckArr->f_name . ' ' . $userCheckArr->l_name;
                    $userMailArr['email'] = $userCheckArr->email;
                    $userMailArr['userType'] = $userCheckArr->user_type;
                    $userMailArr['corp_name'] = $userCheckArrData->corp_name;
                    $userMailArr['otp'] = $Otpstring;
                } else {
                    $userMailArr['name'] = $userCheckArr->f_name . ' ' . $userCheckArr->l_name;
                    $userMailArr['email'] = $userCheckArr->email;
                    $userMailArr['userType'] = $userCheckArr->user_type;
                    //$userMailArr['password']   = $string;
                    $userMailArr['otp'] = $Otpstring;
                }
                /*Resend OTP end again and again CODE */
                    // SEND otp register mobile no
                    $recipients   =   '+'.$userCheckArr->country_code.''.$userCheckArr->phone_no;
                    $message    =   'You are receiving this One Time Password (OTP) '.$Otpstring.' to verify your mobile number with us.';

                    Helpers::sendSMS($recipients,$message);


                   // Event::dispatch("user.sendotp", serialize($userMailArr));
                    return redirect(route('otp', ['token' => Crypt::encrypt($email)]))->withErrors(trans('success_messages.otp_sent_messages'));
                }
            }
        }
    }


   /**
     * Show the application OTP page.
     *
     * @return \Illuminate\Http\Response
     */
    public function verifiedotpUser() {
        $userId = Session::has('userId') ? Session::get('userId') : 0;
        $userArr = [];

        if ($userId > 0) {
            $userArr = $this->userRepo->find($userId);

        }
        return view('backend.auth.otp-thanks', compact('userArr'));
    }

public function resetAccountPasswordForm(Request $request) {


        try {
            $token = $request->get('token');
            if (isset($token) && !empty($token)) {
                $email = Crypt::decrypt($token);
                $userCheckArr = $this->userRepo->getuserByEmail($email);
                if ($userCheckArr != false) {
                    if ($userCheckArr->is_pwd_changed == 1) {
                        Session::flash('error', 'You have already changed system generated password');
                        return redirect(route('login_open'));
                    }
                    $userdata = [];
                    $userdata['email'] = $userCheckArr->email;
                    $userdata['token'] = $token;
                    $userdata['user_type'] = $userCheckArr->user_type;
                    return view('backend.auth.ch_systemgenerate_pass_form', $userdata);
                } else {
                    return redirect(route('login_open'))->withErrors(trans('error_messages.invalid_token'));
                }
            } else {
                return redirect(route('login_open'))->withErrors(trans('error_messages.invalid_token'));
            }
        } catch (DecryptException $ex) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($ex));
        }
    }


    public function resetAccountPassword(Request $request) {

        try {
            $token = $request->get('token');
            if (isset($token) && !empty($token)) {
                $email = Crypt::decrypt($token);
                $userCheckArr = $this->userRepo->getuserByEmail($email);
                $validatedData = $this->validate(
                        $request, [
                    'current-password' => 'required',
                    'new-password' => 'required|min:8|max:15|regex:/^(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[^\w\s]).{8,}$/',
                    'new-password_confirmation' => 'required|same:new-password',
                        ], [
                    'password_confirmation.required' => trans('forms.ChgPass_Form.server_error.cur-req'),
                    'current-password.required' => trans('forms.ChgPass_Form.server_error.cur-req'),
                    'new-password.required' => trans('forms.ChgPass_Form.server_error.new-req'),
                    'new-password.min' => trans('forms.ChgPass_Form.server_error.min'),
                    'new-password.max' => trans('forms.ChgPass_Form.server_error.max'),
                    'new-password.regex' => trans('forms.ChgPass_Form.server_error.pass_rules'),
                    'new-password_confirmation.required' => trans('forms.ChgPass_Form.server_error.confirm_req'),
                    'new-password_confirmation.same' => trans('forms.ChgPass_Form.server_error.confirm_pass'),
                        ]
                );


                if (!(Hash::check($request->get('current-password'), $userCheckArr->password))) {
                    // The passwords matches

                    return redirect()->back()->with("error", trans("forms.ChgPass_Form.server_error.oldpass_msg"));
                }

                if (strcmp($request->get('current-password'), $request->get('new-password')) == 0) {
                    //Current password and new password are same
                    return redirect()->back()->with("error", trans("forms.ChgPass_Form.server_error.old_newpassmsg"));
                }

                //Change Password
                $newpassword = $request->get('new-password');

                // $password = bcrypt($request->get('new-password'));
                //$updata = [];
                // $updata['password'] = $password;
                // $rs = $this->userRepo->updateSystemGeneratedPass($updata, $email);


                $token_data = [];
                $token_data['email'] = $email;
                $token_data['password'] = $newpassword;
                //=======================

                $userId = $userCheckArr->user_id;
                $userMailArr = [];
                $otpArr = [];
                $date = new DateTime;
                $currentDate = $date->format('Y-m-d H:i:s');
                $date->modify('+30 minutes');
                $formatted_date = $date->format('Y-m-d H:i:s');

                $prev_otp = $this->userRepo->getOtpsbyActive($userId)->toArray();
                $Otpstring = Helpers::randomOTP();

                if(isset($prev_otp) && count($prev_otp) == 1){
                    $arrUpdateOtp = [];
                    $arrUpdateOtp['is_otp_expired'] = 1;
                    $arrUpdateOtp['otp_exp_time'] = $currentDate;
                    //dd($prev_otp[0]['otp_trans_id']);
                    $this->userRepo->updateOtp($arrUpdateOtp, (int) $prev_otp[0]['otp_trans_id']);
                }

                $otpArr['otp_no'] = $Otpstring;
                $otpArr['activity_id'] = 1;
                $otpArr['user_id'] = $userId;
                $otpArr['is_otp_expired'] = 0;
                $otpArr['is_otp_resent'] = 0;
                $otpArr['otp_exp_time'] = $formatted_date;
                $otpArr['is_verified'] = 0;
                $this->userRepo->saveOtp($otpArr);
               // echo "==>".$userCheckArr->user_type; exit;
                $userdata= $this->userRepo->getfullUserDetail($userId);//call for admin user get the user data
                $date = new DateTime;
                $currentDate = $date->format('Y-m-d H:i:s');

                $userArr['is_email_verified'] = 1;
                $userArr['is_otp_verified'] = 1;
                $userArr['is_otp_resent'] = 0;
                 $otpArr['activity_id'] = 1;
                $userArr['is_verified'] = 1;
                $userArr['otp_verified_updatetime'] = $currentDate;
                $userArr['is_active'] = 1;
                $userArr['password'] = bcrypt($newpassword);
                $userArr['is_pwd_changed'] = 1;

                if($userdata->is_by_admin == 1){
                   $userArr['user_type'] = 3;
                }
                $this->userRepo->save($userArr, $userId);

                // Send OTP mail to User

               // Event::dispatch("user.otp_password_reset", serialize($userMailArr));//new implemented
                //==================
                return redirect()->route('backend_login_open');
               // return redirect()->route('backend.auth.changepass_otp', ['token' => Crypt::encrypt($token_data)]);
            } else {
                return redirect(route('login_open'))->withErrors(trans('error_messages.invalid_token'));
            }
        } catch (DecryptException $ex) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($ex));
        }
    }


    /**
     * Show the application OTP page.
     *
     * @return \Illuminate\Http\Response
     */
    public function changepassOtpForm(Request $request) {
        $token = $request->get('token');
        $tokenarr = [];
        $userArr = [];
        $date = new DateTime;
        $currentDate = $date->format('Y/m/d H:i:s');
        if (isset($token) && !empty($token)) {
            $tokenData = Crypt::decrypt($token);
            $tokenarr['token'] = $token;
            $email = $tokenData['email'];
            $userArr = $this->userRepo->getUserByEmailforOtp($email);
        }


        if (isset($userArr)) {

      
            return view('backend.auth.changepassOtpForm', compact('userArr'), compact('tokenarr'));

        } else {
            // return redirect()->route('/otp');
        }
    }


     public function verifyChangePassOtp(Request $request) {
        $token = $request->get('token');
        $otp = $request->get('otp');
        if (isset($token) && !empty($token) && isset($otp) && !empty($otp)) {

            $tokenData = Crypt::decrypt($token);
            $email = $tokenData['email'];
            $new_password = $tokenData['password'];

            $userCheckArr = $this->userRepo->getUserByOPT($otp);

            $userId = (int) $userCheckArr['user_id'];
            if ($userCheckArr != false) {

                //OTP CHECK

                $userdata= $this->userRepo->getfullUserDetail($userId);//call for admin user get the user data

                $this->thanksOtpConfirmation($userId);

                $userMailArr = [];
                $userArr = [];
                $this->expireOTP($userCheckArr->otp_trans_id, $otp);



                $date = new DateTime;
                $currentDate = $date->format('Y-m-d H:i:s');
                $userArr['is_otp_verified'] = 1;
                $userArr['is_otp_resent'] = 0;
                $userArr['otp_verified_updatetime'] = $currentDate;
                $userArr['is_active'] = 1;
                $userArr['password'] = bcrypt($new_password);
                $userArr['is_pwd_changed'] = 1;

                if($userdata->is_by_admin == 1){
                   $userArr['user_type'] = 3;
                }

                $this->userRepo->save($userArr, $userId);
                /*  Admin redirect start  */
                if(isset($userdata->is_by_admin) && $userdata->is_by_admin == 1) {
                  return redirect()->route('changepassword_backend_login_open');
                }
                /*  Admin redirect  end */


               // $compliance_admin_email = config('common.admin_email');// Compliance admin email
                $compliance_admin_email = 'compliance.officer@sofodev.co';

                $userMailArr['email'] = $compliance_admin_email;
                $lang = config('common.EMAIL_LANG');
                 $userCheckArr = $this->userRepo->getfullUserDetail($userId);
                if($userCheckArr->user_type=='1') {
                   $userMailArr['name'] =$userCheckArr->f_name . ' ' . $userCheckArr->l_name;
                   $content = EmailTemplate::getEmailTemplate("sent_indiv_signup_notification_toadmin", $lang);
                }

                 if($userCheckArr->user_type=='2'){
                    $corpname = $this->application->getRegisterDetails($userId);
                    $userMailArr['name'] = $corpname['corp_name'];
                    $content = EmailTemplate::getEmailTemplate("sent_corp_signup_notification_toadmin", $lang);
                }
                $userMailArr['subject'] = $content['subject'];
                $userMailArr['message'] = $content['message'];
                 //dd($userMailArr);
                 Event::dispatch("sent.admin.signup.notification", serialize($userMailArr));
               // die;
                return redirect()->route('frontend_logout');

                //return redirect()->route('login_withotp', ['token' =>$token]);


            }else{


                return redirect()->route('adchangepass_otp', ['token' => $token])->withErrors(trans('error_messages.invalid_otp'));
            }
        } else {
            return redirect()->route('adchangepass_otp', ['token' => $token])->withErrors(trans('error_messages.data_not_found'));
        }
    }
    
    protected function userEmailConfirmation($userId) {

        $userArr = [];
        $userArr = $this->userRepo->find($userId, ['email', 'first_name', 'last_name', 'user_type']);
        $verifyUserArr = [];
        $verifyUserArr['name'] = $userArr->f_name . ' ' . $userArr->l_name;
        $verifyUserArr['email'] = $userArr->email;
        $verifyUserArr['user_type'] = $userArr->user_type;
        Event::dispatch("user.email.confirm", serialize($verifyUserArr));
    }

    protected function thanksOtpConfirmation($userId, $userType) {

        $verifyUserArr = [];

        // echo "==>".$userType; exit;
        if ($userType == 1) {
            $userCheckArr = $this->userRepo->getfullUserDetail($userId);
        } else if($userType == 2){
            $userCorp = $this->userRepo->getcorpDetail($userId);
            $userCheckArr = $this->userRepo->getfullUserDetail($userId);

            $verifyUserArr['corp_name'] = $userCorp->corp_name;
        } else if($userType == 3) {
            $userCheckArr = $this->userRepo->getfullUserDetail($userId);
        }

        $verifyUserArr['name'] = $userCheckArr->f_name . ' ' . $userCheckArr->l_name;
        $verifyUserArr['phone_no'] = $userCheckArr->phone_no;
        $verifyUserArr['email'] = $userCheckArr->email;
        $verifyUserArr['user_type'] = $userCheckArr->user_type;
        $verifyUserArr['country_code'] = $userCheckArr->country_code;


        Event::dispatch("user.email.otpconfirmation", serialize($verifyUserArr));
    }

     public function expireOTP($otp_trans_id) {
        $currentDate = new DateTime();
        $arrUpdateOtp = [];
        $arrUpdateOtp['is_otp_expired'] = 1;
        $arrUpdateOtp['is_verified'] = 1;
        $arrUpdateOtp['otp_exp_time'] = $currentDate;
        $this->userRepo->updateOtp($arrUpdateOtp, $otp_trans_id);
    }
}