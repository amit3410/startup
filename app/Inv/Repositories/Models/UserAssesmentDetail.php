<?php

namespace App\Inv\Repositories\Models;

use DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Inv\Repositories\Entities\User\Exceptions\BlankDataExceptions;
use App\Inv\Repositories\Entities\User\Exceptions\InvalidDataTypeExceptions;

class UserAssesmentDetail extends Authenticatable
{

    use Notifiable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_assesment_detail';

    /**
     * Custom primary key is set for the table
     *
     * @var integer
     */
    protected $primaryKey = 'id';

    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_kyc_id',
        'assesment_id',
        'rank',
        'option_key',
        'is_active',
        'created_by',
        'created_at',
        'updated_at',
        'updated_by'
    ];

    
 /**
     * storeData
     * @param
     * @return array
     * @since 0.1
     * @author Arvind soni
    */
    public static function storeData($inputData,$id=''){
        try {
            $data = self::updateOrCreate(['id' => (int) $id],$inputData);
            return ($data ?: false);
        } catch (\Exception $e) {
            //echo $e; exit;
            $data['errors'] = $e->getMessage();
        }
    }
    
    public static function batchSaveData($inputData){
        try {
      
            $data = self::insert($inputData);
            return ($data ?: false);
        } catch (\Exception $e) {
            //echo $e; exit;
            $data['errors'] = $e->getMessage();
        }
    }
    
    /*
     * Get Assement Rating data
     * @param int user_kyc_id
     * @return array
     *@author  arvind soni
    */
    public static function getData($user_kyc_id=''){        
        try {
      
            $result=self::where('user_kyc_id','=',$user_kyc_id)
                    ->where('is_active',1)->get();
            return ($result ? $result :false);
        } catch (\Exception $e) {
            echo $e; exit;
            $data['errors'] = $e->getMessage();
        }
    }
    
    /**
     * Delete Data
     *
     * @param  integer $userId
     * @return mixed Array | Boolean false
     * @throws InvalidDataTypeExceptions
     */
    public static function deleteData($user_kyc_id)
    {
        /**
         * Check user id is not an integer
         */
        if (!is_int((int) $user_kyc_id) && $user_kyc_id != 0) {
            throw new InvalidDataTypeExceptions(trans('error_message.invalid_data_type'));
        }

        $sklDelete = self::where('user_kyc_id', '=', $user_kyc_id)->delete();

        return ($sklDelete ?: false);
    }



    /**
     * updateData
     * @param
     * @return array
     * @since 0.1
     * @author Arvind soni
    */
    public static function updateData($inputData,$user_kyc_id, $assesment_id){
        try {

             //$rowUpdate = DB::table('user_rights_tmp')->where('id', '=', $rightId)->update($arrData);
             //return ($rowUpdate ? (int) $rightId : false);

            $data = DB::table('user_assesment_detail')->where('user_kyc_id', '=', $user_kyc_id)->where('assesment_id', '=', $assesment_id)->update($inputData);

                
            return ($data ?: false);
        } catch (\Exception $e) {
            //echo $e; exit;
            $data['errors'] = $e->getMessage();
        }
    }
    
    
     /**
     * updateData
     * @param
     * @return array
     * @since 0.1
     * @author Arvind soni
    */
    public static function updateRiskAssesmentData($inputData,$user_kyc_id){
        try {
            $data = DB::table('user_assesment_detail')
                ->where('user_kyc_id', '=', $user_kyc_id)
                ->update($inputData);
            return ($data ?: false);
        } catch (\Exception $e) {
            //echo $e; exit;
            $data['errors'] = $e->getMessage();
        }
    }


    
}