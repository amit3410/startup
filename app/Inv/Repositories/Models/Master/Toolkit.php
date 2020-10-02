<?php

namespace App\Inv\Repositories\Models\Master;
use DB;
use App\Inv\Repositories\Factory\Models\BaseModel;
use App\Inv\Repositories\Entities\User\Exceptions\BlankDataExceptions;
use App\Inv\Repositories\Entities\User\Exceptions\InvalidDataTypeExceptions;

class Toolkit extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'mst_toolkit';

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
        'type_for',
        'type',
        'value_id',
        'is_active',
        'updated_at'
    ];

    /**
     * Scopes for active list
     *
     * @param string $query
     * @param string $type
     * @return type
     */
    public function scopeActive($query, $type)
    {
        return $query->where('is_active', $type);
    }


    

    public static function gettooklit()
    {
        $today=date('Y-m-d');
        $toolkitvalue = self::where('is_active',1)
              ->whereDate('mst_toolkit.updated_at','=',$today)
              ->first();
        //echo "---->"; exit;
        return ($toolkitvalue) ? $toolkitvalue->value_id : false;
    }

    
    public static function updatetoolkit($id, $arrData = [])
    {

        /**
         * Check Data is Array
         */
        if (!is_array($arrData)) {
            throw new InvalidDataTypeExceptions(trans('error_message.send_array'));
        }

        /**
         * Check Data is not blank
         */
        if (empty($arrData)) {
            throw new BlankDataExceptions(trans('error_message.no_data_found'));
        }
        $arrData['created_by'] = Auth()->user()->user_id;
        $arrData['updated_by'] = Auth()->user()->user_id;

        //$array['created_at'] = date('Y-m-d H:i:s');
        $arrUserData['updated_at'] = date('Y-m-d H:i:s');
       
        $rowUpdate = self::updateOrCreate(['id'=>$id], $arrData);

        return ($rowUpdate ? $id : false);
    }



      /**
     * Delete Data
     *
     * @param  integer $userId
     * @return mixed Array | Boolean false
     * 
     */
    public static function deleteToolkit()
    {
        
       //$toolkitDelete = DB::table('mst_toolkit')->truncate();
        //echo "debug"; exit;
        $today=date('Y-m-d');
        $toolkitDelete = self::whereDate('mst_toolkit.updated_at','<',$today)->truncate();

       // DB::raw('ALTER TABLE dex_mst_toolkit AUTO_INCREMENT = 1');
       // DB::table('mst_toolkit')->truncate();
        return ($toolkitDelete ?: false);
    }

    /**
     * Get Status
     *
     * @return type
     */
    public static function gettoolkitStatus()
    {
        
         $Status = self::where('is_active',1)
              ->Where('type_for','=','statuses')
              ->get();

        return ($Status) ? $Status : false;
    }


    /**
     * Get Status
     *
     * @return type
     */
    public static function gettoolkitRisks()
    {

         $risks = self::where('is_active',1)
              ->Where('type_for','=','risks')
              ->get();

        return ($risks) ? $risks : false;
    }

    /**
     * Get Tool Kit Reason
     *
     * @return type
     */
    public static function gettoolkitReasons()
    {

         $reasons = self::where('is_active',1)
              ->Where('type_for','=','reason')
              ->get();

        return ($reasons) ? $reasons : false;
    }



    public static function toolketvaluebyId($id)
    {

         $data = self::where('is_active',1)
              ->Where('id','=',$id)
              ->first();

        return ($data) ? $data->value_id : false;
    }

     public static function toolkettypebyId($id)
    {

         $data = self::where('is_active',1)
              ->Where('id','=',$id)
              ->first();

        return ($data) ? $data->type : false;
    }


}