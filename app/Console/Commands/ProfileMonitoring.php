<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Helpers;
use Event;
use App\Inv\Repositories\Contracts\UserInterface as InvUserRepoInterface;

class ProfileMonitoring extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ProfileMonitoring:DocumentExpireReminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'User profile document expired reminder';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    /**
     * User repository
     *
     * @var object
     */
    protected $userRepo;

    public function __construct(InvUserRepoInterface $user) {
        parent::__construct();
        $this->userRepo = $user;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        // get all list of document  whose will be expired after 30,15,7,1 days

        $arrDays = [30, 15, 7, 1];
        $today = date('Y-m-d');


        $arrDocument = Helpers::getMonitoringDropDown();

        // Passport Monitoring

        $doc_no = 1;
        $arrDocNo=[1,2,3];
        $passportList = $this->userRepo->documentBeingExpire($arrDays, $arrDocNo);

//echo "<pre>";
//print_r($passportList); exit;
//echo "eeee";exit;
        // Utility Bill Monitoring
        //$doc_no   =   2;
       
        // Company trade license Monitoring
        //$doc_no   =   3;
        
        // Passport Monitoring
        
        //$doc_no   =   1;

        if ($passportList && $passportList->count()) {
            foreach ($passportList as $obj2) {

                // sending reminder e-mail for renew expiray date
                $user_kyc_id = $obj2->user_kyc_id;
                echo $user_kyc_id;
                $expiry_date = $obj2->expire_date;

                $days = Helpers::dateDiffInDays($today, $expiry_date);

                // reminder history update

                $inputData = [];
                if($obj2->doc_no==1){
                    $inputData['reminder_for'] = 'Passport expiry reminder';
                    $inputData['reminder_type'] = 'PassportExpiry';
                }else if($obj2->doc_no==2){
                    $inputData['reminder_for'] = 'Utility Bill expiry reminder';
                    $inputData['reminder_type'] = 'UtilityBillExpiry';
                }else if($obj2->doc_no==3){
                    $inputData['reminder_for'] = 'Company Trade License expiry reminder';
                    $inputData['reminder_type'] = 'CompanyTradeLicenseExpiry';
                }
                
                $inputData['user_kyc_id'] = $obj2->user_kyc_id;
                $inputData['user_id'] = $obj2->user_id;
                $inputData['status'] = 1;
                $inputData['message'] = 'Dear ' . $obj2->f_name . ' ' . $obj2->l_name . ', please be informed the Utility Bill will expire in ' . $days . ' days. Upload a new document in your registered profile with Dexter Capital Financial Consultancy LLC Compliance Platform. After the expiry date of the Utility Bill your account will be locked.';

                $this->userRepo->saveReminder($inputData);
                if($obj2->status != 1){
                    // update document status

                    $indoc = [];
                    $indoc['is_active'] = 0;

                    $update = $this->userRepo->storeUserKycDocumentTypeData($indoc, $obj2->id);
                    // create new row
                  
                    $documentData['user_req_doc_id'] = $update->user_req_doc_id;
                    $documentData['doc_id'] = $update->doc_id;
                    $documentData['document_number'] = $update->document_number;
                    $documentData['is_monitor'] = $update->is_monitor;
                    $documentData['form_id'] = $update->form_id;
                    $documentData['doc_for'] = $update->doc_for;
                    $documentData['doc_no'] = $obj2->doc_no;
                    $documentData['is_active'] = 1;
                    $documentData['status'] = 1;
                    $documentData['doc_status'] = 0;
                    $documentData['issuance_date'] = $update->issuance_date;
                    $documentData['expire_date'] = $update->expire_date;
                    $documentData["user_kyc_id"] = $user_kyc_id;
                    $documentData["created_at"] = $update->created_at;
                    $documentData["created_by"] = $update->created_by;
                    $documentData["updated_by"] = $update->updated_by;
                    $documentData["updated_at"] = date('Y-m-d H:i:s');

                    $this->userRepo->storeUserKycDocumentTypeData($documentData, null);
                }

                if($obj2->user_type==2){
                    $userCheckArrData = $this->userRepo->getcorpDetail($obj2->user_id);
                    $verifyUserArr['corp_name'] = $userCheckArrData->corp_name;
                }else{
                    $verifyUserArr['corp_name'] = '';
                }
                
                $verifyUserArr['name'] = $obj2->f_name . ' ' . $obj2->l_name;
                $verifyUserArr['days'] = $days;
                $verifyUserArr['email'] = $obj2->email;
                 $verifyUserArr['user_type'] = $obj2->user_type;
                $verifyUserArr['document_type'] = $obj2->doc_no;
                $verifyUserArr['document_name'] = $arrDocument[$obj2->doc_no];
                $verifyUserArr['update_document_link'] = route('login_open');
                
                Event::dispatch("user.email.documentExpireReminder", serialize($verifyUserArr));
            }
        }

        // Utility Bill Monitoring
      
        /* if($utilityBillList && $utilityBillList->count()){
          foreach($utilityBillList as $obj1){
          $expiry_date    =   $obj1->expire_date;

          $days = Helpers::dateDiffInDays($today,$days);

          // reminder history update

          $inputData  =   [];
          $inputData['reminder_for']  =   'Passport Expiry reminder';
          $inputData['reminder_type'] =   'PassportExpiry';
          $inputData['user_kyc_id']   =   $obj1->user_kyc_id;
          $inputData['user_id']       =   $obj1->user_id;
          $inputData['status']        =   1;
          $inputData['message']       =   '<p>passport will expire in '.$days.' days. Upload a new document in your registered profile with Dexter Capital Financial Consultancy LLC Compliance Platform. After the expiry date of the passport your account will be locked.</p>';

          $this->userRepo->saveReminder($inputData);

          // sending reminder e-mail for renew expiray date

          $verifyUserArr['name'] = $obj1->f_name . ' ' . $obj1->l_name;
          $verifyUserArr['days'] = $days;
          $verifyUserArr['email'] = $obj1->email;
          $verifyUserArr['document_type'] = 1;
          $verifyUserArr['document_name'] = $arrDocument[$obj1->document_type];
          $verifyUserArr['update_document_link'] = route('login_open');

          Event::dispatch("user.email.documentExpireReminder", serialize($verifyUserArr));
          }
          } */



        // Company trade license Monitoring
        /* if($tradeLicenseList && $tradeLicenseList->count()){
          foreach($tradeLicenseList as $obj3){
          // sending reminder e-mail for renew expiray date

          $expiry_date    =   $obj3->expire_date;

          $days = Helpers::dateDiffInDays($today,$days);

          // reminder history update

          $inputData  =   [];
          $inputData['reminder_for']  =   'Company registration Expiry reminder';
          $inputData['reminder_type'] =   'CompanyTradeLicenseExpiry';
          $inputData['user_kyc_id']   =   $obj2->user_kyc_id;
          $inputData['user_id']       =   $obj2->user_id;
          $inputData['status']        =   1;
          $inputData['message']       =   'Dear '.$obj3->f_name . ' ' . $obj3->l_name.', please update company registration certificate of '.$obj3->corp_name.'. Upload a new document in the registered profile with Dexter Capital Financial Consultancy LLC Compliance Platform. The company’s account will be locked after '.$days.' days in case of failure to update the required document.';

          $this->userRepo->saveReminder($inputData);

          $verifyUserArr['name'] = $obj3->f_name . ' ' . $obj3->l_name;
          $verifyUserArr['days'] = $days;
          $verifyUserArr['email'] = $obj3->email;
          $verifyUserArr['document_type'] = 3;
          $verifyUserArr['document_name'] = $arrDocument[$obj3->document_type];
          $verifyUserArr['update_document_link'] = route('login_open');

          Event::dispatch("user.email.documentExpireReminder", serialize($verifyUserArr));
          }
          } */
    }

}
