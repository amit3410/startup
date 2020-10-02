<?php

namespace App\Inv\Repositories\Models\Master;

use Carbon\Carbon;
use App\Inv\Repositories\Factory\Models\BaseModel;
use App\Inv\Repositories\Entities\User\Exceptions\BlankDataExceptions;
use App\Inv\Repositories\Entities\User\Exceptions\InvalidDataTypeExceptions;

class KycProcessForm extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'mst_user_kyc_process';

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
    public $userstamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = [
        'form_name',
        'kyc_type',
        'is_required',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
     
    /*
     * Get KYC Process Form Status Info
     * @param int user_id
     * @return array
     *@author  arvind soni
    */
    public static function getData($kyc_type=''){      
        try {
            $result=self::where('kyc_type','=',$kyc_type)->get();
            return ($result?:false);
        } catch (\Exception $e) {
            $data['errors'] = $e->getMessage();
        }
    } 
    
}