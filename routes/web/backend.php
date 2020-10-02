<?php

/**
 * This route have all backend guest and auth routes
 *
 * @since 1.0
 *
 * @author Prolitus Dev Team
 */
Route::domain(config('proin.backend_uri'))->group(function () {

    Route::get('/', [
        'as' => 'backend_login_open',
        'uses' => 'Backend\Auth\LoginController@showLoginForm'
    ]);
    Route::post(
            '/', [
        'as' => 'backend_login_open',
        'uses' => 'Backend\Auth\LoginController@login'
            ]
    );
    Route::post(
            'logout', [
        'as' => 'backend_logout',
        'uses' => 'Backend\Auth\LoginController@logout'
            ]
    );
      Route::get(
            'changepassword', [
        'as' => 'changepassword_backend_login_open',
        'uses' => 'Backend\Auth\LoginController@adminlogoutchangepassword'
            ]
    );

    Route::get('adchange-autogenerate-password', [
        'as' => 'adchange_autogenerate_password',
        'uses' => 'Backend\Auth\LoginController@resetAccountPasswordForm'
            ]
    );
    Route::post('adchange-autogenerate-password', [
        'as' => 'adchange_autogenerate_password',
        'uses' => 'Backend\Auth\LoginController@resetAccountPassword'
            ]
    );



     Route::get('adchangepass-otp', [
        'as' => 'adchangepass_otp',
        'uses' => 'Backend\Auth\LoginController@changepassOtpForm'
    ]);


    Route::post('adchangepass-otp', [
        'as' => 'adchangepass_otp',
        'uses' => 'Backend\Auth\LoginController@verifyChangePassOtp'
    ]);

    Route::get('adotp/{token}', [
        'as' => 'adotp',
        'uses' => 'Backend\Auth\LoginController@otpForm'
    ]);


    Route::post('adverify-otp', [
        'as' => 'adverify_otp',
        'uses' => 'Backend\Auth\LoginController@verifyotpUser'
    ]);
    Route::get('resend-otp', [
        'as' => 'resend_otp',
        'uses' => 'Backend\Auth\LoginController@resendotpUser'
    ]);


    Route::get('adotp-thanks', [
        'as' => 'adotp_thanks',
        'uses' => 'Backend\Auth\LoginController@verifiedotpUser'
    ]);

   Route::get('admin-verify-email', [
        'as' => 'admin_verify_email',
        'uses' => 'Backend\Auth\LoginController@adminVerifyUser'
    ]);


    Route::group(['prefix' => 'password'], function () {
        // Reset request email
        $this->get('reset-password', [
            'as' => 'password.do.reset',
            'uses' => 'Backend\Auth\ForgotPasswordController@showLinkRequestForm'
                ]
        );

        $this->get('email', [
            'as' => 'password.email',
            'uses' => 'Backend\Auth\ForgotPasswordController@showResetLinkEmail'
                ]
        );



        $this->post('email', [
            'as' => 'password.email',
            'uses' => 'Backend\Auth\ForgotPasswordController@sendResetLinkEmail'
                ]
        );
        $this->get('reset', [
            'as' => 'admin.password.reset',
            'uses' => 'Backend\Auth\ResetPasswordController@adminshowResetForm'
                ]
        );
        $this->post('reset', [
            'as' => 'password.reset',
            'uses' => 'Backend\Auth\ResetPasswordController@reset'
                ]
        );
    });

    Route::group(
        ['prefix' => 'admin'], function() {
        Route::group(
                ['middleware' => 'auth'], function () {
            
            Route::get(
                'individual-user', [
                'as' => 'individual_user',
                'uses' => 'Backend\UserController@individualUsersList'
                    ]
            );

            Route::get(
                    'corporate-user', [
                'as' => 'corporate_user',
                'uses' => 'Backend\UserController@corporateUsersList'
                    ]
            );

            Route::get(
                    'user-detail', [
                'as' => 'user_detail',
                'uses' => 'Backend\UserController@viewUserDetail'
                    ]
            );



            Route::get(
                'individual-trading-user', [
                'as' => 'individual_trading_user',
                'uses' => 'Backend\UserController@individualTradingUsersList'
                    ]
            );

            Route::get(
                    'corporate-trading-user', [
                'as' => 'corporate_trading_user',
                'uses' => 'Backend\UserController@corporateTradingUsersList'
                    ]
            );

           
            
            //


            Route::get(
                    'user-detail-similar', [
                'as' => 'user_detail_similar',
                'uses' => 'Backend\UserController@viewUserDetailSimilar'
                    ]
            );


            Route::get(
                    'corp-detail-similar', [
                'as' => 'corp_detail_similar',
                'uses' => 'Backend\UserController@viewCorpDetailSimilar'
                    ]
            );


            
            Route::get(
                    'corp-user-detail', [
                'as' => 'corp_user_detail',
                'uses' => 'Backend\UserController@viewCorporateUserDetail'
                    ]
            );
            
            Route::get(
                    'individual-Kyc', [
                'as' => 'individual_Kyc',
                'uses' => 'Backend\UserKycController@individualKycList'
                    ]
            );
            
            Route::get(
                    'beneficiary-Kyc', [
                'as' => 'beneficiary_Kyc',
                'uses' => 'Backend\UserKycController@beneficiaryKycList'
                    ]
            );
            
            Route::get(
                    'corporate-Kyc', [
                'as' => 'corporate_Kyc',
                'uses' => 'Backend\UserKycController@corporateKycList'
                    ]
            );
            Route::get('similar-download',['as'=>'similar_download','uses'=>'Backend\UserController@similardownloadPDF']);
            Route::get('report-download',['as'=>'report_download','uses'=>'Backend\UserController@downloadPDF']);
            Route::post('report-corpcomplete-download',['as'=>'report_corpcomplete_download','uses'=>'Backend\UserController@downloadcorpcompletePDF']);
            Route::post('report-indicomplete-download',['as'=>'report_indicomplete_download','uses'=>'Backend\UserController@downloadIndicompletePDF']);
            
            Route::get('historical-pdf-download',['as'=>'historical_pdf_download','uses'=>'Backend\UserController@downloadhistoricalPDF']);
            Route::get('download-document',['as'=>'download_document','uses'=>'Backend\UserController@docDownload']);
            
            Route::post(
                'change-status', [
                'as' => 'change_status',
                'uses' => 'Backend\UserController@updateUserStatus'
                ]
            );
            
            Route::post(
                'save-assesment-rank', [
                'as' => 'save_assesment_rank',
                'uses' => 'Backend\UserController@storeUserRiskAssessmentRank'
                ]
            );

           

            Route::post(
                'save-final-report', [
                'as' => 'save_final_report',
                'uses' => 'Backend\UserController@storefinalReport'
                ]
            );


            Route::get(
                    'individual-api-popup', [
                'as' => 'individual_api_popup',
                'uses' => 'Backend\UserController@individualApi'
                    ]
            );


            Route::get(
                    'corp-api-popup', [
                'as' => 'corp_api_popup',
                'uses' => 'Backend\UserController@corpApi'
                    ]
            );

            Route::post('edit',
                [
                'as' => 'personal_wcapi_call',
                'uses' => 'Backend\UserController@callindividualApi'
            ]);
            
            Route::get(
                    'individual-api-approve', [
                'as' => 'individual_api_approve',
                'uses' => 'Backend\UserController@individualApiApprove'
                    ]
            );
             Route::post(
                    'individual-api-approve', [
                'as' => 'individual_api_approve',
                'uses' => 'Backend\UserController@individualApiAction'
                    ]
            );

            Route::get(
                    'individual_api_disapprove', [
                'as' => 'individual_api_disapprove',
                'uses' => 'Backend\UserController@individualApiDisApprove'
                    ]
            );
             Route::post(
                    'individual_api_disapprove', [
                'as' => 'individual_api_disapprove',
                'uses' => 'Backend\UserController@individualApiActionD'
                    ]
            );

            Route::get(
                    'individual-final-approve', [
                'as' => 'individual_final_approve',
                'uses' => 'Backend\UserController@individualFinalApprove'
                    ]
            );
             Route::post(
                    'individual-final-approve', [
                'as' => 'individual_final_approve',
                'uses' => 'Backend\UserController@individualFinalAction'
                    ]
            );

            Route::get(
                    'individual_final_disapprove', [
                'as' => 'individual_final_disapprove',
                'uses' => 'Backend\UserController@individualFinalDisApprove'
                    ]
            );
            
            
             Route::post(
                    'individual_final_disapprove', [
                'as' => 'individual_final_disapprove',
                'uses' => 'Backend\UserController@individualFinalActionD'
                    ]
            );


             Route::get(
                    'individual_final_nomatch', [
                'as' => 'individual_final_nomatch',
                'uses' => 'Backend\UserController@individualFinalNomatch'
                    ]
            );


             Route::post(
                    'individual_final_nomatch', [
                'as' => 'individual_final_nomatch',
                'uses' => 'Backend\UserController@individualFinalActionNomatch'
                    ]
            );

            Route::get(
                    'individual_api_resolve', [
                'as' => 'individual_api_resolve',
                'uses' => 'Backend\UserController@individualApiresolve'
                    ]
            );
             Route::post(
                    'individual_api_resolve', [
                    'as' => 'individual_api_resolve',
                    'uses' => 'Backend\UserController@individualApiresolve'
                    ]
            );

              Route::post(
                    'individual-api-resolve-all', [
                    'as' => 'individual_api_resolve_all',
                    'uses' => 'Backend\UserController@individualApiresolveAll'
                    ]
            );


            Route::get(
                'other-document', [
                'as' => 'other_document',
                'uses' => 'Backend\UserController@getAllOtherDocsPaginate'
                ]
            ); 
            
            Route::get(
                'add-other-document', [
                'as' => 'add_other_document',
                'uses' => 'Backend\UserController@addOtherDocumentForm'
                ]
            ); 
            
            Route::get(
                'edit-other-document', [
                'as' => 'edit_other_document',
                'uses' => 'Backend\UserController@editOtherDocumentForm'
                ]
            );
            
            
            Route::post(
                'save-other-document', [
                'as' => 'save_other_document',
                'uses' => 'Backend\UserController@saveOrEditDocument'
                ]
            );

            
            Route::get(
                'active_inactive_other_document',[
                'as'=>'active_inactive_other_document',
                'uses' => 'Backend\UserController@activeInactiveOtherDocument'
                ]
            );
            
            Route::post(
                'send-other-docreq', [
                'as' => 'send_other_docreq',
                'uses' => 'Backend\UserController@sendIndvOtherDocReq'
                ]
            );
            
            Route::post(
                'send-corp-other-docreq', [
                'as' => 'send_corp_other_docreq',
                'uses' => 'Backend\UserController@sendCorpOtherDocReq'
                ]
            );
            
            //
            Route::get(
                'profile-monitoring', [
                'as' => 'profile_monitoring',
                'uses' => 'Backend\UserController@profileMonitoringList'
                ]
            );

            Route::get(
                'document-detail', [
                'as' => 'document_detail',
                'uses' => 'Backend\UserController@viewDocumentDetail'
                    ]
            );
            
            //updateDocStatus
            
            Route::post(
                'update-docsuatus', [
                'as' => 'update_docsuatus',
                'uses' => 'Backend\UserController@updateDocStatus'
                    ]
            );


           

            Route::any(
                    'sendtotrading-user', [
                'as' => 'sendtotrading_user',
                'uses' => 'Api\UserController@sendtotradingUser'
                    ]
            );




            Route::Get(
                'lock-unlock', [
                    'as' => 'lock_unlock',
                    'uses' => 'Backend\UserController@lockUnlock'
                ]
            );



            Route::get(
                'individual-status', [
                    'as' => 'individual_status',
                    'uses' => 'Backend\UserController@updateUserStatusform'
                ]
            );

            Route::post(
                'individual-status', [
                    'as' => 'individual_status',
                    'uses' => 'Backend\UserController@updateUserStatus'
                ]
            );

             Route::get(
                'individual-searchcase', [
                    'as' => 'individual_searchcase',
                    'uses' => 'Backend\UserController@individualSearchCases'
                ]
            );

            Route::post(
                'individual-searchcase', [
                    'as' => 'individual_searchcase',
                    'uses' => 'Backend\UserController@individualSearchCases'
                ]
            );

            Route::get(
                'individual_generate_report', [
                'as' => 'individual_generate_report',
                'uses' => 'Backend\UserController@individualGenerateReport'
                 ]
            );



        });
    });

    Route::group(
            ['prefix' => 'dashboard'], function () {
        Route::group(
                ['middleware' => 'auth'], function () {
            Route::get(
                    '/', [
                'as' => 'backend_dashboard',
                'uses' => 'Backend\DashboardController@index'
                    ]
            );
            Route::get(
                    'viewnewsletter', [
                'as' => 'viewnewsletter',
                'uses' => 'Backend\UserController@newsLetterDetail'
                    ]
            );
            Route::get(
                    'manage-users', [
                'as' => 'manage_users',
                'uses' => 'Backend\UserController@viewUserList'
                    ]
            );
            Route::get(
                    'user-detail', [
                'as' => 'user_detail',
                'uses' => 'Backend\UserController@viewUserDetail'
                    ]
            );

            Route::get(
                    'get-user-detail', [
                'as' => 'get_user_detail',
                'uses' => 'Backend\UserController@detail'
                    ]
            );


            Route::get(
                    'edit-user', [
                'as' => 'edit_backend_user',
                'uses' => 'Backend\UserController@editUser'
                    ]
            );

            Route::get(
                    'view-user', [
                'as' => 'view_user_detail',
                'uses' => 'Backend\UserController@viewUserDetail'
                    ]
            );


            Route::post(
                    'delete-user', [
                'as' => 'delete_users',
                'uses' => 'Backend\UserController@deleteUser'
                    ]
            );
            Route::post(
                    'save-user', [
                'as' => 'save_backend_user',
                'uses' => 'Backend\UserController@saveUser'
                    ]
            );

            Route::get(
                    'scout', [
                'as' => 'show_scout',
                'uses' => 'Backend\UserController@viewAllScout'
                    ]
            );
            Route::get(
                    'user', [
                'as' => 'show_user',
                'uses' => 'Backend\UserController@viewAllUser'
                    ]
            );


            Route::get(
                    'user_paginate', [
                'as' => 'user_paginate',
                'uses' => 'Backend\UserController@viewUserAjaxPaginate'
                    ]
            );
            Route::post(
                    'user-detail', [
                'as' => 'admin_approved',
                'uses' => 'Backend\UserController@updateUserDetail'
                    ]
            );
            

            
        });
    });

    Route::group(
            ['prefix' => 'account'], function () {
        Route::group(
                ['middleware' => 'auth'], function () {
            Route::get(
                    'view-profile', [
                'as' => 'view_profile',
                'uses' => 'Backend\UserController@viewProfile'
                    ]
            );

            Route::get(
                    'update-profile', [
                'as' => 'update_profile',
                'uses' => 'Backend\UserController@updateProfile'
                    ]
            );

            Route::post(
                    'update-profile', [
                'as' => 'update_profile',
                'uses' => 'Backend\UserController@updateUserProfile'
                    ]
            );

            Route::post(
                    'upload-image', [
                'as' => 'upload_image',
                'uses' => 'Backend\UserController@ajaxImageUpload'
                    ]
            );


            Route::get(
                    'change-password', [
                'as' => 'change_password',
                'uses' => 'Backend\UserController@changePassword'
                    ]
            );

            Route::post(
                    'change-password', [
                'as' => 'change_password',
                'uses' => 'Backend\UserController@updateChangePassword'
                    ]
            );
        });
    });
});
