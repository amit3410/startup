<?php

namespace App\Inv\Repositories\Models;

use DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Inv\Repositories\Entities\User\Exceptions\BlankDataExceptions;
use App\Inv\Repositories\Entities\User\Exceptions\InvalidDataTypeExceptions;
use App\Inv\Repositories\Models\Master\Role as Role;
use App\Inv\Repositories\Models\Master\Permission;
use Tymon\JWTAuth\Contracts\JWTSubject;

class ApiUser extends Authenticatable implements JWTSubject
{

    use Notifiable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'api_users';

    /**
     * Custom primary key is set for the table
     *
     * @var integer
     */
    protected $primaryKey = 'user_id';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    //protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'f_name',
        'm_name',
        'l_name',
        'username',
        'email',
        'password',
        'is_active',
        'api_token',
        'created_by',
        'created_at',
        'updated_at',
        'updated_by'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return ['username','password'];
    }

    /**
     * update user details
     *
     * @param integer $user_id     user id
     * @param array   $arrUserData user data
     *
     * @return boolean
     */

    
    public static function saveapiUser($arrUserData = [])
    {

       

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

       
        $rowUpdate = self::create($arrUserData);

        return ($rowUpdate ? $rowUpdate : false);
    }

    /**
     * Get User Details base of user Id
     *
     * @param  integer $user_id
     * @return array
     * @throws BlankDataExceptions
     * @throws InvalidDataTypeExceptions
     * Since 0.1
     */
    public static function apiLogin($username, $password)
    {
 
           $arrUser = self::select('api_users.*')
            ->where('username', $username)
               ->where('password', $password)
            ->first();
         // $arrUser =  (array)$arrUser;
           return ($arrUser ?: false);

    }
    
    



    



/**
     * Get User Details base of user Id
     *
     * @param  integer $user_id
     * @return array
     * @throws BlankDataExceptions
     * @throws InvalidDataTypeExceptions
     * Since 0.1
     */
    public static function getUserID($user_id)
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

        $arrUser = self::select('users.*')
            ->where('users.user_id', (int) $user_id)
            ->first();

        return ($arrUser->user_id ?: false);
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
            ->where('user_type', 1)->get();
        return ($result ? $result : '');
    }
    
    
    
    
    
}