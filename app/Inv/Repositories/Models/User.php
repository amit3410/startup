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

class User extends Authenticatable implements JWTSubject
{

    use Notifiable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

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
        'country_code',
        'register_as',
        'about',
        'phone_no',
        'user_type',
        'is_email_verified',
        'is_superadmin',
        'email_verified_updatetime',
        'is_otp_verified',
        'otp_verified_updatetime',
        'is_pwd_changed',
        'pwd_updatetime',
        'is_active',
        'block_status_id',
        'block_status_updatetime',
        'kyc_completed_sent_mail',
        'user_source',
        'parent_id',
        'is_by_admin',
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

        //echo "<pre>";
        //print_r($arrUserData); exit;

        $rowUpdate = self::find((int) $user_id)->update($arrUserData);

        return ($rowUpdate ? $user_id : false);
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
    public static function getUserDetail($user_id)
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

        $arrUser = self::join('user_detail as ud','ud.user_id','=','users.user_id')
            ->select('users.*','ud.country_id','ud.date_of_birth')
            ->where('users.user_id', (int) $user_id)
            //->where('users.is_active', 1)
            ->first();

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
    public static function getCorpUserDetail($user_id)
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

        $arrUser = self::join('corp_detail as ud','ud.user_id','=','users.user_id')->select('users.*','ud.country_id','ud.corp_name','ud.corp_date_of_formation','ud.corp_license_number','ud.date_of_birth')
            ->where('users.user_id', (int) $user_id)
            ->where('users.is_active', 1)
            ->first();

        return ($arrUser ?: false);
    }



    /**
     * Get User Details base of user Id for admin
     *
     * @param  integer $user_id
     * @return array
     * @throws BlankDataExceptions
     * @throws InvalidDataTypeExceptions
     * Since 0.1
     */
    public static function getCorpUserDetailAdmin($user_id)
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

        $arrUser = self::join('corp_detail as ud','ud.user_id','=','users.user_id')->select('users.*','ud.country_id','ud.corp_name','ud.corp_date_of_formation','ud.corp_license_number','ud.date_of_birth')
            ->where('users.user_id', (int) $user_id)
            ->first();

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
     * Get User Details base of user Id
     *
     * @param  integer $user_id
     * @return array
     * @throws BlankDataExceptions
     * @throws InvalidDataTypeExceptions
     * Since 0.1
     */
    public static function getfullUserDetail($user_id)
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

        return ($arrUser ?: false);
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
    
    
    /**
     * Get all users
     *
     * @return array
     * @throws BlankDataExceptions
     * @throws InvalidDataTypeExceptions
     * Since 0.1
     */
    public static function getAllUsersPaginate($user_type='',$filter)
    {
        /**
         * Check id is not blank
         */
        if (empty($user_type)) {
            throw new BlankDataExceptions(trans('error_message.no_data_found'));
        }

        /**
         * Check id is not an integer
         */
        if (!is_int($user_type)) {
            throw new InvalidDataTypeExceptions(trans('error_message.invalid_data_type'));
        }
        if(isset($filter['profile_status'])) {
            $ps = $filter['profile_status'];
        } else {
            $ps = '';
        }

       

        if($ps!=6) {

            if($user_type == 1) {
            $query=self::join('user_kyc as ku','ku.user_id','=','users.user_id')
             //->leftjoin('user_assesment_rank as ar','ar.user_kyc_id','=','ku.kyc_id','ar.is_active','=','1')
             ->leftjoin('user_assesment_rank', function($join)
             {
                 $join->on('user_assesment_rank.user_kyc_id', '=', 'ku.kyc_id')
                 ->where('user_assesment_rank.is_active','=', 1);
             })
             ->select('users.*','ku.kyc_id as user_kyc_id','ku.is_by_company')
             ->where('users.user_type',$user_type)
             ->where('ku.is_by_company','0');
            } else {
               $query=self::join('user_kyc as ku','ku.user_id','=','users.user_id')
             //->leftjoin('user_assesment_rank as ar','ar.user_kyc_id','=','ku.kyc_id','ar.is_active','=','1')
             ->leftjoin('user_assesment_rank', function($join)
             {
                 $join->on('user_assesment_rank.user_kyc_id', '=', 'ku.kyc_id')
                 ->where('user_assesment_rank.is_active','=', 1);
             })
             ->select('users.*','ku.kyc_id as user_kyc_id','ku.is_by_company','ud.country_id',
             'ud.corp_name', 'ud.corp_date_of_formation',
             'ud.corp_date_of_formation', 'ud.corp_license_number','ud.date_of_birth')
             ->join('corp_detail as ud', 'users.user_id', '=', 'ud.user_id')
             ->where('users.user_type',$user_type)
             ->where('ku.is_by_company','0');
            }


        } else {

         //   echo $user_type; exit;

          if($user_type == 1)   {
          $query=self::join('user_kyc as ku','ku.user_id','=','users.user_id')
                    //->leftjoin('user_assesment_rank as ar','ar.user_kyc_id','=','ku.kyc_id','ar.is_active','=','1')
                    ->leftjoin('user_assesment_rank', function($join)
                    {
                        $join->on('user_assesment_rank.user_kyc_id', '=', 'ku.kyc_id')
                        ->whereIn('user_assesment_rank.avg_rank',[1,2,3,4,5])
                        ->where('user_assesment_rank.is_active','=', 1);
                    })
                    ->select('users.*','ku.kyc_id as user_kyc_id','ku.is_by_company')
                    ->where('users.user_type',$user_type)
                    ->where('ku.is_by_company','0');
          } else {
              $query=self::join('user_kyc as ku','ku.user_id','=','users.user_id')
                    //->leftjoin('user_assesment_rank as ar','ar.user_kyc_id','=','ku.kyc_id','ar.is_active','=','1')
                    ->leftjoin('user_assesment_rank', function($join)
                    {
                        $join->on('user_assesment_rank.user_kyc_id', '=', 'ku.kyc_id')
                        ->whereIn('user_assesment_rank.avg_rank',[1,2,3,4,5])
                        ->where('user_assesment_rank.is_active','=', 1);
                    })
                    ->select('users.*','ku.kyc_id as user_kyc_id','ku.is_by_company','ud.country_id',
             'ud.corp_name', 'ud.corp_date_of_formation',
             'ud.corp_date_of_formation', 'ud.corp_license_number','ud.date_of_birth')
                    ->join('corp_detail as ud', 'users.user_id', '=', 'ud.user_id')
                    ->where('users.user_type',$user_type)
                    ->where('ku.is_by_company','0');
              
              
          }


        }
        
        $isApproved=['2'=>'1','3'=>'2'];
        if(isset($filter['kyc_status'])) {
            if ($filter['kyc_status'] != "") {
                $kyc_status = trim($filter['kyc_status']);
                if($kyc_status=='0' ||$kyc_status=='1'){
                    $query->where('ku.is_kyc_completed',$kyc_status);
                }else if($kyc_status=='2' ||$kyc_status=='3'){                    
                    $query->where('ku.is_finalapprove',$isApproved[$kyc_status]);
                }
                
            }
        }



        if(isset($filter['profile_status'])) {
            if ($filter['profile_status'] != "") {
                $profile_status = trim($filter['profile_status']);
                if($profile_status=='1'){
                    $query->where('user_assesment_rank.avg_rank',$profile_status);
                }else if($profile_status=='3' ){
                     $query->where('user_assesment_rank.avg_rank',$profile_status);
                } else if($profile_status=='4'){
                     $query->where('user_assesment_rank.avg_rank',$profile_status);
                } 
            }
        }



        if(isset($filter['user_source'])) {
            if ($filter['user_source'] != "") {
                $user_source = trim($filter['user_source']);
               // echo "==>".$user_source;
                if($user_source==2){
                    $query->where('users.user_source','trading');
                }else if($user_source==1){
                  $query->whereNull('users.user_source');
                }
                else if($user_source==0){
                }

            }
        }
        
        if(isset($filter['search_keyword'])) {
            if ($filter['search_keyword'] != "") {
                $search_keyword = trim($filter['search_keyword']);
                $search_keyword =   strtolower($search_keyword);
                $query->where(function ($query) use ($search_keyword,$user_type) {
                        $query->where('users.f_name', 'LIKE', '%'.$search_keyword.'%')
                                ->orWhere('users.l_name', 'LIKE', '%'.$search_keyword.'%')
                                ->orWhere('users.email', 'LIKE', '%'.$search_keyword.'%')
                                ->orwhere(DB::raw('concat(dex_users.f_name," ",dex_users.l_name)') , 'LIKE', "%".$search_keyword."%");
                            

                        if($user_type ==2) {
                            $query->orWhere('ud.corp_name', 'LIKE', '%'.$search_keyword.'%');
                        }

                });
            }
        }

        $query->orderBy('user_id', 'DESC');
        $result = $query->paginate(10);
        return ($result ? $result : '');
    }
    
    /**
     * getUserByemail
     * @param $email
     * @return array $arrUser
     * @since 0.1
     * 
     */
    public static function getUserByemail($email)
    {
        
        $arrUser = SELF::select('*')
            ->where('email', '=', $email)
            ->first();
        return ($arrUser ? $arrUser : []);
    }

    /**
     * getUserByuserName
     * @param $userName
     * @return array $arrUser
     * @since 0.1
     * 
     */
    public static function getUserByuserName($userName)
    {

        $arrUser = SELF::select('*')
            ->where('username', '=', $userName)
            ->first();
        return ($arrUser ? $arrUser : []);
    }



    /**
     * getUserType
     * @param $userId
     * @return array $arrUser
     * @since 0.1
     * @author 
     */
    public static function getUserType($userId)
    {

        $arrUser = SELF::select('user_type')
                    ->where('user_id', '=', $userId)
                   // ->where('is_active', '=', 1)
                    ->first();
        return ($arrUser ? $arrUser->user_type : false);
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




    /**
     * Get User Details base of user Id
     *
     * @param  integer $user_id
     * @return array
     * @throws BlankDataExceptions
     * @throws InvalidDataTypeExceptions
     * Since 0.1
     */
    public static function getUserPersonalData($user_id)
    {
       // return User::find($user_id);
        //Check id is not blank

        if (empty($user_id)) {
            throw new BlankDataExceptions(trans('error_message.no_data_found'));
        }
        //Check id is not an integer

        if (!is_int($user_id)) {
            throw new InvalidDataTypeExceptions(trans('error_message.invalid_data_type'));
        }

        /*$arrUser = self::select('users.*')
            ->where('users.user_id', (int) $user_id)
            ->first();
*/

         $arrUser = self::select('users.*', 'ud.country_id',
             'ud.date_of_birth'
              )

            ->join('user_detail as ud', 'users.user_id', '=', 'ud.user_id')
            ->where('users.user_id', (int) $user_id)
            ->first();




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
    public static function getUserCorpData($user_id)
    {
       // return User::find($user_id);
        //Check id is not blank

        if (empty($user_id)) {
            throw new BlankDataExceptions(trans('error_message.no_data_found'));
        }
        //Check id is not an integer

        if (!is_int($user_id)) {
            throw new InvalidDataTypeExceptions(trans('error_message.invalid_data_type'));
        }

        /*$arrUser = self::select('users.*')
            ->where('users.user_id', (int) $user_id)
            ->first();
*/

         $arrUser = self::select('users.*', 'ud.country_id',
             'ud.corp_name', 'ud.corp_date_of_formation',
             'ud.corp_date_of_formation', 'ud.corp_license_number','ud.date_of_birth')

            ->join('corp_detail as ud', 'users.user_id', '=', 'ud.user_id')
            ->where('users.user_id', (int) $user_id)
            ->first();




        return ($arrUser ?: false);
    }
   
    
    public static function updateSystemGeneratedPass($arrUserData,$email){
        
        $rowUpdate = self::where('email',$email)->update($arrUserData);

        return ($rowUpdate ? $email : false);
    }

    /**
     * Fetch the collection of site permissions.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getPermissions()
    {
        return Permission::with('roles')->get();
    }

/**
     * A user may have multiple roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, "role_user", 'user_id');
    }

    /**
     * Assign the given role to the user.
     *
     * @param string $role
     *
     * @return mixed
     */
    public function assignRole($role_id)
    {
        return $this->roles()->sync(array($role_id));
    }

    /**
     * Determine if the user has the given role.
     *
     * @param  mixed $role
     * @return boolean
     */
    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }
        return !!$role->intersect($this->roles)->count();
    }

    /**
     * Determine if the user may perform the given permission.
     *
     * @param Permission $permission
     *
     * @return boolean
     */
    public function hasPermission(Permission $permission)
    {
        return $this->hasRole($permission->roles);
    }

    /**
     * Get Roles by user id
     *
     * @param $user_id user id
     *
     * @return object roles
     */
    public static function getUserRoles($user_id)
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

        $arrRoles = self::find($user_id)->roles;

        return ($arrRoles ? : false);
    }

    /**
     * Get backend user data w.r.t id
     *
     * @param integer $user_id
     *
     * @return array User List
     */
    public static function getBackendUser($user_id)
    {
         $users = self::getUserRoles($user_id);
          return $users;
    }

    ///////////////////////


    /**
     * Get User Details base of trading user Id
     *
     * @param  integer $user_id
     * @return array
     * @throws BlankDataExceptions
     * @throws InvalidDataTypeExceptions
     * Since 0.1
     */
    public static function getUserPrimaryId($user_id)
    {
        //Check id is not blank
        if (empty($user_id)) {
            throw new BlankDataExceptions(trans('error_message.no_data_found'));
        }
        //Check id is not an integer
        if (!is_int($user_id)) {
            throw new InvalidDataTypeExceptions(trans('error_message.invalid_data_type'));
        }
        $arrUser = self::where('users.user_id', (int) $user_id)
            ->first();
        return ($arrUser ?: false);
    }


     /**
     * Get User Details base of trading user Id
     *
     * @param  integer $user_id
     * @return array
     * @throws BlankDataExceptions
     * @throws InvalidDataTypeExceptions
     * Since 0.1
     */
    public static function getUserByEmailforAPI($email)
    {
        //Check email is not blank
        if (empty($email)) {
            throw new BlankDataExceptions(trans('error_message.no_data_found'));
        }
        
        $arrUser = self::where('users.email',$email)
            ->first();
       // dd($arrUser);
        return ($arrUser ?: false);
    }


    /**
     * Get all users
     *
     * @return array
     * @throws BlankDataExceptions
     * @throws InvalidDataTypeExceptions
     * Since 0.1
     */
    public static function getAllTradingUsersPaginate($user_type='',$filter)
    {
        /**
         * Check id is not blank
         */
        if (empty($user_type)) {
            throw new BlankDataExceptions(trans('error_message.no_data_found'));
        }

        /**
         * Check id is not an integer
         */
        if (!is_int($user_type)) {
            throw new InvalidDataTypeExceptions(trans('error_message.invalid_data_type'));
        }

        $query=self::join('user_kyc as ku','ku.user_id','=','users.user_id')
            ->select('users.*','ku.kyc_id as user_kyc_id','ku.is_by_company')
            ->where('users.user_type',$user_type)
            ->where('users.user_source','trading')
            ->where('ku.is_by_company','0');

        $isApproved=['2'=>'1','3'=>'2'];
        if(isset($filter['kyc_status'])) {
            if ($filter['kyc_status'] != "") {
                $kyc_status = trim($filter['kyc_status']);
                if($kyc_status=='0' ||$kyc_status=='1'){
                    $query->where('ku.is_kyc_completed',$kyc_status);
                }else if($kyc_status=='2' ||$kyc_status=='3'){
                    $query->where('ku.is_finalapprove',$isApproved[$kyc_status]);

                }

            }
        }

        if(isset($filter['search_keyword'])) {
            if ($filter['search_keyword'] != "") {
                $search_keyword = trim($filter['search_keyword']);
                $search_keyword =   strtolower($search_keyword);
                $query->where(function ($query) use ($search_keyword) {
                        $query->where('users.f_name', 'LIKE', '%'.$search_keyword.'%')
                                ->orWhere('users.l_name', 'LIKE', '%'.$search_keyword.'%')
                                ->orWhere('users.email', 'LIKE', '%'.$search_keyword.'%');
                });
            }
        }

        $query->orderBy('user_id', 'DESC');

        $result = $query->paginate(10);
        return ($result ? $result : '');
    }

    public static function userAccountLockUnlock($userId,$blockId) {

        $status = self::where('user_id',$userId)->update(['block_status_id'=>$blockId]);
        return ($status ? $status : false);
        //return UserModel::where('user_id',$userId)->update('block_status_id',$blockId);
    }

    public static function getTermCondition($userId) {
        
        $status = self::where('user_id',$userId)->first();
        //dd($status);

        return ($status ? $status->is_term_condition : false);
    }

    public static function getOtherDocTermCondition($userId) {
        
        $status = self::where('user_id',$userId)->first();
        return ($status ? $status->is_otherdoc_term_condition : false);
    }


    public static function getTradingkycCompletedApproved() {
        

        return self::join('user_kyc as ku','ku.user_id','=','users.user_id')
            ->select('users.user_id','users.f_name','users.email','ku.kyc_id as user_kyc_id','ku.is_by_company','ku.is_approve','ku.is_kyc_completed')
            ->where('users.user_source','trading')
            ->where('ku.is_kyc_completed',1)
           ->get();
    }

}