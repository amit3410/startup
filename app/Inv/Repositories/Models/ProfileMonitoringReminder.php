<?php

namespace App\Inv\Repositories\Models;

use Carbon\Carbon;
use DateTime;
use App\Inv\Repositories\Factory\Models\BaseModel;

class ProfileMOnitoringReminder extends BaseModel
{
    /* The database table used by the model.
     *
     * @var string
     */
    protected $table = 'profile_monitoring_reminder';

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
        'user_kyc_id',
        'user_id',
        'reminder_type',
        'reminder_for',
        'status',
        'message',
        'created_at',
        'updated_at',
    ];

    /**
     * Save Reminder
     *
     * @param  array $arrInput
     *
     * @throws InvalidDataTypeExceptions
     * @throws BlankDataExceptions
     */
    public static function saveReminder($arrInput = [])
    {
        //Check data is Array
        if (!is_array($arrInput)) {
            throw new InvalidDataTypeExceptions(trans('error_message.send_array'));
        }

        //Check data is not blank
        if (empty($arrInput)) {
            throw new BlankDataExceptions(trans('error_message.data_not_found'));
        }
        
        /**
         * Create Reminder
         */
        $result = self::create($arrInput);

        return ($result->id ?: false);
    }

  
    

    /*
     * update Reminder status
     * @param $arrInput, $id
     * return int
    */

    public static function updateReminder($arrInput, $id)
    {
        //Check data is Array
        if (!is_array($arrInput)) {
            throw new InvalidDataTypeExceptions(trans('error_message.send_array'));
        }
        
        //Check id is integer
        if (!is_int($id)) {
            throw new InvalidDataTypeExceptions(trans('error_message.invalid_data_type'));
        }
        $res = self::where('id', $id)->update($arrInput);
        return $res ?: false;
    }

}


