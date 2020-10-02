<?php

namespace App\Inv\Repositories\Models\Master;

use App\Inv\Repositories\Factory\Models\BaseModel;
use App\Inv\Repositories\Entities\User\Exceptions\BlankDataExceptions;
use App\Inv\Repositories\Entities\User\Exceptions\InvalidDataTypeExceptions;

class State extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'mst_state';

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
        'country_id',
        'name',
        'state_key',
        'is_active',
        'is_active'
    ];


    
    /**
     * Get all State
     *
     * @return type array
     */
    public static function getStateList()
    {
        $state = self::select('mst_state.*','mst_country.country_name')
                ->join('mst_country', 'mst_state.country_id', '=', 'mst_country.id')
                ->where('mst_state.is_active','!=',config('inv_common.IS_DELETED'));
        return $state ? : false;
    }
    /*
     * get State data by id
     * @param $id
     */
    public static function getStateById($id)
    {
        $data = self::select('*')
                ->where('id', $id)
                ->first();
        return ($data ? $data : false);
    }
    
    /*
     * Save or update state
     * @param $postArr, $id
     * return int
     */
    
    public static function saveOrEditState($postArr, $id = null) 
    {
        
        if (!is_int($id)) {
            throw new InvalidDataTypeExceptions(trans('error_message.invalid_data_type'));
        }
        $res = self::updateOrCreate(['id' => $id], $postArr);

        return $res->id ?: false;
    }
    
    /**
     * Delete State
     * @param array $cid
     * @return integer $res
     */
    public static function deleteState($state_id)
    {     
        $res = self::whereIn('id', $state_id)->update(['is_active' => config('inv_common.IS_DELETED')]);
        return $res ? $res : false;
    }

}