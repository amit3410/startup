<?php

namespace App\Inv\Repositories\Events;

use Mail;
use Illuminate\Queue\SerializesModels;
use App\Inv\Repositories\Factory\Events\BaseEvent;
use App\Inv\Repositories\Models\Master\EmailTemplate;

class UserEventsListener extends BaseEvent
{

    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Event that would be fired on a user login
     *
     * @param object $user Logged in user object
     *
     */
    public function onLoginSuccess($user)
    {
        $user = unserialize($user);
        self::addActivityLog(1, trans('activity_messages.login_sucessfully'),
            $user);
    }

    /**
     * Event that would be fired on a failed login attempt
     *
     * @param array $user email data
     *
     * @since 0.1
     */
    public function onFailedLogin($user)
    {
        $user = unserialize($user);
        self::addActivityLog(2, trans('activity_messages.login_failed'), $user);
    }

    /**
     * Event that would be fired on a user logout
     *
     * @param object $user Logged in user object
     *
     * @since 0.1
     */
    public function onLogoutSuccess($user)
    {
        $user = unserialize($user);
        self::addActivityLog(3, trans('activity_messages.logout_sucessfully'),
            $user);
    }

    /**
     * Event that would be fired on a user verification
     *
     * @param object $user user data
     */
    public function onVerifyUser_old($user)
    {
        $user = unserialize($user);
        $lang = config('common.EMAIL_LANG');
        //Send mail to Case Manager
        $email_content = EmailTemplate::getEmailTemplate("VERIFYUSEREMAIL", $lang);
        if ($email_content) {
            $mail_body = str_replace(
                ['%name', '%otp'],
                [ucwords($user['name']),$user['otp']],
                $email_content->message
            );
            Mail::send('email', ['baseUrl'=>env('REDIRECT_URL',''),'varContent' => $mail_body,
                ],
                function ($message) use ($user, $email_content) {
                $message->from(config('common.FRONTEND_FROM_EMAIL'),
                    config('common.FRONTEND_FROM_EMAIL_NAME'));
                $message->to($user["email"], $user["name"])->subject($email_content->subject);
            });
        }
    }

    public function onVerifyUser($user)
    {
        $user = unserialize($user);
        $lang = config('common.EMAIL_LANG');
        //Send mail to Case Manager
        $email_content1 = EmailTemplate::getEmailTemplate("VERIFYUSEREMAIL", $lang);



        if ($email_content1) {
            $mail_body = str_replace(
                ['%name', '%link'],
                [ucwords($user['name']),
                $user['vlink']],
                $email_content1->message
            );


            Mail::send('email', ['baseUrl'=>env('REDIRECT_URL',''),'varContent' => $mail_body,
                ],
                function ($message) use ($user, $email_content1) {
                $message->from(config('common.FRONTEND_FROM_EMAIL'),
                    config('common.FRONTEND_FROM_EMAIL_NAME'));
                $message->to($user["email"], $user["name"])->subject($email_content1->subject);
            });
          // dd($email_content1, $mail_body);

        }

    }



    public function onVerifyUsercorp($user)
    {
        $user = unserialize($user);
        $lang = config('common.EMAIL_LANG');
        //Send mail to Case Manager
        $email_content1 = EmailTemplate::getEmailTemplate("VERIFYUSEREMAILCORP", $lang);


        if ($email_content1) {
            $mail_body = str_replace(
                ['%name', '%link'],
                [ucwords($user['name']),
                $user['vlink']],
                $email_content1->message
            );


            Mail::send('email', ['baseUrl'=>env('REDIRECT_URL',''),'varContent' => $mail_body,
                ],
                function ($message) use ($user, $email_content1) {
                $message->from(config('common.FRONTEND_FROM_EMAIL'),
                    config('common.FRONTEND_FROM_EMAIL_NAME'));
                $message->to($user["email"], $user["name"])->subject($email_content1->subject);
            });
          // dd($email_content1, $mail_body);

        }

    }


    /**
     * Event that would be fired on a user verification
     *
     * @param object $user user data
     */
    public function onUserRegistration($user)
    {
        $user = unserialize($user);
        $lang = config('common.EMAIL_LANG');
        //Send mail to User
        if($user['user_type'] == '1'){
           $email_content = EmailTemplate::getEmailTemplate("USER_REGISTERED", $lang);
        }

        if($user['user_type'] == '2'){
          $email_content = EmailTemplate::getEmailTemplate("USER_REGISTERED_CORP", $lang);
        }


        if($user['user_type'] == '3'){
          $email_content = EmailTemplate::getEmailTemplate("USER_REGISTERED", $lang);
        }

        if ($email_content) {
            $mail_body = str_replace(
                ['%name', '%username','%password'],
                [ucwords($user['name']),$user['username'],$user['password']],
                $email_content->message
            );

            Mail::send('email', ['baseUrl'=>env('REDIRECT_URL',''),'varContent' => $mail_body,
                ],
                function ($message) use ($user, $email_content) {
                $message->from(config('common.FRONTEND_FROM_EMAIL'),
                    config('common.FRONTEND_FROM_EMAIL_NAME'));
                $message->to($user["email"], $user["name"])->subject($email_content->subject);
            });
        }
    }

     /**
     * Event that would be fired on a user verification email
     *
     * @param object $user user data
     */

    public function onSendOtp($user)
    {
        $user = unserialize($user);
        $lang = config('common.EMAIL_LANG');
        //Send mail to User

        if($user['userType'] == 1) {

            $email_content = EmailTemplate::getEmailTemplate("OTP_SEND", $lang);
            if ($email_content) {
                $mail_body = str_replace(
                    ['%name', '%otp'],
                    [ucwords($user['name']),$user['otp']],
                    $email_content->message
                );
            }
        } 
        if($user['userType'] == 2) {
        
            $email_content = EmailTemplate::getEmailTemplate("OTP_SEND_CORP", $lang);
            if ($email_content) {
                $mail_body = str_replace(
                    ['%name', '%otp','%corp_name'],
                    [ucwords($user['name']),$user['otp'],$user['corp_name']],
                    $email_content->message
                );
             }
        } 


                Mail::send('email', ['baseUrl'=>env('REDIRECT_URL',''),'varContent' => $mail_body,
                    ],
                    function ($message) use ($user, $email_content) {
                    $message->from(config('common.FRONTEND_FROM_EMAIL'),
                        config('common.FRONTEND_FROM_EMAIL_NAME'));
                    $message->to($user["email"], $user["name"])->subject($email_content->subject);
                });
        
    }



    public function onForgotPassword($user) {
        $user = unserialize($user);
        $lang = 'eng';


        //Send mail to User
        $email_content = EmailTemplate::getEmailTemplate("FORGOT_PASSWORD", $lang);



        if ($email_content) {
            $mail_body = str_replace(
                ['%name', '%reset_link','%username'],
                [ucwords($user['name']),$user['reset_link'],$user['username']],
                $email_content->message
            );

            Mail::send('email', ['baseUrl'=>env('REDIRECT_URL',''),'varContent' => $mail_body,
                ],
                function ($message) use ($user, $email_content) {
                $message->from(config('common.FRONTEND_FROM_EMAIL'),
                    config('common.FRONTEND_FROM_EMAIL_NAME'));
                $message->to($user["email"], $user["name"])->subject($email_content->subject);
            });
        }
    }

    public function onResetPasswordSuccess($user) {
        $user = unserialize($user);
        $lang = config('common.EMAIL_LANG');
        //Send mail to User

        
        $email_content = EmailTemplate::getEmailTemplate("RESET_PASSWORD_SUCCESSS", $lang);
        if ($email_content) {
            $mail_body = str_replace(
                ['%name','%username'],
                [ucwords($user['name']), $user['username']],
                $email_content->message
            );

            Mail::send('email', ['baseUrl'=>env('REDIRECT_URL',''),'varContent' => $mail_body,
                ],
                function ($message) use ($user, $email_content) {
                $message->from(config('common.FRONTEND_FROM_EMAIL'),
                    config('common.FRONTEND_FROM_EMAIL_NAME'));
                $message->to($user["email"], $user["name"])->subject($email_content->subject);
            });
        }
    }


     public function verificationConfirmation($user) {

        $user = unserialize($user);
        $lang = config('common.EMAIL_LANG');
        //Send mail to User
        if($user['user_type'] == '1'){
          $email_content = EmailTemplate::getEmailTemplate("CONFIRMATIONEMAIL", $lang);
        }

        if($user['user_type'] == '2'){
          $email_content = EmailTemplate::getEmailTemplate("CORP_CONFIRMATIONEMAIL", $lang);
        }

         if($user['user_type'] == '3'){
          $email_content = EmailTemplate::getEmailTemplate("CONFIRMATIONEMAIL", $lang);
        }

        if ($email_content) {
            $mail_body = str_replace(
                ['%name','%email'],
                [ucwords($user['name']),$user['email']],
                $email_content->message
            );

            Mail::send('email', ['varContent' => $mail_body,
                ],
                function ($message) use ($user, $email_content) {
                $message->from(config('common.FRONTEND_FROM_EMAIL'),
                    config('common.FRONTEND_FROM_EMAIL_NAME'));
                $message->to($user["email"], $user["name"])->subject($email_content->subject);
            });

        }
    }

    public function otpVerificationConfirmationMail($user) {

        $user = unserialize($user);
        $lang = config('common.EMAIL_LANG');
        //Send mail to User
        if($user['user_type'] == '1'){
          $email_content = EmailTemplate::getEmailTemplate("MOBILE_VERIFY_CONFIRM", $lang);
            if ($email_content) {
                $mail_body = str_replace(
                    ['%name','%mobile','%countrycode'],
                    [ucwords($user['name']),$user['phone_no'],$user['country_code']],
                    $email_content->message
                );

                Mail::send('email', ['varContent' => $mail_body,
                    ],
                    function ($message) use ($user, $email_content) {
                    $message->from(config('common.FRONTEND_FROM_EMAIL'),
                        config('common.FRONTEND_FROM_EMAIL_NAME'));
                    $message->to($user["email"], $user["name"])->subject($email_content->subject);
                });

           }
        }

        if($user['user_type'] == '2'){
          $email_content = EmailTemplate::getEmailTemplate("MOBILE_VERIFY_CONFIRM_CORP", $lang);

                if ($email_content) {
                $mail_body = str_replace(
                    ['%name','%mobile','%corp_name','%countrycode'],
                [ucwords($user['name']),$user['phone_no'],$user['corp_name'],$user['country_code']],
                    $email_content->message
                );

                Mail::send('email', ['varContent' => $mail_body,
                    ],
                    function ($message) use ($user, $email_content) {
                    $message->from(config('common.FRONTEND_FROM_EMAIL'),
                        config('common.FRONTEND_FROM_EMAIL_NAME'));
                    $message->to($user["email"], $user["name"])->subject($email_content->subject);
                });

            }
        }





    }

    //first change password confirmation
    public function firstChangePasswordConfirmationMail($user) {

        $user = unserialize($user);
        $lang = config('common.EMAIL_LANG');
        //Send mail to User
       // dd($user);
        if($user['user_type'] == 1) {
            $email_content = EmailTemplate::getEmailTemplate("FIRST_PASSWORD_CHANGE_INDIVIDUAL", $lang);
        } else if($user['user_type'] == 2) {
            $email_content = EmailTemplate::getEmailTemplate("FIRST_PASSWORD_CHANGE_CORP", $lang);
        }


        if ($email_content) {
            $mail_body = str_replace(
                ['%name','%username'],
                [ucwords($user['name']),$user['username']],
                $email_content->message
            );

            Mail::send('email', ['varContent' => $mail_body,
                ],
                function ($message) use ($user, $email_content) {
                $message->from(config('common.FRONTEND_FROM_EMAIL'),
                    config('common.FRONTEND_FROM_EMAIL_NAME'));
                $message->to($user["email"], $user["name"])->subject($email_content->subject);
            });

        }
       
    }

    // Indivisual kyc completed  sent mail
    public function kycCompletedMail($user) {

        $user = unserialize($user);
        $lang = config('common.EMAIL_LANG');
        //Send mail to User
        $email_content = EmailTemplate::getEmailTemplate("INDIVISUAL_KYC_COMPLETED", $lang);

        if ($email_content) {
            $mail_body = str_replace(
                ['%name'],
                [ucwords($user['name']) ],
                $email_content->message
            );

            Mail::send('email', ['varContent' => $mail_body,
                ],
                function ($message) use ($user, $email_content) {
                $message->from(config('common.FRONTEND_FROM_EMAIL'),
                    config('common.FRONTEND_FROM_EMAIL_NAME'));
                $message->to($user["email"], $user["name"])->subject($email_content->subject);
            });

        }
    }

    // Corporate kyc completed  sent mail
    public function corporatekycCompletedSentMail($user) {

        $user = unserialize($user);
        $lang = config('common.EMAIL_LANG');
        //Send mail to User
        $email_content = EmailTemplate::getEmailTemplate("CORPORATE_KYC_COMPLETE_CONFIRMATION", $lang);

        if ($email_content) {
            $mail_body = str_replace(
                ['%name'],
                [ucwords($user['name']) ],
                $email_content->message
            );

            Mail::send('email', ['varContent' => $mail_body,
                ],
                function ($message) use ($user, $email_content) {
                $message->from(config('common.FRONTEND_FROM_EMAIL'),
                    config('common.FRONTEND_FROM_EMAIL_NAME'));
                $message->to($user["email"], $user["name"])->subject($email_content->subject);
            });

        }
    }

     // Indivisual and corporate Account Unlock
    public function indivisualCorpUnlockAccount($user) {

        $user = unserialize($user);
        $lang = config('common.EMAIL_LANG');
        //Send mail to User

        if($user['usertype'] == 1) {
        $email_content = EmailTemplate::getEmailTemplate("INDI_ACCOUNT_UNLOCK", $lang);
        }

        if($user['usertype'] == 2) {
        $email_content = EmailTemplate::getEmailTemplate("CORPORATE_UNLOCK_ACCOUNT", $lang);
        }
        if ($email_content) {
            $mail_body = str_replace(
                ['%name'],
                [ucwords($user['name']) ],
                $email_content->message
            );

            Mail::send('email', ['varContent' => $mail_body,
                ],
                function ($message) use ($user, $email_content) {
                $message->from(config('common.FRONTEND_FROM_EMAIL'),
                    config('common.FRONTEND_FROM_EMAIL_NAME'));
                $message->to($user["email"], $user["name"])->subject($email_content->subject);
            });

        }
    }

    // demo cron job
    public function demoemail($user) {

        $user = unserialize($user);
        $lang = config('common.EMAIL_LANG');
        //Send mail to User




        $email_content = EmailTemplate::getEmailTemplate("CORPORATE_UNLOCK_ACCOUNT", $lang);

        if ($email_content) {
            $mail_body = str_replace(
                ['%name'],
                [ucwords($user['name']) ],
                $email_content->message
            );

            Mail::send('email', ['varContent' => $mail_body,
                ],
                function ($message) use ($user, $email_content) {
                $message->from(config('common.FRONTEND_FROM_EMAIL'),
                    config('common.FRONTEND_FROM_EMAIL_NAME'));
                $message->to($user["email"], $user["name"])->subject($email_content->subject);
            });

        }
    }





    /**
     * Event that would be fired on a user final Approved
     *
     * @param object $user user data
     */
    public function onFinalApprove($user)
    {
        $user = unserialize($user);
        $lang = config('common.EMAIL_LANG');
        //Send mail to User
        if($user['user_type'] == '1'){
           $email_content = EmailTemplate::getEmailTemplate("INDIVIDUAL_FINAL_APPROVE", $lang);
        }

        if($user['user_type'] == '2'){
          $email_content = EmailTemplate::getEmailTemplate("CORPORATE_FINAL_APPROVE", $lang);
        }


        if ($email_content) {
            $mail_body = str_replace(
                ['%name', '%corpname'],
                [ucwords($user['name']),$user['corpname']],
                $email_content->message
            );

            Mail::send('email', ['baseUrl'=>env('REDIRECT_URL',''),'varContent' => $mail_body,
                ],
                function ($message) use ($user, $email_content) {
               $message->from(config('common.FRONTEND_FROM_EMAIL'),
                    config('common.FRONTEND_FROM_EMAIL_NAME'));
                $message->to($user["email"], $user["name"])->subject($email_content->subject);
            });
        }
    }



     /**
     * Event that would be fired on a user final Decline
     *
     * @param object $user user data
     */
    public function onFinalDecline($user)
    {
        $user = unserialize($user);
        $lang = config('common.EMAIL_LANG');
        //Send mail to User
        if($user['user_type'] == '1'){
           $email_content = EmailTemplate::getEmailTemplate("INDIVIDUAL_FINAL_DECLINE", $lang);
        }

        if($user['user_type'] == '2'){
          $email_content = EmailTemplate::getEmailTemplate("CORPORATE_FINAL_DECLINE", $lang);
        }


        if ($email_content) {
            $mail_body = str_replace(
                ['%name', '%corpname'],
                [ucwords($user['name']),$user['corpname']],
                $email_content->message
            );

            Mail::send('email', ['baseUrl'=>env('REDIRECT_URL',''),'varContent' => $mail_body,
                ],
                function ($message) use ($user, $email_content) {
                $message->from(config('common.FRONTEND_FROM_EMAIL'),
                    config('common.FRONTEND_FROM_EMAIL_NAME'));
                $message->to($user["email"], $user["name"])->subject($email_content->subject);
            });
        }
    }
    
    //
    
     /**
     * Event that would be fired on a user final Decline
     *
     * @param object $user user data
     */
    public function onMonitoringDocApproval($user)
    {
        $user = unserialize($user);
        $lang = config('common.EMAIL_LANG');
        //Send mail to User


        $email_content = EmailTemplate::getEmailTemplate("PROFILE_MONITORING_DOCUMENT_APPROVED", $lang);
        

        if ($email_content) {
            
            $mail_body = str_replace(
                ['%name', '%corpname','%doc_status','%document_name'],
                [ucwords($user['name']),$user['corp_name'],$user['doc_status'],$user['document_name']],
                $email_content->message
            );
            
            $sub=str_replace(['%status'],[ucfirst($user['doc_status'])],$email_content->subject);
            
            Mail::send('email', ['baseUrl'=>env('REDIRECT_URL',''),'varContent' => $mail_body,
                ],
                function ($message) use ($user, $email_content,$sub) {
               $message->from(config('common.FRONTEND_FROM_EMAIL'),
                    config('common.FRONTEND_FROM_EMAIL_NAME'));
                $message->to($user["email"], $user["name"])->subject($sub);
            });
        }
    }
    
    /**
     * Event that would be fired on a user final Approved
     *
     * @param object $user user data
     */
    public function onDocumentExpireReminder($user)
    {
        $user = unserialize($user);
        
        $lang = config('common.EMAIL_LANG');

        if($user['document_type']==1){
            
            $email_content = EmailTemplate::getEmailTemplate("PASSPORT_EXPIRY_REMINDER_INDV", $lang);
            
            
        }else if($user['document_type']==2){
            if($user['user_type']==1){
               $email_content = EmailTemplate::getEmailTemplate("UTILITY_BILL_EXPIRY_REMINDER_INDV", $lang); 
            }else{
                $email_content = EmailTemplate::getEmailTemplate("UTILITY_BILL_EXPIRY_REMINDER_CORP", $lang); 
            }           
            
        }else if($user['document_type']==3){
            
            $email_content = EmailTemplate::getEmailTemplate("COMPANY_LICENSE_EXPIRY_REMINDER_CORP", $lang); 
            
        }
        
        

       

        if ($email_content) {
            if($user['document_type']==1){
                if($user['corp_name']!=''){
                    $user['corp_name']=$user['corp_name'].',';
                }
                
            }
            $mail_body = str_replace(
                ['%name', '%corp_name','%days','%update_document_link'],
                [ucwords($user['name']),$user['corp_name'],$user['days'],$user['update_document_link']],
                $email_content->message
                );
            
            
        //echo $mail_body; exit;

            Mail::send('email', ['baseUrl'=>env('REDIRECT_URL',''),'varContent' => $mail_body,
                ],
                function ($message) use ($user, $email_content) {
                $message->from(config('common.FRONTEND_FROM_EMAIL'),
                    config('common.FRONTEND_FROM_EMAIL_NAME'));
                $message->to($user["email"], $user["name"])->subject($email_content->subject);
            });
        }
   }
   
    /**
     * Event that would be fired on a user final Approved
     *
     * @param object $user user data
     */
    public function onDocumentUpdateNotification($user)
    {
        $user = unserialize($user);
        $lang = config('common.EMAIL_LANG');

        if($user['document_type']==1){
            
            $email_content = EmailTemplate::getEmailTemplate("PASSPORT_UPDATED_NOTIFICATION_INDV", $lang);
        
            
        }else if($user['document_type']==2){
            if($user['user_type']==1){
               $email_content = EmailTemplate::getEmailTemplate("UTILITY_BILL_UPDATED_INDV", $lang); 
            }else{
                $email_content = EmailTemplate::getEmailTemplate("UTILITY_BILL_UPDATED_CORP", $lang); 
            }           
            
        }else if($user['document_type']==3){
            
            $email_content = EmailTemplate::getEmailTemplate("COMPANY_LICENSE_UPDATED_CORP", $lang); 
            
        }
        
        

       

        if ($email_content) {
            if($user['document_type']==1){
                if($user['corp_name']!=''){
                    $user['corp_name']=$user['corp_name'].',';
                }
                
            }
            $mail_body = str_replace(
                ['%name', '%corp_name'],
                [ucwords($user['name']),$user['corp_name']],
                $email_content->message
                );
            
            
        

            Mail::send('email', ['baseUrl'=>env('REDIRECT_URL',''),'varContent' => $mail_body,
                ],
                function ($message) use ($user, $email_content) {
                $message->from(config('common.FRONTEND_FROM_EMAIL'),
                    config('common.FRONTEND_FROM_EMAIL_NAME'));
                $message->to($user["email"], $user["name"])->subject($email_content->subject);
            });
        }
   }
   
   /**
     * Event that would be fired on a user final Approved
     *
     * @param object $user user data
     */
    public function onDocumentUpdateNotificationAmin($user)
    {
        $user = unserialize($user);
        $lang = config('common.EMAIL_LANG');
        if($user['document_type']==1){
            
            $email_content = EmailTemplate::getEmailTemplate("PASSPORT_UPDATED_NOTIFICATION_COMPLIANCE_ADMIN", $lang);
        
            
        }else if($user['document_type']==2){
            
            $email_content = EmailTemplate::getEmailTemplate("UTILITY_BILL_UPDATED_ADMIN", $lang); 
                       
            
        }else if($user['document_type']==3){
            
            $email_content = EmailTemplate::getEmailTemplate("COMPANY_LICENSE_UPDATED_ADMIN", $lang); 
            
        }
        
        

       

        if ($email_content) {
            if($user['document_type']==1){
                if($user['corp_name']!=''){
                    $user['corp_name']=$user['corp_name'].',';
                }
                
            }
            $mail_body = str_replace(
                ['%name', '%corp_name'],
                [ucwords($user['name']),$user['corp_name']],
                $email_content->message
                );
            
            
            Mail::send('email', ['baseUrl'=>env('REDIRECT_URL',''),'varContent' => $mail_body,
                ],
                function ($message) use ($user, $email_content) {
               $message->from(config('common.FRONTEND_FROM_EMAIL'),
                    config('common.FRONTEND_FROM_EMAIL_NAME'));
                $message->to(config('common.ADMIN_EMAIL'), $user["name"])->subject($email_content->subject);
            });
        }
   }
    //

    //send user credential by admin

    public function sendUserCredential($user)
    {
        $user = unserialize($user);
        $lang = config('common.EMAIL_LANG');
        $email_content = EmailTemplate::getEmailTemplate("SEND_ROLE_CREDENTIAL_BYADMIN", $lang);


        if ($email_content) {
            $mail_body = str_replace(
                ['%name', '%username', '%password'],
                [ucwords($user['name']),$user['username'],$user['password']],
                $email_content->message
            );

            Mail::send('email', ['varContent' => $mail_body,
                ],
                function ($message) use ($user, $email_content) {
                $message->from(config('common.FRONTEND_FROM_EMAIL'),
                    config('common.FRONTEND_FROM_EMAIL_NAME'));
                $message->to($user['email'], $user["name"])->subject($email_content->subject);
            });
        }

        
    }
    
    // OTP RESET PASSWORD
    public function otpPasswordReset($user)
    {
        $user = unserialize($user);
        $lang = config('common.EMAIL_LANG');
        //Send mail to Case Manager

        if($user['user_source'] =='trading') {
            $email_content1 = EmailTemplate::getEmailTemplate("Trading_otp_reset_password", $lang);
        } else{
              $email_content1 = EmailTemplate::getEmailTemplate("OTP_FOR_PASSWORD_RESET", $lang);
        }

        //echo "==>".$user['user_source'];
       // echo $email_content1;
        //exit;

        if ($email_content1) {
            $mail_body = str_replace(
                ['%name', '%otp'],
                [ucwords($user['name']),
                $user['otp']],
                $email_content1->message
            );


            Mail::send('email', ['baseUrl'=>env('REDIRECT_URL',''),'varContent' => $mail_body,
                ],
                function ($message) use ($user, $email_content1) {
                $message->from(config('common.FRONTEND_FROM_EMAIL'),
                    config('common.FRONTEND_FROM_EMAIL_NAME'));
                $message->to($user["email"], $user["name"])->subject($email_content1->subject);
            });
        }
        
     } 

    //verify admin email

    public function adminVerifyUserRole_old($user)
    {
        $user = unserialize($user);
        $lang = config('common.EMAIL_LANG');
        //Send mail to Case Manager
        $email_content1 = EmailTemplate::getEmailTemplate("ADMIN_VERIFY_USEREMAIL", $lang);
        if ($email_content1) {
            $mail_body = str_replace(
                ['%name', '%link'],
                [ucwords($user['name']),
                $user['vlink']],
                $email_content1->message
            );
            Mail::send('email', ['baseUrl'=>env('REDIRECT_URL',''),'varContent' => $mail_body,
                ],
                function ($message) use ($user, $email_content1) {
                $message->from(config('common.FRONTEND_FROM_EMAIL'),
                    config('common.FRONTEND_FROM_EMAIL_NAME'));
                $message->to($user["email"], $user["name"])->subject($email_content1->subject);
            });
        }
     }



     public function adminVerifyUserRole($user)
    {
        $user = unserialize($user);
        $lang = config('common.EMAIL_LANG');
        //Send mail to Case Manager
        $email_content1 = EmailTemplate::getEmailTemplate("ADMIN_VERIFY_USEREMAIL", $lang);
        if ($email_content1) {
            $mail_body = str_replace(
                ['%name', '%rolename','%link','%username','%password'],
                [ucwords($user['name']),$user['roleName'],$user['vlink'],$user['username'],$user['password']],
                $email_content1->message
            );
            Mail::send('email', ['baseUrl'=>env('REDIRECT_URL',''),'varContent' => $mail_body,
                ],
                function ($message) use ($user, $email_content1) {
                $message->from(config('common.FRONTEND_FROM_EMAIL'),
                    config('common.FRONTEND_FROM_EMAIL_NAME'));
                $message->to($user["email"], $user["name"])->subject($email_content1->subject);
            });
        }
     }

     //send email for other docuemnt Request
    public function UserOtherDocumentRequest($user)
    {
        $user = unserialize($user);
        $lang = config('common.EMAIL_LANG');
        //Send mail to Case Manager


        $email_content1 = EmailTemplate::getEmailTemplate("OTHER_DOCUMENT_REQUEST", $lang);
       

        if ($email_content1) {
            $mail_body = str_replace(
                ['%name', '%docname'],
                [ucwords($user['name']),
                $user['document_name']],
                $email_content1->message
            );


            Mail::send('email', ['baseUrl'=>env('REDIRECT_URL',''),'varContent' => $mail_body,
                ],
                function ($message) use ($user, $email_content1) {
                $message->from(config('common.FRONTEND_FROM_EMAIL'),
                    config('common.FRONTEND_FROM_EMAIL_NAME'));
                $message->to($user["email"], $user["name"])->subject($email_content1->subject);
            });


        }
     }

    public function KycApprovedNotification($user) {
        $user = unserialize($user);
        $lang = config('common.EMAIL_LANG');
        //Send mail to Case Manager
        
              if($user['userType']== 2) {
                 $email_content = EmailTemplate::getEmailTemplate("CORPRATE_KYC_APPROVED_NOTIFICATION", $lang);
                 if ($email_content) {
                $mail_body = str_replace(
                    ['%name','%corpname'],
                    [ucwords($user['name']),ucwords($user['corp_name'])],
                    $email_content->message
                );
            }

        }
        if($user['userType']== 1) {
                 $email_content = EmailTemplate::getEmailTemplate("Indiv_KYC_Approval_Notification", $lang);
                 if ($email_content) {
                $mail_body = str_replace(
                    ['%name'],
                    [ucwords($user['name'])],
                    $email_content->message
                );
            }

        }

            Mail::send('email', ['varContent' => $mail_body,
                ],
                function ($message) use ($user, $email_content) {
                $message->from(config('common.FRONTEND_FROM_EMAIL'),
                    config('common.FRONTEND_FROM_EMAIL_NAME'));
                $message->to($user['email'], $user["name"])->subject($email_content->subject);
            });
    }

     public function KycDeclineNotification($user) {
        $user = unserialize($user);
        $lang = config('common.EMAIL_LANG');
        //Send mail to Case Manager
        
    if($user['userType'] == 2) {
        $email_content = EmailTemplate::getEmailTemplate("KORP_KYC_DECLINE_NOTIFICATION
                ", $lang);
        if ($email_content) {
            $mail_body = str_replace(
                ['%name','%corpname'],
                [ucwords($user['name']),ucwords($user['corp_name'])],
                $email_content->message
            );
        }    
    }    
    

    if($user['userType'] == 1) {
    $email_content = EmailTemplate::getEmailTemplate("Individual_KYC_Decline_Notification", $lang);
        if ($email_content) {
            $mail_body = str_replace(
                ['%name'],
                [ucwords($user['name'])],
                $email_content->message
            );
        }    
    }        


            Mail::send('email', ['varContent' => $mail_body,
                ],
                function ($message) use ($user, $email_content) {
               $message->from(config('common.FRONTEND_FROM_EMAIL'),
                    config('common.FRONTEND_FROM_EMAIL_NAME'));
                $message->to($user['email'], $user["name"])->subject($email_content->subject);
            });


        
     }

    public function accountLockUnlock($user) {
        $user = unserialize($user);
        $lang = config('common.EMAIL_LANG');
        $email_content = EmailTemplate::getEmailTemplate("INDI_ACCOUNT_LOCK", $lang);
        $email_content1 = EmailTemplate::getEmailTemplate("INDI_ACCOUNT_LOCK_ADMIN", $lang);
         
        if ($email_content) {
            $mail_body = str_replace(
                ['%name'],
                [ucwords($user['name'])],
                $email_content->message
            );
        }    
        Mail::send('email', ['varContent' => $mail_body,
                ],
                function ($message) use ($user, $email_content) {
                $message->from(config('common.FRONTEND_FROM_EMAIL'),
                    config('common.FRONTEND_FROM_EMAIL_NAME'));
                $message->to($user['email'], $user["name"])->subject($email_content->subject);
            });

          //For Admin sent email
        if ($email_content1) {
            $mail_body = str_replace(
                ['%name'],
                [ucwords($user['name'])],
                $email_content1->message
            );
        }    
        Mail::send('email', ['varContent' => $mail_body,
                ],
                function ($message) use ($user, $email_content1) {
                $message->from(config('common.FRONTEND_FROM_EMAIL'),
                    config('common.FRONTEND_FROM_EMAIL_NAME'));
                $message->to($user['email'], $user["name"])->subject($email_content1->subject);
            });



    }
    

     public function sendTradinguserLoginDetails($user) 
     {
        $user = unserialize($user);
        $lang = config('common.EMAIL_LANG');
        if($user['user_type'] == '1') {
        $email_content = EmailTemplate::getEmailTemplate("Trading_Indiv_user_login_details", $lang);

            if ($email_content) {
            $mail_body = str_replace(
                ['%name','%username','%password'],
                [ucwords($user['name']),$user['username'], $user['password']],
                $email_content->message
            );
            Mail::send('email', ['varContent' => $mail_body,
                ],
                function ($message) use ($user, $email_content) {
                $message->from(config('common.FRONTEND_FROM_EMAIL'),
                    config('common.FRONTEND_FROM_EMAIL_NAME'));
                $message->to($user['email'], $user["name"])->subject($email_content->subject);
            });

        } 
    } 


          if($user['user_type'] == '2'){
                $email_content = EmailTemplate::getEmailTemplate("Trading_Corp_user_login_details", $lang);
           
            if ($email_content) {
                $mail_body = str_replace(
                    ['%name','%username','%password','%corp_name'],
                    [ucwords($user['name']),$user['username'], $user['password'],ucwords($user['corp_name'])],
                    $email_content->message
                );
                Mail::send('email', ['varContent' => $mail_body,
                    ],
                    function ($message) use ($user, $email_content) {
                    $message->from(config('common.FRONTEND_FROM_EMAIL'),
                    config('common.FRONTEND_FROM_EMAIL_NAME'));
                    $message->to($user['email'], $user["name"])->subject($email_content->subject);
                });

            }    
        }

    }     


    public function adminNotificationSent($content) {
       
        $content = unserialize($content);
        if ($content) {
                $mail_body = str_replace(
                    ['%name'],
                    [ucwords($content['name'])],
                    $content['message']
                );
                Mail::send('email', ['varContent' => $mail_body,
                    ],
                    function ($message) use ($content) {
                    $message->from(config('common.FRONTEND_FROM_EMAIL'),
                    config('common.FRONTEND_FROM_EMAIL_NAME'));
                    $message->to(config('common.ADMIN_EMAIL'), $content["name"])->subject($content['subject']);
                });

        } 
    }


    public function adminNotificationSentkycComplete($user) {
       
        $user = unserialize($user);
        $lang = config('common.EMAIL_LANG');
        
        if($user['user_type']== '2') {
         $email_content = EmailTemplate::getEmailTemplate("corp_kyc_comple_noti_sent_admin", $lang);
        }
        if($user['user_type']== '1') {
         $email_content = EmailTemplate::getEmailTemplate("Indi_kyc_comple_noti_sent_admin", $lang);
        }

        if ($email_content) {
            $mail_body = str_replace(
                ['%name'],
                [ucwords($user['name'])],
                $email_content->message
            );
            Mail::send('email', ['varContent' => $mail_body,
                ],
                function ($message) use ($user, $email_content) {
               $message->from(config('common.FRONTEND_FROM_EMAIL'),
                    config('common.FRONTEND_FROM_EMAIL_NAME'));
                $message->to($user['email'], $user["name"])->subject($email_content->subject);
            });

        } 
    }
    /**
     * Event subscribers
     *
     * @param mixed $events
     */
    public function subscribe($events)
    {

        $events->listen(
            'user.login.success',
            'App\Inv\Repositories\Events\UserEventsListener@onLoginSuccess'
        );

        $events->listen(
            'user.logout.success',
            'App\Inv\Repositories\Events\UserEventsListener@onLogoutSuccess'
        );

        $events->listen(
            'user.login.failed',
            'App\Inv\Repositories\Events\UserEventsListener@onFailedLogin'
        );
        $events->listen(
            'user.email.verify',
            'App\Inv\Repositories\Events\UserEventsListener@onVerifyUser'
        );

        $events->listen(
            'user.email.verifycorp',
            'App\Inv\Repositories\Events\UserEventsListener@onVerifyUsercorp'
        );

        $events->listen(
            'user.registered',
            'App\Inv\Repositories\Events\UserEventsListener@onUserRegistration'
        );

       $events->listen(
            'user.sendotp',
            'App\Inv\Repositories\Events\UserEventsListener@onSendOtp'
        );

        $events->listen(
            'admin.disapproved',
            'App\Inv\Repositories\Events\UserEventsListener@onDisApprovedAdmin'
        );


        $events->listen(
            'forgot_password',
            'App\Inv\Repositories\Events\UserEventsListener@onForgotPassword'
        );
        $events->listen(
            'RESET_PASSWORD_SUCCESSS',
            'App\Inv\Repositories\Events\UserEventsListener@onResetPasswordSuccess'
        );

         $events->listen(
            'user.email.confirm',
            'App\Inv\Repositories\Events\UserEventsListener@verificationConfirmation'
        );
        $events->listen(
            'user.email.otpconfirmation',
            'App\Inv\Repositories\Events\UserEventsListener@otpVerificationConfirmationMail'
        );

        $events->listen(
            'user.email.otpconfirmationfchangepass',
            'App\Inv\Repositories\Events\UserEventsListener@firstChangePasswordConfirmationMail'
        );
         $events->listen(
            'user.email.firstchangepassword',
            'App\Inv\Repositories\Events\UserEventsListener@firstChangePasswordConfirmationMail'
        );
        $events->listen(
            'user.email.kycCompletedMail',
            'App\Inv\Repositories\Events\UserEventsListener@kycCompletedMail'
        );
        $events->listen(
            'user.email.corporatekycCompletedMail',
            'App\Inv\Repositories\Events\UserEventsListener@corporatekycCompletedSentMail'
        );
        $events->listen(
            'user.email.indi_corp_unlock_account',
            'App\Inv\Repositories\Events\UserEventsListener@indivisualCorpUnlockAccount'
        );
        $events->listen(
            'user.testmsg',
            'App\Inv\Repositories\Events\UserEventsListener@demoemail'
        );


        $events->listen(
            'admin.finalapproved',
            'App\Inv\Repositories\Events\UserEventsListener@onFinalApprove'
        );

        $events->listen(
            'admin.finaldisapproved',
            'App\Inv\Repositories\Events\UserEventsListener@onFinalDecline'
        );
        
        //
        
        $events->listen(
            'user.email.documentExpireReminder',
            'App\Inv\Repositories\Events\UserEventsListener@onDocumentExpireReminder'
        );
        
        $events->listen(
            'user.email.documentUpdateNotification',
            'App\Inv\Repositories\Events\UserEventsListener@onDocumentUpdateNotification'
        );
        
        $events->listen(
            'user.email.documentUpdateNotificationAdmin',
            'App\Inv\Repositories\Events\UserEventsListener@onDocumentUpdateNotificationAmin'
        );
        

        //
        $events->listen(
            'admin.profiledocapproval',
            'App\Inv\Repositories\Events\UserEventsListener@onMonitoringDocApproval'
            );

        $events->listen(
            'admin.roleuser.email.verify',
            'App\Inv\Repositories\Events\UserEventsListener@adminVerifyUserRole'
        );
        $events->listen(

            'ADMIN.CREATE_BACKEND_USER_MAIL',
            'App\Inv\Repositories\Events\UserEventsListener@sendUserCredential'
        );
         $events->listen(

            'ADMIN.EMAIL.OTHERDOCUMENT',
            'App\Inv\Repositories\Events\UserEventsListener@UserOtherDocumentRequest'
        );

        $events->listen(

            'user.otp_password_reset',
            'App\Inv\Repositories\Events\UserEventsListener@otpPasswordReset'
        );
        $events->listen(

            'Admin.Kyc.Approved.Notification',
            'App\Inv\Repositories\Events\UserEventsListener@KycApprovedNotification'
        );
        $events->listen(

            'Admin.Kyc.Decline.Notification',
            'App\Inv\Repositories\Events\UserEventsListener@KycDeclineNotification'
        );
        $events->listen(
            'user.email.account.lockUnlock',
            'App\Inv\Repositories\Events\UserEventsListener@accountLockUnlock'
        );
        $events->listen(
            'Trading.user.registered',
            'App\Inv\Repositories\Events\UserEventsListener@sendTradinguserLoginDetails'
        );
        $events->listen(
            'sent.admin.signup.notification',
            'App\Inv\Repositories\Events\UserEventsListener@adminNotificationSent'
        );
        $events->listen(
            'sent.admin.kyc.complete.notification',
            'App\Inv\Repositories\Events\UserEventsListener@adminNotificationSentkycComplete'
        );

        
    }
}
