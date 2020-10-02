<?php

namespace App\Http\Controllers\Application;

use Auth;
use File;
use Session;
use Helpers;
use Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PersonalProfileFormRequest;
use App\Http\Requests\FamilyFormRequest;
use App\Http\Requests\ResidentialFormRequest;
use App\Http\Requests\ProdessionalFormRequest;
use App\Http\Requests\CommercialFormRequest;
use App\Http\Requests\FinancialFormRequest;
use App\Inv\Repositories\Contracts\Traits\StorageAccessTraits;
use App\Inv\Repositories\Contracts\UserInterface as InvUserRepoInterface;
use App\Inv\Repositories\Contracts\ApplicationInterface as InvAppRepoInterface;
use App\Inv\Repositories\Libraries\Storage\Contract\StorageManagerInterface;
use App\Inv\Repositories\Models\UserReqDoc;
use App\Inv\Repositories\Models\Userkyc;
use App\Inv\Repositories\Models\Document;
use App\Inv\Repositories\Models\User as UserModel;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller {

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

    use StorageAccessTraits;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(InvUserRepoInterface $user, InvAppRepoInterface $application, StorageManagerInterface $storage) {
        $this->middleware('auth');
        $this->userRepo = $user;
        $this->application = $application;
        $this->storage = $storage;
    }

    /**
     * Show the User KYC Personal Information
     *
     * @return Response
     */
    public function index(Request $request) {
     
        try {
        $flag=$this->checkUserKycStatus(Auth()->user()->user_id); //check user kyc completed or not
        if($flag==1){
            return redirect(route('thanks_page'));
        }
        $share_name = "";
        $share_passportnumber = "";
        //if(@$request->get('corp_user_id')!=null && @$request->get('user_kyc_id')!=null){
        $corp_user_id = $request->get('corp_user_id');
        $user_kyc_id = $request->get('user_kyc_id');
        $share_name = $request->get('share_name');
        $share_passportnumber = $request->get('share_passportnumber');


        $recentRights = [];
        $benifinary = [];
        $userPersonalData = [];
        $userDocumentType = [];
        $userSocialMedia = [];
        $userSignupdata = [];

        if($corp_user_id > 0 && $user_kyc_id > 0){

            $benifinary['user_kyc_id'] = (int) $user_kyc_id;
            $benifinary['corp_user_id'] = (int) $corp_user_id;
            $benifinary['is_by_company'] = 1;
            $userKycId = (int) $user_kyc_id;
            $userId = null;
        } else {
            $userId = (int) Auth::user()->user_id;
            $userKycId = $this->application->getUserKycid(Auth()->user()->user_id);
            $benifinary['user_kyc_id'] = $userKycId;
            $benifinary['corp_user_id'] = 0;
            $benifinary['is_by_company'] = 0;
        }

       
        
        $benifinary['next_url']=route('family_information', ['user_kyc_id' => $benifinary['user_kyc_id'], 'corp_user_id' => $benifinary['corp_user_id'], 'is_by_company' => $benifinary['is_by_company']]);
        
        $userSignupdata = $this->userRepo->getUserDetail(Auth()->user()->user_id);

        // echo "<pre>";
        // print_r($userSignupdata); exit;
        $resData = $this->userRepo->getUseKycPersonalData($userKycId);
        $resDocumentType = $this->userRepo->getDocumentTypeInfo($userKycId);
    
        $resDocumentName = $this->userRepo->getPrimaryDocumentName();
        //userDocumentType
        $resSocialMedia = $this->userRepo->getSocialmediaInfo($userKycId);
        if ($resDocumentType) {
            if ($resDocumentType->count()) {
                $userDocumentType = $resDocumentType->toArray();
            }
        }
        
        if ($resSocialMedia) {
            if ($resSocialMedia->count()) {
                $userSocialMedia = $resSocialMedia->toArray();
            }
        }


        if ($resData) {
            if ($resData->count()) {

                $userPersonalData = $resData->toArray();
            }
        }
        
      
        
        // kyc admin approve status
        // $kycApproveStatus=$this->userRepo->kycApproveStatus($userKycId);
        $kycApproveStatus=$this->userRepo->kycApproveFinalStatus($userKycId);
        
        return view('frontend.profile', compact('userPersonalData', 'benifinary', 'userDocumentType', 
            'userSocialMedia', 'userSignupdata','kycApproveStatus','resDocumentName','share_name','share_passportnumber'));
        } catch (Exception $ex) {
            dd($ex->getMessage());
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($ex));
        }
    }

    
    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    /* PersonalProfileFormRequest $request */
    public function savePersonalProfile(Request $request) {
        try {



            $data = [];

            $requestVar = $request->all();


            $id = (@$requestVar['id'] != '' && $requestVar['id'] != null) ? (int) $requestVar['id'] : null;

            $date_of_birth = null;
            $legal_maturity_date = null;

            if($requestVar['corp_user_id'] > 0 && $requestVar['user_kyc_id'] > 0 && $requestVar['is_by_company'] == 1) {
                
                $userId = (int) $requestVar['corp_user_id'];
                $userKycId = (int) $requestVar['user_kyc_id'];
                $corpUserId = (int) $requestVar['corp_user_id'];
                $isBycompany = (int) $requestVar['is_by_company'];
                
            }else{
                
                $userId = (int) Auth::user()->user_id;
                //$userKycId = (int) Auth::user()->user_kyc_id;
                $userKycId = $this->application->getUserKycid(Auth()->user()->user_id);
                $isBycompany = 0;
                $corpUserId = 0;
            }




            $inputData = [];
            $id = ($requestVar['id'] != '' && $requestVar['id'] != null) ? (int) $requestVar['id'] : null;
            $inputData['user_id'] = $userId;
            $inputData['user_kyc_id'] = $userKycId;
            $inputData['title'] = $requestVar['title'];
            $inputData['f_name'] = $requestVar['f_name'];
            $inputData['m_name'] = $requestVar['m_name'];
            $inputData['l_name'] = $requestVar['l_name'];
            $inputData['gender'] = $requestVar['gender'];
            $inputData['date_of_birth'] = isset($requestVar['date_of_birth']) ? Helpers::getDateByFormat(@$requestVar['date_of_birth'], 'd/m/Y', 'Y-m-d') : null;
            $inputData['birth_country_id'] = $requestVar['birth_country_id'];
            $inputData['birth_city_id'] = $requestVar['birth_city_id'];
            //$inputData['birth_state_id']    = $requestVar['birth_state_id'];
            $inputData['father_name'] = $requestVar['father_name'];
            $inputData['mother_f_name'] = $requestVar['mother_f_name'];
            $inputData['mother_m_name'] = $requestVar['mother_m_name'];
            $inputData['reg_no'] = $requestVar['reg_no'];
            $inputData['reg_place'] = $requestVar['reg_place'];
            $inputData['f_nationality_id'] = $requestVar['f_nationality_id'];
            $inputData['sec_nationality_id'] = $requestVar['sec_nationality_id'];
            $inputData['residence_status'] = $requestVar['residence_status'];
            $inputData['family_status'] = $requestVar['family_status'];
            //$inputData['guardian_name']     = $requestVar['guardian_name'];
            // $inputData['legal_maturity_date']       = isset($requestVar['legal_maturity_date']) ? Helpers::getDateByFormat(@$requestVar['legal_maturity_date'], 'd/m/Y', 'Y-m-d') : null;
            $inputData['educational_level'] = $requestVar['educational_level'];
            $inputData['educational_level_other'] = ($requestVar['educational_level'] == '9') ? $requestVar['education_other'] : '';
            $inputData['is_residency_card'] = $requestVar['is_residency_card'];

//echo $requestVar['political_position_hold'][0]; exit;


            $mixed = "";


            if (isset($requestVar['political_position_hold'][0]) == 1) {
                $mixed = $requestVar['political_position_hold'][0] . ",";
            }

            if (@$requestVar['political_position_hold'][0] == 2) {

                $mixed = "," . $requestVar['political_position_hold'][0];
            }


            if (isset($requestVar['political_position_hold'][0]) == 1 && isset($requestVar['political_position_hold'][1]) == 2) {
                $mixed = $requestVar['political_position_hold'][0] . "," . $requestVar['political_position_hold'][1];
            }
            if ($requestVar['do_you_hold']) {
                $inputData['do_you_hold'] = $requestVar['do_you_hold'];
            } else {
                $inputData['do_you_hold'] = 0;
            }



            $inputData['political_position'] = ($requestVar['do_you_hold'] == 1) ? $mixed : null;
            $inputData['political_position_dec'] = ($requestVar['do_you_hold'] == 1) ? $requestVar['political_position_dec'] : null;


            //echo $mixed; exit;
            if ($requestVar['are_you_directly']) {
                $inputData['you_related_directly'] = $requestVar['are_you_directly'];
            } else {
                $inputData['you_related_directly'] = 0;
            }


            if (isset($requestVar['related_political_position'][0]) == 3) {
                $mixed_secound = $requestVar['related_political_position'][0] . ",";
            }

            if (@$requestVar['related_political_position'][0] == 4) {
                $mixed_secound = "," . $requestVar['related_political_position'][0];
            }


            if (isset($requestVar['related_political_position'][0]) == 3 && isset($requestVar['related_political_position'][1]) == 4) {
                $mixed_secound = $requestVar['related_political_position'][0] . "," . $requestVar['related_political_position'][1];
            }


            $inputData['related_political_position'] = ($requestVar['are_you_directly'] == 1) ? $mixed_secound : null;

            $inputData['related_political_position_dec'] = ($requestVar['are_you_directly'] == 1) ? $requestVar['related_political_position_dec'] : null;



            $inputData['created_by'] = $userId;
            $inputData['updated_by'] = $userId;

           
            $datainsert = $this->userRepo->storeUseKycPersonalData($inputData, $id);
            if ($datainsert) {

                $this->userRepo->deleteDocumentType($userKycId);

                $documentData = [];

                $documentIds = @$requestVar['document_type_id' . $i];
                $documentNumbers = @$requestVar['document_number'];
                $issuanceDates = @$requestVar['issuance_date'];
                $expiryDates = @$requestVar['expiry_date'];

                $j = 1;
                for ($i = 0; $i < 4; $i++) {

                    $documentData['doc_id'] = $requestVar['document_type_id' . $j];
                    $documentData['document_number'] = $requestVar['document_number' . $i];
                    $documentData['is_monitor'] = isset($requestVar['is_monitor' . $i])? (int)$requestVar['is_monitor' . $i]:'';
                    $documentData['form_id']=1;
                    $documentData['doc_for']=1;
                    $documentData['doc_no']=isset($requestVar['doc_no' . $i])? (int)$requestVar['doc_no' . $i]:'';
                    $documentData['is_active']=1;
                    $documentData['status']=0;
                    $documentData['issuance_date'] = isset($requestVar['issuance_date' . $i]) ? Helpers::getDateByFormat($requestVar['issuance_date' . $i], 'd/m/Y', 'Y-m-d') : null;
                    $documentData['expire_date'] = isset($requestVar['expiry_date' . $i]) ? Helpers::getDateByFormat($requestVar['expiry_date' . $i], 'd/m/Y', 'Y-m-d') : null;
                    $documentData["user_kyc_id"] = $userKycId;
                    $documentData["created_by"] = $userId;
                    $documentData["updated_by"] = $userId;

                    $this->userRepo->storeUserKycDocumentTypeData($documentData,null);

                    $j++;
                }

//==== update social media link


                $this->userRepo->deleteSocialmediaInfo($userKycId);
                $socialmediaData = [];
                $socialmediaIds = $requestVar['social_media_id'];
                $socialmediaLinks = $requestVar['social_media_link'];
                $socialmediaOther = $requestVar['social_other'];
               // dd($socialmediaOther);
                if (count($socialmediaIds) > 0) {

                    foreach ($socialmediaIds as $key => $docIds) {
                        $socialmediaData['social_media'] = $docIds;
                        $socialmediaData['social_media_link'] = $socialmediaLinks[$key];
                        if($docIds == 15) {
                          $socialmediaData['social_media_other'] = $socialmediaOther[$key];
                        } else {
                             $socialmediaData['social_media_other'] = Null;
                        }
                        $socialmediaData["user_kyc_id"] = $userKycId;
                        $socialmediaData["created_by"] = $userId;
                        $socialmediaData["updated_by"] = $userId;
                        //dd($socialmediaData);
                        $this->userRepo->storeUserKycSocialmediaData($socialmediaData);
                    }
                }

                //////update kyc updated

                if ($id == null) {
                    // update kyc process status
                    $form_id = 1;
                    $upProcessData = [];
                    $upProcessData['status'] = '1';

                    $this->userRepo->updateKycProcessStatus($upProcessData, $userKycId, $form_id);
                }

                if ($inputData['family_status'] == '2') {
                    $is_family_required = '1';
                } else {
                    $is_family_required = '0';
                }

                $form_id = 2;
                $upProcessData = [];
                $upProcessData['is_required'] = $is_family_required;
                $this->userRepo->updateKycProcessStatus($upProcessData, $userKycId, $form_id);

                // check
                $chkResult = $this->userRepo->checkKycProcessComplete($userKycId);
                if ($chkResult && $chkResult->count()) {
                    $is_kyc_completed = 0;
                } else {
                    $is_kyc_completed = 1;
                }
                $dataKycUpdate['is_kyc_completed'] = $is_kyc_completed;
                $this->application->updateUserKyc($userKycId, $dataKycUpdate);

                if ($inputData['residence_status'] == '1') {
                    $is_residence_required = '1';
                } else {
                    $is_residence_required = '0';
                }

                $form_id = 3;
                $upProcessData = [];
                $upProcessData['is_required'] = $is_residence_required;
                $this->userRepo->updateKycProcessStatus($upProcessData, $userKycId, $form_id);

                
                ////end kyc complete
                
                // Indivual Corp KYC status update
                
                if($isBycompany!=0 && $corpUserId!=0){
                    
                    $this->updateIndivualCorpStatus($corpUserId);
                    
                    
                }
                

                Session::flash('message', trans('success_messages.update_personal_successfully'));

                if (@$request->save !== '' && @$request->save != null) {
                    return redirect(route('profile', ['user_kyc_id' => $userKycId, 'corp_user_id' => $corpUserId, 'is_by_company' => $isBycompany]));
                }

                if (@$request->save_next !== '' && $request->save_next != null) {
                   // echo "==>".$request->residence_status; exit;

                    //if user select non Resident and Family status(Widowed)
                    if($request->residence_status=='2' && $request->family_status=='3')
                    {
                        return redirect(route('professional_information', ['user_kyc_id' => $userKycId, 'corp_user_id' => $corpUserId, 'is_by_company' => $isBycompany]));
                     }
                     


                    return redirect(route('family_information', ['user_kyc_id' => $userKycId, 'corp_user_id' => $corpUserId, 'is_by_company' => $isBycompany]));
                }
            } else {
                Session::flash('message', trans('error_messages.update_personal_not_successfully'));
                return redirect(route('profile', ['user_kyc_id' => $userKycId, 'corp_user_id' => $corpUserId, 'is_by_company' => $isBycompany]));
            }
        } catch (Exception $ex) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($ex));
        }
    }

    /**
     * edit Family Information
     *
     * @return Response
     */
    public function editFamilyInformation(Request $request) {

        //$this->checkUserKycStatus(Auth()->user()->user_id);  //check user kyc completed and sent mail
        $flag=$this->checkUserKycStatus(Auth()->user()->user_id); //check user kyc completed or not
        
        if($flag==1){
            return redirect(route('thanks_page'));
        }
        
        $userData = [];
        $benifinary = [];
        $corp_user_id = @$request->get('corp_user_id');
        $user_kyc_id = @$request->get('user_kyc_id');

        if ($corp_user_id > 0 && $user_kyc_id > 0) {

            $benifinary['user_kyc_id'] = (int) $user_kyc_id;
            $benifinary['corp_user_id'] = (int) $corp_user_id;
            $benifinary['is_by_company'] = 1;
            $userKycId = (int) $user_kyc_id;
            $userId = null;
        } else {

            $userId = (int) Auth::user()->user_id;
            $userKycId = $this->application->getUserKycid(Auth()->user()->user_id);
            $benifinary['user_id'] = (int) Auth::user()->user_id;
            $benifinary['user_kyc_id'] = $userKycId;
            $benifinary['corp_user_id'] = 0;
            $benifinary['is_by_company'] = 0;
        }
        
        $benifinary['next_url']=route('residential_information', ['user_kyc_id' => $benifinary['user_kyc_id'], 'corp_user_id' => $benifinary['corp_user_id'], 'is_by_company' => $benifinary['is_by_company']]);
        $benifinary['prev_url']=route('profile', ['user_kyc_id' => $benifinary['user_kyc_id'], 'corp_user_id' => $benifinary['corp_user_id'], 'is_by_company' => $benifinary['is_by_company']]);

        $personalData = $this->userRepo->getUseKycPersonalData($userKycId);

        // kyc admin approve status
        //$kycApproveStatus=$this->userRepo->kycApproveStatus($userKycId);
        $kycApproveStatus=$this->userRepo->kycApproveFinalStatus($userKycId);

        
        if ($personalData->family_status == '2') {
            $resData = $this->userRepo->getFamilyInfo($userKycId);


            if ($resData && $resData->count()) {
                $userData = $resData->toArray();
            }
            return view('frontend.family_information', compact('userData', 'benifinary','kycApproveStatus'));
        } else {
            return redirect(route('residential_information', ['user_kyc_id' => $benifinary['user_kyc_id'], 'corp_user_id' => $benifinary['corp_user_id'], 'is_by_company' => $benifinary['is_by_company']]));
        }
    }

    /*     * FamilyFormRequest 
     * save Family Information
     *
     * @return Response
     */

    public function saveFamilyInformation(Request $request) {
        try {
            $requestVar = $request->all();

           



            if (@$requestVar['corp_user_id'] > 0 && @$requestVar['user_kyc_id'] > 0 && @$requestVar['is_by_company'] == 1) {
                $userId = 0;
                $userKycId = (int) $requestVar['user_kyc_id'];
                $corpUserId = (int) $requestVar['corp_user_id'];
                $isBycompany = (int) $requestVar['is_by_company'];
            } else {
                $userId = (int) Auth::user()->user_id;
                $userKycId = $this->application->getUserKycid(Auth()->user()->user_id);
                $isBycompany = 0;
                $corpUserId = 0;
            }

            $inputData = [];
            $id = (@$requestVar['id'] != '' && @$requestVar['id'] != null) ? (int) $requestVar['id'] : null;
            $inputData['user_id'] = $userId;
            $inputData['user_kyc_id'] = $userKycId;
            $inputData['spouse_f_name'] = $requestVar['spouse_f_name'];
            $inputData['spouse_m_name'] = $requestVar['spouse_m_name'];
            $inputData['is_spouse_profession'] = $requestVar['is_professional_status'];
            $inputData['spouse_profession'] = $requestVar['spouse_profession'];
            $inputData['spouse_employer'] = $requestVar['spouse_employer'];
            if (isset($requestVar['is_child'])) {
                $inputData['is_child'] = 1;
            } else {
                $inputData['is_child'] = 0;
            }

            $childInfo = [];

            if ($inputData['is_child'] != '1') {
                $childNames = $requestVar['child_name'];
                $childDOBs = $requestVar['child_dob'];
                foreach ($childNames as $key => $child_name) {
                    $inputData2 = [];
                    $inputData2['child_name'] = $child_name;
                    $inputData2['child_dob'] = $childDOBs[$key];
                    $childInfo[$key] = $inputData2;
                }
            }

            $inputData['spouce_child_info'] = json_encode($childInfo);
            $inputData['created_by'] = $userId;
            $inputData['updated_by'] = $userId;

            


            $this->userRepo->storeUseKycFamilyData($inputData, $id);
            ////kyc complete Status
            if ($id == null) {
                // update kyc process status
                $form_id = 2;
                $upProcessData = [];
                $upProcessData['status'] = '1';

                $this->userRepo->updateKycProcessStatus($upProcessData, $userKycId, $form_id);
            }

            // check
            $chkResult = $this->userRepo->checkKycProcessComplete($userKycId);
            if ($chkResult && $chkResult->count()) {
                $is_kyc_completed = 0;
            } else {
                $is_kyc_completed = 1;
            }
            $dataKycUpdate['is_kyc_completed'] = $is_kyc_completed;
            $this->application->updateUserKyc($userKycId, $dataKycUpdate);


            //////End Kyc Complete
            
            // Indivual Corp KYC status update
                
                if($isBycompany!=0 && $corpUserId!=0){
                    
                    $this->updateIndivualCorpStatus($corpUserId);
                    
                    
                }


            Session::flash('message', trans('success_messages.update_family_successfully'));

            if (@$request->save !== '' && @$request->save != null) {
                return redirect(route('family_information', ['user_kyc_id' => $userKycId, 'corp_user_id' => $corpUserId, 'is_by_company' => $isBycompany]));
            }

            if (@$request->save_next !== '' && @$request->save_next != null) {
                return redirect(route('residential_information', ['user_kyc_id' => $userKycId, 'corp_user_id' => $corpUserId, 'is_by_company' => $isBycompany]));
            }
        } catch (Exception $ex) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($ex));
        }
    }

    /**
     * edit Residential Information
     *
     * @return Response
     */
    public function editResidentialInformation(Request $request) {


        $flag=$this->checkUserKycStatus(Auth()->user()->user_id); //check user kyc completed or not
        
        if($flag==1){
            return redirect(route('thanks_page'));
        }

        $corp_user_id = @$request->get('corp_user_id');
        $user_kyc_id = @$request->get('user_kyc_id');
        //echo $user_kyc_id."==".$corp_user_id;
        $userData = [];
        $benifinary = [];

        if ($corp_user_id > 0 && $user_kyc_id > 0) {

            $benifinary['user_kyc_id'] = (int) $user_kyc_id;
            $benifinary['corp_user_id'] = (int) $corp_user_id;
            $benifinary['is_by_company'] = 1;
            $userKycId = (int) $user_kyc_id;
            $userId = null;
        } else {
            $userId = (int) Auth::user()->user_id;
            //$userKycId = (int) Auth::user()->user_kyc_id;
            $userKycId = $this->application->getUserKycid(Auth()->user()->user_id);
            $benifinary['user_kyc_id'] = $userKycId;
            $benifinary['corp_user_id'] = 0;
            $benifinary['is_by_company'] = 0;
        }
        
        $benifinary['next_url']=route('professional_information', ['user_kyc_id' => $benifinary['user_kyc_id'], 'corp_user_id' => $benifinary['corp_user_id'], 'is_by_company' => $benifinary['is_by_company']]);
        $personalData = $this->userRepo->getUseKycPersonalData($userKycId);

        if ($personalData->family_status!= '1') {

             $benifinary['prev_url']=route('family_information', ['user_kyc_id' => $benifinary['user_kyc_id'], 'corp_user_id' => $benifinary['corp_user_id'], 'is_by_company' => $benifinary['is_by_company']]);
        } else {
             $benifinary['prev_url']=route('profile', ['user_kyc_id' => $benifinary['user_kyc_id'], 'corp_user_id' => $benifinary['corp_user_id'], 'is_by_company' => $benifinary['is_by_company']]);
        }

         $resData = $this->userRepo->getResidentialInfo($userKycId);
            if ($resData && $resData->count()) {
                $userData = $resData->toArray();
            }
            // kyc admin approve status
           // $kycApproveStatus=$this->userRepo->kycApproveStatus($userKycId);
            $kycApproveStatus=$this->userRepo->kycApproveFinalStatus($userKycId);

            return view('frontend.residential_information', compact('userData', 'benifinary','kycApproveStatus'));


       
    }

    /**
     * save Residential Information
     *
     * @return Response
     */
    public function saveResidentialInformation(ResidentialFormRequest $request) {

        try {

            $requestVar = $request->all();

            // echo "<pre>";
            // print_r($requestVar);
            //  exit;
            $inputData = [];
            if ($requestVar['corp_user_id'] > 0 && $requestVar['user_kyc_id'] > 0 && $requestVar['is_by_company'] == 1) {
                $userId = 0;
                $userKycId = (int) $requestVar['user_kyc_id'];
                $corpUserId = (int) $requestVar['corp_user_id'];
                $isBycompany = (int) $requestVar['is_by_company'];
            } else {
                $userId = (int) Auth::user()->user_id;
                $userKycId = $this->application->getUserKycid(Auth()->user()->user_id);
                $isBycompany = 0;
                $corpUserId = 0;
            }

            $id = (@$requestVar['id'] != '' && @$requestVar['id'] != null) ? (int) $requestVar['id'] : null;

            $inputData['user_id'] = $userId;
            $inputData['user_kyc_id'] = $userKycId;
            $inputData['country_id'] = $requestVar['country_id'];
            $inputData['city_id'] = $requestVar['city_id'];
            $inputData['region'] = $requestVar['region'];
            $inputData['building_no'] = $requestVar['building_no'];
            $inputData['floor_no'] = $requestVar['floor_no'];
            $inputData['street_addr'] = $requestVar['street_addr'];
            $inputData['postal_code'] = $requestVar['postal_code'];
            $inputData['post_box'] = $requestVar['post_box'];
            $inputData['addr_email'] = $requestVar['addr_email'];
            $inputData['addr_phone_no'] = $requestVar['addr_phone_no'];
            $inputData['tele_country_code'] = $requestVar['tele_country_code'];
            $inputData['addr_country_code'] = $requestVar['country_code'];
            $inputData['addr_mobile_no'] = $requestVar['addr_mobile_no'];
            $inputData['fax_country_code'] = $requestVar['fax_country_code'];
            $inputData['addr_fax_no'] = $requestVar['addr_fax_no'];
            $inputData['created_by'] = $userId;
            $inputData['updated_by'] = $userId;

            // dd($inputData);

            $rs = $this->userRepo->storeUserKycResidentialData($inputData, $id);

            ////kyc complete Status
            if ($id == null) {
                // update kyc process status
                $form_id = 3;
                $upProcessData = [];
                $upProcessData['status'] = '1';

                $this->userRepo->updateKycProcessStatus($upProcessData, $userKycId, $form_id);
             }

            // check
            $chkResult = $this->userRepo->checkKycProcessComplete($userKycId);
            if ($chkResult && $chkResult->count()) {
                $is_kyc_completed = 0;
            } else {
                $is_kyc_completed = 1;
            }
            $dataKycUpdate['is_kyc_completed'] = $is_kyc_completed;
            $this->application->updateUserKyc($userKycId, $dataKycUpdate);
            //////End Kyc Complete


            // Indivual Corp KYC status update
                
                if($isBycompany!=0 && $corpUserId!=0){
                   
                        $this->updateIndivualCorpStatus($corpUserId);
                    
                    
                }


            Session::flash('message', trans('success_messages.update_residential_successfully'));
            if (@$request->save !== '' && @$request->save != null) {
                return redirect(route('residential_information', ['user_kyc_id' => $userKycId, 'corp_user_id' => $corpUserId, 'is_by_company' => $isBycompany]));
            } else if (@$request->save_next !== '' && @$request->save_next != null) {

                return redirect(route('professional_information', ['user_kyc_id' => $userKycId, 'corp_user_id' => $corpUserId, 'is_by_company' => $isBycompany]));
            }
        } catch (Exception $ex) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($ex));
        }
    }

    /**
     * edit Professional Information
     *
     * @return Response
     */
    public function editProfessionalInformation(Request $request) {
        
        $flag=$this->checkUserKycStatus(Auth()->user()->user_id); //check user kyc completed or not
        
        if($flag==1){
            return redirect(route('thanks_page'));
        }
        
        $corp_user_id = @$request->get('corp_user_id');
        $user_kyc_id = @$request->get('user_kyc_id');

        $userData = [];
        $benifinary = [];


        if ($corp_user_id > 0 && $user_kyc_id > 0) {

            $benifinary['user_kyc_id'] = (int) $user_kyc_id;
            $benifinary['corp_user_id'] = (int) $corp_user_id;
            $benifinary['is_by_company'] = 1;
            $userKycId = (int) $user_kyc_id;
            $userId = null;
        } else {
            $userId = (int) Auth::user()->user_id;
            $userKycId = $this->application->getUserKycid(Auth()->user()->user_id);
            $benifinary['user_kyc_id'] = $userKycId;
            $benifinary['corp_user_id'] = 0;
            $benifinary['is_by_company'] = 0;
        }
        
        $benifinary['next_url']=route('commercial_information', ['user_kyc_id' => $benifinary['user_kyc_id'], 'corp_user_id' => $benifinary['corp_user_id'], 'is_by_company' => $benifinary['is_by_company']]);
        $benifinary['prev_url']=route('residential_information', ['user_kyc_id' => $benifinary['user_kyc_id'], 'corp_user_id' => $benifinary['corp_user_id'], 'is_by_company' => $benifinary['is_by_company']]);
        
        $resData = $this->userRepo->getProfessionalInfo($userKycId);
        if ($resData && $resData->count()) {
            $userData = $resData->toArray();
        }
        
         // kyc admin approve status
        //$kycApproveStatus=$this->userRepo->kycApproveStatus($userKycId);
        $kycApproveStatus=$this->userRepo->kycApproveFinalStatus($userKycId);
        
        return view('frontend.professional_information', compact('userData', 'benifinary','kycApproveStatus'));



        


    }

    /**
     * save Professional Information
     *
     * @return Response
     */
    public function saveProfessionalInformation(Request $request) {
        try {

            $requestVar = $request->all();
            $inputData = [];
              //  dd($requestVar);
            if ($requestVar['corp_user_id'] > 0 && $requestVar['user_kyc_id'] > 0 && $requestVar['is_by_company'] == 1) {
                $userId = 0;
                $userKycId = (int) $requestVar['user_kyc_id'];
                $corpUserId = (int) $requestVar['corp_user_id'];
                $isBycompany = (int) $requestVar['is_by_company'];
            } else {
                $userId = (int) Auth::user()->user_id;
                $userKycId = $this->application->getUserKycid(Auth()->user()->user_id);
                $isBycompany = 0;
                $corpUserId = 0;
            }

            $id = ($requestVar['id'] != '' && $requestVar['id'] != null) ? (int) $requestVar['id'] : null;
            $inputData['user_id'] = $userId;
            $inputData['user_kyc_id'] = $userKycId;
            $inputData['prof_status'] = isset($requestVar['prof_status']) ? $requestVar['prof_status'] : '';
            $prof_status = $requestVar['prof_status'];
            //if user select Retired 
            if (isset($requestVar['prof_status']) && $prof_status == '6') {

                $inputData['prof_detail'] = isset($requestVar['prof_detail']) ? $requestVar['prof_detail'] : '';
                $inputData['position_title'] = isset($requestVar['position_title']) ? $requestVar['position_title'] : '';
                $inputData['date_retirement'] = isset($requestVar['date_retirement']) ? Helpers::getDateByFormat($requestVar['date_retirement'], 'd/m/Y', 'Y-m-d') : null;
                $inputData['last_monthly_salary'] = isset($requestVar['position_title']) ? (double) @$requestVar['last_monthly_salary'] : null;
            } else {

                $inputData['prof_detail'] = null;
                $inputData['position_title'] = null;
                $inputData['date_retirement'] = null;
                $inputData['last_monthly_salary'] = null;
            }

            //if user select employee, business and unemployee
            if (isset($requestVar['prof_status']) && $prof_status == '1'|| $prof_status == '4') {

          $inputData['profession_occu'] = isset($requestVar['prof_occupation']) ? $requestVar['prof_occupation'] : '';
          $inputData['position_job_title'] = isset($requestVar['position_job_title']) ? $requestVar['position_job_title'] : '';
                $inputData['date_employment'] = isset($requestVar['date_employment']) ? Helpers::getDateByFormat($requestVar['date_employment'], 'd/m/Y', 'Y-m-d') : null;
            } else {

                $inputData['profession_occu'] = null;
                $inputData['position_job_title'] = null;
                $inputData['date_employment'] = null;
            }

            //if user select other
            if (isset($requestVar['prof_status']) && $prof_status == '8') {
                $inputData['other_prof_status'] = isset($requestVar['other_prof_status']) ? $requestVar['other_prof_status'] : '';
            } else {
                $inputData['other_prof_status'] = null;
            }

            //if user select business owner

            if (isset($requestVar['prof_status']) && $prof_status == '3') {

                $inputData['profession_occu'] = isset($requestVar['prof_occupation_business']) ? $requestVar['prof_occupation_business'] : '';
                $inputData['position_job_title'] = isset($requestVar['position_job_title_business']) ? $requestVar['position_job_title_business'] : '';
                
            }

            $inputData['additional_activities'] = isset($requestVar['additional_activity'])? $requestVar['additional_activity']:null;
            $inputData['created_by'] = $userId;
            $inputData['updated_by'] = $userId;
            //dd($inputData);
            $this->userRepo->storeUserKycProfessionalData($inputData, $id);

            ////kyc complete Status

            if ($id == null) {
                // update kyc process status
                $form_id = 4;
                $upProcessData = [];
                $upProcessData['status'] = '1';

                $this->userRepo->updateKycProcessStatus($upProcessData, $userKycId, $form_id);
            }

            if (in_array($inputData['prof_status'], ['3', '4'])) {
                $is_required = '1';
            } else {
                $is_required = '0';
            }

            $form_id = 5;
            $upProcessData = [];
            $upProcessData['is_required'] = $is_required;
            $this->userRepo->updateKycProcessStatus($upProcessData, $userKycId, $form_id);

            // check
            $chkResult = $this->userRepo->checkKycProcessComplete($userKycId);
            if ($chkResult && $chkResult->count()) {
                $is_kyc_completed = 0;
            } else {
                $is_kyc_completed = 1;
            }
            $dataKycUpdate['is_kyc_completed'] = $is_kyc_completed;
            $this->application->updateUserKyc($userKycId, $dataKycUpdate);

            //////End Kyc Complete

            // Indivual Corp KYC status update
                
                if($isBycompany!=0 && $corpUserId!=0){
                    
                        $this->updateIndivualCorpStatus($corpUserId);
                    
                    
                }

            Session::flash('message', trans('success_messages.update_professional_successfully'));


            if (@$request->save != '' && @$request->save != null) {
                return redirect(route('professional_information', ['user_kyc_id' => $userKycId, 'corp_user_id' => $corpUserId, 'is_by_company' => $isBycompany]));
            } else if (@$request->save_next !== '' && @$request->save_next != null) {
                return redirect(route('commercial_information', ['user_kyc_id' => $userKycId, 'corp_user_id' => $corpUserId, 'is_by_company' => $isBycompany]));
            }
        } catch (Exception $ex) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($ex));
        }
    }

    /**
     * edit Commercial Information
     *
     * @return Response
     */
    public function editCommercialInformation(Request $request) {
//============================

        $flag=$this->checkUserKycStatus(Auth()->user()->user_id); //check user kyc completed or not
        
        if($flag==1){
            return redirect(route('thanks_page'));
        }
        
        $corp_user_id = @$request->get('corp_user_id');
        $user_kyc_id = @$request->get('user_kyc_id');

        $userData = [];
        $benifinary = [];

        if ($corp_user_id > 0 && $user_kyc_id > 0) {

            $benifinary['user_kyc_id'] = (int) $user_kyc_id;
            $benifinary['corp_user_id'] = (int) $corp_user_id;
            $benifinary['is_by_company'] = 1;
            $userKycId = (int) $user_kyc_id;
            $userId = null;
        } else {
            $userId = (int) Auth::user()->user_id;
            //$userKycId = (int) Auth::user()->user_kyc_id;
            $userKycId = $this->application->getUserKycid(Auth()->user()->user_id);
            $benifinary['user_kyc_id'] = $userKycId;
            $benifinary['corp_user_id'] = 0;
            $benifinary['is_by_company'] = 0;
        }
        
        $benifinary['next_url']=route('financial_information', ['user_kyc_id' => $benifinary['user_kyc_id'], 'corp_user_id' => $benifinary['corp_user_id'], 'is_by_company' => $benifinary['is_by_company']]);
        $benifinary['prev_url']=route('professional_information', ['user_kyc_id' => $benifinary['user_kyc_id'], 'corp_user_id' => $benifinary['corp_user_id'], 'is_by_company' => $benifinary['is_by_company']]);
        
        $prof_status = $this->userRepo->getProfessionalInfo($userKycId);

        // echo "<pre>";
        //print_r($prof_status); exit;


        if ($prof_status && in_array($prof_status->prof_status, ['3', '4'])) {

            $bussData = [];
            $resData = $this->userRepo->getCommercialInfo($userKycId);
            $resBussData = $this->userRepo->getBussAddrInfo($userKycId);

            /* data polulated from address details */
            $user_id= (int)Auth()->user()->user_id;
            $userKycid = $this->application->getUserKycid($user_id);
            $address = $this->application->getCompanyAddress($userKycid);
            $companyprofile = $this->application->getRegisterDetails($user_id);
            $corpRegis = $this->userRepo->getcorpDetail($user_id);
            //dd($companyprofile);
             /* data polulated end */
             
            if ($resData && $resData->count()) {
                $userData = $resData->toArray();
            }

            if ($resBussData && $resBussData->count()) {
                $bussData = $resBussData->toArray();
            }

            // kyc admin approve status
            //$kycApproveStatus=$this->userRepo->kycApproveStatus($userKycId);
            $kycApproveStatus=$this->userRepo->kycApproveFinalStatus($userKycId);

            return view('frontend.commercial_information', compact('userData', 'benifinary', 'bussData','kycApproveStatus','address','corpRegis','companyprofile'));
        } else {

            return redirect(route('financial_information', ['user_kyc_id' => $user_kyc_id, 'corp_user_id' => $corp_user_id, 'is_by_company' => $benifinary['is_by_company']]));
        }
    }

    /**
     * save Commercial Information
     *
     * @return Response
     */
    public function saveCommercialInformation(CommercialFormRequest $request) {
        try {

            $requestVar = $request->all();

            $inputData = [];

            if ($requestVar['corp_user_id'] > 0 && $requestVar['user_kyc_id'] > 0 && $requestVar['is_by_company'] == 1) {
                $userId = 0;
                $userKycId = (int) $requestVar['user_kyc_id'];
                $corpUserId = (int) $requestVar['corp_user_id'];
                $isBycompany = (int) $requestVar['is_by_company'];
            } else {
                $userId = (int) Auth::user()->user_id;
                $userKycId = $this->application->getUserKycid(Auth()->user()->user_id);
                $isBycompany = 0;
                $corpUserId = 0;
            }

            $id = ($requestVar['id'] != '' && $requestVar['id'] != null) ? (int) $requestVar['id'] : null;

            $inputData['user_id'] = $userId;
            $inputData['user_kyc_id'] = $userKycId;
            $inputData['comm_name'] = $requestVar['comm_name'];
            $inputData['date_of_establish'] = isset($requestVar['date_of_establish']) ? Helpers::getDateByFormat($requestVar['date_of_establish'], 'd/m/Y', 'Y-m-d') : null; //@$requestVar['date_of_establish'];

            $inputData['country_establish_id'] = $requestVar['country_establish_id'];
            $inputData['comm_reg_no'] = $requestVar['comm_reg_no'];
            $inputData['comm_reg_place'] = $requestVar['comm_reg_place'];
            $inputData['comm_country_id'] = $requestVar['comm_country_id'];
            $inputData['country_activity'] = implode(',',$requestVar['country_activity']);

            $inputData['syndicate_no'] = $requestVar['syndicate_no'];
            $inputData['taxation_no'] = $requestVar['taxation_no'];
            //$inputData['taxation_id'] = $requestVar['taxation_id'];
            $inputData['annual_turnover'] = $requestVar['annual_turnover'];
            $inputData['main_suppliers'] = $requestVar['main_suppliers'];
            $inputData['main_clients'] = $requestVar['main_clients'];
            $inputData['authorized_signatory'] = $requestVar['authorized_signatory'];
            $inputData['fax_no'] = $requestVar['buss_fax_no'];
            $inputData['is_hold_mail'] = $request['is_hold_mail'];
            $inputData['mailing_address'] = $requestVar['mailing_address'];
            $inputData['relation_exchange_company'] = $requestVar['relation_exchange_company'];
            $inputData['concerned_party'] = $requestVar['concerned_party'];
            $inputData['details_of_company'] = $requestVar['details_of_company'];
            $inputData['same_business'] = isset($requestVar['populatedata_checkbox'])?1:null;
            
            $inputData['created_by'] = $userId;
            $inputData['updated_by'] = $userId;
             //dd($inputData);
             

            $insertedData = $this->userRepo->storeUserKycCommercialData($inputData, $id);

            if (isset($requestVar['buss_addr_id'])) {


                $buss_addr_id = ($requestVar['buss_addr_id'] != '' && $requestVar['buss_addr_id'] != null) ? (int) $requestVar['buss_addr_id'] : null;
            } else {
                $buss_addr_id = null;
            }
            $bussData['user_kyc_id'] = $userKycId;
            $bussData['buss_country_id'] = $requestVar['buss_country_id'];
            $bussData['buss_city_id'] = $requestVar['buss_city_id'];
            $bussData['buss_region'] = $requestVar['buss_region'];
            $bussData['buss_building'] = $requestVar['buss_building'];
            $bussData['buss_floor'] = $requestVar['buss_floor'];
            $bussData['buss_street'] = $requestVar['buss_street'];
            $bussData['buss_postal_code'] = $requestVar['buss_postal_code'];
            $bussData['buss_po_box_no'] = $requestVar['buss_po_box_no'];
            $bussData['buss_email'] = $requestVar['buss_email'];
            $bussData['country_code_phone'] = $requestVar['country_code_phone'];
            $bussData['buss_telephone_no'] = $requestVar['buss_telephone_no'];
            $bussData['buss_country_code'] = $requestVar['country_code'];
            $bussData['buss_mobile_no'] = $requestVar['buss_mobile_no'];
            $bussData['country_code_fax'] = $requestVar['country_code_fax'];
            $bussData['buss_fax_no'] = $requestVar['buss_fax_no'];



            $bb = $this->userRepo->storeUserKycBussAddrData($bussData, $buss_addr_id);

           // die;
            ////kyc complete Status

            if ($id == null) {
                // update kyc process status
                $form_id = 5;
                $upProcessData = [];
                $upProcessData['status'] = '1';

                $this->userRepo->updateKycProcessStatus($upProcessData, $userKycId, $form_id);
            }

            // check
            $chkResult = $this->userRepo->checkKycProcessComplete($userKycId);
            if ($chkResult && $chkResult->count()) {
                $is_kyc_completed = 0;
            } else {
                $is_kyc_completed = 1;
            }
            $dataKycUpdate['is_kyc_completed'] = $is_kyc_completed;
            $this->application->updateUserKyc($userKycId, $dataKycUpdate);

            //////End Kyc Complete

            // Indivual Corp KYC status update
                
                if($isBycompany!=0 && $corpUserId!=0){
                    
                    $this->updateIndivualCorpStatus($corpUserId);
                    
                    
                }


            Session::flash('message', trans('success_messages.update_comercial_successfully'));
            if ($request->save !== '' && $request->save != null) {
                return redirect(route('commercial_information', ['user_kyc_id' => $userKycId, 'corp_user_id' => $corpUserId, 'is_by_company' => $isBycompany]));
            } else if (@$request->save_next !== '' && $request->save_next != null) {

                return redirect(route('financial_information', ['user_kyc_id' => $userKycId, 'corp_user_id' => $corpUserId, 'is_by_company' => $isBycompany]));
            }
        } catch (Exception $ex) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($ex));
        }
    }

    /**
     * edit Financial Information
     *
     * @return Response
     */
    public function editFinancialInformation(Request $request) {

        $flag=$this->checkUserKycStatus(Auth()->user()->user_id); //check user kyc completed or not
        
        if($flag==1){
            return redirect(route('thanks_page'));
        }
        
        $corp_user_id = @$request->get('corp_user_id');
        $user_kyc_id = @$request->get('user_kyc_id');

        $userData = [];
        $benifinary = [];


        if ($corp_user_id > 0 && $user_kyc_id > 0) {

            $benifinary['user_kyc_id'] = (int) $user_kyc_id;
            $benifinary['corp_user_id'] = (int) $corp_user_id;
            $benifinary['is_by_company'] = 1;
            $userKycId = (int) $user_kyc_id;
            $userId = null;
        } else {
            $userId = (int) Auth::user()->user_id;
            $userKycId = $this->application->getUserKycid(Auth()->user()->user_id);
            $benifinary['user_kyc_id'] = $userKycId;
            $benifinary['corp_user_id'] = 0;
            $benifinary['is_by_company'] = 0;
        }
        $prof_status = $this->userRepo->getProfessionalInfo($userKycId);

        // echo "<pre>";
        //print_r($prof_status); exit;


        if ($prof_status && in_array($prof_status->prof_status, ['3', '4'])) {
            $benifinary['prev_url']=route('commercial_information', ['user_kyc_id' => $benifinary['user_kyc_id'], 'corp_user_id' => $benifinary['corp_user_id'], 'is_by_company' => $benifinary['is_by_company']]);
        }else{
            $benifinary['prev_url']=route('professional_information', ['user_kyc_id' => $benifinary['user_kyc_id'], 'corp_user_id' => $benifinary['corp_user_id'], 'is_by_company' => $benifinary['is_by_company']]);
        }
        $benifinary['next_url']=route('upload_document', ['user_kyc_id' => $benifinary['user_kyc_id'], 'corp_user_id' => $benifinary['corp_user_id'], 'is_by_company' => $benifinary['is_by_company']]);
        
        $resData = [];
        $resData = $this->userRepo->getFinancialInfo($userKycId);


        if ($resData) {
            $userData = $resData->toArray();
        }


        // kyc admin approve status
        //$kycApproveStatus=$this->userRepo->kycApproveStatus($userKycId);
        $kycApproveStatus=$this->userRepo->kycApproveFinalStatus($userKycId);


        return view('frontend.financial_information', compact('userData', 'benifinary','kycApproveStatus'));
    }

    /**
     * save Financial Information
     *
     * @return Response
     */
    public function saveFinancialInformation(FinancialFormRequest $request) {
        try {


            $requestVar = $request->all();
            //dd($requestVar);
            $inputData = [];

            if ($requestVar['corp_user_id'] > 0 && $requestVar['user_kyc_id'] > 0 && $requestVar['is_by_company'] == 1) {
                $userId = (int) $requestVar['corp_user_id'];
                $userKycId = (int) $requestVar['user_kyc_id'];
                $corpUserId = (int) $requestVar['corp_user_id'];
                $isBycompany = (int) $requestVar['is_by_company'];
            } else {
                $userId = (int) Auth::user()->user_id;
                $userKycId = $this->application->getUserKycid(Auth()->user()->user_id);
                $isBycompany = 0;
                $corpUserId = 0;
            }
            $id = ($requestVar['id'] != '' && $requestVar['id'] != null) ? (int) $requestVar['id'] : null;
            $inputData['user_id'] = $userId;
            $inputData['user_kyc_id'] = $userKycId;
            $inputData['source_funds'] = isset($requestVar['source_funds']) ? implode(',', $requestVar['source_funds']) : null;


            $inputData['other_source'] = ($requestVar['other_source'] != '' && $requestVar['other_source'] != null) ? $requestVar['other_source'] : '';

            $inputData['jurisdiction_funds'] = isset($requestVar['jurisdiction_funds'])? implode(',', $requestVar['jurisdiction_funds']):null;
            $inputData['annual_income'] = (int) $requestVar['annual_income'];
            $inputData['estimated_wealth'] = $requestVar['estimated_wealth'];
            $inputData['wealth_source'] = isset($requestVar['wealth_source']) ? implode(',', $requestVar['wealth_source']) : null;
            $inputData['other_wealth_source'] = ($requestVar['other_wealth_source'] != '' && $requestVar['other_wealth_source'] != null) ? $requestVar['other_wealth_source'] : '';

            $inputData['us_fetch_regulation'] = $requestVar['us_fetch_regulation'];
            $usfetchVal = $requestVar['us_fetch_regulation'];

            $inputData['please_specify'] = ($usfetchVal == 1) ? $requestVar['please_specify'] : null;
            $inputData['tin_code'] = ($usfetchVal == 1) ? $requestVar['tin_code'] : null;




            $inputData['is_abandoned'] = ($usfetchVal == 0) ? $requestVar['is_abandoned'] : null;
            $inputData['tin_country_name'] = ($usfetchVal == 0) ? $requestVar['tin_country_name'] : null;


            if ($usfetchVal == 0) {
                $inputData['tin_number'] = isset($requestVar['tin_number']) ? $requestVar['tin_number'] : null;
                $inputData['not_applicable'] = isset($requestVar['not_applicable_tincode']) ? 1 : 0;
            } else {
                $inputData['tin_number'] = null;
                $inputData['not_applicable'] = 0;
            }
            if ($usfetchVal == 0 && $requestVar['is_abandoned'] == 1) {
                $inputData['date_of_abandonment'] = isset($requestVar['date_of_abandonment']) ? Helpers::getDateByFormat($requestVar['date_of_abandonment'], 'd/m/Y', 'Y-m-d') : null;
                $inputData['abandonment_reason'] = $requestVar['abandonment_reason'];
            } else {
                $inputData['date_of_abandonment'] = null;
                $inputData['abandonment_reason'] = null;
            }

//echo "<pre>";
//print_r($inputData); exit;

            $inputData['created_by'] = $userId;
            $inputData['updated_by'] = $userId;
            //dd($inputData);
            $re = $this->userRepo->storeUserKycFinancialData($inputData, $id);

            ////kyc complete Status

            if ($id == null) {
                // update kyc process status
                $form_id = 6;
                $upProcessData = [];
                $upProcessData['status'] = '1';

                $this->userRepo->updateKycProcessStatus($upProcessData, $userKycId, $form_id);
            }

            // check
            $chkResult = $this->userRepo->checkKycProcessComplete($userKycId);
            if ($chkResult && $chkResult->count()) {
                $is_kyc_completed = 0;
            } else {
                $is_kyc_completed = 1;
            }
            $dataKycUpdate['is_kyc_completed'] = $is_kyc_completed;
            $this->application->updateUserKyc($userKycId, $dataKycUpdate);

            //////End Kyc Complete

            // Indivual Corp KYC status update
                
                if($isBycompany!=0 && $corpUserId!=0){
                    
                    $this->updateIndivualCorpStatus($corpUserId);
                    
                    
                }

            Session::flash('message', trans('success_messages.update_financial_successfully'));

            if (@$request->save !== '' && @$request->save != null) {
                return redirect(route('financial_information', ['user_kyc_id' => $userKycId, 'corp_user_id' => $corpUserId, 'is_by_company' => $isBycompany]));
            } else if (@$request->save_next !== '' && @$request->save_next != null) {

                return redirect(route('upload_document', ['user_kyc_id' => $userKycId, 'corp_user_id' => $corpUserId, 'is_by_company' => $isBycompany]));
            }
        } catch (Exception $ex) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($ex));
        }
    }

    /**
     * edit Documents
     *
     * @return Response
     */
    public function editDocuments(Request $request) {
        //dd($request);
        try {
            
            $flag=$this->checkUserKycStatus(Auth()->user()->user_id); //check user kyc completed or not
        
            if($flag==1){
                return redirect(route('thanks_page'));
            }
            
            $corp_user_id = $request->get('corp_user_id');
            $user_kyc_id = $request->get('user_kyc_id');

            $userData = [];
            $benifinary = [];
            $userData = [];
            $benifinary = [];


            if ($corp_user_id > 0 && $user_kyc_id > 0) {

                $benifinary['user_kyc_id'] = (int) $user_kyc_id;
                $benifinary['corp_user_id'] = (int) $corp_user_id;
                $benifinary['is_by_company'] = 1;
                $userKycId = (int) $user_kyc_id;
                $userId = $corp_user_id;
            } else {
                $userId = (int) Auth::user()->user_id;
                $userKycId = $this->application->getUserKycid(Auth()->user()->user_id);
                $benifinary['user_kyc_id'] = $userKycId;
                $benifinary['corp_user_id'] = 0;
                $benifinary['is_by_company'] = 0;
            }
            $benifinary['prev_url']=route('financial_information', ['user_kyc_id' => $benifinary['user_kyc_id'], 'corp_user_id' => $benifinary['corp_user_id'], 'is_by_company' => $benifinary['is_by_company']]);



            $documentArray = $this->application->corporateDocument($userKycId); //corp doc required

            // kyc admin approve status
            
           // $kycApproveStatus=$this->userRepo->kycApproveStatus($userKycId);
            $kycApproveStatus=$this->userRepo->kycApproveFinalStatus($userKycId);
            
            $arrDoc_no=[2];
            $resMstDoc  =   $this->userRepo->getMonitorDocByDocNo($arrDoc_no,$userKycId);
        
            return view('frontend.upload_document', compact('userData', 'benifinary', 'documentArray','kycApproveStatus','resMstDoc'));
        } catch (Exception $ex) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($ex));
        }
    }
    
     /**
     * edit Documents
     *
     * @return Response
     */
    public function editOtherDocuments(Request $request) {
        //dd($request);
        try {
            
            
            $corp_user_id = $request->get('corp_user_id');
            $user_kyc_id = $request->get('user_kyc_id');

            $userData = [];
            $benifinary = [];
            $userData = [];
            $benifinary = [];


            if ($corp_user_id > 0 && $user_kyc_id > 0) {

                $benifinary['user_kyc_id'] = (int) $user_kyc_id;
                $benifinary['corp_user_id'] = (int) $corp_user_id;
                $benifinary['is_by_company'] = 1;
                $userKycId = (int) $user_kyc_id;
                $userId = $corp_user_id;
            } else {
                $userId = (int) Auth::user()->user_id;
                $userKycId = $this->application->getUserKycid(Auth()->user()->user_id);
                $benifinary['user_kyc_id'] = $userKycId;
                $benifinary['corp_user_id'] = 0;
                $benifinary['is_by_company'] = 0;
            }


            

            $documentArray = $this->application->otherReqDocument($userKycId); //corp doc required
            
            // kyc admin approve status
            
            //$kycApproveStatus=$this->userRepo->kycApproveStatus($userKycId);
            $kycApproveStatus=$this->userRepo->kycApproveFinalStatus($userKycId);
        
            return view('frontend.upload_other_document', compact('userData', 'benifinary', 'documentArray','kycApproveStatus'));
        } catch (Exception $ex) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($ex));
        }
    }
    
    //
    public function saveOtherDocuments(Request $request){
        $userid = Auth()->user()->user_id;
        $res = '';

        try {
            $requestVar = $request->all();
        
            $resdata = [];
            if ($requestVar['corp_user_id'] > 0 && $requestVar['user_kyc_id'] > 0 && $requestVar['is_by_company'] == 1) {
                $userId = (int) $requestVar['corp_user_id'];
                $userKycId = (int) $requestVar['user_kyc_id'];
                $corpUserId = (int) $requestVar['corp_user_id'];
                $isBycompany = (int) $requestVar['is_by_company'];
            } else {
                $userId = (int) Auth::user()->user_id;
                $userKycId = $this->application->getUserKycid(Auth()->user()->user_id);
                $isBycompany = 0;
                $corpUserId = 0;
            }
            // validation
            $arrDocId = [];
            $i = 0;
            $isError = 0;
            $errorMsg = [];
            $arrExt = [];
            $j = 0;
            
            //$maxSize=20971520; // 20mb
            $maxSize = config('common.USER_PROFILE_MAX_SIZE');
            //$extenstion=['png','jpeg','jpg','doc','docx','pdf'];//PDF,JPEG,PNG,DOC,DOCX
            if($request->file()){
                foreach ($request->file() as $keyArrayMain) {

                    foreach ($keyArrayMain as $key => $doc) {
                        $keyArray = explode("#", $key);
                        if (!in_array($keyArray[0], $arrDocId)) {
                            $arrDocId[$i] = $keyArray[0];
                            $i++;
                        }
                        
                        $error=[];
                        foreach ($doc as $row) {
                           
                            $ext      = $row->getClientOriginalExtension();
                            $fileSize = $row->getSize();;
                            if(!in_array($ext,config('common.CHECK_EXTENTION'))){
                              $isError = 1;
                              $error[]   =   trans('common.error_messages.only_ext_allowed');  
                            }
                            
                            if($fileSize > config('common.USER_PROFILE_MAX_SIZE') ){
                              $isError = 1;
                              $error[]  =   trans('common.error_messages.max_20mb_file');  
                            }
                          

                        } 
                        
                        if(count($error)){
                            $errms=implode('<br>',$error);
                            $errorMsg['doc_' . $keyArray[0]] = $errms;
                        }
                        
                    }
                }
            }
            
            $requiredDocsList = $this->application->getRequiredOtherDocsList($userKycId);
            
            if (is_array($requiredDocsList) && count($requiredDocsList)) {
                foreach ($requiredDocsList as $key => $req_doc_id) {
                    if (!in_array($key, $arrDocId)) {
                        $isError = 1;
                        $errorMsg['doc_' . $key] = trans('common.error_messages.req_this_field');
                    }
                }
            }

            
        
            if($isError == 1){
                
                $resdata['status'] = 'error';
                $resdata['is_array'] = 1;
                $resdata['message'] = $errorMsg;
                //$resdata['ext'] = $arrExt;
                $resdata['redirect'] = '';
                
                return json_encode($resdata);
                
            }

            //$kycStatus = Userkyc::kycStatus($userid); //check kyc completed or not.  

            if ($request->file()) {
                foreach ($request->file() as $keyArrayMain) {

                    foreach ($keyArrayMain as $key => $doc) {
                        $keyArray = explode("#", $key);
                        $user_req_doc_id = $keyArray[0];
                        $user_id = $keyArray[1];
                        $doc_id = $keyArray[2];

                        foreach ($doc as $row) {

                            $docname = $row->getClientOriginalName();
                            $certificate = basename($row->getClientOriginalName());
                            $certificate = pathinfo($certificate, PATHINFO_FILENAME);
                            $ext = $row->getClientOriginalExtension();
                            $fileSize = $row->getClientSize();

                            
                                $userBaseDir = 'appDocs/Document/indivisual/pdf/' . $user_id;
                                $userFileName = $docname;
                                $pathName = $row->getPathName();

                                $this->storage->engine()->put($userBaseDir . DIRECTORY_SEPARATOR . $userFileName, File::get($pathName));
                                // Delete the temporary file
                                File::delete($pathName);
                            
                            //store data in array
                            $array = [];
                            $array1 = [];
                            $array1['user_req_doc_id'] = $user_req_doc_id;
                            $array1['doc_type'] = 1;
                            $array1['user_kyc_id'] = $user_id;
                            $array1['user_id'] = Auth()->user()->user_id;
                            $array1['doc_id'] = $doc_id;

                            $array1['doc_name'] = $certificate;
                            $array1['doc_ext'] = $ext;
                            $array1['doc_status'] = 1;
                            $array1['enc_id'] = md5(rand(1, 9999));
                            $array1['created_by'] = Auth()->user()->user_id;
                            $array1['updated_by'] = Auth()->user()->user_id;

                            $array['updated_by'] = $user_id;
                            $array['is_upload'] = 1;
                            $result = Document::create($array1);
                        }
                        $res = UserReqDoc::where('user_req_doc_id', $user_req_doc_id)->update($array);
                        
                    }
                }

                
                
            } else {
                if ($isBycompany == 1) {
                    Session::flash('message', trans('success_messages.update_documents_successfully'));

                    $resdata['status'] = 'success';
                    $resdata['message'] = 'success';
                    $resdata['redirect'] = route('shareholding_structure');
                } else {

                    $resdata['status'] = 'success';
                    $resdata['message'] = 'success';
                    
                    Session::flash('message', trans('success_messages.update_documents_successfully'));
                    $resdata['redirect'] = route('upload_other_document', ['user_kyc_id' => $userKycId, 'corp_user_id' => $corpUserId, 'is_by_company' => $isBycompany]);
                    
                }
            }
            if ($res) {
                
                if ($isBycompany == 1) {
                    Session::flash('message', trans('success_messages.update_documents_successfully'));

                    $resdata['status'] = 'success';
                    $resdata['message'] = 'success';
                    $resdata['redirect'] = route('shareholding_structure');
                } else {
                    $resdata['status'] = 'success';
                    $resdata['message'] = 'success';
                   
                    Session::flash('message', trans('success_messages.update_documents_successfully'));
                     $resdata['redirect'] = route('upload_other_document', ['user_kyc_id' => $userKycId, 'corp_user_id' => $corpUserId, 'is_by_company' => $isBycompany]);
                    
                }
            } else {
                if ($isBycompany == 1) {

                    $resdata['status'] = 'success';
                    $resdata['message'] = 'success';
                    $resdata['redirect'] = route('shareholding_structure');
                } else {
                    $resdata['status'] = 'error';
                    $resdata['is_array'] = 0;
                    $resdata['message'] = 'error';
                    $resdata['redirect'] = '';
                }
            }
            return json_encode($resdata);
        } catch (Exception $ex) {
            $resdata['status'] = 'error';
            $resdata['is_array'] = 0;
            $resdata['message'] = Helpers::getExceptionMessage($ex);
            $resdata['redirect'] = '';
            return json_encode($resdata);
        }
    }
    /**
     * save Documents
     *
     * @return Response
     */
    public function saveDocuments(Request $request) {
        $userid = Auth()->user()->user_id;
        $res = '';
        try {
            $requestVar = $request->all();
            $checkboxval = '';
            $checkboxval = $requestVar['checkboxval'];
            $resdata = [];
            if($requestVar['corp_user_id'] > 0 && $requestVar['user_kyc_id'] > 0 && $requestVar['is_by_company'] == 1) {
                $userId = (int) $requestVar['corp_user_id'];
                $userKycId = (int) $requestVar['user_kyc_id'];
                $corpUserId = (int) $requestVar['corp_user_id'];
                $isBycompany = (int) $requestVar['is_by_company'];
            } else {
                $userId = (int) Auth::user()->user_id;
                $userKycId = $this->application->getUserKycid(Auth()->user()->user_id);
                $isBycompany = 0;
                $corpUserId = 0;
            }
            
            // validation
            $arrDocId = [];
            $i = 0;
            $isError = 0;
            $errorMsg = [];
            $j = 0;
            //$maxSize=20971520; // 20mb
            $maxSize = config('common.USER_PROFILE_MAX_SIZE');
           // $extenstion=['png','jpeg','jpg','doc','docx','pdf'];//PDF,JPEG,PNG,DOC,DOCX
            $extenstion = config('common.CHECK_EXTENTION');
            
            if($request->file()){
                foreach ($request->file() as $keyArrayMain) {

                    foreach ($keyArrayMain as $key => $doc) {
                        $keyArray = explode("#", $key);
                        if (!in_array($keyArray[0], $arrDocId)) {
                            $arrDocId[$i] = $keyArray[0];
                            $i++;
                        }
                        
                        $error=[];
                        foreach ($doc as $row) {
                            $ext      = $row->getClientOriginalExtension();
                            $fileSize = $row->getSize(); //
                            if(!in_array($ext,config('common.CHECK_EXTENTION'))){
                              $isError = 1;
                              $error[]   =   trans('common.error_messages.only_ext_allowed');  
                            }
                            
                            if($fileSize> config('common.USER_PROFILE_MAX_SIZE') ){
                              $isError = 1;
                              $error[]  =   trans('common.error_messages.max_20mb_file');  
                            }
                        } 
                        
                        if(count($error)){
                            $errms=implode('<br>',$error);
                            $errorMsg['doc_' . $keyArray[0]] = $errms;
                        }
                        
                    }
                }
            }
            
            $requiredDocsList = $this->application->getRequiredDocsList($userKycId);
            if(is_array($requiredDocsList) && count($requiredDocsList)){
                foreach ($requiredDocsList as $key => $req_doc_id) {
                    if (!in_array($key, $arrDocId)) {
                        $isError = 1;
                        $errorMsg['doc_' . $key] = trans('common.error_messages.req_this_field');
                    }
                }
            }
           
            //for term and condition
            $requiredTermCondi = $this->userRepo->find($userid);
          
            if($checkboxval == 0 && $isBycompany == 0) {
                $isError = 1;
                $errorMsg['termError'] = trans('common.error_messages.req_this_field');
            }
            //============
            //$issuance_date      =   isset($requestVar['issuance_date']) ? Helpers::getDateByFormat($requestVar['issuance_date'], 'd/m/Y', 'Y-m-d') : null;
           // $issuance_date      =   isset($requestVar['document_number']) ? $requestVar['document_number'] : null;

           //die;
            $rules      =   [];
            $messages   =   [];
            $rules['issuance_date']     = 'required';
            $rules['document_number']   = 'required';
            
            $messages['issuance_date.required'] = trans('common.error_messages.req_this_field');
            $messages['document_number.required'] = trans('common.error_messages.req_this_field');
            
            $validator = Validator::make($request->all(), $rules, $messages);

            if($validator->fails()) {
                $isError = 1;
                
                $customMessages = $validator->getMessageBag()->toArray();
                foreach ($customMessages as $key => $msg) {
                    $errorMsg['error'.$key] = $msg;

                }
                
               /* if(!isset($errorMsg['issuance_date'])){
                    $issuance_date=Helpers::getDateByFormat($requestVar['issuance_date'], 'd/m/Y', 'Y-m-d');
                    
                    $today  =   date('Y-m-d');
                    $days=Helpers::dateDiffInDays($issuance_date,$today);
                    
                    if($days>=90){
                        $errorMsg['errorissuance_date'] = trans('common.error_messages.issue_date_max');
                    }
                    
                }*/
            }else{
               /* $issuance_date=Helpers::getDateByFormat($requestVar['issuance_date'], 'd/m/Y', 'Y-m-d');
                    
                $today  =   date('Y-m-d');
                $days=Helpers::dateDiffInDays($issuance_date,$today);
                    
                if($days>=90){
                    $isError = 1;
                    $errorMsg['errorissuance_date'] = trans('common.error_messages.issue_date_max');//;
                }*/
            }


            if($isError == 1){
                
                $resdata['status'] = 'error';
                $resdata['is_array'] = 1;
                $resdata['message'] = $errorMsg;

                $resdata['ext'] = $extenstion;
                $resdata['size'] = config('common.USER_PROFILE_MAX_SIZE');

                $resdata['redirect'] = '';
                return json_encode($resdata);
            }

            $kycStatus = Userkyc::kycStatus($userid); //check kyc completed or not.  

            if($request->file()){
                foreach ($request->file() as $keyArrayMain) {
                    foreach ($keyArrayMain as $key => $doc) {
                        $keyArray = explode("#", $key);
                        $user_req_doc_id = $keyArray[0];
                        $user_id = $keyArray[1];
                        $doc_id = $keyArray[2];

                        foreach ($doc as $row) {

                            $docname = $row->getClientOriginalName();
                            $certificate = basename($row->getClientOriginalName());
                            $certificate = pathinfo($certificate, PATHINFO_FILENAME);
                            $ext = $row->getClientOriginalExtension();
                            $fileSize = $row->getClientSize();

                            
                                $userBaseDir = 'appDocs/Document/indivisual/pdf/' . $user_id;
                                $userFileName = $docname;
                                $pathName = $row->getPathName();

                                $this->storage->engine()->put($userBaseDir . DIRECTORY_SEPARATOR . $userFileName, File::get($pathName));
                                // Delete the temporary file
                                File::delete($pathName);
                            
                            //store data in array
                            $array = [];
                            $array1 = [];
                            $array1['user_req_doc_id'] = $user_req_doc_id;
                            $array1['doc_type'] = 1;
                            $array1['user_kyc_id'] = $user_id;
                            $array1['user_id'] = Auth()->user()->user_id;
                            $array1['doc_id'] = $doc_id;

                            $array1['doc_name'] = $certificate;
                            $array1['doc_ext'] = $ext;
                            $array1['doc_status'] = 1;
                            $array1['enc_id'] = md5(rand(1, 9999));
                            $array1['created_by'] = Auth()->user()->user_id;
                            $array1['updated_by'] = Auth()->user()->user_id;
                            //$array1['created_by'] = Auth()->user()->user_id;
                            $array['updated_by'] = $user_id;
                            $array['is_upload'] = 1;
                            $result = Document::create($array1);
                        }
                        $res = UserReqDoc::where('user_req_doc_id', $user_req_doc_id)->update($array);
                         if ($isBycompany == 0) {
                        UserModel::where('user_id', Auth()->user()->user_id)->update(['is_term_condition'=>1]);
                        }
                    }
                }

                ////kyc complete Status
                // check all required documents uploaded
                $isUploaded = $this->application->checkAllDocUploaded($userKycId);
                if ($isUploaded) {
                    $form_id = 7;

                    $upProcessData = [];
                    $upProcessData['status'] = '1';

                    $this->userRepo->updateKycProcessStatus($upProcessData, $userKycId, $form_id);
                }




                // check
                $chkResult = $this->userRepo->checkKycProcessComplete($userKycId);
                if ($chkResult && $chkResult->count()) {
                    $is_kyc_completed = 0;
                } else {
                    $is_kyc_completed = 1;
                }
                $dataKycUpdate['is_kyc_completed'] = $is_kyc_completed;
                $this->application->updateUserKyc($userKycId, $dataKycUpdate);
                //////End Kyc Complete
                
                // Indivual Corp KYC status update
                
                if($isBycompany!=0 && $corpUserId!=0){
                    
                        $this->updateIndivualCorpStatus($corpUserId);
                    
                    
                }
                
            } else {
                if ($isBycompany == 1) {
                    Session::flash('message', trans('success_messages.update_documents_successfully'));

                    $resdata['status'] = 'success';
                    $resdata['message'] = 'success';
                    $resdata['redirect'] = route('shareholding_structure');
                } else {

                    $resdata['status'] = 'success';
                    $resdata['message'] = 'success';
                    if ($kycStatus == 1) {

                        $resdata['redirect'] = route('upload_document', ['user_kyc_id' => $userKycId, 'corp_user_id' => $corpUserId, 'is_by_company' => $isBycompany]);
                    } else {
                        Session::flash('message', trans('success_messages.update_documents_successfully'));
                        $resdata['redirect'] = route('upload_document', ['user_kyc_id' => $userKycId, 'corp_user_id' => $corpUserId, 'is_by_company' => $isBycompany]);
                    }
                }
            }
            
            //====== update utility bill issue and expiry info=====
            $arrDoc_no=[2];
            
            $resMstDoc  =   $this->userRepo->getMonitorDocByDocNo($arrDoc_no,$userKycId);
                
            if($resMstDoc && $resMstDoc->count()){
                foreach($resMstDoc as $resDoc){

                    $documentData['document_number']    =   $requestVar['document_number'];
                    $documentData['issuance_date']      =   isset($requestVar['issuance_date']) ? Helpers::getDateByFormat($requestVar['issuance_date'], 'd/m/Y', 'Y-m-d') : null;
                    
                    if($documentData['issuance_date']!=null){
                        $today          =   $documentData['issuance_date'];
                        $day            =   90;
                        $expire_date    =   date('Y-m-d',strtotime('+ 1 year',strtotime($today)));
                        $documentData['expire_date']        =   $expire_date;
                    }

                    $documentData["updated_by"]         =   (int) Auth::user()->user_id;
                    $documentData["updated_at"]         =   date('Y-m-d H:i:s');
                    
                    $this->userRepo->storeUserKycDocumentTypeData($documentData,$resDoc->id);

                }



            }
            
            //===========================================
           
            
            if ($res) {
                
                if ($isBycompany == 1) {
                    Session::flash('message', trans('success_messages.update_documents_successfully'));

                    $resdata['status'] = 'success';
                    $resdata['message'] = 'success';
                    $resdata['redirect'] = route('shareholding_structure');
                } else {
                    $resdata['status'] = 'success';
                    $resdata['message'] = 'success';
                    
                    if ($kycStatus == 1) {
                       $resdata['redirect'] = route('upload_document', ['user_kyc_id' => $userKycId, 'corp_user_id' => $corpUserId, 'is_by_company' => $isBycompany]);
                    } else {
                        Session::flash('message', trans('success_messages.update_documents_successfully'));
                        $resdata['redirect'] = route('upload_document', ['user_kyc_id' => $userKycId, 'corp_user_id' => $corpUserId, 'is_by_company' => $isBycompany]);
                    }
                }
            } else {
                if ($isBycompany == 1) {

                    $resdata['status'] = 'success';
                    $resdata['message'] = 'success';
                    $resdata['redirect'] = route('shareholding_structure');
                } else {
                    $resdata['status'] = 'success';
                    $resdata['message'] = 'success';
                    $resdata['redirect'] = '';
                }
            }
            return json_encode($resdata);
        } catch (Exception $ex) {
            $resdata['status'] = 'error';
            $resdata['is_array'] = 0;
            $resdata['message'] = Helpers::getExceptionMessage($ex);
            $resdata['redirect'] = '';
            return json_encode($resdata);
        }
    }

    //download documents

    public function IndivisualDocDownload(Request $request) {
        $documentHash = $request->get('enc_id');

        $docList = $this->application->getSingleDocument($documentHash);
        //dd($docList);
        $userID = $docList->user_kyc_id;
        //$userID = $docList->user_id;
        $fileName = $docList->doc_name . "." . $docList->doc_ext;
       // echo $userID; exit;
        $file = storage_path('app/appDocs/Document/indivisual/pdf/' . $userID . "/" . $fileName);
        //return response()->download($file);
        ////////////////// new code /////////
        $mimetype = mime_content_type($file);
        //echo $mimetype; exit;
        //ob_end_clean();
        //return response()->download($file);
        $data = file_get_contents(storage_path('app/appDocs/Document/indivisual/pdf/' . $userID . "/" . $fileName));
        //echo $data; exit;
        ob_end_clean();
        header('Content-Type: "'.$mimetype.'"');
        header('Content-Disposition: attachment; filename="'.$fileName.'"');
        header("Content-Transfer-Encoding: binary");
        header('Expires: 0'); header('Pragma: no-cache');
        header("Content-Length: ".strlen($data));
        echo $data;
        exit;

        //////////////////
    }

    //===========================================================

    /**
     * Open popup of myaccount
     *
     * @return Response
     */
    public function myAccoutPopup() {
        return view('framePopup.myAccount');
    }

    public function checkUserKycStatus($userId) {
        if (!is_int((int) $userId) && $userId != 0) {
            throw new InvalidDataTypeExceptions(trans('error_message.invalid_data_type'));
        }

        $kycStatus = Userkyc::kycStatus($userId); //check kyc completed.
        $userMailStatus = UserModel::where('user_id', (int) $userId)->first(); //check  kyc completed sent mail.
        $isThankYou=0;
        if($kycStatus == 1){
            if ($userMailStatus->kyc_completed_sent_mail == 0) {
                $userArr = [];
                $userArr = $this->userRepo->find($userId, ['email', 'first_name', 'last_name']);

                $verifyUserArr = [];
                $verifyUserArr['name'] = $userArr->f_name . ' ' . $userArr->l_name;
                $verifyUserArr['email'] = $userArr->email;
                Event::dispatch("user.email.kycCompletedMail", serialize($verifyUserArr));

                // for compliance Admin sent mail
                 $compliance_admin_email = config('common.ADMIN_EMAIL');// Compliance admin email
                 $userCheckArr = $this->userRepo->getfullUserDetail($userId);;
                 $userMailArr['email'] =   $compliance_admin_email;
                 $userMailArr['name'] =    $userCheckArr->f_name . ' ' . $userCheckArr->l_name;;
                 $userMailArr['user_type'] =  (int)Auth()->user()->user_type;
                 Event::dispatch("sent.admin.kyc.complete.notification", serialize($userMailArr));
                 UserModel::where('user_id', (int) $userId)->update(['kyc_completed_sent_mail' => 1]);
                 $isThankYou=1;
            }
        }
        
        return $isThankYou;
    }

    public function thanksPage(Request $request) {


        $corp_user_id = $request->get('corp_user_id');
        $user_kyc_id = $request->get('user_kyc_id');

        $recentRights = [];
        $benifinary = [];
        $userPersonalData = [];
        $userDocumentType = [];
        $userSocialMedia = [];

        if ($corp_user_id > 0 && $user_kyc_id > 0) {

            $benifinary['user_kyc_id'] = (int) $user_kyc_id;
            $benifinary['corp_user_id'] = (int) $corp_user_id;
            $benifinary['is_by_company'] = 1;
            $userKycId = (int) $user_kyc_id;
            $userId = null;
        } else {
            $userId = (int) Auth::user()->user_id;
            $userKycId = $this->application->getUserKycid(Auth()->user()->user_id);
            $benifinary['user_kyc_id'] = $userKycId;
            $benifinary['corp_user_id'] = 0;
            $benifinary['is_by_company'] = 0;
        }
        $benifinary['user_type'] = (int) Auth::user()->user_type;
        $benifinary['user'] = $this->userRepo->find(Auth::user()->user_id);
        return view('frontend.thanks', compact('benifinary'));
    }

    public function emailexist(Request $request) {

        echo $request->userdata;
    }
    
    
    
    public function updateIndivualCorpStatus($corpUserId){
        $userKycId = $this->application->getUserKycid($corpUserId);
        $notAll=$this->userRepo->isCorpIndvsKycCompleted($corpUserId);
        if($notAll==0){
            $form_id = 10;
            $upProcessData = [];
            $upProcessData['status'] = '1';
            $this->userRepo->updateKycProcessStatus($upProcessData, $userKycId, $form_id);
        }else{
            $form_id = 10;
            $upProcessData = [];
            $upProcessData['status'] = '0';
            $this->userRepo->updateKycProcessStatus($upProcessData, $userKycId, $form_id);
        }
        
        
        

        // check
        $chkResult = $this->userRepo->checkKycProcessComplete($userKycId);
        if($chkResult && $chkResult->count()){
                $is_kyc_completed = 0;
        } else {
            $is_kyc_completed = 1;
        }
        $dataKycUpdate['is_kyc_completed'] = $is_kyc_completed;

        $this->application->updateUserKyc($userKycId, $dataKycUpdate);
    }


    public function termCondition(Request $request) {
       return view('framePopup.termConditionPopup');
    }

}
