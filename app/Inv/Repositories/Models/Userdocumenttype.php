<?php

namespace App\Inv\Repositories\Models;

use App\Inv\Repositories\Factory\Models\BaseModel;

class Userdocumenttype extends BaseModel
{
    /* The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users_documenttype';

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
        'user_kyc_id',
        'doc_id',
        'document_number',
        'issuance_date',
        'expire_date',
        'is_monitor',
        'doc_for',
        'doc_no',
        'form_id',
        'user_req_doc_id',
        'is_active',
        'status',
        'doc_status',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        
    ]; 
    
    /**
     * storeData
     * @param
     * @return array
     * @since 0.1
     * @author Arvind soni
     */
    public static function storeData($inputData,$id='') {
        
        try {
            $data = self::updateOrCreate(['id' => (int) $id],$inputData);
            return ($data ?: false);
        } catch (\Exception $e) {
            $data['errors'] = $e->getMessage();
        }
    }
    
    
   /*
     * Get Document Type
     * @param int user_id
     * @return array
     *@author  arvind soni
    */
    public static function getData($user_kyc_id=''){
        
       /* $result=self::select('users_documenttype.*','mst_documents.title as documenttype')->join('mst_documents','mst_documents.id','=','users_documenttype.document_type')->where('user_kyc_id','=',$user_kyc_id)->get();
        return ($result?:false);*/

         $result=self::select('users_documenttype.*')->where('user_kyc_id','=',$user_kyc_id)->where('is_active',1)->where('form_id',1)->orderBy('doc_id')->get();
        return ($result?:false);
    }
    
    
    
    /**
     * Delete Data
     *
     * @param  integer $userId
     * @return mixed Array | Boolean false
     * @throws InvalidDataTypeExceptions
     */
    public static function deleteData($user_kyc_id)
    {
        /**
         * Check user id is not an integer
         */
        if (!is_int((int) $user_kyc_id) && $user_kyc_id != 0){
            throw new InvalidDataTypeExceptions(trans('error_message.invalid_data_type'));
        }

        $sklDelete = self::where('user_kyc_id', '=', $user_kyc_id)->where('form_id', '=',1)->delete();

        return ($sklDelete ?: false);
    }
    
     
    
    /**
     * document Being Expire
     *
     * @param  Array $days
     * @return mixed Array | Boolean false
     * @throws InvalidDataTypeExceptions
    */
    public static function passportBeingExpire($arrDays,$arrDoc_no){
        
       
        //Check data is Array
        if (!is_array($arrDays)){
            throw new InvalidDataTypeExceptions(trans('error_message.send_array'));
        }

        //Check data is not blank
        
        if(empty($arrDays)){
            throw new BlankDataExceptions(trans('error_message.data_not_found'));
        }

        $query = self::select('users_documenttype.id','users_documenttype.doc_no','users_documenttype.status','users_documenttype.doc_status','users_documenttype.user_kyc_id','users_documenttype.expire_date','users.email','users.f_name','users.l_name','users.user_id','users.user_type','users.user_id')
                ->leftJoin('user_kyc','user_kyc.kyc_id','=','users_documenttype.user_kyc_id')
                ->leftJoin('users','users.user_id','=','user_kyc.user_id')
                //->where('users.user_id',198)
                ->where('users_documenttype.is_active',1)
                ->whereIn('users_documenttype.doc_no',$arrDoc_no);
        
        $query->where(function($query) use($arrDays) {
            $today=date('Y-m-d');
            foreach($arrDays as $day){
                $expire_date  =  date('Y-m-d',strtotime('+'.$day.' days',strtotime($today)));
           
                $query->orWhere('users_documenttype.expire_date','=',$expire_date);
            }
        });

        
        
        $resultData=$query->get();
        
        
        return ($resultData ?: false);
    }

    /*
     * Get Document Type
     * @param int user_id
     * @return array
     *@author  arvind soni
    */
    public static function getDocumentTypeInfowithpassport($user_kyc_id, $passportNumber){
         $result=self::select('users_documenttype.*')
             ->where('user_kyc_id','=',$user_kyc_id)
             ->where('document_number','=',$passportNumber)
             ->orderBy('id', 'DESC')
             ->first();
        return ($result?:false);
    }
    
    
    /**
     * Get all Profile MOnitoring Uerss
     *
     * @return array
     * @throws BlankDataExceptions
     * @throws InvalidDataTypeExceptions
     * Since 0.1
     */
    public static function getProfileMonitoringPaginate($filter)
    {
        $query = self::select('users_documenttype.id','users_documenttype.doc_no','users_documenttype.user_kyc_id','users_documenttype.expire_date','users_documenttype.is_active','users_documenttype.status','users_documenttype.doc_status','users.email','users.f_name','users.l_name','users.user_id','users.phone_no','users.phone_no','users.country_code','users.user_type','user_kyc.kyc_id as user_kyc_id','user_kyc.is_by_company')
                ->join('user_kyc','user_kyc.kyc_id','=','users_documenttype.user_kyc_id')
                ->join('users','users.user_id','=','user_kyc.user_id')
                ->whereIn('users_documenttype.status',[1,2])
                ->where('users_documenttype.doc_no','>',0);
        
        if(isset($filter['req_status'])){
            if($filter['req_status'] != ""){
                $request_status = trim($filter['req_status']);
                $request_status =   (int)$request_status;
                $query->where('users_documenttype.status',$request_status);
            }
        }
        
        if(isset($filter['doc_status'])){
            if($filter['doc_status'] != ""){
                $doc_status = trim($filter['doc_status']);
                $doc_status =   (int)$doc_status;
                $query->where('users_documenttype.doc_status',$doc_status);
            }
        }
        
        if(isset($filter['search_keyword'])) {
            if($filter['search_keyword'] != ""){
                $search_keyword = trim($filter['search_keyword']);
                $search_keyword =   strtolower($search_keyword);
                $query->where(function ($query) use ($search_keyword) {
                    $query->where('users.f_name', 'LIKE', '%'.$search_keyword.'%')
                    ->orWhere('users.l_name', 'LIKE', '%'.$search_keyword.'%')
                    ->orWhere('users.email', 'LIKE', '%'.$search_keyword.'%')
                    ->orWhere('users.phone_no', 'LIKE', '%'.$search_keyword.'%');
                });
            }
        }
        $query->orderBy('users_documenttype.status', 'DESC');
        $result = $query->paginate(10);
        return ($result ? $result : '');
    }
    
    /**
     * Get all Profile MOnitoring Uerss
     *
     * @return array
     * @throws BlankDataExceptions
     * @throws InvalidDataTypeExceptions
     * Since 0.1
     */
    public static function getMonitorDocData($id)
    {
        /**
         * Check user id is not an integer
         */
        if (!is_int((int) $id) && $id != 0) {
            throw new InvalidDataTypeExceptions(trans('error_message.invalid_data_type'));
        }
        
        $query = self::select('users_documenttype.id','users_documenttype.doc_no','users_documenttype.user_kyc_id','users_documenttype.expire_date','users_documenttype.is_active','users_documenttype.status','users_documenttype.doc_status','users_documenttype.expire_date','users_documenttype.issuance_date','users_documenttype.document_number','users_documenttype.user_req_doc_id','users.email','users.f_name','users.l_name','users.user_id','users.phone_no','users.phone_no','users.country_code','users.user_type','user_kyc.kyc_id as user_kyc_id','user_kyc.is_by_company')
                ->join('user_kyc','user_kyc.kyc_id','=','users_documenttype.user_kyc_id')
                ->join('users','users.user_id','=','user_kyc.user_id')
                ->where('users_documenttype.id',$id);
        
        
        $result = $query->first();
        return ($result ? $result : '');
    }
    
    
    /**
     * get document by doc number
     *
     * @param integer $doc_no
     *
     * @return object
     */


   
     public static function getMonitorDocByDocNo($arrDoc_no,$user_kyc_id){
         /**
         * Check user id is not an integer
         */
        if (!is_int((int) $user_kyc_id) && $user_kyc_id != 0) {
            throw new InvalidDataTypeExceptions(trans('error_message.invalid_data_type'));
        }
        //Check data is Array
        if (!is_array($arrDoc_no)){
            throw new InvalidDataTypeExceptions(trans('error_message.send_array'));
        } 
        return self::where('is_active',1)->where('is_monitor',1)->where('user_kyc_id',$user_kyc_id)->whereIn('doc_no',$arrDoc_no)->get();
     }

    
    
}