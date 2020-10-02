
<?php

/**
 * FrontEnd routes
 * 
 * @since 1.0
 *
 * @author Amit
 */
Route::domain(config('proin.frontend_uri'))->group(function () {

    Route::get('/', 'Auth\LoginController@welcomePage');
    Route::get('select/{token?}','Auth\LoginController@welcomePage');
    Route::get('/amit', function() {
        
    });
    
    Route::get('send-sms', [
        'as' => 'send_sms',
        'uses' => 'Auth\RegisterController@sendMessage'
    ]);
    //Registration step 1
    Route::get('sign-up', [
        'as' => 'user_register_open',
        'uses' => 'Auth\RegisterController@showRegistrationForm'
    ]);

    Route::get('company-sign-up', [
        'as' => 'company_register_open',
        'uses' => 'Auth\RegisterController@showCompRegistrationForm'
    ]);

    Route::post('checkuseremail', [
        'as' => 'checkuseremail',
        'uses' => 'Auth\RegisterController@userEmailCheck'
    ]);

    Route::post('register', [
        'as' => 'user_register_open',
        'uses' => 'Auth\RegisterController@register'
    ]);

    

    

    Route::get('thanks', [
        'as' => 'thanks',
        'uses' => 'Auth\RegisterController@showThanksForm'
    ]);

    Route::get('otp/{token}', [
        'as' => 'otp',
        'uses' => 'Auth\RegisterController@otpForm'
    ]);
   


    Route::get('verify-email/{token}', [
        'as' => 'verify_email',
        'uses' => 'Auth\RegisterController@verifyUser'
    ]);


    $this->post('change', [
        'as' => 'changepassword',
        'uses' => 'Auth\RegisterController@changePassword'
            ]
    );

    Route::get('login', [
        'as' => 'login_open',
        'uses' => 'Auth\LoginController@showLoginForm'
    ]);

    Route::post('login', [
        'as' => 'login_open',
        'uses' => 'Auth\LoginController@login'
    ]);


    Route::get('verify_email', [
        'as' => 'verify_email_expire',
        'uses' => 'Auth\LoginController@verifyEmailExpire'
    ]);

    //Logout
    Route::post('logout', [
        'as' => 'frontend_logout',
        'uses' => 'Auth\LoginController@logout'
    ]);

    Route::get('logout', [
        'as' => 'frontend_logout',
        'uses' => 'Auth\LoginController@logoutchangepassword'
    ]);

    //=====For Change System Generated password

    Route::get('change-autogenerate-password', [
        'as' => 'change_autogenerate_password',
        'uses' => 'Application\HomeController@resetAccountPasswordForm'
            ]
    );
    Route::post('change-autogenerate-password', [
        'as' => 'change_autogenerate_password',
        'uses' => 'Application\HomeController@resetAccountPassword'
            ]
    );
    
    
    
    Route::get('changepass-otp', [
        'as' => 'changepass_otp',
        'uses' => 'Application\HomeController@changepassOtpForm'
    ]);
 
    
    Route::post('changepass-otp', [
        'as' => 'changepass_otp',
        'uses' => 'Application\HomeController@verifyChangePassOtp'
    ]);
    

    // END Change System Generated password
   
    
    
    // for password
    Route::group(['prefix' => 'password'], function () {

        $this->get('email', [
            'as' => 'password.email',
            'uses' => 'Auth\ForgotPasswordController@showResetLinkEmail'
                ]
        );
        $this->post('email', [
            'as' => 'password.email',
            'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail'
                ]
        );
        $this->get('reset', [
            'as' => 'password.reset',
            'uses' => 'Auth\ResetPasswordController@showResetForm'
                ]
        );
        $this->post('reset', [
            'as' => 'password.reset',
            'uses' => 'Auth\ResetPasswordController@reset'
                ]
        );
        $this->get('change', [
            'as' => 'changepassword',
            'uses' => 'Auth\ChangePasswordController@showChangePasswordForm'
                ]
        );
        $this->post('change', [
            'as' => 'changepassword',
            'uses' => 'Auth\ChangePasswordController@changePassword'
                ]
        );
    });

    Route::get('reg-outside-profile', [
        'as' => 'view_outside_register_profile',
        'uses' => 'Auth\RegisterController@accessStorageImages'
            ]
    );



   
});
