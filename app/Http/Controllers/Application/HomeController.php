<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use File;
use Crypt;
use Hash;
use Event;
use Helpers;
use DateTime;
use Session;
use App\Inv\Repositories\Contracts\UserInterface as InvUserRepoInterface;
use App\Inv\Repositories\Libraries\Storage\Contract\StorageManagerInterface;
use App\Inv\Repositories\Models\Master\EmailTemplate;
use App\Inv\Repositories\Contracts\ApplicationInterface as InvAppRepoInterface;

class HomeController extends Controller {

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
    public function __construct(InvUserRepoInterface $user,InvAppRepoInterface $application,StorageManagerInterface $storage){
        $this->userRepo = $user;
        $this->storage = $storage;
        $this->application = $application;
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
                    return view('auth.ch_systemgenerate_pass_form', $userdata);
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

                if ($userCheckArr->user_type == 2) {
                    $userCheckArrData = $this->userRepo->getcorpDetail($userId);
                    $userMailArr['name'] = $userCheckArr->f_name . ' ' . $userCheckArr->l_name;    
                    $userMailArr['email']       = $userCheckArr->email;
                    $userMailArr['userType']    = $userCheckArr->user_type;
                    $userMailArr['user_source']   = $userCheckArr->user_source;
                   // $userMailArr['corp_name']   = $userCheckArrData->corp_name;
                    //$userMailArr['password']   = $string;
                    $userMailArr['otp'] = $Otpstring;
                } else {
                    $userMailArr['name'] = $userCheckArr->f_name . ' ' . $userCheckArr->l_name;
                    $userMailArr['email'] = $userCheckArr->email;
                    $userMailArr['userType'] = $userCheckArr->user_type;
                    $userMailArr['user_source']   = $userCheckArr->user_source;
                    $userMailArr['otp'] = $Otpstring;
                }

                     //echo "<pre>";
                    //print_r($userMailArr); exit;

                    //$userMailArr['password'] = Session::pull('password');
                
                   // SEND otp register mobile no
                    $recipients   =   '+'.$userCheckArr->country_code.''.$userCheckArr->phone_no;
                    $message    =   "You are receiving this One Time Password (OTP) ".$Otpstring." to reset your Compliance Platform Password. Please do not share this OTP with anyone.\n Thank You,\n Dexter Capital Financial Consultancy";
                    
                    Helpers::sendSMS($recipients,$message);
                
                // Send OTP mail to User

               // Event::dispatch("user.otp_password_reset", serialize($userMailArr));//new implemented
                //==================
                return redirect()->route('changepass_otp', ['token' => Crypt::encrypt($token_data)]);
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

            /* if ($userId > 0) {
              $userArr = $this->userRepo->find($userId);
              } */

            return view('auth.changepassOtpForm', compact('userArr'), compact('tokenarr'));
            
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


                return redirect()->route('changepass_otp', ['token' => $token])->withErrors(trans('error_messages.invalid_otp'));
            }
        } else {
            return redirect()->route('changepass_otp', ['token' => $token])->withErrors(trans('error_messages.data_not_found'));
        }
    }


     //otp confirmation mail
    protected function thanksOtpConfirmation($userId) {

        $userArr = [];
        $verifyUserArr = [];
        $userArr = $this->userRepo->find($userId, ['email', 'first_name', 'last_name', 'phone_no']);
        $userCheckArr = $this->userRepo->getuserByEmail($userArr->email);


            if ($userCheckArr->user_type == 2) {
                $userCheckArrData = $this->userRepo->getcorpDetail($userId);
                $userMailArr['name']        = $userCheckArr->f_name . ' ' . $userCheckArr->l_name . ", " . $userCheckArrData->corp_name;
                $userMailArr['email']       = $userCheckArr->email;
                $userMailArr['username']       = $userCheckArr->username;
                $userMailArr['user_type']    = $userCheckArr->user_type;
                $userMailArr['corp_name']   = $userCheckArrData->corp_name;
                $userMailArr['phone_no'] =  $userCheckArr->phone_no;

            } else {
                $userMailArr['name'] = $userCheckArr->f_name . ' ' . $userCheckArr->l_name;
                $userMailArr['email'] = $userCheckArr->email;
                $userMailArr['username']       = $userCheckArr->username;
                $userMailArr['user_type'] = $userCheckArr->user_type;
                $userMailArr['phone_no'] = $userCheckArr->phone_no;
                $userMailArr['corp_name']   = '';

            }

           // echo "<pre>";
           // print_r($userCheckArr); exit;


        /*$verifyUserArr = [];
        $verifyUserArr['name'] = $userArr->f_name . ' ' . $userArr->l_name;
        $verifyUserArr['phone_no'] = $userArr->phone_no;
        $verifyUserArr['email'] = $userArr->email;
        $verifyUserArr['user_type'] = $userArr->user_type; */

       // Event::dispatch("user.email.otpconfirmation", serialize($userMailArr));
        Event::dispatch("user.email.otpconfirmationfchangepass", serialize($userMailArr));


    }

    public function expireOTP($otp_trans_id) {
        $currentDate = new DateTime();
        $arrUpdateOtp = [];
        $arrUpdateOtp['is_otp_expired'] = 1;
        $arrUpdateOtp['is_verified'] = 1;
        $arrUpdateOtp['otp_exp_time'] = $currentDate;
        $this->userRepo->updateOtp($arrUpdateOtp, $otp_trans_id);
    }

    public function documentUpdateForm(Request $request) {
        try {
             $userdata = [];
             $userdata['email'] = '';
             $userdata['token'] = '';
            return view('auth.document_update', $userdata);
            $token = $request->get('token');
            if (isset($token) && !empty($token)) {
                $arrDocument    =   ['2'=>'Passport'];
                
                $tokenData          =   Crypt::decrypt($token);
                $user_kyc_id        =   $tokenData['user_kyc_id'];
                $email              =   $tokenData['email'];
                $documenttype_id    =   $tokenData['documenttype_id'];
                $document_type      =   $tokenData['document_type'];
                
                $userCheckArr = $this->userRepo->getuserByEmail($email);
                
                if($userCheckArr != false){

                    $userdata = [];
                    $userdata['document_name'] = $arrDocument[$document_type];
                    $userdata['document_type'] = $document_type;
                    $userdata['token']         = $token;

                    return view('auth.document_update', $userdata);
                    
                }else{
                    
                    return redirect(route('login_open'))->withErrors(trans('error_messages.invalid_token'));
                }
            } else {
                return redirect(route('login_open'))->withErrors(trans('error_messages.invalid_token'));
            }
        } catch (DecryptException $ex) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($ex));
        }
    }

    public function updateDocumentOtp(Request $request) {
        $token = $request->get('token');

        if (isset($token) && !empty($token)) {

            $tokenData = Crypt::decrypt($token);
            $user_kyc_id        =   $tokenData['user_kyc_id'];
            $email              =   $tokenData['email'];
            $documenttype_id    =   $tokenData['documenttype_id'];
            $document_type      =   $tokenData['document_type'];

            $validatedData = $this->validate(
            $request, [
                'document_number' => 'required',
                'issuance_date' => 'required',
                'expire_date' => 'required',
                'document_file' => 'required',
                    ], [
                'document_number.required' => trans('common.error_messages.req_this_field'),
                'issuance_date.required' => trans('common.error_messages.req_this_field'),
                'expire_date.required' => trans('common.error_messages.req_this_field'),
                'document_file.required' => trans('common.error_messages.req_this_field')
                    ]
            );

            $inputData = [];
            if($document_type == '2'){
                $inputData['document_number'] = $request->get('document_number');
                $inputData['issuance_date'] = $request->get('issuance_date');
                $inputData['expire_date'] = $request->get('expire_date');
            }
            

            //$this->userRepo->storeData($inputData, $id);
            
            // sending otp for uploaded document confirmationm
            
                $userMailArr = [];
                $otpArr = [];
                $date = new DateTime;
                $currentDate = $date->format('Y-m-d H:i:s');
                $date->modify('+30 minutes');
                $formatted_date = $date->format('Y-m-d H:i:s');

                $prev_otp = $this->userRepo->getKycOtpsbyActive($user_kyc_id)->toArray();
                $Otpstring = Helpers::randomOTP();

                if(isset($prev_otp) && count($prev_otp) == 1){
                    $arrUpdateOtp = [];
                    $arrUpdateOtp['is_otp_expired'] = 1;
                    $arrUpdateOtp['otp_exp_time'] = $currentDate;
                    //dd($prev_otp[0]['otp_trans_id']);
                    $this->userRepo->updateKycOtp($arrUpdateOtp, (int) $prev_otp[0]['otp_trans_id']);
                }
                
                $otpArr['otp_no'] = $Otpstring;
                $otpArr['activity_id'] = 1;
                $otpArr['user_kyc_id'] = $user_kyc_id;
                $otpArr['is_otp_expired'] = 0;
                $otpArr['is_otp_resent'] = 0;
                $otpArr['otp_exp_time'] = $formatted_date;
                $otpArr['is_verified'] = 0;
                
                $this->userRepo->saveKycOtp($otpArr);
                
                if($userCheckArr->user_type == 2){
                    $userCheckArrData = $this->userRepo->getcorpDetail($user_kyc_id);
                    $userMailArr['name'] = $userCheckArr->f_name . ' ' . $userCheckArr->l_name . ", " . $userCheckArrData->corp_name;
                    $userMailArr['email'] = $userCheckArr->email;
                    $userMailArr['otp'] = $Otpstring;
                }else{
                    $userMailArr['name'] = $userCheckArr->f_name . ' ' . $userCheckArr->l_name;
                    $userMailArr['email'] = $userCheckArr->email;
                    $userMailArr['otp'] = $Otpstring;
                }

                //$userMailArr['password'] = Session::pull('password');
                // Send OTP mail to User
                //mail("amit.suman@prolitus.com", "test mail", "helo india this is a testing mail");
                
               // Event::dispatch("user.sendotp", serialize($userMailArr));

                 // SEND otp register mobile no
                    $recipients   =   '+'.$userCheckArr->country_code.''.$userCheckArr->phone_no;
                    $message    =   'You are receiving this One Time Password (OTP) '.$Otpstring.' to verify your mobile number with us.';

                    Helpers::sendSMS($recipients,$message);

            
        } else {
            
        }
    }

    public function verifyUpdateDocOtp(Request $request){
        $token = $request->get('token');
        $otp = $request->get('otp');

        if(isset($token) && !empty($token) && isset($otp) && !empty($otp)){

            $userCheckArr = $this->userRepo->getUserByOPT($otp);
            if ($userCheckArr != false) {

                //OTP CHECK
                $userId = (int) $userCheckArr->user_id;
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
                $this->userRepo->save($userArr, $userId);
                //===================================

                $tokenData = Crypt::decrypt($token);

                $inputData['document_number'] = $tokenData['document_number'];
                $inputData['issuance_date'] = $tokenData['issuance_date'];
                $inputData['expire_date'] = $tokenData['expire_date'];
                $id = $tokenData['id'];

                $this->userRepo->storeData($inputData, $id);

                // document upload
               // $uploadedDocs = $tokenData['uploadedDocs'];
                //store data in array
               /* foreach ($uploadedDocs as $doc) {
                    $array = [];
                    $array1 = [];
                    $array1['user_req_doc_id'] = 1;
                    $array1['doc_type'] = 1;
                    $array1['user_kyc_id'] = $user_kyc_id;
                    $array1['user_id'] = $user_id;
                    $array1['doc_id'] = $doc_id;

                    $array1['doc_name'] = $certificate;
                    $array1['doc_ext'] = $ext;
                    $array1['doc_status'] = 1;
                    $array1['enc_id'] = md5(rand(1, 9999));
                    $array1['created_by'] = $user_id;
                    $array1['updated_by'] = $user_id;

                    $array['updated_by'] = $user_id;
                    $array['is_upload'] = 1;
                    $result = Document::create($array1);
                }

                $res = UserReqDoc::where('user_req_doc_id', $user_req_doc_id)->update($array);
                */


                return redirect()->route('frontend_logout');
            } else {

                return redirect()->route('changepass_otp', ['token' => $token])->withErrors(trans('error_messages.invalid_otp'));
            }
        } else {
            return redirect()->route('changepass_otp', ['token' => $token])->withErrors(trans('error_messages.data_not_found'));
        }
    }

}
