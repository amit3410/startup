<?php
/**
 * This route have user dashboard and all application routes
 *
 * @since 1.0
 *
 * @author Prolitus Dev Team
 */
Route::domain(config('proin.frontend_uri'))->middleware('web')->group(function (){
    Route::group(
        ['prefix' => 'dashboard'],
        function () {
        Route::group(
            ['middleware' => 'auth'],
            function () {
            Route::get(
                '/',
                [
                'as' => 'front_dashboard',
                'uses' => 'Application\DashboardController@index'
                ]
            );

            
        });
    });
    
    Route::group(
        ['middleware' => 'auth'],
        function () {
        
        Route::get(
            'download-zip-file',
            [
            'as' => 'download_zip_file',
            'uses' => 'Application\RightController@downloadZipFile'
            ]
        );        
        Route::get(
            'download-research-file/{user_id}/{file_id}',
            [
            'as' => 'download_research_file',
            'uses' => 'Application\AccountController@getAttachmentResearch'
            ]
        );
        
        Route::get(
            'download-zip-file-latest',
            [
            'as' => 'download_zip_file_latest',
            'uses' => 'Application\RightController@downloadZipFileLatest'
            ]
        );
        
    });

    Route::group(['prefix' => 'profile'],
        function () {
        Route::group(['middleware' => 'auth'],
            function () {

            Route::get('/',
                [
                'as' => 'profile',
                'uses' => 'Application\AccountController@index'
            ]);
           /* 
            Route::get('edit',
                [
                'as' => 'edit_profile',
                'uses' => 'Application\AccountController@editPersonalProfile'
            ]);*/
            Route::get('thanks',
                [
                'as' => 'thanks_page',
                'uses' => 'Application\AccountController@thanksPage'
            ]);
            
            Route::post('edit',
                [
                'as' => 'update_personal_profile',
                'uses' => 'Application\AccountController@savePersonalProfile'
            ]);
            
            Route::get('family-information',
                [
                'as' => 'family_information',
                'uses' => 'Application\AccountController@editFamilyInformation'
            ]);
            
            Route::post('family-information',
                [
                'as' => 'family_information',
                'uses' => 'Application\AccountController@saveFamilyInformation'
            ]);
            
            Route::get('residential-information',
                [
                'as' => 'residential_information',
                'uses' => 'Application\AccountController@editResidentialInformation'
            ]);
            
            Route::post('residential-information',
                [
                'as' => 'residential_information',
                'uses' => 'Application\AccountController@saveResidentialInformation'
            ]);
            
            Route::get('professional-information',
                [
                    
                'as' => 'professional_information',
                'uses' => 'Application\AccountController@editProfessionalInformation'
            ]);
            
            Route::post('professional-information',
                [
                'as' => 'professional_information',
                'uses' => 'Application\AccountController@saveProfessionalInformation'
            ]);
            
            
            
            Route::get('commercial-information',
                [
                'as' => 'commercial_information',
                'uses' => 'Application\AccountController@editCommercialInformation'
            ]);
            
            Route::post('commercial-information',
                [
                'as' => 'commercial_information',
                'uses' => 'Application\AccountController@saveCommercialInformation'
            ]); 
            
            Route::get('financial-information',
                [
                'as' => 'financial_information',
                'uses' => 'Application\AccountController@editFinancialInformation'
            ]);
            
            Route::post('financial-information',
                [
                'as' => 'financial_information',
                'uses' => 'Application\AccountController@saveFinancialInformation'
            ]);
            
            Route::get('upload-document',
                [
                'as' => 'upload_document',
                'uses' => 'Application\AccountController@editDocuments'
            ]);
            
            Route::post('upload-document',
                [
                'as' => 'upload_document',
                'uses' => 'Application\AccountController@saveDocuments'
            ]);
            
            Route::get('upload-other-document',
                [
                'as' => 'upload_other_document',
                'uses' => 'Application\AccountController@editOtherDocuments'
            ]);
            
            Route::post('upload-other-document',
                [
                'as' => 'upload_other_document',
                'uses' => 'Application\AccountController@saveOtherDocuments'
            ]);
            

            Route::get('import_document',
                
                ['as'=>'import_document',
                'uses'=>'Application\AccountController@IndivisualDocDownload'
            ]);
            
            Route::get('my-account',
                [
                'as' => 'my_account',
                'uses' => 'Application\AccountController@myAccoutPopup'
            ]);

            Route::get('public-profile',
                [
                'as' => 'public_profile',
                'uses' => 'Application\AccountController@publicProfile'
            ]);
            
            Route::get('update-notification',
            [
            'as' => 'update_notification',
            'uses' => 'Application\AccountController@updateNotifications'
            ]);

             Route::get('company-profile',
                [
                'as' => 'company_profile-show',
                'uses' => 'Application\CompanyController@index'
            ]);
            
            Route::post('company-profile',
                [
                'as' => 'company_profile',
                'uses' => 'Application\CompanyController@companyDetailsForm'
            ]);
            Route::get('company-address',
                [
                'as' => 'company-address-show',
                'uses' => 'Application\CompanyController@companyAddress'
            ]);
            Route::post('company-address',
                [
                'as' => 'company-address',
                'uses' => 'Application\CompanyController@companyAddressForm'
            ]);

            //shareholding-structure-show

            Route::get('shareholding-structure',
                [
                'as' => 'shareholding_structure',
                'uses' => 'Application\CompanyController@shareholdingStructure'
            ]);

            Route::post('shareholding-structure',
                [
                'as' => 'shareholding_structure',
                'uses' => 'Application\CompanyController@shareHoldingStructureForm'
            ]);
            
            Route::get('financial',
                [
                'as' => 'financial-show',
                'uses' => 'Application\CompanyController@financialInfo'
            ]);
             Route::post('financial',
                [
                'as' => 'financial',
                'uses' => 'Application\CompanyController@financialInfoForm'
            ]);

            Route::get('documents',
                [
                'as' => 'documents-show',
                'uses' => 'Application\CompanyController@documentDeclaration'
            ]);
            Route::post('documents',
                [
                'as' => 'documents',
                'uses' => 'Application\CompanyController@documentDeclarationForm'
            ]);
            
            Route::get('upload-corp-other-document',
                [
                'as' => 'upload_corp_other_document',
                'uses' => 'Application\CompanyController@editOtherDocuments'
            ]);
            
            Route::post('upload-corp-other-document',
                [
                'as' => 'upload_corp_other_document',
                'uses' => 'Application\CompanyController@saveOtherDocuments'
            ]);
           
            Route::get('import-doc',['as'=>'import_doc','uses'=>'Application\CompanyController@docDownload']);

            Route::get('downloads/{user_id}',
                [
                'as' => 'downloads',
                'uses' => 'Application\CompanyController@docDownload'
              ]);

            Route::get('thanku',
                [
                'as' => 'thanku',
                'uses' => 'Application\CompanyController@companyThanksPage'
            ]);

            Route::post('emailcheck',
                [
                'as' => 'emailcheck',
                'uses' => 'Application\AccountController@emailexist'
            ]);

             Route::get('term-condition', [
                'as' => 'term_condition',
                'uses' => 'Application\AccountController@termCondition'
            ]
            );

        });
    });

    
    Route::get('reg-inside-profile',
            [
            'as' => 'view_inside_register_profile',
            'uses' => 'Application\DefaultController@accessStorageImages'
            ]
        );
});
