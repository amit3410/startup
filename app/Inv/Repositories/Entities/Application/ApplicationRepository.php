<?php

namespace App\Inv\Repositories\Entities\Application;
use DB;
use Session;
use App\Inv\Repositories\Models\User;
use App\Inv\Repositories\Models\Research;
use App\Inv\Repositories\Models\Userdetail;
use App\Inv\Repositories\Models\CompanyProfile;
use App\Inv\Repositories\Models\CompanyAddress;
use App\Inv\Repositories\Models\ShareHolding;
use App\Inv\Repositories\Models\FinancialInformation;
use App\Inv\Repositories\Models\Document;
use App\Inv\Repositories\Models\DocumentMaster;
use App\Inv\Repositories\Models\UserReqDoc;
use App\Inv\Repositories\Models\Userkyc;
use App\Inv\Repositories\Models\Corpdetail;
use App\Inv\Repositories\Models\Statuslog;
use App\Inv\Repositories\Models\WcapiResolution;
use App\Inv\Repositories\Models\Master\GroupId;
use App\Inv\Repositories\Models\Master\Toolkit;
use App\Inv\Repositories\Contracts\ApplicationInterface;
use App\Inv\Repositories\Factory\Repositories\BaseRepositories;
use App\Inv\Repositories\Contracts\Traits\CommonRepositoryTraits;

/**
 * Application repository class for right
 */
class ApplicationRepository extends BaseRepositories implements ApplicationInterface {

    use CommonRepositoryTraits;

    /**
     * Class constructor
     *
     * @return void
     */
    protected $CompanyProfile;
    protected $CompanyAddress;
    public function __construct(CompanyProfile $CompanyProfile,CompanyAddress $CompanyAddress,Document $document) {
        $this->Companyprofile=$CompanyProfile;
        $this->Companyaddress=$CompanyAddress;
        $this->document=$document;

    }

    /**
     * Create method
     *
     * @param array $attributes
     */
    protected function create(array $attributes) {
        return Rights::saveRights($attributes);
    }

    /**
     * Update method
     *
     * @param array $attributes
     */
    protected function update(array $attributes, $rightId) {
        return Rights::updateRights((int) $rightId, $attributes);
    }

   

    


    
    

   
    

   

   

    

    

    
     /*-------------company profile function------------*/

    public function getCompanyProfileData($userId)
    {

     return $this->Companyprofile->personalCompanyProfile($userId) ;
    }
    public function saveCompanyProfile($request, $userKycid)
    {

       
        $res=CompanyProfile::where('user_kyc_id', $userKycid)->first();
      
        $attributes=[
                    'user_kyc_id'=>$userKycid,
                    'user_id'=>Auth()->user()->user_id,
                    'company_name'=>$request->companyname,
                    'customer_name'=>$request->customername,
                    'registration_no'=>$request->regisno,
                    'registration_date'=>$request->regisdate,
                    'status'=>$request->status,
                    'status_other'=> ($request->status=='8')? $request->comp_status_other:'',

                    'business_nature'=>$request->naturebusiness,
               ];


               
        if($res){
           
            $result = CompanyProfile::where('user_kyc_id', $userKycid)->update($attributes);

            return $result ?: false;
        } else {
            return CompanyProfile::create($attributes);
        }

        

    }

    
    public function getCompanyAddress($userId)
    {
       return $this->Companyaddress->getCorpAddress($userId);
    }
    
    public function getOnlyCorpAddress($userKycId)
    {
       return $this->Companyaddress->getOnlyCorpAddress($userKycId);
    }



    public function saveCompanyAddress($request, $userId)
    {
    
        return $this->Companyaddress->createCompanyAddress($request,$userId) ;

    }

    
    public function saveShareHoldingForm($attributes,$id)
    {

        return ShareHolding::storeData($attributes,$id);

    }
    
    /*
     * get Last level share with share type comapny
     *       
    */
    
    public function getHigestLevelShareData($user_id,$share_type){
        return ShareHolding::getHigestLevelShareData($user_id,$share_type);
    }
    
    /*
     * get Beneficiary Owners
     * 
    */
    
    public function getBeneficiaryOwnersData($user_id){
        return ShareHolding::getBeneficiaryOwnersData($user_id);
    }

    /*
     * get Beneficiary Tree
     *
    */

    public function getBeneficiary($user_id){
        return ShareHolding::getBeneficiary($user_id);
    }
    
    //getShareHolderData
    
    /*
     * get Beneficiary Owners
     * 
    */
    
    public function getShareHolderData($user_id){
        return ShareHolding::getShareHolderData($user_id);
    }
    
    public function getShareHolderInfo($id){
        return ShareHolding::getData($id);
    }

    public function getCompanyFinancialData($userId)
    {
        return FinancialInformation::getFinancialData($userId);
    }
    public function saveFinancialInfoForm($request, $userKycid)
    {
        $attributes=[
            'user_kyc_id'=>$userKycid,
            'user_id'=>Auth()->user()->user_id,
            'total_debts_usd'=>$request->total_debts_usd,
            'total_cash'=>$request->total_cash_usd,
            'total_payable_usd'=>$request->total_payable_usd,
            'total_receivables_usd'=>$request->total_recei_usd,
            'total_salary_usd'=>$request->total_salary_usd,
            'yearly_usd'=>$request->yearly_usd,
            'yearly_profit_usd'=>$request->yearly_profit_usd,
            'capital_company_usd'=>$request->capital_company_usd,
         ];

         $res=FinancialInformation::where('user_kyc_id', $userKycid)->first();

        

        if($res){
           
            $result = FinancialInformation::where('user_kyc_id', $userKycid)->update($attributes);

            return $result ?: false;
        } else {
            return FinancialInformation::create($attributes);
        }
    
       

    }

   

    public function corporateDocument($userKycId)
    {
         //$userkyc=Userkyc::where('user_id',Auth()->user()->user_id)->first();

         $corpdata=UserReqDoc::where('user_kyc_id', $userKycId)->where('type',1)->get()->toArray();
        if(!empty($corpdata)){
             
             return   $corpdata;
        }

    }
    
    public function otherReqDocument($userKycId)
    {
         //$userkyc=Userkyc::where('user_id',Auth()->user()->user_id)->first();

         $corpdata=UserReqDoc::where('user_kyc_id', $userKycId)->where('type',2)->get()->toArray();
        if(!empty($corpdata)){
             
             return   $corpdata;
        }

    }

    public function getcorpDocument($userId)
    {
            return $data=DB::table('user_req_doc')
                ->where('user_id',$userId)
                ->where('is_upload',1)
                ->get();
    }


    public function getDocumentList($userId,$user_req_doc_id)
    {

           
        return $this->document->getDocumentList($userId,$user_req_doc_id);

    }


    public function getSingleDocument($documentHash)
    {
        return $this->document->getSingleDocument($documentHash);
    }

    public function getUserKycid($userId){
        $userkyc=Userkyc::where('user_id',$userId)->first();
        if($userkyc){
            return $userkyc->kyc_id? :false;
        }else{
            return false;
        }
    }

    public function getUserIdbyKycid($kycId){
        $userkyc=Userkyc::where('kyc_id',$kycId)->first();
        if($userkyc){
            return $userkyc->user_id? :false;
        }else{
            return false;
        }
    }

    public function getUserType($userId){
        

        return User::getUserType($userId);
       // $user=User::find($userId);
    }
     


    public function getRegisterDetails($userId)
    {

        $result=Corpdetail::where('user_id', (int)$userId)->first();

        return $result ? :false;
    }
    
    //Pop up function
     public function userCheck()
    {
        $userId=Auth()->user()->user_id;
        $result=User::where('user_id', (int)$userId)->first();

        return $result->is_first_time_popup ? :false;
    }

 
    public function userPopupUpdate()
    {

        $userId=(int)Auth()->user()->user_id;
        $popvalue='1';
        $user=User::find($userId);
        $user->is_first_time_popup = $popvalue;
        $result=$user->save();

        if($result){
            return $result ? :false;
        }

        
    }


    public function updateUserKyc($user_kyc_id, $dataKycUpdate)
    {

        

        return Userkyc::updateUserKyc($user_kyc_id, $dataKycUpdate);

    }

     public function updatesaddress($request, $user_kyc_id)
    {

        return $this->Companyaddress->updatesaddress($request,$user_kyc_id) ;

    }
    
    /**
     * check All Doc Uploaded
     *    
     */
    public function checkAllDocUploaded($user_kyc_id)
    {
        return UserReqDoc::checkAllDocUploaded($user_kyc_id);

    }
    
    
     /**
     * get Required Docs List
     *    
     */
    public function getRequiredDocsList($user_kyc_id)
    {
        return UserReqDoc::getRequiredDocsList($user_kyc_id);

    }
    
    /**
     * get Required Docs List
     *    
     */
    public function getRequiredOtherDocsList($user_kyc_id)
    {
        return UserReqDoc::getRequiredOtherDocsList($user_kyc_id);

    }
    
    //
    
     /**
     * get Required Docs List
     *    
     */
    public function saveOtherDocsReq($inputData)
    {
        return UserReqDoc::saveOtherDocsReq($inputData);

    }
    
    /**
     * get Required Docs List
     *    
     */
    public function getOtherDocsReq($user_kyc_id)
    {
        return UserReqDoc::getOtherDocsReq($user_kyc_id);

    }
    
    
    
    public function getallBeneficiaryOwnersData($user_id){
        return ShareHolding::getallBeneficiaryOwnersData($user_id);
    }

    public function getgroupID()
    {
        return GroupId::getgroupId();
    }
    
    public function updategroupID($groupArray)
    {
        return GroupId::updategroupID($groupArray);
    }
    
    public function saveWcapiResolution($dataArray)
    {
        return WcapiResolution::saveWcapiResolution($dataArray);
    }
    
    public function getResolutionData($wcId)
    {
        return WcapiResolution::getResolutionData($wcId);
    }

    public function getResolutionDatabyparentID($wcId)
    {
        return WcapiResolution::getResolutionDatabyparentID($wcId);
    }

    public function gettooklit()
    {
        return Toolkit::gettooklit();
    }
    
    public function deleteToolkit()
    {
        return Toolkit::deleteToolkit();
    }
    


    
    public function updatetoolkit($id, $toolkitArray)
    {
        return Toolkit::updatetoolkit($id, $toolkitArray);
    }
    
    public function toolketvaluebyId($id)
    {
        return Toolkit::toolketvaluebyId($id);
    }


    public function createstatus($dataArray)
    {
        return Statuslog::createstatus($dataArray);
    }

    public function getDatabycaseIDandresulid($caseid, $resultId)
    {
        return WcapiResolution::getDatabycaseIDandresulid($caseid, $resultId);
    }

    public function updateResolution($updateDataArray, $resolution_id)
    {
        return WcapiResolution::updateResolution($updateDataArray, $resolution_id);
    }

    

  

}
