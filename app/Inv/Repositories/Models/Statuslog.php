<?php

namespace App\Inv\Repositories\Models;

use Carbon\Carbon;
use DB;
use App\Inv\Repositories\Factory\Models\BaseModel;
use App\Inv\Repositories\Entities\Application\Exceptions\BlankDataExceptions;
use App\Inv\Repositories\Entities\Application\Exceptions\InvalidDataTypeExceptions;

class Statuslog extends BaseModel
{
    /* The database table used by the model.
     *
     * @var string
     */
    protected $table = 'wc_status_update';

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
    // Column for soft deletes
   

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kyc_id',
        'status_id',
        'created_by',
        'created_at',
        'updated_at',
        'updated_by'
        
    ];

    /**
     * Save Status log
     * 
     * @param Array $arrData
     * @return Integer
     * @throws InvalidDataTypeExceptions
     * @throws BlankDataExceptions
     */
    public static function createstatus($arrData = [])
    {

        //Check Data is Array
        if (!is_array($arrData)) {
            throw new InvalidDataTypeExceptions(trans('error_message.send_array'));
        }

        //Check Data is not blank
        if (empty($arrData)) {
            throw new BlankDataExceptions(trans('error_message.no_data_found'));
        }
        /**
         * Create application
         */
        $arrData['created_by'] = \Auth::user()->user_id;
        $arrData['updated_by'] = \Auth::user()->user_id;
        $objStatus = self::create($arrData);

        return ($objStatus->id ?: false);
    }

}