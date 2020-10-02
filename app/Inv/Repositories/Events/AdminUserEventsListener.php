<?php

namespace App\Inv\Repositories\Events;

use Mail;
use Illuminate\Queue\SerializesModels;
use App\Inv\Repositories\Factory\Events\BaseEvent;
use App\Inv\Repositories\Models\Master\EmailTemplate;

class AdminUserEventsListener extends BaseEvent
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
    
     /*
    public function onVerifyUser($user)
    {
        $user = unserialize($user);
        $lang = config('common.EMAIL_LANG');
        //Send mail to Case Manager


        $email_content1 = EmailTemplate::getEmailTemplate("ADMIN_VERIFY_USEREMAIL", $lang);
        echo "<pre>";
        echo "post";
        print_r($email_content1->subject);die;


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

    }  */



 


   
    /**
     * Event subscribers
     *
     * @param mixed $events
     */
    public function subscribe($events)
    {

        $events->listen(
            'user.login.success',
            'App\Inv\Repositories\Events\AdminUserEventsListener@onLoginSuccess'
        );

        $events->listen(
            'user.logout.success',
            'App\Inv\Repositories\Events\AdminUserEventsListener@onLogoutSuccess'
        );

        $events->listen(
            'user.login.failed',
            'App\Inv\Repositories\Events\AdminUserEventsListener@onFailedLogin'
        );
        //from email verify
        /*$events->listen(
            'admin.roleuser.email.verify',
            'App\Inv\Repositories\Events\AdminUserEventsListener@onVerifyUser'
        );*/

    }



}
