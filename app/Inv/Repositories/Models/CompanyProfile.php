<?php

namespace App\Inv\Repositories\Models;

use DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Inv\Repositories\Entities\User\Exceptions\BlankDataExceptions;
use App\Inv\Repositories\Entities\User\Exceptions\InvalidDataTypeExceptions;


class CompanyProfile extends Authenticatable
{

    use Notifiable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'corp_profiles';

    /**
     * Custom primary key is set for the table
     *
     * @var integer
     */
    protected $primaryKey = 'corp_profile_id';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_kyc_id',
        'user_id',
        'company_name',
        'customer_name',
        'registration_no',
        'registration_date',
        'status',
        'status_other',
        'business_nature',
        'created_at',
        'updated_at',
        
        
        
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    

    /**
     * update user details
     *
     * @param integer $user_id     user id
     * @param array   $arrUserData user data
     *
     * @return boolean
     */

    //getcompany profile data
    public function personalCompanyProfile($userKycid)
    {
        return CompanyProfile::where('user_kyc_id',$userKycid)->first();
    }
    //sshow data in admin section
    public static function getOtherStatus($userKycid)
    {
        $other = CompanyProfile::where('user_kyc_id',$userKycid)->first();
        return $other->status_other;
    }
   
  

   
   

}