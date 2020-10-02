<?php

namespace App\Inv\Repositories\Models;

use DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Inv\Repositories\Entities\User\Exceptions\BlankDataExceptions;
use App\Inv\Repositories\Entities\User\Exceptions\InvalidDataTypeExceptions;

class Compliancereport extends Authenticatable
{

    use Notifiable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'compliance_report';

    /**
     * Custom primary key is set for the table
     *
     * @var integer
     */
    protected $primaryKey = 'compliance_report_id';

    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'is_by_company',
        'kyc_id',
        'emirates_id_no',
        'emirates_exp_date',
        'residence_no',
        'residency_expiry_date',
        'iban',
        'rm_name',
        'rm_mobile',
        'tax_registration_no',
        'license',
        'tenancy_exp_date',
        'registration_tenancy',
        'website',
        'bank_name',
        'accouunt_number',
        'corr_accouunt_number',
        'passport_verify',
        'general_investigation',
        'references_investigation',
        'analysis_findings',
        'comment_compliance',
        'conclusion_recommendation',
        'is_report_genrated',
        'created_by',
        'created_at',
        'updated_at',
        'updated_by'
        
    ];

    
 /**
     * Save User complince Report
     *
     * @param  array $arrUsers
     *
     * @throws InvalidDataTypeExceptions
     * @throws BlankDataExceptions
     */
    public static function savecomplinces($arrComplince = [])
    {

        //Check data is Array
        if (!is_array($arrComplince)) {
            throw new InvalidDataTypeExceptions(trans('error_message.send_array'));
        }

        //Check data is not blank
        if (empty($arrComplince)) {
            throw new BlankDataExceptions(trans('error_message.data_not_found'));
        }
        /**
         * Create Complince Report
         */
        $objKyc = self::create($arrComplince);

        return ($objKyc ?: false);
    }
    
    public static function getcomplinces($kycId)
    {
        /**
         * Check user id is not an integer
         */
        if (!is_int((int) $kycId) && $kycId != 0) {
            throw new InvalidDataTypeExceptions(trans('error_message.invalid_data_type'));
        }

        $arr = self::where('kyc_id', (int) $kycId)
            ->orderBy('compliance_report_id', 'DESC')
            ->first();
        return ($arr ?: false);
    }
    

   


     /**
     * Update User complinces
     *
     * @param  array $arrUsers
     *
     * @throws InvalidDataTypeExceptions
     * @throws BlankDataExceptions
     */

    public static function updatecomplinces($kyc_id, $arrData = [])
    {
         //$user_kyc_id = 77;
        /**
         * Check id is not blank
         */
        if (empty($kyc_id)) {
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

    public static function updatecomplincesById($id, $arrData = [])
    {
        /**
         * Check id is not blank
         */
        if (empty($id)) {
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
        $res = self::where('compliance_report_id', (int)$id)->update($arrData);
        return $res ? $res : false;
    }


}