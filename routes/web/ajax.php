<?php
/**
 * All Ajax routes
 */
Route::group(
    ['prefix' => 'ajax'],
    function () {
    Route::group(
        ['middleware' => 'checkAjax'],
        function () {
        Route::group(
            ['middleware' => 'auth'],
            function () {

           Route::post(
                'get-users-wci',
                [
                'as' => 'get_users_wci',
                'uses' => 'AjaxController@getUsersListAPI'
                ]
            ); 

            Route::post(
                'get-users-wci-dummy',
                [
                'as' => 'get_users_wci_dummy',
                'uses' => 'AjaxController@getUsersListAPIDummy'
                 // 'uses' => 'AjaxController@getUsersListAPI11'
                ]
            );


            Route::post(
                'get-users-wci-list',
                [
                'as' => 'get_users_wci_list',
                'uses' => 'AjaxController@getsimilarUsersList'
                 ]
            );

            Route::post(
                'get-groupID',
                [
                'as' => 'get_groupID',
                'uses' => 'AjaxController@getgroupID'
                ]
            );


             Route::post(
                'get-resolution-toolkit',
                [
                'as' => 'get_resolution_toolkit',
                'uses' => 'AjaxController@getResolutionToolkit'
                 ]
            );
             
             Route::post(
                'get-toolkit-value',
                [
                'as' => 'get_toolkit_value',
                'uses' => 'AjaxController@getResolutionToolkitvar'
                 ]
            );

            Route::post(
                'get-users-resolved',
                [
                'as' => 'get_users_resolved',
                'uses' => 'AjaxController@getuserResolved'
                ]
            );
            Route::post(
                'get-users-resolved-all',
                [
                'as' => 'get_users_resolved_all',
                'uses' => 'AjaxController@getuserResolvedAll'
                ]
            );

            Route::post(
                'get-individual-wci-single',
                [
                'as' => 'get_individual_wci_single',
                'uses' => 'AjaxController@getUsersDetailfromApi'
                ]
            );
           
             Route::post(
                'get-users-wci-single',
                [
                'as' => 'get_users_wci_single',
                'uses' => 'AjaxController@getUsersDetailAPIDummy'
                ]
            );

             Route::post(
                'get-users-wci-corp',
                [
                'as' => 'get_users_wci_corp',
                'uses' => 'AjaxController@getUsersListAPICorp'
                ]
            );

             Route::post(
                'get-users-wci-corp-single',
                [
                'as' => 'get_users_wci_corp_single',
                'uses' => 'AjaxController@getCorpDetailAPIDummy'
                ]
            );

           
           //
            Route::post(
                'shareholder-save-ajax',
                [
                'as' => 'shareholder_save_ajax',
                'uses' => 'AjaxController@saveShareholder'
                ]
            );

            Route::post(
                'update-kyc-Approve',
                [
                'as' => 'update_kyc_Approve',
                'uses' => 'AjaxController@updatekycApprove'
                ]
            );


            Route::post(
                'get-country-code',
                [
                'as' => 'get_country_code',
                'uses' => 'AjaxController@countrycode'
                 // 'uses' => 'AjaxController@getUsersListAPI11'
                ]
            );


            Route::post(
                'update-passport',
                [
                'as' => 'update_passport',
                'uses' => 'AjaxController@updatePassport'
                
                ]
            );
            Route::post(
                'update-utilitybill',
                [
                'as' => 'update_utilitybill',
                'uses' => 'AjaxController@updateUtilityBill'
                
                ]
            );


            Route::post(
                'complete-pdf-report',
                [
                'as' => 'complete_pdf_report',
                'uses' => 'AjaxController@completePdfReport'
                ]
            );
            
            Route::get(
                'send-otp',
                [
                'as' => 'send_otp',
                'uses' => 'AjaxController@sendOtp'
                
                ]
            );
           
            Route::get(
                'resend-otp',
                [
                'as' => 'resend_otps',
                'uses' => 'AjaxController@resendotpDocUser'
                
                ]
            );
        });
    });
    Route::post(
                'without-login-right-ajax',
                [
                'as' => 'withought_login_right_ajax',
                'uses' => 'AjaxController@fetchRights'
                ]
            );
    Route::post(
                'without-login-global-search',
                [
                'as' => 'without_login_global_search',
                'uses' => 'AjaxController@globalSearch'
                ]
            );

     Route::get(
                'modal-ajax',
                [
                'as' => 'modal_ajax_load',
                'uses' => 'AjaxController@modalLoad'
                ]
            );
     Route::get(
                'user_first_time_popup',
                [
                'as' => 'modal_ajax_load_update',
                'uses' => 'AjaxController@userPopUpUpdate'
                ]
            );


     Route::post(
        'get-role-list',
        [
        'as' => 'get_role_list',
        'uses' => 'AjaxController@getRoleLists'
        ]
    );

    Route::post(
        'get_user_role',
        [
        'as' => 'get_user_role_list',
        'uses' => 'AjaxController@getUserRoleLists'
        ]
    );
    Route::post(
        'checkemail',
        [
        'as' => 'checkemail',
        'uses' => 'AjaxController@adminUserEmailCheck'
        ]
    );
     Route::post(
        'username',
        [
        'as' => 'username',
        'uses' => 'AjaxController@adminUsernameCheck'
        ]
    );

     Route::post(
        'userrole',
        [
        'as' => 'userrole',
        'uses' => 'AjaxController@adminUserRoleCheck'
        ]
    );
});



