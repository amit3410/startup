<?php

namespace App\Inv\Repositories\Models;

use DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Inv\Repositories\Entities\User\Exceptions\BlankDataExceptions;
use App\Inv\Repositories\Entities\User\Exceptions\InvalidDataTypeExceptions;

class Compliancereportfor extends Authenticatable
{

    use Notifiable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'compliance_report_for';

    /**
     * Custom primary key is set for the table
     *
     * @var integer
     */
    protected $primaryKey = 'report_for_id';

    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kyc_id',
        'report_for',
        'created_by',
        'created_at',
        'updated_at',
        'updated_by'
        
    ];

    
 /**
     * Save User complince Report log
     *
     * @param  array $arrUsers
     *
     * @throws InvalidDataTypeExceptions
     * @throws BlankDataExceptions
     */
    public static function savecomplincesfor($arrComplince = [])
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
    
    public static function getcomplincesfor($kycId)
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
     * Update User complinces log
     *
     * @param  array $arrUsers
     *
     * @throws InvalidDataTypeExceptions
     * @throws BlankDataExceptions
     */

     public static function updatecomplincesfor($kyc_id, $arrData = [])
    {

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


}