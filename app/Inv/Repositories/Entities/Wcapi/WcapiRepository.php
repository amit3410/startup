<?php

namespace App\Inv\Repositories\Entities\Wcapi;

use App\Inv\Repositories\Models\User;
use App\Inv\Repositories\Models\Wcapi;
use App\Inv\Repositories\Models\Master\Countrytype;

use DB;
use App\Inv\Repositories\Contracts\ApplicationInterface;
use App\Inv\Repositories\Factory\Repositories\BaseRepositories;
use App\Inv\Repositories\Contracts\Traits\CommonRepositoryTraits;
use App\Inv\Repositories\Contracts\WcapiInterface;
use Session;




/**
 * Application repository class for right
 */
class WcapiRepository extends BaseRepositories implements WcapiInterface {

    use CommonRepositoryTraits;

    /**
     * Class constructor
     *
     * @return void
     */
    protected $CompanyProfile;
    protected $CompanyAddress;
    public function __construct() {
      

    }



     /**
     * Create method
     *
     * @param array $attributes
     */
    protected function create(array $attributes)
    {

       
    }

    /**
     * Find method
     *
     * @param mixed $id
     * @param array $columns
     */
    public function find($id, $columns = array('*'))
    {

       
    }

    /**
     * Update method
     *
     * @param array $attributes
     */
    protected function update(array $attributes, $id)
    {
       
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
     * Create method
     *
     * @param array $attributes
     */
    public function saveWcapiCall(array $attributes) {
       // echo "debug"; exit;

        return Wcapi::saveWcapiCall($attributes);
    }

  

   public function getWcapiDetail($userKycId, $requestFor) {
       // echo "debug"; exit;

        return Wcapi::getWcapiDetail($userKycId, $requestFor);
    }

    public function getWcapiPersonalDetail($userKycId, $requestFor) {
        return Wcapi::getWcapiPersonalDetail($userKycId, $requestFor);
    }

    public function getWcapiPersonalDetailFinal($userKycId, $requestFor) {
        return Wcapi::getWcapiPersonalDetailFinal($userKycId, $requestFor);
    }
    
    public function getCountryTypeByName($countryName) {
       // echo "debug"; exit;

        return Countrytype::getCountryTypeByName($countryName);
    }

    public function updateUserKycWC($userKycId, $profileId, $responceProfileId, $dataUpadate, $updatefor) {
       // echo "debug"; exit;

        return Wcapi::updateUserKycWC($userKycId, $profileId, $responceProfileId, $dataUpadate, $updatefor);
    }
    
    public function getkycData($Id) {
        return Wcapi::getkycData($Id);
    }

    public function updatebyId($Id, $dataUpadate) {
        return Wcapi::updatebyId($Id, $dataUpadate);
    }
    
    public function getKycDataByKycId($userKycId, $requestFor) {
        return Wcapi::getKycDataByKycId($userKycId, $requestFor);
    }

     public function getKycDatabyparentId($parent_id) {
        return Wcapi::getKycDatabyparentId($parent_id);
    }
     public function getdataByCaseid($kycId) {
        return Wcapi::getdataByCaseid($kycId);
    }



}
