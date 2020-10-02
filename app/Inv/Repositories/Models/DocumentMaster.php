<?php

namespace App\Inv\Repositories\Models;

use DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Inv\Repositories\Entities\User\Exceptions\BlankDataExceptions;
use App\Inv\Repositories\Entities\User\Exceptions\InvalidDataTypeExceptions;

class DocumentMaster extends Authenticatable
{

    use Notifiable;
 

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'mst_doc';

    /**
     * Custom primary key is set for the table
     *
     * @var integer
     */
    protected $primaryKey = 'id';

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
        'doc_name',
        'doc_for',
        'is_active',
        'is_required',
        'type',
        'is_monitor',
        'doc_no',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];
    
    
     /*
     * Save or update Document
     * @param $inputData, $id
     * return int
     */
    
    public static function saveOrEditDocument($inputData, $id = null) 
    {      
        try{
            $data = self::updateOrCreate(['id' => (int) $id],$inputData);
            return ($data ? $data : false);
        }catch (\Exception $e){
            //echo $e; exit;
            $data['errors'] = $e->getMessage();
            return $data;
        }   
    }
    
    public static function updateActiveInactive($inputData, $id = null) 
    {      
        try{
            $data = self::updateOrCreate(['id' => (int) $id],$inputData);
            return ($data ? $data : false);
        }catch (\Exception $e){
            //echo $e; exit;
            $data['errors'] = $e->getMessage();
            return $data;
        }   
    }


    /**
     * Get Document
     *
     * @return type
     */
    public static function getdata($id)
    {
       /**
         * Check user id is not an integer
         */
        if (!is_int((int) $id) && $id != 0) {
            throw new InvalidDataTypeExceptions(trans('error_message.invalid_data_type'));
        }
        $doc = self::where('id',$id)->first();

        return $doc ?: false;
        
    }

    /**
     * update user details
     *
     * @param integer $user_id     user id
     * @param array   $arrUserData user data
     *
     * @return boolean
     */


   
     public static function getUserDocumentsType(){

        return DocumentMaster::where('is_active',1)->get();
     }


      public static function getPrimaryDocumentName(){

        return DocumentMaster::where('is_active',1)
            ->where('doc_for',3)
            ->where('form_id',1)    
            ->get();
     }

     /**
     * Get Document name
     *
     * @return type
     */
    public static function getDocumentName($id)
    {
        $right_type = self::where('id',$id)->first();

        return $right_type->doc_name ?: false;
    }
    
    
    
    /**
     * get Other Docs
     *
     * @param  integer $doc_for
     * @return mixed Array | Boolean false
     * @throws InvalidDataTypeExceptions
     */
    public static function getOtherDocs($doc_for,$ids=array()){
    
        /**
         * Check user id is not an integer
         */
        if (!is_int((int) $doc_for) && $doc_for != 0) {
            throw new InvalidDataTypeExceptions(trans('error_message.invalid_data_type'));
        }
        
        
        if(is_array($ids) && count($ids)){
             $otherDocs = self::where('doc_for',$doc_for)
                    ->where('type', '=',2)
                    ->whereIn('id',$ids) 
                    ->get();
        }else{
             $otherDocs = self::where('doc_for',$doc_for)
                    ->where('type', '=',2) 
                    ->get();
        }
       

        return ($otherDocs ?: false);
    }
    
    
    /**
     * Get Drop down list
     *
     * @return type
     */
    public static function getOtherDocsDropDown($doc_for)
    {
        return self::where('doc_for',$doc_for)
                    ->where('type', '=',2)->orderBy('doc_name', 'ASC')->pluck('doc_name', 'id');
    }

   
    public static function getAllOtherDocsPaginate($filter){
         $query=self::whereIn('is_active',array(0,1));
         if(isset($filter['type']) && $filter['type']!='' && $filter['type']!=null){
             $type  =   (int)$filter['type'];
             $query->where('type',$type);
         }
         
         if(isset($filter['doc_for']) && $filter['doc_for']!='' && $filter['doc_for']!=null){
             $doc_for  =   (int)$filter['doc_for'];
             $query->where('doc_for',$doc_for);
         }
         
         if(isset($filter['doc_name']) && $filter['doc_name']!='' && $filter['doc_name']!=null){
            $doc_name  =   strtolower($filter['doc_name']);
            $query->where('doc_name', 'like', '%' . $filter['doc_name'] . '%');
         }
         $query->orderBy('id','desc');
         
         
         
         $result    =   $query->paginate(10);
         return ($result ? $result : false);
    }
    
    
    /**
     * get document by doc number
     *
     * @param integer $doc_no
     *
     * @return object
     */


   
     public static function getMonitorMstDocByDocNo($arrDoc_no){
        //Check data is Array
        if (!is_array($arrDoc_no)){
            throw new InvalidDataTypeExceptions(trans('error_message.send_array'));
        } 
        return DocumentMaster::where('is_active',1)->where('doc_for',3)->where('is_monitor',1)->whereIn('doc_no',$arrDoc_no)->get();
     }

}

