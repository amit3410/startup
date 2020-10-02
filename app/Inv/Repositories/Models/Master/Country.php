<?php

namespace App\Inv\Repositories\Models\Master;

use Carbon\Carbon;
use App\Inv\Repositories\Factory\Models\BaseModel;
use App\Inv\Repositories\Entities\User\Exceptions\BlankDataExceptions;
use App\Inv\Repositories\Entities\User\Exceptions\InvalidDataTypeExceptions;

class Country extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'mst_country';

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
        'country_code',
        'country_code_alfa3',
        'country_name',
        'is_active',
        'phonecode',
        'is_active'
    ];

    /**
     * Scopes for active list
     *
     * @param string $query
     * @param string $type
     * @return type
     */
    public function scopeActive($query, $type)
    {
        return $query->where('is_active', $type);
    }

    /**
     * Get Drop down list
     *
     * @return type
     */
    public static function getDropDown()
    {  
        return self::where('is_active',1)->pluck('country_name', 'id');
    }
     public static function getCountryCodeDropDown()
    {  

        return self::active(config('inv_common.ACTIVE'))->orderBy('country_name','ASC')->get();
    }
    
    /**
     * Get all country
     *
     * @return type
     */
    public static function getCountryList()
    {   
        $countries = self::select('id','country_code','country_name','is_active')
         ->whereNull('deleted_at');
        return $countries ? : false;
    }
    /*
     * get Country data by id
     * @param $id
     */
    public static function getCountryById($id)
    {
        //Check id is not blank
        if (empty($id)) {
            throw new BlankDataExceptions(trans('error_message.no_data_found'));
        }
        //Check id is not an integer

        if (!is_int($id)) {
            throw new InvalidDataTypeExceptions(trans('error_message.invalid_data_type'));
        }
        $data = self::where('id', $id)->first();
        return ($data ? $data : false);
    }
    
    /*
     * Save or update country
     * @param $postArr, $id
     * return int
     */
    
    public static function saveOrEditCountry($postArr, $id = null) 
    {
        
        if (!is_int($id)) {
            throw new InvalidDataTypeExceptions(trans('error_message.invalid_data_type'));
        }
        $res = self::updateOrCreate(['id' => $id], $postArr);

        return $res->id ?: false;
    }
    
    /**
     * Delete Country
     * @param array $cid
     * @return integer $res
     */
    public static function deleteCountry($cid)
    {   
        $res = self::whereIn('id', $cid)->update(['deleted_at' => Carbon::now()]);
        return $res ? $res : false;
    }


    /*
     * get Country Alfa 3 code by id
     * @param $id
     */
    public static function getCountryCodeById($id)
    {

        $data = self::select('country_code_alfa3')->where('id', $id)->first();
        return ($data ? trim($data->country_code_alfa3) : false);
    }

}