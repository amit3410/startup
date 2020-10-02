<?php

namespace App\Http\Controllers;

use Auth;
use Crypt;
use Helpers;
use Session;
use Mail;
use Event;
use File;
use DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Contracts\Ui\DataProviderInterface;
use App\Inv\Repositories\Models\Master\Country;
use App\Inv\Repositories\Models\Master\State;
use App\Inv\Repositories\Models\Master\Cluster;
use App\Inv\Repositories\Models\Master\RightType;
use App\Inv\Repositories\Models\Master\Source;
use App\Inv\Repositories\Models\Rights;
use App\Inv\Repositories\Models\RightCommission;
use App\Inv\Repositories\Models\Master\EmailTemplate;
use App\Inv\Repositories\Contracts\UserInterface as InvUserRepoInterface;
use App\Inv\Repositories\Contracts\ApplicationInterface as InvAppRepoInterface;
use App\Inv\Repositories\Libraries\Storage\Contract\StorageManagerInterface;
use App\Inv\Repositories\Contracts\WcapiInterface as WcapiRepoInterface;
use App\Http\Requests\Company\ShareholderFormRequest;
use App\Inv\Repositories\Models\DocumentMaster;
use App\Inv\Repositories\Models\UserReqDoc;
use Illuminate\Support\Facades\Validator;

class AjaxController extends Controller {

    /**
     * Request
     *
     * @var \Illuminate\Http\Request;
     */
    protected $request;
    protected $user;
    protected $application;
    protected $wcapi;

     function __construct(Request $request, InvUserRepoInterface $user, InvAppRepoInterface $application, WcapiRepoInterface $wcapi, StorageManagerInterface $storage) {
        // If request is not ajax, send a bad request error
        if (!$request->ajax() && strpos(php_sapi_name(), 'cli') === false) {
            abort(400);
        }
        $this->request = $request;
        $this->userRepo = $user;
        $this->application = $application;
        $this->wcapi = $wcapi;
        $this->storage = $storage;
    }

    /**
     * Get all country list ajax
     *
     * @return json country data
     */
    public function getCountryList(DataProviderInterface $dataProvider) {
        $all_country = Country::getCountryList();
        $countries = $dataProvider->getCountryList($this->request, $all_country);
        return $countries;
    }

    //check signup email exist.
    //modal show
    public function modalLoad() {

        $status = $this->application->userCheck(); //user check for modal load or not
        if (!empty($status)) {
            echo "1";
        } else {
            echo "0";
        }
    }

    //user pop up record update
    public function userPopUpUpdate() {


        $status = $this->application->userPopupUpdate(); //user check for modal load or not
        if (!empty($status)) {
            echo "1";
        }
    }

    /**
     * Get all country list ajax
     *
     * @return json country data
     */
    public function getStateList(DataProviderInterface $dataProvider) {
        $all_state = State::getStateList();
        $state = $dataProvider->getStateList($this->request, $all_state);
        return $state;
    }

    /**
     * Delete country
     *
     * @return int
     */
    public function deleteCountries(Request $request) {
        $cntry_id = $request->get('cid');
        return Country::deleteCountry($cntry_id);
    }

    /**
     * Delete state
     *
     * @return int
     */
    public function deleteState(Request $request) {
        $state_id = $request->get('state_id');
        return State::deleteState($state_id);
    }

    /**
     * Get all User list
     *
     * @return json user data
     */
    public function getUsersList(DataProviderInterface $dataProvider) {

        $usersList = $this->userRepo->getAllUsers();
        $countries = $dataProvider->getUsersList($this->request, $usersList);
        return $countries;
    }

    /**
     * Get all cluster list ajax
     *
     * @return json cluster data
     */
    public function getClusterList(DataProviderInterface $dataProvider) {
        try {
            $cluster_data = Cluster::getClusterList();

            $cluster = $dataProvider->getClusterList($this->request, $cluster_data);
            return $cluster;
        } catch (Exception $ex) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($ex))->withInput();
        }
    }

    /**
     * Delete Cluster
     *
     * @return int
     */
    public function deleteCluster(Request $request) {
        $c_id = $request->get('id');
        return Cluster::deleteCluster($c_id);
    }

    /**
     * Get all rights type list ajax
     *
     * @return json cluster data
     */
    public function getRightTyprList(DataProviderInterface $dataProvider) {
        try {
            $right_data = RightType::getRightTypeList();
            $res = $dataProvider->getRightTyprList($this->request, $right_data);
            return $res;
        } catch (Exception $ex) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($ex))->withInput();
        }
    }

    /**
     * Delete rights type
     *
     * @return int
     */
    public function deleteRightType(Request $request) {
        $c_id = $request->get('id');
        return RightType::deleteRightType($c_id);
    }

    /**
     * Get all Source list ajax
     *
     * @return json Source data
     */
    public function getSourceList(DataProviderInterface $dataProvider) {
        try {
            $source_data = Source::getSourceList();

            $res = $dataProvider->getSourceList($this->request, $source_data);
            return $res;
        } catch (Exception $ex) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($ex))->withInput();
        }
    }

    /**
     * Delete rights type
     *
     * @return int
     */
    public function deleteSource(Request $request) {
        $s_id = $request->get('id');
        return Source::deleteSource($s_id);
    }

    /**
     * fetch rights comments
     *
     * @param Request $request
     */
    public function globalSearch(Request $request) {
        try {
            $input = $request->get('input');


            if ($input === null && Session::has('search_input')) {
                
            } else {
                Session::put('search_input', $input);
            }



            return 1;
        } catch (Exception $ex) {
            return 0;
        }
    }

    /**
     * Delete rights type
     *
     * @return int
     */
    public function deleteRights(Request $request) {
        $c_id = $request->get('id');
        return Rights::deleteRight($c_id);
    }

//ShareholderFormRequest
    public function saveShareholder(Request $request) {

        try {

            $requestVar = $request->all();

            $userId = Auth()->user()->user_id;
            $shareParentIds = $requestVar['share_parent_id'];
            $shareLevels = $requestVar['share_level'];
            //$shareLicenseNo = $requestVar['LicenseNo'];

            $nexted = $requestVar['nexted'];
            $userKycId = $this->application->getUserKycid(Auth()->user()->user_id);
            // echo "===>".$userKycId;
            $rules = [];
            //dd(@$requestVar);
            $error = 0;
            $otherMessage = [];
            $otherKey = [];
            $j = 0;
            foreach ($shareParentIds as $id => $parent_id) {
                $rows = @$requestVar['rows' . $id];
                $LicenseNo = $requestVar['LicenseNo_' . $parent_id];

                $rules['LicenseNo_' . $parent_id] = 'required';
                $arrPassport=[];
                for ($key = 0; $key < $rows; $key++) {
                    $shareType = $requestVar['shareType' . $id . '_' . $key];
                    $passportNo = $requestVar['passportNo' . $id . '_' . $key];
                    if(in_array($passportNo,$arrPassport)){
                        $otherMessage[$j] = trans('common.error_messages.passport_no_unique');
                        $otherKey[$j] = 'errorpassportNo' . $id . '_' . $key;
                        $j++;
                        $error = 1;
                    }
                    $arrPassport[$key]=$passportNo;
                   
                    if ($shareType == '2' && $LicenseNo == $passportNo) {
                        $otherMessage[$j] = trans('common.error_messages.company_llience_same');
                        $otherKey[$j] = 'errorpassportNo' . $id . '_' . $key;
                        $j++;
                        $error = 1;
                        // echo $shareType.'_'.$LicenseNo.'_'.$passportNo.'<br>';
                    } else {
                        //echo $shareType.'_'.$LicenseNo.'_'.$passportNo.'<br>';
                    }
                    $rules['shareType' . $id . '_' . $key] = 'required';
                    $rules['companyName' . $id . '_' . $key] = 'required';
                    $rules['passportNo' . $id . '_' . $key] = 'required|regex:/^[a-z0-9 \s]+$/i|max:20';
                    $rules['sharePercentage' . $id . '_' . $key] = 'required|regex:/^\d+(\.\d{1,2})?$/|max:5';
                    $rules['shareValue' . $id . '_' . $key] = 'required|regex:/^\d+(\.\d{1,2})?$/|max:12';
                }
            }


            $messages = [];
            foreach ($shareParentIds as $id => $parent_id) {
                $rows = @$requestVar['rows' . $id];
                $messages['LicenseNo_' . $parent_id . '.required'] = trans('common.error_messages.req_this_field');
                for ($key = 0; $key < $rows; $key++) {
                    $messages['shareType' . $id . '_' . $key . '.required'] = trans('common.error_messages.req_this_field');
                    $messages['companyName' . $id . '_' . $key . '.required'] = trans('common.error_messages.req_this_field');
                    $messages['passportNo' . $id . '_' . $key . '.required'] = trans('common.error_messages.req_this_field');
                    $messages['passportNo' . $id . '_' . $key . '.regex'] = trans('common.error_messages.req_this_field');
                    $messages['passportNo' . $id . '_' . $key . '.max'] = trans('common.error_messages.req_this_field');
                    //$messages['passportNo' . $id . '_' . $key . '.unique'] = trans('common.error_messages.passport_no_unique');
                    $messages['sharePercentage' . $id . '_' . $key . '.required'] = trans('common.error_messages.req_this_field');
                    $messages['sharePercentage' . $id . '_' . $key . '.regex'] = trans('common.error_messages.invalid_value');
                    $messages['sharePercentage' . $id . '_' . $key . '.max'] = trans('common.error_messages.max_5_len');
                    $messages['shareValue' . $id . '_' . $key . '.required'] = trans('common.error_messages.req_this_field');
                    $messages['shareValue' . $id . '_' . $key . '.regex'] = trans('common.error_messages.invalid_value');
                    $messages['shareValue' . $id . '_' . $key . '.max'] = trans('common.error_messages.max_20_len');
                }
            }

            //die;
            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                $resData['status'] = 'error';
                $error_msg = [];
                //dd($validator->customMessages);
                $i = 0;
                $customMessages = $validator->getMessageBag()->toArray();
                foreach ($customMessages as $key => $msg) {
                    $idx = explode('.', $key);

                    $error_msg[$i] = $msg;
                    $error_index[$i] = 'error' . $idx[0];
                    $i++;
                }
                if ($error == 1) {
                    foreach ($otherMessage as $key => $msg) {
                        $error_msg[$i] = $msg;
                        $error_index[$i] = $otherKey[$i];
                        $i++;
                    }
                }



                $resData['message'] = $error_msg;
                $resData['messagekey'] = $error_index;
                $resData['otherMessage'] = '';
                $resData['otherKey'] = $otherKey;

                return json_encode($resData);
            } else if ($error == 1) {
                $p = 0;
                foreach ($otherMessage as $key => $msg) {
                    $error_msg[$p] = $msg;
                    $error_index[$p] = $otherKey[$p];
                    $p++;
                }
                $resData['status'] = 'error';
                $resData['message'] = $error_msg;
                $resData['messagekey'] = $error_index;
                $resData['otherMessage'] = '';
                return json_encode($resData);
            } else {
                $next = 0;
                foreach ($shareParentIds as $pkey => $parent_id) {
                    $shareLevel = $shareLevels[$pkey];
                    $shareTypes = @$requestVar['share_type_' . $pkey];
                    $rows = @$requestVar['rows' . $pkey];
                    $parent_actual_share = 0;
                    if ($parent_id > 0) {
                        $parentInfo = $this->application->getShareHolderInfo($parent_id);
                        if ($parentInfo) {
                            $parent_actual_share = $parentInfo->actual_share_percent;
                        }
                    }

                    for ($key = 0; $key < $rows; $key++) {
                        $shareData['user_id'] = $userId;
                        $type = $requestVar['shareType' . $pkey . '_' . $key];
                        $shareData['share_type'] = $requestVar['shareType' . $pkey . '_' . $key];
                        $shareData['company_name'] = $requestVar['companyName' . $pkey . '_' . $key];
                        $shareData['passport_no'] = $requestVar['passportNo' . $pkey . '_' . $key];
                        $shareData['share_percentage'] = $requestVar['sharePercentage' . $pkey . '_' . $key];
                        $shareData["share_value"] = $requestVar['shareValue' . $pkey . '_' . $key];
                        $shareData["share_parent_id"] = $parent_id;
                        $shareData["share_level"] = (int) $shareLevel;
                        if ($parent_id > 0) {
                            $actual_share = $parent_actual_share * $shareData['share_percentage'] / 100;
                            $shareData["actual_share_percent"] = (float) $actual_share;
                        } else {
                            $actual_share = $shareData['share_percentage'];
                            $shareData["actual_share_percent"] = (float) $actual_share;
                        }

                        $response = $this->application->saveShareHoldingForm($shareData, null);
                        if ($response) {
                            if ($shareData["actual_share_percent"] >= 5 && $shareData['share_type'] == 1) {
                                $arrKyc['user_id'] = $userId;
                                $arrKyc['is_approve'] = 0;
                                $arrKyc['is_kyc_completed'] = 0;
                                $arrKyc['is_api_pulled'] = 0;
                                $arrKyc['is_by_company'] = 1;
                                $kycDetail = $this->userRepo->saveKycDetails($arrKyc);
                                $updateData['owner_kyc_id'] = $kycDetail->kyc_id;

                                $userKycid = $kycDetail->kyc_id; //get user kyc id
                                $corpdata = UserReqDoc::where('user_kyc_id', $userKycid)->first();
                                $doc_for = 1;
                                $type = 1;
                                
                                if(empty($corpdata)){
                                    $doc = DocumentMaster::where('type',$type)->where('doc_for', $doc_for)->where('is_active', 1)->whereNotNull('displayorder')->orderBy('displayorder','asc')->get();
                                    UserReqDoc::createCorpDocRequired($doc, $userKycid, $userId);
                                }
                                
                                 // add monitoring utility bill documenttype
 
                                $arrDoc_no=[2];
                                $resMstDoc  =   $this->userRepo->getMonitorMstDocByDocNo($arrDoc_no);

                                if($resMstDoc && $resMstDoc->count()){
                                    foreach($resMstDoc as $resDoc){
                                        $documentData['doc_id']             =   $resDoc->id;
                                        $documentData['document_number']    =   null;
                                        $documentData['is_monitor']         =   1;
                                        $documentData['form_id']            =   $resDoc->form_id;
                                        $documentData['doc_for']            =   1;
                                        $documentData['doc_no']             =   $resDoc->doc_no;
                                        $documentData['is_active']          =   $resDoc->is_active;
                                        $documentData['status']             =   0;
                                        $documentData['doc_status']         =   0;
                                        $documentData['issuance_date']      =   null;//isset($requestVar['issuance_date']) ? Helpers::getDateByFormat($requestVar['issuance_date'], 'd/m/Y', 'Y-m-d') : null;
                                        $documentData['expire_date']        =   null;//isset($requestVar['expiry_date' . $i]) ? Helpers::getDateByFormat($requestVar['expiry_date' . $i], 'd/m/Y', 'Y-m-d') : null;
                                        $documentData["user_kyc_id"]        =   $userKycid;
                                        $documentData["created_by"]         =   Auth()->user()->user_id;
                                        $documentData["updated_by"]         =   Auth()->user()->user_id;
                                        
                                        $this->userRepo->storeUserKycDocumentTypeData($documentData,null);
                                    }

                                }
                                



                                $this->application->saveShareHoldingForm($updateData, $response->corp_shareholding_id);
                                //=== save user kyc process form status
                                $arrKycProcessData = [];
                                $fromList = $this->userRepo->getKycProcessForms('1');
                                if ($fromList && $fromList->count()) {
                                    $i = 0;
                                    foreach ($fromList as $objf) {
                                        $dataf = [];

                                        $dataf['form_id'] = $objf->id;
                                        $dataf['user_kyc_id'] = $kycDetail->kyc_id;
                                        $dataf['kyc_type'] = $objf->kyc_type;
                                        $dataf['is_required'] = $objf->is_required;
                                        $dataf['status'] = '0';

                                        $arrKycProcessData[$i] = $dataf;
                                        $i++;
                                    }
                                }

                                if (count($arrKycProcessData)) {
                                    $this->userRepo->saveBatchData($arrKycProcessData);
                                }
                            }
                        }

                        if ($type == '2') {
                            $next++;
                        } else {
                            
                        }
                    }
                }

                $resData['status'] = 'success';
                $resData['message'] = trans('success_messages.UpdateShareHolderSuccessfully');
                if ($next > 0) {

                    Session::flash('message', trans('success_messages.UpdateShareHolderSuccessfully'));
                    $redirect = route('shareholding_structure');
                } else {

                    

                    $redirect = route('shareholding_structure');
                }
                $resData['redirect'] = $redirect;
                return json_encode($resData);
            }
        } catch (Exception $ex) {

            $resData['otherMessage'] = Helpers::getExceptionMessage($ex);
            $resData['status'] = 'error';
            return json_encode($resData);
        }
    }

    ////make userName
    public function changeuserName($fistName, $lastName, $phone) {
        //echo "<pre>";
        //print_r($request);
        $fistNameNew = substr($fistName, 0, 3);
        $lastNameNew = substr($lastName, 0, 3);
        $phoneNew = substr($phone, 0, 4);
        $userName = $fistNameNew . $lastNameNew . $phoneNew;
        return $userName;
    }

    /**
     * Get all Similar records
     *
     * @return json user data
     */
    public function getUsersListAPI11(Request $request) {


        $gatwayhost     = config('common.gatwayhost');
        $groupId        = Helpers::getgroupId();
        $gatwayurl      = config('common.gatwayurl');
        $gatwayhost     = config('common.gatwayhost');
        $contentType    = config('common.contentType');
        // $kycDetail = $this->wcapi->saveWcapiCall($arrKyc);
        $user_kyc_id = $request->get('user_kyc_id');
        $authSignature = $request->get('authorisation');
        $date = $request->get('currentDate');
        $Signature = $request->get('Signature');
        $ContentLength = $request->get('ContentLength');
        $Content = $request->get('content');

        $curl = curl_init();

        $endPoint = "https://" . $gatwayhost . $gatwayurl . "cases/screeningRequest";
        $postRequest = array(
            //CURLOPT_URL => "https://rms-world-check-one-api-pilot.thomsonreuters.com/v1/cases/screeningRequest",
            CURLOPT_URL => $endPoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\n  \"groupId\":\"".$groupId."\",\n  \"entityType\": \"INDIVIDUAL\",\n  \"providerTypes\": [\n    \"WATCHLIST\"\n  ],\n  \"name\": \"putin\",\n  \"secondaryFields\":[{\"typeId\": \"SFCT_2\",\"dateTimeValue\":\"1952-07-10\"}],\n  \"customFields\":[]\n}",
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Accept-Encoding: gzip, deflate",
                "Authorization: " . $authSignature,
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "Content-Length: " . $ContentLength,
                "Content-Type: " . $contentType,
                "Date: " . $date,
                "Host: " . $gatwayhost,
                "cache-control: no-cache"
            ),
        );

        curl_setopt_array($curl, $postRequest);
        $response = curl_exec($curl);

        $postRequestSave = json_decode($postRequest);
        //$dataArray = json_decode($response);

        $arrKyc['user_kyc_id'] = $user_kyc_id;
        $arrKyc['request_for'] = 'screeningRequest';
        $arrKyc['api_request'] = $postRequestSave;
        $arrKyc['api_responce'] = $response;
        $arrKyc['by_whome'] = Auth()->user()->user_id;
        $arrKyc['created_by'] = Auth()->user()->user_id;
        $arrKyc['updated_by'] = Auth()->user()->user_id;

        $kycDetail = $this->wcapi->saveWcapiCall($arrKyc);

        $err = curl_error($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $dataArray = json_decode($response);

            $i = 0;
            foreach ($dataArray->results as $resultArray) {
                $name = $resultArray->matchStrength;
                if ($resultArray->matchStrength == 'STRONG') {
                    $primaryName = $resultArray->primaryName;
                    $category = $resultArray->category;
                    $gender = $resultArray->gender;
                    $referenceId = $resultArray->referenceId;
                    $providerTypes = $resultArray->providerType;
                    ////Events DOB
                    $eventsDataDOB = "";
                    $countryLinksName = "";
                    $identityDocumentsNumber = "";
                    $identityDocumentsType = "";

                    $eventsArray = $resultArray->events;
                    if (count($eventsArray) > 0) {
                        foreach ($eventsArray as $eventsData) {
                            if ($eventsData->type == "BIRTH") {
                                $eventsDataDOB = $eventsData->fullDate;
                            }
                        }
                    }
                    ////Country Events
                    $countryLinksArray = $resultArray->countryLinks;
                    if (count($countryLinksArray) > 0) {
                        foreach ($countryLinksArray as $countryLinksData) {
                            if ($countryLinksData->type == "NATIONALITY") {
                                $countryLinksName = $countryLinksData->country->name;
                            }
                        }
                    }
                    ///Identity
                    $identityDocumentsArray = $resultArray->identityDocuments;
                    if (count($identityDocumentsArray) > 0) {
                        foreach ($identityDocumentsArray as $identityDocumentsData) {
                            $identityDocumentsType = $identityDocumentsData->type;
                            $identityDocumentsNumber = $identityDocumentsData->number;
                        }
                    }


                    echo "ReferenceId : " . $referenceId;
                    echo "<br>Name : " . $primaryName;
                    echo "<br>Category : " . $category;
                    echo "<br>providerTypes : " . $providerTypes;
                    echo "<br>Gender : " . $gender;
                    echo "<br>DOB : " . $eventsDataDOB;
                    echo "<br>Country : " . $countryLinksName;

                    if ($identityDocumentsType != "") {
                        echo "<br>Identity Type : " . $identityDocumentsType;
                        echo "<br>identityDocumentsNumber : " . $identityDocumentsNumber;
                    }
                    $BindData = '';
                    //$BindData = $referenceId."#".$primaryName."#".$category."#".$providerTypes."#".$gender."#".$eventsDataDOB."#".$countryLinksName."#".$identityDocumentsType."#".$identityDocumentsNumber;
                    $BindData = $referenceId . "#" . $primaryName;
                    ?>

                    <table>
                        <tr>
                            <td>
                                <input type="radio" name="kycdetailID" id="kycdetailID" value="<?php echo $BindData; ?>">
                                <input type="hidden" name="hiddenval" value="<?php echo $BindData; ?>">
                                <input type="button" value="getDetail" id="getfullDetail_<?php echo $i; ?>" name="getfullDetail" class="getfullDetail">
                            </td>
                        </tr>



                        <div id="profileDetail_<?php echo $i; ?>"></div>



                    </table>

                    <?php
                    $i++;
                    echo "<br>===========================<br>";
                    echo "<br>===========================<br>";
                }
            }
        }

        exit;
    }

    /**
     * Get all Similar records
     *
     * @return json user data
     */
    public function getUsersListAPI(Request $request) {


        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $dataArray = json_decode($response);


            // $dataArray = json_decode($data);
            // echo "<pre>";
            //print_r($dataArray->results);
            // echo count($dataArray->results);
            $i = 0;
            foreach ($dataArray->results as $resultArray) {
                $name = $resultArray->matchStrength;
                if ($resultArray->matchStrength == 'STRONG') {
                    $primaryName = $resultArray->primaryName;
                    $category = $resultArray->category;
                    $gender = $resultArray->gender;
                    $referenceId = $resultArray->referenceId;
                    $providerTypes = $resultArray->providerType;
                    ////Events DOB
                    $eventsDataDOB = "";
                    $countryLinksName = "";
                    $identityDocumentsNumber = "";
                    $identityDocumentsType = "";

                    $eventsArray = $resultArray->events;
                    if (count($eventsArray) > 0) {
                        foreach ($eventsArray as $eventsData) {
                            if ($eventsData->type == "BIRTH") {
                                $eventsDataDOB = $eventsData->fullDate;
                            }
                        }
                    }
                    ////Country Events
                    $countryLinksArray = $resultArray->countryLinks;
                    if (count($countryLinksArray) > 0) {
                        foreach ($countryLinksArray as $countryLinksData) {
                            if ($countryLinksData->type == "NATIONALITY") {
                                $countryLinksName = $countryLinksData->country->name;
                            }
                        }
                    }
                    ///Identity
                    $identityDocumentsArray = $resultArray->identityDocuments;
                    if (count($identityDocumentsArray) > 0) {
                        foreach ($identityDocumentsArray as $identityDocumentsData) {
                            $identityDocumentsType = $identityDocumentsData->type;
                            $identityDocumentsNumber = $identityDocumentsData->number;
                        }
                    }


                    echo "ReferenceId : " . $referenceId;
                    echo "<br>Name : " . $primaryName;
                    echo "<br>Category : " . $category;
                    echo "<br>providerTypes : " . $providerTypes;
                    echo "<br>Gender : " . $gender;
                    echo "<br>DOB : " . $eventsDataDOB;
                    echo "<br>Country : " . $countryLinksName;

                    if ($identityDocumentsType != "") {
                        echo "<br>Identity Type : " . $identityDocumentsType;
                        echo "<br>identityDocumentsNumber : " . $identityDocumentsNumber;
                    }
                    $BindData = '';
                    //$BindData = $referenceId."#".$primaryName."#".$category."#".$providerTypes."#".$gender."#".$eventsDataDOB."#".$countryLinksName."#".$identityDocumentsType."#".$identityDocumentsNumber;
                    $BindData = $referenceId . "#" . $primaryName;
                    ?>

                    <table>
                        <tr>
                            <td>
                                <input type="radio" name="kycdetailID" id="kycdetailID" value="<?php echo $BindData; ?>">
                                <input type="hidden" name="hiddenval" value="<?php echo $BindData; ?>">
                                <input type="button" value="getDetail" id="getfullDetail_<?php echo $i; ?>" name="getfullDetail" class="getfullDetail">
                            </td>
                        </tr>



                        <div id="profileDetail_<?php echo $i; ?>"></div>



                    </table>

                    <?php
                    $i++;
                    echo "<br>===========================<br>";
                    echo "<br>===========================<br>";
                }


                $curl = curl_init();
            }



            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                $rowData = json_decode($response);
                print_r($rowData);
            }
        }
    }

    /**
     * Get all Similar records
     *
     * @return json user data
     */
    public function getUsersListAPIDummy(Request $request) {

     
    //  echo "dddddd"; exit;
        ////////////////////////

        $gatwayhost = config('common.gatwayhost');
        $groupId = Helpers::getgroupId();
        $gatwayurl = config('common.gatwayurl');
        $gatwayhost = config('common.gatwayhost');
        $contentType = config('common.contentType');


        // $kycDetail = $this->wcapi->saveWcapiCall($arrKyc);

        $user_kyc_id = $request->get('user_kyc_id');



        $authSignature = $request->get('authorisation');
        $date = $request->get('currentDate');
        ;
        $Signature = $request->get('Signature');
        $ContentLength = $request->get('ContentLength');
        $Content = $request->get('content');

        $curl = curl_init();

        $endPoint = "https://" . $gatwayhost . $gatwayurl . "cases/screeningRequest";
        $postRequest = array(
            CURLOPT_URL => $endPoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $Content,
            //CURLOPT_HEADER => 1,
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Accept-Encoding: gzip, deflate",
                "Authorization: " . $authSignature,
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "Content-Length: " . $ContentLength,
                "Content-Type: " . $contentType,
                "Date: " . $date,
                "Host: " . $gatwayhost,
                "cache-control: no-cache"
            ),
        );



        curl_setopt_array($curl, $postRequest);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $response = curl_exec($curl);


        $postRequestSave = json_encode($postRequest);
        //$dataArray = json_decode($response);

      //   echo "<pre>";
      //  print_r($response);
       // exit;

        $arrKyc['user_kyc_id'] = $user_kyc_id;
        $arrKyc['request_for'] = 'screeningRequest';
        $arrKyc['api_request'] = $postRequestSave;
        $arrKyc['api_responce'] = $response;
        $arrKyc['http_responce'] = $httpcode;
        $arrKyc['by_whome'] = Auth()->user()->user_id;
        $arrKyc['created_by'] = Auth()->user()->user_id;
        $arrKyc['updated_by'] = Auth()->user()->user_id;

        $kycDetail = $this->wcapi->saveWcapiCall($arrKyc);

        $err = curl_error($curl);

        // $dataArray = json_decode($response);
        /////////////////////////////
        // $dataArray = json_decode($data);
        // echo "<pre>";
        //print_r($dataArray->results);
        // echo count($dataArray->results);
        //echo $httpcode; exit;
        $err = curl_error($curl);
        if ($err) {
            ?>
            <tr><td width="100%" colspan=8><?php echo "cURL Error #:" . $err; ?></td></tr>
            <?php
        } else {
            $dataArray = json_decode($response);


            $i = 0;
            if ($dataArray) {
                foreach ($dataArray->results as $resultArray) {
                    $matchStrength = $resultArray->matchStrength;
                    $primaryName = $resultArray->primaryName;
                    $category = $resultArray->category;
                    $gender = $resultArray->gender;
                    $referenceId = $resultArray->referenceId;
                    $wcUID = str_replace('e_tr_wci_', '', $referenceId);
                    $providerTypes = $resultArray->providerType;
                    ////Events DOB
                    $eventsDataDOB = "";
                    $countryLinksName = "";
                    $identityDocumentsNumber = "";
                    $identityDocumentsType = "";

                    $eventsArray = $resultArray->events;
                    if (count($eventsArray) > 0) {
                        foreach ($eventsArray as $eventsData) {
                            if ($eventsData->type == "BIRTH") {
                                $eventsDataDOB = $eventsData->fullDate;
                            }
                        }
                    }


                    // PEP Type
                    $pepTypeArray = [];
                    $type = "";
                    $pepcategoryName = "";
                    $pepTypeArray = $resultArray->categories;

                    foreach ($pepTypeArray as $pepTypeData) {
                        $allType = $pepTypeData;
                    }

                    //$allType = "";
                    $allType = str_replace('Law Enforcement', 'LE', $allType);
                    $allType = str_replace('Regulatory Enforcement', 'RE', $allType);
                    $allType = str_replace('Other Bodies', 'OB', $allType);
                    $type = $allType;

                    ////Country Events
                    $countryLinksArray = $resultArray->countryLinks;
                    if (count($countryLinksArray) > 0) {
                        foreach ($countryLinksArray as $countryLinksData) {
                            if ($countryLinksData->type == "NATIONALITY") {
                                $nationality = $countryLinksData->country->name;
                            }
                            if ($countryLinksData->type == "LOCATION") {
                                $countryLocation = $countryLinksData->country->name;
                            }
                        }
                    }
                    ///Identity
                    $identityDocumentsArray = $resultArray->identityDocuments;
                    if (count($identityDocumentsArray) > 0) {
                        foreach ($identityDocumentsArray as $identityDocumentsData) {
                            $identityDocumentsType = $identityDocumentsData->type;
                            $identityDocumentsNumber = $identityDocumentsData->number;
                        }
                    }

                    /*
                      echo "ReferenceId : ".$referenceId;
                      echo "<br>Name : ".$primaryName;
                      echo "<br>Category : ".$category;
                      echo "<br>providerTypes : ".$providerTypes;
                      echo "<br>Gender : ".$gender;
                      echo "<br>DOB : ".$eventsDataDOB;
                      echo "<br>Country : ".$countryLinksName;

                      if($identityDocumentsType!="") {
                      echo "<br>Identity Type : ".$identityDocumentsType;
                      echo "<br>identityDocumentsNumber : ".$identityDocumentsNumber;

                      }

                     */
                    $BindData = '';
                    //$BindData = $referenceId."#".$primaryName."#".$category."#".$providerTypes."#".$gender."#".$eventsDataDOB."#".$countryLinksName."#".$identityDocumentsType."#".$identityDocumentsNumber;
                    $BindData = $referenceId . "#" . $primaryName;
                    ?>




                    <tr>
                        <td width="10%"><?php echo $wcUID; ?></td>
                        <td width="8%"><?php echo $primaryName; ?></td>
                        <td width="7%"><?php echo $type; ?></td>
                        <td width="15%"><?php echo $category; ?></td>
                        <td width="15%"><?php echo $providerTypes; ?></td>
                        <td width="10%"><?php echo $gender; ?></td>
                        <td width="6%"><?php echo $eventsDataDOB; ?></td>
                        <td width="8%"><?php echo $countryLocation; ?></td>
                        <td width="7%"><?php echo $nationality; ?></td>
                        <td width="7%"><?php echo $matchStrength; ?></td>

                        <td class="text-right" width="7%">
                            <input type="radio" name="kycdetailID" id="kycdetailID" value="<?php echo $BindData; ?>">
                            <input type="hidden" name="hiddenval" value="<?php echo $BindData; ?>">

                            <input type="button" value="getDetail" id="getfullDetail_<?php echo $i; ?>" name="getfullDetail" class="getfullDetail">
                        </td>
                    </tr>
                    <tr>

                        <td colspan="11" id="profileDetail_<?php echo $i; ?>"></td>

                    </tr>

                    <?php
                    $i++;
                    echo "<br>===========================<br>";
                    echo "<br>===========================<br>";
                }
            } else {
                ?>
                <tr>

                    <td colspan="8" >No Record Found</td>

                </tr>

                <?php
            }
        }
    }

    public function getsimilarUsersList(Request $request) {

        $data = '';
        $dataArray = json_decode($data);
        ////////////////////////
        $gatwayhost     = config('common.gatwayhost');
        $groupId        = Helpers::getgroupId();
        $gatwayurl      = config('common.gatwayurl');
        $gatwayhost     = config('common.gatwayhost');
        $contentType    = config('common.contentType');
        $user_kyc_id    = $request->get('user_kyc_id');
        $authSignature  = $request->get('authorisation');
        $date           = $request->get('currentDate');
        $Signature      = $request->get('Signature');
        $ContentLength  = $request->get('ContentLength');
        $Content        = $request->get('content');
        $f_name         = $request->get('f_name');
        $m_name         = $request->get('m_name');
        $l_name         = $request->get('l_name');
        $gender         = $request->get('gender');
        $date_of_birth  = $request->get('date_of_birth');
        $countryCode    = $request->get('countryCode');
        $is_passport    = $request->get('is_passport');


       $curl = curl_init();

        $endPoint = "https://" . $gatwayhost . $gatwayurl . "cases/screeningRequest";
        //$authSignature = $authSignature."dsdsdsds";
        $postRequest = array(
            CURLOPT_URL => $endPoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            //CURLOPT_POSTFIELDS => "{\n  \"groupId\":\"0a3687cf-68e5-171f-9a3a-1654000000d5\",\n  \"entityType\": \"INDIVIDUAL\",\n  \"providerTypes\": [\n    \"WATCHLIST\"\n  ],\n  \"name\": \"putin\",\n  \"secondaryFields\":[{\"typeId\": \"SFCT_2\",\"dateTimeValue\":\"1952-07-10\"}],\n  \"customFields\":[]\n}",
            CURLOPT_POSTFIELDS => $Content,
            CURLOPT_HEADER => 1,
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Accept-Encoding: gzip, deflate",
                "Authorization: " . $authSignature,
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "Content-Length: " . $ContentLength,
                "Content-Type: " . $contentType,
                "Date: " . $date,
                "Host: " . $gatwayhost,
                "cache-control: no-cache"
            ),
        );
        curl_setopt_array($curl, $postRequest);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $responseArray = curl_exec($curl);
        list($header, $response) = explode("\r\n\r\n", $responseArray,2);
        //$response = "dddddd";
        $postRequestSave = json_encode($postRequest);
        //$dataArray = json_decode($response);

        $arrKyc['user_kyc_id'] = $user_kyc_id;
        $arrKyc['request_for'] = 'screeningRequest';
        $arrKyc['f_name'] = $f_name;
        $arrKyc['m_name'] = $m_name;
        $arrKyc['l_name'] = $l_name;
        $arrKyc['gender'] = $gender;
        $arrKyc['date_of_birth'] = $date_of_birth;
        $arrKyc['country_id'] = $countryCode;
        $arrKyc['is_passport']  = $is_passport;
        $arrKyc['request_for']  = 'screeningRequest';
        $arrKyc['api_request']  = $postRequestSave;
        $arrKyc['api_responce'] = $response;
        $arrKyc['http_responce']= $header;
        $arrKyc['by_whome']     = Auth()->user()->user_id;
        $arrKyc['created_by']   = Auth()->user()->user_id;
        $arrKyc['updated_by']   = Auth()->user()->user_id;


        $kycDetail = $this->wcapi->saveWcapiCall($arrKyc);
        $arrStatus['kyc_id'] = $user_kyc_id;
        $arrStatus['status_id'] = 1;
        $this->application->createstatus($arrStatus);
        
        $err = curl_error($curl);

        // $dataArray = json_decode($response);
        /////////////////////////////
        // $dataArray = json_decode($data);
        // echo "<pre>";
        //print_r($dataArray->results);
        // echo count($dataArray->results);
        //echo $httpcode; exit;
        $err = curl_error($curl);
        if ($err) {
            // echo "cURL Error #:" . $err;
            echo "Success";
        } else {
            $dataArray = json_decode($response);
            echo "Success";
            $i = 0;
        }
    }



    public function getgroupID(Request $request) {

        try {
            $userId = Auth()->user()->user_id;
           
            $grouIds = $this->application->getgroupID();
            if(!$grouIds) {
                $gatwayhost     = config('common.gatwayhost');
                $gatwayurl      = config('common.gatwayurl');
                $authSignature  = $request->get('authorisation');
                $date           = $request->get('currentDate');
//echo trim($authSignature); exit;
                $endUrl = "https://".$gatwayhost.$gatwayurl."groups";
                $ch = curl_init();

                $postRequest = array(
                        CURLOPT_URL => $endUrl,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "GET",
                        CURLOPT_HTTPHEADER => array(
                            "Date: " . $date,
                            "Authorization: " . $authSignature
                          ),
                    );

                curl_setopt_array($ch, $postRequest);
                $postRequest = json_encode($postRequest);
                //curl_setopt_array( $ch, $options);
                $response = curl_exec($ch);
                $dataArray = json_decode($response);
                    $err = curl_error($ch);
                    if ($err) {
                        echo "cURL Error #:" . $err;
                    }

                   if(count($dataArray) > 0) {
                    $group_id = $dataArray[0]->id;
                    $grouparray['group_id'] = $group_id;
                    $this->application->updategroupID($grouparray);
                   }
                }
        
            

           
             
        } catch (Exception $ex) { 
            $resData['otherMessage'] = Helpers::getExceptionMessage($ex);
            $resData['status'] = 'error';
            return json_encode($resData);
        }

        
    }


    public function getResolutionToolkit(Request $request) {

        try {
            $userId = Auth()->user()->user_id;

            $toolkitIds = $this->application->gettooklit();
            if(!$toolkitIds) {

                $gatwayhost     = config('common.gatwayhost');
                $gatwayurl      = config('common.gatwayurl');
                $APISecret      = config('common.APISecret');
                $apiKey         = config('common.apiKey');
               // $groupId        = config('common.groupId');
                //$groupId        = "{{Helpers::getgroupId()}}";
                $authSignature  = $request->get('authorisation');
                $date           = $request->get('currentDate');
                 $groupId        = $request->get('groupId');
                $endUrl = "https://".$gatwayhost.$gatwayurl."groups/".$groupId."/resolutionToolkits";
                $ch = curl_init();
                curl_setopt_array($ch, array(
                CURLOPT_URL => $endUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "Date: " . $date,
                    "Authorization: " . $authSignature
                  ),
                ));

//echo $endUrl;

    //curl_setopt_array( $ch, $options);
    $response = curl_exec($ch);
    $dataArray = json_decode($response);
        $err = curl_error($ch);
        if ($err) {
            echo "cURL Error #:" . $err;
            echo "Success";
        }

      // echo "debug";exit;
       //delete previous Group ID
       $this->application->deleteToolkit();
      // $this->application->updatetoolkit($id=null, $statusArray);
       if($dataArray->WATCHLIST->resolutionFields->statuses) {
        $statuses = $dataArray->WATCHLIST->resolutionFields->statuses;
        $risks = $dataArray->WATCHLIST->resolutionFields->risks;
        $reasons = $dataArray->WATCHLIST->resolutionFields->reasons;

       
        $pid = 1;
        foreach($statuses as $statuse) {
            $type = $statuse->type;
            $id = $statuse->id;
            $statusArray['id'] = $pid;
            $statusArray['type_for'] = 'statuses';
            $statusArray['type'] = $type;
            $statusArray['value_id'] = $id;
            $statusArray['is_active'] = 1;
            $this->application->updatetoolkit($id=null, $statusArray);
            $pid++;
        }

        foreach($risks as $risk) {
           $type = $risk->label;
            $id = $risk->id;
            $riskArray['id'] = $pid;
            $riskArray['type_for']  = 'risks';
            $riskArray['type']      = $type;
            $riskArray['value_id']  = $id;
            $riskArray['is_active'] = 1;
            $this->application->updatetoolkit($id=null, $riskArray);
            $pid++;
           
        }


        foreach($reasons as $reason) {
           $type = $reason->label;
            $id = $reason->id;
            $reasonArray['id']    = $pid;
            $reasonArray['type_for']    = 'reason';
            $reasonArray['type']        = $type;
            $reasonArray['value_id']    = $id;
            $reasonArray['is_active']   = 1;
            $this->application->updatetoolkit($id=null, $reasonArray);
            $pid++;
        }

        echo "Success";
       }
    }





        } catch (Exception $ex) {
            $resData['otherMessage'] = Helpers::getExceptionMessage($ex);
            $resData['status'] = 'error';
            return json_encode($resData);
        }


    }

    public function getResolutionToolkitvar(Request $request) {

        try {
            $userId = Auth()->user()->user_id;

          //  $userKycId = $this->application->getgroupID();
             $id  = $request->get('id');
             $type_for           = $request->get('type_for');

             $valuedata = $this->application->toolketvaluebyId($id);
             echo $valuedata; //exit;



        } catch (Exception $ex) {
            $resData['otherMessage'] = Helpers::getExceptionMessage($ex);
            $resData['status'] = 'error';
            return json_encode($resData);
        }


    }


    public function getUsersDetailAPIDummy(Request $request) {

        $gatwayhost     = config('common.gatwayhost');
        $groupId        = Helpers::getgroupId();
        $gatwayurl      = config('common.gatwayurl');
        $gatwayhost     = config('common.gatwayhost');
        $contentType    = config('common.contentType');
        $user_kyc_id    = $request->get('user_kyc_id');
        $authSignature  = $_POST['authorisation'];
        $date           = $_POST['currentDate'];
        $Signature      = $_POST['Signature'];
        $profileID      = $_POST['profileID'];
        $primaryId      = $_POST['primaryId'];
        //scoreing Data
        $matchStrenght  = $_POST['matchStrenght'];
        $pep            = $_POST['pep'];
        $passport       = $_POST['passport'];
        $caseId         = $_POST['caseId'];
        $caseSystemId   = $_POST['systemcaseId'];
        $resultId       = $_POST['resultId'];
        $rank   = "";

        //echo $matchStrenght."==>".$pep."===>".$passport; exit;

        $option_key = "";
        if ($matchStrenght == "EXACT") {
            $option_key = "4_1";
            
        } else if ($matchStrenght == "STRONG") {
            $option_key = "3_2";
           
        } else if ($matchStrenght == "MEDIUM") {
            $option_key = "2_3";
            
        }
        else if ($matchStrenght == "WEAK") {
            $option_key = "1_4";
            
        }

        if($matchStrenght == "EXACT" || $matchStrenght == "STRONG" || $matchStrenght == "MEDIUM" || $matchStrenght == "WEAK") {
            $rank = 5;
        } else {
            $rank = 1;
        }


        if ($matchStrenght!= "") {
            $assesment_id = 2;
            $dataAssignmentUpdate = [];
            $dataAssignmentUpdate['rank'] = $rank;
            $dataAssignmentUpdate['option_key'] = $option_key;
            $response = $this->userRepo->updateData($dataAssignmentUpdate, $user_kyc_id, $assesment_id);
        }

        if ($passport!= "") {
            $assesment_id = 3;
            $dataAssignmentUpdate = [];
            $optionValue = "0_1";
            $dataAssignmentUpdate['option_key'] = $optionValue;
            $response = $this->userRepo->updateData($dataAssignmentUpdate, $user_kyc_id, $assesment_id);
        }

        if ($pep!= "") {
            $assesment_id = 4;
            $dataAssignmentUpdate = [];
            $optionValue = "0_5";
            $dataAssignmentUpdate['option_key'] = $optionValue;
            $response = $this->userRepo->updateData($dataAssignmentUpdate, $user_kyc_id, $assesment_id);
        }
        $endPoint = "https://" . $gatwayhost . $gatwayurl . "reference/profile/" . $profileID;
        //$endPoint = 'https://rms-world-check-one-api-pilot.thomsonreuters.com/v1/reference/profile/'.$profileID;

        $curl = curl_init();
        $reqData = array(
            CURLOPT_URL => $endPoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: " . $authSignature,
                "Date: " . $date,
                "cache-control: no-cache"
            ),
        );

        curl_setopt_array($curl, $reqData);

        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            ?>

            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th width="100%" colspan="8"><?php echo $err; ?></th>
                    </tr>
                </thead>
            </table>
            <?php
        } else {
            $resultArray = json_decode($response);
            //dd($response);
            $reqDataSave = json_encode($reqData);
            $arrKyc['user_kyc_id']  = $user_kyc_id;
            $arrKyc['request_for']  = 'profile';
            $arrKyc['parent_id']    = $primaryId;
            //Profile score Date 
            $arrKyc['match_strenght'] = $matchStrenght;
            $arrKyc['pep'] = $pep;
            $arrKyc['passport'] = $passport;
            $arrKyc['endpoint'] = $endPoint;
            $arrKyc['profile'] = $profileID;
            $arrKyc['api_request'] = $reqDataSave;
            $arrKyc['api_responce'] = $response;
            $arrKyc['by_whome'] = Auth()->user()->user_id;
            $arrKyc['created_by'] = Auth()->user()->user_id;
            $arrKyc['updated_by'] = Auth()->user()->user_id;

            $kycDetail = $this->wcapi->saveWcapiCall($arrKyc);
            $wcapiInsertedID = $kycDetail->wcapi_req_res_id;
            // echo "==>".$wcapiInsertedID; exit;

            $category = (string) $resultArray->category;
            $gender   = $resultArray->gender;
            $referenceId = '';
            if(isset($resultArray->entityType) && $resultArray->entityType == 'INDIVIDUAL') {
            if(isset($resultArray->referenceId)) {
               // $referenceId = $resultArray->referenceId;
            }

            $referenceId = $profileID;
            //$providerTypes = $resultArray->providerType;

            $fullName = "";
            $roleTitle = "";
            $roleTitle = "";
            $eventsDataDOB = "";
            $countryLinksName = "";
            $sourcesName = "";
            $sourcesDescription = "";
            $webLinks = "";
            $identityDocumentsNumber = "";
            $identityDocumentsType = "";
            $identityDocumentsissueDate = "";
            $identityDocumentsexpiryDate = "";

            ///GET NAMES

            $responceProfileId = $wcapiInsertedID;
            $nameArray = $resultArray->names;
            if (count($nameArray) > 0) {
                foreach ($nameArray as $nameData) {
                    if ($nameData->type == "PRIMARY") {
                        $fullName = $nameData->fullName;
                    }
                }
            }


            ////Events DOB
            $eventsArray = $resultArray->events;
            if (count($eventsArray) > 0) {
                foreach ($eventsArray as $eventsData) {
                    if ($eventsData->type == "BIRTH") {
                        $eventsDataDOB = $eventsData->fullDate;
                    }
                }
            }


            ////Role Title
            $rolesArray = $resultArray->roles;
            if (count($rolesArray) > 0) {
                foreach ($rolesArray as $roleData) {
                    $roleTitle = $roleData->title . ",";
                }
            }


            ////weblinks
            $weblinksArray = $resultArray->weblinks;
            if (count($weblinksArray) > 0) {

                foreach ($weblinksArray as $weblinksData) {
                    if ($weblinksData->uri != "") {
                        $webLinks.= $weblinksData->uri . "<br>";
                    }
                }
            }

            /// Source Links
            $sourcesArray = $resultArray->sources;
            if (count($sourcesArray) > 0) {

                foreach ($sourcesArray as $sourcesData) {
                    if ($sourcesData->name != "") {
                        $sourcesName = $sourcesData->name;
                        $sourcesDescription = (string) $sourcesData->type->category->description;
                    }
                }
            }

            ////Country Events
            $countryLinksArray = $resultArray->countryLinks;
            if (count($countryLinksArray) > 0) {
                foreach ($countryLinksArray as $countryLinksData) {
                    if ($countryLinksData->type == "NATIONALITY") {
                        $countryLinksName = $countryLinksData->country->name;
                    }
                }
            }
            ///Identity
            $identityDocumentsArray = $resultArray->identityDocuments;
            $stringidentityDocuments = "";
            if (count($identityDocumentsArray) > 0) {

                foreach ($identityDocumentsArray as $identityDocumentsData) {

                    $identityDocumentsType = "";
                    $identityDocumentsNumber = "";
                    $identityDocumentsissueDate = "";
                    $identityDocumentsexpiryDate = "";
                    $identityDocumentsType = $identityDocumentsData->type;
                    $identityDocumentsNumber = $identityDocumentsData->number;
                    $identityDocumentsissueDate = $identityDocumentsData->issueDate;
                    $identityDocumentsexpiryDate = $identityDocumentsData->expiryDate;

                    $stringidentityDocuments.= "Document Type: " . $identityDocumentsType . "<br>Document Number:" . $identityDocumentsNumber . "<br>Document Issue Date:" . $identityDocumentsissueDate . "<br>Expiry Date:" . $identityDocumentsexpiryDate;
                }
            }

            $detail             = $fullName . "<br>" . $gender . "<br>" . $eventsDataDOB;
            $routeDownload      = route('report_download', ['userKycId' => $user_kyc_id, 'referenceId' => $referenceId, 'primaryId' => $primaryId]);
            $routeapprove       = route('individual_api_approve', ['id' => $user_kyc_id, 'profileId' => $referenceId, 'responceProfileId' => $responceProfileId, 'parent_id' => $primaryId]);
            $routedisapprove    = route('individual_api_disapprove', ['id' => $user_kyc_id, 'profileId' => $referenceId, 'responceProfileId' => $responceProfileId, 'parent_id' => $primaryId]);
            $routeresolve      = route('individual_api_resolve', ['userKycId' => $user_kyc_id, 'profileId' => $referenceId, 'responceProfileId' => $responceProfileId, 'parent_id' => $primaryId, 'primaryId' => $primaryId, 'case_id' => $caseId, 'result_id' => $resultId, 'caseSystemId' => $caseSystemId]);



            ?>


            <table class="table table-striped table-sm brown-th table-border-cls">
                <thead>
                    <tr>
                        <th style="color:#fff" width="100%" colspan="2">More Detail</th>
                    </tr>
                </thead>
                <tbody id="similarRecords">
                    <tr>
                        <td width="20%" valign="top">Reference Id</td>
                        <td width="80%" valign="top"><?php echo $referenceId?></td>
                        
                    </tr>
                    <tr>
                        <td width="20%" valign="top">Detail</td>
                        <td width="80%" valign="top"><?php echo $detail?></td>
                    </tr>
                    <tr>
                        <td width="20%" valign="top">Role Title</td>
                        <td width="80%" valign="top"><?php echo trim($roleTitle,",")?></td>

                    </tr>
                    <tr>
                        <td width="20%" valign="top">Identity Documents</td>
                        <td width="80%" valign="top"><?php echo $stringidentityDocuments?></td>
                    </tr>
                    <tr>
                        <td width="20%" valign="top">Country</td>
                        <td width="80%" valign="top"><?php echo $countryLinksName?></td>
                    </tr>
                     <tr>
                        
                        <td width="100%" valign="top" colspan="2">

                             <a href="<?php echo $routeDownload;?>" target="_blank"><button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#Approved_Action">View Pdf</button></a>
                            <!--<a type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#btn-approved-iframe"  data-url="<?php echo $routeapprove?>" data-height="100px" data-width="100%" data-placement="top">Approved</a>
                            <a type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#btn-disapproved-iframe" data-url="<?php echo $routedisapprove?>" data-height="100px" data-width="100%" data-placement="top">Disapproved</a>-->
                            <!--<a type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#btn-resolution-iframe" data-url="<?php echo $routeresolve?>" data-height="350px" data-width="100%" data-placement="top">Resolve</a>-->
                        </td>

                    </tr>
                   
                </tbody>
            </table>


            <?php
            } else { ?>
                <table class="table table-striped table-sm brown-th table-border-cls">
                <tbody id="similarRecords">
                     <tr>
                        <td width="100%" valign="top" >No Match found</td>
                    </tr>

                </tbody>
            </table>
                
            <?php }
        }
    }



    public function getuserResolved(Request $request) {


       // $result_id = $_POST['result_id'];
        $gatwayhost     = config('common.gatwayhost');
        $groupId        = Helpers::getgroupId();
        $gatwayurl      = config('common.gatwayurl');
        $gatwayhost     = config('common.gatwayhost');
        $contentType    = config('common.contentType');
        $user_kyc_id    = $request->get('userKycId');
        $primaryId      = $_POST['primaryId'];
        $responceProfileId      = $_POST['responceProfileId'];
        $authSignature  = $_POST['authorisation'];
        $date           = $_POST['currentDate'];
        $caseId         = $_POST['caseId'];
        $case_system_id = $_POST['case_system_id'];
       // $userKycId      = $_POST['userKycId'];
        $contentLength  = $_POST['contentLength'];
        $Content        = $_POST['content'];
      
        $curl = curl_init();
        $endPoint = "https://" . $gatwayhost . $gatwayurl . "cases/" . $case_system_id."/results/resolution";

        $postRequest = array(
          CURLOPT_URL => $endPoint,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "PUT",
          CURLOPT_POSTFIELDS =>$Content,
          CURLOPT_HTTPHEADER => array(
            "Date: " . $date,
            "Content-Type: application/json",
            "Authorization: ".$authSignature,
            "Content-Length: " . $contentLength
          ),
        );
       curl_setopt_array($curl, $postRequest);

        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);
        curl_close($curl);
        //for testing
        // $httpcode = 204;
        // $response ="responce test";
        //////save log data Data start
            $postRequest = json_encode($postRequest);
            $arrKyc['user_kyc_id']  = $user_kyc_id;
            $arrKyc['endpoint']     =    $endPoint;
            $arrKyc['request_for']  = 'resolution';
            $arrKyc['parent_id']    = $responceProfileId;
            $arrKyc['api_request'] = $postRequest;
            $arrKyc['api_responce'] = $response;
            $arrKyc['http_responce'] = $httpcode;

            $arrKyc['by_whome'] = Auth()->user()->user_id;
            $arrKyc['created_by'] = Auth()->user()->user_id;
            $arrKyc['updated_by'] = Auth()->user()->user_id;
            $kycDetail = $this->wcapi->saveWcapiCall($arrKyc);
            //exit;

//////save log data Data End
        if($httpcode == "204") {
            //Insert Into Tables:
            echo "Success";
        } else {
            echo $response."<br>".$httpcode."<br>".$err; exit;
        }
        
    }

    public function getuserResolvedAll(Request $request) {


        $result_id      = $_POST['result_id'];
        $gatwayhost     = config('common.gatwayhost');
        $groupId        = Helpers::getgroupId();
        $gatwayurl      = config('common.gatwayurl');
        $gatwayhost     = config('common.gatwayhost');
        $contentType    = config('common.contentType');
        $user_kyc_id    = $request->get('userKycId');
        $primaryId      = $_POST['primaryId'];
       
        $authSignature  = $_POST['authorisation'];
        $date           = $_POST['currentDate'];
        $caseId         = $_POST['caseId'];
        $case_system_id = $_POST['case_system_id'];
       // $userKycId      = $_POST['userKycId'];
        $contentLength  = $_POST['contentLength'];
        $Content        = $_POST['content'];

        $curl = curl_init();
        $endPoint = "https://" . $gatwayhost . $gatwayurl . "cases/" . $case_system_id."/results/resolution";

        $postRequest = array(
          CURLOPT_URL => $endPoint,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "PUT",
          CURLOPT_POSTFIELDS =>$Content,
          CURLOPT_HTTPHEADER => array(
            "Date: " . $date,
            "Content-Type: application/json",
            "Authorization: ".$authSignature,
            "Content-Length: " . $contentLength
          ),
        );
       curl_setopt_array($curl, $postRequest);

        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);
        curl_close($curl);
        //for testing
        // $httpcode = 204;
        // $response ="responce test";
        //////save log data Data start
            $postRequest = json_encode($postRequest);
            $arrKyc['user_kyc_id']  = $user_kyc_id;
            $arrKyc['endpoint']     =    $endPoint;
            $arrKyc['request_for']  = 'resolution';
            $arrKyc['parent_id']    = $primaryId;
            $arrKyc['api_request'] = $postRequest;
            $arrKyc['api_responce'] = $response;
            $arrKyc['http_responce'] = $httpcode;

            $arrKyc['by_whome'] = Auth()->user()->user_id;
            $arrKyc['created_by'] = Auth()->user()->user_id;
            $arrKyc['updated_by'] = Auth()->user()->user_id;
            $kycDetail = $this->wcapi->saveWcapiCall($arrKyc);
            //exit;

//////save log data Data End
        if($httpcode == "204") {
            //Insert Into Tables:
            echo "Success";
        } else {
            echo $response."<br>".$httpcode."<br>".$err; exit;
        }

    }
    public function updatekycApprove(Request $request) {

        $user_kyc_id = $request->get('user_kyc_id');

        $dataKycUpdate = [];
        $dataKycUpdate['is_api_pulled'] = 1;
        $dataKycUpdate['is_approve'] = 1;

        $response = $this->application->updateUserKyc($user_kyc_id, $dataKycUpdate);
    }

    ////////////////////

    /**
     * Get all Corp Similar records
     *
     * @return json user data
     */
    public function getUsersListAPICorp(Request $request) {

        //Dummy Data Start
       
        //$dataArray = json_decode($data);
        //Dummy Data End
        ////////////////////////

        $gatwayhost = config('common.gatwayhost');
        $groupId = Helpers::getgroupId();
        $gatwayurl = config('common.gatwayurl');
        $gatwayhost = config('common.gatwayhost');
        $contentType = config('common.contentType');
        $user_kyc_id = $request->get('user_kyc_id');



        $authSignature = $request->get('authorisation');
        $date           = $request->get('currentDate');
        $CorpName       = $request->get('CorpName');
        $CorpCountry    = $request->get('CorpCountry');
        $Signature      = $request->get('Signature');
        $ContentLength = $request->get('ContentLength');
        $Content = $request->get('content');

        $curl = curl_init();

        $endPoint = "https://" . $gatwayhost . $gatwayurl . "cases/screeningRequest";
        $postRequest = array(
            //CURLOPT_URL => "https://rms-world-check-one-api-pilot.thomsonreuters.com/v1/cases/screeningRequest",
            CURLOPT_URL => $endPoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            //CURLOPT_POSTFIELDS => "{\n  \"groupId\":\"0a3687cf-68e5-171f-9a3a-1654000000d5\",\n  \"entityType\": \"INDIVIDUAL\",\n  \"providerTypes\": [\n    \"WATCHLIST\"\n  ],\n  \"name\": \"putin\",\n  \"secondaryFields\":[{\"typeId\": \"SFCT_2\",\"dateTimeValue\":\"1952-07-10\"}],\n  \"customFields\":[]\n}",
            CURLOPT_POSTFIELDS => $Content,
            CURLOPT_HEADER => 1,
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Accept-Encoding: gzip, deflate",
                "Authorization: " . $authSignature,
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "Content-Length: " . $ContentLength,
                "Content-Type: " . $contentType,
                "Date: " . $date,
                "Host: " . $gatwayhost,
                "cache-control: no-cache"
            ),
        );



        curl_setopt_array($curl, $postRequest);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        //$response = curl_exec($curl);
        $responseArray = curl_exec($curl);
        list($header, $response) = explode("\r\n\r\n", $responseArray,2);


        $err = curl_error($curl);

        $postRequestSave = json_encode($postRequest);
        //$postRequestSave = json_encode($response);
        //$dataArray = json_decode($response);

        $arrKyc['user_kyc_id']  = $user_kyc_id;
        $arrKyc['request_for']  = 'screeningRequest-corp';
        $arrKyc['api_request']  = $postRequestSave;
        $arrKyc['api_responce'] = $response;
        $arrKyc['http_responce']= $header;
        $arrKyc['org_name']     = $CorpName;
        $arrKyc['org_country_id']  = $CorpCountry;
        $arrKyc['by_whome']     = Auth()->user()->user_id;
        $arrKyc['created_by']   = Auth()->user()->user_id;
        $arrKyc['updated_by']   = Auth()->user()->user_id;

        $kycDetail = $this->wcapi->saveWcapiCall($arrKyc);
        // $dataArray = json_decode($response);
        /////////////////////////////

        if ($err) {
            ?>
            <tr><td width="100%" colspan="5"><?php echo "cURL Error #:" . $err; ?></td></tr>
        <?php
        } else {
            $dataArray = json_decode($response);
         
            $i = 0;
            if ($dataArray) {
                foreach ($dataArray->results as $resultArray) {
                    //$name           = $resultArray->matchStrength;
                    $matchStrength = $resultArray->matchStrength;
                    $primaryName = $resultArray->primaryName;
                    $category = $resultArray->category;
                    $referenceId = $resultArray->referenceId;
                    $identityDocumentsArray = $resultArray->identityDocuments;
                    if (count($identityDocumentsArray) > 0) {
                        foreach ($identityDocumentsArray as $identityDocumentsData) {
                            $identityDocumentsType = $identityDocumentsData->type;
                            $identityDocumentsNumber = $identityDocumentsData->number;
                        }
                    }


                    $countryLinksArray = $resultArray->countryLinks;
                    if (count($countryLinksArray) > 0) {
                        foreach ($countryLinksArray as $countryLinksData) {
                            if ($countryLinksData->type == "REGISTEREDIN") {
                                $countryLinksName = $countryLinksData->country->name;
                            }
                        }
                    }

                    $BindData = '';
                    //$BindData = $referenceId."#".$primaryName."#".$category."#".$providerTypes."#".$gender."#".$eventsDataDOB."#".$countryLinksName."#".$identityDocumentsType."#".$identityDocumentsNumber;
                    $BindData = $referenceId . "#" . $primaryName;
                    ?>




                    <tr>
                        <td width="15%"><?php echo $referenceId; ?></td>
                        <td width="15%"><?php echo $primaryName; ?></td>
                        <td width="20%"><?php echo $category; ?></td>
                        <td width="20%"><?php echo $countryLinksName; ?></td>
                        <td width="20%"><?php echo $matchStrength; ?></td>

                        <td class="text-right" width="10%">
                            <input type="radio" name="kycdetailID" id="kycdetailID" value="<?php echo $BindData; ?>">
                            <input type="hidden" name="hiddenval" value="<?php echo $BindData; ?>">

                            <input type="button" value="getDetail" id="getfullDetailCorp_<?php echo $i; ?>" name="getfullDetailCorp" class="getfullDetailCorp">
                        </td>
                    </tr>
                    <tr>

                        <td colspan="8" id="profileDetail_<?php echo $i; ?>"></td>

                    </tr>

                    <?php
                    $i++;
                }
            } else {
                ?>
                <tr><td colspan="8" >No Record Found</td></tr>
            <?php
            }
        }
    }

    public function getCorpDetailAPIDummy(Request $request) {

        $gatwayhost     = config('common.gatwayhost');
        $groupId        = Helpers::getgroupId();
        $gatwayurl      = config('common.gatwayurl');
        $gatwayhost     = config('common.gatwayhost');
        $contentType    = config('common.contentType');

        $user_kyc_id = $request->get('user_kyc_id');
        $authSignature = $_POST['authorisation'];
        $date = $_POST['currentDate'];
        $Signature = $_POST['Signature'];
        $profileID = $_POST['profileID'];
        $parent_id = $_POST['parent_id'];
        $caseId         = $_POST['caseId'];
        $caseSystemId   = $_POST['systemcaseId'];
        $resultId       = $_POST['resultId'];
        $rank   = "";

        //scoreing Data
        $rank   = "";
        $matchStrenght  = $_POST['matchStrenght'];
        $pep            = $_POST['pep'];
        //echo $matchStrenght."==>".$pep."===>".$passport; exit;
        $option_key = "";
        

        if($matchStrenght == "EXACT" || $matchStrenght == "STRONG" || $matchStrenght == "MEDIUM" || $matchStrenght == "WEAK") {
            $rank = 5;
        } else {
            $rank = 1;
        }

        if ($matchStrenght!= "") {
            $assesment_id = 2;
            $dataAssignmentUpdate = [];
            $dataAssignmentUpdate['rank'] = $rank;
            $dataAssignmentUpdate['option_key'] = $option_key;
            $response = $this->userRepo->updateData($dataAssignmentUpdate, $user_kyc_id, $assesment_id);
        }

        

        if ($pep!= "") {
            $assesment_id = 3;
            $dataAssignmentUpdate = [];
            $optionValue = "0_1";
            $dataAssignmentUpdate['option_key'] = $optionValue;
            $response = $this->userRepo->updateData($dataAssignmentUpdate, $user_kyc_id, $assesment_id);
        }



        $endPoint = "https://" . $gatwayhost . $gatwayurl . "reference/profile/" . $profileID;
        //$endPoint = 'https://rms-world-check-one-api-pilot.thomsonreuters.com/v1/reference/profile/'.$profileID;

        $curl = curl_init();
        $reqData = array(
            CURLOPT_URL => $endPoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: " . $authSignature,
                "Date: " . $date,
                "cache-control: no-cache"
            ),
        );

        curl_setopt_array($curl, $reqData);
        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);
        curl_close($curl);
//$err = false;
        if ($err) {
            ?>

            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th width="100%" colspan="8"><?php echo $err; ?></th>
                    </tr>
                </thead>
            </table>
            <?php
        } else {
            $resultArray = json_decode($response, JSON_UNESCAPED_UNICODE);

//dd($response);
            $reqDataSave = json_encode($reqData);
            $arrKyc['user_kyc_id'] = $user_kyc_id;
            $userKycId = $user_kyc_id;
            $arrKyc['request_for'] = 'corp-profile';
            $arrKyc['endpoint'] = $endPoint;
            $arrKyc['profile'] = $profileID;
            $arrKyc['parent_id'] = $parent_id;
            $arrKyc['match_strenght'] = $matchStrenght;
            $arrKyc['pep'] = $pep;
            $arrKyc['api_request'] = $reqDataSave;
            $arrKyc['api_responce'] = $response;
            $arrKyc['by_whome'] = Auth()->user()->user_id;
            $arrKyc['created_by'] = Auth()->user()->user_id;
            $arrKyc['updated_by'] = Auth()->user()->user_id;

            $kycDetail = $this->wcapi->saveWcapiCall($arrKyc);
            $wcapiInsertedID = $kycDetail->wcapi_req_res_id;
            $primaryId = $wcapiInsertedID;
            $responceProfileId = $wcapiInsertedID;


            $referenceId = $resultArray['entityId'];
            $category    = $resultArray['category'];
                                             //$providerTypes = $resultArray->providerType;
                                            $nameArray = $resultArray['names'];
                                            if(count($nameArray) > 0) {
                                                foreach($nameArray as $nameData)
                                                   {
                                                    if($nameData['type']=="PRIMARY") {
                                                       $fullName = $nameData['fullName'];
                                                    }
                                                   }

                                            }

                                            ////weblinks
                                            $weblinksArray = $resultArray['weblinks'];
                                            $webLinks = "";
                                            if(count($weblinksArray) > 0) {

                                                foreach($weblinksArray as $weblinksData)
                                                   {
                                                    if($weblinksData['uri']!="") {
                                                       $webLinks.= $weblinksData['uri']."<br>";
                                                     }
                                                   }

                                            }
                                            /// Source Links
                                            $sourcesArray = $resultArray['sources'];
                                            if(count($sourcesArray) > 0) {

                                                foreach($sourcesArray as $sourcesData)
                                                   {
                                                    if($sourcesData['name']!="") {
                                                       $sourcesName = $sourcesData['name'];
                                                       $sourcesDescription = $sourcesData['type']['category']['description'];
                                                     }
                                                   }
                                            }

                                            //address
                                            $corpAddress = $resultArray['addresses'];
                                            $corpmainAddress = "";
                                            if(count($corpAddress) > 0) {
                                                foreach($corpAddress as $corpAdd)
                                                   {
                                                    $street     =   "";
                                                    $region     =   "";
                                                    $postCode   =   "";
                                                    $city       =   "";
                                                    $country    =   "";
                                                    if($corpAdd['street']) {
                                                        $street = $corpAdd['street'].", ";
                                                    }
                                                    if($corpAdd['region']) {
                                                        $region = $corpAdd['region'].", ";
                                                    }
                                                    if($corpAdd['postCode']) {
                                                        $postCode = $corpAdd['postCode'].", ";
                                                    }
                                                    if($corpAdd['city']) {
                                                        $city = $corpAdd['city'].", ";
                                                    }
                                                    if($corpAdd['country']) {
                                                        $country = $corpAdd['country']['name'].", ";
                                                    }
                                                    $corpmainAddress.= $street.$region.$postCode.$city.$country."<br>";
                                                   }
                                            }


                                            ////Country Events
                                            $countryLinksArray = $resultArray['countryLinks'];
                                       if(count($countryLinksArray) > 0) {
                                           foreach($countryLinksArray as $countryLinksData)
                                              {
                                               if($countryLinksData['type']=="NATIONALITY") {
                                                  $nationality = $countryLinksData['country']['name'];
                                               }
                                               if($countryLinksData['type']=="LOCATION") {
                                                  $countryLocation = $countryLinksData['country']['name'];
                                               }

                                               if($countryLinksData['type']=="REGISTEREDIN") {
                                                  $countryRegisterd = $countryLinksData['country']['name'];
                                               }




                                              }
                                       }
                                            ///Identity
                                            $stringidentityDocuments = '';
                                            $identityDocumentsArray = $resultArray['identityDocuments'];
                                            if(count($identityDocumentsArray) > 0) {
                                                
                                                foreach($identityDocumentsArray as $identityDocumentsData)
                                                   {
                                                    $identityDocumentsType = $identityDocumentsData['type'];
                                                    $identityDocumentsNumber = $identityDocumentsData['number'];
                                                    $identityDocumentsissueDate = $identityDocumentsData['issueDate'];
                                                    $identityDocumentsexpiryDate = $identityDocumentsData['expiryDate'];
                                                     $stringidentityDocuments.= "Document Type: ".$identityDocumentsType."<br>Document Number:".$identityDocumentsNumber."<br>Document Issue Date:".$identityDocumentsissueDate."<br>Expiry Date:".$identityDocumentsexpiryDate;

                                                   }
                                            }


                                        //Get User Type
                $routeDownload = route('report_download', ['userKycId' => $userKycId, 'primaryId' => $responceProfileId]);
                $routeapprove = route('individual_api_approve', ['id' => $userKycId, 'profileId' => $referenceId, 'responceProfileId' => $responceProfileId, 'parent_id' => $parent_id]);
                $routedisapprove = route('individual_api_disapprove', ['id' => $userKycId, 'profileId' => $referenceId, 'responceProfileId' => $responceProfileId, 'parent_id' => $parent_id]);
                $routeresolve      = route('individual_api_resolve', ['userKycId' => $user_kyc_id, 'profileId' => $referenceId, 'responceProfileId' => $responceProfileId, 'parent_id' => $parent_id, 'primaryId' => $parent_id, 'case_id' => $caseId, 'result_id' => $resultId, 'caseSystemId' => $caseSystemId]);

               // $routeresolve      = route('individual_api_resolve', ['userKycId' => $user_kyc_id, 'profileId' => $referenceId, 'responceProfileId' => $responceProfileId, 'parent_id' => $primaryId, 'primaryId' => $primaryId, 'case_id' => $caseId, 'result_id' => $resultId, 'caseSystemId' => $caseSystemId]);


                ?>

            <!-- Seperation -->

                <table class="table table-striped table-sm brown-th table-border-cls">
                <thead>
                    <tr>
                        <th style="color:#fff" width="100%" colspan="2">More Detail</th>


                    </tr>
                </thead>
                    <tbody id="similarRecords">
                        <tr>
                        <td width="20%" valign="top">ReferenceId</td>
                        <td width="80%" valign="top"><?php echo $referenceId?></td>
                        </tr>
                        <tr>
                        <td width="20%" valign="top">Company Name</td>
                        <td width="80%" valign="top"><?php echo $fullName?></td>
                        </tr>
                        <tr>
                        <td width="20%" valign="top">Category</td>
                        <td width="80%" valign="top"><?php echo $category;?></td>

                        </tr>
                        <tr>
                        <td width="20%" valign="top">Country</td>
                        <td width="80%" valign="top"><?php echo $countryRegisterd?></td>
                        </tr>
                        <tr>
                        <td width="20%" valign="top">Identity Documents</td>
                        <td width="80%" valign="top"><?php echo $stringidentityDocuments?></td>

                        </tr>
                        <tr>
                        <td width="20%" valign="top">Source Links</td>
                        <td width="80%" valign="top"><?php echo $sourcesName?></td>
                        </tr>
                        <tr>
                        <td width="100%" valign="top" colspan="2">
                            <a href="<?php echo $routeDownload;?>" target="_blank"><button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#Approved_Action" >View Pdf</button></a>
                        <!--<a type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#btn-approved-iframe"  data-url="<?php echo $routeapprove?>" data-height="150px" data-width="100%" data-placement="top">Approved</a>
                        <a type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#btn-disapproved-iframe" data-url="<?php echo $routedisapprove?>" data-height="150px" data-width="100%" data-placement="top">Disapproved</a>-->

                        <!--<a type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#btn-resolution-iframe" data-url="<?php echo $routeresolve?>" data-height="350px" data-width="100%" data-placement="top">Resolve</a>-->

                        </td>
                        </tr>
                    </tbody>
            </table>


            <?php
        }
    }

    public function countrycode(Request $request) {
        $country_id = $request->get('country_id');
        $countryCode = Helpers::getCountryCodeById((int) $country_id);
        return $countryCode;
    }


    /**
     * get role list
     * @param Request $request
     */
    public function getRoleLists(DataProviderInterface $dataProvider) {
       $anchRoleList = $this->userRepo->getRoleList();
       $role = $dataProvider->getRoleList($this->request, $anchRoleList);
       return $role;
    }

    /**
     * get role list
     * @param Request $request
     */
    public function getUserRoleLists(DataProviderInterface $dataProvider) {
       $List = $this->userRepo->getAllData();
       $role = $dataProvider->getUserRoleList($this->request, $List);
       return $role;

    }


 public function completePdfReport(Request $request) {

        $user_kyc_id = $request->get('user_kyc_id');

       
    }
    
    //
    public function updateUtilityBill(Request $request) {
        //**********
        $requestVar = $request->all();

        $rules      =   [];
        $messages   =   [];

        $rules['document_number1'] = 'required';
        $rules['issuance_date1'] = 'required';
        $rules['otp'] = 'required';
        $rules['files.*'] = 'required';


        $messages['document_number1.required']=trans('common.error_messages.req_this_field');

        $messages['issuance_date1.required']=trans('common.error_messages.req_this_field');

        $messages['files.*.required']=trans('common.error_messages.req_this_field');
        $messages['otp.required']=trans('common.error_messages.req_this_field');

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $resData['status'] = 'error';
            $error_msg = [];
            //dd($validator->customMessages);
            $i = 0;
            $customMessages = $validator->getMessageBag()->toArray();

            foreach ($customMessages as $key => $msg) {
                $idx = explode('.', $key);

                $error_msg[$i] = $msg;
                $error_index[$i] =  'error'.$idx[0];
                $i++;
            }
            $isError=0;
            $error_ext='';
            $error_size='';
            if($request->file()){
                $maxSize=config('common.USER_PROFILE_MAX_SIZE'); // 20mb
                $extenstion=config('common.CHECK_EXTENTION');//PDF,JPEG,PNG,DOC,DOCX
                foreach ($request->file() as $docs) {
                    foreach ($docs as $key => $row) {
                        $ext      = $row->getClientOriginalExtension();
                        $fileSize = $row->getSize();;
                        if(!in_array($ext,$extenstion)){
                            $isError = 1;
                            $error_ext   =   trans('common.error_messages.only_ext_allowed');
                        }

                        if($fileSize>$maxSize){
                            $isError = 1;
                            $error_size  =   trans('common.error_messages.max_20mb_file');
                        }
                    }
                }
                if($isError==1){
                    if($error_ext!=''){
                        $error[]=$error_ext;
                    }

                    if($error_size!=''){
                        $error[]=$error_size;
                    }

                    $errms=implode('<br>',$error);
                    $error_msg[$i]=$errms;
                    $error_index[$i] =  'errorfiles';
                }

            }else{
                $error_msg[$i]=trans('common.error_messages.req_this_field');
                $error_index[$i] =  'errorfiles';
            }


            $resData['status'] = 'error';
            $resData['message'] = $error_msg;
            $resData['messagekey'] = $error_index;

            return json_encode($resData);
        }else{

            $isError=0;
            $error_ext='';
            $error_size='';
            $i=0;
            if($request->file()){
                $maxSize=config('common.USER_PROFILE_MAX_SIZE'); // 20mb
                $extenstion=config('common.CHECK_EXTENTION');//PDF,JPEG,PNG,DOC,DOCX
                foreach ($request->file() as $docs) {
                    foreach ($docs as $key => $row) {
                        $ext      = $row->getClientOriginalExtension();
                        $fileSize = $row->getSize();;
                        if(!in_array($ext,$extenstion)){
                            $isError = 1;
                            $error_ext   =   trans('common.error_messages.only_ext_allowed');
                        }

                        if($fileSize>$maxSize){
                            $isError = 1;
                            $error_size  =   trans('common.error_messages.max_20mb_file');
                        }
                    }
                }
                if($isError==1){
                    if($error_ext!=''){
                        $error[]=$error_ext;
                    }

                    if($error_size!=''){
                        $error[]=$error_size;
                    }

                    $errms=implode('<br>',$error);
                    $error_msg[$i]=$errms;
                    $error_index[$i] =  'errorfiles';
                    $i++;
                }

            }else{
                $isError = 1;
                $error_msg[$i]=trans('common.error_messages.req_this_field');
                $error_index[$i] =  'errorfiles';
                $i++;
            }


            $otp             = $requestVar['otp'];
            $userCheckArrOtp = $this->userRepo->getUserByOPT($otp);
            if($userCheckArrOtp==false){
                $isError = 1;
                $error_msg[$i]=trans('error_messages.invalid_otp');
                $error_index[$i] =  'errorotp';
                $i++;
            }


            if($isError==1){
                $resData['status'] = 'error';
                $resData['message'] = $error_msg;
                $resData['messagekey'] = $error_index;
                 return json_encode($resData);
            }


        $id             =   $requestVar['id'];
        $doctype_doc_id =   $requestVar['doc_id'];
        $doc_no         =   $requestVar['doc_no'];
        $user_kyc_id    =   $requestVar['user_kyc_id'];

        $userId =    Auth()->user()->user_id;

        // update doc status
        if($doc_no==3){
            $inputData['doc_status']    =   2;
            $doc_id                     =   9;
        }else if($doc_no==2){
            $inputData['doc_status']    =   2;
            $doc_id                     =   3;
        }

        if($doc_id == 9) {
           $Doc_for_path = 'corporate';
        } else {
           $Doc_for_path = 'indivisual';
        }

        
        $update=$this->userRepo->updatePassportDoc($inputData,$doc_id,$user_kyc_id);

         // save new uploaded doc
        $i=0;
        if ($request->file()) {
        foreach ($request->file() as $docs) {
            foreach ($docs as $key => $row) {

                $docname = $row->getClientOriginalName();
                $certificate = basename($row->getClientOriginalName());
                $certificate = pathinfo($certificate, PATHINFO_FILENAME);
                $ext = $row->getClientOriginalExtension();
                $fileSize = $row->getClientSize();

                $userBaseDir = 'appDocs/Document/'.$Doc_for_path.'/pdf/' . $user_kyc_id;
                $userFileName = $docname;
                $pathName = $row->getPathName();
                $this->storage->engine()->put($userBaseDir . DIRECTORY_SEPARATOR . $userFileName, File::get($pathName));
                // Delete the temporary file
                File::delete($pathName);

                //store data in array
                $array = [];
                $array = [];
                $array['user_req_doc_id'] = $update->user_req_doc_id;
                $array['doc_type'] = 1;
                $array['user_kyc_id'] = $user_kyc_id;
                $array['user_id'] = $userId;
                $array['doc_id'] = $doc_id;

                $array['doc_name'] = $certificate;
                $array['doc_ext'] = $ext;
                $array['doc_status'] = 1;
                $array['enc_id'] = md5(rand(1, 9999));
                $array['created_by'] = Auth()->user()->user_id;
                $array['updated_by'] = Auth()->user()->user_id;
                $array['created_at'] = date('Y-m-d H:i:s');
                $array['updated_at'] = date('Y-m-d H:i:s');
                $data[$i]   =   $array;
                $i++;



            }
        }
            $this->userRepo->saveIndvDocBatch($data);
        }

        // update doc status
        $statusData=[];
        $statusData['is_active']        =   1;
        $statusData['status']           =   2;
        $statusData['user_req_doc_id']  =   $update->user_req_doc_id;
        $statusData['document_number']  =   $requestVar['document_number1'];
        $statusData['issuance_date']    =   isset($requestVar['issuance_date1']) ? Helpers::getDateByFormat($requestVar['issuance_date1'], 'd/m/Y', 'Y-m-d') : null;

        if($statusData['issuance_date']!=null){
            $today          =   $statusData['issuance_date'];
            $expire_date    =   date('Y-m-d',strtotime('+1 year',strtotime($today)));
            $statusData['expire_date']        =   $expire_date;
        }
        
        $statusData['updated_by']       =   $userId;
        $statusData['updated_at']       =   date('Y-m-d H:i:s');

        $updatepass=$this->userRepo->storeUserKycDocumentTypeData($statusData,$id);
        
        if(Auth()->user()->user_type==2){
            $userCheckArrData = $this->userRepo->getcorpDetail(Auth()->user()->user_id);
            $verifyUserArr['corp_name'] = $userCheckArrData->corp_name;
        }else{
            $verifyUserArr['corp_name'] = '';
        }
                
        $verifyUserArr['name']                  =   Auth()->user()->f_name . ' ' . Auth()->user()->l_name;
        $verifyUserArr['email']                 =   Auth()->user()->email;
        $verifyUserArr['user_type']                 =   Auth()->user()->user_type;
        $verifyUserArr['document_type']         =   $doc_no;
        
        Event::dispatch("user.email.documentUpdateNotification", serialize($verifyUserArr));
        
        
        // Document Update NOtificatrion to admin
        $verifyUserArr['admin_email']  = 'admin@admin.com';//sent Compliance Admin
        Event::dispatch("user.email.documentUpdateNotificationAdmin", serialize($verifyUserArr));

        Session::flash('message', trans('success_messages.update_documents_successfully'));
        $resData['message'] = "success";
        $resData['status'] = "success";


        return json_encode($resData);
        }


    }

    public function updatePassport(Request $request) {
        //**********
        $requestVar = $request->all();

        $rules      =   [];
        $messages   =   [];

        $rules['document_number'] = 'required';
        $rules['issuance_date'] = 'required';
        
        $rules['expiry_date'] = 'required';
        $rules['otp'] = 'required';
        $rules['files.*'] = 'required';


        $messages['document_number.required']=trans('common.error_messages.req_this_field');

        $messages['issuance_date.required']=trans('common.error_messages.req_this_field');
        $messages['expiry_date.required']=trans('common.error_messages.req_this_field');
        $messages['files.*.required']=trans('common.error_messages.req_this_field');
        $messages['otp.required']=trans('common.error_messages.req_this_field');

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $resData['status'] = 'error';
            $error_msg = [];
            //dd($validator->customMessages);
            $i = 0;
            $customMessages = $validator->getMessageBag()->toArray();

            foreach ($customMessages as $key => $msg) {
                $idx = explode('.', $key);

                $error_msg[$i] = $msg;
                $error_index[$i] =  'error'.$idx[0];
                $i++;
            }
            $isError=0;
            $error_ext='';
            $error_size='';
            if($request->file()){
                $maxSize=config('common.USER_PROFILE_MAX_SIZE'); // 20mb
                $extenstion=config('common.CHECK_EXTENTION');//PDF,JPEG,PNG,DOC,DOCX
                foreach ($request->file() as $docs) {
                    foreach ($docs as $key => $row) {
                        $ext      = $row->getClientOriginalExtension();
                        $fileSize = $row->getSize();;
                        if(!in_array($ext,$extenstion)){
                            $isError = 1;
                            $error_ext   =   trans('common.error_messages.only_ext_allowed');
                        }

                        if($fileSize>$maxSize){
                            $isError = 1;
                            $error_size  =   trans('common.error_messages.max_20mb_file');
                        }
                    }
                }
                if($isError==1){
                    if($error_ext!=''){
                        $error[]=$error_ext;
                    }

                    if($error_size!=''){
                        $error[]=$error_size;
                    }

                    $errms=implode('<br>',$error);
                    $error_msg[$i]=$errms;
                    $error_index[$i] =  'errorfiles';
                }

            }else{
                $error_msg[$i]=trans('common.error_messages.req_this_field');
                $error_index[$i] =  'errorfiles';
            }


            $resData['status'] = 'error';
            $resData['message'] = $error_msg;
            $resData['messagekey'] = $error_index;

            return json_encode($resData);
        }else{

            $isError=0;
            $error_ext='';
            $error_size='';
            $i=0;
            if($request->file()){
                $maxSize=config('common.USER_PROFILE_MAX_SIZE'); // 20mb
                $extenstion=config('common.CHECK_EXTENTION');//PDF,JPEG,PNG,DOC,DOCX
                foreach ($request->file() as $docs) {
                    foreach ($docs as $key => $row) {
                        $ext      = $row->getClientOriginalExtension();
                        $fileSize = $row->getSize();;
                        if(!in_array($ext,$extenstion)){
                            $isError = 1;
                            $error_ext   =   trans('common.error_messages.only_ext_allowed');
                        }

                        if($fileSize>$maxSize){
                            $isError = 1;
                            $error_size  =   trans('common.error_messages.max_20mb_file');
                        }
                    }
                }
                if($isError==1){
                    if($error_ext!=''){
                        $error[]=$error_ext;
                    }

                    if($error_size!=''){
                        $error[]=$error_size;
                    }

                    $errms=implode('<br>',$error);
                    $error_msg[$i]=$errms;
                    $error_index[$i] =  'errorfiles';
                    $i++;
                }

            }else{
                $isError = 1;
                $error_msg[$i]=trans('common.error_messages.req_this_field');
                $error_index[$i] =  'errorfiles';
                $i++;
            }


            $otp             = $requestVar['otp'];
            $userCheckArrOtp = $this->userRepo->getUserByOPT($otp);
            if($userCheckArrOtp==false){
                $isError = 1;
                $error_msg[$i]=trans('error_messages.invalid_otp');
                $error_index[$i] =  'errorotp';
                $i++;
            }


            if($isError==1){
                $resData['status'] = 'error';
                $resData['message'] = $error_msg;
                $resData['messagekey'] = $error_index;
                 return json_encode($resData);
            }


            $id             = $requestVar['id'];
            $doctype_doc_id =   $requestVar['doc_id'];
            $user_kyc_id    =   $requestVar['user_kyc_id'];

            $userId =    Auth()->user()->user_id;

            
        // update doc status
        $inputData['doc_status']    =   2;
        $doc_id                     =   1;
        $update=$this->userRepo->updatePassportDoc($inputData,$doc_id,$user_kyc_id);

         // save new uploaded doc
        $i=0;
        if ($request->file()) {
        foreach ($request->file() as $docs) {
            foreach ($docs as $key => $row) {

                $docname = $row->getClientOriginalName();
                $certificate = basename($row->getClientOriginalName());
                $certificate = pathinfo($certificate, PATHINFO_FILENAME);
                $ext = $row->getClientOriginalExtension();
                $fileSize = $row->getClientSize();
                $userBaseDir = 'appDocs/Document/indivisual/pdf/' . $user_kyc_id;
                $userFileName = $docname;
                $pathName = $row->getPathName();
                $this->storage->engine()->put($userBaseDir . DIRECTORY_SEPARATOR . $userFileName, File::get($pathName));
                // Delete the temporary file
                File::delete($pathName);

                //store data in array
                $array = [];
                $array = [];
                $array['user_req_doc_id'] = $update->user_req_doc_id;
                $array['doc_type'] = 1;
                $array['user_kyc_id'] = $user_kyc_id;
                $array['user_id'] = $userId;
                $array['doc_id'] = $doc_id;

                $array['doc_name'] = $certificate;
                $array['doc_ext'] = $ext;
                $array['doc_status'] = 1;
                $array['enc_id'] = md5(rand(1, 9999));
                $array['created_by'] = Auth()->user()->user_id;
                $array['updated_by'] = Auth()->user()->user_id;
                $data[$i]   =   $array;
                $i++;



            }
        }
            $this->userRepo->saveIndvDocBatch($data);
        }

        // update doc status
        $statusData=[];
        $statusData['is_active']        =   1;
        $statusData['status']           =   2;
        $statusData['user_req_doc_id']  =   $update->user_req_doc_id;
        $statusData['document_number']  =   $requestVar['document_number'];
        $statusData['issuance_date']    =   isset($requestVar['issuance_date']) ? Helpers::getDateByFormat($requestVar['issuance_date'], 'd/m/Y', 'Y-m-d') : null;
        $statusData['expire_date']      =   isset($requestVar['expiry_date']) ? Helpers::getDateByFormat($requestVar['expiry_date'], 'd/m/Y', 'Y-m-d') : null;
        $statusData['updated_by']       =   $userId;
        $statusData['updated_at']       =   date('Y-m-d H:i:s');

        $updatepass=$this->userRepo->storeUserKycDocumentTypeData($statusData,$id);
        
        // send email notification
        //Auth()->user()->user_id
        //user.email.documentUpdateNotification
        
        if(Auth()->user()->user_type==2){
            $userCheckArrData = $this->userRepo->getcorpDetail(Auth()->user()->user_id);
            $verifyUserArr['corp_name'] = $userCheckArrData->corp_name;
        }else{
            $verifyUserArr['corp_name'] = '';
        }
                
        $verifyUserArr['name']                  =   Auth()->user()->f_name . ' ' . Auth()->user()->l_name;
        $verifyUserArr['email']                 =   Auth()->user()->email;
        $verifyUserArr['document_type']         =   1;  

        Event::dispatch("user.email.documentUpdateNotification", serialize($verifyUserArr));
        
        
        // Document Update NOtificatrion to admin
        
        Event::dispatch("user.email.documentUpdateNotificationAdmin", serialize($verifyUserArr));
     
        Session::flash('message', trans('success_messages.update_documents_successfully'));
        $resData['message'] = "success";
        $resData['status'] = "success";


        return json_encode($resData);
        }


    }

    public function sendOtp(){
        $userId =   Auth()->user()->user_id;
        $user_type =   Auth()->user()->user_type;
        $date = new DateTime;
        $currentDate = $date->format('Y-m-d H:i:s');
        $date->modify('+30 minutes');
        $formatted_date = $date->format('Y-m-d H:i:s');
        $userMailArr = [];
        $otpArr = [];
        $prev_otp = $this->userRepo->getOtpsbyActive($userId)->toArray();
        $Otpstring = Helpers::randomOTP();
      
        if (isset($prev_otp) && count($prev_otp) == 1) {
            $arrUpdateOtp = [];
            $arrUpdateOtp['is_otp_expired'] = 1;
            $arrUpdateOtp['otp_exp_time'] = $currentDate;
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
       
        if($user_type == 2){
            $userCheckArrData = $this->userRepo->getcorpDetail($userId);
            $userMailArr['name'] = $userCheckArrData->f_name . ' ' . $userCheckArrData->l_name;
            $userMailArr['email'] = $userCheckArrData->email;
            $userMailArr['userType'] = $userCheckArrData->user_type;
            $userMailArr['corp_name'] = $userCheckArrData->corp_name;
            $userMailArr['otp'] = $Otpstring;

        }else{
            $userCheckArrData = $this->userRepo->getIndvDetail($userId);
            $userMailArr['name'] = $userCheckArrData->f_name . ' ' . $userCheckArrData->l_name;
            $userMailArr['email'] = $userCheckArrData->email;
            $userMailArr['userType'] = $userCheckArrData->user_type;
            $userMailArr['otp'] = $Otpstring;
        }
                    //country_code,phone_no
        // SEND otp register mobile no
        $recipients   =   '+'.$userCheckArrData->country_code.''.$userCheckArrData->phone_no;
        $message    =   'You are receiving this One Time Password (OTP) '.$Otpstring.' to verify your mobile number with us.';

         Helpers::sendSMS($recipients,$message);

        //$userMailArr['password'] = Session::pull('password');
                    // Send OTP mail to User
        //Event::dispatch("user.sendotp", serialize($userMailArr));
        $resData['message'] = trans('success_messages.otp_sent_messages');
        $resData['status'] = "success";


        return json_encode($resData);


    }

    public function resendotpDocUser() {
        $userId     =   Auth()->user()->user_id;
        $user_type  =   Auth()->user()->user_type;
        $email      =   Auth()->user()->email;
        $userMailArr = [];
        $i = 0;
        $userCheckArr = $this->userRepo->getuserByEmail($email);

        $date = new DateTime;
        $currentDate = $date->format('Y-m-d H:i:s');
        $date->modify('+30 minutes');
        $formatted_date = $date->format('Y-m-d H:i:s');
        $otpArr = [];
        $Otpstring = Helpers::randomOTP();
        $countOtp = $this->userRepo->getOtps($userId)->toArray();
        //dd($countOtp);
        if (isset($countOtp)) {
            $firstData = $countOtp[0]['otp_exp_time'];
            $updatedTime = new DateTime($firstData);
            $currentDate = new DateTime();
            $interval = $updatedTime->diff($currentDate);
            $hours = $interval->format('%h');
            $minutes = $interval->format('%i');
            $expireTime = ($hours * 60 + $minutes);

            if ($expireTime >= 1) {
                $this->userRepo->updateOtpByExpiry(['is_otp_expired' => 1], $userId);
                $this->userRepo->updateOtpByExpiry(['is_otp_resent' => 3], $userId);

                $otpArr['otp_no'] = $Otpstring;
                $otpArr['activity_id'] = 1;
                $otpArr['user_id'] = $userId;
                $otpArr['is_otp_expired'] = 0;
                $otpArr['is_otp_resent'] = 0;
                $otpArr['otp_exp_time'] = $currentDate;
                $otpArr['is_verified'] = 1;
                $this->userRepo->saveOtp($otpArr);

                if($userCheckArr->user_type == 2){
                    
                    $userCheckArrData = $this->userRepo->getcorpDetail($userId);
                    $userMailArr['name'] = $userCheckArr->f_name . ' ' . $userCheckArr->l_name;
                    $userMailArr['email'] = $userCheckArr->email;
                    $userMailArr['userType'] = $userCheckArr->user_type;
                    $userMailArr['corp_name'] = $userCheckArrData->corp_name;
                    $userMailArr['otp'] = $Otpstring;
                    
                }else{
                    
                    $userMailArr['name'] = $userCheckArr->f_name . ' ' . $userCheckArr->l_name;
                    $userMailArr['email'] = $userCheckArr->email;
                    $userMailArr['userType'] = $userCheckArr->user_type;
                    //$userMailArr['password']   = $string;
                    $userMailArr['otp'] = $Otpstring;
                }

                // SEND otp register mobile no
                    $recipients   =   '+'.$userCheckArr->country_code.''.$userCheckArr->phone_no;
                    $message    =   'You are receiving this One Time Password (OTP) '.$Otpstring.' to verify your mobile number with us.';

                    Helpers::sendSMS($recipients,$message);

                //Event::dispatch("user.sendotp", serialize($userMailArr));
                $resData['message'] = trans('success_messages.otp_sent_messages');
                $resData['status'] = "success";

                return json_encode($resData);
            } else {
                $countOtp = $this->userRepo->getOtps($userId)->toArray();
                if (isset($countOtp) && count($countOtp) >= 5) {

                    //return redirect(route('otp', ['token' => Crypt::encrypt($email)]))->withErrors(trans('success_messages.otp_attempts_finish'));
                    $resData['message'] = trans('success_messages.otp_attempts_finish');
                    $resData['status'] = "error";

                    return json_encode($resData);

                } else {
                    $prev_otp = $this->userRepo->getOtpsbyActive($userId)->toArray();
                    if(isset($prev_otp) && count($prev_otp) == 1) {
                        $arrUpdateOtp = [];
                        $arrUpdateOtp['is_otp_expired'] = 1;
                        $arrUpdateOtp['otp_exp_time'] = $currentDate;
                        //dd($prev_otp[0]['otp_trans_id']);
                        $this->userRepo->updateOtp($arrUpdateOtp, (int) $prev_otp[0]['otp_trans_id']);
                        $otpArr['otp_no'] = $Otpstring;
                        $otpArr['activity_id'] = 1;
                        $otpArr['user_id'] = $userId;
                        $otpArr['is_otp_expired'] = 0;
                        $otpArr['is_otp_resent'] = 0;
                        $otpArr['otp_exp_time'] = $currentDate;
                        $otpArr['is_verified'] = 1;
                        $this->userRepo->saveOtp($otpArr);
                    }
                    $userMailArr['name'] = $userCheckArr->f_name . ' ' . $userCheckArr->l_name;
                    $userMailArr['email'] = $userCheckArr->email;
                    $userMailArr['otp'] = $Otpstring;
                   // Event::dispatch("user.sendotp", serialize($userMailArr));
                    


                    // SEND otp register mobile no
                    $recipients   =   '+'.$userCheckArr->country_code.''.$userCheckArr->phone_no;
                    $message    =   'You are receiving this One Time Password (OTP) '.$Otpstring.' to verify your mobile number with us.  Please do not share this OTP with anyone. <br> Thank You,<br> Dexter Capital Financial Consultancy';

                    Helpers::sendSMS($recipients,$message);
                    $resData['message'] = trans('success_messages.otp_sent_messages');
                    $resData['status'] = "success";

                    return json_encode($resData);
                }
            }
        }
    }


    public function adminUserEmailCheck(Request $request) {

         $email = $request->userdata;
        $result = $this->userRepo->getUserByEmail($email);

        if ($result) {
            echo "false";
        } else {
            echo "true";
        }
    }


    public function adminUsernameCheck(Request $request) {
       
        $username = $request->userdata;
        $result = $this->userRepo->checkUserNameExit($username);

        if ($result) {
            echo "false";
        } else {
            echo "true";
        }
    }
    //User Role check form Access Management
    public function adminUserRoleCheck(Request $request) {
       
        $userrole = $request->userdata;
        $result = $this->userRepo->checkUserRoleExit($userrole);

        if ($result) {
            echo "false";
        } else {
            echo "true";
        }
    }



    public function callindividualApi() {

        $endPoint = "https://" . $gatwayhost . $gatwayurl . "reference/profile/" . $profileID;
        //$endPoint = 'https://rms-world-check-one-api-pilot.thomsonreuters.com/v1/reference/profile/'.$profileID;

        $curl = curl_init();
        $reqData = array(
            CURLOPT_URL => $endPoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: " . $authSignature,
                "Date: " . $date,
                "cache-control: no-cache"
            ),
        );

        curl_setopt_array($curl, $reqData);

        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);

        curl_close($curl);

    }


    public function getUsersDetailfromApi(Request $request) {

        $gatwayhost     = config('common.gatwayhost');
        $groupId        = Helpers::getgroupId();
        $gatwayurl      = config('common.gatwayurl');
        $gatwayhost     = config('common.gatwayhost');
        $contentType    = config('common.contentType');
       
        $authSignature  = $_POST['authorisation'];
        $date           = $_POST['currentDate'];
        $Signature      = $_POST['Signature'];
        $profileID      = $_POST['profileID'];
        $parentId       = $_POST['parentId'];
     
       

        
        $endPoint = "https://" . $gatwayhost . $gatwayurl . "reference/profile/" . $profileID;
        //$endPoint = 'https://rms-world-check-one-api-pilot.thomsonreuters.com/v1/reference/profile/'.$profileID;

        $curl = curl_init();
        $reqData = array(
            CURLOPT_URL => $endPoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: " . $authSignature,
                "Date: " . $date,
                "cache-control: no-cache"
            ),
        );

        curl_setopt_array($curl, $reqData);

        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $datafill['responce_type'] = 'fail';
            $datafill['profile_id'] = $profileID;
            $datafill['wc_id'] = $parentId;
            $datafill['response'] = $err;
            $kycDetail = $this->userRepo->saveLogs($datafill);
        } else {
            $datafill['responce_type'] = 'pass';
            $datafill['profile_id'] = $profileID;
            $datafill['wc_id'] = $parentId;
            $datafill['response'] = $response;
            
            $kycDetail = $this->userRepo->saveLogs($datafill);
            //dd($resultArray);
        }
    
            
    }


}
