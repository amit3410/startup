<?php

namespace App\Inv\Repositories\Models\Master;

use Carbon\Carbon;
use App\Inv\Repositories\Factory\Models\BaseModel;
use App\Inv\Repositories\Entities\User\Exceptions\BlankDataExceptions;
use App\Inv\Repositories\Entities\User\Exceptions\InvalidDataTypeExceptions;

class Countrytype extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'country_type';

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
        'country_name',
        'country_type',
        'country_score',
        'is_active'
    ];

    
    /*
     * get Country data by id
     * @param $countryName
     */
    public static function getCountryTypeByName($countryName)
    {
        //Check id is not blank
        if (empty($countryName)) {
            throw new BlankDataExceptions(trans('error_message.no_data_found'));
        }
        
        $data = self::where('country_name', $countryName)->first();
        return ($data ? $data : false);
    }
}