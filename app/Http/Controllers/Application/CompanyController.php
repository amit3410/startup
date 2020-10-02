<?php

namespace App\Http\Controllers\Application;

use Auth;
use File;
use Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PersonalProfileFormRequest;
use App\Http\Requests\Company\ShareholderFormRequest;
use App\Http\Requests\Company\CompanyDetailsFormRequest;
use App\Http\Requests\Company\CompanyAddressFormRequest;
use App\Http\Requests\Company\FinancialFormRequest;
use App\Inv\Repositories\Contracts\Traits\StorageAccessTraits;
use App\Inv\Repositories\Contracts\UserInterface as InvUserRepoInterface;
use App\Inv\Repositories\Contracts\ApplicationInterface as InvAppRepoInterface;
use App\Inv\Repositories\Libraries\Storage\Contract\StorageManagerInterface;
use validate;
use App\Inv\Repositories\Models\Master\EmailTemplate;
use App\Inv\Repositories\Models\CompanyAddress;
use App\Inv\Repositories\Models\Document;
use App\Inv\Repositories\Models\UserReqDoc;
use App\Inv\Repositories\Models\Userkyc;
use App\Inv\Repositories\Models\User as UserModel;
use DB;
use Helpers;
use Event;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller {

    public function __construct(InvUserRepoInterface $user, InvAppRepoInterface $application, StorageManagerInterface $storage) {
        $this->middleware('auth');
        $this->userRepo = $user;
        $this->application = $application;
        $this->storage = $storage;
    }

    public function index() {

        try {
            $is_pwd_changed = Auth::user()->is_pwd_changed;
            if ($is_pwd_changed == 0) {
                return redirect(route('changepassword'));
            }
            
            $flag=$this->checkCorporateKycStatus(Auth()->user()->user_id); //check user kyc completed or not
            if($flag==1){
                return redirect(route('thanku'));
            }
            $userKycid = $this->application->getUserKycid(Auth()->user()->user_id);
            $data['userSignupdata'] = $this->application->getRegisterDetails(Auth()->user()->user_id);
            //dd($userSignupdata->toArray());
            $data['companyprofile'] = $this->application->getCompanyProfileData($userKycid);
            $data['userSocialMedia'] = $this->userRepo->getCorpSocialmediaInfo($userKycid)->toArray();
            
            
            // kyc admin approve status
            //$kycApproveStatus=$this->userRepo->kycApproveStatus($userKycid);
            $kycApproveStatus=$this->userRepo->kycApproveFinalStatus($userKycid);
            $data['kycApproveStatus'] = $kycApproveStatus;
            $data['next_url']=route('company-address');
            $data['userKycid'] = $userKycid;
            //
            return view('frontend.company.company_details', $data);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($ex));
        }
    }

    public function companyDetailsForm(CompanyDetailsFormRequest $request) {


        try {

           // dd($request->all());
          
            $save = isset($request->save)?$request->save:'';
            $userId = Auth()->user()->user_id;   
            $userKycid = $this->application->getUserKycid($userId);
            $res = $this->application->saveCompanyProfile($request, $userKycid);
            
            //==== update social media link
                $this->userRepo->deleteCorpSocialmediaInfo($userKycid);
                $socialmediaData = [];
                $socialmediaIds = $request['social_media_id'];
                $socialmediaLinks = $request['social_media_link'];
                $socialmediaOther = $request['social_other'];
              //  dd($socialmediaIds);
                if (count($socialmediaIds) > 0) {

                    foreach ($socialmediaIds as $key => $docIds) {
                        $socialmediaData['social_media'] = $docIds;
                        $socialmediaData['social_media_link'] = $socialmediaLinks[$key];
                        if($docIds == 15) {
                          $socialmediaData['social_media_other'] = $socialmediaOther[$key];
                        } else {
                             $socialmediaData['social_media_other'] = Null;
                        }
                        $socialmediaData["user_kyc_id"] = $userKycid;
                        $socialmediaData["created_by"] = $userId;
                        $socialmediaData["updated_by"] = $userId;
                        //dd($socialmediaData);
                        $this->userRepo->storeCorpUserKycSocialmediaData($socialmediaData);
                    }
                }






            if ($res) {
                $form_id = 8;
                $upProcessData = [];
                $upProcessData['status'] = '1';
                $this->userRepo->updateKycProcessStatus($upProcessData, $userKycid, $form_id);
                // check
                $chkResult = $this->userRepo->checkKycProcessComplete($userKycid);
                if ($chkResult && $chkResult->count()) {
                    $is_kyc_completed = 0;
                } else {
                    $is_kyc_completed = 1;
                }
                $dataKycUpdate['is_kyc_completed'] = $is_kyc_completed;

                $this->application->updateUserKyc($userKycid, $dataKycUpdate);

    
                if (@$request->save !== '' && @$request->save != null) {
                   return redirect()->route('company_profile-show');
                }

                if (@$request->save_next !== '' && $request->save_next != null) {
                    return redirect()->route('company-address');
                }

            } else {
                return redirect()->back()->withErrors(trans('auth.oops_something_went_wrong'));
            }
        } catch (Exception $ex) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($ex));
        }
    }

    public function companyAddress() {
        try {
            $userKycid = $this->application->getUserKycid(Auth()->user()->user_id);
            $data['address'] = $this->application->getCompanyAddress($userKycid);
            
            $flag=$this->checkCorporateKycStatus(Auth()->user()->user_id); //check user kyc completed or not
            if($flag==1){
                return redirect(route('thanku'));
            }
            //print_r($data['address']); exit;
            
            // kyc admin approve status
            
            //$kycApproveStatus=$this->userRepo->kycApproveStatus($userKycid);
            $kycApproveStatus=$this->userRepo->kycApproveFinalStatus($userKycid);

            $data['kycApproveStatus'] = $kycApproveStatus;
            $data['next_url']=route('shareholding_structure');
            $data['prev_url']=route('company_profile-show');
            $data['userKycid'] = $userKycid;
            return view('frontend.company.address_details', $data);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($ex));
        }
    }

    public function companyAddressForm(CompanyAddressFormRequest $request) {

        try {
            $save = isset($request->save)?$request->save:'';   
            $same_address = $request->get('is_same_corp_corres_address');
           

            if (!isset($same_address)) {
                $dataAddress = [];
                $dataAddress['is_same_corp_corres_address'] = 0;
            } else {
                $dataAddress = [];
                $dataAddress['is_same_corp_corres_address'] = 1;
            }


            $userKycid = $this->application->getUserKycid(Auth()->user()->user_id);

            $res = $this->application->saveCompanyAddress($request, $userKycid);
            $this->application->updatesaddress($dataAddress, $userKycid);

            $form_id = 9;
            $upProcessData = [];
            $upProcessData['status'] = '1';
            $this->userRepo->updateKycProcessStatus($upProcessData, $userKycid, $form_id);

            // check
            $chkResult = $this->userRepo->checkKycProcessComplete($userKycid);
            if ($chkResult && $chkResult->count()) {
                $is_kyc_completed = 0;
            } else {
                $is_kyc_completed = 1;
            }
            $dataKycUpdate['is_kyc_completed'] = $is_kyc_completed;

            $this->application->updateUserKyc($userKycid, $dataKycUpdate);
            //when click on save button
           

            if ($res) {

               
                
                if (@$request->save !== '' && @$request->save != null) {
                   return redirect()->route('company-address-show');
                }

                if(@$request->save_next !== '' && $request->save_next != null) {
                    return redirect()->route('shareholding_structure');
                }

            } else {
                return redirect()->back()->withErrors(trans('auth.oops_something_went_wrong'));
            }
        } catch (Exception $ex) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($ex));
        }
    }

    public function shareholdingStructure() {

        $flag=$this->checkCorporateKycStatus(Auth()->user()->user_id); //check user kyc completed or not
        if($flag==1){
            return redirect(route('thanku'));
        }
        
        $nextShare = [];

        $userId = Auth()->user()->user_id;
         $userKycid = $this->application->getUserKycid(Auth()->user()->user_id);
        $result = $this->application->getHigestLevelShareData((int) $userId, '2');
        $beficiyerData = [];
        $beficiyerData = $this->application->getBeneficiaryOwnersData((int) Auth()->user()->user_id);

        //echo "<pre>";
        //print_r($result);
        // echo "<pre>";
        //print_r($beficiyerData);
        $data['prev_url']=route('company-address-show');
        if ($result && $result->count()) {
            $nextShare = $result->toArray();
            return view('frontend.company.shareholding_stracture', compact('nextShare','userKycid','data'));
        } else if ($beficiyerData && $beficiyerData->count()) {
            //$kycApproveStatus=$this->userRepo->kycApproveStatus($userKycid);
            $kycApproveStatus=$this->userRepo->kycApproveFinalStatus($userKycid);
            $data['kycApproveStatus'] = $kycApproveStatus;
            $data['next_url']   =   route('financial-show');
            
            return view('frontend.company.shareholding_beneficinary', compact('beficiyerData','data','userKycid'));
        } else {
            return view('frontend.company.shareholding_stracture', compact('nextShare','userKycid','data'));
        }
    }

    public function shareHoldingStructureForm(ShareholderFormRequest $request) {
        try {

            $requestVar = $request->all();
            $userId = Auth()->user()->user_id;
            $shareParentIds = @$requestVar['share_parent_id'];
            $shareLevels = @$requestVar['share_level'];

            $next = 0;
            foreach ($shareParentIds as $pkey => $parent_id) {//dd($parent_id);
            $shareLevel = $shareLevels[$pkey];
                $shareTypes = @$requestVar['share_type_' . $parent_id];
                //dd($shareTypes);
                $companyNames = @$requestVar['company_name_' . $parent_id];
                $passportNos = @$requestVar['passport_no_' . $parent_id];
                $sharePercentages = @$requestVar['share_percentage_' . $parent_id];
                $shareValues = @$requestVar['share_value_' . $parent_id];

                foreach ($shareTypes as $key => $type) {

                    $shareData['user_id'] = $userId;
                    $shareData['share_type'] = $type;
                    $shareData['company_name'] = $companyNames[$key];
                    $shareData['passport_no'] = $passportNos[$key];
                    $shareData['share_percentage'] = $sharePercentages[$key];
                    $shareData["share_value"] = $shareValues[$key];
                    $shareData["share_parent_id"] = $parent_id;
                    $shareData["share_level"] = (int) $shareLevel;
                    // echo '<pre>';
                    //print_r($shareData);
                    //echo '</pre>';
                    // $response = $this->application->saveShareHoldingForm($shareData, null);
                    //echo '<pre>';
                    // print_r($response);
                    //echo '</pre>';
                    if ($type == '2') {
                        $next++;
                    }
                }
            }





            if ($next > 0) {
                //return view('frontend.company.shareholding_stracture',compact('nextShare',''));
                Session::flash('message', trans('success_messages.UpdateShareHolderSuccessfully'));
                return redirect()->route('shareholding_structure');
            } else {
                Session::flash('message', trans('success_messages.UpdateShareHolderSuccessfully'));
                return redirect()->route('financial-show');
            }
        } catch (Exception $ex) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($ex));
        }
    }

    public function financialInfo() {
        try {
            $userKycid = $this->application->getUserKycid(Auth()->user()->user_id);
            $flag=$this->checkCorporateKycStatus(Auth()->user()->user_id); //check user kyc completed or not
            if($flag==1){
                return redirect(route('thanku'));
            }
            $userKycid = $this->application->getUserKycid(Auth()->user()->user_id);
            //echo $userKycid;
            $data['financial'] = $this->application->getCompanyFinancialData($userKycid);
            
            //$kycApproveStatus=$this->userRepo->kycApproveStatus($userKycid);
            $kycApproveStatus=$this->userRepo->kycApproveFinalStatus($userKycid);
            $data['kycApproveStatus'] = $kycApproveStatus;
            $data['next_url']   =   route('documents-show');
            $data['prev_url']=route('shareholding_structure');
            $data['userKycid'] = $userKycid;
            return view('frontend.company.financial', $data);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($ex));
        }
    }

    public function financialInfoForm(FinancialFormRequest $request) {

        try {

           
            $save = isset($request->save)?$request->save:'';   
            $userKycid = $this->application->getUserKycid(Auth()->user()->user_id);

            $res = $this->application->saveFinancialInfoForm($request, $userKycid);

            $form_id = 11;
            $upProcessData = [];
            $upProcessData['status'] = '1';
            $this->userRepo->updateKycProcessStatus($upProcessData, $userKycid, $form_id);

            // check
            $chkResult = $this->userRepo->checkKycProcessComplete($userKycid);
            if ($chkResult && $chkResult->count()) {
                $is_kyc_completed = 0;
            } else {
                $is_kyc_completed = 1;
            }
            $dataKycUpdate['is_kyc_completed'] = $is_kyc_completed;

            $this->application->updateUserKyc($userKycid, $dataKycUpdate);
            //if user click on save button
            
            if ($res) {
                
                
                
                if (@$request->save !== '' && @$request->save != null) {
                   return redirect()->route('financial-show');
                }

                if(@$request->save_next !== '' && $request->save_next != null) {
                    return redirect()->route('documents-show');
                }
            } else {
                return redirect()->back()->withErrors(trans('auth.oops_something_went_wrong'));
            }
        } catch (Exception $ex) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($ex));
        }
    }

    public function documentDeclaration() {


        try {
            // $kycStatus = Userkyc::kycStatus(Auth()->user()->user_id);

            $flag=$this->checkCorporateKycStatus(Auth()->user()->user_id); //check user kyc completed or not
           
           
           if($flag==1){
                return redirect(route('thanku'));
            }
            
            $data['kycid'] = $this->application->getUserKycid(Auth()->user()->user_id);

            $data['documentArray'] = $this->application->corporateDocument($data['kycid']); //corp doc required
           // $kycApproveStatus=$this->userRepo->kycApproveStatus($data['kycid']);
            $kycApproveStatus=$this->userRepo->kycApproveFinalStatus($data['kycid']);
            $data['kycApproveStatus'] = $kycApproveStatus;
            $data['next_url']='';
            $data['prev_url']=route('financial-show');
            $data['userKycid'] = $data['kycid'];
            $arrDoc_no=[3];
            $data['resMstDoc']  =   $this->userRepo->getMonitorDocByDocNo($arrDoc_no,$data['kycid']);
       
            return view('frontend.company.documents', $data);
        } catch (Exception $ex) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($ex));
        }
    }

    public function documentDeclarationForm(Request $request) {
        $userid = Auth()->user()->user_id;

        try {

            $requestVar = $request->all();
            $checkboxval = '';
            $checkboxval = $requestVar['checkboxval'];
            
            $resdata = [];
            
            $userKycId  =   $this->application->getUserKycid(Auth()->user()->user_id);
         
            // validation
            $arrDocId = [];
            $i = 0;
            $isError = 0;
            $errorMsg = [];
            $arrExt = [];
            $j = 0;
            /*if($request->file()) {
                foreach ($request->file() as $keyArrayMain) {

                    foreach ($keyArrayMain as $key => $doc) {
                        $keyArray = explode("#", $key);
                        if (!in_array($keyArray[0], $arrDocId)) {
                            $arrDocId[$i] = $keyArray[0];
                            $i++;
                        }

                    }
                }
            }*/

            $maxSize=config('common.USER_PROFILE_MAX_SIZE'); // 20mb
            $extenstion=config('common.CHECK_EXTENTION');//PDF,JPEG,PNG,DOC,DOCX

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
                            
                            if($fileSize > config('common.USER_PROFILE_MAX_SIZE')){
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
           
            if(is_array($requiredDocsList) && count($requiredDocsList)) {
                foreach ($requiredDocsList as $key => $req_doc_id) {
                    if (!in_array($key, $arrDocId)) {
                        $isError = 1;
                        $errorMsg['doc_' . $key] = trans('common.error_messages.req_this_field');
                    }
                }
            }
             //for term and condition
            $requiredTermCondi = $this->userRepo->find($userid);
          
            if($checkboxval == 0) {
                $isError = 1;
                $errorMsg['termError'] = trans('common.error_messages.req_this_field');
            }

            $rules      =   [];
            $messages   =   [];
            $rules['issuance_date']     = 'required';
            $rules['document_number']   = 'required';
            
            $messages['issuance_date.required'] = trans('common.error_messages.req_this_field');
            $messages['document_number.required'] = trans('common.error_messages.req_this_field');
          //  dd($errorMsg);
            $validator = Validator::make($request->all(), $rules, $messages);
            if($validator->fails()) {
                $isError = 1;
                
                $customMessages = $validator->getMessageBag()->toArray();
                foreach ($customMessages as $key => $msg) {
                    $errorMsg['error'.$key] = $msg;

                }
            }
            
            if($isError == 1){
                $resdata['status'] = 'error';
                $resdata['is_array'] = 1;
                $resdata['message'] = $errorMsg;
                $resdata['redirect'] = '';
                return json_encode($resdata);
            }

            foreach ($request->file() as $keyArrayMain){
               
                foreach ($keyArrayMain as $key => $doc){
                    $keyArray = explode("#", $key);

                    $user_req_doc_id = $keyArray[0];
                    $user_id = $keyArray[1];
                    $doc_id = $keyArray[2];

                    foreach ($doc as $row) {

                        $docname = $row->getClientOriginalName();
                        $certificate = basename($row->getClientOriginalName());

                        $certificate = pathinfo($certificate, PATHINFO_FILENAME);
                        $ext = $row->getClientOriginalExtension();




                        /////////////////////////
                        $fileSize = $row->getClientSize();

                            $userBaseDir = 'appDocs/Document/corporate/pdf/' . $user_id;
                            $userFileName = $docname;
                            $pathName = $row->getPathName();
                            //echo $pathName; exit;

                            $this->storage->engine()->put($userBaseDir . DIRECTORY_SEPARATOR . $userFileName, File::get($pathName));
                            // Delete the temporary file
                            File::delete($pathName);
                        
                        //store data in array
                        $array = [];
                        $array1 = [];
                        $array1['user_req_doc_id'] = $user_req_doc_id;
                        $array1['doc_type'] = 2;
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
                    UserModel::where('user_id', Auth()->user()->user_id)->update(['is_term_condition'=>1]);
                }
                
                //====== update utility bill issue and expiry info=====
            $arrDoc_no=[3];
            
            $resMstDoc  =   $this->userRepo->getMonitorDocByDocNo($arrDoc_no,$userKycId);
                
            if($resMstDoc && $resMstDoc->count()){
                foreach($resMstDoc as $resDoc){

                    $documentData['document_number']    =   $requestVar['document_number'];
                    $documentData['issuance_date']      =   isset($requestVar['issuance_date']) ? Helpers::getDateByFormat($requestVar['issuance_date'], 'd/m/Y', 'Y-m-d') : null;
                    
                    if($documentData['issuance_date']!=null){
                        $today          =   $documentData['issuance_date'];
                        $day            =   90;
                        $expire_date    =   date('Y-m-d',strtotime('+1 year',strtotime($today)));
                        $documentData['expire_date']        =   $expire_date;
                    }

                    $documentData["updated_by"]         =   (int) Auth::user()->user_id;
                    $documentData["updated_at"]         =   date('Y-m-d H:i:s');
                    
                    $this->userRepo->storeUserKycDocumentTypeData($documentData,$resDoc->id);

                }
            }
            
            //===========================================
                if ($res) {
                    $isUploaded = $this->application->checkAllDocUploaded($user_id);
                    if ($isUploaded) {
                        $form_id = 12;
                        $upProcessData = [];
                        $upProcessData['status'] = '1';
                        $this->userRepo->updateKycProcessStatus($upProcessData, $user_id, $form_id);
                    }

                    // check
                    $chkResult = $this->userRepo->checkKycProcessComplete($userKycId);
                    if ($chkResult && $chkResult->count()) {
                        $is_kyc_completed = 0;
                    } else {
                        $is_kyc_completed = 1;
                    }
                    $dataKycUpdate['is_kyc_completed'] = $is_kyc_completed;

                    $res = $this->application->updateUserKyc($user_id, $dataKycUpdate);


                    $kycStatus = Userkyc::kycStatus(Auth()->user()->user_id); //check kyc completed or not. 
                    if ($kycStatus == 1) {
                        
                        $status =   "kyccompleted";
                    } else {
                        Session::flash('message', trans('success_messages.update_documents_successfully'));
                        $status =   "success";
                    }
                     $resdata['status'] = $status;
                     $resdata['is_array'] = 0;
                     $resdata['message'] = '';
                     $resdata['redirect'] = '';
                     return json_encode($resdata);
                } else {
                     $resdata['status'] = 'error';
                     $resdata['is_array'] = 0;
                     $resdata['message'] = trans('success_messages.somthing_wrong');
                     $resdata['redirect'] = '';
                     return json_encode($resdata);
                }
            }

            
            
            $kycStatus = Userkyc::kycStatus(Auth()->user()->user_id); //check kyc completed or not. 
            if ($kycStatus == 1) {           
                $status =   "kyccompleted";
            }else{
                
                $status =   "success";
            }
            
            $resdata['status'] = $status;
            $resdata['is_array'] = 0;
            $resdata['message'] = '';
            $resdata['redirect'] = route('documents-show');
            
            return json_encode($resdata);
        } catch (Exception $ex) {
            $resdata['status'] = 'error';
            $resdata['is_array'] = 0;
            $resdata['message'] = Helpers::getExceptionMessage($ex);
            $resdata['redirect'] = '';
            return json_encode($resdata);
        }
    }

    public function docDownload(Request $request) {
        $documentHash = $request->get('enc_id');
        $docList = $this->application->getSingleDocument($documentHash);
        $userID = $docList->user_kyc_id;
        $fileName = $docList->doc_name . "." . $docList->doc_ext;
        $file = storage_path('app/appDocs/Document/corporate/pdf/' . $userID . "/" . $fileName);
        $mimetype = mime_content_type($file);
        //echo $mimetype; exit;
        //ob_end_clean();
        //return response()->download($file);
        $data = file_get_contents(storage_path('app/appDocs/Document/corporate/pdf/' . $userID . "/" . $fileName));
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

    public function companyThanksPage(Request $request) {
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
            $userKycid=$userKycId = (int) $user_kyc_id;
            $userId = null;
        } else {
            $userId = (int) Auth::user()->user_id;
            $userKycid=$userKycId = $this->application->getUserKycid(Auth()->user()->user_id);
            $benifinary['user_kyc_id'] = $userKycId;
            $benifinary['corp_user_id'] = 0;
            $benifinary['is_by_company'] = 0;
        }
        $benifinary['user_type'] = (int) Auth::user()->user_type;
        $benifinary['user'] = $this->userRepo->find(Auth::user()->user_id);

        /*            echo "<pre>";
          print_r($benifinary['user']);die; */
        return view('frontend.company.companythanks', compact('benifinary','userKycid'));
    }

    //corporate kyc confirmation
    public function checkCorporateKycStatus($userId) {
        if (!is_int((int) $userId) && $userId != 0) {
            throw new InvalidDataTypeExceptions(trans('error_message.invalid_data_type'));
        }

        $kycStatus = Userkyc::kycStatus($userId); //check kyc completed.
        //echo "==>".$kycStatus; exit;
        $userMailStatus = UserModel::where('user_id', (int) $userId)->first(); //check  kyc completed sent mail.
        $isThankYou=0;
        if ($kycStatus == 1) {
            if ($userMailStatus->kyc_completed_sent_mail == 0) {
                $userArr = [];
                $userArr = $this->userRepo->find($userId, ['email', 'first_name', 'last_name']);

                $verifyUserArr = [];
                $userMailArr = [] ;
                $verifyUserArr['name'] = $userArr->f_name . ' ' . $userArr->l_name;
                $verifyUserArr['email'] = $userArr->email;

                 Event::dispatch("user.email.corporatekycCompletedMail", serialize($verifyUserArr));
                 // for compliance Admin sent mail
                 $compliance_admin_email =  config('common.ADMIN_EMAIL');// Compliance admin email 
                 $corpname = $this->application->getRegisterDetails($userId);
                 $userMailArr['email'] =   $compliance_admin_email;
                 $userMailArr['name'] =    $corpname['corp_name'];
                 $userMailArr['user_type'] =  (int)Auth()->user()->user_type;
                 Event::dispatch("sent.admin.kyc.complete.notification", serialize($userMailArr));
                 UserModel::where('user_id', (int) $userId)->update(['kyc_completed_sent_mail' => 1]);
                 $isThankYou=1;
            }
        }
        return $isThankYou;
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

               $userKycid= $benifinary['user_kyc_id'] = (int) $user_kyc_id;
                $benifinary['corp_user_id'] = (int) $corp_user_id;
                $benifinary['is_by_company'] = 1;
                $userKycId = (int) $user_kyc_id;
                $userId = $corp_user_id;
            } else {
                $userId = (int) Auth::user()->user_id;
                $userKycid=$userKycId = $this->application->getUserKycid(Auth()->user()->user_id);
                $benifinary['user_kyc_id'] = $userKycId;
                $benifinary['corp_user_id'] = 0;
                $benifinary['is_by_company'] = 0;
            }

            $documentArray = $this->application->otherReqDocument($userKycId); //corp doc required
            // kyc admin approve status
            //$kycApproveStatus=$this->userRepo->kycApproveStatus($userKycId);
            $kycApproveStatus=$this->userRepo->kycApproveFinalStatus($userKycId);
        
            return view('frontend.company.upload_other_document', compact('userData', 'benifinary', 'documentArray','kycApproveStatus','userKycid'));
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
           
            $userId = (int) Auth::user()->user_id;
            $userKycId = $this->application->getUserKycid(Auth()->user()->user_id);
            $isBycompany = 0;
            $corpUserId = 0;
            
            // validation
            $arrDocId = [];
            $i = 0;
            $isError = 0;
            $errorMsg = [];
            $arrExt = [];
            $j = 0;
            
            
            //$maxSize=20971520; // 20mb
            $maxSize = config('common.USER_PROFILE_MAX_SIZE');
            $extenstion=['png','jpeg','jpg','doc','docx','pdf'];//PDF,JPEG,PNG,DOC,DOCX
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
                            
                            if($fileSize > config('common.USER_PROFILE_MAX_SIZE')){
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
        

            if(is_array($requiredDocsList) && count($requiredDocsList)){
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

               
            }else{
                
                $resdata['status'] = 'success';
                $resdata['message'] = 'success';
                    
                Session::flash('message', trans('success_messages.update_documents_successfully'));
                $resdata['redirect'] = route('upload_corp_other_document', ['user_kyc_id' => $userKycId, 'corp_user_id' => $corpUserId, 'is_by_company' => $isBycompany]); 
            
                 return json_encode($resdata);
            }
            
            if($res){
                    $resdata['status'] = 'success';
                    $resdata['message'] = 'success';
                   
                    Session::flash('message', trans('success_messages.update_documents_successfully'));
                     $resdata['redirect'] = route('upload_corp_other_document', ['user_kyc_id' => $userKycId, 'corp_user_id' => $corpUserId, 'is_by_company' => $isBycompany]);
                    return json_encode($resdata);  
            }else{
               
                    $resdata['status'] = 'error';
                    $resdata['is_array'] = 0;
                    $resdata['message'] = 'error';
                    $resdata['redirect'] = '';
                    return json_encode($resdata);
            }
            
        } catch (Exception $ex) {
            $resdata['status'] = 'error';
            $resdata['is_array'] = 0;
            $resdata['message'] = Helpers::getExceptionMessage($ex);
            $resdata['redirect'] = '';
            return json_encode($resdata);
        }
    }

}
