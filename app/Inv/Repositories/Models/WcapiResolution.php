<?php

namespace App\Inv\Repositories\Models;

use DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Inv\Repositories\Entities\User\Exceptions\BlankDataExceptions;
use App\Inv\Repositories\Entities\User\Exceptions\InvalidDataTypeExceptions;

class WcapiResolution extends Authenticatable
{

    use Notifiable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'wcapi_resolution';

    /**
     * Custom primary key is set for the table
     *
     * @var integer
     */
    protected $primaryKey = 'resolution_id';

    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'resolution_id',
        'wcapi_req_res_id',
        'wcapi_parent_id',
        'case_id',
        'case_system_id',
        'result_id',
        'status_mark',
        'risk_level',
        'reson',
        'reson_desp',
        'from_resolve',
        'is_status',
        'created_by',
        'created_at',
        'updated_at',
        'updated_by'
        
    ];

    
 /**
     * Save WCAPI Resolution
     *
     * @param  array $arr
     *
     * @throws InvalidDataTypeExceptions
     * @throws BlankDataExceptions
     */
    public static function saveWcapiResolution($arr = [])
    {

        //Check data is Array
        if (!is_array($arr)) {
            throw new InvalidDataTypeExceptions(trans('error_message.send_array'));
        }

        //Check data is not blank
        if (empty($arr)) {
            throw new BlankDataExceptions(trans('error_message.data_not_found'));
        }
        /**
         * Create API Request and response
         */
        $objWcapi = self::create($arr);

        return ($objWcapi ? $objWcapi : false);
    }
    
   

    public static function getResolutionData($id)
    {

         $data = self::Where('wcapi_req_res_id','=',$id)->orderBy('resolution_id', 'DESC')->first();

        return ($data) ? $data : false;
    }

    public static function getResolutionDatabyparentID($id)
    {
        $data = self::Where('wcapi_parent_id','=',$id)->orderBy('resolution_id', 'DESC')->first();
        return ($data) ? $data : false;
    }
    
    public static function getResolutionDatabycaseID($caseid)
    {
        $data = self::Where('case_id','=',$caseid)
            ->Where('is_status','=',1)->orderBy('resolution_id', 'DESC')->get();
        return ($data) ? $data : false;
    }

     public static function getDatabycaseIDandstatus($caseId, $resolutionStatus)
    {
        $data = self::Where('case_id','=',$caseId)
            ->Where('status_mark','<',$resolutionStatus)
            ->Where('is_status','=',1)->orderBy('resolution_id', 'DESC')->get();
        return ($data) ? $data : false;
    }


    
    public static function getDatabycaseIDandresulid($caseid, $resultId)
    {
        
       

        $data = DB::table('wcapi_resolution')
            ->Where('case_id','=',$caseid)
            ->whereRaw('FIND_IN_SET("'.$resultId.'",result_id)')
            ->Where('is_status','=',1)
            ->orderBy('resolution_id', 'DESC')
            ->get();
        return ($data) ? $data : false;
    }
    
    public static function updateResolution($updateDataArray, $resolution_id)
    {
        $rowUpdate = DB::table('wcapi_resolution')->where('resolution_id', '=', $resolution_id)->update($updateDataArray);
        return ($rowUpdate ? (int) $resolution_id : false);
    }



    

}