<?php

namespace App\Http\Controllers\Auth;

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
use Illuminate\Contracts\Encryption\DecryptException;
use App\Inv\Repositories\Contracts\UserInterface as InvUserRepoInterface;
use App\Inv\Repositories\Contracts\ApplicationInterface as InvAppRepoInterface;
use App\Inv\Repositories\Libraries\Storage\Contract\StorageManagerInterface;
use App\Inv\Repositories\Contracts\Traits\StorageAccessTraits;
use App\Inv\Repositories\Contracts\Traits\ApiAccessTrait;
use App\Inv\Repositories\Models\DocumentMaster;
use App\Inv\Repositories\Models\UserReqDoc;
use App\Inv\Repositories\Models\Userkyc;


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

use RegistersUsers,
    StorageAccessTraits,
    ApiAccessTrait;

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
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(InvUserRepoInterface $user, InvAppRepoInterface $application) {
        $this->middleware('guest');
        $this->userRepo = $user;
        $this->application = $application;
        
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data) {

        //$userId  = Session::has('userId') ? Session::get('userId') : null;
        $arrData = [];
        $arrDetailData = [];
        $arrKyc = [];
        $arrKycData = [];
        $userName = $this->changeuserName($data['first_name'], $data['last_name'], $data['phone']);
        $arrData['f_name'] = $data['first_name'];
        $arrData['m_name'] = $data['middle_name'];
        $arrData['l_name'] = $data['last_name'];
        $arrData['email'] = $data['email'];
        $arrData['username'] = $userName;
        $arrData['country_code'] = $data['country_code'];
        $arrData['phone_no'] = $data['phone'];
        //$arrData['dob']               = $data['dob'];
        $arrData['user_type'] = 1;
        $arrData['is_email_verified'] = 0;
        $arrData['is_pwd_changed'] = 0;
        $arrData['is_email_verified'] = 0;
        $arrData['is_otp_verified'] = 0;
        $arrData['is_active'] = 0;
        $arrData['is_active'] = 0;
        $arrData['user_source'] = isset($data['user_source']) ? (Crypt::decrypt($data['user_source']) == 'trading') ? 'trading' : null : null;
        $userId = null;
        $userDataArray = $this->userRepo->save($arrData, $userId);
        if ($userDataArray->user_id > 0) {
            $arrDetailData['user_id'] = $userDataArray->user_id;
            $arrDetailData['country_id'] = $data['country_id'];
            // $arrDetailData['date_of_birth']    = $data['dob'];
            $date = str_replace('/', '-', $data['dob']);
            $arrDetailData['date_of_birth'] = date('Y-m-d', strtotime($date));
            //$arrDetailData['date_of_birth']    = '2018-05-05';
            $userDetail = $this->userRepo->saveUserDetails($arrDetailData);
            $arrKyc['user_id'] = $userDetail->user_id;
            $arrKyc['is_by_company'] = 0;
            $arrKyc['is_approve'] = 0;
            $arrKyc['is_kyc_completed'] = 0;
            $arrKyc['is_api_pulled'] = 0;


            $kycDetail = $this->userRepo->saveKycDetails($arrKyc);
            $arrKycProcessData = [];
            $fromList = $this->userRepo->getKycProcessForms('1');
            if ($fromList && $fromList->count()) {
                $i = 0;
                foreach ($fromList as $objf) {
                    $dataf = [];

                    $dataf['form_id'] = $objf->id;
                    $dataf['user_kyc_id'] = $kycDetail->kyc_id;
                    $dataf['kyc_type'] = $objf->kyc_type;
                    $dataf['is_required'] = $objf->is_required;
                    $dataf['status'] = '0';

                    $arrKycProcessData[$i] = $dataf;
                    $i++;
                }
            }

            if (count($arrKycProcessData)) {
                $this->userRepo->saveBatchData($arrKycProcessData);
            }
        }

        return $userDataArray;
    }

    /**
     * Create a new company user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function compcreate(array $data) {
        $arrData = [];
        $arrKyc = [];
        $arrKycData = [];
        $userName = $this->changeuserName($data['first_name'], $data['last_name'], $data['phone']);
        $arrData['f_name'] = $data['first_name'];
        $arrData['m_name'] = $data['middle_name'];
        $arrData['l_name'] = $data['last_name'];
        $arrData['email'] = $data['email'];
        $arrData['username'] = $userName;
        $arrData['country_code'] = $data['country_code'];
        $arrData['phone_no'] = $data['phone'];
        //$dateofBirth       = str_replace('/', '-', $data['dob']);
        // $arrData['date_of_birth']         = date('Y-m-d', strtotime($dateofBirth));
        $arrData['user_type'] = 2;
        $arrData['is_email_verified'] = 0;
        $arrData['is_pwd_changed'] = 0;
        $arrData['is_email_verified'] = 0;
        $arrData['is_otp_verified'] = 0;
        $arrData['is_active'] = 0;
        $arrData['is_active'] = 0;
        $arrData['user_source'] = isset($data['user_source']) ? $data['user_source'] : '';
        $userId = null;
        $userDataArray = $this->userRepo->save($arrData, $userId);
        if ($userDataArray->user_id > 0) {
            $arrDetailData['user_id'] = $userDataArray->user_id;
            $arrDetailData['country_id'] = $data['country_id'];
            $arrDetailData['corp_name'] = $data['company_name'];
            $arrDetailData['corp_license_number'] = $data['comp_trade_in'];
            $dateofCorpration = str_replace('/', '-', $data['comp_dof']);
            $arrDetailData['corp_date_of_formation'] = date('Y-m-d', strtotime($dateofCorpration));
            $dateofBirth = str_replace('/', '-', $data['dob']);
            $arrDetailData['date_of_birth'] = date('Y-m-d', strtotime($dateofBirth));
            $CorpDetail = $this->userRepo->saveCorpDetails($arrDetailData);



            $arrKyc['user_id'] = $CorpDetail->user_id;
            $arrKyc['corp_detail_id'] = $CorpDetail->corp_detail_id;
            $arrKyc['is_by_company'] = 0;
            $arrKyc['is_approve'] = 0;
            $arrKyc['is_kyc_completed'] = 0;
            $arrKyc['is_api_pulled'] = 0;

            $kycDetail = $this->userRepo->saveKycDetails($arrKyc);

            $arrKycProcessData = [];
            $fromList = $this->userRepo->getKycProcessForms('2');
            if ($fromList && $fromList->count()) {
                $i = 0;
                foreach ($fromList as $objf) {
                    $dataf = [];

                    $dataf['form_id'] = $objf->id;
                    $dataf['user_kyc_id'] = $kycDetail->kyc_id;
                    $dataf['kyc_type'] = $objf->kyc_type;
                    $dataf['is_required'] = $objf->is_required;
                    $dataf['status'] = '0';

                    $arrKycProcessData[$i] = $dataf;
                    $i++;
                }
            }
            if (count($arrKycProcessData)) {
                $this->userRepo->saveBatchData($arrKycProcessData);
            }
        }

        return $userDataArray;
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm(Request $request) {

        $token = $request->get('token');
        if($token!="") {
            $email = Crypt::decrypt($token);
            $userType = Crypt::encrypt('trading');
        } else {
            $email=false;
            $userType = false;
        }
        

        $userId = Session::has('userId') ? Session::get('userId') : 0;
        $userArr = [];
        if ($userId > 0) {
            $userArr = $this->userRepo->find($userId);
        }
        return view('auth.sign-up', compact('userArr','email','userType'));
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showCompRegistrationForm() {

        $userId = Session::has('userId') ? Session::get('userId') : 0;
        $userArr = [];
        if ($userId > 0) {
            $userArr = $this->userRepo->find($userId);
        }
        return view('auth.company-sign-up', compact('userArr'));
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\RegistrationFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function register(RegistrationFormRequest $request, StorageManagerInterface $storage) {


        try {
            $data = [];
            $arrFileData = [];
            $arrFileData = $request->all();
            //dd($arrFileData);
            //echo "ddddssssd"; exit;
            //Saving data into database
            $user = $this->create($arrFileData);


            if ($user) {
                if (!Session::has('userId')) {
                    Session::put('userId', (int) $user->user_id);
                }


                $this->sendVerificationLink($user->user_id);

                /* Session::flash('message',
                  trans('success_messages.basic_saved_successfully')); */
                //return redirect(route('education_details'));
                return redirect()->route('thanks');
            } else {
                return redirect()->back()->withErrors(trans('auth.oops_something_went_wrong'));
            }
        } catch (Exception $ex) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($ex));
        }
    }




     /**
     * Handle a registration request from API  for the application.
     *
     * @param  \Illuminate\Http\RegistrationFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function registerapi(Request $request) {

        try {
            $data = [];
            $arrFileData = [];
            $arrFileData = $request->all();
            //dd($arrFileData);
            //echo "ddddssssd"; exit;
            //Saving data into database
            $user = $this->create($arrFileData);
            //dd($user);
            if ($user) {
                if (!Session::has('userId')) {
                    Session::put('userId', (int) $user->user_id);
                }
                $this->sendVerificationLink($user->user_id);
                /* Session::flash('message',
                  trans('success_messages.basic_saved_successfully')); */
                //return redirect(route('education_details'));
                return response()->json(['result' => 'yes']);
            } else {
                return response()->json(['result' => 'fail']);
            }
        } catch (Exception $ex) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($ex));
        }
    }


    /**
     * Handle a registration request from API for the application.
     *
     * @param  \Illuminate\Http\RegistrationFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function compregisterapi(Request $request) {


        try {
            $data = [];
            $arrFileData = [];
            $arrFileData = $request->all();
            $user = $this->compcreate($arrFileData);
            if ($user) {
                if (!Session::has('userId')) {
                    Session::put('userId', (int) $user->user_id);
                }
                // echo $user->id; exit;
                $this->sendVerificationLinkcorp($user->user_id);
                return response()->json(['result' => 'yes']);
            } else {
                return response()->json(['result' => 'fail']);
            }
        } catch (Exception $ex) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($ex));
        }
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\RegistrationFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function compregister(RegistrationFormRequest $request, StorageManagerInterface $storage) {


        try {
            $data = [];
            $arrFileData = [];
            $arrFileData = $request->all();
            //dd($arrFileData);
            //echo "ddddssssd"; exit;
            //Saving data into database
            //unset($user);
            $user = $this->compcreate($arrFileData);



            if ($user) {
                if (!Session::has('userId')) {
                    Session::put('userId', (int) $user->user_id);
                }
                // echo $user->id; exit;
                $this->sendVerificationLinkcorp($user->user_id);
                //$this->sendVerificationLink($user->user_id);
                //Session::flash('message',trans('success_messages.basic_saved_successfully'));
                //return redirect(route('education_details'));
                return redirect()->route('thanks');
            } else {
                return redirect()->back()->withErrors(trans('auth.oops_something_went_wrong'));
            }
        } catch (Exception $ex) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($ex));
        }
    }

    /**
     * Show the application Thanks page.
     *
     * @return \Illuminate\Http\Response
     */
    public function showThanksForm() {

        $userId = Session::has('userId') ? Session::get('userId') : 0;
        $userArr = [];
        if ($userId > 0) {
            $userArr = $this->userRepo->find($userId);
        }
        return view('auth.thanks', compact('userArr'));
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

            return view('auth.otp', compact('userArr'), compact('tokenarr'));
        } else {
            return redirect()->route('/otp');
        }
    }

    /**
     * Send Verification link to user to verify email
     * @param Integer $userId
     */
    protected function sendVerificationLink($userId) {
        $userArr = [];
        $userArr = $this->userRepo->find($userId, ['email', 'first_name', 'last_name']);
        $verifyLink = route('verify_email', ['token' => Crypt::encrypt($userArr['email'])]);
        $verifyUserArr = [];
        $verifyUserArr['name'] = $userArr->f_name . ' ' . $userArr->l_name;
        $verifyUserArr['email'] = $userArr->email;
        $verifyUserArr['vlink'] = $verifyLink;
        Event::dispatch("user.email.verify", serialize($verifyUserArr));
    }

    protected function sendVerificationLinkcorp($userId) {

        $userArr = [];
        $userArr = $this->userRepo->find($userId, ['email', 'first_name', 'last_name']);
        $verifyLink = route('verify_email', ['token' => Crypt::encrypt($userArr['email'])]);
        $verifyUserArr = [];
        $verifyUserArr['name'] = $userArr->f_name . ' ' . $userArr->l_name;
        $verifyUserArr['email'] = $userArr->email;
        $verifyUserArr['vlink'] = $verifyLink;
        Event::dispatch("user.email.verifycorp", serialize($verifyUserArr));
    }


    /**
     * Send Verification link for API Users to user to verify email
     * @param Integer $userId
     */
    protected function sendApiVerificationLink($userId) {
        $userArr = [];
        $userArr = $this->userRepo->find($userId, ['email', 'first_name', 'last_name']);
        $verifyLink = route('verify_email', ['token' => Crypt::encrypt($userArr['email'])]);
        $verifyUserArr = [];
        $verifyUserArr['name'] = $userArr->f_name . ' ' . $userArr->l_name;
        $verifyUserArr['email'] = $userArr->email;
        $verifyUserArr['vlink'] = $verifyLink;
        Event::dispatch("user.email.verify", serialize($verifyUserArr));
    }

    /* ------------user email confirmation  send mail----------------- */

    protected function userEmailConfirmation($userId) {

        $userArr = [];
        $userArr = $this->userRepo->find($userId, ['email', 'first_name', 'last_name', 'user_type']);
        $verifyUserArr = [];
        $verifyUserArr['name'] = $userArr->f_name . ' ' . $userArr->l_name;
        $verifyUserArr['email'] = $userArr->email;
        $verifyUserArr['user_type'] = $userArr->user_type;
        Event::dispatch("user.email.confirm", serialize($verifyUserArr));
    }

    //otp confirmation mail
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

    /**
     * Verifying user email
     *
     * @param string $token
     * @return Response
     */
    public function verifyUser($token) {
        try {



            if (isset($token) && !empty($token)) {
                $email = Crypt::decrypt($token);
                $userCheckArr = $this->userRepo->getuserByEmail($email);

                //verify url expire after 24 hrs.
                //print_r($userCheckArr);die;
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

                    if ($userCheckArr->is_email_verified == 1 && $userCheckArr->is_otp_verified == 1) {
                        Session::flash('error', 'Email Already Verified.');
                        return redirect(route('login_open'));
                    }
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
                    //country_code,phone_no

                    // SEND otp register mobile no
                    $recipients   =   '+'.$userCheckArr->country_code.''.$userCheckArr->phone_no;
                    $message    =   "You are receiving this One Time Password (OTP) ".$Otpstring." to verify your mobile number with us. Please do not share this OTP with anyone.\n Thank You,\n Dexter Capital Financial Consultancy";
                    
                    Helpers::sendSMS($recipients,$message);

                    //$userMailArr['password'] = Session::pull('password');
                    // Send OTP mail to User
                   // Event::dispatch("user.sendotp", serialize($userMailArr));
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


    //admin verify email

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
                 //dd($userCheckArrOtp);
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
                    ///////////////////
                    //Document Requested
                    $userKycid = $this->application->getUserKycid($userId); //get user kyc id
                    $corpdata = UserReqDoc::where('user_kyc_id', $userKycid)->first();
                    $doc_for = $userCheckArr->user_type;
                    if (empty($corpdata)) {
                        $doc = DocumentMaster::where('doc_for', $doc_for)->where('type', 1)->where('is_active', 1)->whereNotNull('displayorder')->orderBy('displayorder','asc')->get();
                        UserReqDoc::createCorpDocRequired($doc, $userKycid, $userId);
                    }
                    
                    // add monitoring utility bill documenttype
                    if($userCheckArr->user_type==1){
                        $arrDoc_no=[2];
                        $resMstDoc  =   $this->userRepo->getMonitorMstDocByDocNo($arrDoc_no);
                    
                        if($resMstDoc && $resMstDoc->count()){
                            foreach($resMstDoc as $resDoc){
                                $documentData['doc_id']             =   $resDoc->id;
                                $documentData['document_number']    =   null;
                                $documentData['is_monitor']         =   1;
                                $documentData['form_id']            =   $resDoc->form_id;
                                $documentData['doc_for']            =   1;
                                $documentData['doc_no']             =   $resDoc->doc_no;
                                $documentData['is_active']          =   $resDoc->is_active;
                                $documentData['status']             =   0;
                                $documentData['doc_status']         =   0;
                                $documentData['issuance_date']      =   null;//isset($requestVar['issuance_date']) ? Helpers::getDateByFormat($requestVar['issuance_date'], 'd/m/Y', 'Y-m-d') : null;
                                $documentData['expire_date']        =   null;//isset($requestVar['expiry_date' . $i]) ? Helpers::getDateByFormat($requestVar['expiry_date' . $i], 'd/m/Y', 'Y-m-d') : null;
                                $documentData["user_kyc_id"]        =   $userKycid;
                                $documentData["created_by"]         =   $userCheckArr->user_id;
                                $documentData["updated_by"]         =   $userCheckArr->user_id;
                                $this->userRepo->storeUserKycDocumentTypeData($documentData,null);
                                
                            }
                           
                            
                        }
                    }else if($userCheckArr->user_type==2){
                        $userCheckArrData   =   $this->userRepo->getcorpDetail($userCheckArr->user_id);
                        $arrDoc_no          =   [3];
                        $resMstDoc          =   $this->userRepo->getMonitorMstDocByDocNo($arrDoc_no);
                    
                        if($resMstDoc && $resMstDoc->count()){
                            foreach($resMstDoc as $resDoc){
                                $documentData['doc_id']             =   $resDoc->id;
                                $documentData['document_number']    =   null;
                                $documentData['is_monitor']         =   1;
                                $documentData['form_id']            =   $resDoc->form_id;
                                $documentData['doc_for']            =   2;
                                $documentData['doc_no']             =   $resDoc->doc_no;
                                $documentData['is_active']          =   $resDoc->is_active;
                                $documentData['status']             =   0;
                                $documentData['doc_status']         =   0;
                                $documentData['issuance_date']      =   $userCheckArrData->corp_date_of_formation;//isset($requestVar['issuance_date']) ? Helpers::getDateByFormat($requestVar['issuance_date'], 'd/m/Y', 'Y-m-d') : null;
                                $issuance_date                      =   $documentData['issuance_date'];
                                $expire_date                        =   date('Y-m-d',strtotime('+1 year',strtotime($issuance_date)));
                                $documentData['expire_date']        =   $expire_date;//isset($requestVar['expiry_date' . $i]) ? Helpers::getDateByFormat($requestVar['expiry_date' . $i], 'd/m/Y', 'Y-m-d') : null;
                                $documentData['document_number']    =   $userCheckArrData->corp_license_number;            
                                $documentData["user_kyc_id"]        =   $userKycid;
                                $documentData["created_by"]         =   $userCheckArr->user_id;
                                $documentData["updated_by"]         =   $userCheckArr->user_id;
                                $this->userRepo->storeUserKycDocumentTypeData($documentData,null);
                                
                            }
                        }
                    }
                    
                    
                    
                    
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
        return view('auth.otp-thanks', compact('userArr'));
    }

    public function changePassword(Request $request) {
        //echo "<pre>";
        //print_r($request);

        $userId = Session::has('userId') ? Session::get('userId') : 0;

        $userArr = [];
        if ($userId > 0) {
            $userArr = $this->userRepo->find($userId);
        }
        return view('auth.otp-thanks', compact('userArr'));

        //===========================


        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error", "Your current password does not matches with the password you provided. Please try again.");
        }
        if (strcmp($request->get('current-password'), $request->get('new-password')) == 0) {
            //Current password and new password are same
            return redirect()->back()->with("error", "New Password cannot be same as your current password. Please choose a different password.");
        }
        $validatedData = $request->validate([
            'current-password' => 'required',
            'new-password' => 'required|string|min:6|confirmed',
        ]);
        //Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();
        return redirect()->back()->with("success", "Password changed successfully !");
    }

    ////make userName
    public function changeuserName($fistName, $lastName, $phone) {
       
        
        $fistNameNew = substr($fistName, 0, 3);
        $lastNameNew = strtolower(substr($lastName, 0, 3));//letter should be small
        $phoneNew = substr($phone, 6, 4);
        $userName = $fistNameNew . $lastNameNew . $phoneNew;
        $username1 = $this->checkUserNameExit($fistNameNew,$lastNameNew,$phone, 0);
       //  echo "in main==>".$username1; exit;
        return ucfirst($username1);
        //echo $username; exit;
       
    }

     public function checkUserNameExit($fistNameNew,$lastNameNew,$phone,$i)
    {
         
        if($i==0) {
            $phoneNew = substr($phone, 6, 4);
            $username = $fistNameNew . $lastNameNew . $phoneNew;
        } else {
           $randNo=substr(rand(0,$phone),0, 1);
           $phoneNew = substr($phone, 6, 4);
           $username = $fistNameNew . $lastNameNew . $phoneNew;
           $username=$username.$randNo;
        }
        $result = \App\Inv\Repositories\Models\User::where('username', $username)->first();
        if($result) {
             $i++;
            return  $this->checkUserNameExit($fistNameNew,$lastNameNew,$phone, $i);
            
        } 
       return $username ?: false;
    }

    public function expireOTP($otp_trans_id) {
        $currentDate = new DateTime();
        $arrUpdateOtp = [];
        $arrUpdateOtp['is_otp_expired'] = 1;
        $arrUpdateOtp['is_verified'] = 1;
        $arrUpdateOtp['otp_exp_time'] = $currentDate;
        $this->userRepo->updateOtp($arrUpdateOtp, $otp_trans_id);
    }

    public function userEmailCheck(Request $request) {
        $email = $request->userdata;
       
        $result = $this->userRepo->getUserByEmail($email);

        if ($result) {
            return "false";
        } else {
            return  "true";
        }
    }


}
