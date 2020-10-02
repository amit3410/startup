<?php

namespace App\Inv\Repositories\Models;

use Illuminate\Database\Eloquent\Model;

class UserKycProcessStatus extends Model
{
    /* The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_kyc_process_status';

    /**
     * Custom primary key is set for the table
     *
     * @var integer
     */
    protected $primaryKey = 'id';

    /**
     * Maintain created_at and updated_at automatically
     *
     * @var boolean
     */
    public $timestamps = true;

    /**
     * Maintain created_by and updated_by automatically
     *
     * @var boolean
     */
    public $userstamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_kyc_id',
        'form_id',
        'kyc_type',
        'is_required',
        'status',
        'created_at',
        'updated_at'
    ];

    /**
     * storeData
     * @param
     * @return array
     * @since 0.1
     * @author Arvind soni
     */
    public static function storeData($inputData,$id='') {
        try {
            $data = self::updateOrCreate(['id' => (int) $id],$inputData);
            return ($data ?: false);
        } catch (\Exception $e) {
            $data['errors'] = $e->getMessage();
        }
    }
    
    /**
     * storeData
     * @param
     * @return array
     * @since 0.1
     * @author Arvind soni
     */
    public static function updateStatus($attributes,$user_kyc_id,$form_id) {
        try {
            $result= self::where('user_kyc_id', $user_kyc_id)->where('form_id', $form_id)->update($attributes);
            return ($result?:false);
        } catch (\Exception $e) {
            $data['errors'] = $e->getMessage();
        }
    }
    
    
    /**
     * storeData
     * @param
     * @return array
     * @since 0.1
     * @author Arvind soni
     */
    public static function saveBatchData($inputData) {
        try {
            $data = self::insert($inputData);
            return ($data ?: false);
        } catch (\Exception $e) {
            $data['errors'] = $e->getMessage();
        }
    }
    
    
     /*
     * Get KYC Process Form Status Info
     * @param int user_id
     * @return array
     *@author  arvind soni
    */
    public static function getData($user_kyc_id=''){
        $result=self::where('user_kyc_id','=',$user_kyc_id)->first();
        return ($result?:false);
    }
    
    
     /*
     * Get KYC Process Form Status Info
     * @param int user_id
     * @return array
     *@author  arvind soni
    */
    public static function checkKycProcessComplete($user_kyc_id=''){
        $result=self::where('user_kyc_id','=',$user_kyc_id)->where('is_required','=','1')->where('status','=','0')->get();
        return ($result?:false);
    }
   
}
