<?php

namespace App\Inv\Repositories\Models;

use DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Inv\Repositories\Entities\User\Exceptions\BlankDataExceptions;
use App\Inv\Repositories\Entities\User\Exceptions\InvalidDataTypeExceptions;

class Userdetail extends Authenticatable
{

    use Notifiable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_detail';

    /**
     * Custom primary key is set for the table
     *
     * @var integer
     */
    protected $primaryKey = 'user_detail_id';

    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'country_id',
        'date_of_birth',
        'access_token',
        'created_by',
        'created_at',
        'updated_at',
        'updated_by'
        
    ];

    
 /**
     * Save User Detail
     *
     * @param  array $arrUsers
     *
     * @throws InvalidDataTypeExceptions
     * @throws BlankDataExceptions
     */
    public static function saveUserDetails($arrUsers = [])
    {

        //Check data is Array
        if (!is_array($arrUsers)) {
            throw new InvalidDataTypeExceptions(trans('error_message.send_array'));
        }

        //Check data is not blank
        if (empty($arrUsers)) {
            throw new BlankDataExceptions(trans('error_message.data_not_found'));
        }
        /**
         * Create User Detail
         */
        $objUsers = self::create($arrUsers);

        return ($objUsers ?: false);
    }
    /**
     * update user details
     *
     * @param integer $user_id     user id
     * @param array   $arrUserData user data
     *
     * @return boolean
     */
    public static function updateUser($user_id, $arrUserData = [])
    {
        /**
         * Check id is not blank
         */
        if (empty($user_id)) {
            throw new BlankDataExceptions(trans('error_message.no_data_found'));
        }

        /**
         * Check id is not an integer
         */
        if (!is_int($user_id)) {
            throw new InvalidDataTypeExceptions(trans('error_message.invalid_data_type'));
        }

        /**
         * Check Data is Array
         */
        if (!is_array($arrUserData)) {
            throw new InvalidDataTypeExceptions(trans('error_message.send_array'));
        }

        /**
         * Check Data is not blank
         */
        if (empty($arrUserData)) {
            throw new BlankDataExceptions(trans('error_message.no_data_found'));
        }

        $rowUpdate = self::find((int) $user_id)->update($arrUserData);

        return ($rowUpdate ? $user_id : false);
    }

    

   

    /**
     * Get all users
     *
     * @return array
     * @throws BlankDataExceptions
     * @throws InvalidDataTypeExceptions
     * Since 0.1
     */
    public static function getAllUsers()
    {
        $result = self::select('users.*')
            ->where('user_type', 1);
        return ($result ? $result : '');
    }

    /**
     * update backend user details
     *
     * @param integer $user_id     user id
     * @param array   $arrUserData user data
     *
     * @return boolean
     */
    public static function updateBackendUser($user_id, $arrUserData = [])
    {
        /**
         * Check id is not blank
         */
        if (empty($user_id)) {
            throw new BlankDataExceptions(trans('error_message.no_data_found'));
        }

        /**
         * Check id is not an integer
         */
        if (!is_int($user_id)) {
            throw new InvalidDataTypeExceptions(trans('error_message.invalid_data_type'));
        }

        /**
         * Check Data is Array
         */
        if (!is_array($arrUserData)) {
            throw new InvalidDataTypeExceptions(trans('error_message.send_array'));
        }

        /**
         * Check Data is not blank
         */
        if (empty($arrUserData)) {
            throw new BlankDataExceptions(trans('error_message.no_data_found'));
        }

        $rowUpdate = self::find((int) $user_id)->update($arrUserData);
        return ($rowUpdate ? true : false);
    }

   
   public static function getIndvDetail($user_id)
    {
        //dd($user_id);
        //Check id is not blank

        if (empty($user_id)) {
            throw new BlankDataExceptions(trans('error_message.no_data_found'));
        }
        //Check id is not an integer

        if (!is_int($user_id)) {
            throw new InvalidDataTypeExceptions(trans('error_message.invalid_data_type'));
        }

        $arrUser = self::select('user_detail.*','users.f_name','users.m_name','users.l_name','users.email','users.user_type','users.country_code','users.phone_no')->join('users','users.user_id','=','user_detail.user_id')
            ->where('user_detail.user_id', (int) $user_id)
            ->first();

        return ($arrUser ?: false);
    }

}