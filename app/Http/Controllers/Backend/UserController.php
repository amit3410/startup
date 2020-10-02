<?php

namespace App\Http\Controllers\Backend;

use Session;
use Validator;
use Redirect;
use Route;
use Auth;
use Event;
use Helpers;
use PDF;
use Hash;
use DateTime;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileFormRequest;
use App\Http\Requests\ChangePasswordFormRequest;
use App\Http\Requests\BackendUserFormRequest;
use App\Inv\Repositories\Models\Master\Country;
use App\Inv\Repositories\Contracts\Traits\ApiAccessTrait;
use App\Inv\Repositories\Contracts\UserInterface as InvUserRepoInterface;
use App\Inv\Repositories\Contracts\ApplicationInterface as InvAppRepoInterface;
use App\Inv\Repositories\Contracts\WcapiInterface as WcapiRepoInterface;
use App\Inv\Repositories\Libraries\Storage\Contract\StorageManagerInterface;

class UserController extends Controller {

    /**
     * User repository
     *
     * @var object
     */
    protected $userRepo;

    /**
     * Application repository
     *
     * @var object
     */
    protected $application;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(InvUserRepoInterface $user, InvAppRepoInterface $application, WcapiRepoInterface $wcapi, StorageManagerInterface $storage) {

        $this->userRepo = $user;
        $this->application = $application;
        $this->wcapi = $wcapi;
        $this->storage = $storage;
        $this->middleware('checkBackendLeadAccess');
    }

use ApiAccessTrait;

    /**
     * Show country list.
     *
     * @return Response
     */
    public function viewProfile(Request $request) {
        $userId = \Auth::user()->user_id;
        $userDetails = $this->userRepo->getUserDetail($userId);
        return view('backend.view_profile')->with(['userDetails' => $userDetails]);
    }

    /**
     * Update user profile
     * 
     * @return type
     */
    public function updateProfile() {
        $request = request();
        $userId = \Auth::user()->user_id;
        $userDetails = $this->userRepo->getUserDetail($userId);
        return view('backend.update_profile')->with(['userDetails' => $userDetails]);
    }

    /**
     * Update user profile.
     * 
     * Request UpdateProfileFormRequest
     * @return Response
     */
    public function updateUserProfile(UpdateProfileFormRequest $request) {
        try {
            $userId = \Auth::user()->user_id;
            $email = request()->get('email');
            $first_name = request()->get('first_name');
            $last_name = request()->get('last_name');
            $status_comment = request()->get('status_comment');

            $arrData = [
                'f_name' => $first_name,
                'l_name' => $last_name,
                'email' => $email,
            ];
            $updateUserDate = $this->userRepo->save($arrData, $userId);
            Session::flash('message', 'Basic details have been saved successfully.');
            return redirect(route('view_profile'));
        } catch (Exception $e) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($e))->withInput();
        }
    }

    /**
     * Ajax file upload
     * 
     * @param Request $request
     * @return string
     * 
     */
    public function ajaxImageUpload(Request $request) {
        $validator = Validator::make($request->all(), [
                    'file' => 'mimes:jpeg,jpg,png,gif|required|max:10000',
                        ], [
                    'file.mimes' => 'The file must be an image (jpeg, jpg, png, bmp, gif, or svg)'
        ]);
        if ($validator->fails())
            return array(
                'fail' => true,
                'errors' => $validator->errors()
            );

        $extension = $request->file('file')->getClientOriginalExtension();
        $dir = storage_path() . '/app/public/';
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $request->file('file')->move($dir, $filename);
        $arrData = ['user_photo' => $filename];
        $userId = \Auth::user()->user_id;
        $updateUserDate = $this->userRepo->save($arrData, $userId);
        return $filename;
    }

    /**
     * Change password form
     * 
     */
    public function changePassword() {
        return view('backend.change_password_form');
    }

    /**
     * Update change password
     * @param Request $request
     * @return string
     */
    public function updateChangePassword(ChangePasswordFormRequest $request) {
        try {

            $request_data = $request->All();
            $current_password = \Auth::User()->password;
            if (\Hash::check($request_data['old_password'], $current_password)) {
                $user_id = \Auth::User()->user_id;
                $obj_user = $this->userRepo->find($user_id);
                $obj_user->password = \Hash::make($request_data['new_password']);
                $obj_user->save();
                Session::flash('message', trans('error_messages.admin.password_changed'));
                return Redirect::back();
            } else {
                $error = ['old_password' => trans('error_messages.admin.correct_old_password')];

                return Redirect::back()->withErrors($error);
            }
        } catch (Exception $e) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($e))->withInput();
        }
    }

    //viewAllUser
    /*
     * show user list
     * 
     */

    public function viewUserList() {
        return view('backend.users');
    }

    /*
     * show user Details
     * @param Request $request 
     */

    public function viewUserDetail(Request $request) {
        
        try {
            $APISecret = config('common.APISecret');
            $atwayurl = config('common.gatwayurl');
            $contentType = config('common.contentType');
            $gatwayhost = config('common.gatwayhost');
            $apiKey = config('common.apiKey');
            $groupId = config('common.groupId');

            $dataArray = [];
            $dataArray['groupId'] = $groupId;
            $dataArray['entityType'] = "INDIVIDUAL";
            $dataArray['providerTypes'] = array("WATCHLIST");
            $dataArray['name'] = "putin";
            $dataArray['secondaryFields'] = [];
            $dataArray['customFields'] = [];
            $assesmentDetail = [];
            $arrAssesmentRating = [];
            $assesmentOldData = [];
            $arrRankName = "";

            $endodedData = json_encode($dataArray);
            $user_id = (int) $request->get('user_id');
            $user_type = (int) $request->get('user_type');
            $userKycId = (int) $request->get('user_kyc_id');
            $is_by_company = (int) $request->get('is_by_company');
            $tab = '';
            $tab =  $request->get('tab');
            //echo "==>".$is_by_company; exit;
            $benifinary = [];
            if ($is_by_company == '1') {
                $corp_user_kyc_id = (int) $request->get('corp_user_kyc_id');
                $corp_user_id = $user_id;
                $user_id = 0;
                $benifinary['user_id'] = $user_id;
                $benifinary['corp_user_id'] = $corp_user_id;
                $benifinary['user_type'] = $user_type;
                $benifinary['userKycId'] = $userKycId;
                $benifinary['corp_user_kyc_id'] = $corp_user_kyc_id;
                $benifinary['is_by_company'] = $is_by_company;
            } else {
                $corp_user_id = 0;
                $corp_user_kyc_id = 0;
                $benifinary['user_id'] = $user_id;
                $benifinary['corp_user_id'] = $corp_user_id;
                $benifinary['user_type'] = $user_type;
                $benifinary['userKycId'] = $userKycId;
                $benifinary['corp_user_kyc_id'] = $corp_user_kyc_id;
                $benifinary['is_by_company'] = $is_by_company;
            }

           // echo "user kyc_id:->".$userKycId."==>".$is_by_company; exit;


            if ($userKycId == '' && $userKycId == 0 && $userKycId == null) {
                $userKycId = $this->application->getUserKycid($user_id);
            }
            $arrAssesmentRating = [];
            $rank_decision = '';
           // echo "==>".$user_id; exit;
            if ($user_id > 0) {
                $userData = $this->userRepo->getUserDetail($user_id);
                $userByComapnyId = $user_id;
            } else {
                
                $userData = 'not';
                $userByComapnyId = $corp_user_id;
            }
            ///get user Assgement Data

            $assesmentData = $this->userRepo->getAssesmentData($userKycId, 1);

            //dd($assesmentData);
            $assesmentOldData = $this->userRepo->getAssesmentData($userKycId, 0);

            if ($assesmentData && $assesmentData->count()) {
                $arrRankName = Helper::getRankNames();
                $rank_decision = $arrRankName[$assesmentData[0]->avg_rank];
            } else {
                $arrRankName = [];
            }

            $assesmentDetail    = $this->userRepo->getRiskAssesmentRating($userKycId);
            $documentsData      = $this->userRepo->getDocumentTypeInfo($userKycId);
            $socialmediaData    = $this->userRepo->getSocialmediaInfo($userKycId);
            $userPersonalData   = $this->userRepo->getUseKycPersonalData($userKycId);
            $familyData         = $this->userRepo->getFamilyInfo($userKycId);
            $residentialData    = $this->userRepo->getResidentialInfo($userKycId);
            $professionalData   = $this->userRepo->getProfessionalInfo($userKycId);
            $otherDocsArray     = $this->application->otherReqDocument($userKycId);
            //$commercialData     =   $this->userRepo->getCommercialInfo($userKycId);
            $commercialData     = $this->userRepo->getCommercialInfo($userKycId);
            $financialData      = $this->userRepo->getFinancialInfo($userKycId);
            $bussAddrData       = $this->userRepo->getBussAddrInfo($userKycId);
            $documentArray      = $this->application->corporateDocument($userKycId);
            $kycDetail          = $this->wcapi->getWcapiDetail($userKycId, 'screeningRequest');
            $kycpersonalDetail  = $this->wcapi->getWcapiPersonalDetailFinal($userKycId, 'profile');
            $kycCompleteArray   = Helpers::getKycDetailsByKycID($userKycId);
            $isKycComplete      = isset($kycCompleteArray) ? $kycCompleteArray['is_kyc_completed'] : 0;
            $isKycApprove       = isset($kycCompleteArray) ? $kycCompleteArray['is_approve'] : 0;
            $isapiPulled        = isset($kycCompleteArray) ? $kycCompleteArray['is_api_pulled'] : 0;
            $complianceReport   = $this->userRepo->complianceReport($userKycId);

            /* User Type */
            $is_byCompany = $kycCompleteArray->is_by_company;
            if ($is_byCompany == 1) {
                $userType = 1;
            } else {
                $userType = $this->application->getUserType($kycCompleteArray->user_id);
            }

            if($is_byCompany == 1 && $corp_user_kyc_id == 0){

               $corp_user_id = $kycCompleteArray->user_id;
               $corp_user_kyc_id = $this->application->getUserKycid($corp_user_id);
                $corp_user_id = $user_id;
                $user_id = 0;
                $benifinary['user_id'] = $user_id;
                $benifinary['corp_user_id'] = $corp_user_id;
                $benifinary['user_type'] = 1;
                $benifinary['userKycId'] = $userKycId;
                $benifinary['corp_user_kyc_id'] = $corp_user_kyc_id;
                $benifinary['is_by_company'] = $is_byCompany;
               // echo "<pre>";
                //print_r($benifinary);
               // exit;
            }

           // echo "<pre>";print_r($documentsData); exit;
            /* End User Type */
            $IdentityCardNumber = '';
            $userPassportNumber = '';
            $IdentityCardExp = '';
            $userPassportExp ='';
            if ($documentsData) {
                $passportArray = $this->getPassportNumber($documentsData,'passport');
                $passportArrays = explode("#",$passportArray);
                $userPassportNumber = @$passportArrays[0];
                $userPassportExp    = @$passportArrays[1];
                $IdentityCardArray = $this->getPassportNumber($documentsData,'idcard');
                $IdentityCardArrays = explode("#",$IdentityCardArray);
                $IdentityCardNumber = @$IdentityCardArrays[0];
                $IdentityCardExp = @$IdentityCardArrays[1];

            } 

           

            //$userScoreData = [];
            $arrCountry = Helpers::getCountryDropDown();
            $userCountryID = isset($residentialData->country_id) ? $residentialData->country_id : '';
            $userNationalityID = isset($userPersonalData->f_nationality_id) ? $userPersonalData->f_nationality_id : '';
            if ($userCountryID) {
                $userCountry = $arrCountry[$userCountryID];
            } else {
                $userCountry = $arrCountry[101];
            }

            if ($userNationalityID) {
                $userNationality = $arrCountry[$userNationalityID];
            } else {
                $userNationality = $arrCountry[101];
            }



            
            $bussAddrData = $this->userRepo->getBussAddrInfo($userKycId);
            return view('backend.user_detail')
                        ->with(['userData' => $userData, 'content' => $endodedData,
                            'userPersonalData' => $userPersonalData, 'documentsData' => $documentsData,
                            'socialmediaData' => $socialmediaData, 'familyData' => $familyData,
                            'residentialData' => $residentialData, 'professionalData' => $professionalData,
                            'commercialData' => $commercialData, 'financialData' => $financialData, 'bussAddrData' => $bussAddrData,
                            'userKycId' => $userKycId, 'documentArray' => $documentArray, 'kycDetail' => $kycDetail, 'benifinary' => $benifinary,
                            'arrAssesmentRating' => $arrAssesmentRating, 'rank_decision' => $rank_decision,
                            'assesmentDetail' => $assesmentDetail, 'isKycComplete' => $isKycComplete, 'isKycApprove' => $isKycApprove,
                            'isapiPulled' => $isapiPulled,
                            'kycCompleteArray' => $kycCompleteArray, 'assesmentOldData' => $assesmentOldData,'assesmentData' => $assesmentData,
                            'userCountry' => $userCountry, 'kycpersonalDetail' => $kycpersonalDetail,
                            'arrRankName' => $arrRankName, 'userType' => $userType, 'userByComapnyId' => $userByComapnyId,
                            'otherDocsArray' => $otherDocsArray,'complianceReport' => $complianceReport,'userPassportNumber' => $userPassportNumber
                            ,'userPassportExp' => $userPassportExp,'IdentityCardNumber' => $IdentityCardNumber,'IdentityCardExp' => $IdentityCardExp,
                            'tab'=>$tab,'userNationality' => $userNationality]);
        } catch (Exception $exc) {
            //dd(Helpers::getExceptionMessage($exc));
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($exc))->withInput();
        }
    }

    public function viewCorporateUserDetail(Request $request) {

        try {

            $APISecret = config('common.APISecret');
            $atwayurl = config('common.gatwayurl');
            $contentType = config('common.contentType');
            $gatwayhost = config('common.gatwayhost');
            $apiKey = config('common.apiKey');
            $groupId = config('common.groupId');


            //$fitstName = $userData[0]->f_name;
            $dataArray = [];
            $dataArray['groupId']       = $groupId;
            $dataArray['entityType']    = "INDIVIDUAL";
            $dataArray['providerTypes'] = array("WATCHLIST");
            $dataArray['name']          = "putin";
            $dataArray['secondaryFields'] = [];
            $dataArray['customFields'] = [];
            $secectedtab                = '';
            $endodedData                = json_encode($dataArray);
            $user_id                    = $request->get('user_id');
            $user_type                  = $request->get('user_type');
            $userKycId                  = $request->get('user_kyc_id');
            $is_by_company              = $request->get('is_by_company');
            $tab                = $request->get('tab');
            $assesmentDetail = [];
            $arrAssesmentRating = [];
            $assesmentOldData = [];
            $arrRankName = "";
            $benifinary = [];

            $benifinary['user_id'] = $user_id;
            $benifinary['user_type'] = $user_type;
            $benifinary['userKycId'] = $userKycId;
            $benifinary['is_by_company'] = $is_by_company;

            if ($userKycId == '' && $userKycId == 0 && $userKycId == null) {
                $userKycId = $this->application->getUserKycid($user_id);
            }
            $userKycId = $this->application->getUserKycid($user_id);
            $notAll = $this->userRepo->isCorpIndvsKycCompleted($user_id);

            $arrAssesmentRating = [];
            $rank_decision      = '';
            $assesmentData = $this->userRepo->getAssesmentData($userKycId, 1);
            $assesmentOldData = $this->userRepo->getAssesmentData($userKycId, 0);
            if ($assesmentData && $assesmentData->count()) {
                $arrRankName = Helper::getRankNames();
                $rank_decision = $arrRankName[$assesmentData[0]->avg_rank];
            } else {
                $arrRankName = [];
            }

            $assesmentDetail    = $this->userRepo->getRiskAssesmentRating($userKycId);
            $otherDocsArray = $this->application->otherReqDocument($userKycId);
            $complianceReport = $this->userRepo->complianceReport($userKycId);
            //dd($complianceReport);
            $userData = $this->userRepo->getCorpUserDetailAdmin($user_id);
            $companyProfile = $this->application->getCompanyProfileData($userKycId);
            $socialmediaData = $this->userRepo->getCorpSocialmediaInfo($userKycId);


            $companyAddress = $this->application->getCompanyAddress($userKycId);
            $companyFinancial = $this->application->getCompanyFinancialData($userKycId);
            $documentArray = $this->application->corporateDocument($userKycId);
            $beficiyerArray = [];
            $beficiyerArray = $this->application->getBeneficiaryOwnersData($user_id);
            $kycCompleteArray = Helpers::getKycDetailsByKycID($userKycId);
            $isKycComplete = isset($kycCompleteArray) ? $kycCompleteArray['is_kyc_completed'] : 0;
            $isKycApprove = isset($kycCompleteArray) ? $kycCompleteArray['is_approve'] : 0;

            $beficiyerData = [];
            $beficiyerData = $this->application->getBeneficiaryOwnersData($user_id);
            $beficiyerDataArray = [];
            $beficiyerDataArray = $this->application->getBeneficiary($user_id);
            
            //echo $userKycId; exit;
            $kycpersonalDetail  = $this->wcapi->getWcapiPersonalDetailFinal($userKycId, 'corp-profile');
            $kycDetail = $this->wcapi->getWcapiDetail($userKycId, 'screeningRequest-corp');
            $arrCountry = Helpers::getCountryDropDown();
            $corpregisterCountry = isset($companyAddress->country_id) ? $companyAddress->country_id : '';
            if ($corpregisterCountry) {
                $corpregisterCountry = $arrCountry[$corpregisterCountry];
            } else {
                $corpregisterCountry = $arrCountry[101];;
            }

           

            return view('backend.corp_user_detail')
                            ->with(['userData' => $userData, 'content' => $endodedData, 'companyProfile' => $companyProfile,
                                'companyAddress' => $companyAddress, 'companyFinancial' => $companyFinancial,
                                'documentArray' => $documentArray, 'userKycId' => $userKycId, 'benifinary' => $benifinary,
                                'beficiyerArray' => $beficiyerArray, 'kycDetail' => $kycDetail,
                                'arrAssesmentRating' => $arrAssesmentRating, 'rank_decision' => $rank_decision, 'assesmentDetail' => $assesmentDetail,
                                'notAll' => $notAll, 'kycCompleteArray' => $kycCompleteArray,
                                'isKycApprove' => $isKycApprove, 'otherDocsArray' => $otherDocsArray,'tab' => $tab,
                                'complianceReport' => $complianceReport, 'beficiyerData' => $beficiyerData,'beficiyerDataArray'=>$beficiyerDataArray,
                                'assesmentOldData' => $assesmentOldData,'assesmentData' => $assesmentData,'arrRankName' => $arrRankName,
                                'corpregisterCountry' => $corpregisterCountry,'kycpersonalDetail' =>$kycpersonalDetail,'socialmediaData'=>$socialmediaData]);
        } catch (Exception $exc) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($exc))->withInput();
        }
    }

    public function docDownload(Request $request) {
        $documentHash = $request->get('enc_id');
        $user_type = $request->get('user_type');

        $docList = $this->application->getSingleDocument($documentHash);
        $userID = $docList->user_kyc_id;
        $fileName = $docList->doc_name . "." . $docList->doc_ext;
        if ($user_type == '1') {
            $file = storage_path('app/appDocs/Document/indivisual/pdf/' . $userID . "/" . $fileName);
        } else {
            $file = storage_path('app/appDocs/Document/corporate/pdf/' . $userID . "/" . $fileName);
        }
        $arr = [$file, $file];
       // return response()->download($file);
        /////Manual Download ////
        $mimetype = mime_content_type($file);
        //echo $mimetype; exit;
        //ob_end_clean();
        //return response()->download($file);
        $data = file_get_contents($file);
        //echo $data; exit;
        ob_end_clean();
        header('Content-Type: "'.$mimetype.'"');
        header('Content-Disposition: attachment; filename="'.$fileName.'"');
        header("Content-Transfer-Encoding: binary");
        header('Expires: 0'); header('Pragma: no-cache');
        header("Content-Length: ".strlen($data));
        echo $data;
        exit;



    }

    /*
     * show user Details form
     * @param Request $request 
     */

    public function editUser(Request $request) {

        try {
            $user_id = $request->get('user_id');
            $userData = $this->userRepo->getUserDetail($user_id);



            $countryDropDown = Country::getDropDown();
            return view('backend.edit_user')
                            ->with(['countryDropDown' => $countryDropDown])
                            ->with(['userData' => $userData]);
        } catch (Exception $exc) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($exc))->withInput();
        }
    }

    /*
     * Update user
     * @param BackendUserFormRequest $request
     */

    public function saveUser(BackendUserFormRequest $request) {

        try {
            $user_id = $request->get('user_id');
            $fname = $request->get('first_name');
            $lname = $request->get('last_name');
            $occupation = $request->get('occupation');
            $about = $request->get('about');
            $phone = $request->get('phone');
            $at_phone = $request->get('alternate_phone');
            $addr1 = $request->get('addr1');
            $addr2 = $request->get('addr2');
            $area = $request->get('area');
            $country_id = $request->get('country_id');
            $city = $request->get('city');
            $zip_code = $request->get('zip_code');
            $status = $request->get('status');
            $userArr = [
                'first_name' => isset($fname) ? $fname : NULL,
                'last_name' => isset($lname) ? $lname : NULL,
                'occupation' => isset($occupation) ? $occupation : NULL,
                'about' => isset($about) ? $about : NULL,
                'phone' => isset($phone) ? $phone : NULL,
                'at_phone' => isset($at_phone) ? $at_phone : NULL,
                'addr1' => isset($addr1) ? $addr1 : NULL,
                'addr2' => isset($addr2) ? $addr2 : NULL,
                'area' => isset($area) ? $area : NULL,
                'country_id' => isset($country_id) ? $country_id : NULL,
                'city' => isset($city) ? $city : NULL,
                'zip_code' => isset($zip_code) ? $zip_code : NULL,
                'status' => isset($status) ? $status : NULL,
            ];
            $result = $this->userRepo->updateUser($userArr, $user_id);
            Session::flash('message', 'User updated successfully.');
            return Redirect::Route('manage_users');
        } catch (Exception $ex) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($exc))->withInput();
        }
    }

    /* Soft detele user
     * @param Request $request
     */

    public function deleteUser(Request $request) {
        $user_id = $request->get('uid');
        $response = $this->userRepo->deleteUser($user_id);
        return $response;
    }

    /*
     * show Users list
     * 
     */

    public function viewAllUser() {

        $usersList = $this->userRepo->getAllUsersPaginate();
        return view('backend.users', ['usersList' => $usersList]);
    }

    public function viewUserAjaxPaginate(Request $request) {
        if ($request->ajax()) {
            $usersList = $this->userRepo->getAllUsersPaginate();
            return view('backend.pagination_data', ['usersList' => $usersList])->render();
        }
    }

    /*
     * update scoute
     * @param Request $request
     */

    public function updateUserDetail(Request $request) {

        $v = Validator::make($request->all(), ['is_approved' => 'required']);
        $status = "";
        if ($v->fails()) {
            return redirect()->back()->withErrors($v->errors());
        }
        $user_id = $request->get('user_id');

        $email_id = $request->get('email_id');
        $is_approved_stack = $request->get('is_approved_stack');

        $userArr = [
            'is_admin_approved' => $request->get('is_approved'),
            'reason' => $request->get('reason'),
            'user_type' => 2
        ];
        $userData = $this->userRepo->getUserDetail($user_id);
        //call api for tranjection

        $rrt = (int) $request->get('is_approved');
        $dArr = [];
        if ($userData->is_admin_approved != 0 || $rrt == 1) {
            $dArr['address'] = $userData->address;
            $dArr['type'] = 2;
            $dArr['status'] = $request->get('is_approved') - 1;
            $status = $this->onUpdateUser($dArr);
        }
        /*
          if($is_approved_stack == 0){
          $status = $this->onApprovedScout($email_id, \Auth::user()->user_type);
          }else{
          $status = $this->onUpdateUser($request->get('is_approved'));
          } */
        //dd($status);
        if ($status == '200') {
            $result = $this->userRepo->updateUser($userArr, $user_id);
            $userMailArr = [];
            $userMailArr['email_owner'] = $email_id;
            $userMailArr['name'] = $userData->first_name;
            $userMailArr['reason'] = $request->get('reason');
            if ($userArr['is_admin_approved'] == 1) {
                Event::fire("admin.approved", serialize($userMailArr));
            } else {
                Event::fire("admin.disapproved", serialize($userMailArr));
            }

            Session::flash('message', 'User updated successfully.');
            return Redirect::Route('show_scout');
        } else {
            return redirect()->back()->withErrors('Private key for the user already exists!');
        }
    }

    /*     * ***************************** */
    /*
     * show all individual users
     * 
     */

    public function individualUsersList(Request $request) {
        $filter = [];
        $search_keyword = $request->get('search_keyword');
        $kyc_status     = $request->get('kyc_status');
        $user_source    = $request->get('user_source');
        $profile_status = $request->get('profile_status');
        $filter['search_keyword']   = '';
        $filter['kyc_status']       = '';
        $filter['user_source']      = '';
        $filter['profile_status']   = '';

        if ($search_keyword != '' && $search_keyword != null) {
            $filter['search_keyword'] = $search_keyword;
        }

        if ($kyc_status != '' && $kyc_status != null) {
            $filter['kyc_status'] = $kyc_status;
        }


        if ($user_source != '' && $user_source != null) {
            $filter['user_source'] = $user_source;
        }

        if ($profile_status != '' && $profile_status != null) {
            $filter['profile_status'] = $profile_status;
        }

        $usersList = $this->userRepo->getAllUsersPaginate(1, $filter);
        /*echo "<pre>";
        print_r($usersList);die;*/
        return view('backend.users', ['usersList' => $usersList, 'filter' => $filter]);
    }

    /*
     * show all Corporate users
     * 
     */

    public function corporateUsersList(Request $request) {
        $filter = [];
        $search_keyword = $request->get('search_keyword');
        $kyc_status     = $request->get('kyc_status');
        $profile_status = $request->get('profile_status');
        $filter['search_keyword']   = '';
        $filter['kyc_status']       = '';
        if ($search_keyword != '' && $search_keyword != null) {
            $filter['search_keyword'] = $search_keyword;
        }
        if ($kyc_status != '' && $kyc_status != null) {
            $filter['kyc_status'] = $kyc_status;
        }

        if ($profile_status != '' && $profile_status != null) {
            $filter['profile_status'] = $profile_status;
        }

        $usersList = $this->userRepo->getAllUsersPaginate(2, $filter);
       // echo "<pre>";
       // print_r($usersList);
       // exit;

        return view('backend.users', ['usersList' => $usersList, 'filter' => $filter]);
    }

    /*
     * show all Trading users
     * 
     */



    public function individualTradingUsersList(Request $request) {
        $filter = [];
        $search_keyword = $request->get('search_keyword');
        $kyc_status = $request->get('kyc_status');
        $filter['search_keyword'] = '';
        $filter['kyc_status'] = '';

        if ($search_keyword != '' && $search_keyword != null) {
            $filter['search_keyword'] = $search_keyword;
        }

        if ($kyc_status != '' && $kyc_status != null) {
            $filter['kyc_status'] = $kyc_status;
        }

        $usersList = $this->userRepo->getAllTradingUsersPaginate(1, $filter);
        return view('backend.users', ['usersList' => $usersList, 'filter' => $filter]);
    }



    /*
     * show all Trading Corporate users
     *
     */

    public function corporateTradingUsersList(Request $request) {
        $filter = [];
        $search_keyword = $request->get('search_keyword');
        $kyc_status     = $request->get('kyc_status');
        $filter['search_keyword']   = '';
        $filter['kyc_status']       = '';
        if ($search_keyword != '' && $search_keyword != null) {
            $filter['search_keyword'] = $search_keyword;
        }
        if ($kyc_status != '' && $kyc_status != null) {
            $filter['kyc_status'] = $kyc_status;
        }
        $usersList = $this->userRepo->getAllTradingUsersPaginate(2, $filter);
        return view('backend.users', ['usersList' => $usersList, 'filter' => $filter]);
    }

    public function updateUserStatusform(Request $request) {
        $userId = $request->get('user_id');
        $current_status = $request->get('current_status');
        $curouteName = $request->get('curouteName');
        $user_type = $request->get('user_type');
        $route = route("individual_user");
        return view('framePopup.individual_status_change', ['userId' => $userId,'current_status' => $current_status, 'curouteName' => $curouteName,'route' =>$route,'user_type' => $user_type]);
    }




    public function updateUserStatus(Request $request) {
        $userId = $request->get('user_id');
        $current_status = $request->get('current_status');
        $curouteName = $request->get('curouteName');
        $user_type = $request->get('user_type');
        if ($current_status == '1') {
            $arrData['is_active'] = 0;
        } else {
            $arrData['is_active'] = 1;
        }

       
        $updateUserDate = $this->userRepo->save($arrData, $userId);
        $userArr = $this->userRepo->find($userId, ['email', 'first_name', 'last_name', 'user_type']);
        //mail sent to Indi and corp
        if ($current_status == '1') {

            $userArr = $this->userRepo->find($userId, ['email', 'first_name', 'last_name', 'user_type']);
            $verifyUserArr = [];
            $verifyUserArr['name'] = $userArr->f_name . ' ' . $userArr->l_name;
            $verifyUserArr['email'] = $userArr->email;
            $verifyUserArr['usertype'] = $userArr->user_type;
            Event::dispatch("user.email.indi_corp_unlock_account", serialize($verifyUserArr));
        }
        if($userArr->user_type == 1) {
            $route = route("individual_user");
        } else if($userArr->user_type == 2) {
            $route = route("corporate_user");
        }
       
        Session::flash('is_accept', 1);
        Session::flash('message', 'Status has been updated successfully.');
        return view('framePopup.individual_status_change', ['userId' => $userId,'current_status' => $current_status, 'curouteName' => $curouteName,'route' =>$route,'user_type' => $user_type]);
        //return redirect(route($curouteName));
    }

    public function storeUserRiskAssessmentRank(Request $request) {
        $data       = $request->all();
        $user_id    = \Auth::User()->user_id;
        $total_rank = 0;
        $national_identity      = $request->get('national_identity');
        $worldcheck_match       = $request->get('worldcheck_match');
        $passport_verification  = $request->get('passport_verification');
        $is_peep                = $request->get('is_peep');
        $is_socialmedia         = $request->get('is_socialmedia');
        $is_by_company          = (int) $request->get('is_by_company');
        if($is_by_company == 1) {
            $corp_user_kyc_id   = $request->get('corp_user_kyc_id');
            $corp_user_id       = $request->get('corp_user_id');

            
        }

        $kycId = $data['user_kyc_id'];
        //$this->userRepo->deleteRiskAssesmentDetail($data['user_kyc_id']);
        // Update Old Records
        $updateRiskAssesmentDetail = [];
        $updateRiskAssesmentDetail['is_active'] = 0;
        $this->userRepo->updateRiskAssesmentDetail($updateRiskAssesmentDetail, $data['user_kyc_id']);
        $x=0;
        if ($national_identity) {
            $saveData['user_kyc_id'] = $data['user_kyc_id'];
            $saveData['assesment_id'] = 1;
            $saveData['rank'] = $national_identity;
            $saveData['is_active'] = 1;
            $saveData['created_by'] = $user_id;
            $saveData['updated_by'] = $user_id;
            $saveData['created_at'] = date('Y-m-d H:i:s');
            $saveData['updated_at'] = date('Y-m-d H:i:s');
            $this->userRepo->saveBatchAssesmentDetailData($saveData);
            $total_rank = $total_rank + (int) $national_identity;
            $x = 1;
        }


        if ($worldcheck_match) {
            $saveData['user_kyc_id'] = $data['user_kyc_id'];
            $saveData['assesment_id'] = 2;
            $saveData['rank'] = $worldcheck_match;
            $saveData['is_active'] = 1;
            $saveData['created_by'] = $user_id;
            $saveData['updated_by'] = $user_id;
            $saveData['created_at'] = date('Y-m-d H:i:s');
            $saveData['updated_at'] = date('Y-m-d H:i:s');
            $this->userRepo->saveBatchAssesmentDetailData($saveData);
            $total_rank = $total_rank + (int) $worldcheck_match;
            $x++;
        }

        if ($passport_verification) {
            $saveData['user_kyc_id'] = $data['user_kyc_id'];
            $saveData['assesment_id'] = 3;
            $saveData['rank'] = $passport_verification;
            $saveData['is_active'] = 1;
            $saveData['created_by'] = $user_id;
            $saveData['updated_by'] = $user_id;
            $saveData['created_at'] = date('Y-m-d H:i:s');
            $saveData['updated_at'] = date('Y-m-d H:i:s');
            $this->userRepo->saveBatchAssesmentDetailData($saveData);
            $total_rank = $total_rank + (int) $passport_verification;
            $x++;
        }

        if ($is_peep) {
            $saveData['user_kyc_id'] = $data['user_kyc_id'];
            $saveData['assesment_id'] = 4;
            $saveData['rank'] = $is_peep;
            $saveData['is_active'] = 1;
            $saveData['created_by'] = $user_id;
            $saveData['updated_by'] = $user_id;
            $saveData['created_at'] = date('Y-m-d H:i:s');
            $saveData['updated_at'] = date('Y-m-d H:i:s');
            $this->userRepo->saveBatchAssesmentDetailData($saveData);
            $total_rank = $total_rank + (int) $is_peep;
            $x++;
        }

        if ($is_socialmedia) {
            $saveData['user_kyc_id'] = $data['user_kyc_id'];
            $saveData['assesment_id'] = 5;
            $saveData['rank'] = $is_socialmedia;
            $saveData['is_active'] = 1;
            $saveData['created_by'] = $user_id;
            $saveData['updated_by'] = $user_id;
            $saveData['created_at'] = date('Y-m-d H:i:s');
            $saveData['updated_at'] = date('Y-m-d H:i:s');
            $this->userRepo->saveBatchAssesmentDetailData($saveData);
            $total_rank = $total_rank + (int) $is_socialmedia;
            $x++;
        }

        //$org_avg_rank   = $total_rank / 5;
        $org_avg_rank   = $total_rank / $x;
        $avg_rank       = round($org_avg_rank);
        $saveAssesment  = [];
        $saveAssesment['user_kyc_id'] = $data['user_kyc_id'];
        $saveAssesment['avg_rank'] = $avg_rank;
        $saveAssesment['org_avg_rank'] = $org_avg_rank;
        $saveAssesment['created_by'] = $user_id;
        $saveAssesment['updated_by'] = $user_id;
        $saveAssesment['created_at'] = date('Y-m-d H:i:s');
        $saveAssesment['updated_at'] = date('Y-m-d H:i:s');

        //  $id = (@$data['id'] != '' && @$data['id'] != null) ? (int) $data['id'] : null;
        // check assesment exist
        $assesment = $this->userRepo->getAssesmentData($data['user_kyc_id'], 1);


        if ($assesment && $assesment->count()) {
            $saveAssesmentidActive['is_active'] = 0;
            //$this->userRepo->saveAssesmentData($saveAssesment, $assesment[0]->id);
            $this->userRepo->updateRiskAssesmentRankbyID($saveAssesmentidActive, $assesment[0]->id);
            $saveAssesment['is_active'] = 1;
            $id = null;
            $this->userRepo->saveAssesmentData($saveAssesment, $id);
        } else {
            $saveAssesment['is_active'] = 1;
            $id = null;
            $this->userRepo->saveAssesmentData($saveAssesment, $id);
        }


        Session::flash('message', 'Risk Assesment Ratting  has been updated successfully.');

        if($is_by_company == 1) {
            if ($data['user_type'] == '1') {
                //echo "debug===".$corp_user_kyc_id."==>".$corp_user_id;exit;
                $route = route("user_detail", ['user_id' => $corp_user_id, 'user_type' => 2, 'corp_user_kyc_id' => $corp_user_kyc_id ,'user_kyc_id' => $data['user_kyc_id'], 'is_by_company' => $is_by_company,'tab' => 'tab05']);
            } 
            

        } else {
            if ($data['user_type'] == '1') {
                $route = route("user_detail", ['user_id' => $data['user_id'], 'user_type' => $data['user_type'], 'user_kyc_id' => $data['user_kyc_id'], 'is_by_company' => 0,'tab' => 'tab05']);
            } else if ($data['user_type'] == '2') {
                $route = route("corp_user_detail", ['user_id' => $data['user_id'], 'user_type' => $data['user_type'], 'user_kyc_id' => $data['user_kyc_id'], 'is_by_company' => 0,'tab' => 'tab05']);
            }
            //return redirect($route);
        }
        return redirect($route);
            
        }



     //// download similar report pdf

    public function similardownloadPDF(Request $request) {
        $data = $request->all();
        $primaryId = $data['primaryId'];
        $referenceId = "";
        $date = new DateTime;
        $kycDetail = $this->wcapi->getkycData((int) $primaryId);

        if($kycDetail->f_name!="") {
           $name = $kycDetail->f_name." ".$kycDetail->l_name;
           isset($fullName)? : $fullName = "NoRecord";
           //PDF::setPaper(array(0,0,800,935.433), 'portrait');
            $customPaper = array(0,0,1191,680);
           // PDF::setPaper('A3', 'portrait');
            $pdf = PDF::loadView('backend.user_similar_download', compact('kycDetail'))->setPaper($customPaper, 'landscape');
            $fileName = $name . ".pdf";
            //exit;
            return $pdf->download($fileName);
        } else {
           $name = $kycDetail->org_name;
           isset($fullName)? : $fullName = "NoRecord";
           $customPaper = array(0,0,1191,680);
          //  PDF::setPaper('A3', 'portrait');
            $pdf = PDF::loadView('backend.corp_detail_similar_download', compact('kycDetail'))->setPaper($customPaper, 'landscape');
            $fileName = $name . ".pdf";
            return $pdf->download($fileName);
        }
        
        

    }

    //// download pdf

    public function downloadPDF(Request $request) {

        
        $data = $request->all();
        $userKycId = $data['userKycId'];
        $primaryId = $data['primaryId'];
        $referenceId = "";
        $date = new DateTime;
        // $profiledata = Helpers::getKycprofileData($userKycId, $referenceId, 'profile');
        $kycCompleteArray = Helpers::getKycDetailsByKycID($userKycId);
        //echo "<pre>";
        //print_r($kycCompleteArray); exit;
        $userData = $kycCompleteArray->user_id;
        $is_byCompany = $kycCompleteArray->is_by_company;
        if ($is_byCompany == 1) {
            $userType = 1;
        } else {
            $userType = $this->application->getUserType($userData);
        }

        if ($userType == 1) {
            $profiledata = Helpers::getlatestKycprofileData((int) $userKycId, 'profile');
        } else {
            $profiledata = Helpers::getlatestKycprofileData((int) $userKycId, 'corp-profile');
        }


        $submitData = $this->wcapi->getkycData((int) $primaryId);

        if ($profiledata) {
            $resultArray1 = json_decode($profiledata);
            $resultArray = json_decode($resultArray1->api_responce, JSON_UNESCAPED_UNICODE);
            //echo "<pre>";
            //print_r($resultArray);
           // exit;
            if ($userType == 1) {
                $category = $resultArray['category'];
                $r_gender = $resultArray['gender'];
                $creationDate = $resultArray['creationDate'];
                $modificationDate = $resultArray['modificationDate'];
                $bioGraphy = $resultArray['details'];
                $fulladdresses = $resultArray['addresses'];
                $updateCategory = $resultArray['updateCategory'];


                //$providerTypes = $resultArray->providerType;

                $fullName = "";
                $roleTitle = "";
                $roleTitle = "";
                $eventsDataDOB = "";
                $countryLinksName = "";
                $sourcesName = "";
                $sourcesDescription = "";
                $webLinks = "";
                $r_location = "";
                $identityDocumentsNumber = "";
                $identityDocumentsType = "";
                $identityDocumentsissueDate = "";
                $identityDocumentsexpiryDate = "";

                ///GET NAMES


                $nameArray = $resultArray['names'];
                if (count($nameArray) > 0) {
                    foreach ($nameArray as $nameData) {
                        if ($nameData['type'] == "PRIMARY") {
                            $fullName = $nameData['fullName'];
                            $title = $nameData['prefix'];
                        }
                    }
                }

                ////Events DOB
                $eventsArray = $resultArray['events'];
                $eventsDataDOB = "";
                $bithPlace = "";
                if (count($eventsArray) > 0) {
                    foreach ($eventsArray as $eventsData) {
                        if ($eventsData['type'] == "BIRTH") {
                            if (isset($eventsData['fullDate'])) {
                                $eventsDataDOB = $eventsData['fullDate'];
                            }

                            if (isset($eventsData['address']['country']['name'])) {
                                $bithPlace = $eventsData['address']['country']['name'];
                            }
                        }
                    }
                }


                ////Role Title
                $Position = "";
                $rolesArray = $resultArray['roles'];
                if (count($rolesArray) > 0) {
                    foreach ($rolesArray as $roleData) {
                        if ($roleData['type'] == "Position") {
                            $Position = $roleData['title'];
                        }
                    }
                }


                ////weblinks
                $weblinksArray = isset($resultArray['weblinks']) ? $resultArray['weblinks'] : [];
                /// Source Links
                $sourcesName = "";
                $sourcesDescription = "";
                $sourcesArray = $resultArray['sources'];
                if (count($sourcesArray) > 0) {

                    foreach ($sourcesArray as $sourcesData) {
                        if ($sourcesData['name'] != "") {
                            $sourcesName = $sourcesData['name'];
                            $sourcesDescription = (string) $sourcesData['type']['category']['description'];
                        }
                    }
                }

                // PEP Type
                $pepTypeArray = [];
                $type = "";
                $pepcategoryName = "";
                $allType = "";
                $pepTypeArray = isset($resultArray['subCategory']) ? $resultArray['subCategory'] : [];
                if(is_array($pepTypeArray) && count($pepTypeArray) > 0) {
                    foreach ($pepTypeArray as $pepTypeData) {
                        $allType = $pepTypeData;
                    }
                } else {
                    $allType = $resultArray['subCategory'];
                }

                //$allType = "";
                $allType = str_replace('Law Enforcement', 'LE', $allType);
                $allType = str_replace('Regulatory Enforcement', 'RE', $allType);
                $allType = str_replace('Other Bodies', 'OB', $allType);
                $type    = $allType;

                ////Country Events
                $countryLinksArray = $resultArray['countryLinks'];


                if (count($countryLinksArray) > 0) {
                    foreach ($countryLinksArray as $countryLinksData) {
                        if ($countryLinksData['type'] == "NATIONALITY") {
                            $Nationality = $countryLinksData['country']['name'];
                        }

                        if ($countryLinksData['type'] == "LOCATION") {
                            $r_location.= $countryLinksData['country']['name'] . ",";
                        }
                    }
                }


                ///Identity
                $identityDocumentsArray = $resultArray['identityDocuments'];
                $stringidentityDocuments = "";
                if (count($identityDocumentsArray) > 0) {

                    foreach ($identityDocumentsArray as $identityDocumentsData) {
                        $identityDocumentsType = "";
                        $identityDocumentsNumber = "";
                        $identityDocumentsissueDate = "";
                        $identityDocumentsexpiryDate = "";
                        $identityDocumentsType = $identityDocumentsData['type'];
                        $identityDocumentsNumber = $identityDocumentsData['number'];
                        $identityDocumentsissueDate = $identityDocumentsData['issueDate'];
                        $identityDocumentsexpiryDate = $identityDocumentsData['expiryDate'];
                        $stringidentityDocuments.= "Type: " . $identityDocumentsType . "<br>Number:" . $identityDocumentsNumber . "<br>Issue Date:" . $identityDocumentsissueDate . "<br>Expiry Date:" . $identityDocumentsexpiryDate;
                    }
                }
                if ($userKycId > 0) {
                    $userPersonalData = $this->userRepo->getUseKycPersonalData($userKycId);
                }


                //echo $GROUPNAME; exit;
                $birth_country_name = Helpers::getCountryById($submitData['country_id']);
                //echo "-->".$birth_country_name->country_name; exit;//
                $clientSubmitionData['referenceId'] = $referenceId;
                $clientSubmitionData['groupName'] = "";
                //$clientSubmitionData['name'] = $userPersonalData['f_name'] . " " . $userPersonalData['m_name'] . " " . $userPersonalData['l_name'];
                $clientSubmitionData['name'] = $submitData['f_name'] . " " . $submitData['m_name'] . " " . $profiledata['l_name'];

                $clientSubmitionData['gender'] = $submitData['gender'];
                $clientSubmitionData['dob'] = $submitData['date_of_birth'];
                $clientSubmitionData['nationality'] = $birth_country_name->country_name; //Helpers::getCountryById($userPersonalData['birth_country_id']);
                //$clientSubmitionData['countryLocation'] = $birth_country_name->country_name;
                // $clientSubmitionData['placeBirth'] = $birth_country_name->country_name;
                /////Fetch Data
                $currentDate = $date->format('d-m-Y H-i');
                isset($creationDate) ? : $creationDate = '';
                $creationDateArray = explode("T00:00:00", $creationDate);
                //$creationDate       =  Helpers::getDateByFormat($creationDateArray[0],'Y/m/d', 'Y-m-d');
                //$modificationDate   =  Helper::getDateByFormat($modificationDate,'d/m/Y', 'Y-m-d');
                $clientSubmitionData['r_title'] = isset($title) ? $title : '';
                $clientSubmitionData['r_name'] = isset($fullName) ? $fullName : '';
                $clientSubmitionData['r_nameAliases'] = isset($nameArray) ? $nameArray : '';
                $clientSubmitionData['r_gender'] = isset($r_gender) ? $r_gender : '';
                $clientSubmitionData['r_dob'] = isset($eventsDataDOB) ? $eventsDataDOB : '';
                $clientSubmitionData['r_nationality'] = isset($Nationality) ? $Nationality : '';
                $clientSubmitionData['r_countryLocation'] = isset($r_location) ? trim($r_location, ",") : '';
                ;
                $clientSubmitionData['r_placeBirth'] = isset($bithPlace) ? $bithPlace : '';
                $clientSubmitionData['r_sourcesName'] = isset($sourcesName) ? $sourcesName : '';
                $clientSubmitionData['r_category'] = isset($category) ? $category : '';
                ;
                $clientSubmitionData['r_position'] = isset($Position) ? $Position : '';
                ;
                $clientSubmitionData['r_creationDate'] = isset($creationDate) ? $creationDate : ''; //$date->format('d-m-Y H-i');
                $clientSubmitionData['r_updateCategory'] = isset($updateCategory) ? $updateCategory : '';

                $clientSubmitionData['r_modificationDate'] = isset($modificationDate) ? $modificationDate : '';
                $clientSubmitionData['r_sourcesArray'] = isset($sourcesArray) ? $sourcesArray : '';
                $clientSubmitionData['r_bioGraphy'] = isset($bioGraphy) ? $bioGraphy : '';
                $clientSubmitionData['r_weblinksArray'] = isset($weblinksArray) ? $weblinksArray : '';
                $clientSubmitionData['currentDate'] = isset($currentDate) ? $currentDate : '';
                $clientSubmitionData['pep'] = isset($type) ? $type : '';

                $clientSubmitionData['fulladdresses'] = isset($fulladdresses) ? $fulladdresses : '';
                // This  $data array will be passed to our PDF blade
                isset($fullName)? : $fullName = "NoRecord";
                $pdf = PDF::loadView('backend.report_download', $clientSubmitionData);
                $fileName = $fullName . ".pdf";
                //return $pdf->download($fileName);
                return $pdf->stream($fileName,array('Attachment'=>0));

            } else {
                $downloadCompanyPdf = true;
                
                $parent_id      = $resultArray1->parent_id;
                $rowdata        = $this->wcapi->getKycDatabyparentId($parent_id);
                $creationDate   = $resultArray['creationDate'];
                $modificationDate = $resultArray['modificationDate'];
                if ($downloadCompanyPdf) {
                    $category = $resultArray['category'];
                    //$providerTypes = $resultArray->providerType;
                    $nameArray = $resultArray['names'];
                    if (count($nameArray) > 0) {
                        foreach ($nameArray as $nameData) {
                            if ($nameData['type'] == "PRIMARY") {
                                $fullName = $nameData['fullName'];
                            }
                        }
                    }

                    ////weblinks
                    $weblinksArray = $resultArray['weblinks'];
                    $webLinks = "";
                    if (count($weblinksArray) > 0) {
                        foreach ($weblinksArray as $weblinksData) {
                            if ($weblinksData['uri'] != "") {
                                $webLinks.= $weblinksData['uri'] . "<br>";
                            }
                        }
                    }
                    //dd($weblinksArray);
                    /// Source Links
                    $sourcesArray = $resultArray['sources'];
                    if (count($sourcesArray) > 0) {
                        foreach ($sourcesArray as $sourcesData) {
                            if ($sourcesData['name'] != "") {
                                $sourcesName = $sourcesData['name'];
                                $sourcesDescription = $sourcesData['type']['category']['description'];
                            }
                        }
                    }
                    $namesArray = $resultArray['names'];
                    //Biography
                    $detailsArray = $resultArray['details'];
                    //address
                    $corpAddress = $resultArray['addresses'];
                    $corpAssociates = isset($resultArray['associates']) ? $resultArray['associates'] : [];
                    
                    $corpmainAddress = "";
                    if (count($corpAddress) > 0) {
                        foreach ($corpAddress as $corpAdd) {
                            $street = "";
                            $region = "";
                            $postCode = "";
                            $city = "";
                            $country = "";
                            if ($corpAdd['street']) {
                                $street = $corpAdd['street'] . ", ";
                            }
                            if ($corpAdd['region']) {
                                $region = $corpAdd['region'] . ", ";
                            }
                            if ($corpAdd['postCode']) {
                                $postCode = $corpAdd['postCode'] . ", ";
                            }
                            if ($corpAdd['city']) {
                                $city = $corpAdd['city'] . ", ";
                            }
                            if ($corpAdd['country']) {
                                $country = $corpAdd['country']['name'] . ", ";
                            }
                            $corpmainAddress.= $street . $region . $postCode . $city . $country . "<br>";
                        }
                    }
              
                    // PEP Type
                    $pepTypeArray = [];
                    $type = "";
                    $pepcategoryName = "";
                    $pepTypeArray = isset($resultArray['subCategory']) ? $resultArray['subCategory'] : '';
                    //$allType = "";
                    $type = $pepTypeArray;
                    ////Country Events
                    $countryLinksArray = isset($resultArray['countryLinks']) ? $resultArray['countryLinks'] : '';
                    $corpAddressArray = isset($resultArray['addresses']) ? $resultArray['addresses'] : '';

                    $countryRegisterd = "";
                    $countryLocation  = "";
                    if (count($countryLinksArray) > 0) {
                        foreach ($countryLinksArray as $countryLinksData) {
                            if ($countryLinksData['type'] == "REGISTEREDIN") {
                                $countryRegisterd = $countryLinksData['country']['name'];
                            }
                        }
                    }
                    ///Identity
                    $identityDocumentsArray = $resultArray['identityDocuments'];
                    if (count($identityDocumentsArray) > 0) {
                        $stringidentityDocuments = '';
                        foreach ($identityDocumentsArray as $identityDocumentsData) {
                            $identityDocumentsType = $identityDocumentsData['type'];
                            $identityDocumentsNumber = $identityDocumentsData['number'];
                            $identityDocumentsissueDate = $identityDocumentsData['issueDate'];
                            $identityDocumentsexpiryDate = $identityDocumentsData['expiryDate'];
                            $stringidentityDocuments.= "Document Type: " . $identityDocumentsType . "<br>Document Number:" . $identityDocumentsNumber . "<br>Document Issue Date:" . $identityDocumentsissueDate . "<br>Expiry Date:" . $identityDocumentsexpiryDate;
                        }
                    }
                    
                    
                    //echo $GROUPNAME; exit;
                    $registered_country_name = Helpers::getCountryById($rowdata->org_country_id);
                   
                    $clientSubmitionData['referenceId'] = $referenceId;
                    $clientSubmitionData['groupName'] = "";
                    $clientSubmitionData['corp_name'] = $rowdata->org_name;
                    $clientSubmitionData['registration'] = $registered_country_name->country_name; 
                    /////Fetch Data
                    $currentDate = $date->format('d-m-Y H-i');
                    isset($creationDate) ? : $creationDate = '';
                    $creationDateArray = explode("T00:00:00", $creationDate);
                    $modificationDateArray = explode("T00:00:00", $modificationDate);

                    $creationDate       =  Helpers::getDateByFormat($creationDateArray[0],'Y-m-d', 'Y-M-d');
                    $modificationDate   =  Helper::getDateByFormat($modificationDateArray[0],'Y-m-d', 'Y-M-d');

                    $clientSubmitionData['r_name'] = isset($fullName) ? $fullName : '';
                    $clientSubmitionData['r_nameAliases'] = isset($nameArray) ? $nameArray : '';
                    $clientSubmitionData['r_countryRegisterd'] = isset($countryRegisterd) ? $countryRegisterd : '';
                    $clientSubmitionData['corpAddressArray'] = isset($corpAddressArray) ? $corpAddressArray : '';
                    $clientSubmitionData['r_sourcesName'] = isset($sourcesName) ? $sourcesName : '';
                    $clientSubmitionData['r_category'] = isset($category) ? $category : '';
                    $clientSubmitionData['r_position'] = isset($Position) ? $Position : '';
                    $clientSubmitionData['r_creationDate'] = isset($creationDate) ? $creationDate : ''; 
                    $clientSubmitionData['r_updateCategory'] = isset($updateCategory) ? $updateCategory : '';
                    $clientSubmitionData['r_modificationDate'] = isset($modificationDate) ? $modificationDate : '';
                    $clientSubmitionData['r_sourcesArray'] = isset($sourcesArray) ? $sourcesArray : '';
                    $clientSubmitionData['r_bioGraphy'] = isset($bioGraphy) ? $bioGraphy : '';
                    $clientSubmitionData['r_weblinksArray'] = isset($webLinks) ? $webLinks : '';
                    $clientSubmitionData['currentDate'] = isset($currentDate) ? $currentDate : '';
                    $clientSubmitionData['fulladdresses'] = isset($fulladdresses) ? $fulladdresses : '';
                    $clientSubmitionData['pep'] = isset($type) ? $type : '';
                    $clientSubmitionData['detailsArray'] = isset($detailsArray) ? $detailsArray : '';
                    $clientSubmitionData['namesArray'] = isset($namesArray) ? $namesArray : '';
                    $clientSubmitionData['corpAssociates'] = isset($corpAssociates) ? $corpAssociates : '';
                    $clientSubmitionData['corpAssociates'] = isset($corpAssociates) ? $corpAssociates : '';
                    $clientSubmitionData['parent_id'] = isset($parent_id) ? $parent_id : '';



                    isset($fullName)? : $fullName = "NoRecord";
                    $pdf = PDF::loadView('backend.report_corpdownload', $clientSubmitionData);
                    $fileName = $fullName . ".pdf";
                    return $pdf->stream($fileName,array('Attachment'=>0));
                    //return $pdf->download($fileName);
                }
            }
        }
    }



    /*
     * WCAPI FORM
     * @param Request $request
     */

    public function individualSearchCases(Request $request) {
        try {
            $userKycId = $request->get('id');
            $searchfor = $request->get('searchfor');
           
            $userPersonalData   = $this->userRepo->getUseKycPersonalData($request->get('id'));
            $kycCompleteArray = Helpers::getKycDetailsByKycID($userKycId);
            //dd( $userPersonalData);
            $userID = $kycCompleteArray['user_id'];
            $is_by_company = $kycCompleteArray['is_by_company'];


            if($is_by_company == 1){
                $userType = 2;
            } else {
               $userType = 1;
            }

            //echo "==>".$is_by_company; exit;
            
            $caseid     = $request->get('caseid');
            $nodata = '';
            $wc_id  = '';
            if(isset($caseid) ) {
              $dataArray =  $this->wcapi->getdataByCaseid($caseid);

              
              //$wcId      = $dataArray->wcapi_req_res_id;
              if($dataArray) {
                  $userKycId = ($dataArray->user_kyc_id) ? $dataArray->user_kyc_id : $userKycId;
                  $wc_id  = $dataArray->wcapi_req_res_id;
                  $nodata = '';
                  Session::flash('is_accept', 1);
              } else {
                  $nodata = "No Search Cases Available.";
              }
               
            }
            //echo $wc_id

            return view('backend.individual_searchcase')
                        ->with(['userPersonalData' => $userPersonalData,
                            'userKycId' => $userKycId,
                            'userID' => $userID,
                            'userType' => $userType,
                            'is_by_company' => $is_by_company,
                            'nodata' => $nodata,
                            'searchfor' => $searchfor,'wc_id' => $wc_id]);
        } catch (Exception $exc) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($exc))->withInput();
        }
    }

    /*
     * WCAPI FORM 
     * @param Request $request
     */

    public function individualApi(Request $request) {
        try {

            $userPersonalData   = $this->userRepo->getUseKycPersonalData($request->get('id'));
            $passportNumber     = $request->get('passportNumber');
            $userPassportExp    = $request->get('userPassportExp');

            //dd( $userPersonalData);
            return view('backend.individual_wcapi')
                        ->with(['userPersonalData' => $userPersonalData,'passportNumber' => $passportNumber,'userPassportExp' => $userPassportExp]);
        } catch (Exception $exc) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($exc))->withInput();
        }
    }

    public function viewUserDetailSimilar(Request $request) {

        try {
            $user_id    = (int) $request->get('user_id');
            $user_type  = (int) $request->get('user_type');
            $userKycId  = (int) $request->get('user_kyc_id');
            $is_by_company = (int) $request->get('is_by_company');
            $wc_id      =  $request->get('wc_id');
           // echo "==>".$is_by_company;exit;
            
            //echo "===>".$userKycId;
            $benifinary = [];
            if ($is_by_company == '1') {
                $corp_user_kyc_id = (int) $request->get('corp_user_kyc_id');
                $corp_user_id = $user_id;
                $user_id = 0;
                $benifinary['user_id'] = $user_id;
                $benifinary['corp_user_id'] = $corp_user_id;
                $benifinary['user_type'] = $user_type;
                $benifinary['userKycId'] = $userKycId;
                $benifinary['corp_user_kyc_id'] = $corp_user_kyc_id;
                $benifinary['is_by_company'] = $is_by_company;
            } else {
                $corp_user_id = 0;
                $corp_user_kyc_id = 0;
                $benifinary['user_id'] = $user_id;
                $benifinary['corp_user_id'] = $corp_user_id;
                $benifinary['user_type'] = $user_type;
                $benifinary['userKycId'] = $userKycId;
                $benifinary['corp_user_kyc_id'] = $corp_user_kyc_id;
                $benifinary['is_by_company'] = $is_by_company;
            }
            if ($userKycId == '' && $userKycId == 0 && $userKycId == null) {
                $userKycId = $this->application->getUserKycid($user_id);
            }

            if($wc_id=='') {
                $kycCompleteArray   = Helpers::getKycDetailsByKycID($userKycId);
            } else {
                $kycCompleteArray   = Helpers::getkycdataById($wc_id);
            }

           


           // $is_byCompany = $kycCompleteArray->is_by_company;
           /* if($is_by_company == 1 && $corp_user_kyc_id == 0){
                $corp_user_id = $kycCompleteArray->user_id;
                $corp_user_kyc_id = $this->application->getUserKycid($corp_user_id);
                $user_id = 0;
                $benifinary['user_id'] = $user_id;
                $benifinary['corp_user_id'] = $corp_user_id;
                $benifinary['user_type'] = 1;
                $benifinary['userKycId'] = $userKycId;
                $benifinary['corp_user_kyc_id'] = $corp_user_kyc_id;
                $benifinary['is_by_company'] = $is_by_company;
               
            }*/
            $arrAssesmentRating = [];
            $rank_decision = '';
            //echo "==>".$user_id; exit;
            if ($user_id > 0) {
                $userData = $this->userRepo->getUserDetail($user_id);
            } else {
                $userData = false;
            }
            $userPersonalData = $this->userRepo->getUseKycPersonalData($userKycId);
            //$userScoreData = [];
            $arrCountry = Helpers::getCountryDropDown();
            ///User Kyc Detail
            if($wc_id=='') {
              $kycDetail = $this->wcapi->getWcapiDetail($userKycId, 'screeningRequest');
            } else {
               $kycDetail = $kycCompleteArray;
            }
            
            if($kycDetail['falsecount']==0 || $kycDetail['autoresolved']==0) {
               $this->updatetotalCount($kycDetail);
               $kycDetail = $this->wcapi->getWcapiDetail($userKycId, 'screeningRequest');
            }
            $StatusArray    = Helpers::gettoolkitStatus();
            $RiskArray      = Helpers::gettoolkitRisks();
            $ReasonArray    = Helpers::gettoolkitReasons();

           
            
            return view('backend.user_detail_similar')
                            ->with(['userData' => $userData,
                                'userPersonalData' => $userPersonalData,
                                'userKycId' => $userKycId, 'kycDetail' => $kycDetail,'kycStatus' => $kycCompleteArray,'benifinary' => $benifinary,
                                'StatusArray' => $StatusArray,'RiskArray' => $RiskArray,'ReasonArray' => $ReasonArray]);
        } catch (Exception $exc) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($exc))->withInput();
        }
    }

    public function individualApiApprove(Request $request) {
        try {

            $userKycId = $request->get('id');
            $profileId = $request->get('profileId');
            $responceProfileId = $request->get('responceProfileId');
            $parent_id = $request->get('parent_id');
            $userData = $this->application->getUserIdbyKycid($userKycId);
            $userType = $this->application->getUserType($userData);
            ///////
            //
            $kycCompleteArray = Helpers::getKycDetailsByKycID($userKycId);
            $is_byCompany = $kycCompleteArray->is_by_company;
            if ($is_byCompany == 1) {
                $userType = 1;
            } else {
                $userType = $this->application->getUserType($kycCompleteArray->user_id);
            }
            ////////
            return view('backend.individual_wcapi_action')
                            ->with(['userKycId' => $userKycId])
                            ->with(['profileId' => $profileId])
                            ->with(['responceProfileId' => $responceProfileId])
                            ->with(['userData' => $userData])
                            ->with(['parent_id' => $parent_id])
                            ->with(['userType' => $userType])
                            ->with(['is_by_company' => $is_byCompany]);
        } catch (Exception $exc) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($exc))->withInput();
        }
    }

    public function individualApiAction(Request $request) {

        try {
            
            $userId = \Auth::user()->user_id;
            $user_kyc_id = $request->get('id');
            $profileId = $request->get('profileId');
            $parent_id = (int) $request->get('parent_id');
            

            $responceProfileId = $request->get('responceProfileId');
            $kycCompleteArray = Helpers::getKycDetailsByKycID($user_kyc_id);
            $userData = $kycCompleteArray->user_id;
            $is_byCompany = $kycCompleteArray->is_by_company;
           
            if ($is_byCompany == 1) {
                $userType = 1;
            } else {
                $userType = $this->application->getUserType($userData);
            }


            $dataKycUpdate = [];
            $dataParent     = [];
            $dataKycUpdate['is_api_pulled'] = 1;
            $dataKycUpdate['is_approve'] = 1;
            $dataKycUpdate['updated_by'] = (int) $userId;

            // Update in world check respoonce table
            $dataKycUpdateWc = [];
            $dataKycUpdateWc['is_approved'] = 1;
            $dataKycUpdateWc['is_final'] = 1;
            $dataKycUpdateWc['updated_by'] = (int) $userId;
            Session::flash('is_accept', 1);

            //Update parent
            $dataParent['is_approved']  = 1;
            $dataParent['is_final']     = 1;
            /// Update Report here
           
            $response   = $this->application->updateUserKyc($user_kyc_id, $dataKycUpdate);
            $updatefor = 1;
            $responsewc = $this->wcapi->updateUserKycWC($user_kyc_id, $profileId, $responceProfileId, $dataKycUpdateWc, $updatefor);
            $parentUpdate = $this->wcapi->updatebyId($parent_id, $dataParent);

            if ($userType == 1) {
                $this->updateReport($user_kyc_id, $userType);
            } else {
                $userType2 = 2;
                 $this->updateReport($user_kyc_id, $userType2);
            }

            /// Update Report here
            // echo "===>".$user_kyc_id."==>".$profileId."==>".$responceProfileId; exit;
            return view('backend.individual_wcapi_action')
                            ->with(['userKycId' => $user_kyc_id])
                            ->with(['profileId' => $profileId])
                            ->with(['responceProfileId' => $responceProfileId])
                            ->with(['userData' => $userData])
                            ->with(['parent_id' => $parent_id])
                            ->with(['userType' => $userType])
                            ->with(['is_by_company' => $is_byCompany]);
        } catch (Exception $exc) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($exc))->withInput();
        }
    }

    public function individualApiDisApprove(Request $request) {
        try {
            $userKycId = $request->get('id');
            $profileId = $request->get('profileId');
            $responceProfileId = $request->get('responceProfileId');
            $kycCompleteArray = Helpers::getKycDetailsByKycID($userKycId);
            $userData = $kycCompleteArray->user_id;
            $is_byCompany = $kycCompleteArray->is_by_company;
            if ($is_byCompany == 1) {
                $userType = 1;
            } else {
                $userType = $this->application->getUserType($userData);
            }
            //  echo "<pre>";
            // print_r($userData); exit;
            return view('backend.individual_wcapi_disapprove')
                        ->with(['userKycId' => $userKycId])
                        ->with(['profileId' => $profileId])
                        ->with(['responceProfileId' => $responceProfileId])
                        ->with(['userData' => $userData])
                        ->with(['userType' => $userType])
                        ->with(['is_by_company' => $is_byCompany]);
        } catch (Exception $exc) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($exc))->withInput();
        }
    }

    public function individualApiActionD(Request $request) {
        try {
            $userId = \Auth::user()->user_id;
            $user_kyc_id = $request->get('id');
            $profileId = $request->get('profileId');
            $parent_id= (int)$request->get('parent_id');
            $responceProfileId = $request->get('responceProfileId');
            $kycCompleteArray = Helpers::getKycDetailsByKycID($user_kyc_id);
            $userData = $kycCompleteArray->user_id;
            $is_byCompany = $kycCompleteArray->is_by_company;
            if ($is_byCompany == 1) {
                $userType = 1;
            } else {
                $userType = $this->application->getUserType($userData);
            }
            $userId = \Auth::user()->user_id;
            $dataKycUpdate = [];
            $dataKycUpdate['is_api_pulled'] = 1;
            $dataKycUpdate['is_approve']    = 2;
            $dataKycUpdate['updated_by']    = (int) $userId;

            // Update in world check respoonce table
            $dataKycUpdateWc = [];
            $dataKycUpdateWc['is_approved'] = 2;
            $dataKycUpdateWc['is_final']    = 1;
            $dataKycUpdateWc['updated_by']  = (int) $userId;
            Session::flash('is_accept', 1);

            $response = $this->application->updateUserKyc($user_kyc_id, $dataKycUpdate);
            $updatefor = 1;
            $responsewc = $this->wcapi->updateUserKycWC($user_kyc_id, $profileId, $responceProfileId, $dataKycUpdateWc, $updatefor);


            /// Update Report here
            if ($userType == 1) {
                $this->updateReport($user_kyc_id, $userType);
            } else {
                $userType2 = 2;
                 $this->updateReport($user_kyc_id, $userType2);
            }
            /// Update Report here

            
            return view('backend.individual_wcapi_action')
                        ->with(['userKycId' => $user_kyc_id])
                        ->with(['profileId' => $profileId])
                        ->with(['responceProfileId' => $responceProfileId])
                        ->with(['userData' => $userData])
                        ->with(['parent_id' => $parent_id])
                        ->with(['userType' => $userType])
                        ->with(['is_by_company' => $is_byCompany]);
        } catch (Exception $exc) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($exc))->withInput();
        }
    }


    //////// Resolution Section for individual/////////

    public function individualApiresolve(Request $request) {
        try {
            $case_id        = $request->get('case_id');
            $result_id      = $request->get('result_id');
            $caseSystemId   = $request->get('caseSystemId');
            $userKycId      = $request->get('userKycId');
            $wcapi_req_res_id   = $request->get('primaryId');
            $profileId      = $request->get('profileId');
            $responceProfileId   = $request->get('responceProfileId');
           //echo "<pre>";
           // print_r($_POST);
            //exit;

          //echo "parent id:->".$wcapi_req_res_id."profile id==>".$profileId."responceprofileid==>".$responceProfileId;
           // echo "Case id:->".$case_id."==".$result_id."==kyc_id==>".$wc_primaryId;
            $kycCompleteArray = Helpers::getKycDetailsByKycID($userKycId);
            ///get toolkit values
            $StatusArray    = Helpers::gettoolkitStatus();
            $RiskArray      = Helpers::gettoolkitRisks();
            $ReasonArray    = Helpers::gettoolkitReasons();
           //dd($StatusArray,$RiskArray,$ReasonArray);
            $userData = $kycCompleteArray->user_id;
            $is_byCompany = $kycCompleteArray->is_by_company;
            if ($is_byCompany == 1) {
                $userType = 1;
            } else {
                $userType = $this->application->getUserType($userData);
            }
            //  echo "<pre>";
            // print_r($userData); exit;
            $is_save = $request->get('is_save');
            if($is_save) {
               
                $resolutionStatus           = $request->get('resolutionStatus');
                $resolutionRisk             = $request->get('resolutionRisk');
                $resolutionReason           = $request->get('resolutionReason');
                $resolutionReasoncomment    = $request->get('resolutionReasoncomment');
                $case_id                     = $request->get('case_id');
                $case_system_id             = $request->get('case_system_id');
                $result_id                  = $request->get('result_id');
                $result_id = $result_id.",";

                
                $result_id1 = trim($result_id,',');
                $result_id_array = explode(',',$result_id);
                foreach($result_id_array as $key => $value) {
                    if (empty($value)) {
                           unset($result_id_array[$key]);
                       } else {
                          $resolvedDataArray = $this->application->getDatabycaseIDandresulid($case_id, $value);
                          if(count($resolvedDataArray) > 0) {
                          $makeArray = explode(',',$resolvedDataArray[0]->result_id);

                          if(count($makeArray) > 2) {
                            //$resolvedDataArray = $this->application->getDatabycaseIDandresulid($case_id, $value);
                            $stored_result_id = $resolvedDataArray[0]->result_id;
                            $resolution_id = $resolvedDataArray[0]->resolution_id;
                            $searchstring = $value.",";
                            $new_result_id = str_replace($searchstring,'',$stored_result_id);
                            $updateDataArray['result_id'] =$new_result_id;
                          
                            $id = $this->application->updateResolution($updateDataArray, $resolution_id);

                          } else if(count($resolvedDataArray) > 0 ) {
                                $resolution_id = $resolvedDataArray[0]->resolution_id;
                                $updateDataArray['is_status'] = 0;
                                $id = $this->application->updateResolution($updateDataArray, $resolution_id);
                            }
                          }

                       }
               }
                 /////update

                $userId = \Auth::user()->user_id;
                $dataKycUpdate = [];
                $dataKycUpdate['is_api_pulled'] = 1;
                $dataKycUpdate['is_approve']    = 1;
                $dataKycUpdate['updated_by']    = (int) $userId;

                // Update in world check respoonce table
                $dataKycUpdateWc = [];
                $dataKycUpdateWc['is_approved'] = 1;
                $dataKycUpdateWc['is_final']    = 1;
                $dataKycUpdateWc['updated_by']  = (int) $userId;
                Session::flash('is_accept', 1);

               //echo "profileID==>".$profileId."==>".$wcapi_req_res_id."==>".$userKycId; exit;

                $response = $this->application->updateUserKyc($userKycId, $dataKycUpdate);
                $updatefor = 1;
                $responsewc = $this->wcapi->updateUserKycWC($userKycId, $profileId, $responceProfileId, $dataKycUpdateWc, $updatefor);



                
               /// Update here Ends
                //get resolution value key by id
                $statusKey = $this->application->toolketvaluebyId($resolutionStatus);
                $riskKey   = $this->application->toolketvaluebyId($resolutionRisk);
                $reasonKey = $this->application->toolketvaluebyId($resolutionReason);

                $dataArray['wcapi_req_res_id']  = $responceProfileId;
                $dataArray['wcapi_parent_id']   = $wcapi_req_res_id;
                $dataArray['case_id']            = $case_id;
                $dataArray['case_system_id']    = $case_system_id;
                $dataArray['result_id']         = $result_id;
                $dataArray['status_mark']       = $resolutionStatus;
                $dataArray['risk_level']        = $resolutionRisk;
                $dataArray['reson']             = $resolutionReason;
                $dataArray['from_resolve']      = 1;
                $dataArray['is_status']         = 1;
                $dataArray['reson_desp']        = $resolutionReasoncomment;
                $dataArray['created_by']        = \Auth::user()->user_id;
                $dataArray['updated_by']        = \Auth::user()->user_id;

              // echo "<pre>";
              // print_r($dataArray);
              // exit;
                $this->application->saveWcapiResolution($dataArray);
                //get user type
                 $kycCompleteArray = Helpers::getKycDetailsByKycID($userKycId);
                   $userData = $kycCompleteArray->user_id;
                   $is_byCompany = $kycCompleteArray->is_by_company;
                   if ($is_byCompany == 1) {
                        $userType = 1;
                   } else {
                        $userType = $this->application->getUserType($userData);
                   }

                $this->updateReport($userKycId, $userType);
                $arrStatus['kyc_id'] = $userKycId;
                $arrStatus['status_id'] = 2;
                $this->application->createstatus($arrStatus);
               
                Session::flash('message', trans('success_messages.other_documents_request_succss'));
                Session::flash('is_accept', 1);
                ///////
            }


            return view('backend.individual_wcapi_resolve')
                        ->with(['userKycId' => $userKycId])
                        ->with(['profileId' => $profileId])
                        ->with(['responceProfileId' => $responceProfileId])
                        ->with(['userData' => $userData])
                        ->with(['userType' => $userType])
                        ->with(['is_by_company' => $is_byCompany])
                        ->with(['StatusArray' => $StatusArray])
                        ->with(['RiskArray' => $RiskArray])
                        ->with(['ReasonArray' => $ReasonArray,'case_id' => $case_id,'result_id' => $result_id,'caseSystemId' => $caseSystemId,'wcapi_req_res_id' => $wcapi_req_res_id]);
        } catch (Exception $exc) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($exc))->withInput();
        }
    }


    public function individualApiresolveAll(Request $request) {
        try {
            $case_id        = $request->get('case_id');
            $result_id      = $request->get('result_id');
            $caseSystemId   = $request->get('caseSystemId');
            $userKycId      = $request->get('userKycId');
            $wcapi_req_res_id   = $request->get('primaryId');
           // $profileId      = $request->get('profileId');
           // $responceProfileId   = $request->get('responceProfileId');
        

          //echo "parent id:->".$wcapi_req_res_id."profile id==>".$profileId."responceprofileid==>".$responceProfileId;
           // echo "Case id:->".$case_id."==".$result_id."==kyc_id==>".$wc_primaryId;
            $kycCompleteArray = Helpers::getKycDetailsByKycID($userKycId);
            ///get toolkit values
            $StatusArray    = Helpers::gettoolkitStatus();
            $RiskArray      = Helpers::gettoolkitRisks();
            $ReasonArray    = Helpers::gettoolkitReasons();
           //dd($StatusArray,$RiskArray,$ReasonArray);
            $userData = $kycCompleteArray->user_id;
            $is_byCompany = $kycCompleteArray->is_by_company;
            if ($is_byCompany == 1) {
                $userType = 1;
            } else {
                $userType = $this->application->getUserType($userData);
            }
            //  echo "<pre>";
            // print_r($userData); exit;
            $is_save = $request->get('is_save');
            if($is_save) {



                


                $resolutionStatus           = $request->get('resolutionStatus');
                $resolutionRisk             = $request->get('resolutionRisk');
                $resolutionReason           = $request->get('resolutionReason');
                $resolutionReasoncomment    = $request->get('resolutionReasoncomment');
                $case_id                     = $request->get('case_id');
                $case_system_id             = $request->get('case_system_id');
                $result_id                  = $request->get('result_id');
               


                $result_id1 = trim($result_id,',');
                $result_id_array = explode(',',$result_id);
                foreach($result_id_array as $key => $value) {
                    if (empty($value)) {
                           unset($result_id_array[$key]);
                       } else {
                          $resolvedDataArray = $this->application->getDatabycaseIDandresulid($case_id, $value);
                          if(count($resolvedDataArray) > 0) {
                          $makeArray = explode(',',$resolvedDataArray[0]->result_id);

                          

                          if(count($makeArray) > 2) {
                            //$resolvedDataArray = $this->application->getDatabycaseIDandresulid($case_id, $value);
                            $stored_result_id = $resolvedDataArray[0]->result_id;
                            $resolution_id = $resolvedDataArray[0]->resolution_id;
                            $searchstring = $value.",";
                            $new_result_id = str_replace($searchstring,'',$stored_result_id);
                            $updateDataArray['result_id'] =$new_result_id;
                           // echo "<pre>";
                            //print_r($updateDataArray);
                            //echo $resolution_id;

                            $id = $this->application->updateResolution($updateDataArray, $resolution_id);
                            
                          } else if(count($resolvedDataArray) > 0 && count($makeArray) <= 2) {
                                $resolution_id = $resolvedDataArray[0]->resolution_id;
                                $updateDataArray['is_status'] = 0;
                                $id = $this->application->updateResolution($updateDataArray, $resolution_id);
                            }
                          }
               
                       }
               }

               // echo $resolutionStatus."==>".$resolutionRisk."==>".$resolutionReason."==>".$resolutionReasoncomment;
               // exit;
                ///update here Start

                 /////update

                $userId = \Auth::user()->user_id;
                $dataKycUpdate = [];
                $dataKycUpdate['is_api_pulled'] = 1;
                $dataKycUpdate['is_approve']    = 1;
                $dataKycUpdate['updated_by']    = (int) $userId;

                // Update in world check respoonce table
                $dataKycUpdateWc = [];
                $dataKycUpdateWc['is_approved'] = 1;
                $dataKycUpdateWc['is_final']    = 1;
                $dataKycUpdateWc['updated_by']  = (int) $userId;
                Session::flash('is_accept', 1);

               //echo "profileID==>".$profileId."==>".$wcapi_req_res_id."==>".$userKycId; exit;
                $response   = $this->application->updateUserKyc($userKycId, $dataKycUpdate);
                $updatefor = 2;
                $profileId = '';
                $responsewc = $this->wcapi->updateUserKycWC($userKycId, $profileId, $wcapi_req_res_id, $dataKycUpdateWc, $updatefor);

               /// Update here Ends
                //get resolution value key by id
                $statusKey = $this->application->toolketvaluebyId($resolutionStatus);
                $riskKey   = $this->application->toolketvaluebyId($resolutionRisk);
                $reasonKey = $this->application->toolketvaluebyId($resolutionReason);

                $dataArray['wcapi_req_res_id']  = $wcapi_req_res_id;
                $dataArray['wcapi_parent_id']   = $wcapi_req_res_id;
                $dataArray['case_id']            = $case_id;
                $dataArray['case_system_id']    = $case_system_id;
                $dataArray['result_id']         = $result_id;
                $dataArray['status_mark']       = $resolutionStatus;
                $dataArray['risk_level']        = $resolutionRisk;
                $dataArray['reson']             = $resolutionReason;
                $dataArray['from_resolve']      = 2;
                $dataArray['is_status']         = 1;
                $dataArray['reson_desp']        = $resolutionReasoncomment;
                $dataArray['created_by']        = \Auth::user()->user_id;
                $dataArray['updated_by']        = \Auth::user()->user_id;

                $this->application->saveWcapiResolution($dataArray);
              
                //get user type
                 $kycCompleteArray = Helpers::getKycDetailsByKycID($userKycId);
                   $userData = $kycCompleteArray->user_id;
                   $is_byCompany = $kycCompleteArray->is_by_company;
                   if ($is_byCompany == 1) {
                        $userType = 1;
                   } else {
                        $userType = $this->application->getUserType($userData);
                   }

               // $this->updateReport($userKycId, $userType);
                $resolutionDatas = Helpers::getDatabycaseIDandstatus($case_id, $resolutionStatus);
                if(count($resolutionDatas) > 0) {
                } else {
                     $this->updateReportAll($userKycId, $userType, $wcapi_req_res_id);
                }
                
                $arrStatus['kyc_id'] = $userKycId;
                $arrStatus['status_id'] = 2;
                $this->application->createstatus($arrStatus);

                Session::flash('message', trans('Update Successfully'));
                
                if($userType == 1) {
                    $route=route("user_detail_similar",['user_id' => $userData,'user_type' => $userType,'user_kyc_id'=>$userKycId,'is_by_company' => $is_byCompany,'tab' => 'tab06']);
                } else if($userType == 2){
                    $route=route("corp_detail_similar",['user_id' => $userData,'user_type' => $userType,'user_kyc_id'=>$userKycId,'is_by_company' => $is_byCompany,'tab' => 'tab06']);
                }
               

                return redirect($route);
                ///////
            }
        } catch (Exception $exc) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($exc))->withInput();
        }
    }

  //// Resoluton Section End for individual /////////
    /*
     * WCAPI Corp FORM
     * @param Request $request
     */

    public function corpApi(Request $request) {

        try {
            //echo "==>". $request->get('id'); exit;
            $userKycId = $request->get('id');
            $companyProfile = $this->application->getCompanyProfileData($userKycId);
            $companyAddress = $this->application->getOnlyCorpAddress($userKycId);
            if($companyProfile) {
               $companyName    =   $companyProfile->company_name;
               $companyCountry = @$companyAddress->country_id;
            } else {
                $companyName = '';
                $companyCountry = '';
            }
            
            


            //$userPersonalData   =   $this->userRepo->getUseKycPersonalData($request->get('id'));
            //dd( $userPersonalData);
            return view('backend.corp_wcapi')
                            ->with(['companyName' => $companyName, 'companyCountry' => $companyCountry, 'companyProfile' => $companyProfile, 'userKycId' => $userKycId]);
        } catch (Exception $exc) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($exc))->withInput();
        }
    }

    public function getPassportNumber($documentsData, $type) {
        $passportNumber     = "";
        $passportexpNumber  = "";
        $idcardNumber       = "";
        $ExpireDate         = "";

        foreach ($documentsData as $Array) {
           
            if ($Array->doc_id == 18 && $type == 'passport') {
                $passportNumber = $Array->document_number;
                $passportexpNumber = $Array->expire_date;
                return $passportNumber."#".$passportexpNumber;
            }

            if ($Array->doc_id == 17 && $type == 'idcard') {
                $idcardNumber = $Array->document_number;
                $ExpireDate = $Array->expire_date;
                return $idcardNumber."#".$ExpireDate;
            }
        }
        
    }

    public function updateReport($userKycId, $userType) {


        try{
             // echo "debug"; exit;
            $user_id = \Auth::user()->user_id;
            $documentsData = $this->userRepo->getDocumentTypeInfo($userKycId);
           //echo "==>".$userType; exit;
           // echo "==>".$userType;
            if($userType == 1) {
                $kycpersonalDetail = $this->wcapi->getWcapiPersonalDetailFinal((int) $userKycId, 'profile');
                $userPersonalData = $this->userRepo->getUseKycPersonalData($userKycId);
                $userCountryID = $userPersonalData->f_nationality_id;
            
            } else {
               $kycpersonalDetail   = $this->wcapi->getWcapiPersonalDetailFinal((int) $userKycId, 'corp-profile');
              // $userPersonalData    = $this->userRepo->getUseKycPersonalData($userKycId);
              
                $companyAddress      = $this->application->getOnlyCorpAddress($userKycId);
                if($companyAddress) {
                    $userCountryID       = $companyAddress->country_id;
                } else {
                    $kycCompleteArray = Helpers::getKycDetailsByKycID($userKycId);
                    $userId = $kycCompleteArray['user_id'];
                    $userCheckArr = $this->userRepo->getfullUserDetail($userId);
                    $userCountryID = $userCheckArr->country_code;
                }
            }
           
           
            //get resolution values
            $wcId = $kycpersonalDetail->parent_id;
            //echo "==>".$wcId;
            
            $resolutionDataArray = $this->application->getResolutionDatabyparentID($wcId);
           // echo "<pre>";
            //print_r($resolutionDataArray);
           //  exit;
            $status = '';
            $risk = '';
            $reson = '';
            $reson_desp ='';
            $matchresult = '';

             if($resolutionDataArray['status_mark']!=''){
                $status = $resolutionDataArray['status_mark'];
                $risk   = $resolutionDataArray['risk_level'];
                $reson  = $resolutionDataArray['reson'];
                $reson_desp  = $resolutionDataArray['reson_desp'];
                $matchresult   = $resolutionDataArray['result_id'];
               // echo $statusName."==>".$riskName."===>".$resonName;
            } else {
                $status =5;
            }

            $arrCountry = Helpers::getCountryDropDown();
            $userCountry = $arrCountry[$userCountryID];

            if ($userCountry == "United States") {
                $userCountry = "United States of America (USA)";
            }

            $kycCompleteArray = Helpers::getKycDetailsByKycID($userKycId);
            $isKycComplete = isset($kycCompleteArray) ? $kycCompleteArray['is_kyc_completed'] : 0;
            $isKycApprove = isset($kycCompleteArray) ? $kycCompleteArray['is_approve'] : 0;
            $isReportGenerated = isset($kycCompleteArray) ? $kycCompleteArray['is_report_generated'] : 0;

            $userTypeCountryArray = $this->wcapi->getCountryTypeByName($userCountry);
            if($userTypeCountryArray){
                $userCountryType = $userTypeCountryArray->country_score;
            }else{
                $userCountryType='';
            }


            if ($documentsData) {
                $userPassportNumber = $this->getPassportNumber($documentsData,'passport');
            } else {
                $userPassportNumber = '';
            }

          
            
            if ($kycpersonalDetail) {
                $wcMatch = $kycpersonalDetail->match_strenght;

                //echo "==>".$wcMatch; //exit;
                $wcpep = $kycpersonalDetail->pep;
                $wcPassport = $kycpersonalDetail->passport;
                //$userPassportNumber = $documentsData->
            } else {
                $wcMatch = '';
                $wcpep = '';
                $wcPassport = '';
            }


          


            $assesment = $this->userRepo->getRiskAssesmentRating($userKycId);
            if ($assesment && $assesment->count()) {
                $updateRiskAssesmentDetail['is_active'] = 0;
                $this->userRepo->updateRiskAssesmentDetail($updateRiskAssesmentDetail, $userKycId);
                $saveAssesment['is_active'] = 0;
                //$this->userRepo->saveAssesmentData($saveAssesment, $assesment[0]->id);
                $this->userRepo->updateRiskAssesmentRank($saveAssesment, $userKycId);
            }

            if($userType == 1) {
                $total_rank = 0;
                for ($i = 0; $i < 5; $i++) {
                    if ($i == 0) {
                        $rank = $userCountryType;
                    } else {
                        $rank = 0;
                    }

                    if ($i == 1) {
                            if ($status == "1") {
                               $rank = 5;
                            }
                            else if($status == "2"){
                                 $rank = 4;
                            } else if($status == "4") {
                                 $rank = 3;
                            } else if($status == "3") {
                                 $rank = 2;
                            }  else if($status == "5") {
                               $rank = 1;
                            }
                        } 
                    
                    if ($i == 2) {
                        $rank = 0; // For passport
                    }
                    if ($i == 3) {
                       // if ($wcpep!= "" && ) {
                        if ($status == "1" ) {
                            $rank = 5;
                        } else {
                            $rank = 1;
                        }
                    }


                    $saveData2 = [];
                    $kname = $i + 1;

                    $saveData2['user_kyc_id'] = $userKycId;
                    $saveData2['assesment_id'] = $kname;
                    $saveData2['rank'] = $rank;
                    $saveData2['is_active'] = 1;
                    $saveData2['created_by'] = $user_id;
                    $saveData2['updated_by'] = $user_id;
                    $saveData2['created_at'] = date('Y-m-d H:i:s');
                    $saveData2['updated_at'] = date('Y-m-d H:i:s');
                    $saveData[$i] = $saveData2;
                    $total_rank = $total_rank + (int) $rank;
                    //$i++;
                }


            } else {
                $total_rank = 0;
                for ($i = 0; $i < 4; $i++) {
                    if ($i == 0) {
                        $rank = $userCountryType;
                    } else {
                        $rank = 0;
                    }

                   if ($i == 1) {
                            if ($status == "1") {
                               $rank = 5;
                            }
                            else if($status == "2"){
                                 $rank = 4;
                            } else if($status == "4") {
                                 $rank = 3;
                            } else if($status == "3") {
                                 $rank = 2;
                            }  else if($status == "5") {
                               $rank = 1;
                            }
                        } 
                    //echo "matchrank==>".$rank;
                    if ($i == 2) {
                        // if ($wcpep!= "") {
                         if ($status == "1") {
                            $rank = 5;
                        } else {
                            $rank = 1;
                        }
                    }



                    $saveData2 = [];
                    $kname = $i + 1;

                    $saveData2['user_kyc_id'] = $userKycId;
                    $saveData2['assesment_id'] = $kname;
                    $saveData2['rank'] = $rank;
                    $saveData2['is_active'] = 1;
                    $saveData2['created_by'] = $user_id;
                    $saveData2['updated_by'] = $user_id;
                    $saveData2['created_at'] = date('Y-m-d H:i:s');
                    $saveData2['updated_at'] = date('Y-m-d H:i:s');
                    $saveData[$i] = $saveData2;
                    $total_rank = $total_rank + (int) $rank;
                    //$i++;
                }
            }

          // echo "<pre>";
           //print_r($saveData); exit;
          // exit;
           
            $this->userRepo->saveBatchAssesmentDetailData($saveData);
            $org_avg_rank = $total_rank / 5;
            $avg_rank = round($org_avg_rank);
            $saveAssesment = [];
            $saveAssesment['user_kyc_id'] = $userKycId;
            $saveAssesment['avg_rank'] = $avg_rank;
            $saveAssesment['org_avg_rank'] = $org_avg_rank;
            $saveAssesment['is_active'] = 1;
            $saveAssesment['created_by'] = $user_id;
            $saveAssesment['updated_by'] = $user_id;
            $saveAssesment['created_at'] = date('Y-m-d H:i:s');
            $saveAssesment['updated_at'] = date('Y-m-d H:i:s');
            // check assesment exist
            $id = null;
          //  $this->userRepo->saveAssesmentData($saveAssesment, $id);
        } catch (Exception $exc) {
            echo $exc; exit;
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($exc))->withInput();
        }
    }




    public function updateReportAll($userKycId, $userType, $wcId) {


        try{
             // echo "debug"; exit;
            $user_id = \Auth::user()->user_id;
            $documentsData = $this->userRepo->getDocumentTypeInfo($userKycId);
           //echo "==>".$userType; exit;
           // echo "==>".$userType;
            if($userType == 1) {
                $userPersonalData = $this->userRepo->getUseKycPersonalData($userKycId);
                $userCountryID = $userPersonalData->f_nationality_id;

            } else {
                $companyAddress      = $this->application->getOnlyCorpAddress($userKycId);
                if($companyAddress) {
                    $userCountryID       = $companyAddress->country_id;
                } else {
                    $kycCompleteArray = Helpers::getKycDetailsByKycID($userKycId);
                    $userId = $kycCompleteArray['user_id'];
                    $userCheckArr = $this->userRepo->getfullUserDetail($userId);
                    $userCountryID = $userCheckArr->country_code;
                }
            }

            $resolutionDataArray = $this->application->getResolutionDatabyparentID($wcId);
            $status = '';
            $risk   = '';
            $reson  = '';
            $reson_desp ='';
            $matchresult = '';

             if($resolutionDataArray['status_mark']!=''){
                $status = $resolutionDataArray['status_mark'];
                $risk   = $resolutionDataArray['risk_level'];
                $reson  = $resolutionDataArray['reson'];
                $reson_desp  = $resolutionDataArray['reson_desp'];
                $matchresult   = $resolutionDataArray['result_id'];
               // echo $statusName."==>".$riskName."===>".$resonName;
            } else {
                $status =5;
            }

            $arrCountry = Helpers::getCountryDropDown();
            $userCountry = $arrCountry[$userCountryID];

            if ($userCountry == "United States") {
                $userCountry = "United States of America (USA)";
            }

            $kycCompleteArray = Helpers::getKycDetailsByKycID($userKycId);
            $isKycComplete  = isset($kycCompleteArray) ? $kycCompleteArray['is_kyc_completed'] : 0;
            $isKycApprove   = isset($kycCompleteArray) ? $kycCompleteArray['is_approve'] : 0;
            $isReportGenerated = isset($kycCompleteArray) ? $kycCompleteArray['is_report_generated'] : 0;

            $userTypeCountryArray = $this->wcapi->getCountryTypeByName($userCountry);
            if($userTypeCountryArray){
                $userCountryType = $userTypeCountryArray->country_score;
            }else{
                $userCountryType='';
            }


            if ($documentsData) {
                $userPassportNumber = $this->getPassportNumber($documentsData,'passport');
            } else {
                $userPassportNumber = '';
            }

            $assesment = $this->userRepo->getRiskAssesmentRating($userKycId);
            if ($assesment && $assesment->count()) {
                $updateRiskAssesmentDetail['is_active'] = 0;
                $this->userRepo->updateRiskAssesmentDetail($updateRiskAssesmentDetail, $userKycId);
                $saveAssesment['is_active'] = 0;
                //$this->userRepo->saveAssesmentData($saveAssesment, $assesment[0]->id);
                $this->userRepo->updateRiskAssesmentRank($saveAssesment, $userKycId);
            }

            if($userType == 1) {
                $total_rank = 0;
                for ($i = 0; $i < 5; $i++) {
                    if ($i == 0) {
                        $rank = $userCountryType;
                    } else {
                        $rank = 0;
                    }

                    if ($i == 1) {
                            if ($status == "1") {
                               $rank = 5;
                            }
                            else if($status == "2"){
                                 $rank = 4;
                            } else if($status == "4") {
                                 $rank = 3;
                            } else if($status == "3") {
                                 $rank = 2;
                            }  else if($status == "5") {
                               $rank = 1;
                            }
                        }

                    if ($i == 2) {
                        $rank = 0; // For passport
                    }
                    if ($i == 3) {
                       // if ($wcpep!= "" && ) {
                        if ($status == "1" ) {
                            $rank = 5;
                        } else {
                            $rank = 1;
                        }
                    }


                    $saveData2 = [];
                    $kname = $i + 1;

                    $saveData2['user_kyc_id'] = $userKycId;
                    $saveData2['assesment_id'] = $kname;
                    $saveData2['rank'] = $rank;
                    $saveData2['is_active'] = 1;
                    $saveData2['created_by'] = $user_id;
                    $saveData2['updated_by'] = $user_id;
                    $saveData2['created_at'] = date('Y-m-d H:i:s');
                    $saveData2['updated_at'] = date('Y-m-d H:i:s');
                    $saveData[$i] = $saveData2;
                    $total_rank = $total_rank + (int) $rank;
                    //$i++;
                }


            } else {
                $total_rank = 0;
                for ($i = 0; $i < 4; $i++) {
                    if ($i == 0) {
                        $rank = $userCountryType;
                    } else {
                        $rank = 0;
                    }

                   if ($i == 1) {
                            if ($status == "1") {
                               $rank = 5;
                            }
                            else if($status == "2"){
                                 $rank = 4;
                            } else if($status == "4") {
                                 $rank = 3;
                            } else if($status == "3") {
                                 $rank = 2;
                            }  else if($status == "5") {
                               $rank = 1;
                            }
                        }
                    //echo "matchrank==>".$rank;
                    if ($i == 2) {
                        // if ($wcpep!= "") {
                         if ($status == "1") {
                            $rank = 5;
                        } else {
                            $rank = 1;
                        }
                    }



                    $saveData2 = [];
                    $kname = $i + 1;

                    $saveData2['user_kyc_id'] = $userKycId;
                    $saveData2['assesment_id'] = $kname;
                    $saveData2['rank'] = $rank;
                    $saveData2['is_active'] = 1;
                    $saveData2['created_by'] = $user_id;
                    $saveData2['updated_by'] = $user_id;
                    $saveData2['created_at'] = date('Y-m-d H:i:s');
                    $saveData2['updated_at'] = date('Y-m-d H:i:s');
                    $saveData[$i] = $saveData2;
                    $total_rank = $total_rank + (int) $rank;
                    //$i++;
                }
            }

          // echo "<pre>";
           //print_r($saveData); exit;
          // exit;

            $this->userRepo->saveBatchAssesmentDetailData($saveData);
            $org_avg_rank = $total_rank / 5;
            $avg_rank = round($org_avg_rank);
            $saveAssesment = [];
            $saveAssesment['user_kyc_id'] = $userKycId;
            $saveAssesment['avg_rank'] = $avg_rank;
            $saveAssesment['org_avg_rank'] = $org_avg_rank;
            $saveAssesment['is_active'] = 1;
            $saveAssesment['created_by'] = $user_id;
            $saveAssesment['updated_by'] = $user_id;
            $saveAssesment['created_at'] = date('Y-m-d H:i:s');
            $saveAssesment['updated_at'] = date('Y-m-d H:i:s');
            // check assesment exist
            $id = null;
          //  $this->userRepo->saveAssesmentData($saveAssesment, $id);
        } catch (Exception $exc) {
            echo $exc; exit;
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($exc))->withInput();
        }
    }



    public function updateReportnomatch($userKycId, $userType) {


        try{
            $user_id = \Auth::user()->user_id;
            $documentsData = $this->userRepo->getDocumentTypeInfo($userKycId);
        if($userType == 1) {
                $userPersonalData = $this->userRepo->getUseKycPersonalData($userKycId);
                $userCountryID = $userPersonalData->f_nationality_id;
            } else {
               $companyAddress      = $this->application->getCompanyAddress($userKycId);
               $userCountryID       = $companyAddress->country_id;
            }
            //echo "==>".$userType; exit;
            $arrCountry = Helpers::getCountryDropDown();
            $userCountry = $arrCountry[$userCountryID];
            if ($userCountry == "United States") {
                $userCountry = "United States of America (USA)";
            }

            $kycCompleteArray = Helpers::getKycDetailsByKycID($userKycId);
            $isKycComplete = isset($kycCompleteArray) ? $kycCompleteArray['is_kyc_completed'] : 0;
            $isKycApprove = isset($kycCompleteArray) ? $kycCompleteArray['is_approve'] : 0;
            $isReportGenerated = isset($kycCompleteArray) ? $kycCompleteArray['is_report_generated'] : 0;

            $userTypeCountryArray = $this->wcapi->getCountryTypeByName($userCountry);
            if($userTypeCountryArray){
                $userCountryType = $userTypeCountryArray->country_score;
            }else{
                $userCountryType='';
            }


            if ($documentsData) {
                $userPassportNumber = $this->getPassportNumber($documentsData,'passport');
            } else {
                $userPassportNumber = '';
            }
            
            //echo "==>".$userKycId; exit;
            //update old record as Inactive mode
            //$assesment = $this->userRepo->getAssesmentData($userKycId, 1);
            $assesment = $this->userRepo->getRiskAssesmentRating($userKycId);

           
        
         
            if ($assesment && $assesment->count()) {
                $updateRiskAssesmentDetail['is_active'] = 0;
                $this->userRepo->updateRiskAssesmentDetail($updateRiskAssesmentDetail, $userKycId);
                $saveAssesment['is_active'] = 0;
                //$this->userRepo->saveAssesmentData($saveAssesment, $assesment[0]->id);
                $this->userRepo->updateRiskAssesmentRank($saveAssesment, $userKycId);
            }
            if($userType == 1) {
                $total_rank = 0;
                for ($i = 0; $i < 5; $i++) {
                   
                    if ($i == 0) {
                        $rank = $userCountryType;
                    } else {
                        $rank = 1;
                    }
                   

                    if ($i == 1) {
                        
                            $rank = 1;
                       
                    }
                    if ($i == 2) {
                        
                            $rank = 0;
                        
                    }
                    if ($i == 3) {
                        
                            $rank = 1;
                        
                    }
                    if ($i == 4) {

                            $rank = 0;

                    }


                    $saveData2 = [];
                    $kname = $i + 1;

                    $saveData2['user_kyc_id'] = $userKycId;
                    $saveData2['assesment_id'] = $kname;
                    $saveData2['rank'] = $rank;
                    $saveData2['is_active'] = 1;
                    $saveData2['created_by'] = $user_id;
                    $saveData2['updated_by'] = $user_id;
                    $saveData2['created_at'] = date('Y-m-d H:i:s');
                    $saveData2['updated_at'] = date('Y-m-d H:i:s');
                    $saveData[$i] = $saveData2;
                    $total_rank = $total_rank + (int) $rank;
                    //$i++;
                }

            } else {
                $total_rank = 0;
                for ($i = 0; $i < 4; $i++) {
                    $rank = 0;
                     if ($i == 0) {
                        $rank = $userCountryType;
                    } else {
                        $rank = 1;
                    }
                    if ($i == 1) {
                            $rank = 1;
                    }
                    if ($i == 2) {
                            $rank = 1;
                    }
                    if ($i == 3) {
                            $rank = 0;
                    }


                    $saveData2 = [];
                    $kname = $i + 1;

                    $saveData2['user_kyc_id'] = $userKycId;
                    $saveData2['assesment_id'] = $kname;
                    $saveData2['rank'] = $rank;
                    $saveData2['is_active'] = 1;
                    $saveData2['created_by'] = $user_id;
                    $saveData2['updated_by'] = $user_id;
                    $saveData2['created_at'] = date('Y-m-d H:i:s');
                    $saveData2['updated_at'] = date('Y-m-d H:i:s');
                    $saveData[$i] = $saveData2;
                    $total_rank = $total_rank + (int) $rank;
                    //$i++;
                }
            }

           // echo "<pre>";
           // print_r($saveData);
            //exit;

            $this->userRepo->saveBatchAssesmentDetailData($saveData);
            $org_avg_rank = $total_rank / 5;
            $avg_rank = round($org_avg_rank);
            $saveAssesment = [];
            $saveAssesment['user_kyc_id'] = $userKycId;
            $saveAssesment['avg_rank'] = $avg_rank;
            $saveAssesment['org_avg_rank'] = $org_avg_rank;
            $saveAssesment['is_active'] = 1;
            $saveAssesment['created_by'] = $user_id;
            $saveAssesment['updated_by'] = $user_id;
            $saveAssesment['created_at'] = date('Y-m-d H:i:s');
            $saveAssesment['updated_at'] = date('Y-m-d H:i:s');
            // check assesment exist
            $id = null;
           // $this->userRepo->saveAssesmentData($saveAssesment, $id); //not auto save
        } catch (Exception $exc) {

            echo $exc; exit;
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($exc))->withInput();
        }
    }

    public function sendmailNotification($userKycId, $mailStatus) {
        $mailStatus = "finalapproved";

        $kycCompleteArray = Helpers::getKycDetailsByKycID($userKycId);
        $userId = $kycCompleteArray->user_id;
        $userCheckArr = $this->userRepo->getUserDetail($user_id);
        $userMailArr = [];

        $eventFire = "admin." . $mailStatus;


        if ($userCheckArr->user_type == 2) {
            $userCheckArrData = $this->userRepo->getcorpDetail($userId);
            $userMailArr['name'] = $userCheckArr->f_name . ' ' . $userCheckArr->l_name;
            $userMailArr['email'] = $userCheckArr->email;
            $userMailArr['userType'] = $userCheckArr->user_type;
            $userMailArr['corp_name'] = $userCheckArrData->corp_name;
        } else {
            $userMailArr['name'] = $userCheckArr->f_name . ' ' . $userCheckArr->l_name;
            $userMailArr['email'] = $userCheckArr->email;
            $userMailArr['userType'] = $userCheckArr->user_type;
            $userMailArr['corp_name'] = "";
        }
        if ($userArr['is_admin_approved'] == 1) {
            Event::fire("admin.approved", serialize($userMailArr));
        } else {
            Event::fire("admin.disapproved", serialize($userMailArr));
        }
    }

    public function viewCorpDetailSimilar(Request $request) {

        try {
            $user_id = (int) $request->get('user_id');
            $user_type = (int) $request->get('user_type');
            $userKycId = (int) $request->get('user_kyc_id');
            $is_by_company = (int) $request->get('is_by_company');



            //echo "-->".$userKycId."==>".$user_id; exit;
            $benifinary = [];
            if ($is_by_company == '1') {
                $corp_user_kyc_id = (int) $request->get('corp_user_kyc_id');
                $corp_user_id = $user_id;
                $user_id = 0;
                $benifinary['user_id'] = $user_id;
                $benifinary['corp_user_id'] = $corp_user_id;
                $benifinary['user_type'] = $user_type;
                $benifinary['userKycId'] = $userKycId;
                $benifinary['corp_user_kyc_id'] = $corp_user_kyc_id;
                $benifinary['is_by_company'] = $is_by_company;
            } else {
                $corp_user_id = 0;
                $corp_user_kyc_id = 0;
                $benifinary['user_id'] = $user_id;
                $benifinary['corp_user_id'] = $corp_user_id;
                $benifinary['user_type'] = $user_type;
                $benifinary['userKycId'] = $userKycId;
                $benifinary['corp_user_kyc_id'] = $corp_user_kyc_id;
                $benifinary['is_by_company'] = $is_by_company;
            }
            if ($userKycId == '' && $userKycId == 0 && $userKycId == null) {
                $userKycId = $this->application->getUserKycid($user_id);
            }
            $arrAssesmentRating = [];
            $rank_decision = '';

            //echo "==>".$user_id; exit;
            if ($user_id > 0) {
                $userData = $this->userRepo->getUserDetail($user_id);
            } else {
                $userData = false;
            }



            $userPersonalData = $this->userRepo->getUseKycPersonalData($userKycId);
            $kycCompleteArray = Helpers::getKycDetailsByKycID($userKycId);
            $userId = $kycCompleteArray->user_id;
            $is_byCompany = $kycCompleteArray->is_by_company;
            if ($is_byCompany == 1) {
                $userType = 1;
            } else {
                $userType = $this->application->getUserType($userId);
            }

            $arrCountry = Helpers::getCountryDropDown();
            ///User Kyc Detail
            $kycDetail = $this->wcapi->getWcapiDetail($userKycId, 'screeningRequest-corp');

            if($kycDetail['falsecount']==0 || $kycDetail['autoresolved']==0) {
               $this->updatetotalCount($kycDetail);
               $kycDetail = $this->wcapi->getWcapiDetail($userKycId, 'screeningRequest-corp');
            }

            $StatusArray    = Helpers::gettoolkitStatus();
            $RiskArray      = Helpers::gettoolkitRisks();
            $ReasonArray    = Helpers::gettoolkitReasons();

            return view('backend.corp_detail_similar')
                            ->with(['userData' => $userData,
                                'userPersonalData' => $userPersonalData,
                                'userKycId' => $userKycId, 'kycDetail' => $kycDetail,
                                'userId' => $userId, 'userType' => $userType,
                                'StatusArray' => $StatusArray,'RiskArray' => $RiskArray, 'ReasonArray'=> $ReasonArray]);
        } catch (Exception $exc) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($exc))->withInput();
        }
    }

    // Final Approval and disapproval Action

    public function individualFinalApprove(Request $request) {
        try {


            $userKycId = $request->get('id');
            $profileId = $request->get('profileId');
            $responceProfileId = $request->get('responceProfileId');
            $kycCompleteArray = Helpers::getKycDetailsByKycID($userKycId);
            $userData = $kycCompleteArray->user_id;
            $is_byCompany = $kycCompleteArray->is_by_company;
            if ($is_byCompany == 1) {
                $userType = 1;
            } else {
                $userType = $this->application->getUserType($userData);
            }

            return view('backend.individual_final_action')
                            ->with(['userKycId' => $userKycId])
                            ->with(['profileId' => $profileId])
                            ->with(['responceProfileId' => $responceProfileId])
                            ->with(['userData' => $userData])
                            ->with(['userType' => $userType]);
        } catch (Exception $exc) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($exc))->withInput();
        }
    }

    public function individualFinalAction(Request $request) {

        try {
            
            $userId = \Auth::user()->user_id;

            $user_kyc_id = $request->get('id');
            $profileId = $request->get('profileId');
            $responceProfileId = $request->get('responceProfileId');
            $kycCompleteArray = Helpers::getKycDetailsByKycID($user_kyc_id);
            $userData = $kycCompleteArray->user_id;
            $is_byCompany = $kycCompleteArray->is_by_company;
            $userId=$kycCompleteArray->user_id;

            if ($is_byCompany == 1) {
                $userType = 1;
            } else {
                $userType = $this->application->getUserType($userData);
            }

            $dataKycUpdate = [];
            $dataKycUpdate['is_finalapprove'] = 1;
            $dataKycUpdate['updated_by'] = (int) $userId;
            Session::flash('is_accept', 1);
            /// Update Report here
        
            if ($userType == 1) {
               // $this->updateReport($user_kyc_id, $userType);
            }
            /// Update Report here
            // echo "===>".$user_kyc_id."==>".$profileId."==>".$responceProfileId; exit;
           $response = $this->application->updateUserKyc($user_kyc_id, $dataKycUpdate);
            // Send Email to Users

            $userArr = $this->userRepo->find($userId, ['email', 'first_name', 'last_name', 'user_type','user_source']);
            $UserEmailData = [];
            $UserEmailData['name'] = $userArr->f_name . ' ' . $userArr->l_name;
            $UserEmailData['email'] = $userArr->email;
            $UserEmailData['userType'] = $userArr->user_type;
            $UserEmailData['user_source'] = $userArr->user_source;

            if($userArr->user_type == 2) {
                $userCorp = $this->userRepo->getcorpDetail($userId);
                $UserEmailData['corp_name'] = $userCorp->corp_name;
            }
            Event::dispatch("Admin.Kyc.Approved.Notification", serialize($UserEmailData));
            return view('backend.individual_final_action')
                            ->with(['userKycId' => $user_kyc_id])
                            ->with(['profileId' => $profileId])
                            ->with(['responceProfileId' => $responceProfileId])
                            ->with(['userData' => $userData])
                            ->with(['userType' => $userType]);
        } catch (Exception $exc) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($exc))->withInput();
        }
    }

    public function individualFinalDisApprove(Request $request) {

        try {

            $userKycId = $request->get('id');
            $profileId = $request->get('profileId');
            $responceProfileId = $request->get('responceProfileId');
            $kycCompleteArray = Helpers::getKycDetailsByKycID($userKycId);
            $userData = $kycCompleteArray->user_id;
            $is_byCompany = $kycCompleteArray->is_by_company;
            if ($is_byCompany == 1) {
                $userType = 1;
            } else {
                $userType = $this->application->getUserType($userData);
            }


            //  echo "<pre>";
            // print_r($userData); exit;
            return view('backend.individual_final_disapprove')
                            ->with(['userKycId' => $userKycId])
                            ->with(['profileId' => $profileId])
                            ->with(['responceProfileId' => $responceProfileId])
                            ->with(['userData' => $userData])
                            ->with(['userType' => $userType]);
        } catch (Exception $exc) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($exc))->withInput();
        }
    }

    public function individualFinalActionD(Request $request) {
        try {

            $userId = \Auth::user()->user_id;

            $userKycId = $request->get('id');
            $profileId = $request->get('profileId');
            $responceProfileId = $request->get('responceProfileId');
            $kycCompleteArray = Helpers::getKycDetailsByKycID($userKycId);
            $userData = $kycCompleteArray->user_id;
            $userId=$userData;
            $is_byCompany = $kycCompleteArray->is_by_company;
            if ($is_byCompany == 1) {
                $userType = 1;
            } else {
                $userType = $this->application->getUserType($userData);
            }

            $dataKycUpdate = [];
            $dataKycUpdate['is_finalapprove'] = 2;
            $dataKycUpdate['updated_by'] = (int) $userId;
            Session::flash('is_accept', 1);
            $response = $this->application->updateUserKyc($userKycId, $dataKycUpdate);
            //sent email for decline profile 

            $userArr = $this->userRepo->find($userId, ['email', 'first_name', 'last_name', 'user_type']);
            
            $UserEmailData = [];
            $UserEmailData['name'] = $userArr->f_name . ' ' . $userArr->l_name;
            $UserEmailData['email'] = $userArr->email;
            $UserEmailData['userType'] = $userArr->user_type;
            if($userArr->user_type == 2) {
             $userCorp = $this->userRepo->getcorpDetail($userId);
             $UserEmailData['corp_name'] = $userCorp->corp_name;
            }
           
            Event::dispatch("Admin.Kyc.Decline.Notification", serialize($UserEmailData));




            return view('backend.individual_final_disapprove')
                            ->with(['userKycId' => $userKycId])
                            ->with(['profileId' => $profileId])
                            ->with(['responceProfileId' => $responceProfileId])
                            ->with(['userData' => $userData])
                            ->with(['userType' => $userType]);
        } catch (Exception $exc) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($exc))->withInput();
        }
    }



     public function individualFinalNomatch(Request $request) {

        try {

            $userKycId = $request->get('id');
            $profileId = $request->get('profileId');

            $responceProfileId = $request->get('responceProfileId');
            $kycCompleteArray = Helpers::getKycDetailsByKycID($userKycId);
            $userData = $kycCompleteArray->user_id;
            $is_byCompany = $kycCompleteArray->is_by_company;
            if ($is_byCompany == 1) {
                $userType = 1;
            } else {
                $userType = $this->application->getUserType($userData);
            }


            //  echo "<pre>";
            // print_r($userData); exit;
            return view('backend.individual_final_nomatch')
                            ->with(['userKycId' => $userKycId])
                            ->with(['profileId' => $profileId])
                            ->with(['responceProfileId' => $responceProfileId])
                            ->with(['userData' => $userData])
                            ->with(['userType' => $userType])
                            ->with(['is_by_company' => $is_byCompany]);
        } catch (Exception $exc) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($exc))->withInput();
        }
    }

    public function individualFinalActionNomatch(Request $request) {
        try {

            $userId = \Auth::user()->user_id;
            $nomatch = $request->get('nomatch');
            $userKycId = $request->get('id');
            $profileId = $request->get('profileId');
            $responceProfileId = $request->get('responceProfileId');
            $kycCompleteArray = Helpers::getKycDetailsByKycID($userKycId);
            $userData = $kycCompleteArray->user_id;
            $userId=$userData;
            $is_byCompany = $kycCompleteArray->is_by_company;
            if ($is_byCompany == 1) {
                $userType = 1;
            } else {
                $userType = $this->application->getUserType($userData);
            }
            //echo "==>".$userType; exit;
            $dataKycUpdate = [];
            $dataKycUpdate['is_finalapprove'] = 3;
            $dataKycUpdate['updated_by'] = (int) $userId;
            Session::flash('is_accept', 1);
            $response = $this->application->updateUserKyc($userKycId, $dataKycUpdate);
            //sent email for decline profile
            /// Update Report here
                $this->updateReportnomatch($userKycId, $userType);
            /// Update Report here
            $userArr = $this->userRepo->find($userId, ['email', 'first_name', 'last_name', 'user_type']);
            $UserEmailData = [];
            $UserEmailData['name'] = $userArr->f_name . ' ' . $userArr->l_name;
            $UserEmailData['email'] = $userArr->email;
            $UserEmailData['userType'] = $userArr->user_type;
            if($userArr->user_type == 2) {
             $userCorp = $this->userRepo->getcorpDetail($userId);
             $UserEmailData['corp_name'] = $userCorp->corp_name;
            }
            //Event::dispatch("Admin.Kyc.Decline.Notification", serialize($UserEmailData));
            return view('backend.individual_final_nomatch')
                            ->with(['userKycId' => $userKycId])
                            ->with(['profileId' => $profileId])
                            ->with(['responceProfileId' => $responceProfileId])
                            ->with(['userData' => $userData])
                            ->with(['userType' => $userType])
                            ->with(['is_by_company' => $is_byCompany]);
        } catch (Exception $exc) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($exc))->withInput();
        }
    }





    // Final Approval and Disapproval Action End
    //
    
    public function sendIndvOtherDocReq(Request $request) {
        try {
            $validatedData = $this->validate(
                    $request, ['other_documents' => 'required',], ['other_documents.required' => trans('common.error_messages.req_this_field')]
            );

            $userId = \Auth::user()->user_id;
            $user_id = $request->get('user_id');
            $is_by_company = $request->get('is_by_company');
            $corp_user_id = $request->get('corp_user_id');
            $userKycId = $request->get('userKycId');
            $user_type = $request->get('user_type');
            $corp_user_kyc_id = $request->get('corp_user_kyc_id');
            $other_docsId = $request->get('other_documents');

            $doc_for = '1';
            
            $userArr = $this->userRepo->getfullUserDetail($user_id);//get the user details to send email

            $otherDocs = $this->userRepo->getOtherDocs('1', $other_docsId);

            $inputData = [];
            $i=0;
            $arrDocName=[];
            foreach ($otherDocs as $key => $doc) {
                $data = [];
                $arrSel=[];
                $data['doc_type'] = $doc_for;
                $data['user_kyc_id'] = $userKycId;
                $data['user_id'] = $user_id;
                $data['doc_id'] = $doc->id;
                $data['is_required'] = $doc->is_required;
                $data['upload_doc_name'] = $doc->doc_name;
                $arrDocName[$i]= $doc->doc_name;
                $data['is_upload'] = 0;
                $data['type'] = 2;
                $data['created_by'] = $userId;

                $inputData[$key] = $data;
                $i++;
            }
             
            $res = $this->application->saveOtherDocsReq($inputData);
            $docNmae=(is_array($arrDocName) && count($arrDocName))? implode(',  ', $arrDocName):'';

            //email sent to user
            $UserEmailData = [];
            $UserEmailData['name'] = $userArr->f_name . ' ' . $userArr->l_name;
            $UserEmailData['email'] = $userArr->email;
            $UserEmailData['document_name']= $docNmae;
            
            Event::dispatch("ADMIN.EMAIL.OTHERDOCUMENT", serialize($UserEmailData));
            
            Session::flash('message', trans('success_messages.other_documents_request_succss'));
            $route = route("user_detail", ['user_id' => $user_id, 'user_type' => $user_type, 'user_kyc_id' => $userKycId, 'is_by_company' => $is_by_company]);
            return redirect($route);
        } catch (Exception $exc) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($exc))->withInput();
        }
    }

    public function sendCorpOtherDocReq(Request $request) {
        try {
            $validatedData = $this->validate(
                    $request, ['other_documents' => 'required',], ['other_documents.required' => trans('common.error_messages.req_this_field')]
            );

            $userId = \Auth::user()->user_id;
            $user_id = $request->get('user_id');
            $is_by_company = $request->get('is_by_company');
            $corp_user_id = $request->get('corp_user_id');
            $userKycId = $request->get('userKycId');
            $user_type = $request->get('user_type');
            $corp_user_kyc_id = $request->get('corp_user_kyc_id');
            $other_docsId = $request->get('other_documents');

            $doc_for = '2';
            $userArr = $this->userRepo->getfullUserDetail($user_id);//get the user details to send email
            $otherDocs = $this->userRepo->getOtherDocs($doc_for, $other_docsId);

            $inputData = [];
             $i=0;
            $arrDocName=[];
            foreach ($otherDocs as $key => $doc) {
                $data = [];

                $data['doc_type'] = $doc->doc_for;
                $data['user_kyc_id'] = $userKycId;
                $data['user_id'] = $user_id;
                $data['doc_id'] = $doc->id;
                $data['is_required'] = $doc->is_required;
                $data['upload_doc_name'] = $doc->doc_name;
                $arrDocName[$i]= $doc->doc_name;
                $data['is_upload'] = 0;
                $data['type'] = 2;
                $data['created_by'] = $userId;

                $inputData[$key] = $data;
                $i++;
            }

            $res = $this->application->saveOtherDocsReq($inputData);
            $docNmae=(is_array($arrDocName) && count($arrDocName))? implode(',  ', $arrDocName):'';

            //email sent to user
            $UserEmailData = [];
            $UserEmailData['name'] = $userArr->f_name . ' ' . $userArr->l_name;
            $UserEmailData['email'] = $userArr->email;
            $UserEmailData['document_name']= $docNmae;
            
            Event::dispatch("ADMIN.EMAIL.OTHERDOCUMENT", serialize($UserEmailData));
            
            Session::flash('message', trans('success_messages.other_documents_request_succss'));
            $route = route("corp_user_detail", ['user_id' => $user_id, 'user_type' => $user_type, 'user_kyc_id' => $userKycId, 'is_by_company' => $is_by_company]);
            return redirect($route);
        } catch (Exception $exc) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($exc))->withInput();
        }
    }

    //

    /*
     * get Document document list
     * @param $postArr, $id
     * return int
     */
    public function getAllOtherDocsPaginate(Request $request) {
        $filter=[];
        $filter['type'] =   (int)$request->get('type');
        $filter['doc_for']  =  (int)$request->get('doc_for');
        $filter['doc_name'] = $request->get('doc_name');
        $resData=$this->userRepo->getAllOtherDocsPaginate($filter);  
        return view('backend.master.requested_document.index',compact('resData','filter'));
    }

    /*
     * Save or update Document
     * @param $postArr, $id
     * return int
     */
    public function addOtherDocumentForm() {
        $resData=false;
        return view('backend.master.requested_document.add_edit_document',compact('resData'));
    }
    
    //
    /*
     * Save or update Document
     * @param $postArr, $id
     * return int
     */
    public function editOtherDocumentForm(Request $request) {

        $id =   (int)$request->get('id');
        $resData=$this->userRepo->getDocumentData($id);
       
        return view('backend.master.requested_document.add_edit_document',compact('resData','id'));
    }

    /*
     * Save or update Document
     * @param $postArr, $id
     * return int
     */
    public function saveOrEditDocument(Request $request) {
       
        try {
            $validatedData = $this->validate(
               $request, 
               ['doc_name' => 'required|max:120',
                'doc_for' => 'required', 
                'type' => 'required',
                'is_required'=>'required'],
               ['doc_name.required' => trans('common.error_messages.req_this_field'),
                'doc_name.max' => trans('common.error_messages.max_120_chars'),   
                'doc_for.required' => trans('common.error_messages.req_this_field'),
                'type.required' => trans('common.error_messages.req_this_field'),
                'is_required.required' => trans('common.error_messages.req_this_field')   
               ]
            );
            
            $inputdata = [];
            $id = ($request->get('id') != '' && $request->get('id') != null) ? (int) $request->get('id') : null;
            $userId = \Auth::user()->user_id;
            $inputdata['doc_name'] = ucwords($request->get('doc_name'));
            $inputdata['doc_for'] = (int)$request->get('doc_for');
            $inputdata['type'] = (int)$request->get('type');
            $inputdata['is_required'] = 1;
            $inputdata['is_active'] = 1;
            if($id==null){
                $inputdata['created_by'] = $userId;
                $inputdata['created_at'] = date('Y-m-d H:i:s'); 
            }else{
                $inputdata['updated_by'] = $userId;
                $inputdata['updated_at'] = date('Y-m-d H:i:s');
            }
           
            $otherDocs = $this->userRepo->saveOrEditDocument($inputdata,$id);
           
            if($otherDocs){
                if($id>0){
                    Session::flash('message', trans('success_messages.other_documents_updated'));
                }else{
                    Session::flash('message', trans('success_messages.other_documents_created'));
                }
                return redirect(route('other_document'));
            }else{
               return redirect(route('other_document')); 
            }
        } catch (Exception $exc) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($exc))->withInput();
        }
    }


    public function activeInactiveOtherDocument(Request $request) 
    {

        try{
            $inputdata=[];
            $id= (int) $request->id;

            //1 for Active 0 for Inactive
            if($request->actInct == 1){
              $inputdata['is_active'] = 0;
             } else {
                $inputdata['is_active'] = 1;
             }
            $inputdata['updated_by']= Auth()->user()->user_id;
            $inputdata['updated_at'] = date('Y-m-d H:i:s');
            $status = $this->userRepo->updateActiveInactive($inputdata,$id);
            return redirect(route('other_document'));
            } catch(Exception $exc) {
             return redirect()->back()->withErrors(Helpers::getExceptionMessage($exc))->withInput();
        } 

    }



    //// download Report complete pdf

    public function downloadcorpcompletePDF(Request $request) {
        $data = $request->all();
        $userKycId = $data['userKycId'];
        $user_id = $data['user_id'];
        $user_type = $data['report_type'];

       //echo "--->".$user_id; exit;
        $userType = 2;
            if ($userType == 2) {
                
                $companyProfile = $this->application->getCompanyProfileData($userKycId);
                $companyAddress = $this->application->getCompanyAddress($userKycId);
                $companyFinancial = $this->application->getCompanyFinancialData($userKycId);
                $documentArray = $this->application->corporateDocument($userKycId);
                $complianceReport = $this->userRepo->complianceReport($userKycId);
                $is_report_genrated = $complianceReport->is_report_genrated;
                $compliance_report_id   = $complianceReport->compliance_report_id;
                $socialmediaData = $this->userRepo->getCorpSocialmediaInfo($userKycId);
                
                $beficiyerData = [];
                $beficiyerData = $this->application->getBeneficiaryOwnersData($user_id);
                $kycDetail = $this->wcapi->getWcapiDetail($userKycId, 'screeningRequest-corp');
                //dd($kycDetail);
                $primaryId = isset($kycDetail->wcapi_req_res_id) ? $kycDetail->wcapi_req_res_id : '';
                $arrCountry = Helpers::getCountryDropDown();
                $assesmentData = $this->userRepo->getAssesmentData($userKycId, 1);
                $assesmentOldData = $this->userRepo->getAssesmentData($userKycId, 0);
                if ($assesmentData && $assesmentData->count()) {
                    $arrRankName = Helper::getRankNames();
                    $rank_decision = $arrRankName[$assesmentData[0]->avg_rank];
                } else {
                    $arrRankName = [];
                }
                $assesmentDetail    = $this->userRepo->getRiskAssesmentRating($userKycId);


                //echo "<pre>";
                //print_r($companyProfile);
                //echo "".$companyProfile['registration_no'];exit;


                $corporationName = $companyProfile->company_name;
                $registration_date=($companyProfile->registration_date!='' && $companyProfile->registration_date!=null) ? $companyProfile->registration_date :'';
                $beficiyerArray = [];
                $beficiyerArray = $this->application->getBeneficiaryOwnersData($user_id);
                $corporateAddress = '';
                $resAddress = '';
                //Business Address
                if($companyAddress) {
                    if($companyAddress->floor) {
                        $corporateAddress=$companyAddress->floor.",";
                    }

                    if($companyAddress->building) {
                        $corporateAddress.=$companyAddress->building.",";
                    }

                    if($companyAddress && isset($companyAddress->street)) {
                        $corporateAddress.= $companyAddress->street.",";
                    }

                    

                    if($companyAddress->region) {
                        $corporateAddress.=$companyAddress->region.",";
                    }

                    if($companyAddress->city_id) {
                        $corporateAddress.=$companyAddress->city_id.",";
                    }

                    if($companyAddress->country_id) {
                        $corporateAddress.=$arrCountry[$companyAddress->country_id].",";

                    }
                    if($companyAddress->postal_code) {
                        $corporateAddress.=$companyAddress->postal_code.",";
                    }
                    $corporateAddress = trim($corporateAddress,",");



                    if($companyAddress->corre_floor) {
                        $resAddress=$companyAddress->corre_floor.",";
                    }

                    if($companyAddress->corre_building) {
                        $resAddress.=$companyAddress->corre_building.",";
                    }
                    if($companyAddress->corre_street) {
                        $resAddress.= $companyAddress->corre_street.",";
                    }

                    if($companyAddress->corre_region) {
                        $resAddress.=$companyAddress->corre_region.",";
                    }

                    if($companyAddress->corre_city) {
                        $resAddress.=$companyAddress->corre_city.",";
                    }

                    if($companyAddress->corre_country) {
                        $resAddress.=$arrCountry[$companyAddress->corre_country].",";

                    }
                     if($companyAddress->corre_postal_code) {
                        $resAddress.=$companyAddress->corre_postal_code;
                    }
                    $resAddress = trim($resAddress,",");

                    $telephoneNumber = "( ".$companyAddress->area_code." )".$companyAddress->corre_telephone;
                    $mobileNo = "( ".$companyAddress->country_code." )".$companyAddress->corre_mobile;
                    $faxNo = "( ".$companyAddress->fax_countrycode." )".$companyAddress->corre_fax;
                    $Email =  $companyAddress->corre_email;
                } else {
                    $mobileNo = '';
                    $faxNo = '';
                    $Email =  '';
                    $telephoneNumber ='';
                    $resAddress = '';
                    $corporateAddress ='';
                }
                
                //$registration_date = "";
                
                $clientSubmitionData['corporationName']     = $corporationName;
                $clientSubmitionData['registration_no']     = $companyProfile['registration_no'];
                $clientSubmitionData['registration_date']   = $registration_date;
                $clientSubmitionData['corporateAddress']    = $corporateAddress;
                $clientSubmitionData['resAddress']   = $resAddress;
                $clientSubmitionData['telephoneNumber']     = $telephoneNumber;
                $clientSubmitionData['mobileNo']            = $mobileNo;
                $clientSubmitionData['faxNo']            = $faxNo;
                $clientSubmitionData['Email']            = $Email;
                //$clientSubmitionData['complianceReport'] = $complianceReport;
                $reportByName = \Auth::User()->f_name." ".\Auth::User()->l_name;
                 // This  $data array will be passed to our PDF blade
                isset($corporationName)? : $corporationName = "NoRecord";
                $fullName = $corporationName;

                $saveDatalog['kyc_id'] = $userKycId;
                $saveDatalog['created_by'] = \Auth::User()->user_id;
                $saveDatalog['created_at'] = date('Y-m-d H:i:s');
                

                if($user_type == 'Dexter') {
                    $saveDatalog['report_for'] = 'Dexter';
                    $this->userRepo->savecomplincesfor($saveDatalog);
                $pdf = PDF::loadView('backend.report_corpcomplete_download',compact('clientSubmitionData','beficiyerArray',
                                     'arrCountry','complianceReport','beficiyerData','user_id','primaryId','assesmentDetail',
                                    'assesmentData','reportByName','socialmediaData'));

                    $userBaseDir = 'appDocs/Document/corporatepdf/pdf/' . $userKycId;
                    $userFileName = $fullName.date('ymdhis').'_report.pdf';
                    $docfullpath = $userBaseDir.DIRECTORY_SEPARATOR.$userFileName;
                    // $fileContent = $pdf->output();
                    $this->storage->engine()->put($docfullpath, $pdf->output());
                    $pfdBaseDirApp = "app/" . $userBaseDir;
                    $pdfRealPath = storage_path() . DIRECTORY_SEPARATOR . $pfdBaseDirApp . DIRECTORY_SEPARATOR . $userFileName;
                    chmod($pdfRealPath, 0777); //exit;
                    $hashfile= hash_file('SHA256', $pdfRealPath);
                    $saveDatatrail['kyc_id'] = $userKycId;
                    $saveDatatrail['document_path'] = $docfullpath;
                    $saveDatatrail['document_hash'] = "0x".$hashfile;
                    $saveDatatrail['status'] = $is_report_genrated;
                    $saveDatatrail['eth_status'] = 0;
                    $saveDatatrail['created_by'] = \Auth::User()->user_id;
                    $saveDatatrail['created_at'] = date('Y-m-d H:i:s');
                    $this->userRepo->savecomplincestrail($saveDatatrail);
                    $dataArray['is_report_genrated'] = 2;
                    $this->userRepo->updatecomplincesById($compliance_report_id, $dataArray);



                }
                

                if($user_type == 'JuriDex') {
                    $corp_jur_addressto = $data['corp_jur_addressto'];
                    $saveDatalog['report_for'] = $corp_jur_addressto;
                    $this->userRepo->savecomplincesfor($saveDatalog);

                    
                    $client_name= 'Olga';
                    $pdf = PDF::loadView('backend.juridex_report_corpcomplete_download',compact('clientSubmitionData','beficiyerArray',
                                     'arrCountry','complianceReport','beficiyerData','user_id','primaryId','assesmentDetail',
                                    'assesmentData','reportByName','client_name','corp_jur_addressto','socialmediaData'));

                    $userBaseDir = 'appDocs/Document/corporatepdf/pdf/' . $userKycId;
                    $userFileName = $fullName.date('ymdhis').'_report.pdf';
                    $docfullpath = $userBaseDir.DIRECTORY_SEPARATOR.$userFileName;
                    $this->storage->engine()->put($docfullpath, $pdf->output());
                    $pfdBaseDirApp = "app/" . $userBaseDir;
                    $pdfRealPath = storage_path() . DIRECTORY_SEPARATOR . $pfdBaseDirApp . DIRECTORY_SEPARATOR . $userFileName;

                    chmod($pdfRealPath, 0777); //exit;
                    $hashfile= hash_file('SHA256', $pdfRealPath);
                    $saveDatatrail['kyc_id'] = $userKycId;
                    $saveDatatrail['document_path'] = $docfullpath;
                    $saveDatatrail['document_hash'] = "0x".$hashfile;
                    $saveDatatrail['status'] = $is_report_genrated;
                    $saveDatatrail['eth_status'] = 0;
                    $saveDatatrail['created_by'] = \Auth::User()->user_id;
                    $saveDatatrail['created_at'] = date('Y-m-d H:i:s');
                    $this->userRepo->savecomplincestrail($saveDatatrail);
                    $dataArray['is_report_genrated'] = 2;
                    $this->userRepo->updatecomplincesById($compliance_report_id, $dataArray);
                }

                $fileName = $corporationName . ".pdf";
                return $pdf->download($fileName);
            } 
        
    }
    
    
    /*
     * show all individual users
     * 
     */

    public function profileMonitoringList(Request $request) {
        $filter = [];
        $search_keyword = $request->get('search_keyword');
        $req_status = $request->get('req_status');
        $doc_status = $request->get('doc_status');
        $filter['search_keyword'] = '';
        $filter['req_status'] = '';
        $filter['doc_status'] = '';
        if ($search_keyword != '' && $search_keyword != null) {
            $filter['search_keyword'] = $search_keyword;
        }


        if($req_status != '' && $req_status != null) {
            $filter['req_status'] = $req_status;
        }
        
        if($doc_status != '' && $doc_status != null) {
            $filter['doc_status'] = $doc_status;
        }

        $usersList = $this->userRepo->getProfileMonitoringPaginate($filter);
        return view('backend.profile_monitoring', ['usersList' => $usersList, 'filter' => $filter]);
    }


    public function viewDocumentDetail(Request $request){
        
        $doc_no = (int) $request->get('doc_no');
        $id = (int) $request->get('id');
        
        $result = $this->userRepo->getMonitorDocData($id);    
     
         return view('backend.profile_monitoring_detail', ['docdetail' => $result]);
    }
    
    
    public function updateDocStatus(Request $request){
        
        $userMailArr        =   [];
        $arrDocument =   Helpers::getMonitoringDropDown();
        
        $id                 =   (int) $request->get('id');
        $doc_status         =   (int) $request->get('doc_status');
        
        $data['doc_status'] =   (int) $doc_status;
        $userId             =   \Auth::user()->user_id;

        $data['updated_at'] =   date('Y-m-d H:i:s');
        $data['updated_by'] =   (int) $userId;
        
        if($data['doc_status']==2){
            
           $data['is_active'] =   0; 
           
        }
        $result = $this->userRepo->updateDocStatus($data,$id);
        
        $documentData  =   [];
        $user_kyc_id                        =   $result->user_kyc_id;
        if($data['doc_status']==2){
            
            $documentData['user_req_doc_id']    =   $result->user_req_doc_id;
            $documentData['doc_id']             =   $result->doc_id;
            $documentData['document_number']    =   $result->document_number;
            $documentData['is_monitor']         =   $result->is_monitor;
            $documentData['form_id']            =   $result->form_id;
            $documentData['doc_for']            =   $result->doc_for;
            $documentData['doc_no']             =   $result->doc_no;
            $documentData['is_active']          =   1;
            $documentData['status']             =   1;
            $documentData['doc_status']         =   0;
            $documentData['issuance_date']      =   $result->issuance_date;
            $documentData['expire_date']        =   $result->expire_date;
            $documentData["user_kyc_id"]        =   $result->user_kyc_id;
            $documentData["created_by"]         =   $userId;
            $documentData["updated_by"]         =   $userId;
            $documentData["created_at"]         =   date('Y-m-d H:i:s');
            $documentData["updated_at"]         =   date('Y-m-d H:i:s');
            
            $this->userRepo->storeUserKycDocumentTypeData($documentData,null);
            
            $userMailArr['doc_status']          =   'disapproved';
            $userMailArr['document_name']       =    $arrDocument[$result->doc_no];
            
        }else if($data['doc_status']==1){

            $userMailArr['doc_status']          =   'approved';
            $userMailArr['document_name']       =   $arrDocument[$result->doc_no];
        }
        
        // send mail to user for acknowledging approved or disapproved document
        
        $kycCompleteArray = Helpers::getKycDetailsByKycID($user_kyc_id);
        $userId = $kycCompleteArray->user_id;

        $userType  = $this->userRepo->getUserType($userId);
        if($userType == 1) {
            $userCheckArr = $this->userRepo->getUserDetail($userId);
        } else if($userType == 2) {
          $userCheckArr = $this->userRepo->getUserCorpData($userId);
        }
      
       
        if ($userCheckArr->user_type == 2) {
            $userCheckArrData = $this->userRepo->getcorpDetail($userId);
            $userMailArr['name'] = $userCheckArr->f_name . ' ' . $userCheckArr->l_name;
            $userMailArr['email'] = $userCheckArr->email;
            $userMailArr['userType'] = $userCheckArr->user_type;
            $userMailArr['corp_name'] = $userCheckArrData->corp_name;
        } else {
            $userMailArr['name'] = $userCheckArr->f_name . ' ' . $userCheckArr->l_name;
            $userMailArr['email'] = $userCheckArr->email;
            $userMailArr['userType'] = $userCheckArr->user_type;
            $userMailArr['corp_name'] = "";
        }

        Event::dispatch("admin.profiledocapproval", serialize($userMailArr));

        return redirect(route('profile_monitoring'));
    }
//// download Compliance Report pdf

    public function downloadIndicompletePDF(Request $request) {
        $data = $request->all();
        $userKycId = $data['userKycId'];
        $user_id = $data['user_id'];
        $report_type=$data['report_type'];
       // echo "--->".$user_id; exit;
        $user_type = 1;
            if ($user_type == 1) {
            $assesmentDetail = [];
            $arrAssesmentRating = [];
            $assesmentOldData = [];
            $arrRankName = "";

           
            $is_by_company = (int) $request->get('is_by_company');
            $benifinary = [];
            if ($is_by_company == '1') {
                $corp_user_kyc_id = (int) $request->get('corp_user_kyc_id');
                $corp_user_id = $user_id;
                $user_id = 0;
                $benifinary['user_id'] = $user_id;
                $benifinary['corp_user_id'] = $corp_user_id;
                $benifinary['user_type'] = $user_type;
                $benifinary['userKycId'] = $userKycId;
                $benifinary['corp_user_kyc_id'] = $corp_user_kyc_id;
                $benifinary['is_by_company'] = $is_by_company;
            } else {
                $corp_user_id = 0;
                $corp_user_kyc_id = 0;
                $benifinary['user_id'] = $user_id;
                $benifinary['corp_user_id'] = $corp_user_id;
                $benifinary['user_type'] = $user_type;
                $benifinary['userKycId'] = $userKycId;
                $benifinary['corp_user_kyc_id'] = $corp_user_kyc_id;
                $benifinary['is_by_company'] = $is_by_company;
            }

            if ($userKycId == '' && $userKycId == 0 && $userKycId == null) {
                $userKycId = $this->application->getUserKycid($user_id);
            }


            $arrAssesmentRating = [];
            $rank_decision = '';
            //echo "==>".$user_id; exit;
            if ($user_id > 0) {
                $userData = $this->userRepo->getUserDetail($user_id);
                //$userKycId = 98;
                $userByComapnyId = $user_id;
            } else {
                $userData = false;
                $userByComapnyId = $corp_user_id;
            }
            ///get user Assgement Data

            $assesmentData = $this->userRepo->getAssesmentData($userKycId, 1);
            //dd($assesmentData);
            $assesmentOldData = $this->userRepo->getAssesmentData($userKycId, 0);

            if ($assesmentData && $assesmentData->count()) {
                $arrRankName = Helper::getRankNames();
                $rank_decision = $arrRankName[$assesmentData[0]->avg_rank];
            } else {
                $arrRankName = [];
            }

            $assesmentDetail    = $this->userRepo->getRiskAssesmentRating($userKycId);
            $documentsData      = $this->userRepo->getDocumentTypeInfo($userKycId);
            //dd($documentsData);
            $socialmediaData    = $this->userRepo->getSocialmediaInfo($userKycId);
            $userPersonalData   = $this->userRepo->getUseKycPersonalData($userKycId);
            $familyData         = $this->userRepo->getFamilyInfo($userKycId);
            $residentialData    = $this->userRepo->getResidentialInfo($userKycId);
            $professionalData   = $this->userRepo->getProfessionalInfo($userKycId);
            $otherDocsArray     = $this->application->otherReqDocument($userKycId);
            //$commercialData     =   $this->userRepo->getCommercialInfo($userKycId);
            $commercialData     = $this->userRepo->getCommercialInfo($userKycId);
            $financialData      = $this->userRepo->getFinancialInfo($userKycId);
            $bussAddrData       = $this->userRepo->getBussAddrInfo($userKycId);
            $documentArray      = $this->application->corporateDocument($userKycId);
            $kycDetail          = $this->wcapi->getWcapiDetail($userKycId, 'screeningRequest');
            $kycpersonalDetail  = $this->wcapi->getWcapiPersonalDetailFinal($userKycId, 'profile');
            $kycCompleteArray   = Helpers::getKycDetailsByKycID($userKycId);
            $isKycComplete      = isset($kycCompleteArray) ? $kycCompleteArray['is_kyc_completed'] : 0;
            $isKycApprove       = isset($kycCompleteArray) ? $kycCompleteArray['is_approve'] : 0;
            $complianceReport   = $this->userRepo->complianceReport($userKycId);
            $is_report_genrated = $complianceReport->is_report_genrated;
            $compliance_report_id   = $complianceReport->compliance_report_id;
            
           

            /* User Type */
            $is_byCompany = $kycCompleteArray->is_by_company;
            if ($is_byCompany == 1) {
                $userType = 1;
            } else {
                $userType = $this->application->getUserType($kycCompleteArray->user_id);
            }

            $IdentityCardNumber = '';
            $userPassportNumber = '';
            $IdentityCardExp = '';
            $userPassportExp ='';

           if ($documentsData) {
                $passportArray = $this->getPassportNumber($documentsData, 'passport');
                $passportArrays = explode("#",$passportArray);
                $userPassportNumber = $passportArrays[0];
                $userPassportExp    = $passportArrays[1];
                $IdentityCardArray = $this->getPassportNumber($documentsData,'idcard');
                $IdentityCardArrays = explode("#",$IdentityCardArray);
                $IdentityCardNumber = @$IdentityCardArrays[0];
                $IdentityCardExp = @$IdentityCardArrays[1];
            } else {
                $userPassportNumber = '';
                $userPassportExp    = '';
            }
            

            //$userScoreData = [];
            $arrCountry = Helpers::getCountryDropDown();
            $userCountryID = isset($residentialData->country_id) ? $residentialData->country_id : '';
            if ($userCountryID) {
                $userCountry = $arrCountry[$userCountryID];
            } else {
                $userCountry = 101;
            }

            ///User Kyc Detail
            $bussAddrData = $this->userRepo->getBussAddrInfo($userKycId);
            $primaryId = isset($kycDetail->wcapi_req_res_id) ? $kycDetail->wcapi_req_res_id : '';
                $clientSubmitionData['userData']        = $userData;
                $clientSubmitionData['documentsData']   = $documentsData;
                $clientSubmitionData['socialmediaData'] = $socialmediaData;
                $clientSubmitionData['professionalData']= $professionalData;
                $fullName = $userPersonalData->f_name."_".$userPersonalData->l_name;
                $reportByName = \Auth::User()->f_name." ".\Auth::User()->l_name;
                 // This  $data array will be passed to our PDF blade
                isset($fullName)? : $fullName = "NoRecord";
                $saveDatalog['kyc_id'] = $userKycId;
                $saveDatalog['created_by'] = \Auth::User()->user_id;
                $saveDatalog['created_at'] = date('Y-m-d H:i:s');
                if($report_type =="Dexter") {
                    $saveDatalog['report_for'] = 'Dexter';
                    $this->userRepo->savecomplincesfor($saveDatalog);
                  /*  return view('backend.report_indicomplete_download',compact('clientSubmitionData',
                                     'arrCountry','complianceReport','userPersonalData','documentsData','assesmentData','userPassportNumber','userPassportExp',
                                    'fullName','primaryId','assesmentDetail', 'reportByName','IdentityCardNumber','IdentityCardExp')); */
                $pdf = PDF::loadView('backend.report_indicomplete_download',compact('clientSubmitionData',
                                     'arrCountry','complianceReport','userPersonalData','documentsData','assesmentData','userPassportNumber','userPassportExp',
                                    'fullName','primaryId','assesmentDetail', 'reportByName','IdentityCardNumber','IdentityCardExp','socialmediaData'));

                    $userBaseDir = 'appDocs/Document/individualpdf/pdf/' . $userKycId;
                    $userFileName = $fullName.date('ymdhis').'_report.pdf';
                    $docfullpath = $userBaseDir.DIRECTORY_SEPARATOR.$userFileName;
                    // $fileContent = $pdf->output();
                    $this->storage->engine()->put($docfullpath, $pdf->output());
                    $pfdBaseDirApp = "app/" . $userBaseDir;
                    $pdfRealPath = storage_path() . DIRECTORY_SEPARATOR . $pfdBaseDirApp . DIRECTORY_SEPARATOR . $userFileName;
                    chmod($pdfRealPath, 0777); //exit;
                    $hashfile= hash_file('SHA256', $pdfRealPath);
                    $saveDatatrail['kyc_id'] = $userKycId;
                    $saveDatatrail['document_path'] = $docfullpath;
                    $saveDatatrail['document_hash'] = "0x".$hashfile;
                    $saveDatatrail['status'] = $is_report_genrated;
                    $saveDatatrail['eth_status'] = 0;
                    $saveDatatrail['created_by'] = \Auth::User()->user_id;
                    $saveDatatrail['created_at'] = date('Y-m-d H:i:s');
                    $this->userRepo->savecomplincestrail($saveDatatrail);
                    $dataArray['is_report_genrated'] = 2;
                    $this->userRepo->updatecomplincesById($compliance_report_id, $dataArray);
                }
                 

                if($report_type =="JuriDex") {

                    $indi_jur_addressto = $data['indi_jur_addressto'];
                    $saveDatalog['report_for'] = $indi_jur_addressto;
                    $this->userRepo->savecomplincesfor($saveDatalog);
                    /*return view('backend.report2_indicomplete_download',compact('clientSubmitionData',
                                     'arrCountry','complianceReport','userPersonalData','documentsData','assesmentData','userPassportNumber','userPassportExp',
                                    'fullName','primaryId','assesmentDetail', 'reportByName','indi_jur_addressto'));  */

                  $pdf = PDF::loadView('backend.report2_indicomplete_download',compact('clientSubmitionData',
                                     'arrCountry','complianceReport','userPersonalData','documentsData','assesmentData','userPassportNumber','userPassportExp',
                                    'fullName','primaryId','assesmentDetail', 'reportByName','indi_jur_addressto','IdentityCardNumber','IdentityCardExp','socialmediaData'));

                $userBaseDir = 'appDocs/Document/individualpdf/pdf/' . $userKycId;
                $userFileName = $fullName.date('ymdhis').'_report.pdf';
                $docfullpath = $userBaseDir.DIRECTORY_SEPARATOR.$userFileName;
                $this->storage->engine()->put($docfullpath, $pdf->output());
                $pfdBaseDirApp = "app/" . $userBaseDir;
                $pdfRealPath = storage_path() . DIRECTORY_SEPARATOR . $pfdBaseDirApp . DIRECTORY_SEPARATOR . $userFileName;
               
                chmod($pdfRealPath, 0777); //exit;
                $hashfile= hash_file('SHA256', $pdfRealPath);
                $saveDatatrail['kyc_id'] = $userKycId;
                $saveDatatrail['document_path'] = $docfullpath;
                $saveDatatrail['document_hash'] = "0x".$hashfile;
                $saveDatatrail['status'] = $is_report_genrated;
                $saveDatatrail['eth_status'] = 0;
                $saveDatatrail['created_by'] = \Auth::User()->user_id;
                $saveDatatrail['created_at'] = date('Y-m-d H:i:s');
                $this->userRepo->savecomplincestrail($saveDatatrail);
                $dataArray['is_report_genrated'] = 2;
                $this->userRepo->updatecomplincesById($compliance_report_id, $dataArray);
                
                }
                $fileName = $fullName . ".pdf";
                return $pdf->download($fileName);
            }
    

    }
/////// API  calling for user detail///

    /*
     * show user Details
     * @param Request $request
     */

    public function detail(Request $request) {

        try {

            
            $assesmentDetail = [];
            $arrAssesmentRating = [];
            $assesmentOldData = [];
            $arrRankName = "";
            $user_id = (int) $request->get('user_id');
            
          
            if ($user_id !='') {
                 
                $userKycId = $this->application->getUserKycid($user_id);
            }


            $arrAssesmentRating = [];
            $rank_decision = '';
            //echo "==>".$user_id; exit;
            if ($user_id > 0) {
                $userData = $this->userRepo->getUserDetail($user_id);
                //$userKycId = 98;
                $userByComapnyId = $user_id;
            } else {
                $userData = false;
                $userByComapnyId = $corp_user_id;
            }



            ///get user Assgement Data

            $assesmentData = $this->userRepo->getAssesmentData($userKycId, 1);
            $assesmentOldData = $this->userRepo->getAssesmentData($userKycId, 0);

            if ($assesmentData && $assesmentData->count()) {
                $arrRankName = Helper::getRankNames();
                $rank_decision = $arrRankName[$assesmentData[0]->avg_rank];
            } else {
                $arrRankName = [];
            }

            $assesmentDetail = $this->userRepo->getRiskAssesmentRating($userKycId);


            $documentsData = $this->userRepo->getDocumentTypeInfo($userKycId);
            //dd($documentsData);

            $socialmediaData = $this->userRepo->getSocialmediaInfo($userKycId);
            $userPersonalData = $this->userRepo->getUseKycPersonalData($userKycId);
            $familyData = $this->userRepo->getFamilyInfo($userKycId);
            $residentialData = $this->userRepo->getResidentialInfo($userKycId);



            $professionalData = $this->userRepo->getProfessionalInfo($userKycId);
            $complianceReport = $this->userRepo->complianceReport($userKycId);
            $otherDocsArray = $this->application->otherReqDocument($userKycId);
            //$commercialData     =   $this->userRepo->getCommercialInfo($userKycId);
            $commercialData = $this->userRepo->getCommercialInfo($userKycId);
            $financialData = $this->userRepo->getFinancialInfo($userKycId);
            $bussAddrData = $this->userRepo->getBussAddrInfo($userKycId);
            $documentArray = $this->application->corporateDocument($userKycId);
            $kycDetail = $this->wcapi->getWcapiDetail($userKycId, 'screeningRequest');
            $kycpersonalDetail = $this->wcapi->getWcapiPersonalDetailFinal($userKycId, 'profile');
            $kycCompleteArray = Helpers::getKycDetailsByKycID($userKycId);
            $isKycComplete = isset($kycCompleteArray) ? $kycCompleteArray['is_kyc_completed'] : 0;
            $isKycApprove = isset($kycCompleteArray) ? $kycCompleteArray['is_approve'] : 0;

            $beficiyerData = [];
            $beficiyerData = $this->application->getBeneficiaryOwnersData($user_id);
            /* User Type */
            $is_byCompany = $kycCompleteArray->is_by_company;
            if ($is_byCompany == 1) {
                $userType = 1;
            } else {
                $userType = $this->application->getUserType($kycCompleteArray->user_id);
            }



            if ($documentsData) {
                $userPassportNumber = $this->getPassportNumber($documentsData,'passport');
            } else {
                $userPassportNumber = '';
            }


            //echo "<pre>";
            // print_r($kycpersonalDetail); exit;
            if ($kycpersonalDetail) {
                $wcMatch = $kycpersonalDetail->match_strenght;
                $wcpep = $kycpersonalDetail->pep;
                $wcPassport = $kycpersonalDetail->passport;
                //$userPassportNumber = $documentsData->
            } else {
                $wcMatch = '';
                $wcpep = '';
                $wcPassport = '';
            }

            //$userScoreData = [];
            $arrCountry = Helpers::getCountryDropDown();
            $userCountryID = isset($residentialData->country_id) ? $residentialData->country_id : '';
            if ($userCountryID) {
                $userCountry = $arrCountry[$userCountryID];
            } else {
                $userCountry = 101;
            }

            ///User Kyc Detail
            $bussAddrData = $this->userRepo->getBussAddrInfo($userKycId);

            
            $data = response()->json(['userData' => $userData,
                            'userPersonalData' => $userPersonalData, 'documentsData' => $documentsData,
                            'socialmediaData' => $socialmediaData, 'familyData' => $familyData,
                            'residentialData' => $residentialData, 'professionalData' => $professionalData,
                            'commercialData' => $commercialData, 'financialData' => $financialData, 'bussAddrData' => $bussAddrData,
                            'userKycId' => $userKycId, 'documentArray' => $documentArray, 'kycDetail' => $kycDetail, 
                            'arrAssesmentRating' => $arrAssesmentRating, 'rank_decision' => $rank_decision,
                            'assesmentDetail' => $assesmentDetail, 'isKycComplete' => $isKycComplete, 'isKycApprove' => $isKycApprove,
                            'kycCompleteArray' => $kycCompleteArray, 'assesmentOldData' => $assesmentOldData,
                            'userCountry' => $userCountry, 'kycpersonalDetail' => $kycpersonalDetail,
                            'arrRankName' => $arrRankName, 'userType' => $userType, 'userByComapnyId' => $userByComapnyId,
                            'otherDocsArray' => $otherDocsArray, 'complianceReport' => $complianceReport,
                            'beficiyerData' => $beficiyerData]);
            return $data;
                //dd($data);
            
        } catch (Exception $exc) {
            dd(Helpers::getExceptionMessage($exc));
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($exc))->withInput();
        }
    }

//////API calling end



    public function storefinalReport(Request $request) {
        $data = $request->all();
        $user_id = \Auth::User()->user_id;
        $total_rank = 0;
        $emirates_id_no             = $request->get('emirates_id_no');
        $emirates_exp_date          = $request->get('emirates_exp_date');
        
        $residence_no               = $request->get('residence_no');
        $residency_expiry_date      = $request->get('residency_expiry_date');
        
        $iban                       = $request->get('iban');
        $rm_name                    = $request->get('rm_name');
        $rm_mobile                  = $request->get('rm_mobile');
        $tax_registration_no        = $request->get('tax_registration_no');
        $license                    = $request->get('license');
        $tenancy_exp_date           = $request->get('tenancy_exp_date');
        $registration_tenancy       = $request->get('registration_tenancy');
        
        $website                    = $request->get('website');
        $bank_name                  = $request->get('bank_name');
        $accouunt_number            = $request->get('accouunt_number');
        $passport_verify            = $request->get('passport_verify');
        $corr_accouunt_number       = $request->get('corr_accouunt_number');
        $general_investigation      = $request->get('general_investigation');
        $references_investigation   = $request->get('references_investigation');
        $analysis_findings          = $request->get('analysis_findings');
        $conclusion_recommendation  = $request->get('conclusion_recommendation');
        $comment_compliance         = $request->get('comment_compliance');


        $kycId = $data['user_kyc_id'];
        $saveData['kyc_id']             = $data['user_kyc_id'];
        $saveData['emirates_id_no']     = $emirates_id_no;
        $saveData['emirates_exp_date']  = $emirates_exp_date;
        $saveData['residence_no']       = $residence_no;
        $saveData['residency_expiry_date'] = $residency_expiry_date;
        $saveData['iban']               = $iban;
        $saveData['rm_name']            = $rm_name;
        $saveData['rm_mobile']          = $rm_mobile;
        $saveData['website']            = $website;

        $saveData['tax_registration_no']= $tax_registration_no;
        $saveData['license']            = $license;
        $saveData['tenancy_exp_date']   = $tenancy_exp_date;
        $saveData['registration_tenancy'] = $registration_tenancy;

        $saveData['bank_name']            = $bank_name;
        $saveData['accouunt_number']        = $accouunt_number;
        $saveData['passport_verify']        = $passport_verify;
        $saveData['corr_accouunt_number']   = $corr_accouunt_number;
        $saveData['general_investigation']  = $general_investigation;
        $saveData['references_investigation'] = $references_investigation;
        $saveData['analysis_findings'] = $analysis_findings;
        $saveData['comment_compliance'] = $comment_compliance;
        $saveData['is_report_genrated'] = 1;
        $saveData['conclusion_recommendation'] = $conclusion_recommendation;
        $saveData['created_by'] = $user_id;
        $saveData['updated_by'] = $user_id;
        $saveData['created_at'] = date('Y-m-d H:i:s');
        $saveData['updated_at'] = date('Y-m-d H:i:s');

        $this->userRepo->savecomplinces($saveData);


        $saveDatalog['kyc_id'] = $data['user_kyc_id'];
        $saveDatalog['status_mark'] = 1;
        $saveDatalog['created_by'] = $user_id;
        $saveDatalog['created_at'] = date('Y-m-d H:i:s');

        $this->userRepo->savecomplinceslog($saveDatalog);
           
        Session::flash('message', 'Compliance Reported Updated Successfully.');
        if ($data['user_type'] == '1') {
            $route = route("user_detail", ['user_id' => $data['user_id'], 'user_type' => $data['user_type'], 'user_kyc_id' => $data['user_kyc_id'], 'is_by_company' => 0,'tab' => 'tab08']);
        } else if ($data['user_type'] == '2') {
            $route = route("corp_user_detail", ['user_id' => $data['user_id'], 'user_type' => $data['user_type'], 'user_kyc_id' => $data['user_kyc_id'], 'is_by_company' => 0,'tab' => 'tab08']);
        }

        return redirect($route);

    }
    public function lockUnlock(Request $request) {

        try {
            $userArr = [];

            $userId = $request->user_id;
            if($request->block_statatus ==1){
            $block_status = NULL;//unblock
            } else {
               $block_status = 1;  //block
            }
            
            $this->userRepo->userAccountLockUnlock($userId,$block_status);
            $userArr = $this->userRepo->find($userId, ['email', 'first_name', 'last_name']);
            $verifyUserArr = [];
            $verifyUserArr['name'] = $userArr->f_name . ' ' . $userArr->l_name;
            $verifyUserArr['email'] = $userArr->email;
            $verifyUserArr['user_type'] = $userArr->user_type;
            Event::dispatch("user.email.account.lockUnlock", serialize($verifyUserArr));
            return redirect()->route('individual_user');

            } catch (Exception $exc) {
             return redirect()->back()->withErrors(Helpers::getExceptionMessage($exc))->withInput();
        }
                   

    }

     public function generateHash($fileHash) {
        try {

            $token = $fileHash;
            $cipher_method = 'aes-128-ctr';
            $tokenKey = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $enc_key = openssl_digest($tokenKey, 'SHA256', TRUE);
            $enc_iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher_method));
            $crypted_token = openssl_encrypt($token, $cipher_method, $enc_key, 0, $enc_iv) . "::" . bin2hex($enc_iv);
            return $crypted_token;

            } catch (Exception $exc) {
             return redirect()->back()->withErrors(Helpers::getExceptionMessage($exc))->withInput();
        }
    }
    
    public function updatetotalCount($kycDetail) {

        try {
            $unresolved = 0;
            $autoresolved = 0;
            $caseId = '';
            $caseSystemId = '';
            $resultId ='';

            $response  = $kycDetail['api_responce']; 
            $primaryId = $kycDetail['wcapi_req_res_id'];
            $dataArray = json_decode($response, JSON_UNESCAPED_UNICODE);
            if(isset($dataArray['results']) && count($dataArray) > 0) {
                if($dataArray['caseId']) {
                    $caseId = $dataArray['caseId'];
                }
                if(isset($dataArray['caseSystemId'])) {
                    $caseSystemId = $dataArray['caseSystemId'];
                }
                if($dataArray['results']) {
                    


                  foreach($dataArray['results'] as $resultArray) {
                      $resolutionArray = $resultArray['resolution'];
                    if(is_null($resolutionArray)) {
                         $unresolved++;
                     }

                    if(!is_null($resolutionArray)) {
                        $resolutionStatusID = $resultArray['resolution']['statusId'];
                        $resolutionRiskID = $resultArray['resolution']['riskId'];
                        $resolutionresaonID = $resultArray['resolution']['reasonId'];
                          if($resolutionStatusID!='' && is_null($resolutionRiskID) && is_null($resolutionresaonID)) {
                            $autoresolved++;
                         }
                     }

                }

                    $totalMatch = 0;
                    $data   =[];
                    $data['exactcount']   = 0;
                    $data['falsecount']   = $unresolved;
                    $data['autoresolved'] = $autoresolved;
                    $data['caseid']       = $caseId;
                    Helpers::UpdatePositive($data ,$primaryId);
                }
            }
        }
            catch (Exception $exc) {
             return redirect()->back()->withErrors(Helpers::getExceptionMessage($exc))->withInput();
        }
    }


public function downloadhistoricalPDF(Request $request) {
        $data = $request->all();
        $userKycId =  $request->get('userKycId');
        //echo "==>".$userKycId; exit;
        $Arrays = Helpers::getcomplincestrail($userKycId);
        $file = storage_path() . DIRECTORY_SEPARATOR."app/".$Arrays[0]->document_path;
       // return response()->download($file);
        /////Manual Download ////
        $fileName = "historical_report.pdf";
        $mimetype = mime_content_type($file);
        //echo $mimetype; exit;
        //ob_end_clean();
        //return response()->download($file);
        $data = file_get_contents($file);
        //echo $data; exit;
        ob_end_clean();
        header('Content-Type: "'.$mimetype.'"');
        header('Content-Disposition: attachment; filename="'.$fileName.'"');
        header("Content-Transfer-Encoding: binary");
        header('Expires: 0'); header('Pragma: no-cache');
        header("Content-Length: ".strlen($data));
        echo $data;
        exit;

    }




    public function individualGenerateReport(Request $request) {
        try {
            $userKycId = $request->get('kycId');
            return view('backend.individual_download_history')
                            ->with(['userKycId' => $userKycId]);
        } catch (Exception $exc) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($exc))->withInput();
        }
    }


}
