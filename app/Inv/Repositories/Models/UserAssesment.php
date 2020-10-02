<?php

namespace App\Inv\Repositories\Models;

use DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Inv\Repositories\Entities\User\Exceptions\BlankDataExceptions;
use App\Inv\Repositories\Entities\User\Exceptions\InvalidDataTypeExceptions;

class UserAssesment extends Authenticatable
{

    use Notifiable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_assesment_rank';

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
        'avg_rank',
        'org_avg_rank',
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
            return ($data ? $data : false);
        } catch (\Exception $e) {
            //echo $e; exit;
            $data['errors'] = $e->getMessage();
        }
    }
    
    /*
     * Get Assement DATA
     * @param int user_kyc_id
     * @return array
     *@author  arvind soni
    */
    public static function getData($user_kyc_id='', $isActive){
        try {
            $result=self::where('user_kyc_id','=',$user_kyc_id)
                ->where('is_active',$isActive)
                ->orderBy('id', 'DESC')
                ->get();
            return ($result?:false);
        } catch (\Exception $e) {
            echo $e; exit;
            $data['errors'] = $e->getMessage();
        }
    }



     /**
     * updateData
     * @param
     * @return array
     * @since 0.1
     * @author Amit kumar Suman
    */
    public static function updateRiskAssesmentRank($inputData,$user_kyc_id){
        try {
            $data = DB::table('user_assesment_rank')
                ->where('user_kyc_id', '=', $user_kyc_id)
                ->update($inputData);
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
     * @author Amit kumar Suman
    */
    public static function updateRiskAssesmentRankbyID($inputData,$id){
        try {
            $data = DB::table('user_assesment_rank')
                ->where('id', '=', $id)
                ->update($inputData);
            return ($data ?: false);
        } catch (\Exception $e) {
            //echo $e; exit;
            $data['errors'] = $e->getMessage();
        }
    }
}