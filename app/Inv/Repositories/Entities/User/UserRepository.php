<?php

namespace App\Inv\Repositories\Entities\User;

use Carbon\Carbon;

use App\Inv\Repositories\Models\Relationship;
use App\Inv\Repositories\Models\Userdetail;
use App\Inv\Repositories\Models\Corpdetail;
use App\Inv\Repositories\Models\Userkyc;
use App\Inv\Repositories\Models\Otp;
use App\Inv\Repositories\Models\KycOtp;
use App\Inv\Repositories\Models\ProfileMOnitoringReminder;
use App\Inv\Repositories\Models\Userpersonal;
use App\Inv\Repositories\Models\Userdocumenttype;
use App\Inv\Repositories\Models\DocumentMaster;
use App\Inv\Repositories\Models\Document;
use App\Inv\Repositories\Models\UserSocialmedia;
use App\Inv\Repositories\Models\CorpSocialmedia;
use App\Inv\Repositories\Contracts\UserInterface;
use App\Inv\Repositories\Models\User as UserModel;
use App\Inv\Repositories\Models\UserFamily;
use App\Inv\Repositories\Models\UserResidential;
use App\Inv\Repositories\Models\UserBussinessAddress;
use App\Inv\Repositories\Models\UserProfessional;
use App\Inv\Repositories\Models\UserCommercial;
use App\Inv\Repositories\Models\UserFinancial;
use App\Inv\Repositories\Models\Compliancereport;
use App\Inv\Repositories\Models\Compliancereportlog;
use App\Inv\Repositories\Models\Compliancereportfor;
use App\Inv\Repositories\Models\Compliancereporttrail;
use App\Inv\Repositories\Models\UserKycProcessStatus;
use App\Inv\Repositories\Models\Master\KycProcessForm;
use App\Inv\Repositories\Models\UserAssesmentDetail;
use App\Inv\Repositories\Models\UserAssesment;
use App\Inv\Repositories\Contracts\Traits\AuthTrait;
use App\Inv\Repositories\Models\Master\Role;
use App\Inv\Repositories\Models\Master\Permission as PermissionModel;
use App\Inv\Repositories\Models\Master\PermissionRole as PermissionRole;
use App\Inv\Repositories\Models\Master\RoleUser;
use App\Inv\Repositories\Factory\Repositories\BaseRepositories;
use App\Inv\Repositories\Contracts\Traits\CommonRepositoryTraits;
use App\Inv\Repositories\Entities\User\Exceptions\BlankDataExceptions;
use App\Inv\Repositories\Entities\User\Exceptions\InvalidDataTypeExceptions;
use App\Inv\Repositories\Models\ApiUser as ApiUserModel;
use App\Inv\Repositories\Models\Apilog;

class UserRepository extends BaseRepositories implements UserInterface
{

    use CommonRepositoryTraits,
        AuthTrait;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Create method
     *
     * @param array $attributes
     */
    protected function create(array $attributes)
    {
        
        return UserModel::create($attributes);
    }

    /**
     * Find method
     *
     * @param mixed $id
     * @param array $columns
     */
    public function find($id, $columns = array('*'))
    {
       
        return (UserModel::find($id)) ?: false;
    }

    /**
     * Update method
     *
     * @param array $attributes
     */
    protected function update(array $attributes, $id)
    {
        $result = UserModel::updateUser((int) $id, $attributes);

        return $result ?: false;
    }

    /**
     * Delete method
     *
     * @param mixed $ids
     */
    protected function destroy($ids)
    {
        //
    }

    /**
     * Validating and parsing data passed thos this method
     *
     * @param array $attributes
     * @param mixed $user_id
     *
     * @return New record ID that was added
     *
     * @since 0.1
     */
    public function save($attributes = [], $userId = null)
    {
        /**
         * Check Data is Array
         */
        if (!is_array($attributes)) {
            throw new InvalidDataTypeExceptions('Please send an array');
        }

        /**
         * Check Data is not blank
         */
        if (empty($attributes)) {
            throw new BlankDataExceptions('No Data Found');
        }

        return is_null($userId) ? $this->create($attributes) : $this->update($attributes,$userId);
    }


    /**
     * Validating and parsing data passed though this method
     *
     * @param array $attributes
     *
     * @return New record ID that was added
     *
     * @since 0.1
     */
    public function saveUserDetails($attributes)
    {
        /**
         * Check Data is Array
         */
        if (!is_array($attributes)) {
            throw new InvalidDataTypeExceptions('Please send an array');
        }

        /**
         * Check Data is not blank
         */
        if (empty($attributes)) {
            throw new BlankDataExceptions('No Data Found');
        }
        return Userdetail::saveUserDetails($attributes);
    }

/**
     * Validating and parsing data passed though this method
     *
     * @param array $attributes
     *
     * @return New record ID that was added
     *
     * @since 0.1
     */
    public function saveCorpDetails($attributes)
    {
        /**
         * Check Data is Array
         */
        if (!is_array($attributes)) {
            throw new InvalidDataTypeExceptions('Please send an array');
        }

        /**
         * Check Data is not blank
         */
        if (empty($attributes)) {
            throw new BlankDataExceptions('No Data Found');
        }
        return Corpdetail::saveCorpDetails($attributes);
    }



    /**
     * Validating and parsing data passed though this method
     *
     * @param array $attributes
     *
     * @return New Kyc Detail add
     *
     */
    public function saveKycDetails($attributes)
    {
        /**
         * Check Data is Array
         */
        if (!is_array($attributes)) {
            throw new InvalidDataTypeExceptions('Please send an array');
        }

        /**
         * Check Data is not blank
         */
        if (empty($attributes)) {
            throw new BlankDataExceptions('No Data Found');
        }
        return Userkyc::saveKycDetails($attributes);
    }



    /**
     * Get a user model by email
     *
     * @param string $email
     *
     * @return boolean
     *
     * @since 0.1
     */
    public function getUserByEmail($email)
    {
        $result = UserModel::where('email', $email)->first();

        return $result ?: false;
    }
   


      /**
     * Get a user model by user_name
     *
     * @param string $userName
     *
     * @return boolean
     *
     * @since 0.1
     */
    public function getUserByuserName($userName)
    {
        $result = UserModel::where('username', $userName)->first();

        return $result ?: false;
    }

   


 


     /**
     * Validating and parsing data passed thos this method
     *
     * @param array $attributes
     * @param mixed $user_id
     *
     * @return New record ID that was added
     *
     * @since 0.1
     */
    public function saveOtp($attributes)
    {
        /**
         * Check Data is Array
         */
        if (!is_array($attributes)) {
            throw new InvalidDataTypeExceptions('Please send an array');
        }

        /**
         * Check Data is not blank
         */
        if (empty($attributes)) {
            throw new BlankDataExceptions('No Data Found');
        }
        return Otp::saveOtp($attributes);
    }
    

    
    /**
     * Validating and parsing data passed thos this method
     *
     * @param array $attributes
     * @param mixed $user_id
     *
     * @return New record ID that was added
     *
     * @since 0.1
     */
    public function updateOtp($attributes, $id)
    {
        /**
         * Check Data is Array
         */
        if (!is_array($attributes)) {
            throw new InvalidDataTypeExceptions('Please send an array');
        }

        /**
         * Check Data is not blank
         */
        if (empty($attributes)) {
            throw new BlankDataExceptions('No Data Found');
        }
        return Otp::updateOtp($attributes, $id);
    }

        /**
     * Validating and parsing data passed thos this method
     *
     * @param array $attributes
     * @param mixed $user_id
     *
     * @return New record ID that was added
     *
     * @since 0.1
     */
    public function updateOtpByExpiry($attributes, $id)
    {
        /**
         * Check Data is Array
         */
        if (!is_array($attributes)) {
            throw new InvalidDataTypeExceptions('Please send an array');
        }

        /**
         * Check Data is not blank
         */
        if (empty($attributes)) {
            throw new BlankDataExceptions('No Data Found');
        }
        return Otp::updateOtpByExpiry($attributes, $id);
    }

    /**
     * Get a user model by otp
     *
     * @param string $opt
     *
     * @return boolean
     *
     * @since 0.1
     */
    public function getOtps($otp)
    {
       // $result = Otp::where('otp_no', $otp)->first();

        //return $result ?: false;

         return Otp::getOtps($otp);
    }
    
    public function selectOtpbyLastExpired($user_id)
    {
         return Otp::selectOtpbyLastExpired($user_id);
    }
    
    public function selectOtpbyLastExpiredByThirty($user_id)
    {

         return Otp::selectOtpbyLastExpiredByThirty($user_id);
    }
    
    public function getOtpsbyActive($otp)
    {
       // $result = Otp::where('otp_no', $otp)->first();

        //return $result ?: false;

         return Otp::getOtpsbyActive($otp);
    }
    

    /**
     * Get a user model by otp
     *
     * @param string $opt
     *
     * @return boolean
     *
     * @since 0.1
     */
    public function getUserByOPT($otp)
    {
       // $result = Otp::where('otp_no', $otp)->first();

        //return $result ?: false;

         return Otp::getUserByOPT($otp);
    }




    /**
     * Get a user model by id
     *
     * @param integer $userId
     *
     * @return boolean
     *
     * @since 0.1
     */
    public function getUserID($userId)
    {
        $result = UserModel::getUserID((int) $userId);

        return $result ?: false;
    }


    /**
     * Get a user model by id
     *
     * @param integer $userId
     *
     * @return boolean
     *
     * @since 0.1
     */
    public function getUserDetail($userId)
    {
        $result = UserModel::getUserDetail((int) $userId);

        return $result ?: false;
    }



    //
    
     /**
     * Get a user model by id
     *
     * @param integer $userId
     *
     * @return boolean
     *
     * @since 0.1
     */
    public function getCorpUserDetail($userId)
    {
        $result = UserModel::getCorpUserDetail((int) $userId);

        return $result ?: false;
    }



    /**
     * Get a user model by id for Admin
     *
     * @param integer $userId
     *
     * @return boolean
     *
     * @since 0.1
     */
    public function getCorpUserDetailAdmin($userId)
    {
        $result = UserModel::getCorpUserDetailAdmin((int) $userId);

        return $result ?: false;
    }

     /**
     * Get a user model by id
     *
     * @param integer $userId
     *
     * @return boolean
     *
     * @since 0.1
     */
    public function getfullUserDetail($userId)
    {
        $result = UserModel::getfullUserDetail((int) $userId);

        return $result ?: false;
    }

    /**
     * Get a user model by id
     *
     * @param integer $userId
     *
     * @return boolean
     *
     * @since 0.1
     */
    public function getAllUsers()
    {
        $result = UserModel::getAllUsers();

        return $result ?: false;
    }
    
    
    /**
     * Get a user model by id
     *
     * @param integer $userId
     *
     * @return boolean
     *
     * @since 0.1
     */
    public function getAllUsersPaginate($user_type,$filter)
    {
        $result = UserModel::getAllUsersPaginate($user_type,$filter);

        return $result ?: false;
    }


     public function getAllTradingUsersPaginate($user_type,$filter)
    {
        $result = UserModel::getAllTradingUsersPaginate($user_type,$filter);

        return $result ?: false;
    }
    
    /**
     * Get a user model by id
     *
     * @param integer $userId
     *
     * @return boolean
     *
     * @since 0.1
     */
    public function getProfileMonitoringPaginate($filter)
    {
        $result = Userdocumenttype::getProfileMonitoringPaginate($filter);

        return $result ?: false;
    }
    
    /**
     * Get a user model by id
     *
     * @param integer $userId
     *
     * @return boolean
     *
     * @since 0.1
     */
    public function getAllUsersKycPaginate($kyc_type,$userType)
    {
        $result = Userkyc::getAllUsersKycPaginate($kyc_type,$userType);

        return $result ?: false;
    }
    

    /**
     * Save backend user
     *
     * @param integer $userId
     *
     * @return boolean
     *
     * @since 0.1
     */
    public function updateUser(array $attributes, $userId)
    {
        $result = UserModel::updateBackendUser((int) $userId, $attributes);

        return $result ?: false;
    }

    /**
     * delete user
     *
     * @param integer $userId
     *
     * @return boolean
     *
     * @since 0.1
     */
    public function deleteUser(array $userId)
    {  
        $current_data_time = Carbon::now()->toDateTimeString();
        $result = UserModel::whereIn('id', $userId)->update(['deleted_at' => $current_data_time]);
        return $result ?: false;
    }
    
    
    /**
     * Get a user model by email and is_block
     *
     * @param string $email
     *
     * @return boolean
     *
     * @since 0.1
     */
    public function getUserByEmailforOtp($email)
    {
        $result = UserModel::where('email', $email)
            ->where('is_active', 0)
            ->where('is_otp_verified', 0)
            ->first();

        return $result ?: false;
    }




    /**
     * Validating and parsing data passed thos this method
     *
     * @param array $attributes
     * @param mixed $user_id
     *
     * @return New record ID that was added
     *
     * @since 0.1
     */
    public function saveUserPersonal($attributes,$id)
    {
        /**
         * Check Data is Array
         */
        if (!is_array($attributes)) {
            throw new InvalidDataTypeExceptions('Please send an array');
        }

        /**
         * Check Data is not blank
         */
        if (empty($attributes)) {
            throw new BlankDataExceptions('No Data Found');
        }
        return Userpersonal::saveUserPersonal($attributes,$id);
    }



/**
     * Get a user Corporation Data model by id
     *
     * @param integer $userId
     *
     * @return boolean
     *
     * @since 0.1
     */
   

     public function getUserPersonalData($userId)
    {
        $result = UserModel::getUserPersonalData((int) $userId);

        return $result ?: false;
    }
    
   /**
     * Get a user Corporation Data model by id
     *
     * @param integer $userId
     *
     * @return boolean
     *
     * @since 0.1
     */
   

     public function getUserCorpData($userId)
    {
        $result = UserModel::getUserCorpData((int) $userId);

        return $result ?: false;
    }



    //
    /**
     * Get user KYC Personal data
     *
     * @param 
     * @param integer $user_id
     * @return array
     */
    public function storeUseKycPersonalData($inputData,$id){
        return Userpersonal::storeData($inputData,$id);
    }

    /**
     * Get user KYC Personal data
     *
     * @param 
     * @param integer $user_id
     * @return array
     */
    public function getPersonalInfo($user_id){
        
        return Userpersonal::getData($user_id);
    }
    
    
    /**
     * Get user KYC Personal data
     *
     * @param 
     * @param integer $user_id
     * @return array
     */
    public function getUseKycPersonalData($user_kyc_id){
        
        return Userpersonal::getKycPersonalData($user_kyc_id);
    }
     
    
    /**
     * Store user family data
     *
     * @param array $inputData
     * @param integer $Id
     * @return array
     */
    public function storeUseKycFamilyData($inputData,$id)
    {
       
        //dd($inputData);
        return UserFamily::storeData($inputData,$id);
    }
    
    
    /**
     * Get user KYC Residential data
     *
     * @param 
     * @param integer $user_id
     * @return array
     */
    public function getFamilyInfo($user_kyc_id){
        
        return UserFamily::getData($user_kyc_id);
    }
    
    
    /**
     * Store user Residential data
     *
     * @param array $inputData
     * @param integer $Id
     * @return array
     */
    public function storeUserKycResidentialData($inputData,$id)
    {
        return UserResidential::storeData($inputData,$id);
    }
    
    /**
     * Get user KYC Residential data
     *
     * @param 
     * @param integer $user_id
     * @return array
     */
    public function getResidentialInfo($user_kyc_id){
        
        return UserResidential::getData($user_kyc_id);
    }
    
    /**
     * Store user KYC Professional data
     *
     * @param array $inputData
     * @param integer $Id
     * @return array
     */
    public function storeUserKycProfessionalData($inputData,$id)
    {
        return UserProfessional::storeData($inputData,$id);
    }
    
     
    /**
     * Get user KYC Financial data
     *
     * @param 
     * @param integer $user_id
     * @return array
     */
    public function getProfessionalInfo($user_kyc_id){
        
        return UserProfessional::getData($user_kyc_id);
    }
    
    /**
     * Store user KYC Commercial data
     *
     * @param array $inputData
     * @param integer $Id
     * @return array
     */
    public function storeUserKycCommercialData($inputData,$id)
    {
        return UserCommercial::storeData($inputData,$id);
    }
    
    /**
     * Get user KYC Financial data
     *
     * @param 
     * @param integer $user_id
     * @return array
     */
    public function getCommercialInfo($user_kyc_id){
        
        return UserCommercial::getData($user_kyc_id);
    }
    
    /**
     * Store user KYC Financial data
     *
     * @param array $inputData
     * @param integer $Id
     * @return array
     */
    public function storeUserKycFinancialData($inputData,$id)
    {
        return UserFinancial::storeData($inputData,$id);
    }
    
    /**
     * Get user KYC Financial data
     *
     * @param 
     * @param integer $user_id
     * @return array
     */
    public function getFinancialInfo($user_kyc_id){
        
        return UserFinancial::getData($user_kyc_id);
    }
    
     /**
     * Store User Kyc DocumentTypeData
     *
     * @param array $inputData
     * @param integer $Id
     * @return array
     */
    public function storeUserKycDocumentTypeData($inputData,$id)
    {
        return Userdocumenttype::storeData($inputData,$id);
    }
    
    /**
     * get Document Type Info
     *
     * @param 
     * @param integer $user_kyc_id
     * @return array
     */
    public function getDocumentTypeInfo($user_kyc_id){
        return Userdocumenttype::getData($user_kyc_id);
        
        //return Userdocumenttype::getData($user_kyc_id);
    }
    
    
    /**
     * Get user Document Type data
     *
     * @param 
     * @param integer $user_id
     * @return array
     */
    public function deleteDocumentType($user_kyc_id){
        
        return Userdocumenttype::deleteData($user_kyc_id);
    }

    /**
     * Get user Document Name
     *
     * @param
     * @param integer $user_id
     * @return array
     */
    public function getPrimaryDocumentName(){

        return DocumentMaster::getPrimaryDocumentName();
    }
    //
    /**
     * Store user KYC Financial data
     *
     * @param array $inputData
     * @param integer $Id
     * @return array
     */
    public function storeUserKycSocialmediaData($inputData)
    {
        return UserSocialmedia::storeData($inputData,null);
    }


    public function storeCorpUserKycSocialmediaData($inputData)
    {
        return CorpSocialmedia::storeData($inputData,null);
    }
    
    /**
     * Get user KYC Financial data
     *
     * @param 
     * @param integer $user_id
     * @return array
     */
    public function getSocialmediaInfo($user_kyc_id){
        
        return UserSocialmedia::getData($user_kyc_id);
    }
    
     public function getCorpSocialmediaInfo($user_kyc_id){
        
        return CorpSocialmedia::getData($user_kyc_id);
    }

    
    /**
     * Get user Document Type data
     *
     * @param 
     * @param integer $user_id
     * @return array
     */
    public function deleteSocialmediaInfo($user_kyc_id){
        
        return UserSocialmedia::deleteData($user_kyc_id);
    }
    
     public function deleteCorpSocialmediaInfo($user_kyc_id){
        
        return CorpSocialmedia::deleteData($user_kyc_id);
    }

    /**
     * Store user KYC Bissiness Address data
     *
     * @param array $inputData
     * @param integer $Id
     * @return array
     */
    public function storeUserKycBussAddrData($inputData,$id)
    {
        return UserBussinessAddress::storeData($inputData,$id);
    }
    
    /**
     * Get user KYC Bissiness Address data
     *
     * @param 
     * @param integer $user_id
     * @return array
     */
    public function getBussAddrInfo($user_kyc_id){
        
        return UserBussinessAddress::getData($user_kyc_id);
    }

     public function getcorpDetail($userId)
    {
        $result = Corpdetail::getcorpDetail((int) $userId);

        return $result ?: false;
    }
   
    // 
    
    /**
     * Get Individual User Detail
     *
     * @param 
     * @param integer $user_id
     * @return array
     */
   
     public function getIndvDetail($userId)
    {
        $result = Userdetail::getIndvDetail((int) $userId);

        return $result ?: false;
    }
    
    /**
     * Store user KYC Process Status
     *
     * @param array $inputData
     * @param integer $Id
     * @return array
     */
    public function getKycProcessForms($kyc_type)
    {
        return KycProcessForm::getData($kyc_type);
    }
    
    /**
     * Store user KYC Process Status
     *
     * @param array $inputData
     * @param integer $Id
     * @return array
     */
    public function saveBatchData($inputData)
    {
        return UserKycProcessStatus::saveBatchData($inputData);
    }
    
    //
    
    /**
     * Store user KYC Process Status
     *
     * @param array $inputData
     * @param integer $Id
     * @return array
     */
    public function updateKycProcessStatus($attributes,$user_kyc_id,$form_id)
    {
        return UserKycProcessStatus::updateStatus($attributes,$user_kyc_id,$form_id);
    }
    
    /**
     * Store user KYC Process Status
     *
     * @param array $inputData
     * @param integer $Id
     * @return array
     */
    public function checkKycProcessComplete($user_kyc_id)
    {
        return UserKycProcessStatus::checkKycProcessComplete($user_kyc_id);
    }
    

    /**
     * Store Asssesment detail
     *
     * @param array $inputData
     * @return array
     */
    public function saveBatchAssesmentDetailData1($inputData)
    {
        return UserAssesmentDetail::batchSaveData($inputData);
    }



    /**
     * Store Asssesment detail
     *
     * @param array $inputData
     * @return array
     */
    public function saveBatchAssesmentDetailData($inputData)
    {
        return UserAssesmentDetail::batchSaveData($inputData);
    }
    
    /**
     * Store Asssesment 
     *
     * @param array $inputData
     * * @param int $id
     * @return array
     */
    public function saveAssesmentData($inputData,$id)
    {
        return UserAssesment::storeData($inputData,$id);
    }


    /**
     * Update Asssesment Rank
     *
     * @param array $inputData
     * @return array
     */
    public function updateRiskAssesmentRank($array, $user_kyc_id)
    {
        return UserAssesment::updateRiskAssesmentRank($array, $user_kyc_id);
    }

    /**
     * Update Asssesment Rank by Id
     *
     * @param array $inputData
     * @return array
     */
    public function updateRiskAssesmentRankbyID($array, $user_kyc_id)
    {
        return UserAssesment::updateRiskAssesmentRankbyID($array, $user_kyc_id);
    }
    
    ///
    /**
     * Store Asssesment detail
     *
     * @param array $inputData
     * @return array
     */
    public function getRiskAssesmentRating($user_kyc_id)
    {
        //echo "==>".$user_kyc_id; exit;
        return UserAssesmentDetail::getData($user_kyc_id);
    }
    
    /**
     * Delete Asssesment detail
     *
     * @param array $inputData
     * @return array
     */
    public function deleteRiskAssesmentDetail($user_kyc_id)
    {
        return UserAssesmentDetail::deleteData($user_kyc_id);
    }


    /**
     * Update Asssesment detail
     *
     * @param array $inputData
     * @return array
     */
    public function updateRiskAssesmentDetail($array, $user_kyc_id)
    {
        return UserAssesmentDetail::updateRiskAssesmentData($array, $user_kyc_id);
    }
    
    /**
     * Store Asssesment 
     *
     * @param array $inputData
     * * @param int $id
     * @return array
     */
    public function getAssesmentData($user_kyc_id, $isActive)
    {
        return UserAssesment::getData($user_kyc_id, $isActive);
    }
    //

    

    /**
     * update Asssesment detail
     *
     * @param array $inputData
     * @return array
     */
    public function updateData($inputData, $user_kyc_id, $assesment_id)
    {
        return UserAssesmentDetail::updateData($inputData, $user_kyc_id, $assesment_id);
    }
    
   // 
    
    /**
     * Update method
     *
     * @param array $attributes
     */
    public function updateSystemGeneratedPass(array $attributes, $email)
    {
        $result = UserModel::updateSystemGeneratedPass($attributes,$email);

        return $result ?: false;
    }
    
    /**
     * Update method
     *
     * @param array $attributes
     */
    public function kycApproveStatus($userKycId)
    {
       $kycApproveStatus = Userkyc::kycApproveStatus($userKycId);

        return $kycApproveStatus ?: false;
    }
    
     /**
     * Get Kyc Final Status 
     *
     * @param array $userKycId
     */
    public function kycApproveFinalStatus($userKycId)
    {
       $kycApproveStatus = Userkyc::kycApproveFinalStatus($userKycId);

        return $kycApproveStatus ?: false;
    }


    

    //

    /**
     * document Being Expire
     *
     * @param 
     * @param integer $user_kyc_id
     * @return array
     */
    public function documentBeingExpire($arrDays,$arrDocNo){
        
        
        return Userdocumenttype::passportBeingExpire($arrDays,$arrDocNo);
        
        
       /* if($doc_no==1){ // Utility Bill
            
        }
        else if($doc_no==2){ // Passport 
            return Userdocumenttype::passportBeingExpire($arrDays,$doc_no);
        }else if($doc_no==3){// For Company Trade Lince
           // return Userdocumenttype::passportBeingExpire($arrDays);
        }*/
        
    }
    
    
    //******* kyc OTP Methods
    
    /**
     * Validating and parsing data passed thos this method
     *
     * @param array $attributes
     * @param mixed $user_id
     *
     * @return New record ID that was added
     *
     * @since 0.1
     */
    public function saveKycOtp($attributes)
    {
        /**
         * Check Data is Array
         */
        if (!is_array($attributes)) {
            throw new InvalidDataTypeExceptions('Please send an array');
        }

        /**
         * Check Data is not blank
         */
        if (empty($attributes)) {
            throw new BlankDataExceptions('No Data Found');
        }
        return KycOtp::saveOtp($attributes);
    }


 
    /**
     * Validating and parsing data passed thos this method
     *
     * @param array $attributes
     * @param mixed $user_id
     *
     * @return New record ID that was added
     *
     * @since 0.1
     */
    public function updateKycOtp($attributes, $id)
    {
        /**
         * Check Data is Array
         */
        if (!is_array($attributes)) {
            throw new InvalidDataTypeExceptions('Please send an array');
        }

        /**
         * Check Data is not blank
         */
        if (empty($attributes)) {
            throw new BlankDataExceptions('No Data Found');
        }
        return KycOtp::updateOtp($attributes, $id);
    }

     /**
     * Validating and parsing data passed thos this method
     *
     * @param array $attributes
     * @param mixed $user_id
     *
     * @return New record ID that was added
     *
     * @since 0.1
     */
    public function updateKycOtpByExpiry($attributes, $id)
    {
        /**
         * Check Data is Array
         */
        if (!is_array($attributes)) {
            throw new InvalidDataTypeExceptions('Please send an array');
        }

        /**
         * Check Data is not blank
         */
        if (empty($attributes)) {
            throw new BlankDataExceptions('No Data Found');
        }
        return KycOtp::updateOtpByExpiry($attributes, $id);
    }

/**
     * Get a user model by otp
     *
     * @param string $opt
     *
     * @return boolean
     *
     * @since 0.1
     */
    public function getKycOtps($otp)
    {
       // $result = Otp::where('otp_no', $otp)->first();

        //return $result ?: false;

         return KycOtp::getOtps($otp);
    }
    
    public function selectKycOtpbyLastExpired($user_kyc_id)
    {
         return KycOtp::selectOtpbyLastExpired($user_kyc_id);
    }

    public function selectKycOtpbyLastExpiredByThirty($user_kyc_id)
    {

         return KycOtp::selectOtpbyLastExpiredByThirty($user_kyc_id);
    }


   public function getKycOtpsbyActive($otp)
    {
       // $result = Otp::where('otp_no', $otp)->first();

        //return $result ?: false;

         return KycOtp::getOtpsbyActive($otp);
    }
    

    /**
     * Get a user model by otp
     *
     * @param string $opt
     *
     * @return boolean
     *
     * @since 0.1
     */
    public function getUserByKycOtp($otp)
    {      

         return KycOtp::getUserByOPT($otp);
    }
    
       
    /**
     * Validating and parsing data passed thos this method
     *
     * @param array $attributes
     * @param mixed $user_id
     *
     * @return New record ID that was added
     *
     * @since 0.1
     */
    public function saveReminder($attributes)
    {
        /**
         * Check Data is Array
         */
        if (!is_array($attributes)) {
            throw new InvalidDataTypeExceptions(trans('error_message.send_array'));
        }

        /**
         * Check Data is not blank
         */
        if (empty($attributes)) {
             throw new BlankDataExceptions(trans('error_message.data_not_found'));
        }
        return ProfileMOnitoringReminder::saveReminder($attributes);
    }


 
    /**
     * Validating and parsing data passed thos this method
     *
     * @param array $attributes
     * @param mixed $user_id
     *
     * @return New record ID that was added
     *
     * @since 0.1
     */
    public function updateReminder($attributes, $id)
    {
      
        /**
         * Check id is integer
         */
        if (!is_int($id)) {
            throw new InvalidDataTypeExceptions(trans('error_message.invalid_data_type'));
        }
        /**
         * Check Data is Array
         */
        if (!is_array($attributes)) {
            throw new InvalidDataTypeExceptions(trans('error_message.send_array'));
        }

        /**
         * Check Data is not blank
         */
        if (empty($attributes)) {
             throw new BlankDataExceptions(trans('error_message.data_not_found'));
        }
        return ProfileMOnitoringReminder::updateReminder($attributes, $id);
    }
    
    /**
     * get User Status Summary
     *
     * @param array $attributes
     */
    public function getUserStatusSummary()
    {
       $userStatusSummary = Userkyc::getUserStatusSummary();

        return $userStatusSummary ?: false;
    }
    
    /**
     * Get a user model by id
     *
     * @param integer $userId
     *
     * @return boolean
     *
     * @since 0.1
     */
    public function isCorpIndvsKycCompleted($user_id)
    {
        $count = Userkyc::isCorpIndvsKycCompleted($user_id);

        return $count ?: 0;
    }

    
    /**
     * get Other Docs
     *
     * @param  integer $doc_for
     * @return mixed Array | Boolean false
     * @throws InvalidDataTypeExceptions
    */
    
    public function getOtherDocs($doc_for,$ids=array())
    {
        $result = DocumentMaster::getOtherDocs($doc_for,$ids);

        return $result ?: false;
    }
    

    


   /* role list functions start */

 /**
     * Get a backend user by id
     *
     * @param integer $user_id
     *
     * @return boolean
     *
     * @since 0.1
     */
    public function getRoleByArray($arr)
    {
        $user = Role::getRoleByArray($arr);
        return $user;
    }
  /**
     *
     *
     * @param integer $user_id
     *
     * @return boolean
     *
     * @since 0.1
     */
    public function getRoleList()
    {
        $role = Role::getRoleLists();
        return $role;
    }

      /**
     * add role
     *
     * @param integer $user_id
     *
     * @return boolean
     *
     * @since 0.1
     */
    public function addRole($roleData, $role_id)
    {
        $role = Role::addRole($roleData, $role_id);
        return $role;
    }


      /**
     * Get a role  by id
     *
     * @param integer $user_id
     *
     * @return boolean
     *
     * @since 0.1
     */
    public function getRole($role_id)
    {
        $role = Role::getRole($role_id);
        return $role;
    }

 /**
     * Give permission to role
     *
     * @param array $attributes
     *
     * @return object
     */
    public function givePermissionTo($roleid, $permission)
    {

        $role   = Role::where('id', $roleid)->first();
        $result = $role->assignRolePermission($permission);

        return $result ? : false;
    }



     /**
     * Get a all permition list
     *
     * @param integer $user_id
     *
     * @return boolean
     *
     * @since 0.1
     */
    public function getRoute()
    {
        $role = PermissionModel::getRoute();
        return $role;
    }


      /**
     * Get a all parent Route list
     *
     * @param integer $user_id
     *
     * @return boolean
     *
     * @since 0.1
     */
    public function getParaentRoute()
    {
        $role = PermissionModel::getParentRoute();
        return $role;
    }

    /**
     * Get all children of permmision
     *
     * @param type $permission_idgetChildByPermissionId
     *
     * @return permissions object
     */
    public function getChildByPermissionId($permission_id)
    {
        return PermissionModel::getChildByPermissionId($permission_id);
    }

    /**
     * Get user role
     *
     * @return mixed
     */
    public function getAllData()
    {
        return RoleUser::getAllData();
    }


     /**
     * set user role
     *
     * @return mixed
     */
    public function addNewRoleUser($roleData)
    {
        return RoleUser::addNewRoleUser($roleData);
    }
   /**
     * Get Backend User
     *
     *
     *
     * @since 0.1
     */
    public static function getRoleDataById($user_id)
    {
       return RoleUser::getRoleDataById($user_id);
    }
/**
     * Get Backend User
     *
     *
     *
     * @since 0.1
     */
    public static function updateUserRole($userId, $role)
    {
       return RoleUser::updateUserRole($userId, $role);
    }


  /* role list functions end */

    /*
     * Save or update Document
     * @param $postArr, $id
     * return int
     */
    
    public static function saveOrEditDocument($postArr, $id) 
    {
        
        
        $res = DocumentMaster::saveOrEditDocument($postArr,$id);

        return $res ?: false;
    }

    public static function updateActiveInactive($postArr, $id) 
    {
        $res = DocumentMaster::updateActiveInactive($postArr,$id);

        return $res ?: false;
    }
    
    //
    
    /*
     * Save or update Document
     * @param $postArr, $id
     * return int
     */
    
    public static function getDocumentData($id) 
    {
        
        
        $res = DocumentMaster::getdata($id);

        return $res ?: false;
    }
    
    /*
     * getAllOtherDocsPaginate
     * @param array $filter
     * return array
     */
    
    public static function getAllOtherDocsPaginate($filter){
       return DocumentMaster::getAllOtherDocsPaginate($filter);
    } 




    public static function saveapiUser($data){
       return ApiUserModel::saveapiUser($data);
    } 
    

    /*
     * update passport detail
     * @param array $input
     * return array
     */
    
    public static function updatePassport($filter){
       return DocumentMaster::updatePassport($filter);
    }
    
    /*
     * update passport doc
     * @param array $input
     * return array
     */
    
    public static function updatePassportDoc($inputData,$doc_id,$user_kyc_id){
       return Document::updateByKycIdDocId($inputData,$doc_id,$user_kyc_id);
    }
    
    //
    
    //storeData
    /*
     * update passport doc
     * @param array $input
     * return array
     */
    
    public static function saveIndvDocBatch($data){
       return Document::saveBatchData($data);
    }
    
    //
    
     /*
     * update passport doc
     * @param array $input
     * return array
     */
    
    public static function getMonitorDocData($id){
       return Userdocumenttype::getMonitorDocData($id);
    }
    
    /*
     * update Doc Status
     * @param array $input
     * return array
     */
    
    public static function updateDocStatus($data,$id){
       return Userdocumenttype::storeData($data,$id);
    }
    

     public static function apiLogin($username, $password){
       return ApiUserModel::apiLogin($username, $password);
    }

    /**
     * Store Compline Report
     *
     * @param array $inputData
     * @return array
     */
    public function savecomplinces($inputData)
    {
        return Compliancereport::savecomplinces($inputData);
    }

    /**
     * Store Compline Report log
     *
     * @param array $inputData
     * @return array
     */
    public function savecomplinceslog($inputData)
    {
        return Compliancereportlog::savecomplinceslog($inputData);
    }


     /**
     * Store Compline Report for
     *
     * @param array $inputData
     * @return array
     */
    public function savecomplincesfor($inputData)
    {
        return Compliancereportfor::savecomplincesfor($inputData);
    }


    /**
     * Store Compline Report trail
     *
     * @param array $inputData
     * @return array
     */
    public function savecomplincestrail($inputData)
    {
        return Compliancereporttrail::savecomplincestrail($inputData);
    }


     /**
     * get Compline Report by id
     *
     * @param array $inputData
     * @return array
     */
    public function complianceReport($kycId)
    {
        return Compliancereport::getcomplinces($kycId);
    }


    /**
     * get document by doc number
     *
     * @param array $doc_no
     *
     * @return object
     */

     public static function getMonitorMstDocByDocNo($arrDoc_no){
        
        return DocumentMaster::getMonitorMstDocByDocNo($arrDoc_no);
     }
     
     /**
     * get document type by doc number
     *
     * @param array $doc_no
     *
     * @return object
     */


    public function saveLogs($inputData)
    {
        return Apilog::saveLogs($inputData);
    }

    public function checkUserNameExit($userName) 
    {
        return UserModel::where('username', $userName)->first();
    }
    public function checkUserRoleExit($userRole) 
    {
        return Role::where('name', $userRole)->first();
    }



     public static function getMonitorDocByDocNo($arrDoc_no,$user_kyc_id){
        
        return Userdocumenttype::getMonitorDocByDocNo($arrDoc_no,$user_kyc_id);
     }
     
     //

      /**
     * Get a user model by id
     *
     * @param integer $userId
     *
     * @return boolean
     *
     * @since 0.1
     */
    public function getUserType($userId)
    {
        $result = UserModel::getUserType((int) $userId);

        return $result ?: false;
    }


    ///////////////

    /**
     * Get a user model by trading user id
     *
     * @param integer $userId
     *
     * @return boolean
     *
     * @since 0.1
     */
    public function getUserPrimaryId($userId)
    {
        $result = UserModel::getUserPrimaryId((int) $userId);

        return $result ?: false;
    }
     /**
     * Get a user model by email
     *
     * @param $email
     *
     * @return boolean
     *
     * @since 0.1
     */
    public function getUserByEmailforAPI($email)
    {
        $result = UserModel::getUserByEmailforAPI($email);

        return $result ?: false;
    }



    public function userAccountLockUnlock($userId,$block_Id)
    {
        $result = UserModel::userAccountLockUnlock((int) $userId, $block_Id);

        return $result ?: false;
    }

    public function getTradingkycCompletedApproved()
    {
        $result = UserModel::getTradingkycCompletedApproved();

        return $result ?: false;
    }
    
    public function updatecomplincesById($id, $arrData)
    {
        $result = Compliancereport::updatecomplincesById($id, $arrData);

        return $result ?: false;
    }




   

}