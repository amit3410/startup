<?php

namespace App\Http\Controllers\Api;

use Session;
use Validator;
use Redirect;
use Route;
use Auth;
use Event;
use Helpers;
use PDF;
use DateTime;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileFormRequest;
use App\Http\Requests\ChangePasswordFormRequest;
use App\Http\Requests\BackendUserFormRequest;
use App\Inv\Repositories\Models\Master\Country;
use App\Inv\Repositories\Contracts\Traits\ApiAccessTrait;
use App\Inv\Repositories\Contracts\UserInterface as InvUserRepoInterface;
use App\Inv\Repositories\Contracts\ApplicationInterface as InvAppRepoInterface;
use App\Inv\Repositories\Contracts\WcapiInterface as WcapiRepoInterface;


class UserController extends Controller {

    /**
     * User repository
     *
     * @var object
     */
    protected $userRepo;

    /**
     * Application repository
     *
     * @var object
     */
    protected $application;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(InvUserRepoInterface $user, InvAppRepoInterface $application, WcapiRepoInterface $wcapi) {

        $this->userRepo = $user;
        $this->application = $application;
        $this->wcapi = $wcapi;
       
        //$this->middleware('checkBackendLeadAccess');
    }

use ApiAccessTrait;

    
   

    

    


    

    /*
     * show all Corporate users
     * 
     */

    public function updateUserStatus(Request $request) {
        $userId = $request->get('user_id');
        $current_status = $request->get('current_status');
        $curouteName = $request->get('curouteName');
        if ($current_status == '1') {
            $arrData['is_active'] = 0;
        } else {
            $arrData['is_active'] = 1;
        }
        $updateUserDate = $this->userRepo->save($arrData, $userId);
        //mail sent to Indi and corp
        if ($current_status == '1') {

            $userArr = $this->userRepo->find($userId, ['email', 'first_name', 'last_name', 'user_type']);
            $verifyUserArr = [];
            $verifyUserArr['name'] = $userArr->f_name . ' ' . $userArr->l_name;
            $verifyUserArr['email'] = $userArr->email;
            $verifyUserArr['usertype'] = $userArr->user_type;
            Event::dispatch("user.email.indi_corp_unlock_account", serialize($verifyUserArr));
        }

        Session::flash('message', 'Status has been updated successfully.');
        return redirect(route($curouteName));
    }

   

  

    

    

    public function sendtotradingUser(Request $request) {
        try {

            
            $data = $request->all();
            
            $userId = $request->get('id');
            $userArr = $this->userRepo->find($userId, ['email', 'f_name', 'm_name','l_name', 'user_type','phone_no']);
            if($request->post('email')) {
                 $userName = $this->changeuserName($userArr['f_name'], $userArr['l_name'], $userArr['phone_no']);

                 $string = Helpers::randomPassword();
                 $userData['username']  = $userName;
                 $userData['password']  = $string;
                 $userData['email']     = $userArr['email'];
                 $responceArray = $this->createCredentails($userData); // call dexter compline api


                $userMailArr['name'] = $userArr->f_name . ' ' . $userArr->l_name;
                $userMailArr['email'] = $userArr->email;
                $userMailArr['username'] = $userName;
                $userMailArr['password'] = $string;
                $userMailArr['user_type'] = $userArr->user_type;

                if($userArr->user_type== 2)
                {
                    $userCorp = $this->userRepo->getcorpDetail($userId);
                    $userMailArr['corp_name'] = $userArr->company_name;
                }
                Event::dispatch("Trading.user.registered", serialize($userMailArr));

                $responce = json_decode($responceArray);
               
               // if($responce->result == "yes") {
                    //Event::dispatch("user.registered", serialize($userMailArr));
                    Session::flash('is_accept', 1);
                    return redirect()->back();
              // } 

//exit;


                //echo "debug"; exit;
               // $route = route("individual_user");
               // return redirect($route);
                //$password ="";
                //
            }
            return view('backend.sendtotradinguser')
                        ->with(['userId' => $userId])
                        ->with(['userArr' => $userArr]);
        } catch (Exception $exc) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($exc))->withInput();
        }
    }

    public function individualApiAction(Request $request) {

        try {
            $userId = \Auth::user()->user_id;
            $user_kyc_id = $request->get('id');
            $profileId = $request->get('profileId');

            $responceProfileId = $request->get('responceProfileId');
            $kycCompleteArray = Helpers::getKycDetailsByKycID($user_kyc_id);
            $userData = $kycCompleteArray->user_id;
            $is_byCompany = $kycCompleteArray->is_by_company;
            if ($is_byCompany == 1) {
                $userType = 1;
            } else {
                $userType = $this->application->getUserType($userData);
            }


            $dataKycUpdate = [];
            $dataKycUpdate['is_api_pulled'] = 1;
            $dataKycUpdate['is_approve'] = 1;
            $dataKycUpdate['updated_by'] = (int) $userId;

            // Update in world check respoonce table
            $dataKycUpdateWc = [];
            $dataKycUpdateWc['is_approved'] = 1;
            $dataKycUpdateWc['is_final'] = 1;
            $dataKycUpdateWc['updated_by'] = (int) $userId;
            Session::flash('is_accept', 1);
            /// Update Report here
            if ($userType == 1) {
                $this->updateReport($user_kyc_id);
            }
            /// Update Report here
            // echo "===>".$user_kyc_id."==>".$profileId."==>".$responceProfileId; exit;
            $response = $this->application->updateUserKyc($user_kyc_id, $dataKycUpdate);
            $responsewc = $this->wcapi->updateUserKycWC($user_kyc_id, $profileId, $responceProfileId, $dataKycUpdateWc);
            // Send Email to Users

            return view('backend.individual_wcapi_action')
                            ->with(['userKycId' => $user_kyc_id])
                            ->with(['profileId' => $profileId])
                            ->with(['responceProfileId' => $responceProfileId])
                            ->with(['userData' => $userData])
                            ->with(['userType' => $userType]);
        } catch (Exception $exc) {
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($exc))->withInput();
        }
    }

    
 ////make userName
    public function changeuserName($fistName, $lastName, $phone) {


        $fistNameNew = substr($fistName, 0, 3);
        $lastNameNew = substr($lastName, 0, 3);
        $phoneNew = substr($phone, 6, 4);
        $userName = $fistNameNew . $lastNameNew . $phoneNew;
        $user = $this->checkUserNameExit($userName);

        if($user) {

              $randNo=substr(rand(0,$phone),0, 1);
              $userName=$user->username.$randNo;
              $this->checkUserNameExit($userName);

        }

        return ucfirst($userName);


    }

     public function checkUserNameExit($username)
    {
        $result = \App\Inv\Repositories\Models\User::where('username', $username)->first();

        return $result ?: false;
    }
    


/////// API  calling for user detail///

    /*
     * show user Details
     * @param Request $request
     */

    public function detail(Request $request) {

        try {

            
            $assesmentDetail    = [];
            $arrAssesmentRating = [];
            $assesmentOldData   = [];
            $arrRankName        = "";
            //$user_id = (int) $request->get('user_id');
            $email   = $request->get('email');
            //echo "==>".$email; //exit;
            $userArray  = $this->userRepo->getUserByEmailforAPI($email);
           // dd($userArray);

            $userId =$userArray->user_id;
            //echo "==>".$userId; exit;
            $getuserData = $this->userRepo->getUserPrimaryId($userId);
            //dd($getuserData);
            $user_id = $getuserData->user_id;
            if ($user_id !='') {
                $userKycId = $this->application->getUserKycid($user_id);
            }


            $arrAssesmentRating = [];
            $rank_decision = '';
            //echo "==>".$user_id; exit;
            if ($user_id > 0) {
                $userData = $this->userRepo->getUserDetail($user_id);
                //$userKycId = 98;
                $userByComapnyId = $user_id;
            } else {
                $userData = false;
                $userByComapnyId = $corp_user_id;
            }



            ///get user Assgement Data

            $assesmentData = $this->userRepo->getAssesmentData($userKycId, 1);
            $assesmentOldData = $this->userRepo->getAssesmentData($userKycId, 0);

            if ($assesmentData && $assesmentData->count()) {
                $arrRankName = Helper::getRankNames();
                $rank_decision = $arrRankName[$assesmentData[0]->avg_rank];
            } else {
                $arrRankName = [];
            }

            $assesmentDetail = $this->userRepo->getRiskAssesmentRating($userKycId);


            $documentsData = $this->userRepo->getDocumentTypeInfo($userKycId);
            //dd($documentsData);

            $socialmediaData = $this->userRepo->getSocialmediaInfo($userKycId);
            $userPersonalData = $this->userRepo->getUseKycPersonalData($userKycId);
            $familyData = $this->userRepo->getFamilyInfo($userKycId);
            $residentialData = $this->userRepo->getResidentialInfo($userKycId);



            $professionalData = $this->userRepo->getProfessionalInfo($userKycId);

            $otherDocsArray = $this->application->otherReqDocument($userKycId);
            //$commercialData     =   $this->userRepo->getCommercialInfo($userKycId);
            $commercialData = $this->userRepo->getCommercialInfo($userKycId);
            $financialData = $this->userRepo->getFinancialInfo($userKycId);
            $bussAddrData = $this->userRepo->getBussAddrInfo($userKycId);
            $documentArray = $this->application->corporateDocument($userKycId);
            $kycDetail = $this->wcapi->getWcapiDetail($userKycId, 'screeningRequest');
            $kycpersonalDetail = $this->wcapi->getWcapiPersonalDetailFinal($userKycId, 'profile');
            $kycCompleteArray = Helpers::getKycDetailsByKycID($userKycId);
            $isKycComplete = isset($kycCompleteArray) ? $kycCompleteArray['is_kyc_completed'] : 0;
            $isKycApprove = isset($kycCompleteArray) ? $kycCompleteArray['is_finalapprove'] : 0;


            /* User Type */
            $is_byCompany = $kycCompleteArray->is_by_company;
            if ($is_byCompany == 1) {
                $userType = 1;
            } else {
                $userType = $this->application->getUserType($kycCompleteArray->user_id);
            }



            if ($documentsData) {
                $userPassportNumber = $this->getPassportNumber($documentsData);
            } else {
                $userPassportNumber = '';
            }


            //echo "<pre>";
            // print_r($kycpersonalDetail); exit;
            if ($kycpersonalDetail) {
                $wcMatch = $kycpersonalDetail->match_strenght;
                $wcpep = $kycpersonalDetail->pep;
                $wcPassport = $kycpersonalDetail->passport;
                //$userPassportNumber = $documentsData->
            } else {
                $wcMatch = '';
                $wcpep = '';
                $wcPassport = '';
            }

            //$userScoreData = [];
            $arrCountry = Helpers::getCountryDropDown();
            $userCountryID = isset($residentialData->country_id) ? $residentialData->country_id : '';
            if ($userCountryID) {
                $userCountry = $arrCountry[$userCountryID];
            } else {
                $userCountry = 101;
            }

            ///User Kyc Detail
            $bussAddrData = $this->userRepo->getBussAddrInfo($userKycId);

            
            $data = response()->json(['userData' => $userData,
                            'userPersonalData' => $userPersonalData, 'documentsData' => $documentsData,
                            'socialmediaData' => $socialmediaData, 'familyData' => $familyData,
                            'residentialData' => $residentialData, 'professionalData' => $professionalData,
                            'commercialData' => $commercialData, 'financialData' => $financialData, 'bussAddrData' => $bussAddrData,
                            'userKycId' => $userKycId, 'documentArray' => $documentArray, 'kycDetail' => $kycDetail, 
                            'arrAssesmentRating' => $arrAssesmentRating, 'rank_decision' => $rank_decision,
                            'assesmentDetail' => $assesmentDetail, 'isKycComplete' => $isKycComplete, 'isKycApprove' => $isKycApprove,
                            'kycCompleteArray' => $kycCompleteArray, 'assesmentOldData' => $assesmentOldData,
                            'userCountry' => $userCountry, 'kycpersonalDetail' => $kycpersonalDetail,
                            'arrRankName' => $arrRankName, 'userType' => $userType, 'userByComapnyId' => $userByComapnyId,
                            'otherDocsArray' => $otherDocsArray]);
                //echo "===>".count($data); exit;
                //dd($data);
                return $data;
            
        } catch (Exception $exc) {
            dd(Helpers::getExceptionMessage($exc));
            return redirect()->back()->withErrors(Helpers::getExceptionMessage($exc))->withInput();
        }
    }

//////API calling end

    public function getPassportNumber($documentsData) {
        $passportNumber     = "";
        $passportexpNumber  = "";

        foreach ($documentsData as $Array) {

            if ($Array->doc_id == 18) {
                $passportNumber = $Array->document_number;
                $passportexpNumber = $Array->expire_date;
            }
        }
        return $passportNumber."#".$passportexpNumber;
    }

}
