<?php

namespace App\Helpers;

use Mail;
use Exception;
use Carbon\Carbon;
use App\Helpers\PaypalHelper;
use App\Inv\Repositories\Models\Patent;
use App\Inv\Repositories\Models\Master\Permission;
use App\Inv\Repositories\Models\Master\PermissionRole;
use App\Inv\Repositories\Models\Master\RoleUser;
use App\Inv\Repositories\Models\Master\Role;
use App\Inv\Repositories\Models\User;
use Twilio\Rest\Client;
use Twilio\Jwt\ClientToken;
use App\Inv\Repositories\Contracts\Traits\ApiAccessTrait;
use DB;

class Helper extends PaypalHelper {

    /**
     * Send exception emails
     *
     * @param Exception $exception
     * @param string    $exMessage
     * @param boolean   $handler
     */
    public static function shootDebugEmail($exception, $handler = false) {
        $request = request();
        $data['page_url'] = $request->url();
        $data['loggedin_userid'] = (auth()->guest() ? 0 : auth()->user()->id);
        $data['ip_address'] = $request->getClientIp();
        $data['method'] = $request->method();
        $data['message'] = $exception->getMessage();
        $data['class'] = get_class($exception);

       /* if (app()->envrionment('live') === false) {
            $data['request'] = $request->except('password');
        } */

        $data['file'] = $exception->getFile();
        $data['line'] = $exception->getLine();
        $data['trace'] = $exception->getTraceAsString();

        $subject = 'Dexter (' . config('app.env') . ') ' . ($handler ?
                        '' : 'EXCEPTION') . ' Error at ' . date('Y-m-d D H:i:s T');

        //config(['mail.driver' => 'mail']);
        
        /*Mail::raw(
                print_r($data, true), function ($message) use ($subject) {
            $message->to(config('errorgroup.error_notification_group'))
                    ->from(
                            config('errorgroup.error_notification_email'), config('errorgroup.error_notification_from')
                    )
                    ->subject($subject);
        }); */


        Mail::raw(
                print_r($data, true), function ($message) use ($subject) {
            $message->to(config('common.ERROR_NOTIFICATION_GROUP'))
                    ->from(
                            config('common.ERROR_FROM_EMAIL'), config('common.ERROR_FROM_NAME')
                    )
                    ->subject($subject);
        });
    }

    /**
     * Get exception message w.r.t. application environment
     *
     * @param  Exception $exception
     * @return string
     */
    public static function getExceptionMessage($exception) {
        $exMessage = trans('error_messages.generic.failure');

        $actualException = 'Error: ' . $exception->getMessage() .
                ' . File: ' . $exception->getFile() . ' . Line#: ' . $exception->getLine();

        if (config('app.debug') === false) {
            self::shootDebugEmail($exception);
            return $exMessage;
        } else {
            return $actualException;
        }
    }

    /**
     * Get How you heard about us drop down
     * 
     * @return object|array
     */
    public static function getHeardFromDropDown() {
        return \App\Inv\Repositories\Models\Master\HeardFrom::getDropDown();
    }

    /**
     * Get Country drop down
     *
     * @return object|array
     */
    public static function getCountryDropDown() {
        return \App\Inv\Repositories\Models\Master\Country::getDropDown();
    }

    public static function getCountryCode() {
        return \App\Inv\Repositories\Models\Master\Country::getCountryCodeDropDown();
    }

    /**
     * Get How you heard about us drop down
     *
     * @return array
     */
    public static function getYearDropdown() {
        $year = [];
        for ($i = date("Y"); $i >= config('inv_common.START_YEAR'); $i--) {
            $year[$i] = $i;
        }
        return $year;
    }

    /**
     * Get all months
     *
     * @return array
     */
    public static function getMonthDropdown() {
        $months = [
            'January' => 'January',
            'February' => 'February',
            'March' => 'March',
            'April' => 'April',
            'May' => 'May',
            'June' => 'June',
            'July' => 'July',
            'August' => 'August',
            'September' => 'September',
            'October' => 'October',
            'November' => 'November',
            'December' => 'December'
        ];

        return $months;
    }

    /**
     * getUserType
     * 
     * @return string
     */
    public static function getUserType() {
        $usertype = [
            '1' => 'To validate and earn',
            '2' => 'To innovate and earn',
            '3' => 'To explore and develop'
        ];

        return $usertype;
    }

    /**
     * Creating random password for user
     * 
     * @return String
     */
    public static function randomPassword() {
        $len = config('inv_common.PWD_LENGTH');
        $sets = array();
        $sets[] = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
        $sets[] = 'abcdefghjkmnpqrstuvwxyz';
        $sets[] = '0123456789';
        //$sets[] = '!@#$&*?';

        $password = '';
        foreach ($sets as $set) {
            $password .= $set[array_rand(str_split($set))];
        }
        while (strlen($password) < $len) {

            $randomSet = $sets[array_rand($sets)];
            $password .= $randomSet[array_rand(str_split($randomSet))];
        }

        return str_shuffle($password);
    }

    /**
     * Creating random password for user
     *
     * @return String
     */
    public static function randomOTP() {
        $len = config('inv_common.OTP_LENGTH');
        $sets = array();
        $sets[] = '123456789';
        //$sets[] = '!@#$&*?';

        $password = '';
        foreach ($sets as $set) {
            $password .= $set[array_rand(str_split($set))];
        }
        while (strlen($password) < $len) {

            $randomSet = $sets[array_rand($sets)];
            $password .= $randomSet[array_rand(str_split($randomSet))];
        }

        return str_shuffle($password);
    }

    /**
     * Get Users Detail by User ID
     *
     * @return string
     */
    public static function getUserDetail($user_id) {
        return \App\Inv\Repositories\Models\User::getUserDetail((int) $user_id);
    }

    /**
     * Get Document drop down
     *
     * @return type
     */
    public static function getDocumentsDropDown() {
        return \App\Inv\Repositories\Models\Master\Documents::getDropDown();
    }

    public static function getUserDocuments() {
        return \App\Inv\Repositories\Models\Master\Documents::getUserDocumentsType();
    }

    /**
     * Get country name by id
     *
     * @return type
     */
    public static function getCountryById($countryId) {
        return \App\Inv\Repositories\Models\Master\Country::getCountryById($countryId);
    }

    /**
     * Get Social media drop down
     *
     * @return type
     */
    public static function getSocialmediaDropDown() {
        return \App\Inv\Repositories\Models\Master\Socialmedia::getDropDown();
    }

    /**
     * Get Date Format
     *
     * @param date  $date
     *
     * @param string $format
     *
     * @return string
     */
    public static function getDateByFormat($date, $fromFormat = 'Y-m-d H:i:s', $format = 'd F Y') {
        try {
            return Carbon::createFromFormat($fromFormat, $date)->format($format);
        } catch (InvalidArgumentException $x) {
            echo $x->getMessage();
        }
    }

    /**
     * Get valid novlty list
     *
     * @return object|array
     */
    public static function getValidNovltyCheckList($column_name, $type) {
        return \App\Inv\Repositories\Models\Master\ValidChecklist::getValidNovltyCheckList($column_name, $type);
    }

    /**
     * Get type wise list
     *
     * @return object|array
     */
    public static function getTypeWiseList($type) {
        return \App\Inv\Repositories\Models\Master\ValidChecklist::getTypeWiseList($type);
    }

    /**
     * Create log for api
     *
     * @return string
     */
    public static function saveApiLog($arrData) {
        return \App\Inv\Repositories\Models\ApiLog::saveApiLog($arrData);
    }

    public static function ifscountPositive($valid_scout, $invalid_scout) {
        $result = ($valid_scout - $invalid_scout);

        if ($invalid_scout == 0 && $valid_scout > 0) {
            $returnresult = true;
        } else {
            $returnresult = false;
        }
        return $returnresult;
    }

    public static function getKycDetails($userId) {
        return \App\Inv\Repositories\Models\Userkyc::getKycDetails($userId);
    }

    public static function getKycDetailsbyType($userId,$is_by_company) {
        return \App\Inv\Repositories\Models\Userkyc::getKycDetailsbyType($userId,$is_by_company);
    }

    public static function getKycDetailsByKycID($userKycId) {
        return \App\Inv\Repositories\Models\Userkyc::getKycDetailsByKycID($userKycId);
    }

    public static function getCorpDocument($userId, $docid) {
        return \App\Inv\Repositories\Models\Document::getTotalDoc($userId, $docid);
    }

    public static function getDocumentList($kycid, $user_req_doc_id) {

        return \App\Inv\Repositories\Models\Document::getDocumentList($kycid, $user_req_doc_id);
    }
    
    public static function getDocumentListExpired($kycid, $user_req_doc_id, $fordocid) {

        return \App\Inv\Repositories\Models\Document::getDocumentListExpired($kycid, $user_req_doc_id, $fordocid);
    }


    public static function getCorpStatus() {

        return \App\Inv\Repositories\Models\CorpStatus::all();
    }

    /**
     * Get Annual Income drop down
     * 
     * @return object|array
     */
    public static function getAnnualIncomeDropDown() {
        return ['1' => '<=20,000', '2' => '20,001 to 50,000', '3' => '50,001 to 100,000', '4' => '100,001 to 200,000', '5' => '200,001 to 500,000', '6' => '500,001 to 1,000,000', '7' => '1,000,001 to 2,500,000', '8' => '2,500,001 to 5,000,000', '9' => '5,000,001 to 10,000,000', '10' => '10,000,001 to 20,000,000', '11' => '> 20,000,000'];
    }

    /**
     * Get Estimated Wealth drop down
     * 
     * @return object|array
     */
    public static function getEstimatedWealthDropDown() {
        return ['1' => '<=25,000', '2' => '25,001 to 100,000', '3' => '100,001 to 250,000', '4' => '250,001 to 500,000', '5' => '500,001 to 1,000,000', '6' => '1000,001 to 2,500,000', '7' => '2,500,001 to 5,000,000', '8' => '5,000,001 to 10,000,000', '9' => '10,000,001 to 25,000,000', '10' => '25,000,001 to 50,000,000', '11' => '>  50,000,000'];
    }

    /**
     * Get Wealth Source drop down
     * 
     * @return object|array
     */
    public static function getWealthSourceDropDown() {
        return ["Commercial business activities", "Inheritance", "Accumulated Earnings", "Salary", "Other"];
    }

    /**
     * Get Fund Source drop down
     * 
     * @return object|array
     */
    public static function getFundSourceDropDown() {

        /* return ['1'=>'Salary','2'=>'Rents','3'=>'Loan','4'=>'Dividends','5'=>'Bond Return','6'=>'Interest Earned on Deposit','7'=>'Crypto Assets Return','8'=>'FX Returns','9'=>'Other Returns']; */
        $arr = array("Salary", "Rents", "Loan", "Dividends", "Bond Return", "Interest Earned on Deposit", "Crypto Assets Return", "FX Returns", "Other Returns");
        return $arr;
    }

    public static function UsfetchYesDropDown() {
        return array("US Citizen", "US Green Card Holder", "Have been more than 180 days in US");
    }

    public static function getUserName($user_id) {

        $name = \App\Inv\Repositories\Models\User::find($user_id);
        return $name->f_name . " " . $name->l_name;
    }

    public static function getKycprofileData($userKycId, $referenceId, $requestFor) {
        return \App\Inv\Repositories\Models\Wcapi::getKycprofileData($userKycId, $referenceId, $requestFor);
    }

    public static function getKycprofileDatagraterPID($userKycId, $referenceId, $requestFor, $primaryId) {
        return \App\Inv\Repositories\Models\Wcapi::getKycprofileDatagraterPID($userKycId, $referenceId, $requestFor, $primaryId);
    }
    
    public  static function  getAllKycprofileDatagraterPID($userKycId, $requestFor, $primaryId)
    {
      return \App\Inv\Repositories\Models\Wcapi::getAllKycprofileDatagraterPID($userKycId, $requestFor, $primaryId);
    }

    public  static function  getAllKycprofileDataHistory($userKycId, $requestFor)
    {
      return \App\Inv\Repositories\Models\Wcapi::getAllKycprofileDataHistory($userKycId, $requestFor);
    }



    public static function getlatestKycprofileData($userKycId, $requestFor) {
        return \App\Inv\Repositories\Models\Wcapi::getlatestKycprofileData($userKycId, $requestFor);
    }

    /**
     * Get professional Status drop down
     *
     * @return type
     */
    public static function getProfStatusDropDown() {
        return \App\Inv\Repositories\Models\Master\ProfessionalStatus::getDropDown();
    }

    

    /**
     * Get Fund Source drop down
     * 
     * @return object|array
     */
    public static function getKycStatusDropDown() {
        return ['0' => 'Pending', '1' => 'Completed', '2' => 'Approved', '3' => 'Disapproved'];
    }

    public static function getuserSource() {
        return ['0' => 'All', '1' => 'Compliance User', '2' => 'Trading User'];
    }

    public static function getProfileStatusSource() {
        return ['1' => 'On board', '3' => 'Case by case', '4' => 'Declined','6' => 'Pending'];
    }

    public static function getRiskAssesmentParameters() {
        return ['1' => 'National Identity', '2' => 'Maches (World Check)', '3' => 'Password Verification (World Check)', '4' => 'Exposure (PEP)', '5' => 'General Verification On Social Media'];
    }

    public static function getRiskAssesmentOptions() {

        $arrParamOption = [];
        $arrParamOption['1'] = ['0_5' => 'Blocklisted and sanctioned countries (5)', '1_4' => 'Improving Global AML/CFT Compliance countries (4)', '2_3' => 'Latin America and Africa (3)', '3_2' => 'Asia Pacific and rest of the world (2)', '4_1' => 'Europe,US (1)'];
        $arrParamOption['2'] = ['0_5' => 'Positive (5)', '1_4' => 'Possible Negative (4)', '2_3' => 'Possible Positive (3)', '3_2' => 'False (2)', '4_1' => 'Negative (1)'];
        $arrParamOption['3'] = ['0_1' => 'Verified (1)', '1_5' => 'Not Verified (Invalid) (5)'];
        $arrParamOption['4'] = ['0_5' => 'Direct (5)', '1_4' => 'Indirect (4)', '2_4' => 'Connected (4)', '3_3' => 'Low Conected (3)', '4_2' => 'Indirectly Connected (2)', '5_1' => 'Not Exposed (1)'];
        $arrParamOption['5'] = ['0_1' => '1', '1_2' => '2', '2_3' => '3', '3_4' => '4', '4_5' => '5'];
        return $arrParamOption;
    }

    public static function getRiskAssesmentNames() {
        return ['1' => 'national_identity', '2' => 'maches', '3' => 'password_verification', '4' => 'exposure', '5' => 'Social_Media'];
    }

    public static function getRankNames() {
        return ['1' => 'On Board', '2' => 'On Board', '3' => 'Case By Case', '4' => 'Declined', '5' => 'Declined'];
    }

    /**
     * Get country alfa 3 code by id
     *
     * @return type
     */
    public static function getCountryCodeById($countryId) {
        return \App\Inv\Repositories\Models\Master\Country::getCountryCodeById($countryId);
    }

    
    /**
     * Get date Diff In Days
     *
     * @return int 
     */
    public static function dateDiffInDays($date1,$date2) {
        // Calulating the difference in timestamps 
        $diff = strtotime($date2) - strtotime($date1);


        // 1 day = 24 hours 
        // 24 * 60 * 60 = 86400 seconds 
        return abs(round($diff / 86400));
    }
    
    public static function getDocumentName($docId)
    {
        return \App\Inv\Repositories\Models\DocumentMaster::getDocumentName($docId);
    }

     
    /**
     * Get other Docs DropDown
     *
     * @return object|array
     */
    public static function otherDocsDropDown($doc_for)
    {
        return \App\Inv\Repositories\Models\DocumentMaster::getOtherDocsDropDown((int)$doc_for);
    }
    
    
    /**
     * Get other Docs DropDown
     *
     * @return object|array
     */
    public static function getOtherDocsReq($kyc_id)
    {
        return \App\Inv\Repositories\Models\UserReqDoc::getOtherDocsReq((int)$kyc_id);
    }
    
    //

     /*
     * make model popup with Iframe
     *
     */
    public static function makeIframePopup($modelId, $title, $model){

     //return \App\Inv\Repositories\Models\CorpStatus::all();

        return "<div  class=\"modal\" id=\"$modelId\">
        <div class=\"modal-dialog $model\">
          <div class=\"modal-content\">
              <div class=\"modal-header\">
              <h4 class=\"modal-title\">$title</h4>
              <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
            </div>
              <div class=\"modal-body\">
              <iframe frameborder=\"0\" id=\"Myiframe\"  allowtransparency=\"yes\"  style=\"width:100%;border:none;background-color: #FFFFFF;\"></iframe>
            </div>
          </div>
        </div>
    </div>";
    }




    /**
     * Get aal role
     *      *
     * @param integer $user_id | default
     */
    public static function getAllUsersByRoleId($role_id) {
        $data = RoleUser::getAllUsersByRoleId($role_id);
                return $data;

    }
  /**
     * Get aal role
     *      *
     * @param integer $user_id | default
     */
    public static function getAllRole() {
        $data = Role::getAllRole();
                return $data;

    }

    /**
     * Get aal role
     *      *
     * @param integer $user_id | default
     */
    public static function getRoleById($id) {
        $data = Role::getRole($id);
                return $data;

    }
 /**
     * Check permission
     *      *
     * @param integer $user_id | default
     */
    public static function checkPermission($routePerm) {


        $user_id = \Auth::user()->user_id;
        $roleData = User::getBackendUser($user_id);

        if ($roleData[0]->is_superadmin == 1) {
            return true;
        }
        $role_id = $roleData[0]->id;
        $prData = PermissionRole::getPermissionByRoleID($role_id)->toArray();
        $routes = Permission::getPermissionByArr($prData)->toArray();
        $check = in_array($routePerm, $routes);
        return $check;

    }
 /**
     * Get User Role
     *
     * @param integer $user_id | default
     */
    public static function getUserRole($user_id = null) {
        if (is_null($user_id)) {
            $user_id = \Auth::user()->user_id;
        }
        $roleData = User::getBackendUser($user_id);
        return $roleData;
    }
   /**
     * Get permission by Role id
     *
     * @param integer $app_id
     */
    public static function getByParent($parentId,$isDisplay){
        return Permission::getByParent($parentId, $isDisplay);
    }
     /**
     * Get permission by Role id
     *
     * @param integer $app_id
     */
    public static function checkRole($parentId,$role_id){
        return PermissionRole::checkRole($parentId,$role_id);
    }
    
    
    //From : 2564729699
//Account SID : ACe0a81bb932c465caf7325d73be8cc9fc
//Token : 10bb6d2d49e5b567f26b065dc50d0b3f
//To : 8383069584
    /**
     * send sms Message
     *
     * @param string $message
     * @param string $recipients
     */
    public static function sendSMS($recipients,$message) {
        //$message="HI Test";
        //$recipients='+918860233639';
       
        $account_sid = config('proin.TWILIO_SID');//getenv("TWILIO_SID");
        $auth_token = config('proin.TWILIO_AUTH_TOKEN');//getenv("TWILIO_AUTH_TOKEN");
        $twilio_number = config('proin.TWILIO_NUMBER');//getenv("TWILIO_NUMBER");

        try {
            $client = new Client($account_sid, $auth_token);
            $result=$client->messages->create($recipients, ['from' => $twilio_number, 'body' => $message]);
            return $result;

        } catch (Exception $ex) {
           
            return false;
            
           // return redirect()->back()->withErrors(Helpers::getExceptionMessage($ex));
        }

        
       
     }


     /**
     * Get Fund Source drop down
     * 
     * @return object|array
     */
    public static function getDocForDropDown() {
        return ['1' => 'Individual', '2' => 'Corporate','3'=>'Individual Profile'];
    }
    
    /**
     * Get Fund Source drop down
     * 
     * @return object|array
     */
    public static function getDocTypeDropDown() {
        return ['1' => 'KYC Form Documents', '2' => 'Other Document'];
    }

    
    /**
     * Get Fund Source drop down
     * 
     * @return object|array
     */
    public static function getDocRequiredDropDown() {
        return ['1' => 'Yes', '0' => 'No'];
    }



    /**
     * Get Document Type By Kyc Id
     *
     * @return object|array
     */
    public static function getDocumentTypeInfowithpassport($kyc_id, $passportNumber) {
        return \App\Inv\Repositories\Models\Userdocumenttype::getDocumentTypeInfowithpassport($kyc_id, $passportNumber);
    }


    /**
     * Get Document Type By Kyc Id
     *
     * @return object|array
     */
    public static function getDocumentTypeInfo($kyc_id) {
        return \App\Inv\Repositories\Models\Userdocumenttype::getData($kyc_id);
    }


     /**
     * Get user Address by Kyc Id
     *
     * @return object|array
     */
    public static function getCommercialInfo($kyc_id) {
        return \App\Inv\Repositories\Models\UserResidential::getData($kyc_id);
    }


     public static function getEducationLevel() {
       
      return [
          '1'=>'Literate','2'=>'Primary School','3'=>'Middle School','4'=>'High School','5'=>'Bachelor','6'=>'Post Graduate','7'=>'Master','8'=>'Doctorate','9'=>'Other'
         ];

    }

    public static function getProfessionalStatus() {
       
      return [
           '1'=>'Employed','2'=>'Unemployed','3'=>'Business Owner','4'=>'Self Employed','5'=>'At Home','6'=>'Retired','7'=>'Student','8'=>'Other'
         ];

    }

    
    /**
     * Get Request Status drop down
     * 
     * @return object|array
     */
    public static function getReqStatusDropDown() {
        return ['1' => 'Sent', '2' => 'Completed'];
    }
    
    /**
     * Get Document Status drop down
     * 
     * @return object|array
     */
    public static function getDocStatusDropDown() {
        return ['0' => 'Pending','1' => 'Approved', '2' => 'Disapproved'];
    }
    
    /**
     * Get Document Status drop down
     * 
     * @return object|array
     */
    public static function getMonitoringDropDown() {
        return ['1'=>'Passport','2'=>'Utility Bill','3'=>'Company Trade License'];
    }
    
    





   


    public static function getCompanyDetailStatus($statusId,$kycid) {
        if($statusId!='8'){
           return \App\Inv\Repositories\Models\CorpStatus::getStatus($statusId);
         } else if($statusId == '8') {
              return \App\Inv\Repositories\Models\CompanyProfile::getOtherStatus($kycid);
            }
    }

   

    public  static function getkycdataById($primaryId)
    {
      return \App\Inv\Repositories\Models\Wcapi::getkycdataById($primaryId);
    }
    
    public  static function UpdatePositive($data ,$primaryId)
    {
      return \App\Inv\Repositories\Models\Wcapi::UpdatePositive($data ,$primaryId);
    }

    public  static function getKycDataByKycId($userKycId, $requestFor)
    {
      return \App\Inv\Repositories\Models\Wcapi::getKycDataByKycId($userKycId, $requestFor);
    }
    
    public  static function getDataByKycId($userKycId, $requestFor)
    {
      return \App\Inv\Repositories\Models\Wcapi::getDataByKycId($userKycId, $requestFor);
    }




    public  static function getKycDatabyparentId($id)
    {
      return \App\Inv\Repositories\Models\Wcapi::getKycDatabyparentId($id);
    }


    public static function buildCategory($parent, $category) {

            $html   = "";
            $space  = "";
         
            if (isset($category['parent_share'][$parent])) {
            if($parent == 0) {
                echo "<ul class='main-nested'>";
                //$space = ">>";
            }
            foreach ($category['parent_share'][$parent] as $cat_id) {

                if (!isset($category['parent_share'][$cat_id])) {
                  echo "<li>" . ucfirst($category['shares'][$cat_id]['company_name']) ."( ". $category['shares'][$cat_id]['share_percentage']. " %) (Individual)</li>";
                }

                if (isset($category['parent_share'][$cat_id])) {
                //echo "<li class='remove-bullet'><span class='com-name'>" .ucfirst($category['shares'][$cat_id]['company_name'])."( ".$category['shares'][$cat_id]['actual_share_percent']." %)</span>";
                echo "<li >" .ucfirst($category['shares'][$cat_id]['company_name'])."( ".$category['shares'][$cat_id]['share_percentage']." %) (Corporate)";
                echo '<ul class="nested">';

                self::buildCategory($cat_id, $category);
                echo '</ul></li>';

                }
            }
            if($parent == 0) {
                echo '</ul>';
            }

            }

    }



    public static function getCorpShareStructure($user_id) {

            return \App\Inv\Repositories\Models\ShareHolding::getCorpShareStructure($user_id);
    }

    public  static function getAssesmentData($kycid)
    {
      return \App\Inv\Repositories\Models\UserAssesmentDetail::getData($kycid);
    }

   


    public  static function getAssesmentRankData($kycid, $isActive)
    {
      return \App\Inv\Repositories\Models\UserAssesment::getData($kycid, $isActive);
    }


    public static function callindividualApi($targetEntityId, $token, $get_users_wci_single, $parent_id)
    {
      return \App\Inv\Repositories\Contracts\Traits\ApiAccessTrait::callindividualApi($targetEntityId, $token, $get_users_wci_single, $parent_id);
    }
    
    public static function checkcallindividualApi($targetEntityId)
    {
      return \App\Inv\Repositories\Models\Apilog::checkcallindividualApi($targetEntityId);
    }

    public static function UserpersonalData($kycid)
    {
      return \App\Inv\Repositories\Models\Userpersonal::getKycPersonalData($kycid);
    }

    public  static function getTermCondition($userId) {
        return User::getTermCondition($userId);

    }
    //other Doc Term condition 
      public  static function getOtherTermCondition($userId) {
        return User::getOtherDocTermCondition($userId);
    }


     public  static function getCountryTypeByName($countryName) {
        return \App\Inv\Repositories\Models\Master\Countrytype::getCountryTypeByName($countryName);

    }

     public  static function getSocialmediaNameById($ID) {
        return \App\Inv\Repositories\Models\Master\Socialmedia::getSocialmediaNameById($ID);

    }


    public static function getPassportNumber($documentsData, $type) {
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


     /**
     * Get group ID
     *
     * @return type
     */
    public static function getgroupId()
    {
        return \App\Inv\Repositories\Models\Master\GroupId::getgroupId();
    }
    
     /**
     * Get ToolkitStatus
     *
     * @return type
     */
    public static function gettoolkitStatus()
    {
        return \App\Inv\Repositories\Models\Master\Toolkit::gettoolkitStatus();
    }

    /**
     * Get Toolkit Risk
     *
     * @return type
     */
    public static function gettoolkitRisks()
    {
        return \App\Inv\Repositories\Models\Master\Toolkit::gettoolkitRisks();
    }

    /**
     * Get Toolkit Reason
     *
     * @return type
     */
    public static function gettoolkitReasons()
    {
        return \App\Inv\Repositories\Models\Master\Toolkit::gettoolkitReasons();
    }

    public static function toolketvaluebyId($id)
    {
        return \App\Inv\Repositories\Models\Master\Toolkit::toolketvaluebyId($id);
    }
    
    public static function toolkettypebyId($id)
    {
        return \App\Inv\Repositories\Models\Master\Toolkit::toolkettypebyId($id);
    }


    public static function getResolutionData($id)
    {
        return \App\Inv\Repositories\Models\WcapiResolution::getResolutionData($id);
    }
    
    public static function getResolutionDatabyparentID($id)
    {
        return \App\Inv\Repositories\Models\WcapiResolution::getResolutionDatabyparentID($id);
    }

    public static function getResolutionDatabycaseID($id)
    {
        return \App\Inv\Repositories\Models\WcapiResolution::getResolutionDatabycaseID($id);
    }

    public static function getDatabycaseIDandstatus($caseId, $resolutionStatus)
    {
        return \App\Inv\Repositories\Models\WcapiResolution::getDatabycaseIDandstatus($caseId, $resolutionStatus);
    }

     public static function getcomplincestrail($kycId)
    {
        return \App\Inv\Repositories\Models\Compliancereporttrail::getcomplincestrail($kycId);
    }

    public static function getDatabycaseIDandresulid($caseid, $resultId)
    {
        return \App\Inv\Repositories\Models\WcapiResolution::getDatabycaseIDandresulid($caseid, $resultId);
    }

 

}
