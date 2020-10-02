<?php

namespace App\Inv\Repositories\Models\Master;

use App\Inv\Repositories\Factory\Models\BaseModel;
use App\Inv\Repositories\Entities\User\Exceptions\BlankDataExceptions;
use App\Inv\Repositories\Entities\User\Exceptions\InvalidDataTypeExceptions;

class GroupId extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'mst_group_id';

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
        'group_id',
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

    /**
     * Get Drop down list
     *
     * @return type
     */
    public static function getgroupId()
    {
        $today=date('Y-m-d');
         $groupid = self::where('is_active',1)
              ->whereDate('mst_group_id.updated_at','=',$today)
              ->first();

        return ($groupid) ? $groupid->group_id : false;
    }


    public static function updategroupID($arrUserData = [])
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

        $id = 1;
        //$array['created_at'] = date('Y-m-d H:i:s');
        $arrUserData['updated_at'] = date('Y-m-d H:i:s');
       
        $rowUpdate = self::find((int) $id)->update($arrUserData);

        return ($rowUpdate ? $id : false);
    }

}