<?php

namespace App\Inv\Repositories\Models;

use DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Inv\Repositories\Entities\User\Exceptions\BlankDataExceptions;
use App\Inv\Repositories\Entities\User\Exceptions\InvalidDataTypeExceptions;

class UserReqDoc extends Authenticatable
{

    use Notifiable;
 

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_req_doc';

    /**
     * Custom primary key is set for the table
     *
     * @var integer
     */
    protected $primaryKey = 'user_req_doc_id';

    protected $fillable=[
       'doc_id',
       'doc_no', 
       'doc_type',
       'user_id',
       'upload_doc_name',
       'user_kyc_id',
       'is_upload',
       'is_required',
       'type', 
       'created_by',

    ];

   public static function createCorpDocRequired($doc,$userKycid, $userID)
   {
       
        if(count($doc)>0)
        {
            
            foreach($doc as $val)
            {
               $res=UserReqDoc::create([
                       'doc_type'=>$val->doc_for,
                       'user_kyc_id'=>$userKycid,
                       'user_id'=>$userID,
                       'doc_id'=>$val->id,
                       'doc_no'=>$val->doc_no,
                       'is_required'=>$val->is_required,
                       'upload_doc_name'=>$val->doc_name,
                       'is_upload'=>0,
                       'type'=>1,
                       'created_by'=>$userID,

                ]);


            }
        }
        return $res;
   }
    

   public static function getUserDocuments()
   {
        $userkyc=Userkyc::where('user_id',Auth()->user()->user_id)->first();

        $corpdata=UserReqDoc::where('user_kyc_id', $userkyc->kyc_id)->where('type',1)->get();
        if(!empty($corpdata)){
             
             return   $corpdata;
        }

   } 
   
   public static function checkAllDocUploaded($kyc_id){
       $corpdata=self::where('user_kyc_id', $kyc_id)->where('is_required',1)->where('is_upload',0)->where('type',1)->get();
       
       if($corpdata && $corpdata->count()){
           return false; 
       }else{
          return true; 
       }
        
   }
   
    public static function getRequiredDocsList($kyc_id){
        try{
            $data = self::where('user_kyc_id',$kyc_id)->where('is_required',1)->where('is_upload',0)->where('type',1)
                        ->pluck('upload_doc_name', 'user_req_doc_id')->toArray();
            
            return ($data ?: false);
        } catch (\Exception $e) {
            echo $e; exit;//
            $data['errors'] = $e->getMessage();
        }
    }
    
    public static function getRequiredOtherDocsList($kyc_id){
        try{
            $data = self::where('user_kyc_id',$kyc_id)->where('is_required',1)->where('is_upload',0)->where('type',2)
                        ->pluck('upload_doc_name', 'user_req_doc_id')->toArray();
            
            return ($data ?: false);
        } catch (\Exception $e) {
            echo $e; exit;//
            $data['errors'] = $e->getMessage();
        }
    }

   
    public static function saveOtherDocsReq($inputData){
        try{
            $res= self::insert($inputData);
            return ($res ?: false); 
          
        }catch(\Exception $e) {
            $data['errors'] = $e->getMessage();
            return $data; 
        }
   }
   
   public static function getOtherDocsReq($kyc_id){
        try{
            $data = self::where('user_kyc_id',$kyc_id)->where('type',2)->get();
            
            return ($data ?: false);
        } catch (\Exception $e) {
            echo $e; exit;//
            $data['errors'] = $e->getMessage();
        }
   }

}

