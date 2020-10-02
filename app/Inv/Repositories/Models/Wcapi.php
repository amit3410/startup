<?php

namespace App\Inv\Repositories\Models;

use DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Inv\Repositories\Entities\User\Exceptions\BlankDataExceptions;
use App\Inv\Repositories\Entities\User\Exceptions\InvalidDataTypeExceptions;

class Wcapi extends Authenticatable
{

    use Notifiable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'wcapi_req_res';

    /**
     * Custom primary key is set for the table
     *
     * @var integer
     */
    protected $primaryKey = 'wcapi_req_res_id';

    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_kyc_id',
        'parent_id',
        'request_for',
        'endpoint',
        'caseid',
        'profile',
        'f_name',
        'm_name',
        'l_name',
        'gender',
        'dob',
        'date_of_birth',
        'country_id',
        'org_name',
        'org_country_id',
        'is_approved',
        'is_final',
        'match_strenght',
        'pep',
        'passport',
        'is_passport',
        'api_request',
        'api_responce',
        'http_responce',
        'by_whome',
        'created_by',
        'created_at',
        'updated_at',
        'updated_by'
        
    ];

    
 /**
     * Save WCAPI Request and Response
     *
     * @param  array $arr
     *
     * @throws InvalidDataTypeExceptions
     * @throws BlankDataExceptions
     */
    public static function saveWcapiCall($arr = [])
    {

        //Check data is Array
        if (!is_array($arr)) {
            throw new InvalidDataTypeExceptions(trans('error_message.send_array'));
        }

        //Check data is not blank
        if (empty($arr)) {
            throw new BlankDataExceptions(trans('error_message.data_not_found'));
        }
        /**
         * Create API Request and response
         */
        $objWcapi = self::create($arr);

        return ($objWcapi ? $objWcapi : false);
    }
    
   

   
    

    /**
     * get WC API details
     *
     * @param integer $userKycId user_kyc_id
     *
     *
     * @return boolean
     */
    public static function getWcapiDetail($userKycId, $requestFor)
    {
      

       if (empty($userKycId)) {
            throw new BlankDataExceptions(trans('error_message.no_data_found'));
        }
        //Check id is not an integer

        if (!is_int($userKycId)) {
            throw new InvalidDataTypeExceptions(trans('error_message.invalid_data_type'));
        }


       $arrResponcce = self::select('wcapi_req_res.*')
            ->where('user_kyc_id', (int) $userKycId)
            ->where('request_for', $requestFor)
            //->where('wcapi_req_res_id', 177)
            ->orderBy('wcapi_req_res_id', 'DESC')
            ->first();

        return ($arrResponcce ?: false);
    }

    



    public static function getWcapiPersonalDetail($userKycId, $requestFor)
    {


       if (empty($userKycId)) {
            throw new BlankDataExceptions(trans('error_message.no_data_found'));
        }
        //Check id is not an integer

        if (!is_int($userKycId)) {
            throw new InvalidDataTypeExceptions(trans('error_message.invalid_data_type'));
        }

       $wherein = array(0=>1,1=>2);
       $arrUser = self::select('wcapi_req_res.*')
            ->where('user_kyc_id', (int) $userKycId)
            ->wherein('is_approved', $wherein)
            ->where('request_for', $requestFor)
            ->orderBy('wcapi_req_res_id', 'DESC')
            ->first();

        return ($arrUser ?: false);
    }


    /**
     * get WC API Personal details
     *
     * @param integer $userKycId     user_kyc_id
     *
     *
     * @return boolean
     */
    public static function getKycprofileData($userKycId, $referenceId, $requestFor)
    {


       if (empty($userKycId)) {
            throw new BlankDataExceptions(trans('error_message.no_data_found'));
        }
        //Check id is not an integer

        if (!is_int($userKycId)) {
            throw new InvalidDataTypeExceptions(trans('error_message.invalid_data_type'));
        }


       $arrUser = self::select('wcapi_req_res.*')
            ->where('user_kyc_id', (int) $userKycId)
            ->where('profile', $referenceId)
            ->where('request_for', $requestFor)->orderBy('wcapi_req_res_id', 'DESC')
            ->first();

        return ($arrUser ?: false);
    }



    /**
     * get Latest WC API Personal details
     *
     * @param integer $userKycId     user_kyc_id
     *
     *
     * @return boolean
     */
    public static function getlatestKycprofileData($userKycId, $requestFor)
    {
       if (empty($userKycId)) {
            throw new BlankDataExceptions(trans('error_message.no_data_found'));
        }
        //Check id is not an integer
        if (!is_int($userKycId)) {
            throw new InvalidDataTypeExceptions(trans('error_message.invalid_data_type'));
        }
        //$wherein = array(0=>1,1=>2);
       $arrUser = self::select('wcapi_req_res.*')
            ->where('user_kyc_id', (int) $userKycId)
            ->where('request_for', $requestFor)->orderBy('wcapi_req_res_id', 'DESC')
            ->first();
        return ($arrUser ?: false);
    }


    /**
     * get WC API Personal details
     *
     * @param integer $userKycId  user_kyc_id
     *
     *
     * @return boolean
     */
    public static function getKycprofileDatagraterPID($userKycId, $referenceId, $requestFor, $primaryId)
    {


       if (empty($userKycId)) {
            throw new BlankDataExceptions(trans('error_message.no_data_found'));
        }
        //Check id is not an integer

        if (!is_int($userKycId)) {
            throw new InvalidDataTypeExceptions(trans('error_message.invalid_data_type'));
        }
       $wherein = array(0=>1,1=>2);
       $arrUser = self::select('wcapi_req_res.*')
            ->where('user_kyc_id', (int) $userKycId)
            //->where('profile', $referenceId)
            ->where('wcapi_req_res_id','>', $primaryId)
            ->wherein('is_approved', $wherein)
            ->where('request_for', $requestFor)->orderBy('wcapi_req_res_id', 'DESC')
            ->first();
        return ($arrUser ?: false);
    }



    /**
     * get WC API Personal details
     *
     * @param integer $userKycId  user_kyc_id
     *
     *
     * @return boolean
     */
    public static function getAllKycprofileDatagraterPID($userKycId, $requestFor, $primaryId)
    {


       if (empty($userKycId)) {
            throw new BlankDataExceptions(trans('error_message.no_data_found'));
        }
        //Check id is not an integer

        if (!is_int($userKycId)) {
            throw new InvalidDataTypeExceptions(trans('error_message.invalid_data_type'));
        }
       $wherein = array(0=>1,1=>2);
       $arrUser = self::select('wcapi_req_res.*')
            ->where('user_kyc_id', (int) $userKycId)
            ->where('wcapi_req_res_id','>', $primaryId)
            //->wherein('is_approved', $wherein)
            ->where('request_for', $requestFor)
            ->orderBy('wcapi_req_res_id', 'DESC')
            ->get();
        return ($arrUser ?: false);
    }



/**
     * get WC API Personal details
     *
     * @param integer $userKycId  user_kyc_id
     *
     *
     * @return boolean
     */
    public static function getAllKycprofileDataHistory($userKycId, $requestFor)
    {


       if (empty($userKycId)) {
            throw new BlankDataExceptions(trans('error_message.no_data_found'));
        }
        //Check id is not an integer

        if (!is_int($userKycId)) {
            throw new InvalidDataTypeExceptions(trans('error_message.invalid_data_type'));
        }
       $arrUser = self::select('wcapi_req_res.*')
            ->where('user_kyc_id', (int) $userKycId)
            ->where('request_for', $requestFor)
            ->orderBy('wcapi_req_res_id', 'DESC')
            ->get();
        return ($arrUser ?: false);
    }





    public static function updateUserKycWC($user_kyc_id, $profileId, $responceProfileId, $arrData = [], $updatefor)
    {
        /**
         * Check id is not blank
         */
        if (empty($user_kyc_id)) {
            throw new BlankDataExceptions(trans('error_message.no_data_found'));
        }
        /**
         * Check Data is Array
         */
        if (!is_array($arrData)) {
            throw new InvalidDataTypeExceptions(trans('error_message.send_array'));
        }

        /**
         * Check Data is not blank
         */
        if (empty($arrData)) {
            throw new BlankDataExceptions(trans('error_message.no_data_found'));
        }
       /* $res = self::where('user_kyc_id', (int)$user_kyc_id)
            ->where('wcapi_req_res_id', (int)$responceProfileId)
            ->where('profile', $profileId)
            ->update($arrData) ; */
        if($updatefor == 1) {
        $res = self::where('user_kyc_id', (int)$user_kyc_id)
           // ->where('parent_id', (int)$responceProfileId)
            ->where('wcapi_req_res_id', (int)$responceProfileId)
            ->where('profile', $profileId)
            ->update($arrData) ;
        } else if($updatefor == 2){
         $res = self::where('user_kyc_id', (int)$user_kyc_id)
           // ->where('parent_id', (int)$responceProfileId)
            ->where('wcapi_req_res_id', (int)$responceProfileId)
            
            ->update($arrData) ;
        }
        return $res ? $res : false;
    }

 /**
     * get WC API Personal details
     *
     * @param integer $userKycId     user_kyc_id
     *
     *
     * @return boolean
     */

    public static function getWcapiPersonalDetailFinal($userKycId, $requestFor)
    {


       if (empty($userKycId)) {
            throw new BlankDataExceptions(trans('error_message.no_data_found'));
        }
        //Check id is not an integer

        if (!is_int($userKycId)) {
            throw new InvalidDataTypeExceptions(trans('error_message.invalid_data_type'));
        }

       $wherein = array(0=>1,1=>2);
       $arrUser = self::select('wcapi_req_res.*')
            ->where('user_kyc_id', (int) $userKycId)
            ->wherein('is_approved', $wherein)
            ->where('request_for', $requestFor)
            ->where('is_final', 1)
            ->orderBy('wcapi_req_res_id', 'DESC')
            ->first();

        return ($arrUser ?: false);
    }

    /**
     * get WC API details By ID
     *
     * @param integer $Id 
     *
     *
     * @return boolean
     */
    public static function getkycData($Id)
    {
       if (empty($Id)) {
            throw new BlankDataExceptions(trans('error_message.no_data_found'));
        }
        //Check id is not an integer
        if (!is_int($Id)) {
            throw new InvalidDataTypeExceptions(trans('error_message.invalid_data_type'));
        }

       
       $arrUser = self::select('wcapi_req_res.*')
            ->where('wcapi_req_res_id', (int) $Id)
            ->first();
        return ($arrUser ?: false);
    }



     public static function updatebyId($Id, $arrData = [])
    {

        /**
         * Check id is not blank
         */
        if (empty($Id)) {
            throw new BlankDataExceptions(trans('error_message.no_data_found'));
        }
        /**
         * Check Data is Array
         */
        if (!is_array($arrData)) {
            throw new InvalidDataTypeExceptions(trans('error_message.send_array'));
        }

        /**
         * Check Data is not blank
         */
        if (empty($arrData)) {
            throw new BlankDataExceptions(trans('error_message.no_data_found'));
        }


        $res = self::where('wcapi_req_res_id', (int)$Id)
                    ->update($arrData);
        return $res ? $res : false;


    }
    
    /**
     * get WC API By primaryID
     *
     * @param integer $primaryId
     *
     *
     * @return boolean
     */
    public static function getkycdataById($primaryId)
    {
       $arrUser = self::select('wcapi_req_res.*')
            ->where('wcapi_req_res_id','=', $primaryId)
            ->first();
        return ($arrUser ?: false);
    }

    public static function UpdatePositive($data ,$primaryId) {
         $res = self::where('wcapi_req_res_id', (int)$primaryId)
                    ->update($data);
        return $res ? $res : false;
    }



    /**
     * get WC API Personal details
     *
     * @param integer $userKycId
     *
     *
     * @return boolean
     */
    public static function getKycDataByKycId($userKycId, $requestFor)
    {
       if (empty($userKycId)) {
            throw new BlankDataExceptions(trans('error_message.no_data_found'));
        }
        //Check id is not an integer
        if (!is_int($userKycId)) {
            throw new InvalidDataTypeExceptions(trans('error_message.invalid_data_type'));
        }
       $arrUser = self::select('wcapi_req_res.*')
            ->where('user_kyc_id', (int) $userKycId)
            ->where('is_approved', 1)
            ->where('is_final', 1)
            ->where('request_for', $requestFor)->orderBy('wcapi_req_res_id', 'DESC')
            ->first();
        return ($arrUser ?: false);
    }

    /**
     * get WC API Personal details By parent ID
     *
     * @param integer $userKycId  parent_id
     *
     *
     * @return boolean
     */
    public static function getKycDatabyparentId($parent_id)
    {
       // echo "-->".$parent_id; exit;
       if (empty($parent_id)) {
            throw new BlankDataExceptions(trans('error_message.no_data_found'));
        }
        //Check id is not an integer

        if (!is_int($parent_id)) {
            throw new InvalidDataTypeExceptions(trans('error_message.invalid_data_type'));
        }
       
       $arrUser = self::select('wcapi_req_res.*')
            ->where('wcapi_req_res_id', $parent_id)
            ->first();
        return ($arrUser ?: false);
    }


     /**
     * search WC API By caseID
     *
     * @param integer $primaryId
     *
     *
     * @return boolean
     */
    public static function getdataByCaseid($caseId)
    {
        if (empty($caseId)) {
            throw new BlankDataExceptions(trans('error_message.no_data_found'));
        }
        $dataArray = self::select('wcapi_req_res.*')
            ->where('caseid','=', $caseId)
            ->first();
        return ($dataArray ?: false);
    }


    /**
     * get WC API Personal details
     *
     * @param integer $KycId
     *
     *
     * @return boolean
     */
    public static function getDataByKycId($userKycId, $requestFor)
    {
       if (empty($userKycId)) {
            throw new BlankDataExceptions(trans('error_message.no_data_found'));
        }
        //Check id is not an integer
        if (!is_int($userKycId)) {
            throw new InvalidDataTypeExceptions(trans('error_message.invalid_data_type'));
        }
       $arrUser = self::select('wcapi_req_res.*')
            ->where('user_kyc_id', (int) $userKycId)
            ->where('request_for', $requestFor)->orderBy('wcapi_req_res_id', 'DESC')
            ->first();
        return ($arrUser ?: false);
    }


}