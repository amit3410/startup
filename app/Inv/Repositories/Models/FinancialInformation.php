<?php

namespace App\Inv\Repositories\Models;

use DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Inv\Repositories\Entities\User\Exceptions\BlankDataExceptions;
use App\Inv\Repositories\Entities\User\Exceptions\InvalidDataTypeExceptions;

class FinancialInformation extends Authenticatable
{

    use Notifiable;
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'corp_financial_info';

    /**
     * Custom primary key is set for the table
     *
     * @var integer
     */
    protected $primaryKey = 'corp_financial_info_id';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_kyc_id',
        'user_id',
        'total_debts_usd',
        'total_cash',
        'total_receivables_usd',
        'total_payable_usd',
        'total_salary_usd',
        'yearly_usd',
        'yearly_profit_usd',
        'capital_company_usd',

        
        
        
    
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
    public static function getFinancialData($userKycId)
    {
       return  FinancialInformation::where('user_kyc_id', $userKycId)->first();
    }

   
  

   
   

}

