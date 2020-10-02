@extends('layouts.admin')

@section('content')
<?php
$registration_date = '';
$TotalScore = 0;
$averageScore = 0;
?>
<div class="col-md-12 dashbord-white">
    <div class="form-section">
        @if(Session::has('message'))
        <div class=" my-alert-success alert base-reverse alert-dismissible" role="alert" style="background: #743939de;color: #fff;"> <span><i class="fa fa-bell fa-lg" aria-hidden="true"></i></span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
            {{ Session::get('message') }}
        </div>
        {{Session::forget('message')}}
        @endif
        <div class="breadcrumbs">
            <div class="d-md-flex mb-3">
                <div class="breadcrumbs-bg">
                    <ul>
                        <li>
                            <a onclick="window.location.href = '{{ route('corporate_user') }}'"> Manage Users</a>
                        </li>
                        <li>
                            Corporate User Details
                        </li>


                          <?php if($rank_decision!=''){?>
                         <li>
                            <span class='btn btn-bg-green'>{{$rank_decision}}</span>
                        </li>
                      <?php }?>
                    </ul>
               </div>
                @php
                $arrCountry     =   Helpers::getCountryDropDown();
                @endphp
                 @if($kycCompleteArray)
                @php
                    $bounty = Helpers::getKycDetails($kycCompleteArray->user_id);
                    if(isset($bounty) && $bounty['is_approve']==1 && $bounty['is_api_pulled']==1) {
                     $boun_appr = 'Approved';
                     $boun_appr_status = 'btn-approved';
                    } else if(isset($bounty) && $bounty['is_approve']==2 && $bounty['is_api_pulled']==1)  {
                     $boun_appr = 'Disapproved';
                     $boun_appr_status = 'btn-disapproved';

                    } else {
                     $boun_appr = 'No Action';
                     $boun_appr_status = 'btn-disapproved';
                    }
                @endphp
                @endif

                
                <div class="ml-md-auto d-flex form action-btns">
                <?php
                    if($kycCompleteArray->is_finalapprove == 1) {
                        
                    ?>
                    <button type="button" class="btn btn-default btn-sm btn-approved" >Approved</button>
                      <?php
                    } else if($kycCompleteArray->is_finalapprove == 2) {
                    ?>
                    <button type="button" class="btn btn-default btn-sm btn-disapproved" >Decline</button>
                    <?php } else if(($kycCompleteArray->is_finalapprove == 0 || $kycCompleteArray->is_finalapprove == 3) && $kycCompleteArray->is_kyc_completed == 1){ ?>
                     <button type="button" class="btn btn-default btn-sm btn-approved" data-toggle="modal" data-target="#btn-approved-iframe"  data-url="{{route('individual_final_approve',['id'=>$userKycId])}}" data-height="95px" data-width="100%" data-placement="top">Approve</button>
                     <button type="button" class="btn btn-default btn-sm btn-disapproved" data-toggle="modal" data-target="#btn-disapproved-iframe"  data-url="{{route('individual_final_disapprove',['id'=>$userKycId])}}" data-height="95px" data-width="100%" data-placement="top">Decline</button>
                     <?php
                    }
                ?>


                    <!--<button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#Approved_Action"></button>-->
                    <!-- <button type="button" class="btn btn-default btn-sm btn-disapproved">Disapproved</button>-->
                    <!-- <button type="button" class="btn btn-default btn-sm btn-locked">Lock</button>-->
                </div>
            </div>
        </div>
       



        <div class="tabs-section">

            <ul class="nav nav-tabs tabs-cls">
                <li class="nav-item active">
                    <a class="nav-link parent-tab active tab001" data-toggle="tab" href="#tab01">Registration Details</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link parent-tab tab002" data-toggle="tab" href="#tab02">User KYC Details</a>
                </li>
                <!--<li class="nav-item">
                    <a class="nav-link parent-tab" data-toggle="tab" href="#tab03">Third Party Details</a>
                </li>-->
                
                <li class="nav-item">
                    <a class="nav-link parent-tab tab006" data-toggle="tab" href="#tab06" id="worldcheckapi">World Check Data</a>
                </li>
                


                <li class="nav-item">
                    <a class="nav-link parent-tab tab004" data-toggle="tab" href="#tab04">Documents</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link parent-tab tab007" data-toggle="tab" href="#tab07">Additional Document</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link parent-tab" data-toggle="tab" href="#tab05" id="ProfileScore">Profile Score</a>
                </li>
                <?php if($companyProfile && $companyProfile->count()) {?>
                <li class="nav-item">
                    <a class="nav-link parent-tab CompleteReport tab008" data-toggle="tab" href="#tab08" id="ComplianceReport">Compliance Report</a>
                </li>
                <?php } ?>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane container active" id="tab01">
                    <div class="row mb-4">
                        <div class="col-md-6 view-detail">
                            <label>Country</label>
                            <span>{{(isset($userData->country_id))?$arrCountry[$userData->country_id]:''}}</span>
                        </div>

                        <div class="col-md-6 view-detail">
                            <label>Company Name</label>
                            <span>{{isset($userData->corp_name)? ucfirst($userData->corp_name):''}}
                             <?php
                                $corpName     = "";
                                $corpCountry  = "";
                                $corpName     = isset($userData->corp_name) ? $userData->corp_name: '';
                                $corpCountry  = isset($userData->country_id) ? $userData->country_id : '';
                                ?>
                            </span>
                        </div>
                    </div>
                    <?php
                    $emailVarified = '';
                    $mobileVarified = '';
                    if($userData->is_email_verified) {
                        if($userData->is_email_verified == 1) {
                            $emailVarified = '<i class="fa fa-check-square green-color"></i>';
                        } else {
                           $emailVarified = '<i class="fa fa-times red-color"></i>';
                        }
                    } else {
                           $emailVarified = '<i class="fa fa-times red-color"></i>';
                        }

                    if($userData->is_otp_verified) {
                        if($userData->is_otp_verified == 1) {
                            $mobileVarified = '<i class="fa fa-check-square green-color"></i>';
                        } else {
                           $mobileVarified = '<i class="fa fa-times red-color"></i>';
                        }
                    } else {
                        $mobileVarified = '<i class="fa fa-times red-color"></i>';
                    } ?>
                    @php
                    $date_of_formation=isset($userData->corp_date_of_formation) ? Helpers::getDateByFormat($userData->corp_date_of_formation, 'Y-m-d', 'd/m/Y') :'';
                    @endphp
                    <div class="row mb-4">
                        <div class="col-md-6 view-detail">
                            <label>Company Date of Formation</label>
                            <span>{{$date_of_formation}}</span>
                        </div>
                        <div class="col-md-6 view-detail">
                            <label>Company Trade License Number</label>
                            <span>{{isset($userData->corp_license_number)?$userData->corp_license_number:''}}</span>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="heading col-md-12">
                            <h4>Comapny Authorised Signatory</h4>
                        </div>

                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6 view-detail">
                            <label>First Name</label>
                            <span>{{isset($userData->f_name) ? $userData->f_name:''}}</span>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6 view-detail">
                            <label>Middle Name</label>
                            <span>{{isset($userData->m_name) ? $userData->m_name : ''}}</span>
                        </div>
                        <div class="col-md-6 view-detail">
                            <label>Last Name</label>
                            <span>{{isset($userData->l_name) ? $userData->l_name : ''}}</span>
                        </div>
                    </div>
                    @php
                    $date_of_birth = isset($userData->date_of_birth) ? Helpers::getDateByFormat($userData->date_of_birth, 'Y-m-d', 'd/m/Y') :'';
                    @endphp
                    <div class="row mb-4">
                        <div class="col-md-6 view-detail">
                            <label>Authorized Signatory Date of Birth</label>
                            <span>{{$date_of_birth}}</span>
                        </div>
                        <div class="col-md-6 view-detail">
                            <label>Official Email Address</label>
                            <span>{{isset($userData->email) ? $userData->email:''}} &nbsp;&nbsp;<?php echo  $emailVarified;?></span>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6 view-detail">
                            <label>Official Mobile Number</label>
                            <span> {{'(+'.$userData->country_code.')'}}&nbsp; {{isset($userData->phone_no) ? $userData->phone_no:''}} &nbsp;&nbsp;<?php echo  $mobileVarified;?></span>
                        </div>
                    </div>
                </div>

                <div class="tab-pane container" id="tab02">

                    <ul class="nav nav-pills tabs-cls">
                        <li class="nav-item active">
                            <a class="nav-link sub-tab active" data-toggle="pill" href="#subtab01">Company Detail</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link sub-tab" data-toggle="pill" href="#subtab02">Address Details</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link sub-tab" data-toggle="pill" href="#subtab03">Shareholding Structure</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link sub-tab" data-toggle="pill" href="#subtab04">Financial Information</a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        @if($companyProfile)
                        <div class="tab-pane container active" id="subtab01">
                            <div class="row mb-4">
                                <div class="col-md-12 view-detail">
                                    <label>Name of the Company</label>
                                    <span>{{isset($companyProfile->company_name) ? $companyProfile->company_name : ''}}</span>
                                </div>

                            </div>   
                            <div class="row mb-4">
                                <div class="col-md-12 view-detail">
                                    <label>Name of the Customer: (as per Certificate of Incorporation/ Registration)</label>
                                    <span>{{isset($companyProfile->customer_name) ? $companyProfile->customer_name : ''}}</span>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-6 view-detail">
                                    <label>Registration Number</label>
                                    <span>{{isset($companyProfile->registration_no) ? $companyProfile->registration_no : ''}}</span>
                                </div>
                                <div class="col-md-6 view-detail">
                                    <label>Registration Date</label>

                                <span> 
                                    <?php
                                        $registration_date= isset($companyProfile->registration_date)?$companyProfile->registration_date:'';
                                       
                                        $date= isset($registration_date)?date('d/m/Y',strtotime($registration_date)):'';
                                     ?>
                                    {{$date}}
                                </span>

                                </div>
                            </div>
                            <div class="row mb-4">
                                @if($companyProfile->status!= '8')
                                <div class="col-md-6 view-detail">
                                    <label>Status</label>
                                <span>{{Helpers::getCompanyDetailStatus($companyProfile->status,$companyProfile->user_kyc_id)}}</span>
                                </div>
                                @endif

                                @if($companyProfile->status == '8')
                                <div class="col-md-6 view-detail">
                                    <label>Other Status</label>
                                <span>{{Helpers::getCompanyDetailStatus($companyProfile->status,$companyProfile->user_kyc_id)}}</span>
                                </div>
                                @endif

                            </div>
                            <div class="row mb-4">
                                <div class="col-md-12 view-detail">
                                    <label>Nature of Business</label>
                                    <p>{{isset($companyProfile->business_nature) ? $companyProfile->business_nature : ''}}</p>
                                </div>
                            </div>

                            @if($socialmediaData && $socialmediaData->count())
                            @foreach($socialmediaData as $objSocial)

                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label>Social Media</label>
                                     @if($objSocial->socialmedia!='Other')
                                    <span>{{$objSocial->socialmedia}}</span>
                                     @else
                                        <span>{{$objSocial->social_media_other}}</span>    
                                    @endif
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Social Media Link</label>
                                    <span>
                                        <?php if($objSocial->social_media_link) {?>
                                        <a href="<?php echo $objSocial->social_media_link?>" target="_blank"><?php echo $objSocial->social_media_link?></a>
                                        <?php }?>


                                    </span>

                                </div>
                            </div>
                            @endforeach
                            @endif


                        </div>
                        @else
                        <div class="tab-pane container active" id="subtab01">
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h4>Pending</h4>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($companyAddress)
                        <div class="tab-pane container" id="subtab02">
                            <div class="row mb-4">
                                <div class="heading col-md-12">
                                    <h4>Permanent/Registered Address:</h4>
                                </div>

                            </div>

                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label> Country </label>
                                    <span> {{isset($companyAddress->country_id)?$arrCountry[$companyAddress->country_id]:''}} </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label> City </label>
                                    <span> {{isset($companyAddress->city_id) ? $companyAddress->city_id: ''}} </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label> Region </label>
                                    <span> {{isset($companyAddress->region) ? $companyAddress->region : ''}} </span>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label> Building </label>
                                    <span>{{isset($companyAddress->building) ? $companyAddress->building : ''}} </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label> Floor </label>
                                    <span> {{isset($companyAddress->floor) ? $companyAddress->floor : ''}} </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label> Street </label>
                                    <span> {{isset($companyAddress->street) ? $companyAddress->street : ''}} </span>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label> Postal Code </label>
                                    <span> {{isset($companyAddress->postal_code) ? $companyAddress->postal_code : ''}} </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>P.O Box </label>
                                    <span> {{isset($companyAddress->po_box) ? $companyAddress->po_box : ''}} </span>
                                </div>

                            </div>
                            <div class="row mb-4">

                                <div class="col-md-3 view-detail">
                                    <label>Email </label>
                                    <span> {{isset($companyAddress->email) ? $companyAddress->email : ''}} </span>
                                </div>

                            </div>
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label> Telephone No. </label>
                                    <span> {{"( ".$companyAddress->area_code." )"}}
                                        {{isset($companyAddress->telephone) ?  $companyAddress->telephone : ''}} </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Mobile No. </label>
                                    <span> {{"( ".$companyAddress->country_code." )"}}
                                        {{isset($companyAddress->mobile) ? $companyAddress->mobile : ''}} </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Fax No. </label>
                                    <span>{{ ($companyAddress->fax_countrycode ) ? "( ".$companyAddress->fax_countrycode." )" : ''}}
                                        {{isset($companyAddress->fax) ? $companyAddress->fax : ''}}</span>
                                </div>

                            </div>

                            <div class="row mb-4">
                                <div class="heading col-md-12">
                                    <h4>Address for Correspondence</h4>
                                </div>

                            </div>

                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label> Country </label>
                                    <span> {{isset($companyAddress->corre_country)?$arrCountry[$companyAddress->corre_country]:''}} </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label> City </label>
                                    <span> {{isset($companyAddress->corre_city) ? $companyAddress->corre_city: ''}} </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label> Region </label>
                                    <span> {{$companyAddress->corre_region}} </span>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label> Building </label>
                                    <span>{{$companyAddress->corre_building}} </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label> Floor </label>
                                    <span> {{$companyAddress->corre_floor}} </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label> Street </label>
                                    <span> {{isset($companyAddress->corre_street) ? $companyAddress->corre_street : ''}} </span>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label> Postal Code </label>
                                    <span> {{$companyAddress->corre_postal_code}} </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>P.O Box </label>
                                    <span> {{$companyAddress->corre_po_box}} </span>
                                </div>

                            </div>
                            <div class="row mb-4">

                                <div class="col-md-3 view-detail">
                                    <label>Email </label>
                                    <span> {{$companyAddress->corre_email}} </span>
                                </div>

                            </div>
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label> Telephone No. </label>
                                    <span> {{ ($companyAddress->corre_area_code)  ? "( ".$companyAddress->corre_area_code." )" : '' }} {{$companyAddress->corre_telephone}} </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Mobile No. </label>
                                    <span>{{ ($companyAddress->corre_country_code) ? "( ".$companyAddress->corre_country_code." )" : ''}} {{$companyAddress->corre_mobile}} </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Fax No. </label>
                                    <span>{{ ($companyAddress->corre_fax_countrycode) ? "( ".$companyAddress->corre_fax_countrycode." )" : '' }} {{$companyAddress->corre_fax}}</span>
                                </div>
                            </div>
                            </div>
                        @else
                        <div class="tab-pane container" id="subtab02">
                            <div class="row mb-4">
                                <div class="col-md-12"><h4>Pending</h4></div>
                            </div>
                        </div>
                        @endif

                        <div class="tab-pane container" id="subtab03">
                            <div class="row marB10 marT20">
                                <div class="table-responsive">
                                    <table class="table table-ultimate">
                                        <thead>
                                            <tr>
                                                <th>{{trans('forms.shareholding_beneficiary.Label.th_name')}}</th>
                                                <th>{{trans('forms.shareholding_beneficiary.Label.th_sharehol')}}</th>
                                                <th>{{trans('forms.shareholding_beneficiary.Label.th_status')}}</th>
                                                <th>{{trans('forms.shareholding_beneficiary.Label.th_actions')}}</th>
                                                <th>
                                                {{trans('forms.shareholding_beneficiary.Label.th_profile_score')}}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            @if($beficiyerArray && $beficiyerArray->count())
                                            @foreach($beficiyerArray as $obj)
                                            <?php
                                            $kycStatusVar = '';
                                            $kycStatus = Helpers::getKycDetailsByKycID($obj->owner_kyc_id);
                                            if($kycStatus->is_kyc_completed == 1) {
                                                $kycStatusVar = "Completed";
                                            } else {
                                                $kycStatusVar = "Pending";
                                            }
                                            ?>
                                            <tr>
                                                <td>{{$obj->company_name}}</td>
                                                <td>{{$obj->actual_share_percent}} {{trans('forms.shareholding_beneficiary.Label.sym')}}</td>
                                                <td><a href="javascript::void();" class="kyc-pending">
                                                        {{$kycStatusVar}}
                                                    </a></td>
                                                <td>@if($obj->actual_share_percent>=5)<a href="{{route("user_detail",['user_id' => @$obj->user_id,'user_type' =>'2','corp_user_kyc_id'=>$benifinary['userKycId'],'user_kyc_id'=>$obj->owner_kyc_id,'is_by_company'=>'1'])}}" class="kyc-color">View Detail</a>@else {{trans('forms.shareholding_beneficiary.Label.is_kyc')}} @endif</td>
                                                <td>
                                                    <?php
                                                    $assesmentData1 = Helpers::getAssesmentRankData($obj->owner_kyc_id, 1);
                                                    if ($assesmentData1 && $assesmentData1->count()) {
                                                        $arrRankName = Helpers::getRankNames();
                                                        //echo "<pre>";
                                                        //print_r($assesmentData); exit;
                                                        //echo "==>".$assesmentData[0]->avg_rank;exit;
                                                        $rank_decision = $arrRankName[$assesmentData1[0]->avg_rank];
                                                    } else {
                                                        $arrRankName = [];
                                                        $rank_decision = "--";
                                                    }
                                                    echo $rank_decision;
                                                    ?>

                                                </td>
                                            </tr>
                                            @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                                
                        @if($companyFinancial)
                        <div class="tab-pane container" id="subtab04">
                            <div class="row mb-4">
                                <div class="col-md-6 view-detail">
                                    <label>Total debts in USD</label>
                                    <span>$ {{$companyFinancial->total_debts_usd}}</span>
                                </div>
                                <div class="col-md-6 view-detail">
                                    <label>Total cash in banks in USD</label>
                                    <span>$ {{$companyFinancial->total_cash}}</span>
                                </div>

                            </div>
                            <div class="row mb-4">
                                <div class="col-md-6 view-detail">
                                    <label>Total payables in USD</label>
                                    <span>$ {{$companyFinancial->total_payable_usd}}</span>
                                </div>
                                <div class="col-md-6 view-detail">
                                    <label>Total receivables in USD</label>
                                    <span>$ {{$companyFinancial->total_receivables_usd}}</span>
                                </div>

                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6 view-detail">
                                    <label>Total salaries in USD</label>
                                    <span>$ {{$companyFinancial->total_salary_usd}}</span>
                                </div>
                                <div class="col-md-6 view-detail">
                                    <label>Yearly turnover in USD</label>
                                    <span>$ {{$companyFinancial->yearly_usd}}</span>
                                </div>

                            </div>

                            <div class="row mb-4">
                                 <div class="col-md-6 view-detail">
                                    <label>Yearly profit in USD</label>
                                    <span>$ {{$companyFinancial->yearly_profit_usd}}</span>
                                </div>
                                <div class="col-md-6 view-detail">
                                    <label>Capital of the company in USD</label>
                                    <span>$ {{$companyFinancial->capital_company_usd}}</span>
                                </div>
                            </div>

                        </div>
                        @else
                        <div class="tab-pane container" id="subtab04">
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h4>Pending</h4>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="tab-pane container p-0 mt-3" id="tab03">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                           <thead>
                                <tr>
                                    <th width="30%">Company Name</th>
                                    <th width="30%">Company Date of Formation</th>
                                    <th width="40%">Country</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{isset($userData->corp_name)?$userData->corp_name:''}}</td>
                                    <td>{{$date_of_formation}}</td>
                                    <td><span class="text-requested">{{isset($userData->country_id)?$arrCountry[$userData->country_id]:''}}</span></td>
                                    <td class="text-right">
                                        <button type="button" class="getSimilarWCI"  id="getSimilarWCI">Get Similar</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!--Similar records  Start-->
                    <?php
                        if(isset($kycDetail) && $kycDetail!= null ) {
                        $response   = $kycDetail['api_responce'];
                        $usersKycId = $kycDetail['user_kyc_id'];
                        $primaryId  = $kycDetail['wcapi_req_res_id'];
                        $dataArray  = json_decode($response);
                        $i = 0;
                    ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th width="20%">Name</th>
                                    <th width="15%">Date of Formation</th>
                                    <th width="15%">Company Type</th>
                                    <th width="15%">Country</th>
                                    <th width="15%">Risk</th>
                                    <th class="text-right" width="20%">Actions</th>
                                </tr>
                            </thead>

                            
                        </table>
                    </div>
                    <?php } else { ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th width="15%">Name</th>
                                    <th width="15%">Date of Formation</th>
                                    <th width="20%">Company Type</th>
                                    <th width="20%">Country</th>
                                    <th width="20%">Risk</th>
                                    <th class="text-right" width="10%">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="similarRecords">
                           </tbody>
                        </table>
                    </div>
                    <?php } ?>
                    <!--Similar records End-->
                </div>



<!-- World check similar record Data -->
    <div class="tab-pane container p-0 mt-3" id="tab06">
        <div class="col-md-12 text-right mb-3">
            <a class="btn btn-save btn-sm " data-toggle="modal" data-target="#searchCases" data-url="{{route('individual_searchcase',['id' => $userKycId,'searchfor' => 'corporate'])}}" data-height="180px" data-width="80%" data-placement="top">Search Cases</a>
            <a class="btn btn-sm btn-save" data-toggle="modal" data-target="#corpwcapi" data-url="{{route('corp_api_popup',['id'=>$userKycId])}}" data-height="300px" data-width="100%" data-placement="top">
                        Get Similar </a>
        </div>

        <div class="table-responsive mb-3">
           <table class="table table-striped table-sm">
                <tbody>
                <tr>
                    <td width="30%"><b>Company Name</b></td>
                    <td width="30%"><b>Company Date of Formation</b></td>
                    <td width="20%"><b>Country</b></td>
                    <td width="100px" align="right">&nbsp;</td>
                </tr>
                <tr>
                    <td width="30%">{{isset($userData->corp_name)?$userData->corp_name:''}}</td>
                    <td width="30%">{{$date_of_formation}}</td>
                    <td width="20%">{{isset($userData->country_id)?$arrCountry[$userData->country_id]:''}}</td>
                    <td width="100px" align="right">&nbsp;</td>
                </tr>
                </tbody>
            </table>
        </div>
    <!--if data exits -->
        

<div class="tabs-section">

                    <ul class="nav nav-tabs tabs-cls">
                        <li class="nav-item active">
                            <a class="nav-link parent-tab  active" data-toggle="tab" href="#tabW01">Searched Cases</a>
                        </li>
                         <li class="nav-item">
                            <a class="nav-link parent-tab" data-toggle="tab" href="#tabW02">Resolved Cases</a>
                        </li>

                    </ul>

            <div class="tab-content">
                <div class="tab-pane container p-0 mt-3 active" id="tabW01">

                    <div class="table-responsive">
                    <table class="table table-striped table-sm brown-th">
                        <thead>

                            <tr>
                                <th width="10%">S.No</th>
                                <th width="40%">Case Id</th>
                                <th width="20%">Created By</th>
                                <th width="15%">Created Date</th>
                                <th width="15%" class="text-center">Case Report</th>
                            </tr>
                        </thead>
                        <tbody>
                    <?php

                             $profiledataArray = Helpers::getAllKycprofileDataHistory($userKycId, 'screeningRequest-corp');
                             $createdBy = "";
                              $CreatedDate = "";
                              $countvar = 1;
                              if($profiledataArray) {
                                  foreach($profiledataArray  as $profiledata) {
                                  $createdBy   = Helpers::getUserName($profiledata->by_whome);
                                  $CreatedDate = date('d/m/Y',strtotime($profiledata->created_at));

                        ?>


                            <tr>
                                <td width="10%">{{$countvar}}</td>
                                <td width="40%">{{$profiledata->caseid}}</td>
                                <td width="20%">{{$createdBy}}</td>
                                <td width="15%">{{$CreatedDate}}</td>
                                <td width="15%" align="center"><a href="{{route('similar_download',['userKycId' => $userKycId,'primaryId' => $primaryId])}}"><button type="button" class="btn btn-save btn-sm" >Download</button></a></td>
                            </tr>
                      <?php
                      $countvar++;


                      } }?>
                        </tbody>

                       </table>
                     </div>

                </div>
                <div class="tab-pane container p-0 mt-3" id="tabW02">
                    <div class="table-responsive">
                    <table class="table table-striped table-sm brown-th">
                        <thead>

                            <tr>
                                <th width="20%">S.No</th>

                                <th width="20%">Created By</th>
                                <th width="20%">Created Date</th>
                                <th width="20%">Similar Report</th>
                                <th width="20%" class="text-center">Case Report</th>
                            </tr>
                        </thead>
                        <tbody>
                    <?php

                             $profiledataArray = Helpers::getAllKycprofileDataHistory($userKycId, 'corp-profile');
                             $createdBy = "";
                              $CreatedDate = "";
                              $countvar = 1;
                              if($profiledataArray) {
                                  foreach($profiledataArray  as $profiledata) {
                                  $createdBy   = Helpers::getUserName($profiledata->by_whome);
                                  $CreatedDate = date('d/m/Y',strtotime($profiledata->created_at));
                        ?>
                            <tr>
                                <td width="20%">{{$countvar}}</td>
                                <td width="20%">{{$createdBy}}</td>
                                <td width="20%">{{$CreatedDate}}</td>
                                <td width="20%"><a href="{{route('similar_download',['userKycId' => $userKycId,'primaryId' => $primaryId])}}"><button type="button" class="btn btn-save btn-sm" >Download</button></a></td>
                                <td width="20%" align="center"><a href="{{route('report_download',['userKycId' => $userKycId,'primaryId' => $primaryId])}}" target="_blank"><button type="button" class="btn btn-save btn-sm" >Download</button></a></td>
                            </tr>
                      <?php
                      $countvar++;
                      } }?>
                        </tbody>
                       </table>
                     </div>
                </div>
            </div>
</div>
<!-- End Data Exits-->

                </div>

<!-- World Check Similar Record Data End -->

                <div class="tab-pane container  p-0 mt-3" id="tab04">
                    <div class="row mb-2 align-items-center">
                        <div class="col-md-4 view-detail pl-4">
                            <label class="m-0">Document Name</label>
                        </div>
                        <div class="col-md-4 view-detail pl-2">
                            <label class="m-0">Uploaded on</label>
                        </div>
                        <div class="col-md-4 view-detail text-right mb-0">
                            <label class="m-0">Action</label>
                        </div>
                        <!--<div class="col-md-4 view-detail text-right">
                            <button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Repull Data</button>
                        </div>-->
                    </div>

                    <div class="documents-list full-width  pl-1">
                        <ul>
                            @if($documentArray && count($documentArray))
                            @foreach($documentArray as $document)
                            <li>
                                <div class="d-flex justify-content-between mb-3">
                                    <h4 class="w-100 background-heading">{{$document['upload_doc_name']}}</h4>
                                    <!--<button type="button" class="transparent-btn">Download All <i class="fa fa-download"></i></button>-->
                                </div>
                                @php
                                $docList = [];
                                $docList=Helpers::getDocumentList($userKycId, $document['user_req_doc_id'] );
                                $docListExp=Helpers::getDocumentListExpired($userKycId, $document['user_req_doc_id'], 9 );
                                @endphp
                                
                                @if(count($docList) > 0 )
                                <table class="data-download full-width">
                                    @foreach($docList as $val)
                                    @php
                                    $docName ='';
                                    $docName = $val->doc_name.".".$val->doc_ext;
                                    $uploaded_on=date('d/m/Y',strtotime($val->created_at));
                                    @endphp


                                    @if($document['doc_no'] == 3 && $val->doc_id == 9)
                                           <tr>
                                               <td colspan="3">
                                                <table class="data-download full-width">
                                                    @if(count($docListExp) > 0)
                                                    <tr>
                                                         <td  colspan="3"><h4 class="w-100 background-heading">Expired Documents</h4></td>
                                                    </tr>
                                                    @endif

                                                    @foreach($docListExp as $valexp)
                                                    @php
                                                        $docNameExp ='';
                                                        $docNameExp = $valexp->doc_name.".".$valexp->doc_ext;
                                                        $uploaded_onExp=date('d/m/Y',strtotime($valexp->created_at));
                                                    @endphp

                                                        <tr>
                                                             <td width="33%"><span class="db_uplad-file"> {{$docNameExp}}  &nbsp; &nbsp;</span></td>
                                                             <td width="33%">{{$uploaded_onExp}}</td>
                                                             <td width="33%" align="right"><a href="{{route('download_document',['enc_id' => $valexp->enc_id,'user_type'=>'2'])}}"><button type="button" class="btn btn-save btn-sm" data-toggle="modal" data-target="#Approved_Action">Download</button></a></td>
                                                         </tr>
                                                      @endforeach
                                                 </table>
                                                </td>
                                           </tr>
                                        @endif
                                        @if(count($docListExp) > 0)
                                        <tr>
                                                <td  colspan="3"><h4 class="w-100 background-heading">New Documents</h4></td>

                                        </tr>
                                        @endif






                                    <tr>
                                        <td width="33%"><span class="db_uplad-file"> {{$docName}}  &nbsp; &nbsp;</span></td>
                                        <td width="33%">{{$uploaded_on}}</td>
                                        <td width="33%" align="right"><a href="{{route('download_document',['enc_id' => $val->enc_id,'user_type'=>'2'])}}"><button type="button" class="btn btn-save btn-sm" data-toggle="modal" data-target="#Approved_Action">Download</button></a></td>
                                    </tr>
                                    @endforeach
                                </table>
                                @endif
                            </li>
                            @endforeach
                            @endif
                        </ul>
                    </div>

                </div>
                  <div class="tab-pane container  p-0 mt-4" id="tab07">
                    <div class="col-md-12 dashbord-white">
                        <div class="documents-list full-width  pl-1">
                        <ul> <li>
                        <div class="form-section">
                            <div class="row marB10">
                                <div class="col-md-12">
                                    <h3 class="h3-headline">Other Documents Request</h3>
                                </div>
                            </div>
                            {!!
                                Form::open(
                                array(
                                'name' => 'otherDocForm',
                                'id' => 'otherDocForm',
                                'url' => route('send_corp_other_docreq',$benifinary),
                                'autocomplete' => 'off','class'=>'loginForm form form-cls'
                                ))
                            !!}
                            <div class="row">
                                <div class="col-md-8">
                                <div class="form-group">
                                    <div class="form-group form-custom-cls">
                                        @php
                                        $otherDocs=Helpers::otherDocsDropDown('2');
                                        $selectedVal=[];
                                        @endphp
                                        {{ Form::label('other_documents','Other Documents',['class'=>''])}} <span class="mandatory">*<span>
                                        <select multiple="multiple" name="other_documents[]" id="other_documents" class='form-control multiselect_dropdown'>
                                        @foreach($otherDocs as $docid=>$docname)
                                            @if(in_array($docid, $selectedVal))
                                                <option value="{{$docid}}" selected="">{{$docname}}</option>
                                            @else
                                                <option value="{{$docid}}">{{$docname}}</option>
                                            @endif
                                        @endforeach
                                        </select>
                                        <span class="text-danger">{{ $errors->first('other_documents') }}</span>
                                    </div>
                                </div>
                                </div>
                                <div class="col-md-4 marT20">
                                {{ Form::submit('Send Request',['class'=>'btn btn-send','name'=>'save_next']) }}
                                </div>
                            </div>

                        {{Form::close()}}
</div>
                                </li></ul></div>
                <div class="row marT20">
                <div class="col-md-12">

                    <div class="row mb-2 align-items-center set-label">
                        <div class="col-md-4 view-detail pl-4 mb-0">
                            <label class="m-0">Document Name</label>
                        </div>
                        <div class="col-md-4 view-detail pl-2 mb-0">
                            <label class="m-0">Uploaded on</label>
                       </div>
                        <div class="col-md-4 view-detail text-right mb-0">
                            <label class="m-0">Action</label>
                        </div>
                    </div>
                    <div class="documents-list full-width  pl-1">
                        <ul>
                            @if($otherDocsArray && count($otherDocsArray))
                            @foreach($otherDocsArray as $document)
                            <li>
                                <div class="d-flex justify-content-between mb-3">
                                    <h4 class="w-100 background-heading">{{$document['upload_doc_name']}}</h4>
                                    <!--<button type="button" class="transparent-btn">Download All <i class="fa fa-download"></i></button>-->
                                </div>
                                @php
                                $docList = [];
                                $docList=Helpers::getDocumentList($userKycId, $document['user_req_doc_id'] );
                                @endphp
                                @if(count($docList) > 0 )
                                <table class="data-download full-width">
                                    @foreach($docList as $val)
                                    @php
                                    $docName ='';
                                    $docName = $val->doc_name.".".$val->doc_ext;
                                    $uploaded_on=date('d/m/Y',strtotime($val->created_at));
                                    @endphp

                                    <tr>
                                        <td width="33%"><span class="db_uplad-file"> {{$docName}}  &nbsp; &nbsp;</span></td>
                                        <td width="33%">{{$uploaded_on}}</td>
                                        <td width="33%" align="right"><a href="{{route('download_document',['enc_id' => $val->enc_id,'user_type'=>'1'])}}"><button type="button" class="btn btn-default btn-sm btn-approved" data-toggle="modal" data-target="#Approved_Action">Download</button></a></td>
                                    </tr>
                                    @endforeach
                                </table>
                                @else
                                <table class="data-download full-width">
                                    <tr>
                                        <td width="33%"><span class="db_uplad-file"></span></td>
                                        <td width="33%"></td>
                                        <td width="33%" align="right">Pending</td>
                                    </tr>
                                </table>
                                @endif
                            </li>
                            @endforeach
                            @endif
                        </ul>
                    </div>
                    </div>
                    </div>
                    </div>
                    </div>





<div class="tab-pane container  p-0 mt-3" id="tab05">
                    <div class="col-md-12 dashbord-white">

                 <!-- Old Data Display Here Start-->

               <div class="form-section pt-0 pr-0 pl-0">

                        <div class="tab-pane container p-0 mt-0" id="tab06">
                        <?php

                         $createdBy = "";
                         $CreatedDate = "";
                         if(isset($assesmentOldData)) {



                        ?>
                        <div class="row">
                            <div class="col-md-9">
                         <div class="table-responsive">
                         <table class="table table-striped table-sm">
                             <thead>

                                 <tr>
                                     <th width="10%">S.No</th>
                                     <th width="30%">Created By</th>
                                     <th width="30%">Created Date</th>
                                     <th width="10%">Score</th>
                                     <th width="20%" class="text-right"></th>
                                 </tr>
                                </thead>
                                 <tbody>
                            <?php

                           $arrRankName = Helpers::getRankNames();

                             $count = 1;
                             if(count($assesmentData) > 0) {
                                foreach($assesmentData as $assesmentDataRow) {
                                    $score      = $assesmentDataRow->avg_rank;
                                    $org_score      = $assesmentDataRow->org_avg_rank;
                                    $createdBy   = Helpers::getUserName($assesmentDataRow->updated_by);
                                    $CreatedDate = date('d/m/Y',strtotime($assesmentDataRow->updated_at));
                                    ?>
                                        <tr>
                                            <td width="10%">{{$count}}</td>
                                            <td width="30%">{{$createdBy}}</td>
                                            <td width="30%">{{$CreatedDate}}</td>
                                            <td width="10%">{{$org_score}}</td>
                                            <td width="20%" class="text-right"></td>
                                        </tr>
                         <?php $count++;}
                             }

                             $count = 1;
                             if(count($assesmentOldData) > 0) {
                                foreach($assesmentOldData as $assesmentscoreOldData) {
                                    $score      = $assesmentscoreOldData->avg_rank;
                                    $org_score      = $assesmentscoreOldData->org_avg_rank;
                                    $createdBy   = Helpers::getUserName($assesmentscoreOldData->updated_by);
                                    $CreatedDate = date('d/m/Y',strtotime($assesmentscoreOldData->updated_at));
                                    ?>
                                        <tr>
                                            <td width="10%">{{$count}}</td>
                                            <td width="30%">{{$createdBy}}</td>
                                            <td width="30%">{{$CreatedDate}}</td>
                                            <td width="10%">{{$org_score}}</td>
                                            <td width="20%" class="text-right"></td>
                                        </tr>
                         <?php $count++;}
                             } else { ?>

                               <tr>
                                   <td width="100%" colspan="5">No Score Generated Yet.</td>
                                </tr>
                            <?php } ?>
                             </tbody>

                            </table>
                          </div>
                        </div>
                         <?php }?>

                            <div class="col-md-3">
                            <div class="table-responsive">
                                <table class="table table-striped table-sm">

                                    <thead>
                                    <tr>
                                     <th width="50%">Score</th>
                                     <th width="50%">Decision</th>
                                    </tr>
                                    </thead>
                                    <tr>
                                     <td width="50%">1</td>
                                     <td width="50%">On board</td>
                                    </tr>
                                    <tr>
                                     <td width="50%">2</td>
                                     <td width="50%">On board</td>
                                    </tr>
                                    <tr>
                                     <td width="50%">3</td>
                                     <td width="50%">Case by case</td>
                                    </tr>
                                    <tr>
                                     <td width="50%">4</td>
                                     <td width="50%">Declined</td>
                                    </tr>
                                    <tr>
                                     <td width="50%">5</td>
                                     <td width="50%">Declined</td>
                                    </tr>
                                </table>
                            </div>

                        </div>
                   </div>







                      </div>
                </div>


                   <!-- Old Data Display Here End -->




                <div class="form-section pr-0 pl-0">
                    <div class="row marB10">
                        <div class="col-md-9">
                            <h4 class="h3-headline w-100 background-heading">Profile Score</h4>
                        </div>
                    </div>
                    <?php if($isKycApprove == 1 || $isKycApprove == 0 || $kycCompleteArray->is_finalapprove == 0) { ?>
                    {!!
                    Form::open(
                    array(
                    'name' => 'assesmentForm',
                    'id' => 'assesmentForm',
                    'url' => route('save_assesment_rank',['user_id'=>$kycCompleteArray->user_id,'user_type'=>2,'user_kyc_id'=>$userKycId]),
                    'autocomplete' => 'off','class'=>'loginForm form form-cls'
                    ))
                    !!}
                    @php
                    $sssesmentParameters=Helpers::getRiskAssesmentParameters();
                    $arrOptions =   Helpers::getRiskAssesmentOptions();
                    $arrNames =   Helpers::getRiskAssesmentNames();

                    @endphp
                    <!--Report Sectio -->
            <div class="row">
                <div class="col-md-9">
                        <table class="table table-striped table-sm table-error">
                            <tbody>
                                <tr>
                                    <td width="33.3%"><b>1. Country of Registration</b> </td>
                                    <td width="33.3%">{{isset($corpregisterCountry) ? "(".$corpregisterCountry.")" : ''}}</td>
                                    <td width="33.3%">
                                        <?php
                                            $countryValue     = "";
                                            $machesWorldcheck = "";
                                            $passport         = "";
                                            $pep              = "";
                                            $socialMedia      = "";
                                           // dd($assesmentDetail);

                                        if (isset($assesmentDetail) && count($assesmentDetail)
                                            > 0) {
                                            $countryValue     = "";
                                            $machesWorldcheck = "";
                                            $passport         = "";
                                            $pep              = "";
                                            $socialMedia      = "";

                                            if (isset($assesmentDetail[0]->assesment_id) == 1) {
                                                $countryValue = $assesmentDetail[0]->rank;
                                            }

                                            if (isset($assesmentDetail[1]->assesment_id) == 2) {
                                                $machesWorldcheck = $assesmentDetail[1]->rank;
                                            }

                                           

                                            if (isset($assesmentDetail[2]->assesment_id) == 4) {
                                                $pep = $assesmentDetail[2]->rank;
                                            }

                                            if (isset($assesmentDetail[3]->assesment_id) == 5) {
                                                $socialMedia = $assesmentDetail[3]->rank;
                                            }
                                        } else {
                                            $countryValue = '';
                                            if(isset($companyAddress->country_id)) {
                                                 $userCountry = isset($companyAddress->country_id) ? $arrCountry[$companyAddress->country_id] : '';
                                            } else
                                            {
                                                $userCountry = isset($userData->country_id) ? $arrCountry[$userData->country_id] : '';
                                            }

                                            if ($userCountry == "United States") {
                                                $userCountry = "United States of America (USA)";
                                            }
                                            $userTypeCountryArray = Helpers::getCountryTypeByName($userCountry);
                                            if($userTypeCountryArray){
                                                $countryValue = $userTypeCountryArray->country_score;
                                            }else{
                                                $countryValue='';
                                            }
                                        }

                                         
                                        ?>



                                        <select class="form-control" name="national_identity" id="national_identity">
                                            <option value="">Please Select</option>
                                            <option value="1" {{isset($countryValue) ? (($countryValue == '1') ? 'selected':'') : ''}}>1</option>
                                            <option value="2" {{isset($countryValue) ? (($countryValue == '2') ? 'selected':'') : ''}}>2</option>
                                            <option value="3" {{isset($countryValue) ? (($countryValue == '3') ? 'selected':'') : ''}}>3</option>
                                            <option value="4" {{isset($countryValue) ? (($countryValue == '4') ? 'selected':'') : ''}}>4</option>
                                            <option value="5" {{isset($countryValue) ? (($countryValue == '5') ? 'selected':'') : ''}}>5</option>
                                        </select>

                                    </td>
                                </tr>
                                <tr>
                                    <td width="33.3%"><b>2. World-check Matches</b></td>
                                    <td width="33.3%">
                                        <?php
                                        $machesStrngth = '';
                                        if($machesWorldcheck) {
                                            if($machesWorldcheck == 1) {
                                                $machesStrngth = "Negative";
                                            } else if($machesWorldcheck == 2) {
                                                $machesStrngth = "False";
                                            } else if($machesWorldcheck == 3) {
                                                $machesStrngth = "Resolved as Unspecified";
                                            } else if($machesWorldcheck == 4) {
                                                $machesStrngth = "Resolved as Possible";
                                            } else if($machesWorldcheck == 5) {
                                                $machesStrngth = "Positive";
                                            }

                                        }
                                        ?>

                                        <div id="worldcheckStrength">{{(isset($machesStrngth))? "(".$machesStrngth.")" : (($kycCompleteArray->is_finalapprove == '3') ? 'No Match' : '' )}}</div>

                                        </td>
                                    <td width="33.3%">
                                        <select class="form-control" name="worldcheck_match" id="worldcheck_match">
                                            <option value="">Please Select</option>
                                            <option value="1" {{isset($machesWorldcheck) ? (($machesWorldcheck == '1') ? 'selected':'') : ''}}>1</option>
                                            <option value="2" {{isset($machesWorldcheck) ? (($machesWorldcheck == '2') ? 'selected':'') : ''}}>2</option>
                                            <option value="3" {{isset($machesWorldcheck) ? (($machesWorldcheck == '3') ? 'selected':'') : ''}}>3</option>
                                            <option value="4" {{isset($machesWorldcheck) ? (($machesWorldcheck == '4') ? 'selected':'') : ''}}>4</option>
                                            <option value="5" {{isset($machesWorldcheck) ? (($machesWorldcheck == '5') ? 'selected':'') : ''}}>5</option>
                                        </select>
                                    </td>
                                </tr>

                                
                              
                                <tr>
                                    <td width="33.3%"><b>3. World-check Exposure </b></td>
                                    <td width="33.3%">
                                        <?php
                                    $pepstatus = '';
                                    if($machesWorldcheck == 1 || $machesWorldcheck == 2 || $machesWorldcheck == 3 || $machesWorldcheck == 4) {
                                                $pepstatus = "( Not Exposed )";
                                            } else {

                                            if(isset($kycpersonalDetail->pep) && $kycpersonalDetail->pep!='')  { $pepstatus = "(".$kycpersonalDetail->pep.")"; } else  { $pepstatus = 'No Match'; }

                                    }

                                    echo $pepstatus;
                                    ?>

                                       </td>
                                    <td width="33.3%">
                                        <select class="form-control" name="is_peep" id="is_peep">
                                            <option value="">Please Select</option>
                                            <option value="1" {{isset($pep) ? (($pep == '1') ? 'selected':'') : ''}}>1</option>
                                            <option value="2" {{isset($pep) ? (($pep == '2') ? 'selected':'') : ''}}>2</option>
                                            <option value="3" {{isset($pep) ? (($pep == '3') ? 'selected':'') : ''}}>3</option>
                                            <option value="4" {{isset($pep) ? (($pep == '4') ? 'selected':'') : ''}}>4</option>
                                            <option value="5" {{isset($pep) ? (($pep == '5') ? 'selected':'') : ''}}>5</option>
                                        </select>
                                    </td>
                                </tr>

                                <tr>
                                    <td width="33.3%"><b>4. General Verification On Social Media</b></td>
                                    <td width="33.3%" style="max-width: 100px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;">
                                        @if($socialmediaData && $socialmediaData->count())
                                        @foreach($socialmediaData as $objSocial)
                                        <ul>
                                           <li>

                                        <a href="{{$objSocial->social_media_link}}" target="_blank">{{$objSocial->social_media_link}}
                                         </a>

                                         </li>
                                        </ul>
                                        @endforeach
                                        @endif
                                    </td>
                                    <td width="33.3%"><select class="form-control" name="is_socialmedia" id="is_socialmedia">
                                            <option value="">Please Select</option>
                                            <option value="1" {{isset($socialMedia) ? (($socialMedia == '1') ? 'selected':'') : ''}}>1</option>
                                            <option value="2" {{isset($socialMedia) ? (($socialMedia == '2') ? 'selected':'') : ''}}>2</option>
                                            <option value="3" {{isset($socialMedia) ? (($socialMedia == '3') ? 'selected':'') : ''}}>3</option>
                                            <option value="4" {{isset($socialMedia) ? (($socialMedia == '4') ? 'selected':'') : ''}}>4</option>
                                            <option value="5" {{isset($socialMedia) ? (($socialMedia == '5') ? 'selected':'') : ''}}>5</option>
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>


                <div class="col-md-3">

                <div class="table-responsive">
                                <table class="table table-striped table-sm">
                                    <tr>
                                     <td width="50%">Very low risk</td>
                                     <td width="50%">1</td>
                                    </tr>
                                    <tr>
                                     <td width="50%">Low risk</td>
                                     <td width="50%">2</td>
                                    </tr>
                                    <tr>
                                     <td width="50%">Medium risk</td>
                                     <td width="50%">3</td>
                                    </tr>
                                    <tr>
                                     <td width="50%">High risk</td>
                                     <td width="50%">4</td>
                                    </tr>
                                    <tr>
                                     <td width="50%">Very high risk</td>
                                     <td width="50%">5</td>
                                    </tr>

                                </table>
                            </div>
                       </div>
                    </div>
                    <!--Report section -->
                    <div class="row marT20">
                        <div class="col-md-9 text-right">
                            {{ Form::submit('Generate Report',['class'=>'btn btn-save','name'=>'save']) }}
                        </div>
                    </div>
                    {{ Form::close() }}
                <?php } ?>
                </div>
            </div>
            </div>

<!-- Complete Report Section Start -->
     <?php if($companyProfile && $companyProfile->count()) {?>
        <div class="tab-pane container  p-0 mt-4" id="tab08">
            <div class="col-md-12 dashbord-white">
                <div class="table-responsive">
                    <!-- Table start Here-->
                    {!!
                    Form::open(
                    array(
                    'name' => 'finalForm',
                    'id' => 'finalForm',
                    'url' => route('save_final_report',['user_kyc_id'=>$userKycId, 'user_type' =>'2','user_id' => $kycCompleteArray->user_id]),
                    'autocomplete' => 'off','class'=>'loginForm form form-cls'
                    ))
                    !!}

                    <table class="table table-striped table-sm brown-th">
                        <tbody>
                            <tr><th align="left" style="padding-bottom: 0;"><h2 class="customer-head">Know Your Customer Details
                                <!-- <button type="button" class="btn btn-save hover-green float-right" data-toggle="modal" data-target="#corpreportdownload" >Download</button> -->

                             <?php
                            // echo "==>".$complianceReport->compliance_report_id; exit;
                                 if(isset($complianceReport) && (@$complianceReport->compliance_report_id!='') ) { ?>
                         <button type="button" class="btn btn-save hover-green float-right" data-toggle="modal" data-target="#reportblockchain" data-height="400px" data-width="100%" data-url="{{route('individual_generate_report',['kycId'=>$userKycId])}}">Download</button>
                         <?php if($kycCompleteArray->is_finalapprove ==0 || $kycCompleteArray->is_finalapprove == 3) {?>
                            <button type="button" class="btn btn-save hover-green float-right" data-toggle="modal" data-target="#corpreportdownload">Generate Report</button>
                         <?php } ?>


                   <?php } ?>
                        </h2> </th></tr>
                            <tr>
                                <td align="center" style="">
                                    <table width="100%" border="1" style="border-collapse:collapse;max-width: 660px; margin:0 auto; background: #fff;border: 1px solid #ddd;padding: 20px;">
                                        <thead>
                                            <tr>
                                                <th colspan="2" align="center">Client Type : Company <input type="checkbox" checked="true" style="vertical-align: middle;"></th>
                                            </tr>
                                            
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td width="30%">Company Name</td>
                                                <td width="70%">{{isset($userData->corp_name)? ucfirst($userData->corp_name):''}}</td>
                                            </tr>
                                            <tr>
                                                <td width="30%">Registration Number</td>
                                                <td>{{isset($companyProfile->registration_no) ? $companyProfile->registration_no : ''}}</td>
                                            </tr>
                                            <tr>
                                                <td width="30%">Registration Date</td>
                                                
                                                 <td> {{isset($date)?$date:''}} </td>
                                            </tr>
                                            <tr>
                                                <td width="30%">Tax Registration No.</td>
                                                <td><input type="text" class="form-control" name="tax_registration_no" id="tax_registration_no" value="{{ isset($complianceReport->tax_registration_no) ? $complianceReport->tax_registration_no : ''}}" placeholder="Tax Registration No."></td>
                                            </tr>
                                            <tr>
                                                <td><strong>License T.C.R. Regulater No. (If applicable)</strong></td>
                                                <td><input type="text" class="form-control" name="license" id="license" value="{{ isset($complianceReport->license) ? $complianceReport->license : ''}}" placeholder="License T.C.R. Regulater No. (If applicable)"></td>
                                            </tr>
                                            <tr>
                                                <td width="30%">Tenancy Contract Expiry Date</td>
                                                <td><input type="text" class="form-control tenancydatepicker" name="tenancy_exp_date" id="tenancy_exp_date" value="{{ isset($complianceReport->tenancy_exp_date) ? $complianceReport->tenancy_exp_date : ''}}" placeholder="Tenancy Contact Expiry Date"></td>
                                            </tr>
                                            <tr>
                                                <td>Registration of Tenancy Contract</td>
                                                <td><input type="text" class="form-control" name="registration_tenancy" id="registration_tenancy" value="{{ isset($complianceReport->registration_tenancy) ? $complianceReport->registration_tenancy : ''}}" placeholder="Registration of Tenancy Contract"></td>
                                            </tr>
                                            <tr>
                                                <td>Registered Address</td>
                                                <td>
                                                   <?php
                                                   $corporateAddress = '';
                                                   //Business Address
                                                   if(isset($companyAddress->floor)?$companyAddress->floor:'') {
                                                       $corporateAddress=$companyAddress->floor.","." ";
                                                   }
                                                    if(isset($companyAddress->building)?$companyAddress->building:'') {
                                                       $corporateAddress.=$companyAddress->building.","." ";
                                                   }

                                                   if(isset($companyAddress->street)?$companyAddress->street:''){
                                                       $corporateAddress.= $companyAddress->street.","." ";
                                                   }
                                                   

                                                  

                                                   if(isset($companyAddress->region)?$companyAddress->region:'') {
                                                       $corporateAddress.=$companyAddress->region.","." ";
                                                   }

                                                   if(isset($companyAddress->city_id)?$companyAddress->city_id:'') {
                                                       $corporateAddress.=$companyAddress->city_id.","." ";
                                                   }

                                                   if(isset($companyAddress->country_id)?$companyAddress->country_id:'') {
                                                       $corporateAddress.=$arrCountry[$companyAddress->country_id].", ";
                                                   }

                                                   if(isset($companyAddress->postal_code)?$companyAddress->postal_code:'') {
                                                       $corporateAddress.=$companyAddress->postal_code;
                                                   }

                                                 

                                                   echo $corporateAddress;
                                                   ?>
                                                </td>
                                            </tr>
                                            <!--<tr>
                                                <td>Office Address</td>
                                                <td></td>
                                            </tr>-->
                                            <tr>
                                                <td>Telephone Number</td>
                                                <td>{{isset($companyAddress->area_code)?"( ".$companyAddress->area_code." )":''}} {{isset($companyAddress->telephone)?$companyAddress->telephone:''}}</td>
                                            </tr>
                                            <tr>
                                                <td>Mobile Number</td>
                                                <td>{{isset($companyAddress->country_code)? "( ".$companyAddress->country_code." )" :''}} {{isset($companyAddress->mobile)?$companyAddress->mobile:''}}</td>
                                            </tr>

                                             <tr>
                                                <td>Fax Number</td>
                                                <td>{{isset($companyAddress->fax_countrycode)? "( ".$companyAddress->fax_countrycode." )":''}} {{isset($companyAddress->fax)?$companyAddress->fax:''}}</td>
                                            </tr>
                                            <tr>
                                                <td>Email Address</td>
                                                <td>{{isset($companyAddress->email)?$companyAddress->email:''}}</td>
                                            </tr>
                                            <tr>
                                                <td>Website</td>
                                                <td><input type="text" class="form-control" name="website" id="bank_name" value="{{ isset($complianceReport->website) ? $complianceReport->website : ''}}" placeholder="Website URL"></td>
                                            </tr>
                                            <tr>
                                                <th colspan="2" align="left">Bank Details</th>
                                            </tr>
                                            <tr>
                                                <td>Bank Name</td>
                                                <td><input type="text" class="form-control" name="bank_name" id="bank_name" value="{{ isset($complianceReport->bank_name) ? $complianceReport->bank_name : ''}}" placeholder="Bank Name"></td>
                                            </tr>
                                            <tr>
                                                <td>Account Number</td>
                                                <td><input type="text" class="form-control" name="accouunt_number" id="accouunt_number" value="{{ isset($complianceReport->accouunt_number) ? $complianceReport->accouunt_number : ''}}" placeholder="Account Number"></td>
                                            </tr>
                                            <tr>
                                                <td>Correspondent Account No.</td>
                                                <td><input type="text" class="form-control" name="corr_accouunt_number" id="corr_accouunt_number" value="{{ isset($complianceReport->corr_accouunt_number) ? $complianceReport->corr_accouunt_number : ''}}" placeholder="Correspondent Account No."></td>
                                            </tr>
                                            <tr>
                                                <th colspan="2" align="left">Shareholder Structure - Ultimate Beneficiary Owner</th>
                                            </tr>
                                            <?php  $i=1;?>
                                            @if($beficiyerArray && $beficiyerArray->count())
                                            
                                            @foreach($beficiyerArray as $obj)
                                            @php

                                           // $documentsData = Helpers::getDocumentTypeInfowithpassport($obj->owner_kyc_id, $obj->passport_no);
                                           // echo "<pre>";
                                           // print_r($documentsData); exit;

                                             $documentsData = Helpers::getDocumentTypeInfo($obj->owner_kyc_id);
                                          

                                            if ($documentsData) {
                                                    $passportArray = Helpers::getPassportNumber($documentsData,'passport');
                                                    $passportArrays = explode("#",$passportArray);
                                                    $userPassportNumber = @$passportArrays[0];
                                                    $userPassportExp    = @$passportArrays[1];
                                                }
                                         

                                            $residentialData = Helpers::getCommercialInfo($obj->owner_kyc_id);
                                            //echo "<pre>";
                                           //print_r($residentialData);
                                            $fullAddress = '';
                                            if($residentialData['floor_no']){
                                                    $fullAddress = $residentialData['floor_no'].",";
                                            }
                                            if($residentialData['building_no']){
                                                    $fullAddress.=$residentialData['building_no'].",";
                                            }
                                            if($residentialData['street_addr']){
                                                    $fullAddress.=$residentialData['street_addr'].",";
                                            }
                                            if($residentialData['region']){
                                                    $fullAddress.=$residentialData['region'].",";
                                            }
                                            if($residentialData['city_id']){
                                                    $fullAddress.=$residentialData['city_id'].",";
                                            }

                                            if($residentialData['country_id']){
                                                $userCountryName = $arrCountry[$residentialData->country_id];
                                                $fullAddress.=$userCountryName.",";
                                            }
                                            if($residentialData['postal_code ']){
                                                    $fullAddress.=$residentialData['postal_code '];
                                            }

                                            $fullAddress = trim($fullAddress,",");


                                            @endphp

                                            <tr>
                                                <th colspan="2" align="left">Individuals 
                                            </tr>
                                            <tr>
                                                <th colspan="2" align="left">Shareholder {{$i}}</th>
                                            </tr>
                                            <tr>
                                                <td>Full Name</td>
                                                <td>{{$obj->company_name}}</td>
                                            </tr>
                                            <tr>
                                                <td>Passport Number</td>
                                                <td>{{isset($userPassportNumber) ? $userPassportNumber : ''}}</td>
                                            </tr>
                                            <tr>
                                                <td>Passport Expiry Date</td>
                                                <td>{{ isset($userPassportExp) ? $userPassportExp : ''}}</td>
                                            </tr>
                                            <tr>
                                                <td>Full Address</td>
                                                <td>{{ $fullAddress }}</td>
                                            </tr>
                                            <tr>
                                                <td>Mobile Number</td>
                                                <td>{{ isset($residentialData['addr_country_code']) ? "(".$residentialData['addr_country_code'].")". $residentialData['addr_mobile_no'] : ''}}</td>
                                            </tr>
                                            <tr>
                                                <td>Email Address</td>
                                                <td>{{ isset($residentialData['addr_email']) ? $residentialData['addr_email'] : ''}}</td>
                                            </tr>
                                            <?php  $i++;?>
                                            @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <!-- Share Holding Structure start -->

                             
                            <tr>
                                <td>
                                    <table width="100%" border="1" style="border-collapse:collapse;max-width: 660px; margin:0 auto; background: #fff;border: 1px solid #ddd;padding: 20px;">
                                        <thead>
                                        <tr>
                                            <th colspan="2" align="center">2. Shareholding Chart Structure</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td width="100%" colspan="2">{{isset($userData->corp_name)? ucfirst($userData->corp_name):''}}</td>
                                            </tr>
                                            <?php
                                             $shareresult  =   Helpers::getCorpShareStructure($kycCompleteArray->user_id);
                                            //$newresult = $beficiyerData;
                                                $category = array(
                                                    'shares' => array(),
                                                    'parent_share' => array()
                                                );
                                                if($shareresult && $shareresult->count()){
                                                    foreach($shareresult as $row){

                                                        //creates entry into categories array with current category id ie. $categories['categories'][1]
                                                        $category['shares'][$row['corp_shareholding_id']] = $row;
                                                        //creates entry into parent_cats array. parent_cats array contains a list of all categories with children
                                                        $category['parent_share'][$row['share_parent_id']][] = $row['corp_shareholding_id'];
                                                    }
                                                }
                                            ?>

                                            <tr>
                                                <td width="100%" colspan="2">{{Helpers::buildCategory(0, $category)}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <!-- Share Holding Structure End -->
                            <!-- General Investigataion  Report -->
                            <tr>
                                <td>
                                    <table width="100%" border="1" style="border-collapse:collapse;max-width: 660px; margin:0 auto; background: #fff;border: 1px solid #ddd;padding: 20px;">
                                        <thead>
                                            <tr>
                                                <th colspan="2" align="center">3. World Check Report</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td width="100%">
                                                    <?php
                                                    $totalMatch = "";
                                                    $statusName = '';
                                                    $autoresolved = 0;
                                                    $falsecount = 0;
                                                    $kycData=false;
                                                    if(isset($primaryId) && $primaryId > 0){
                                                        $kycData = Helpers::getkycdataById($primaryId);
                                                    }

                                                   // echo "<pre>";
                                                   // print_r($kycData);
                                                    if($kycData)
                                                    {
                                                        $j = 1;
                                                        $caseid     =   $kycData->caseid;
                                                        $falsecount = $kycData->falsecount;
                                                        $autoresolved = $kycData->autoresolved;
                                                        $totalMatch = ($autoresolved+$falsecount);
                                                        echo "<h5>3.".$j." ".ucfirst($kycData->org_name);
                                                        echo  str_repeat('&nbsp;', 2);     
                                                        echo "Total Matches-"."( ".$totalMatch." )</h5>";
                                                        echo "<br>";
                                                   

                                                    $possbilecount = 0;
                                                    $positivecount = 0;
                                                    $falsecounting = 0;
                                                    $unspecifiedcount = 0;
                                                    //echo "==>".$primaryId;
                                                   //  $primaryId = 255;
                                                    $i           = 0;
                                                    $j ++;
                                                    $ExactCount  = 0;
                                                    $FalseCount  = 0;
                                                    $possibleStatus    = '';
                                                    $positiveStatus    = '';
                                                    $falseStatus       = '';
                                                    $unspecifiedStatus = '';
                                                    $Possible_Array    = [];
                                                    $Positive_Array    = [];
                                                    $False_Array       = [];
                                                    $Unspecified_Array = [];
                                                    $matchresultArray = [];
                                                    $resolvedMatched =0;
                                                    $autoresolvedfalse = 0;
                                                        if($caseid!='') {
                                                       $resolutionDatas = Helpers::getResolutionDatabycaseID($caseid);
                                                      
                                                       
                                                       $resolvedMatched   = $autoresolved;
                                                        $autoresolvedfalse = $autoresolved;
                                                       foreach($resolutionDatas as $resolutionData) {
                                                        if($resolutionData['status_mark']!=''){
                                                            $status = $resolutionData['status_mark'];
                                                            $risk   = $resolutionData['risk_level'];
                                                            $reson  = $resolutionData['reson'];
                                                            $reson_desp  = $resolutionData['reson_desp'];

                                                            $statusName  = Helpers::toolkettypebyId($status);
                                                            $riskName    = Helpers::toolkettypebyId($risk);
                                                            $resonName   = Helpers::toolkettypebyId($reson);
                                                            $resolvedon = $resolutionData['created_at'];
                                                            // echo $statusName."==>".$riskName."===>".$resonName;
                                                            //echo "===>".$matchresultCount;
                                                        }




                                                        if($statusName!='') {
                                                             if($statusName =="POSSIBLE") {
                                                                $possibleStatus = 'POSSIBLE';

                                                                if($resolutionData['from_resolve'] == '2') {
                                                                    $matchresult    = $resolutionData['result_id'];
                                                                    $explodeList = explode(',',$matchresult);
                                                                    foreach($explodeList as $key => $value) {
                                                                         if (empty($value)) {
                                                                                unset($explodeList[$key]);
                                                                            } else {
                                                                                array_push($Possible_Array,$value);
                                                                            }
                                                                    }

                                                                    $possbilecount  = ($possbilecount)+count($Possible_Array);
                                                                } else if($resolutionData['from_resolve'] == '1') {
                                                                   $matchresult      = $resolutionData['result_id'];
                                                                   $matchresult = trim($matchresult,",");
                                                                   $Possible_Array[] = $matchresult;
                                                                   $possbilecount    = $possbilecount+1;
                                                                }
                                                                $matchresultArray = array_merge($matchresultArray,$Possible_Array);

                                                             } else if($statusName =="POSITIVE") {
                                                                $positiveStatus = 'POSITIVE';
                                                                if($resolutionData['from_resolve'] == '2') {
                                                                    $matchresult   = $resolutionData['result_id'];
                                                                    $explodeList = explode(',',$matchresult);
                                                                    foreach($explodeList as $key => $value) {
                                                                         if (empty($value)) {
                                                                                unset($explodeList[$key]);
                                                                            } else {
                                                                                array_push($Positive_Array,$value);
                                                                            }
                                                                    }

                                                                    $positivecount = ($positivecount)+count($Positive_Array);
                                                                } else if($resolutionData['from_resolve'] == '1'){
                                                                   $matchresult   = $resolutionData['result_id'];
                                                                   $matchresult = trim($matchresult,",");
                                                                   $Positive_Array[] = $matchresult;
                                                                   $positivecount = $positivecount+1;
                                                                }
                                                                $matchresultArray = array_merge($matchresultArray,$Positive_Array);




                                                             } else if($statusName =="FALSE") {

                                                                $falseStatus = 'FALSE';
                                                                if($resolutionData['from_resolve'] == '2') {
                                                                    $matchresult    = $resolutionData['result_id'];
                                                                    $explodeList = explode(',',$matchresult);
                                                                    foreach($explodeList as $key => $value) {
                                                                         if (empty($value)) {
                                                                                unset($explodeList[$key]);
                                                                            } else {
                                                                                array_push($False_Array,$value);
                                                                            }
                                                                    }


                                                                    $falsecounting  = ($falsecounting)+count($False_Array);
                                                                } else if($resolutionData['from_resolve'] == '1'){
                                                                   $matchresult   = $resolutionData['result_id'];
                                                                   $matchresult = trim($matchresult,",");
                                                                   $False_Array[] = $matchresult;
                                                                   $falsecounting = $falsecounting+1;
                                                                }


                                                                $matchresultArray = array_merge($matchresultArray,$False_Array);
                                                             } else if($statusName =="UNSPECIFIED") {
                                                                 $unspecifiedStatus = 'UNSPECIFIED';
                                                                 if($resolutionData['from_resolve'] == '2') {
                                                                    $matchresult          = $resolutionData['result_id'];

                                                                    $explodeList = explode(',',$matchresult);
                                                                    foreach($explodeList as $key => $value) {
                                                                         if (empty($value)) {
                                                                                unset($explodeList[$key]);
                                                                            } else {
                                                                                array_push($Unspecified_Array,$value);
                                                                            }
                                                                    }
                                                                    $unspecifiedcount     = ($unspecifiedcount)+(count($Unspecified_Array));
                                                                } else if($resolutionData['from_resolve'] == '1'){
                                                                   $matchresult           = $resolutionData['result_id'];
                                                                   $matchresult = trim($matchresult,",");
                                                                   $Unspecified_Array[]   = $matchresult;
                                                                   $unspecifiedcount      = $unspecifiedcount+1;
                                                                }
                                                                $matchresultArray = array_merge($matchresultArray,$Unspecified_Array);
                                                             }
                                                         }

                                                        }
                                                        $falsecount = $falsecount-($possbilecount+$positivecount+$falsecounting+$unspecifiedcount);
                                                        $resolvedMatched = $resolvedMatched + ($possbilecount+$positivecount+$falsecounting+$unspecifiedcount);
                                                        $autoresolvedfalse = $autoresolvedfalse+$falsecounting;
                                                        }//if caseid blank
                                                        $ExactCount  = 0;
                                                        $FalseCount  = 0;
                                                    ?>
                                                    <table width="100%" cellspacing="0" border="0">
                                                        <tr>
                                                           <td width="20%" style="font-size:14px;">Resolved Matches:</td>
                                                           <td width="10%" class="text-center"><div id="resolveMatch">{{$resolvedMatched}}</div></td>
                                                           <td width="45%" style="font-size:14px; text-align: left;"> <div style="margin-right:20px;display: inline;">Positive: {{$positivecount}}</div> <div style="margin-right:20px;display: inline;">Possible: {{$possbilecount}}</div> False: {{ ($autoresolved!='') ? $autoresolved : '0'}}</td>
                                                           <td width="25%" class="text-left" style="font-size:14px; text-align: center;">Unspecified: {{$unspecifiedcount}}</td>
                                                       </tr>
                                                       <tr >
                                                           <td width="25%" style="font-size:14px;">Unresolved Matches:</td>
                                                           <td width="10%" class="text-center"><div id="totalMatch">{{$falsecount}}</div></td>
                                                           <td width="65%" style="font-size:14px;" colspan="2">&nbsp;</td>

                                                       </tr>
                                                    </table>
                                                     


                                                    <?php
                                                   // echo "<pre>";
                                                  //  print_r($beficiyerData);
                                                  // exit;


                                                    if($beficiyerData && $beficiyerData->count()) {
                                                   // $j = 1;
                                                    foreach($beficiyerData as $obj) {
                                                     // echo "==>".$obj['owner_kyc_id'];
                                                        $kycDataprofile = Helpers::getDataByKycId($obj['owner_kyc_id'],'screeningRequest');
                                                        // $kycDataprofile = Helpers::getKycDataByKycId($obj['owner_kyc_id'],'profile');
                                                        if($kycDataprofile['wcapi_req_res_id']!='') {
                                                        //    echo "==>".$kycDataprofile['wcapi_req_res_id'];
                                                        // $kycData = Helpers::getkycdataById($kycDataprofile['parent_id']);
                                                         $kycData = Helpers::getKycDatabyparentId($kycDataprofile['wcapi_req_res_id']);
                                                        if($kycData)
                                                            {
                                                            $childcaseid =  @$kycData->caseid;
                                                            $childwcid =  @$kycData->wcapi_req_res_id;
                                                            $falsecount = $kycData->falsecount;
                                                            $autoresolved = $kycData->autoresolved;
                                                            $response  = $kycData->api_responce; //exit
                                                            $dataArray = json_decode($response, JSON_UNESCAPED_UNICODE);
                                                            $totalMatch = $falsecount+$autoresolved;
                                                            echo "<br>";
                                                            echo "<h5>3.".$j." ".ucfirst($kycData->f_name)." ".$kycData->m_name." ".$kycData->l_name;
                                                            echo " Total Matches-"."( ".$totalMatch." )</h5>";
                                                           // echo "<br>Resolved Matches- ".$kycData->exactcount." (POSITIVE)";
                                                            //echo "<br>Resolved Matches - ".$kycData->falsecount." (FALSE)";
                                                        
                                                         $j++;

                                                        
                                                    $possbilecount = 0;
                                                    $positivecount = 0;
                                                    $falsecounting = 0;
                                                    $unspecifiedcount = 0;
                                                    $statusName = '';
                                                    //echo "==>".$primaryId;
                                                   //  $primaryId = 255;
                                                     //$resolutionData = Helpers::getResolutionDatabyparentID($childwcid);
                                                        $i           = 0;
                                                        $ExactCount  = 0;
                                                        $FalseCount  = 0;
                                                        $possibleStatus    = '';
                                                        $positiveStatus    = '';
                                                        $falseStatus       = '';
                                                        $unspecifiedStatus = '';
                                                        $Possible_Array    = [];
                                                        $Positive_Array    = [];
                                                        $False_Array       = [];
                                                        $Unspecified_Array = [];
                                                        $matchresultArray  = [];
                                                        $resolutionDatas   = Helpers::getResolutionDatabycaseID($childcaseid);
                                                        $resolvedMatched   = ($autoresolved) ? $autoresolved : 0;
                                                        $autoresolvedfalse = ($autoresolved) ? $autoresolved : 0;
                                                        //echo count($dataArray['results']); exit;

                                                        if(isset($dataArray['results']) && count($dataArray['results']) > 0) {
                                                            //echo "dddddd"; exit;

                                                            foreach($resolutionDatas as $resolutionData) {
                                                             if($resolutionData['status_mark']!=''){
                                                                 $status = $resolutionData['status_mark'];
                                                                 $risk   = $resolutionData['risk_level'];
                                                                 $reson  = $resolutionData['reson'];
                                                                 $reson_desp  = $resolutionData['reson_desp'];

                                                                 $statusName  = Helpers::toolkettypebyId($status);
                                                                 $riskName    = Helpers::toolkettypebyId($risk);
                                                                 $resonName   = Helpers::toolkettypebyId($reson);
                                                                 $resolvedon = $resolutionData['created_at'];
                                                                 // echo $statusName."==>".$riskName."===>".$resonName;
                                                                 //echo "===>".$matchresultCount;
                                                             }




                                                             if($statusName!='') {
                                                                  if($statusName =="POSSIBLE") {
                                                                     $possibleStatus = 'POSSIBLE';

                                                                     if($resolutionData['from_resolve'] == '2') {
                                                                         $matchresult    = $resolutionData['result_id'];
                                                                         $explodeList = explode(',',$matchresult);
                                                                         foreach($explodeList as $key => $value) {
                                                                              if (empty($value)) {
                                                                                     unset($explodeList[$key]);
                                                                                 } else {
                                                                                     array_push($Possible_Array,$value);
                                                                                 }
                                                                         }

                                                                         $possbilecount  = ($possbilecount)+count($Possible_Array);
                                                                     } else if($resolutionData['from_resolve'] == '1') {
                                                                        $matchresult      = $resolutionData['result_id'];
                                                                        $matchresult = trim($matchresult,",");
                                                                        $Possible_Array[] = $matchresult;
                                                                        $possbilecount    = $possbilecount+1;
                                                                     }
                                                                     $matchresultArray = array_merge($matchresultArray,$Possible_Array);

                                                                  } else if($statusName =="POSITIVE") {
                                                                     $positiveStatus = 'POSITIVE';
                                                                     if($resolutionData['from_resolve'] == '2') {
                                                                         $matchresult   = $resolutionData['result_id'];
                                                                         $explodeList = explode(',',$matchresult);
                                                                         foreach($explodeList as $key => $value) {
                                                                              if (empty($value)) {
                                                                                     unset($explodeList[$key]);
                                                                                 } else {
                                                                                     array_push($Positive_Array,$value);
                                                                                 }
                                                                         }

                                                                         $positivecount = ($positivecount)+count($Positive_Array);
                                                                     } else if($resolutionData['from_resolve'] == '1'){
                                                                        $matchresult   = $resolutionData['result_id'];
                                                                        $matchresult = trim($matchresult,",");
                                                                        $Positive_Array[] = $matchresult;
                                                                        $positivecount = $positivecount+1;
                                                                     }
                                                                     $matchresultArray = array_merge($matchresultArray,$Positive_Array);




                                                                  } else if($statusName =="FALSE") {

                                                                     $falseStatus = 'FALSE';
                                                                     if($resolutionData['from_resolve'] == '2') {
                                                                         $matchresult    = $resolutionData['result_id'];
                                                                         $explodeList = explode(',',$matchresult);
                                                                         foreach($explodeList as $key => $value) {
                                                                              if (empty($value)) {
                                                                                     unset($explodeList[$key]);
                                                                                 } else {
                                                                                     array_push($False_Array,$value);
                                                                                 }
                                                                         }


                                                                         $falsecounting  = ($falsecounting)+count($False_Array);
                                                                     } else if($resolutionData['from_resolve'] == '1'){
                                                                        $matchresult   = $resolutionData['result_id'];
                                                                        $matchresult = trim($matchresult,",");
                                                                        $False_Array[] = $matchresult;
                                                                        $falsecounting = $falsecounting+1;
                                                                     }


                                                                     $matchresultArray = array_merge($matchresultArray,$False_Array);
                                                                  } else if($statusName =="UNSPECIFIED") {
                                                                      $unspecifiedStatus = 'UNSPECIFIED';
                                                                      if($resolutionData['from_resolve'] == '2') {
                                                                         $matchresult          = $resolutionData['result_id'];

                                                                         $explodeList = explode(',',$matchresult);
                                                                         foreach($explodeList as $key => $value) {
                                                                              if (empty($value)) {
                                                                                     unset($explodeList[$key]);
                                                                                 } else {
                                                                                     array_push($Unspecified_Array,$value);
                                                                                 }
                                                                         }
                                                                         $unspecifiedcount     = ($unspecifiedcount)+(count($Unspecified_Array));
                                                                     } else if($resolutionData['from_resolve'] == '1'){
                                                                        $matchresult           = $resolutionData['result_id'];
                                                                        $matchresult = trim($matchresult,",");
                                                                        $Unspecified_Array[]   = $matchresult;
                                                                        $unspecifiedcount      = $unspecifiedcount+1;
                                                                     }
                                                                     $matchresultArray = array_merge($matchresultArray,$Unspecified_Array);
                                                                  }
                                                              }

                                                             }
                                                             $falsecount = $falsecount-($possbilecount+$positivecount+$falsecounting+$unspecifiedcount);
                                                             $resolvedMatched = $resolvedMatched + ($possbilecount+$positivecount+$falsecounting+$unspecifiedcount);
                                                             $autoresolvedfalse = $autoresolvedfalse+$falsecounting;
                                                        }

                                                        $ExactCount  = 0;
                                                        $FalseCount  = 0;
                                                    ?>
                                                    <table width="100%" cellspacing="0" border="0">
                                                        <tr>
                                                           <td width="20%" style="font-size:14px;">Resolved Matches:</td>
                                                           <td width="10%" class="text-center"><div id="resolveMatch">{{$resolvedMatched}}</div></td>
                                                           <td width="45%" style="font-size:14px; text-align: left;"> <div style="margin-right:20px;display: inline;">Positive: {{$positivecount}}</div> <div style="margin-right:20px;display: inline;">Possible: {{$possbilecount}}</div> False: {{$autoresolvedfalse}}</td>
                                                           <td width="25%" class="text-left" style="font-size:14px; text-align: center;">Unspecified: {{$unspecifiedcount}}</td>
                                                       </tr>
                                                       <tr >
                                                           <td width="25%" style="font-size:14px;">Unresolved Matches:</td>
                                                           <td width="10%" class="text-center"><div id="totalMatch">{{$falsecount}}</div></td>
                                                           <td width="65%" style="font-size:14px;" colspan="2">&nbsp;</td>

                                                       </tr>
                                                    </table>

                                                   
                                                    <?php }
                                                         }

                                                      } } } ?>
                                                    

                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <!-- General Investigataion  Report  End-->
                            <!-- General Investigataion  Report -->
                            <tr>
                                <td>
                                    <table width="100%" border="1" style="border-collapse:collapse;max-width: 660px; margin:0 auto; background: #fff;border: 1px solid #ddd;padding: 20px;">
                                        <thead>
                                            <tr>
                                                <th colspan="2" align="center">4. Internet General Investigation Report</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td width="30%">Social Media Links URL</td>
                                                <td width="70%">
                                                     @if($socialmediaData && $socialmediaData->count())
                                                        @foreach($socialmediaData as $objSocial)
                                                                    @php $socialmediaName = Helpers::getSocialmediaNameById($objSocial->social_media);@endphp
                                                                    <?php $socialMediaUrl = '<a href="'.$objSocial->social_media_link.'" target="_blank">'.$objSocial->social_media_link.'</a>';

                                                                echo $socialmediaName ." : ". $socialMediaUrl."<br>";
                                                                ?>

                                                        @endforeach
                                                    @endif

                                                    

                                                    {{Form::textarea('general_investigation',isset($complianceReport->general_investigation) ? $complianceReport->general_investigation : '',['class'=>'form-control','id'=>'general_investigation','rows'=>'4','placeholder' =>'Enter Remarks'])}}

                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <!-- General Investigataion  Report  End-->
                            <!-- General Investigataion  Report -->
                            <tr>
                                <td>
                                    <table width="100%" border="1" style="border-collapse:collapse;max-width: 660px; margin:0 auto; background: #fff;border: 1px solid #ddd;padding: 20px;">
                                        <thead>
                                            <tr>
                                                <th colspan="2" align="center">5. References Investigation Report</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td> <div class="form-group">
                                                        {{Form::textarea('references_investigation',isset($complianceReport->references_investigation) ? $complianceReport->references_investigation : '',['class'=>'form-control','id'=>'references_investigation','rows'=>'7'])}}
                                                     </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <!-- General Investigataion  Report  End-->



                            <!-- Score  Report Start -->
                            <tr>
                                <td>
                                    <table width="100%" border="1" style="border-collapse:collapse;max-width: 660px; margin:0 auto; background: #fff;border: 1px solid #ddd;padding: 20px;">
                                        <thead>
                                            <tr>
                                                <th colspan="2" align="center">6. Calculation of the final score according to the Onboarding risk assessment:</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="2">
                                                    <table class="table table-striped table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th width="70%">Parameter</th>
                                                                <th width="30%" style="border-left: 1px solid #dee2e6;"><center>Score</center></th>
                                                            </tr>
                                                           </thead>
                                                            <tbody>

                                                          <tr>
                                                                <td width="70%"><strong>{{isset($userData->corp_name)? ucfirst($userData->corp_name):''}}</strong></td>
                                                                <td width="30%" style="border-left: 1px solid #dee2e6;"></td>
                                                            </tr>
                                                            <tr>
                                                                <td width="70%">Country of registration</td>
                                                                <td width="30%" style="border-left: 1px solid #dee2e6;"><center>{{@$countryValue}}</center></td>
                                                            </tr>
                                                             <tr>
                                                                <td width="70%">Matches and Risk (World Check)</td>
                                                                <td width="30%" style="border-left: 1px solid #dee2e6;"><center>{{ @$machesWorldcheck }}</center></td>
                                                            </tr>
                                                             
                                                             <tr>
                                                                <td width="70%">Type and Exposure (PEP)</td>
                                                                <td width="30%" style="border-left: 1px solid #dee2e6;"><center>{{ @$pep }}</center></td>
                                                            </tr>
                                                             <tr>
                                                                <td width="70%">General Verification on Website and Social Media</td>
                                                                <td width="30%" style="border-left: 1px solid #dee2e6;"><center>{{ @$socialMedia }}</center></td>
                                                            </tr>

                                                              <tr>
                                                                <td width="70%"><strong>Score</strong></td>
                                                                <td width="30%" style="border-left: 1px solid #dee2e6;"><center><strong style=>{{ @$assesmentData[0]->org_avg_rank }}</strong></center></td>
                                                            </tr>
                                                            </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            
                                            <!-- For multiple share holders-->
                                            <tr>
                                                <td colspan="2" bgcolor="#fff">
                                                    
                                                   
                                                    @if($beficiyerData && $beficiyerData->count())

                                                    
                                                    @php @$j = 1; @endphp
                                                    @foreach($beficiyerData as $obj)
                                                        @php
                                                        $IndividualAssesmentData = Helpers::getAssesmentData($obj->owner_kyc_id);
                                                        $getAssesmentRankData = Helpers::getAssesmentRankData($obj->owner_kyc_id, 1);
                                                        $beneficiary = Helpers::UserpersonalData($obj->owner_kyc_id);
                                                        if($IndividualAssesmentData)
                                                        { @endphp

                                                         

                                                        <?php

                                                        //echo "<pre>";
                                                        //print_r($getAssesmentRankData);

                                                            $countryValue     = "";
                                                            $machesWorldcheck = "";
                                                            $passport         = "";
                                                            $pep              = "";
                                                            $socialMedia      = "";

                                                        if (isset($IndividualAssesmentData) && count($IndividualAssesmentData)
                                                            > 0) {
                                                           

                                                            if (isset($IndividualAssesmentData[0]->assesment_id) == 1) {
                                                                $countryValue = $IndividualAssesmentData[0]->rank;
                                                            }

                                                            if (isset($IndividualAssesmentData[1]->assesment_id) == 2) {
                                                                $machesWorldcheck = $IndividualAssesmentData[1]->rank;
                                                            }
                                                            if (isset($IndividualAssesmentData[2]->assesment_id)
                                                                        == 3) {
                                                                    $passport = $IndividualAssesmentData[2]->rank;
                                                                }

                                                            if (isset($IndividualAssesmentData[3]->assesment_id) == 4) {
                                                                $pep = $IndividualAssesmentData[3]->rank;
                                                            }

                                                            if (isset($IndividualAssesmentData[4]->assesment_id) == 5) {
                                                                $socialMedia = $IndividualAssesmentData[4]->rank;
                                                            }
                                                        }

                                                        if(isset($getAssesmentRankData[0]->org_avg_rank)) {
                                                         $TotalScore = ($TotalScore+$getAssesmentRankData[0]->org_avg_rank);
                                                        }


                                                        ?>
                                                        <table class="table table-striped table-sm">
                                                            <tr>
                                                                <td width="70%"><strong>{{isset($beneficiary->f_name)? ucfirst($beneficiary->f_name." ".$beneficiary->l_name):'Profile Incomplete'}}</strong></td>
                                                                <td width="30%" style="border-left: 1px solid #dee2e6;"></td>
                                                            </tr>
                                                            <tr>
                                                                <td width="70%">National Identity</td>
                                                                <td width="30%" style="border-left: 1px solid #dee2e6;"><center>{{ @$countryValue }}</center></td>
                                                            </tr>
                                                             <tr>
                                                                <td width="70%">Name Matches – World Check</td>
                                                                <td width="30%" style="border-left: 1px solid #dee2e6;"><center>{{@$machesWorldcheck}}</center></td>
                                                            </tr>
                                                            <tr>
                                                                <td width="70%" style="border-left: 1px solid #dee2e6;">Passport Verification – World Check</td>
                                                                <td width="30%"><center>{{ @$passport }}</center></td>
                                                            </tr>
                                                             
                                                             <tr>
                                                                <td width="70%">Exposure (PEP)</td>
                                                                <td width="30%" style="border-left: 1px solid #dee2e6;"><center>{{@$pep}}</center></td>
                                                            </tr>
                                                             <tr>
                                                                <td width="70%">General Verification on Website and Social Media</td>
                                                                <td width="30%" style="border-left: 1px solid #dee2e6;"><center>{{@$socialMedia}}</center></td>
                                                            </tr>

                                                            <tr>
                                                                <td width="70%"><strong>Score</strong></td>
                                                                <td width="30%" style="border-left: 1px solid #dee2e6;"><strong><center>{{ isset($getAssesmentRankData[0]->org_avg_rank) ? $getAssesmentRankData[0]->org_avg_rank : '' }}</center></strong></td>
                                                            </tr>
                                                            </table>
                                                        @php }
                                                         @$j++;
                                                        @endphp
                                                    @endforeach
                                                    @endif

                                                   


                                                    <?php
                                                   // echo "<pre>";
                                                    //print_r($assesmentData);
                                                    if($TotalScore) {
                                                        isset($assesmentData[0]->org_avg_rank) ? $asData = $assesmentData[0]->org_avg_rank : $asData = 0;
                                                      $TotalScore =   ($TotalScore+$asData);
                                                      $beficiyerCount = $beficiyerData->count();
                                                      $noofUsers = $beficiyerCount+1;
                                                      $averageScore = $TotalScore/$noofUsers;
                                                      //echo "total :->".$TotalScore;
                                                      //echo "noofUsers :->".$noofUsers;
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td width="69%"><strong>Final Score</strong></td>
                                                        <td width="31%"><center><strong>{{ number_format($averageScore,2) }}</strong></center></td>
                                                    </tr>
                                                </td>
                                            </tr>

                                            <tr>
                                               <td colspan="2"> <div class="form-group">
                                                        {{Form::textarea('comment_compliance',isset($complianceReport->comment_compliance) ? $complianceReport->comment_compliance : '',['class'=>'form-control','id'=>'comment_compliance','rows'=>'3','placeholder'=>'Comments of Compliance Officer'])}}
                                                    </div>
                                                </td>

                                            </tr>
</tbody>

                                        
                                    </table>
                                </td>
                            </tr>
                           
                            <!-- Score  Report End-->




                            <!-- Analysis of the findings start-->
                            <tr>
                                <td>
                                    <table width="100%" border="1" style="border-collapse:collapse;max-width: 660px; margin:0 auto; background: #fff;border: 1px solid #ddd;padding: 20px;">
                                        <thead>
                                            <tr>
                                                <th colspan="2" align="center">6. Analysis of the Findings</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td> <div class="form-group">
                                                        {{Form::textarea('analysis_findings',isset($complianceReport->analysis_findings) ? $complianceReport->analysis_findings : '',['class'=>'form-control','id'=>'specify_position','rows'=>'3'])}}
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>

                            <!-- Analysis of the findings End-->

                            <!-- Analysis of the findings start-->
                            <tr>
                                <td>
                                    <table width="100%" border="1" style="border-collapse:collapse;max-width: 660px; margin:0 auto; background: #fff;border: 1px solid #ddd;padding: 20px;">
                                        <thead>
                                            <tr>
                                                <th colspan="2" align="center">7. Conclusion and Recommendation</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td> <div class="form-group">
                                                        {{Form::textarea('conclusion_recommendation',isset($complianceReport->conclusion_recommendation) ? $complianceReport->conclusion_recommendation : '',['class'=>'form-control','id'=>'conclusion_recommendation','rows'=>'3'])}}
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <!-- Analysis of the findings End-->
                             <?php if($kycCompleteArray->is_finalapprove ==0  || $kycCompleteArray->is_finalapprove ==3) {?>
                            <tr>
                                <td>
                                    <div class="row">
                                        <div class="col-md-10">
                                            <input type="submit" name="submit" value="Save" class="btn btn-save float-right">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                             <?php } ?>
                        </tbody>
                    </table>
                {{ Form::close() }}
                    <!-- Table End start Here-->
                </div>
            </div>
        </div>
<?php } ?>
<!-- Complete Report Section End -->
            </div>

        </div>

    </div>

</div>



@endsection
@section('pageTitle')
User Detail
@endsection



@section('iframe')
 <div class="modal" id="corpwcapi">
        <div class="modal-dialog modal-xs">
          <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Get Similar</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
              <iframe id ="frameset"  frameborder="0" scrolling="no"></iframe>
            </div>
          </div>
        </div>
    </div>

<div class="modal" id="searchCases">

        <div class="modal-dialog modal-lg custom-modal">
          <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Search Cases</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body" style="padding: 0.5rem;">
              <iframe id ="frameset"  frameborder="0" scrolling="no"></iframe>
            </div>
          </div>
        </div>
</div>


    <div class="modal" id="btn-approved-iframe">
        <div class="modal-dialog modal-xs">
          <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Action</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
              <iframe id ="frameset"  frameborder="0" scrolling="no"></iframe>
            </div>
          </div>
        </div>
    </div>


    <div class="modal" id="btn-disapproved-iframe">
        <div class="modal-dialog modal-xs">
          <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Action</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body" style="padding: 0.5rem;">
              <iframe id ="frameset"  frameborder="0" scrolling="no"></iframe>
            </div>
          </div>
        </div>
    </div>

    <!--Corporate Report download -->
     <div class="modal fade" id="corpreportdownload" role="dialog" style='padding-top:50px;'>
        <div class="modal-dialog modal-sm">
          <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Report Download</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                
                <form method='post' id="corp_report_submit" action="{{route('report_corpcomplete_download',['userKycId'=>$userKycId,'user_id'=>$kycCompleteArray->user_id])}}">
                    @csrf
                <div class='row'>
                    <input type='hidden' name="userKycId" value="{{$userKycId}}">
                    <input type='hidden' name="user_id" value="{{$kycCompleteArray->user_id}}">
                    <div class='col-md-4'>Select Report Format:</div>
                    <div class='col-md-3'>
                        <select name="report_type" id = "report_type_corp" class='form-control'>
                            <option value="">Select</option>
                            <option value="Dexter">Dexter</option>
                            <option value="JuriDex">JuriDex</option>
                        </select>
                    </div>
                </div> 

                <div class='row' style="display:none; margin-top: 10px;" id='addressto' >

                    <div class='col-md-4'>Client Name:</div>
                    <div class='col-md-3'>
                        <input type='text' name="corp_jur_addressto" id="corp_jur_addressto" value="" placeholder="Address To">
                    </div>
                </div> 
                

                <div class="row" style='padding-top: 20px;'>
                    <div class='col-md-3'></div>
                    <div class='col-md-3'><button class='btn btn-save float-right'>Submit</button> </div> 
                </div>
                </form>  
            </div>
          </div>
        </div>
    </div>


<!--Report download -->
     <div class="modal fade" id="reportblockchain" role="dialog" style='padding-top:100px;' data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-sm">
            <div class="modal-content" style="width:600px !important;">
           <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Report History Download</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <iframe id ="frameset"  frameborder="0" scrolling="no"></iframe>
            </div>
          </div>
        </div>
    </div>

@endsection
@section('additional_css')
<link href="{{ asset('frontend/multiselect/jquery.multiselect.css')}}" rel="stylesheet" />
@endsection
@section('jscript')
<script src="{{ asset('frontend/multiselect/jquery.multiselect.js') }}"></script>
<script>
    var selectedTab = "{{ isset($secectedtab) ? $secectedtab : '' }}";
    //aler(selectedTab);
    $(function () {
       
        $('.multiselect_dropdown').multiselect({
            placeholder: 'Select',
            search: true,
            searchOptions: {
                'default': 'Search'
            },
            selectAll: true
        });
        $('.tenancydatepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        minDate: new Date()

        });
     $('.CompleteReport').on('click',function(){
        var token = "{{ csrf_token() }}";
        var user_kyc_id = "{{ $userKycId }}";
        var complete_pdf_report = "{{ URL::route('complete_pdf_report') }}";
        var myKeyVals = {_token: token, user_kyc_id: user_kyc_id};
            $.ajax({
                type: 'POST',
                url: complete_pdf_report,
                data: myKeyVals,
                dataType: "text",
                success: function (resultData) {
                    //$('#profileDetail_' + DynamicIdval).html(resultData);
                   // alert(resultData);Comapny Authorised Signatory
                }
            });
        });

     $('#corp_report_submit').validate({
               ignore: [],
               rules: {

                     report_type : {
                       required: true,
                    },
                },
                messages:{


                    report_type: {
                        required: messages.req_this_field,
                    },
                    submitHandler: function (form) {

                        //('#reportdownload').modal('hide');
                    }
                    
                }


            });

     
        $('#finalForm').validate({
               ignore: [],
               rules: {
                    accouunt_number:{
                        digits:true,
                        maxlength:30,
                    },
                     corr_accouunt_number:{
                        digits:true,
                        maxlength:30,
                    },
                     general_investigation : {
                       required: true,
                    },

                    references_investigation : {
                       required: true,
                    },
                    analysis_findings : {
                       required: true,
                    },
                     conclusion_recommendation : {
                       required: true,
                    },
                },
                messages:{

                    accouunt_number:{
                        digits: messages.onlydigits,
                        maxlength: messages.maxlen,
                    },
                     corr_accouunt_number:{
                        digits: messages.onlydigits,
                        maxlength:messages.maxlen,
                     },
                    general_investigation: {
                        required: messages.req_this_field,
                    },

                     references_investigation: {
                        required: messages.req_this_field,
                    },
                    analysis_findings: {
                        required: messages.req_this_field,
                    },
                     conclusion_recommendation: {
                        required: messages.req_this_field,
                    },
                }

            });
    });

    var messages = {
        get_users_wci_corp: "{{ URL::route('get_users_wci_corp') }}",
        get_users_wci_corp_single: "{{ URL::route('get_users_wci_corp_single') }}",
        delete_users: "{{ URL::route('delete_users') }}",
        data_not_found: "{{ trans('error_messages.data_not_found') }}",
        token: "{{ csrf_token() }}",
        token2: "{{ csrf_token() }}",
        APISecret: "{{config('common.APISecret')}}",
        gatwayurl: "{{config('common.gatwayurl')}}",
        contentType: "{{config('common.contentType')}}",
        gatwayhost: "{{config('common.gatwayhost')}}",
        apiKey: "{{config('common.apiKey')}}",
        groupId: "{{config('common.groupId')}}",
        content: "{{ $content }}",
        user_kyc_id: "{{ $userKycId }}",
        CorpName: "{{$corpName}}",
        CorpCountry: "{{ $corpCountry }}",
        onlydigits: "{{ trans('forms.CorpComplianceReport.server_error.only_digits') }}",
        maxlen: "{{ trans('forms.CorpComplianceReport.server_error.maxlen') }}",
        get_resolution_toolkit : "{{ URL::route('get_resolution_toolkit') }}",
        //CorpName: "Sakshay",
        //CorpCountry: "IMN",
    };

    var tab = "{{$tab}}";
    if(tab == 'tab05') {
        var selecttabid = "#ProfileScore";
    } else if(tab == 'tab08') {
        var selecttabid = "#ComplianceReport";
    } else if(tab == 'tab06') {
        var selecttabid = "#worldcheckapi";
    }

    $('.sub-tab').on('click',function(){
    $('.sub-tab').removeClass('active');
    $(this).addClass('active');
});

if(tab!="") {
   //$('.parent-tab').removeClass('active');
   // alert(this);
    $(selecttabid).click();
}

$('.parent-tab').on('click',function(){
    $('.parent-tab').removeClass('active');
    $(this).addClass('active');
});
if(selectedTab!='') {
// $('.'+selectedTab).trigger("click");
 }


</script>
<style>

.btn-bg-green{
    background-color: green;
    color: #fff;
    padding: 4px 5px;
    font-size: 11px;
    border-radius: 4px;
    text-transform: capitalize;
}
.btn-bg-green:hover{background-color: #21BA45;}
    .font-size-13 tr td, .font-size-13 tr th{
        font-size: 13px !important;
    }
  .dashbord-white .form-section {
    padding: 30px 15px 35px 20px;
}
.background-heading{
     background: #743939de;
    color: #fff !important;
    padding: 5px 10px;
}
.btn.btn-sm.btn-approved{
    font-size: 12px;
}
.set-label .view-detail {
    padding: 0px 35px;
}

.nav-tabs .nav-link{margin: 0 15px 0 0;}
.hover-green:hover{background: green !important;}
.customer-head{
    font-size: 20px;
    line-height: 24px;
    padding: 5px 10px;
}
.main-nested li {
    padding-bottom: 9px;
    position: relative;
}
.main-nested li:after{
    content: "";
    width: 5px;
    height: 4px;
    background: #743939de;
    position: absolute;
    left: -13px;
    border-radius: 50%;
    top: 7px;
}
.main-nested li.remove-bullet:after{display: none;}



.nested {
    padding-left: 18px;
}
.main-nested {
    font-size: 16px;
        padding-left: 10px;
}
.com-name {
    font-size: 15px;
    font-weight: 900;
    padding-bottom: 5px;
    display: block;
}
.btn.btn-send {
    color: #F7CA5A;
    background: #743939;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 4px;
    padding: 5px 10px 5px 10px;
    margin: 0px 5px;
    font-size: 12px;
    font-weight: 700;
    text-transform: capitalize;
}

</style>
<link href="{{ asset('frontend/multiselect/jquery.multiselect.css')}}" rel="stylesheet" />
<script type="text/javascript" src="{{asset('common/js/jquery.validate.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>
<script src="{{ asset('backend/js/wciapicall_corporate.js') }}" type="text/javascript"></script>
<script src="{{ asset('backend/js/individual_api.js') }}"></script>
<script src="{{ asset('common/js/toolkit.js') }}"></script>

<script type="text/javascript">
    var messagesG = {
        groupId : "{{Helpers::getgroupId()}}",
    };
    $(document).ready(function(){
         checktoolkit();
    });

</script>

@endsection