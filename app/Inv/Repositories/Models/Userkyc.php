<?php

namespace App\Inv\Repositories\Models;

use DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Inv\Repositories\Entities\User\Exceptions\BlankDataExceptions;
use App\Inv\Repositories\Entities\User\Exceptions\InvalidDataTypeExceptions;

class Userkyc extends Authenticatable
{

    use Notifiable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_kyc';

    /**
     * Custom primary key is set for the table
     *
     * @var integer
     */
    protected $primaryKey = 'kyc_id';

    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'corp_detail_id',
        'is_by_company',
        'is_approve',
        'is_by_company',
        'is_kyc_completed',
        'is_api_pulled',
        'created_by',
        'created_at',
        'updated_at',
        'updated_by'
        
    ];

    
 /**
     * Save User Kyc
     *
     * @param  array $arrUsers
     *
     * @throws InvalidDataTypeExceptions
     * @throws BlankDataExceptions
     */
    public static function saveKycDetails($arrKyc = [])
    {

        //Check data is Array
        if (!is_array($arrKyc)) {
            throw new InvalidDataTypeExceptions(trans('error_message.send_array'));
        }

        //Check data is not blank
        if (empty($arrKyc)) {
            throw new BlankDataExceptions(trans('error_message.data_not_found'));
        }
        /**
         * Create User Detail
         */
        $objKyc = self::create($arrKyc);

        return ($objKyc ?: false);
    }
    
    public static function getKycDetails($userId)
    {
        /**
         * Check user id is not an integer
         */
        if (!is_int((int) $userId) && $userId != 0) {
            throw new InvalidDataTypeExceptions(trans('error_message.invalid_data_type'));
        }

        $arrKyc = self::where('user_id', (int) $userId)->where('is_kyc_completed',1)
            ->first();
        return ($arrKyc ?: false);
    }
    

   public static function getAllUsersKycPaginate($kyc_type,$user_type){
         /**
         * Check id is not blank
         */
        if (empty($kyc_type)) {
            throw new BlankDataExceptions(trans('error_message.no_data_found'));
        }

        /**
         * Check id is not an integer
         */
        if (!is_int($kyc_type)) {
            throw new InvalidDataTypeExceptions(trans('error_message.invalid_data_type'));
        }
        
        /**
         * Check id is not blank
         */
        if (empty($user_type)) {
            throw new BlankDataExceptions(trans('error_message.no_data_found'));
        }

        /**
         * Check id is not an integer
         */
        if (!is_int($user_type)) {
            throw new InvalidDataTypeExceptions(trans('error_message.invalid_data_type'));
        }
        
        if(!in_array($kyc_type,['1','2','3'])){
            throw new InvalidDataTypeExceptions(trans('error_message.invalid_data_type'));
        }
        
        if($kyc_type=='1'){
            $result = self::select('user_kyc.*','kp.gender','kp.title','kp.f_name','kp.l_name')
                    ->join('user_kyc_personal as kp','kp.user_kyc_id','=','user_kyc.kyc_id')
                    ->join('users','users.user_id','=','user_kyc.user_id')
                    ->where('user_kyc.is_by_company',0)
                    ->where('users.user_type',$user_type)
                    ->paginate(5);
        }else if($kyc_type=='2'){
            $result = self::select('user_kyc.*','users.gender','users.title','users.f_name','users.l_name')
                    ->join('corp_detail as kp','kp.user_kyc_id','=','user_kyc.kyc_id')
                    ->join('users','users.user_id','=','user_kyc.user_id')
                    ->where('user_kyc.is_by_company',0)
                    ->where('users.user_type',$user_type)
                    ->paginate(5);
        }else if($kyc_type=='3'){
            $result = self::select('user_kyc.*','kp.gender','kp.title','kp.f_name','kp.l_name')
                    ->join('user_kyc_personal as kp','kp.user_kyc_id','=','user_kyc.kyc_id')
                    ->where('user_kyc.is_by_company',1)
                    ->paginate(5);
        }
            

        
        return ($result ? $result : '');
    }


     /**
     * Update User Kyc
     *
     * @param  array $arrUsers
     *
     * @throws InvalidDataTypeExceptions
     * @throws BlankDataExceptions
     */

     public static function updateUserKyc($user_kyc_id, $arrData = [])
    {

        
         //$user_kyc_id = 77;
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

        
        $res = self::where('kyc_id', (int)$user_kyc_id)->update($arrData);
        return $res ? $res : false;

        
    }



     public static function getKycDetailsByKycID($user_kyc_id)
    {
        /**
         * Check user id is not an integer
         */
        if (!is_int((int) $user_kyc_id) && $user_kyc_id != 0) {
            throw new InvalidDataTypeExceptions(trans('error_message.invalid_data_type'));
        }

        $arrKyc = self::where('kyc_id', (int) $user_kyc_id)
            ->first();
        return ($arrKyc ?: false);
    }

    public static function kycStatus($userid)
    {
        /**
         * Check user id is not an integer
         */
        if (!is_int((int) $userid) && $userid != 0) {
            throw new InvalidDataTypeExceptions(trans('error_message.invalid_data_type'));
        }

        $status = Userkyc::where('user_id', (int)$userid)->first();
        
        return ($status->is_kyc_completed ?: false);
           

    }
    
    public static function kycApproveStatus($userKycId)
    {
        /**
         * Check user id is not an integer
         */
        if (!is_int((int) $userKycId) && $userKycId != 0) {
            throw new InvalidDataTypeExceptions(trans('error_message.invalid_data_type'));
        }

        $status = Userkyc::where('kyc_id', (int)$userKycId)->first();
        
        return ($status->is_approve ?: false);
           

    }



    public static function kycApproveFinalStatus($userKycId)
    {
        /**
         * Check user id is not an integer
         */
        if (!is_int((int) $userKycId) && $userKycId != 0) {
            throw new InvalidDataTypeExceptions(trans('error_message.invalid_data_type'));
        }

        $status = Userkyc::where('kyc_id', (int)$userKycId)->first();

        return ($status->is_finalapprove ?: false);


    }
    
    
    public static function getUserStatusSummary()
    {
        $summary=[];
        
        $summary['IndvKycPending']   = self::join('users','users.user_id','=','user_kyc.user_id')->where('users.user_type',1)->where('user_kyc.is_kyc_completed',0)->where('user_kyc.is_approve',0)->where('user_kyc.is_by_company',0)->get()->count();
        $summary['CorpKycPending']   = self::join('users','users.user_id','=','user_kyc.user_id')->where('users.user_type',2)->where('user_kyc.is_kyc_completed',0)->where('user_kyc.is_approve',0)->where('user_kyc.is_by_company',0)->get()->count();
        
        $summary['IndvApprPending']   = self::join('users','users.user_id','=','user_kyc.user_id')->where('users.user_type',1)->where('user_kyc.is_kyc_completed',1)->where('user_kyc.is_approve',0)->where('user_kyc.is_by_company',0)->get()->count();
        $summary['CorpApprPending']   = self::join('users','users.user_id','=','user_kyc.user_id')->where('users.user_type',2)->where('user_kyc.is_kyc_completed',1)->where('user_kyc.is_approve',0)->where('user_kyc.is_by_company',0)->get()->count();
        
        $summary['IndvApproved']   = self::join('users','users.user_id','=','user_kyc.user_id')->where('users.user_type',1)->whereIn('user_kyc.is_finalapprove',[1,3])->where('user_kyc.is_by_company',0)->get()->count();
        $summary['CorpApproved']   = self::join('users','users.user_id','=','user_kyc.user_id')->where('users.user_type',2)->whereIn('user_kyc.is_finalapprove',[1,3])->where('user_kyc.is_by_company',0)->get()->count();
        
        
        
        return ($summary ?: false);
           

    } 
    
    /*
     * Get KYC Process Form Status Info
     * @param int user_id
     * @return array
     *@author  arvind soni
    */
    public static function isCorpIndvsKycCompleted($user_id=''){
        $count=self::where('user_id','=',$user_id)->where('is_by_company','=','1')->where('is_kyc_completed','=','0')->get()->count();
        return ($count?:0);
    }
   

    public static function getKycDetailsbyType($userId,$is_by_company)
    {
        /**
         * Check user id is not an integer
         */
        if (!is_int((int) $userId) && $userId != 0) {
            throw new InvalidDataTypeExceptions(trans('error_message.invalid_data_type'));
        }

        $arrKyc = self::where('user_id', (int) $userId)
            ->where('is_by_company',$is_by_company)
            ->where('is_kyc_completed',1)
            ->first();
        return ($arrKyc ?: false);
    }
}