@extends('layouts.admin')

@section('content')


@php
$apiFullName ='';
$date_of_birthAPI = '';
$arrDocuments   =   Helpers::getDocumentsDropDown();
$arrProfStatus  =   Helpers::getProfStatusDropDown();
$arrCountry     =   Helpers::getCountryDropDown();
$annual         =   Helpers::getAnnualIncomeDropDown();
$estimated      =   Helpers::getEstimatedWealthDropDown();
$wealthSource   =   Helpers::getWealthSourceDropDown();
$fundSource     =   Helpers::getFundSourceDropDown();
$kycStatus      =   Helpers::getKycDetailsByKycID($userKycId);

@endphp

<style>

    .dashbord-white {
        min-height: 90vh !important;
        height:auto;
        overflow: auto;
   }
</style>
<script>
  document.body.style.background = "transparent";
</script>
<div class="col-md-12 dashbord-white">

    <div class="form-section">

        <div class="breadcrumbs">
            <div class="d-md-flex mb-3">

                <div class="breadcrumbs-bg">

                    <ul>

                        @if($benifinary['is_by_company']==0)
                        <li>
                            <a onclick="window.location.href = '{{ route('individual_user') }}'"> Manage Users</a>
                        </li>
                        <li>
                            Individual User Details
                        </li>
                        @else
                        <li>
                            <a onclick="window.location.href = '{{ route('corporate_user') }}'"> Manage Users</a>
                        </li>
                        <li>
                            <a onclick="window.location.href = '{{ route("corp_user_detail",['user_id' => $benifinary['corp_user_id'],'user_type' => $benifinary['user_type'],'user_kyc_id'=>$benifinary['corp_user_kyc_id'],'is_by_company'=>'0']) }}'"> Corporate User Details</a>
                        </li>
                        <li>
                            Individual User Details
                        </li>
                    
                        @endif
                         <?php if($rank_decision!=''){?>
                         <li>
                            <span class='btn btn-bg-green'>{{$rank_decision}}</span>
                        </li>
                      <?php }?>

                    </ul>

            

                </div>



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
                        @if($kycStatus->is_by_company == 1)
                    <div>
                       
                        <a class="btn btn-sm btn-save" onclick="window.location.href = '{{ route("corp_user_detail",['user_id' => $kycStatus->user_id,'user_type' => 2,'user_kyc_id'=>$benifinary['corp_user_kyc_id'],'is_by_company'=>1]) }}'">Back</a>
                    </div>
                    @endif

                         <?php
                           if($kycStatus->is_finalapprove == 1) {
                           ?>
                           <button type="button" class="btn btn-default btn-sm btn-approved" >Approved</button>
                             <?php
                           } else if($kycStatus->is_finalapprove == 2) {
                           ?>
                           <button type="button" class="btn btn-default btn-sm btn-disapproved" >Decline</button>
                           <?php } else if(($kycStatus->is_approve == 0 || $kycStatus->is_finalapprove == 0 || $kycStatus->is_finalapprove == 3) && $kycStatus->is_kyc_completed == 1 && $kycStatus->is_by_company == 0){ ?>
                            <button type="button" class="btn btn-default btn-sm btn-approved" data-toggle="modal" data-target="#btn-approved-iframe"  data-url="{{route('individual_final_approve',['id'=>$userKycId])}}" data-height="auto" data-width="100%" data-placement="top">Approve<!-- {{$kycStatus->is_by_company}} --></button>
                            <button type="button" class="btn btn-default btn-sm btn-disapproved" data-toggle="modal" data-target="#btn-disapproved-iframe"  data-url="{{route('individual_final_disapprove',['id'=>$userKycId])}}" data-height="90px" data-width="100%" data-placement="top">Decline</button>
                            <?php
                           }
                           ?>
                </div>
            </div>
        </div>
       
        <div class="tabs-section">

            <ul class="nav nav-tabs tabs-cls">
                @if($userData)
                <li class="nav-item active">
                    <a class="nav-link parent-tab  active" data-toggle="tab" href="#tab01">Registration Details</a>
                </li>
                 <li class="nav-item">
                    <a class="nav-link parent-tab" data-toggle="tab" href="#tab02">User KYC Details</a>
                </li>

                @else
                <li class="nav-item">
                    <a class="nav-link parent-tab active" data-toggle="tab" href="#tab02">User KYC Details</a>
                </li>

                @endif


               <!-- <li class="nav-item">
                    <a class="nav-link parent-tab" data-toggle="tab" href="#tab03">Third Party Details</a>
                </li>-->
                @if($isKycComplete == 1)
                <li class="nav-item">
                    <a class="nav-link parent-tab" data-toggle="tab" href="#tab06" id="worldcheckapi">World Check Data</a>
                </li>
                @endif


                <li class="nav-item">
                    <a class="nav-link parent-tab" data-toggle="tab" href="#tab04">Documents</a>
                </li>

                @if($benifinary['is_by_company']!=1)
                <li class="nav-item">
                    <a class="nav-link parent-tab" data-toggle="tab" href="#tab07">Additional Document</a>
                </li>
                @endif
                @if($isapiPulled == 1 || $isapiPulled == 0)
                <li class="nav-item">
                    <a class="nav-link parent-tab" data-toggle="tab" href="#tab05" id="ProfileScore">Profile Score</a>
                </li>
                @endif
                @if($kycStatus->is_by_company == 0)
                <li class="nav-item">
                    <a class="nav-link parent-tab" data-toggle="tab" href="#tab08" id="ComplianceReport">Compliance Report</a>
                </li>
                @endif
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">

                <?php 

                
   
                if($userData!='not') {
                    
                    ?>

                
                <div class="tab-pane container active" id="tab01">
                    <div class="row mb-4">
                        <div class="col-md-3 view-detail">
                            <label>Nationality</label>
                            <span>{{$arrCountry[@$userData->country_id]}}</span>
                        </div>
                        <div class="col-md-3 view-detail">
                            <label>First Name</label>
                            <span>{{$userData->f_name}}</span>
                        </div>
                        <div class="col-md-3 view-detail">
                            <label>Middle Name</label>
                            <span>{{$userData->m_name}}</span>
                        </div>
                        <div class="col-md-3 view-detail">
                            <label>Last Name</label>
                            <span>{{$userData->l_name}}</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 view-detail brown-i">
                            <label>Official Email</label>

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
                            }
                            ?>


                            <span>{{$userData->email}} &nbsp;&nbsp;<?php echo  $emailVarified;?></span>

                        </div>
                        <div class="col-md-3 view-detail brown-i">
                            <label>Official Mobile No.</label>

                            <span>{{'(+'.$userData->country_code.')'}}&nbsp;{{$userData->phone_no}} &nbsp;&nbsp;<?php echo $mobileVarified;?></span>

                        </div>

                    </div>

                </div>

                 

                <div class="tab-pane container" id="tab02">

                    <ul class="nav nav-pills tabs-cls">
                        <li class="nav-item active">
                            <a class="nav-link sub-tab" data-toggle="pill" href="#subtab01">Personal Information</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link sub-tab" data-toggle="pill" href="#subtab02">Professional Information</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link sub-tab" data-toggle="pill" href="#subtab03">Financial Information</a>
                        </li>
                    </ul>

                    <!-- Tab panes -->

                    
                    <div class="tab-content">

                        @if($userPersonalData && $userPersonalData->count())
                         <div class="tab-pane container active" id="subtab01">

                            <div class="row mb-4">

                                <div class="col-md-3 view-detail">
                                    <label>First Name</label>
                                    <span>{{$userPersonalData->title.' '.$userPersonalData->f_name}}</span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Middle Name</label>
                                    <span>{{$userPersonalData->m_name}}</span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Last Name</label>
                                    <span>{{$userPersonalData->l_name}}</span>
                                </div>
                            </div>


                            <?php
                            $genger=['M'=>'Male','F'=>'Female'];
                            ?>
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label>Gender</label>
                                    <span>{{$genger[$userPersonalData->gender]}}</span>
                                </div>
                                <?php
                                $apiFullName  = "";
                                $date_of_birthAPI = "";
                                    $date_of_birth = ($userPersonalData->date_of_birth != '' && $userPersonalData->date_of_birth != null) ? Helpers::getDateByFormat($userPersonalData->date_of_birth, 'Y-m-d', 'd/m/Y') : '';
                                    if($userPersonalData->date_of_birth!=''){
                                        $date_of_birthAPI = Helpers::getDateByFormat($userPersonalData->date_of_birth, 'Y-m-d', 'Y-m-d');
                                    }
                                    if($userPersonalData->f_name!="") {
                                        $apiFullName  = $userPersonalData->f_name." ".$userPersonalData->l_name;
                                    }
                                    if($userPersonalData->l_name!="") {
                                        $apiFullName  = $userPersonalData->f_name." ".$userPersonalData->m_name." ".$userPersonalData->l_name;
                                    }

                                        ?>
                                <div class="col-md-3 view-detail">
                                    <label>Date of Birth</label>
                                    <span>{{$date_of_birth}}</span>
                                </div>

                            </div>

                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label>Country of Birth</label>
                                    <span>{{$arrCountry[$userPersonalData->birth_country_id]}}</span>
                                </div>

                                <div class="col-md-3 view-detail">
                                    <label>City of Birth</label>
                                    <span>{{$userPersonalData->birth_city_id}}</span>
                                </div>

                            </div>
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label>Father's Name</label>
                                    <span>{{isset($userPersonalData->father_name) ? $userPersonalData->father_name: ''}}</span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Mother's First Name</label>
                                    <span>{{isset($userPersonalData->mother_f_name) ? $userPersonalData->mother_f_name:''}}</span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Mother's Maiden Name</label>
                                    <span>{{isset($userPersonalData->mother_m_name) ? $userPersonalData->mother_m_name: ''}}</span>
                                </div>

                            </div>
                            <div class="row mb-4">
                                @if(isset($userPersonalData->reg_no) && $userPersonalData->reg_no!='')
                                <div class="col-md-3 view-detail">
                                    <label>Registration Number</label>
                                    <span>{{isset($userPersonalData->reg_no) ? $userPersonalData->reg_no:''}}</span>
                                </div>
                                @endif

                                @if(isset($userPersonalData->reg_place) && $userPersonalData->reg_place!='')
                                <div class="col-md-3 view-detail">
                                    <label>Registration Place</label>
                                    <span>{{isset($userPersonalData->reg_place)? $userPersonalData->reg_place: ''}}</span>
                                </div>
                                @endif
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label>Nationality</label>
                                    <span>{{($userPersonalData->f_nationality_id>0)?$arrCountry[$userPersonalData->f_nationality_id]:''}}</span>
                                </div>
                                @if(isset($userPersonalData->sec_nationality_id) && $userPersonalData->sec_nationality_id!='')
                                <div class="col-md-3 view-detail">
                                    <label>Secondary Nationality</label>
                                    <span>{{($userPersonalData->sec_nationality_id>0) ? (($userPersonalData->sec_nationality_id == 9999) ? 'Not Applicable' : $arrCountry[$userPersonalData->sec_nationality_id]) : '' }}</span>
                                </div>
                                @endif
                            </div>

                            @if($documentsData && $documentsData->count())
                            @foreach($documentsData as $objDoc)
                            @php
                            $issuance_date=($objDoc->issuance_date!='' && $objDoc->issuance_date!=null) ? Helpers::getDateByFormat($objDoc->issuance_date, 'Y-m-d', 'd/m/Y') :'';
                            $expire_date=($objDoc->expire_date!='' && $objDoc->expire_date!=null) ? Helpers::getDateByFormat($objDoc->expire_date, 'Y-m-d', 'd/m/Y') :'';
                            @endphp
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label>Document Type</label>
                                    <span>{{isset($objDoc->doc_id) ? Helpers::getDocumentName($objDoc->doc_id) : ''}}</span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Document Number</label>
                                    <span>{{$objDoc->document_number}}</span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Issuance Date</label>
                                    <span>{{$issuance_date}}</span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Expiry Date</label>
                                    <span>{{$expire_date}}</span>
                                </div>

                            </div>
                            @endforeach
                            @endif

                            

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
                                        <?php if(@$objSocial->social_media_link) {?>
                                        <a href="<?php echo @$objSocial->social_media_link?>" target="_blank"><?php echo @$objSocial->social_media_link?></a>
                                        <?php }?>
                                    </span>

                                </div>
                            </div>
                            @endforeach
                            @endif
                            @php
                            $resStatus=['1'=>'Resident','2'=>'Non-Resident'];
                            $famStatus=['1'=>'Single','2'=>'Married','3'=>'Divorced','4'=>'Separated','5'=>'Minor','6'=>'Engaged','7'=>'Widowed'];
                            @endphp
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label>Residence Status</label>
                                    <span>{{(@$userPersonalData->residence_status>0)?@$resStatus[$userPersonalData->residence_status]:''}}</span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Family Status</label>
                                    <span>{{(@$userPersonalData->family_status>0)?@$famStatus[$userPersonalData->family_status]:''}}</span>

                                </div>
                            </div>
                           
                            @php
                            $eduLevel=['1'=>'Less than Baccalaureate','2'=>'Baccalaureate','3'=>'Bachelor','4'=>'Post Graduated','5'=>'Illiterate','6'=>'Other'];
                            $resicard=['1'=>'No','2'=>'Resident Alien Card (Green Card)','3'=>'Carte de sejour (France)','4'=>'Permanent Residency Card (Canada)']+['5'=>'Others'];
                            @endphp
                            <div class="row mb-4">


                                @if($userPersonalData->educational_level == '9')
                                <div class="col-md-3 view-detail">
                                    <label> Educational Other Level </label>
                                    <span>{{$userPersonalData->educational_level_other}} </span>
                                </div>
                                @else
                                <?php
                                 $arrEdu=Helpers::getEducationLevel();
                                ?>
                                <div class="col-md-3 view-detail">
                                    <label> Educational Level </label>
                                    <span>{{$arrEdu[$userPersonalData->educational_level]}} </span>
                                </div>
                                @endif

                            </div>
                            <div class="row mb-4">
                                <div class="col-md-4 view-detail">
                                    <label>Residency card </label>
                                    <span>{{($userPersonalData->is_residency_card>0)?$resicard[$userPersonalData->is_residency_card]:''}} </span>
                                </div>

                            </div>
                               
                            <div class="row mb-12">
                            <div class="col-md-12 view-detail">
                                <label>{{trans('forms.personal_Profile.Label.do_you_hold_held')}} </label>
                                <span> <p>{{($userPersonalData->do_you_hold == '0')?'No':'Yes' }}</p></span>
                            </div>

                             </div>

                           

                            @if($userPersonalData->political_position!='' && $userPersonalData->political_position!=null)
                            <?php
                            $arrPolitical=['1'=>'Senior position in the public sector','2'=>'Political position'];
                            $position   =   explode(',',$userPersonalData->political_position);

                            $opt_position1  =   '';
                            $opt_position2  =   '';
                            $chk1 =' ';
                            $chk2 =' ';


                            if(is_array($position) && count($position)){
                                if(isset($position[0]) && $position[0] !=''){
                                    $opt_position1=$arrPolitical[$position[0]];
                                    $chk1 = true;
                                }

                                if(isset($position[1]) && $position[1]!=''){
                                    $opt_position2=$arrPolitical[$position[1]];
                                    $chk2 = true;
                                }
                            }
                            ?>
                            <div class="row mb-4">
                                <div class="col-md-6 view-detail">

                                <label> 
                                    @if(isset($opt_position1) && $opt_position1 !== '')
                                    {{Form::checkbox('opt_positin1','1',$chk1,['class'=>'','disabled'])}}
                                    {{$opt_position1}} &nbsp; &nbsp;&nbsp;  
                                    
                                    @endif
                                    
                                    @if(isset($opt_position2) && $opt_position2 !== '')
                                    {{Form::checkbox('opt_positin2','1',$chk2,['class'=>'','disabled'])}}

                                   {{$opt_position2}}
                                    @endif
                                   
                               </label>

                                {{Form::label('',trans('forms.personal_Profile.Label.political_position_dec'),['class'=>''])}}
                                    
                                <p> {{($userPersonalData->political_position_dec!='' && $userPersonalData->political_position_dec!=null)?$userPersonalData->political_position_dec:''}}</p>
                                </div>

                            </div>
                            @endif

                            <div class="row mb-12">
                            <div class="col-md-12 view-detail">
                                <label>{{trans('forms.personal_Profile.Label.are_you_related_directly_or_indirectly')}} </label>
                                <span> <p>{{($userPersonalData->you_related_directly == '0')?'No':'Yes' }}</p></span>
                            </div>
                            </div>

                            @if($userPersonalData->related_political_position!='' && $userPersonalData->related_political_position!=null)
                            <?php


                    $arrRelatedPolitical=['3'=>'Senior position in the public sector','4'=>'Political position'];
                            $relatedPosition   =   explode(',',$userPersonalData->related_political_position);

                            $opt_position3  =   '';
                            $opt_position4  =   '';
                            $chk3 = '';
                            $chk4 = '';

                            if(is_array($relatedPosition) && count($relatedPosition)){
                                if(isset($relatedPosition[0]) && $relatedPosition[0]!=''){
                                    $opt_position3=$arrRelatedPolitical[$relatedPosition[0]];
                                    $chk3 = true;
                                }

                                if(isset($relatedPosition[1]) && $relatedPosition[1]!=''){
                                    $opt_position4=$arrRelatedPolitical[$relatedPosition[1]];
                                    $chk4 = true;
                                }
                            }
                            ?>


                            <div class="row mb-4">
                                <div class="col-md-6 view-detail">
                                    <label> 
                                 @if(isset($opt_position3) && $opt_position3 !== '')
                                  {{Form::checkbox('related_posi','1',$chk3,['class'=>'','disabled'])}}
                                    {{$opt_position3}}          
                                     @endif
                                     &nbsp;&nbsp;&nbsp;

                                     @if(isset($opt_position4) && $opt_position4 !== '')
                                     {{Form::checkbox('related_posit','1',$chk4,['class'=>'','disabled'])}}
                                     {{$opt_position4}}
                                     @endif
                                    </label>

                                    {{Form::label('',trans('forms.personal_Profile.Label.political_position_dec'),['class'=>''])}}

                                    <p> {{($userPersonalData->related_political_position_dec!='' && $userPersonalData->related_political_position_dec!=null)?$userPersonalData->related_political_position_dec:''}}</p>
                                </div>

                            </div>
                            @endif
                            



                        @if($familyData && $familyData->count())
                            <div class="row mb-4">
                                <div class="heading col-md-12">
                                    <h4>Family Information</h4>
                                </div>

                            </div>

                            <div class="row mb-4">
                                @if(isset($familyData->spouse_f_name))
                                <div class="col-md-3 view-detail">
                                    <label> Spouse First Name </label>
                                    <span>{{(isset($familyData->spouse_f_name) ? $familyData->spouse_f_name : '')}} </span>
                                </div>
                                @endif
                                @if(isset($familyData->spouse_m_name))
                                <div class="col-md-3 view-detail">
                                    <label> Spouse Maiden Name </label>
                                    <span> {{(isset($familyData->spouse_m_name) ? $familyData->spouse_m_name : '') }}</span>
                                </div>
                                @endif
                            </div>
                            <div class="row mb-4">
                                @if(isset($familyData->is_spouse_profession))
                                <div class="col-md-3 view-detail">
                                    @php
                                        $spouseProStatus=Helpers::getProfStatusDropDown();

                                    @endphp
                                    <label>Spouse Professional Status </label>
                                    <span> {{(isset($familyData->is_spouse_profession) ? $spouseProStatus[$familyData->is_spouse_profession] : '') }} </span>
                                </div>
                                @endif
                                @if(isset($familyData->spouse_profession))
                                <div class="col-md-3 view-detail">
                                    <label> Spouse’s Profession (if only) </label>
                                    <span>{{(isset($familyData->spouse_profession) ? $familyData->spouse_profession : '') }} </span>
                                </div>
                                @endif
                                @if(isset($familyData->spouse_employer) && $familyData->spouse_employer!='')
                                <div class="col-md-3 view-detail">
                                    <label> Spouse’s Employer (if any) </label>
                                    <span> {{(isset($familyData->spouse_employer) ? $familyData->spouse_employer : '') }} </span>
                                </div>
                                @endif
                            </div>

                            @php
                            $childInfo = [];
                            $childInfo  = isset($familyData->spouce_child_info) ? $familyData->spouce_child_info : [];
                            $arrChildInfo=[];
                            @endphp
                            @if($childInfo!='' && $childInfo!=null)
                            @php
                                $arrChildInfo=  json_decode($childInfo);
                            @endphp


                            @if(isset($familyData->spouce_child_info))
                            @if(count($arrChildInfo)>0)
                            <div class="row mb-4">
                                <div class="heading col-md-12 sub-heading">
                                    <h4>Children Information</h4>
                                </div>

                            </div>
                            @php $i=1;@endphp
                                @foreach($arrChildInfo as $key=>$chinfo)
                                <div class="row mb-4">
                                    <div class="col-md-3 view-detail">
                                        <label> Child {{$i}} </label>
                                        <span> {{$chinfo->child_name}} </span>
                                    </div>
                                    <div class="col-md-3 view-detail">
                                        <label> Date of Birth</label>
                                        <span>{{$chinfo->child_dob}} </span>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                            @endif

                            @endif

                            @endif
                            
                          

                            @if($residentialData && $residentialData->count())
                            <div class="row mb-4">
                                <div class="heading col-md-12">
                                    <h4>Residential Information</h4>
                                </div>

                            </div>
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label> Country </label>
                                    <span> {{isset($arrCountry[$residentialData->country_id]) ? $arrCountry[$residentialData->country_id] : ''}} </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label> City </label>
                                    <span> {{isset($residentialData->city_id) ? $residentialData->city_id : ''}} </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label> Region </label>
                                    <span> <span> {{isset($residentialData->region) ? $residentialData->region : ''}} </span> </span>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label> Building </label>
                                    <span> {{ isset($residentialData->building_no) ? $residentialData->building_no : ''}} </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Floor/ Apartment Number</label>
                                    <span> {{isset($residentialData->floor_no) ? $residentialData->floor_no: ''}} </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label> Street </label>
                                    <span> {{isset($residentialData->street_addr) ? $residentialData->street_addr : ''}} </span>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label> Postal Code </label>
                                    <span> {{isset($residentialData->postal_code) ? $residentialData->postal_code : '' }} </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>P.O Box </label>
                                    <span> {{isset($residentialData->post_box) ? $residentialData->post_box : ''}} </span>
                                </div>

                            </div>
                            <div class="row mb-4">

                                <div class="col-md-3 view-detail">
                                    <label>Email </label>
                                    <span> {{isset($residentialData->addr_email) ? $residentialData->addr_email: ''}} </span>
                                </div>

                            </div>
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label> Telephone No. </label>
                                    <span> {{isset($residentialData->addr_phone_no) ? "(+".$residentialData->tele_country_code.") ". $residentialData->addr_phone_no: ''}}  </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Mobile No. </label>
                                    <span> {{isset($residentialData->addr_mobile_no) ? "(+".$residentialData->addr_country_code.") ". $residentialData->addr_mobile_no : ''}}</span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Fax No. </label>
                                    <span>
                                    {{isset($residentialData->addr_fax_no) ? "(+".$residentialData->fax_country_code.") ". $residentialData->addr_fax_no : ''}}
                                    </span>
                                </div>

                            </div>
                            @endif
                            
                        </div>
                        @else
                        <div class="tab-pane container" id="subtab01">
                           No Personal Information Found.
                        </div>

                        @endif

                       <!--Professional information Page-->

                        @if($professionalData && $professionalData->count())

                            @php
                            $profStatus=Helpers::getProfessionalStatus();

                            @endphp
                            <div class="tab-pane container" id="subtab02">
                            <div class="row mb-4">

                                <div class="col-md-3 view-detail">
                                    <label>Professional Status</label>
                                    <span>{{($professionalData->prof_status)? $profStatus[$professionalData->prof_status]:''}}</span>
                                </div>

                            </div>

                        @if(in_array($professionalData->prof_status, ['1','3','4']))

                            <div class="row mb-4">
                                <div class="col-md-6 view-detail">
                                    <label>Profession Occupation in detail</label>
                                    <p>

                                {{isset($professionalData->profession_occu) ? $professionalData->profession_occu:''}}
                                     </p>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-6 view-detail">
                                    <label>Position/Job title</label>
                                    <p>

                                        {{isset($professionalData->position_job_title) ? $professionalData->position_job_title:''}}
</p>
                                </div>
</div>

                          @php
                           $date_employment=($professionalData->date_employment!='' && $professionalData->date_employment!=null) ? Helpers::getDateByFormat($professionalData->date_employment, 'Y-m-d', 'd/m/Y') :'';
                           setlocale(LC_MONETARY, 'en_US');
                           @endphp
                            <div class="row mb-4">
                                <div class="col-md-6 view-detail">
                                    <label>Date of Employment</label>
                                    <span>{{$date_employment}}</span>
                                </div>
                            </div>
                        @endif


                        @if(in_array($professionalData->prof_status, ['6']))

                            <div class="row mb-4">
                                <div class="col-md-6 view-detail">
                                    <label>Previous Profession/Occupation</label>
                                    <p>

                                {{isset($professionalData->prof_detail)? $professionalData->prof_detail:''}}
                                     </p>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-6 view-detail">
                                    <label>Last Position/Job title</label>
                                    <p>

                                        {{isset($professionalData->position_title) ? $professionalData->position_title:''}}
</p>
                                </div>
</div>

                          @php
                           $date_retirement=($professionalData->date_retirement!='' && $professionalData->date_retirement!=null) ? Helpers::getDateByFormat($professionalData->date_retirement, 'Y-m-d', 'd/m/Y') :'';
                           setlocale(LC_MONETARY, 'en_US');
                           @endphp
                            <div class="row mb-4">
                                <div class="col-md-6 view-detail">
                                    <label>Date of retirement</label>
                                    <span>{{$date_retirement}}</span>
                                </div>
                                <div class="col-md-6 view-detail">
                               <label>Last monthly salary if retired</label>
                               <span>{{isset($professionalData->last_monthly_salary) ? money_format('%i',$professionalData->last_monthly_salary) : ''}}</span>
                               </div>
                            </div>
                        @endif

                        @if($professionalData->prof_status=='8')
                        <div class="row mb-4">
                            <div class="col-md-6 view-detail">
                               <label>Other Profession Status</label>
                               <span>{{($professionalData->other_prof_status!='' && $professionalData->other_prof_status!=null)?$professionalData->other_prof_status:''}}</span>
                            </div>
                        </div>
                       @endif

                        @if($professionalData->additional_activities!='')
                        <div class="row mb-4">
                            <div class="col-md-6 view-detail">
                               <label>Additional Activities</label>
                               <span>{{($professionalData->additional_activities)?$professionalData->additional_activities:''}}</span>
                            </div>
                        </div>
                        @endif
                        <!--Pfofessional Informational end-->

                            @if(in_array($professionalData->prof_status, ['3', '4']) && $commercialData && $commercialData->count())
                            <div class="row mb-4">
                                <div class="heading col-md-12">
                                    <h4>For Sole Proprietorship/Self Employed, Please Specify</h4>
                                </div>

                            </div>

                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label>Commercial name</label>
                                    <span>{{$commercialData->comm_name}}</span>
                                </div>

                            </div>
                            <?php
                            $date_of_establish  =   ($commercialData->date_of_establish!='' && $commercialData->date_of_establish!=null) ? Helpers::getDateByFormat($commercialData->date_of_establish, 'Y-m-d', 'd/m/Y') :'';


                            ?>
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label>Date of establishment</label>
                                    <span>{{$date_of_establish}}</span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Country of establishment</label>
                                    <span>{{isset($commercialData->country_establish_id)?$arrCountry[$commercialData->country_establish_id]:''}}</span>
                                </div>

                            </div>

                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label>Commercial Register No.</label>
                                    <span>{{isset($commercialData->comm_reg_no) ? $commercialData->comm_reg_no : ''}}</span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Place</label>
                                    <span>{{isset($commercialData->comm_reg_place) ? $commercialData->comm_reg_place : ''}}</span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Country</label>
                                    <span>{{($commercialData->comm_country_id>0)?$arrCountry[$commercialData->comm_country_id]:''}}</span>
                                </div>

                            </div>


                            @php
                              $arrarVal=[];
                              $countryVal=Helpers::getCountryDropDown();

                              if(isset($commercialData['country_activity'])) {
                              $arrarVal = explode(',',$commercialData['country_activity']);
                              }
                              else {
                              $arrarVal=[];

                              }
                              @endphp
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label>Country(ies) of Activity</label>
                                <?php
                                    $arrSel=[];
                                    $i=0;
                                ?>
                                @foreach($countryVal as $Dval)
                                    @if(@in_array($Dval, $arrarVal))
                                    @php
                                     $arrSel[$i]=$Dval;
                                     $i++;
                                    @endphp
                                    @endif     
                                @endforeach 
                                @php $sources=implode(',  ',$arrSel); @endphp
                                        <span> {{$sources}}</span>
                                </div>
                            </div>
                               

                            
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label>Syndicate No.</label>
                                    <span>{{isset($commercialData->syndicate_no) ? $commercialData->syndicate_no : ''}}</span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Taxation ID No.</label>
                                    <span>{{isset($commercialData->taxation_no) ? $commercialData->taxation_no:''}}</span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Taxation ID</label>
                                    <span>{{isset($commercialData->taxation_id) ? $commercialData->taxation_id : ''}}</span>
                                </div>

                            </div>
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label>Annual Business Turnover (in $)</label>
                                    <span>$ {{isset($commercialData->annual_turnover) ? $commercialData->annual_turnover : ''}}</span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Main Suppliers</label>
                                    <span>{{isset($commercialData->main_suppliers) ? $commercialData->main_suppliers: ''}}</span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Main Clients</label>
                                    <span>{{isset($commercialData->main_clients) ? $commercialData->main_clients : ''}}</span>
                                </div>

                            </div>
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label>Name of authorized signatory</label>
                                    <span>{{isset($commercialData->authorized_signatory) ? $commercialData->authorized_signatory : ''}}</span>
                                </div>

                            </div>


                            @if($bussAddrData && $bussAddrData->count())
                            <div class="row mb-4">
                                <div class="heading col-md-12">
                                    <h4>Business Address</h4>
                                </div>

                            </div>

                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label> Country </label>
                                    <span> {{($bussAddrData->buss_country_id>0)?$arrCountry[$bussAddrData->buss_country_id]:''}} </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label> City </label>
                                    <span> {{$bussAddrData->buss_city_id}} </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label> Region </label>
                                    <span> {{$bussAddrData->buss_region}}  </span>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label> Building </label>
                                    <span> {{$bussAddrData->buss_building}} </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label> Floor </label>
                                    <span> {{$bussAddrData->buss_floor}} </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label> Street </label>
                                    <span> {{$bussAddrData->buss_street}} </span>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label> Postal Code </label>
                                    <span> {{$bussAddrData->buss_postal_code}} </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>P.O Box </label>
                                    <span> {{$bussAddrData->buss_po_box_no}} </span>
                                </div>

                            </div>

                            <div class="row mb-4">

                                <div class="col-md-3 view-detail">
                                    <label>Email </label>
                                    <span> {{$bussAddrData->buss_email}} </span>
                                </div>

                            </div>
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label> Telephone No. </label>
                                    <span> {{isset($bussAddrData->buss_telephone_no) ? "(+".$bussAddrData->country_code_phone.") ".$bussAddrData->buss_telephone_no : ''}} </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Mobile No. </label>
                                    <span>
                                    {{isset($bussAddrData->buss_mobile_no) ? "(+".$bussAddrData->buss_country_code.") ".$bussAddrData->buss_mobile_no : ''}}
                                    </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Fax No. </label>
                                    <span>

                                    {{isset($bussAddrData->buss_fax_no) ? "(+".$bussAddrData->country_code_fax.") ".$bussAddrData->buss_fax_no : ''}}
                                    </span>
                                </div>

                            </div>
                            @endif




                            <div class="row mb-4">
                                <div class="heading col-md-12">
                                    <h4>Mailing Address</h4>
                                </div>

                            </div>
                            @php
                            $isHold =   ['1'=>'Yes','0'=>'No'];
                            @endphp
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label> Hold Mail</label>
                                    <span>{{($bussAddrData->is_hold_mail=='1')?$isHold[$bussAddrData->is_hold_mail]:'No'}} </span>
                                </div>

                            </div>
                            @php
                            $mailAddr=['1'=>'Residential Address','2'=>'Secondary Address','3'=>'Business Address'];
                            @endphp
                            <div class="row mb-4">
                                <div class="col-md-6 view-detail">
                                    <label> In case of sending documents through mail, please specify mailing address </label>
                                    <span> {{($commercialData->mailing_address>0)?$mailAddr[$commercialData->mailing_address]:''}} </span>
                                </div>

                            </div>
                            <div class="row mb-4">
                                <div class="col-md-6 view-detail">
                                    <label> Relation with Exchange Company/ Establishment </label>
                                    <p> Are you or your spouse or any of your dependents (ascendants and descendants) the owner or shareholder or partner or director or signatory of an exchange establishment/ company? If yes please disclose the full names of the concerned parties and the full name and details of the establishment / company </p>
                                </div>

                            </div>
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label></label>
                                    <span> {{($commercialData->relation_exchange_company=='1')?'Yes':'No'}}</span>
                                </div>

                            </div>
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label>Name of Concerned Party</label>
                                    <span> {{$commercialData->concerned_party}}</span>
                                </div>

                            </div>
                            <div class="row mb-4">
                                <div class="col-md-6 view-detail">
                                    <label>Name/Details of Establishment/Company</label>
                                    <span> {{$commercialData->details_of_company}}</span>
                                </div>

                            </div>

                            @endif


                        </div>
                        @else
                        <div class="tab-pane container" id="subtab02">
                          No Profesional Information Found.
                        </div>
                        @endif

                        @if($financialData && $financialData->count())


                         <div class="tab-pane container" id="subtab03">
                            <div class="row mb-4">
                                <div class="col-md-6 view-detail">
                                    <label>Source of funds</label>

                                    <span>{{ isset($financialData->source_funds) ? $financialData->source_funds :''}}</span>

                                </div>
                                <div class="col-md-6 view-detail text-justify">
                                    <?php
                                    $arraVal=[];
                                    $funds=Helpers::getCountryDropDown();
                                    if(isset($financialData->jurisdiction_funds)) {
                                     $arraVal = explode(',',$financialData->jurisdiction_funds);
                                    
                                     }
                                     $sel=[];
                                     $i=0;
                                     foreach($funds as $key=>$val){
                                          if(@in_array($key, $arraVal))
                                          {
                                            $sel[$i]=$val;
                                          }
                                          $i++;
                                     }
                                     $selectedData = implode(', ',$sel);
                                    ?>


                                    <label>Jurisdiction of Funds</label>

                                    <span> {{$selectedData}}
                                    </span>
                                </div>

                            </div>


                            <div class="row mb-4">
                                <div class="col-md-6 view-detail">
                                    <label>Annual Income (in USD)</label>
                                    <span>{{($financialData->annual_income>0)?$annual[$financialData->annual_income]:''}}</span>
                                </div>
                                <div class="col-md-6 view-detail">
                                    <label>Estimated Wealth (in USD)</label>
                                    <span>{{($financialData->estimated_wealth>0)?$estimated[$financialData->estimated_wealth]:''}}</span>
                                </div>

                            </div>



                            <div class="row mb-4">
                                <div class="col-md-6 view-detail">
                                    <label>Kindly provide details on the source(s) of your wealth</label>
                                    <span>{{ isset($financialData->wealth_source) ? $financialData->wealth_source : ''}}</span>
                                </div>

                            </div>
                             @isset($wealthSource[$financialData->wealth_source])
                                @if($wealthSource[$financialData->wealth_source]=='Other')
                                <div class="row mb-4">
                                    <div class="col-md-6 view-detail">
                                        <label>Other source(s) of your wealth</label>
                                        <span>{{($financialData->other_wealth_source!='' && $financialData->other_wealth_source!=null)?$financialData->other_wealth_source:''}}</span>
                                    </div>

                                </div>
                                @endif
                            @endif

                            <hr>

                            <?php
                            if($financialData->us_fetch_regulation == 1) { ?>


                            <div class="row mb-4">

                                <div class="col-md-6 view-detail">
                                    <label> US FATCA regulation:</label>
                                    <span>Yes</span>
                                </div>


                            </div>



                            <?php
                            if(isset($financialData->us_fetch_regulation) == 1) { ?>
                            <div class="row mb-4">
                                 <div class="col-md-6 view-detail">
                                    <label>Specify  Country</label>
                                    <span>

                                        <?php
                                        if($financialData->please_specify == 0) {
                                            echo "US Citizen";
                                        } else if($financialData->please_specify == 1) {
                                                echo "US Green Card Holder";
                                           } else if($financialData->please_specify == 2) {
                                             echo "Have been more than 180 days in US";
                                        }
                                        ?>



                                        </span>
                                </div>
                                <div class="col-md-6 view-detail">
                                    <label>US TIN Code</label>
                                    <span>{{isset($financialData->tin_code) ? $financialData->tin_code : ''}}</span>
                                </div>


                            </div>

                            <?php } ?>

                            <?php } else { ?>

                            <div class="row mb-4">

                                <div class="col-md-6 view-detail">
                                    <label> US FATCA regulation:</label>
                                    <span>No</span>
                                </div>


                            </div>





                            <div class="row mb-4">
                                <div class="col-md-6 view-detail">
                                    <label>TIN Country</label>
                                    <span>
                                        {{isset($arrCountry[$financialData->tin_country_name])?$arrCountry[$financialData->tin_country_name]:''}}
                                    </span>
<br><br><br>
                                    <label>TIN Number</label>
                                    <span>
                                        <?php
                                      // echo "==>".$financialData->not_applicable;

                                        if($financialData->not_applicable == 1){ ?>
                                            {{isset($financialData->tin_number)?$financialData->tin_number:'Not Applicable'}}
                                        <?php
                                        } else { ?>

                                         {{isset($financialData->tin_number)?$financialData->tin_number:'Not Applicable'}}
                                         <?php } ?>
                                    </span>
                                    
                                </div>
                                <div class="col-md-6 view-detail">
                                    <label>Was US citizenship abandoned after June 2014?</label>
                                    <span>{{isset($financialData->is_abandoned) ? ($financialData->is_abandoned=='1')?'Yes':'No' : ''}}</span>
                                </div>

                            </div>

                             <?php
                             if(isset($financialData->date_of_abandonment)) {
                                $date_of_abandonment=($financialData->date_of_abandonment!='' && $financialData->date_of_abandonment!=null) ? Helpers::getDateByFormat($financialData->date_of_abandonment, 'Y-m-d', 'd/m/Y') :'';
                                 } else {
                                    $date_of_abandonment = '';
                                 }

                                 if($financialData->is_abandoned=='1') {
                                 ?>
                            <div class="row mb-4">
                                <div class="col-md-6 view-detail">
                                    <label>Please specify date of abandonment</label>
                                    <span>{{isset($date_of_abandonment) ? $date_of_abandonment : ''}}</span>
                                </div>

                            </div>
                            <div class="row mb-4">
                                <div class="col-md-6 view-detail">
                                    <label>Reason</label>
                                    <p>{{isset($financialData->abandonment_reason) ? $financialData->abandonment_reason : ''}}</p>
                                </div>

                            </div>
                                 <?php } ?>
                           <!-- <div class="row mb-4">
                                <div class="col-md-6 view-detail">
                                    <label>Justification (If reason B is selected)</label>
                                    <p>{{isset($financialData->justification) ? $financialData->justification : ''}}</p>
                                </div>

                            </div>
                            <div class="row mb-4">
                                <div class="col-md-6 view-detail">
                                    <label>TIN Country</label>
                                    <span>{{isset($arrCountry[$financialData->tin_country_name])?$arrCountry[$financialData->tin_country_name]:''}}</span>
                                </div>
                                <div class="col-md-6 view-detail">
                                    <label>TIN (Taxpayer Identification Number) or functional equivalent of the TIN</label>
                                    <span>{{isset($financialData->tin_number) ? $financialData->tin_number : ''}}</span>
                                </div>

                            </div>-->

                            <?php } ?>

                        </div>
                        @else
                        <div class="tab-pane container" id="subtab03">
                            No Financial Information Found.
                        </div>
                        @endif

                    </div>
                </div>

                <?php } else {
                   
                    ?>
               
                <div class="tab-pane container active" id="tab02">

                    <ul class="nav nav-pills tabs-cls">
                        <li class="nav-item">
                            <a class="nav-link sub-tab active" data-toggle="pill" href="#subtab01">Personal Information</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link sub-tab" data-toggle="pill" href="#subtab02">Professional Information</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link sub-tab" data-toggle="pill" href="#subtab03">Financial Information</a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                       
                        
                        @if($userPersonalData && $userPersonalData->count())
                         <div class="tab-pane container active" id="subtab01">

                            <div class="row mb-4">

                                <div class="col-md-3 view-detail">
                                    <label>First Name</label>
                                    <span>{{$userPersonalData->title.' '.$userPersonalData->f_name}}</span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Middle Name</label>
                                    <span>{{$userPersonalData->m_name}}</span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Last Name</label>
                                    <span>{{$userPersonalData->l_name}}</span>
                                </div>
                            </div>
                            <?php
                            $genger=['M'=>'Male','F'=>'Female'];
                            ?>

                             
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label>Gender</label>
                                    <span>{{isset($userPersonalData->gender) ? $genger[$userPersonalData->gender] : ''}}</span>
                                </div>
                                <?php
                                $apiFullName  = "";
                                $date_of_birthAPI = "";
                                    $date_of_birth = ($userPersonalData->date_of_birth != '' && $userPersonalData->date_of_birth != null) ? Helpers::getDateByFormat($userPersonalData->date_of_birth, 'Y-m-d', 'd/m/Y') : '';
                                    if($userPersonalData->date_of_birth!=''){
                                        $date_of_birthAPI = Helpers::getDateByFormat($userPersonalData->date_of_birth, 'Y-m-d', 'Y-m-d');
                                    }
                                    if($userPersonalData->f_name!="") {
                                        $apiFullName  = $userPersonalData->f_name." ".$userPersonalData->l_name;
                                    }
                                    if($userPersonalData->m_name!="") {
                                        $apiFullName  = $userPersonalData->f_name." ".$userPersonalData->m_name." ".$userPersonalData->l_name;
                                    }


                                        ?>
                                <div class="col-md-3 view-detail">
                                    <label>Date of Birth</label>
                                    <span>{{$date_of_birth}}</span>
                                </div>

                            </div>

                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label>Country of Birth</label>
                                    <span>{{ isset($arrCountry[$userPersonalData->birth_country_id]) ? $arrCountry[$userPersonalData->birth_country_id] : ''}}</span>
                                </div>

                                <div class="col-md-3 view-detail">
                                    <label>City of birth</label>
                                    <span>{{isset($userPersonalData->birth_city_id) ? $userPersonalData->birth_city_id : ''}}</span>
                                </div>

                            </div>
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label>Father's Name</label>
                                    <span>{{$userPersonalData->father_name}}</span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Mother's First Name</label>
                                    <span>{{$userPersonalData->mother_f_name}}</span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Mother's Maiden Name</label>
                                    <span>{{$userPersonalData->mother_m_name}}</span>
                                </div>

                            </div>
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label>Registration Number</label>
                                    <span>{{$userPersonalData->reg_no}}</span>

                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Registration Place</label>
                                    <span>{{$userPersonalData->reg_place}}</span>
                                </div>



                            </div>

 

                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label>Nationality</label>
                                    <span>{{($userPersonalData->f_nationality_id>0)?$arrCountry[$userPersonalData->f_nationality_id]:''}}</span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Secondary Nationality</label>
                                    <span>
                                    {{($userPersonalData->sec_nationality_id>0) ? (($userPersonalData->sec_nationality_id == 9999) ? 'Not Applicable' : $arrCountry[$userPersonalData->sec_nationality_id]) : '' }}
                                    </span>
                                </div>

                            </div>



                            @if($documentsData && $documentsData->count())
                            @foreach($documentsData as $objDoc)
                            @php
                            $issuance_date=($objDoc->issuance_date!='' && $objDoc->issuance_date!=null) ? Helpers::getDateByFormat($objDoc->issuance_date, 'Y-m-d', 'd/m/Y') :'';
                            $expire_date=($objDoc->expire_date!='' && $objDoc->expire_date!=null) ? Helpers::getDateByFormat($objDoc->expire_date, 'Y-m-d', 'd/m/Y') :'';
                            @endphp
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label>Document Type</label>
                                    <span>{{isset($objDoc->doc_id) ? Helpers::getDocumentName($objDoc->doc_id) : ''}}</span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Document Number</label>
                                    <span>{{$objDoc->document_number}}</span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Issuance Date</label>
                                    <span>{{$issuance_date}}</span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Expiry Date</label>
                                    <span>{{$expire_date}}</span>
                                </div>

                            </div>
                            @endforeach
                            @endif

                            @if($socialmediaData && $socialmediaData->count())
                            @foreach($socialmediaData as $objSocial)

                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label>Social Media</label>
                                    <span>{{$objSocial->socialmedia}}</span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Social Media Link</label>
                                    <span>{{$objSocial->social_media_link}}</span>

                                </div>
                            </div>
                            @endforeach
                            @endif
                            @php
                            $resStatus=['1'=>'Resident','2'=>'Non-Resident'];
                            $famStatus=['1'=>'Single','2'=>'Married','3'=>'Divorced','4'=>'Separated','5'=>'Minor','6'=>'Engaged','7'=>'Widowed'];
                            @endphp
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label>Residence Status</label>
                                    <span>{{($userPersonalData->residence_status>0)?$resStatus[$userPersonalData->residence_status]:''}}</span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Family Status</label>
                                    <span>{{($userPersonalData->family_status>0)?$famStatus[$userPersonalData->family_status]:''}}</span>

                                </div>
                            </div>
                            

                            @php
                            $eduLevel=Helpers::getEducationLevel();
                            $resicard=['1'=>'No','2'=>'Resident Alien Card (Green Card)','3'=>'Carte de sejour (France)','4'=>'Permanent Residency Card (Canada)']+['5'=>'Others'];
                            
                            @endphp

                            <div class="row mb-4">
                                 @if($userPersonalData->educational_level!='8')
                                <div class="col-md-3 view-detail">
                                    <label> Educational Level </label>
                                    <span>{{($userPersonalData->educational_level>0)?$eduLevel[$userPersonalData->educational_level]:''}} </span>

                                </div>
                                @endif
                                @if($userPersonalData->educational_level=='8')
                                <div class="col-md-3 view-detail">
                                    <label> Educational Other Level </label>
                                    <span>{{$userPersonalData->educational_level_other}} </span>
                                </div>

                                @endif

                            </div>
                            <div class="row mb-4">
                                <div class="col-md-4 view-detail">
                                    <label>Residency card </label>
                                    <span>{{($userPersonalData->is_residency_card>0)?$resicard[$userPersonalData->is_residency_card]:''}} </span>
                                </div>

                            </div>
                            
                            <div class="row mb-12">
                            <div class="col-md-12 view-detail">
                                <label>{{trans('forms.personal_Profile.Label.do_you_hold_held')}} </label>
                                <span> <p>{{($userPersonalData->do_you_hold == '0')?'No':'Yes' }}</p></span>
                            </div>

                             </div>
                            

                            @if($userPersonalData->do_you_hold == '1')

                            @if($userPersonalData->political_position!='' && $userPersonalData->political_position!=null)

                            <?php
                            $arrPolitical=['1'=>'Senior position in the public sector','2'=>'Political position'];
                            $position   =   explode(',',$userPersonalData->political_position);

                            $opt_position1  =   '';
                            $opt_position2  =   '';
                            $chk1= '';
                            $chk2= '';

                            if(is_array($position) && count($position)){
                                if(isset($position[0]) && $position[0]!=''){
                                    $opt_position1=$arrPolitical[$position[0]];
                                    $chk1= true;
                                }

                                if(isset($position[1]) && $position[1]!=''){
                                    $opt_position2=$arrPolitical[$position[1]];
                                    $chk2= true;
                                }
                            }
                            ?>
                            @endif

                            
                                <div class="row mb-4">
                                    <div class="col-md-6 view-detail">
                                        <label> 
                                        {{Form::checkbox('opt_positin1','1',$chk1,['class'=>'','disabled'])}}
                                        {{$opt_position1}}  &nbsp;&nbsp;&nbsp;  {{Form::checkbox('opt_positin2','1',$chk2,['class'=>'','disabled'])}}
                                        {{$opt_position2}}

                                       </label>
                                       {{Form::label('',trans('forms.personal_Profile.Label.political_position_dec'),['class'=>''])}}
                                        <p> {{($userPersonalData->political_position_dec!='' && $userPersonalData->political_position_dec!=null)?$userPersonalData->political_position_dec:''}}</p>
                                    </div>

                                </div>
                                


                            @endif

                            
                            <div class="row mb-12">
                                <div class="col-md-12 view-detail">
                                    <label>Are you related directly or indirectly to a person currently holding or who has previously held a Senior position in the public sector / Political position </label>
                                    <span><p>{{($userPersonalData->you_related_directly == '0')?'No':'Yes' }}</p></span>
                                </div>

                             </div>
                            



                            @if($userPersonalData->related_political_position!='' && $userPersonalData->related_political_position!=null)
                            <?php
                            $arrRelatedPolitical=['3'=>'Senior position in the public sector','4'=>'Political position'];
                            $relatedPosition   =   explode(',',$userPersonalData->related_political_position);

                            $opt_position3  =   '';
                            $opt_position4  =   '';
                            $chk3='';
                            $chk4='';

                            if(is_array($relatedPosition) && count($relatedPosition)){
                                if(isset($relatedPosition[0]) && $relatedPosition[0]!=''){
                                    $opt_position3=$arrRelatedPolitical[$relatedPosition[0]];
                                    $chk3=true;
                                }

                                if(isset($relatedPosition[1]) && $relatedPosition[1]!=''){
                                    $opt_position4=$arrRelatedPolitical[$relatedPosition[1]];
                                    $chk4=true;
                                }
                            }

                            ?>
                            <div class="row mb-4">
                                <div class="col-md-6 view-detail">
                                    <label> 
                                    {{Form::checkbox('opt_positin3','1',$chk3,['class'=>'','disabled'])}}
                                    {{$opt_position3}} &nbsp; &nbsp; &nbsp;

                                    {{Form::checkbox('opt_positin4','1',$chk4,['class'=>'','disabled'])}}
                                     {{$opt_position4}} </label>

                                     {{Form::label('',trans('forms.personal_Profile.Label.political_position_dec'),['class'=>''])}}
                                     
                                    <p> {{($userPersonalData->related_political_position_dec!='' && $userPersonalData->related_political_position_dec!=null)?$userPersonalData->related_political_position_dec:''}}</p>
                                </div>

                            </div>
                            @endif


                            @if($familyData && $familyData->count())
                            <div class="row mb-4">
                                <div class="heading col-md-12">
                                    <h4>Family Information</h4>
                                </div>

                            </div>
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label> Spouse first Name </label>
                                    <span>{{$familyData->spouse_f_name}} </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label> Spouse maiden name </label>
                                    <span> {{$familyData->spouse_m_name}}  </span>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label>Spouse professional Status </label>
                                    <span> {{($familyData->is_spouse_profession) ? $arrProfStatus[$familyData->is_spouse_profession] : ''}}  </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label> Spouse’s profession (if only) </label>
                                    <span> {{$familyData->spouse_profession}}  </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label> Spouse’s employer (if any) </label>
                                    <span> {{$familyData->spouse_employer}} </span>
                                </div>
                            </div>
                            @php
                            $childInfo  = $familyData->spouce_child_info;
                            $arrChildInfo=[];
                            @endphp
                            @if($childInfo!='' && $childInfo!=null)
                            @php
                                $arrChildInfo=  json_decode($childInfo);
                            @endphp
                                @if(count($arrChildInfo)>0)
                                <div class="row mb-4">
                                    <div class="heading col-md-12 sub-heading">
                                        <h4>Children Information</h4>
                                    </div>

                                </div>
                                @php $i=1;@endphp
                                    @foreach($arrChildInfo as $key=>$chinfo)
                                    <div class="row mb-4">
                                        <div class="col-md-3 view-detail">
                                            <label> Child {{$i}} </label>
                                            <span> {{$chinfo->child_name}} </span>
                                        </div>
                                        <div class="col-md-3 view-detail">
                                            <label> Date of Birth</label>
                                            <span>{{$chinfo->child_dob}} </span>
                                        </div>
                                    </div>
                                    @endforeach
                                @endif
                            @endif

                            @endif

                       
                            @if($residentialData && $residentialData->count())
                            

                            <div class="row mb-4">
                                <div class="heading col-md-12">
                                    <h4>Residential Information</h4>
                                </div>

                            </div>
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label> Country </label>
                                    <span> {{$arrCountry[$residentialData->country_id]}} </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label> City </label>
                                    <span> {{$residentialData->city_id}} </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label> Region </label>
                                    <span> <span> {{$residentialData->region}} </span> </span>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label> Building </label>
                                    <span> {{$residentialData->building_no}} </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label> Floor </label>
                                    <span> {{$residentialData->floor_no}} </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label> Street </label>
                                    <span> {{$residentialData->street_addr}} </span>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label> Postal Code </label>
                                    <span> {{$residentialData->postal_code}} </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>P.O Box </label>
                                    <span> {{$residentialData->post_box}} </span>
                                </div>

                            </div>
                            <div class="row mb-4">

                                <div class="col-md-3 view-detail">
                                    <label>Email </label>
                                    <span> {{$residentialData->addr_email}} </span>
                                </div>

                            </div>
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label> Telephone No. </label>
                                    <span> {{isset($residentialData->addr_phone_no) ? "(".$residentialData->tele_country_code.")". $residentialData->addr_phone_no: ''}} </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Mobile No. </label>
                                    <span> {{"(".$residentialData->addr_country_code.")".$residentialData->addr_mobile_no}} </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Fax No. </label>
                                    <span> {{"(".$residentialData->fax_country_code.")".$residentialData->addr_fax_no}}</span>
                                </div>

                            </div>
                         @endif

                        </div>
                        @else
                        <div class="tab-pane container" id="subtab01">

                        </div>

                        @endif


                        @if($professionalData && $professionalData->count())
                           @php
                            $profStatus=Helpers::getProfessionalStatus();

                            @endphp
                            <div class="tab-pane container" id="subtab02">
                            <div class="row mb-4">

                                <div class="col-md-3 view-detail">
                                    <label>Professional Status</label>
                                    <span>{{($professionalData->prof_status)? $profStatus[$professionalData->prof_status]:''}}</span>
                                </div>

                            </div>
                             @if($professionalData->prof_status=='8')
                               <div class="row mb-4">
                                   <div class="col-md-6 view-detail">
                                       <label>Other Profession Status</label>
                                       <span>{{($professionalData->other_prof_status!='' && $professionalData->other_prof_status!=null)?$professionalData->other_prof_status:''}}</span>
                                   </div>
                            </div>
                            @endif

                           
                            @if(in_array($professionalData->prof_status, ['1','3','4']))
                            <div class="row mb-4">
                                <div class="col-md-6 view-detail">
                                    <label>Profession Occupation in detail</label>
                                    <p>
                                {{isset($professionalData->profession_occu) ? $professionalData->profession_occu:''}}</p>
                                </div>
                                <div class="col-md-6 view-detail">
                                    <label>Position/Job title</label>
                                    <p>

                                        {{isset($professionalData->position_job_title) ? $professionalData->position_job_title:''}}
                                    </p>
                                </div>
                            </div>
                            

                            <?php
                            $date_employment=($professionalData->date_employment!='' && $professionalData->date_employment!=null) ? Helpers::getDateByFormat($professionalData->date_employment, 'Y-m-d', 'd/m/Y') :'';
                            setlocale(LC_MONETARY, 'en_US');

                            ?>
                            <div class="row mb-4">
                                <div class="col-md-6 view-detail">
                                    <label>Date of employment</label>
                                    <span>{{$date_employment}}</span>
                                </div>
                            </div>
                            @endif


                            @if(in_array($professionalData->prof_status, ['6']))
                            <div class="row mb-4">
                                <div class="col-md-6 view-detail">
                                    <label>Previous Profession/Occupation</label>
                                    <p>

                                {{isset($professionalData->prof_detail)? $professionalData->prof_detail:''}}
                                     </p>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-6 view-detail">
                                    <label>Last Position/Job title</label>
                                    <p>

                                        {{isset($professionalData->position_title) ? $professionalData->position_title:''}}
                                    </p>
                                </div>
                            </div>

                          @php
                           $date_retirement=($professionalData->date_retirement!='' && $professionalData->date_retirement!=null) ? Helpers::getDateByFormat($professionalData->date_retirement, 'Y-m-d', 'd/m/Y') :'';
                           setlocale(LC_MONETARY, 'en_US');
                           @endphp
                            <div class="row mb-4">
                                <div class="col-md-6 view-detail">
                                    <label>Date of retirement</label>
                                    <span>{{$date_retirement}}</span>
                                </div>
                                <div class="col-md-6 view-detail">
                                   <label>Last monthly salary if retired</label>
                                   <span>{{isset($professionalData->last_monthly_salary) ? money_format('%i',$professionalData->last_monthly_salary) : ''}}</span>
                                </div>
                            </div>
                        @endif

                        
                            @if(@in_array($professionalData->prof_status, ['3', '4']) && isset($commercialData->user_kyc_id))

                            <div class="row mb-4">
                                <div class="heading col-md-12">
                                    <h4>For Sole Proprietorship/Self Employed, Please Specify</h4>
                                </div>

                            </div>
                            

                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label>Commercial name</label>
                                    <span>{{@$commercialData->comm_name}}</span>
                                </div>

                            </div>
                       
                            <?php
                            $date_of_establish  =   ($commercialData->date_of_establish!='' && $commercialData->date_of_establish!=null) ? Helpers::getDateByFormat($commercialData->date_of_establish, 'Y-m-d', 'd/m/Y') :'';
                            ?>
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label>Date of establishment</label>
                                    <span>{{$date_of_establish}}</span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Country of establishment</label>
                                    <span>{{($commercialData->country_establish_id>0)?$arrCountry[$commercialData->country_establish_id]:''}}</span>
                                </div>

                            </div>
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label>Commercial Register No.</label>
                                    <span>{{$commercialData->comm_reg_no}}</span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Place</label>
                                    <span>{{$commercialData->comm_reg_place}}</span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Country</label>
                                    <span>{{($commercialData->comm_country_id>0)?$arrCountry[$commercialData->comm_country_id]:''}}</span>
                                </div>

                            </div>

                             @php
                              $arrarVal=[];
                              $countryVal=Helpers::getCountryDropDown();

                              if(isset($commercialData['country_activity'])) {
                              $arrarVal = explode(',',$commercialData['country_activity']);
                              }
                              else {
                              $arrarVal=[];

                              }
                              @endphp


                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label>Country(ies) of Activity</label>
                                    
                                    <?php
                                        $arrSel=[];
                                        $i=0;
                                        ?>
                                        @foreach($countryVal as $Dval)
                                            @if(@in_array($Dval, $arrarVal))
                                            @php
                                             $arrSel[$i]=$Dval;
                                             $i++;
                                            @endphp
                                            @endif     
                                        @endforeach 
                                        @php $sources=implode(',  ',$arrSel); @endphp
                                        <span> {{$sources}}</span>
                                </div>

                            </div>
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label>Syndicate No.</label>
                                    <span>{{$commercialData->syndicate_no}}</span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Taxation ID No.</label>
                                    <span>{{$commercialData->taxation_no}}</span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Taxation ID</label>
                                    <span>{{$commercialData->taxation_id}}</span>
                                </div>

                            </div>
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label>Annual Business Turnover (in $)</label>
                                    <span>$ {{$commercialData->annual_turnover}}</span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Main Suppliers</label>
                                    <span>{{$commercialData->main_suppliers}}</span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Main Clients</label>
                                    <span>{{$commercialData->main_clients}}</span>
                                </div>

                            </div>
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label>Name of authorized signatory</label>
                                    <span>{{$commercialData->authorized_signatory}}</span>
                                </div>

                            </div>
                              
                            @if($bussAddrData && $bussAddrData->count())

                            
                            <div class="row mb-4">
                                <div class="heading col-md-12">
                                    <h4>Business Address</h4>
                                </div>

                            </div>

                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label> Country </label>
                                    <span> {{($bussAddrData->buss_country_id>0)?$arrCountry[$bussAddrData->buss_country_id]:''}} </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label> City </label>
                                    <span> {{$bussAddrData->buss_city_id}} </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label> Region </label>
                                    <span> {{$bussAddrData->buss_region}}  </span>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label> Building </label>
                                    <span> {{$bussAddrData->buss_building}} </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label> Floor </label>
                                    <span> {{$bussAddrData->buss_floor}} </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label> Street </label>
                                    <span> {{$bussAddrData->buss_street}} </span>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label> Postal Code </label>
                                    <span> {{$bussAddrData->buss_postal_code}} </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>P.O Box </label>
                                    <span> {{$bussAddrData->buss_po_box_no}} </span>
                                </div>

                            </div>

                            <div class="row mb-4">

                                <div class="col-md-3 view-detail">
                                    <label>Email </label>
                                    <span> {{$bussAddrData->buss_email}} </span>
                                </div>

                            </div>
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label> Telephone No. </label>
                                    <span> {{isset($residentialData->addr_phone_no) ? "(".$residentialData->addr_country_code.")". $residentialData->addr_phone_no: ''}}  </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Mobile No. </label>
                                    <span> {{$bussAddrData->buss_mobile_no}} </span>
                                </div>
                                <div class="col-md-3 view-detail">
                                    <label>Fax No. </label>
                                    <span> {{$bussAddrData->buss_fax_no}}</span>
                                </div>

                            </div>
                            @endif




                            <div class="row mb-4">
                                <div class="heading col-md-12">
                                    <h4>Mailing Address</h4>
                                </div>

                            </div>
                            @php
                            $isHold =   ['1'=>'Yes','0'=>'No'];
                            @endphp
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label> Hold Mail </label>
                                    <span>{{($commercialData->is_hold_mail=='1')?$isHold[$commercialData->is_hold_mail]:'No'}} </span>
                                </div>

                            </div>
                            @php
                            $mailAddr=['1'=>'Residential Address','2'=>'Secondary Address','3'=>'Business Address'];
                            @endphp
                            <div class="row mb-4">
                                <div class="col-md-6 view-detail">
                                    <label> In case of sending documents through mail, please specify mailing address </label>
                                    <span> {{($commercialData->mailing_address>0)?$mailAddr[$commercialData->mailing_address]:''}} </span>
                                </div>

                            </div>
                            <div class="row mb-4">
                                <div class="col-md-6 view-detail">
                                    <label> Relation with Exchange Company/ Establishment </label>
                                    <p> Are you or your spouse or any of your dependents (ascendants and descendants) the owner or shareholder or partner or director or signatory of an exchange establishment/ company? If yes please disclose the full names of the concerned parties and the full name and details of the establishment / company </p>
                                </div>

                            </div>
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label></label>
                                    <span> {{($commercialData->relation_exchange_company=='1')?'Yes':'No'}}</span>
                                </div>

                            </div>
                            <div class="row mb-4">
                                <div class="col-md-3 view-detail">
                                    <label>Name of Concerned Party</label>
                                    <span> {{$commercialData->concerned_party}}</span>
                                </div>

                            </div>
                            <div class="row mb-4">
                                <div class="col-md-6 view-detail">
                                    <label>Name/Details of Establishment/Company</label>
                                    <span> {{$commercialData->details_of_company}}</span>
                                </div>

                            </div>
                            @else
                            
                            @endif


                        </div>
                        @else
                        <div class="tab-pane container" id="subtab02">

                        </div>
                        @endif

                       
                        
                        @if(isset($financialData->user_kyc_id))

                            @php
                                $arrarVal=[];
                                $sourceDropVal=Helpers::getFundSourceDropDown();

                                if(isset($financialData->source_funds)) {
                                $arrarVal = explode(',',$financialData->source_funds);
                                }
                                else {
                                $arrarVal=[];

                                }
                             @endphp
                           
                         <div class="tab-pane container" id="subtab03">
                            <div class="row mb-4">
                                <div class="col-md-6 view-detail">
                                    <label>Source of funds</label>
                                        <?php
                                        $arrSel=[];
                                        $i=0;
                                        ?>
                                        @foreach($sourceDropVal as $Dval)
                                            @if(@in_array($Dval, $arrarVal))
                                            @php
                                             $arrSel[$i]=$Dval;
                                             $i++;
                                            @endphp
                                            @endif     
                                        @endforeach 
                                        @php $sources=implode(',  ',$arrSel); @endphp
                                        <span> {{$sources}}</span>
                                </div>
                               
                                <div class="col-md-6 view-detail">
                                    <label>Jurisdiction of Funds</label>

                                    <span> {{($financialData->jurisdiction_funds>0)? @$arrCountry[$financialData->jurisdiction_funds]:''}}</span>
                                </div>

                            </div>


                            <div class="row mb-4">
                                <div class="col-md-6 view-detail">
                                    <label>Annual Income (in USD)</label>
                                    <span>{{($financialData->annual_income>0)?$annual[$financialData->annual_income]:''}}</span>
                                </div>
                                <div class="col-md-6 view-detail">
                                    <label>Estimated Wealth (in USD)</label>
                                    <span>{{($financialData->estimated_wealth>0)?$estimated[$financialData->estimated_wealth]:''}}</span>
                                </div>

                            </div>
                             
                            @php
                            $wealthSource=Helpers::getWealthSourceDropDown();

                            if(isset($financialData->wealth_source)) {
                            $selectedVal=explode(',',$financialData->wealth_source);
                            } else {
                            $selectedVal=[];
                            }

                            @endphp
                            
                            <div class="row mb-4">
                                <div class="col-md-6 view-detail">
                                    <label>Kindly provide details on the source(s) of your wealth</label>
                                    <span> <?php
                                        $arrSel=[];
                                        $i=0;
                                        ?>
                                        @foreach($wealthSource as $wval)

                                            @if(in_array($wval, $selectedVal))
                                            @php
                                             $arrSel[$i]=$wval;
                                             $i++;
                                            @endphp
                                            @endif     
                                        @endforeach 
                                        @php $welthsources=implode(',  ',$arrSel); @endphp
                                        <span> {{$welthsources}}</span></span>
                                </div>

                            </div>
                            @if(isset($wealthSource[$financialData->wealth_source]) && $wealthSource[$financialData->wealth_source]=='Other')
                            <div class="row mb-4">
                                <div class="col-md-6 view-detail">
                                    <label>Other source(s) of your wealth</label>
                                    <span>{{($financialData->other_wealth_source!='' && $financialData->other_wealth_source!=null)?$financialData->other_wealth_source:''}}</span>
                                </div>

                            </div>
                            @endif
                            
                            <hr>
                            <div class="row mb-4">
                                <div class="col-md-6 view-detail">
                                    <label>US TIN Code</label>
                                    <span>{{$financialData->tin_code}}</span>
                                </div>
                                <div class="col-md-6 view-detail">
                                    <label>Was US citizenship abandoned after June 2014?</label>
                                    <span>{{($financialData->is_abandoned=='1')?'Yes':'No'}}</span>
                                </div>

                            </div>
                            
                             <?php
                            $date_of_abandonment=($financialData->date_of_abandonment!='' && $financialData->date_of_abandonment!=null) ? Helpers::getDateByFormat($financialData->date_of_abandonment, 'Y-m-d', 'd/m/Y') :'';
                            ?>
                            <div class="row mb-4">
                                <div class="col-md-6 view-detail">
                                    <label>Please specify date of abandonment</label>
                                    <span>{{$date_of_abandonment}}</span>
                                </div>

                            </div>
                            <div class="row mb-4">
                                <div class="col-md-6 view-detail">
                                    <label>Reason</label>
                                    <p>{{$financialData->abandonment_reason}}</p>
                                </div>

                            </div>
                            <!--<div class="row mb-4">
                                <div class="col-md-6 view-detail">
                                    <label>Justification (If reason B is selected)</label>
                                    <p>{{$financialData->justification}}</p>
                                </div>


                            </div>-->
                            <div class="row mb-4">

                                <div class="col-md-6 view-detail">
                                    <label>TIN Country</label>
                                    <span>{{($financialData->tin_country_name>0)? @$arrCountry[$financialData->tin_country_name]:''}}</span>
                                </div>
                                <div class="col-md-6 view-detail">
                                    <label>TIN (Taxpayer Identification Number) or functional equivalent of the TIN</label>
                                    <span>{{$financialData->tin_number}}</span>
                                </div>

                            </div>
                        </div>
                        @else
                        <div class="tab-pane container" id="subtab03"></div>
                        @endif

                    </div>
                </div>

                <?php } ?>


                <div class="tab-pane container p-0 mt-3" id="tab03">

                    <!--Similar records  Start-->
                @if($apiFullName!='' && $date_of_birthAPI!='')
                <?php

                if(!isset($kycDetail)) {
                ?>
                    <div class="table-responsive">

                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th width="10%">ReferenceId</th>
                                    <th width="15%">Name</th>
                                    <th width="15%">Category</th>
                                    <th width="15%">Provider Types</th>
                                    <th width="10%">Gender</th>
                                    <th width="10%">DOB</th>
                                    <th width="15%">Country</th>

                                    <th class="text-right" width="10%">Actions</th>

                                </tr>
                            </thead>
                            <tbody id="similarRecords">

                            </tbody>
                        </table>

                    </div>
                <?php } else {
                       $primaryId = "";
                       $response    = $kycDetail['api_responce']; //exit;
                       $primaryId  = $kycDetail['wcapi_req_res_id'];
                       //echo "==>".$primarryId;
                       $usersKycId  = isset($kycDetail) ? $kycDetail['user_kyc_id'] : '';
                       //$response = (string)$response;

                      // $response= iconv('windows-1256', 'utf-8', ($response));
                       //$dataArray = json_decode(strip_tags($response),JSON_UNESCAPED_UNICODE);
                       $dataArray = json_decode($response, JSON_UNESCAPED_UNICODE);
                       //echo "<pre>";
                       //print_r($dataArray); exit;
                       $i = 0;
                    ?>

                    <div class="table-responsive">

                        <table class="table table-striped table-sm font-size-13">
                            <thead>
                                <tr>
                                    <th width="10%">WC UID</th>
                                    <th width="8%">Matching Entity</th>
                                    <th width="7%">Type</th>
                                    <th width="15%">Category</th>
                                    <th width="15%">Provider Types</th>
                                    <th width="10%">Gender</th>
                                    <th width="6%">DOB</th>
                                    <th width="7%">Nationality</th>
                                    <th width="7%">Location</th>
                                    <th width="7%">Resolution Reason</th>
                                    <th class="text-right" width="7%">Actions</th>

                                </tr>
                            </thead>

                            <tbody id="similarRecords">
                                <?php

                                    if(isset($dataArray['results']) && $dataArray!='') {

                                foreach($dataArray['results'] as $resultArray) {
                                    $matchStrength = $resultArray['matchStrength'];

                                       $primaryName = $resultArray['primaryName'];
                                       $category    = $resultArray['category'];
                                       $gender      = $resultArray['gender'];
                                       $referenceId = $resultArray['referenceId'];

                                       $wcUID = str_replace('e_tr_wci_','',$referenceId);
                                       $providerTypes = $resultArray['providerType'];
                                       ////Events DOB
                                       $eventsDataDOB = "";
                                       $countryLocation = "";
                                       $identityDocumentsNumber = "";
                                       $identityDocumentsType = "";

                                       $eventsArray = $resultArray['events'];
                                       if(count($eventsArray) > 0) {
                                           foreach($eventsArray as $eventsData)
                                              {
                                               if($eventsData['type']=="BIRTH") {
                                                  $eventsDataDOB = $eventsData['fullDate'];
                                               }
                                              }

                                       }

                                       // PEP Type
                                       $pepTypeArray = [];
                                       $type = "";
                                       $pepcategoryName = "";
                                       $pepTypeArray = $resultArray['categories'];

                                       foreach($pepTypeArray as $pepTypeData) {
                                           $allType= $pepTypeData;
                                        }

                                       //$allType = "";
                                       $allType = str_replace('Law Enforcement', 'LE', $allType);
                                       $allType = str_replace('Regulatory Enforcement', 'RE', $allType);
                                       $allType = str_replace('Other Bodies', 'OB', $allType);
                                       $type    = $allType;


                                       ////Country Events
                                       $countryLinksArray = $resultArray['countryLinks'];
                                       if(count($countryLinksArray) > 0) {
                                           foreach($countryLinksArray as $countryLinksData)
                                              {
                                               if($countryLinksData['type']=="NATIONALITY") {
                                                  $nationality = $countryLinksData['country']['name'];
                                               }
                                               if($countryLinksData['type']=="LOCATION") {
                                                  $countryLocation = $countryLinksData['country']['name'];
                                               }


                                              }
                                       }
                                       ///Identity
                                       $identityDocumentsArray = $resultArray['identityDocuments'];
                                       $passportNumber = "";
                                       if(count($identityDocumentsArray) > 0) {

                                           foreach($identityDocumentsArray as $identityDocumentsData)
                                              {
                                               $identityDocumentsType = $identityDocumentsData['type'];
                                               $identityDocumentsNumber = $identityDocumentsData['number'];

                                               if($identityDocumentsType == "Passport") {
                                                    $passportNumber = $identityDocumentsNumber;
                                                }
                                              }
                                       }

                                       $BindData = '';
                                       //$BindData = $referenceId."#".$primaryName."#".$category."#".$providerTypes."#".$gender."#".$eventsDataDOB."#".$countryLinksName."#".$identityDocumentsType."#".$identityDocumentsNumber;
                                       $BindData = $referenceId."#".$primaryName."#".$matchStrength."#".$type."#".$passportNumber;

                                       ?>
                                        <tr>
                                             <td width="10%"><?php echo $wcUID;?></td>
                                             <td width="8%"><?php echo $primaryName;?></td>
                                             <td width="7%"><?php echo $type;?></td>
                                             <td width="15%"><?php echo $category;?></td>
                                             <td width="15%"><?php echo $providerTypes;?></td>
                                             <td width="10%"><?php echo $gender;?></td>
                                             <td width="6%"><?php echo $eventsDataDOB;?></td>
                                             <td width="8%"><?php echo $countryLocation;?></td>
                                             <td width="7%"><?php echo $nationality;?></td>
                                             <td width="7%"><?php echo $matchStrength;?></td>

                                             <td class="text-right" width="7%">
                                                 <div class="d-flex align-items-center">
                                                 <input type="radio" name="kycdetailID" id="kycdetailID" value="<?php echo $BindData;?>">
                                                 <input type="hidden" name="hiddenval" value="<?php echo $BindData;?>">

                                                 <input type="button" value="Generate Report" id="getfullDetail_<?php echo $i;?>" name="getfullDetail" class="getfullDetail btn btn-save btn-sm">
                                                 </div>
                                              </td>
                                        </tr>



                                            <?php
                                           // echo "===>".$primaryId;

                                            $profiledata = Helpers::getKycprofileData($userKycId, $referenceId, 'profile');
                                           // $profiledata = Helpers::getAllKycprofileDatagraterPID($userKycId, 'profile',$primaryId);

                                          //  echo "<pre>";
                                          //  print_r($profiledata); exit;

                                            if($profiledata) {
                                            $resultArray1 = json_decode($profiledata);
                                            $resultArray = json_decode($resultArray1->api_responce);
                                            $category    = $resultArray->category;
                                            $gender      = $resultArray->gender;

                                             //$providerTypes = $resultArray->providerType;

                                             $fullName            = "";
                                             $roleTitle           = "";
                                             $roleTitle           = "";
                                             $eventsDataDOB       = "";
                                             $countryLinksName    = "";
                                             $sourcesName         = "";
                                             $sourcesDescription  = "";
                                             $webLinks            = "";
                                             $identityDocumentsNumber     = "";
                                             $identityDocumentsType       = "";
                                             $identityDocumentsissueDate  = "";
                                             $identityDocumentsexpiryDate = "";

                                         ///GET NAMES


                                         $nameArray = $resultArray->names;
                                          if(count($nameArray) > 0) {
                                             foreach($nameArray as $nameData)
                                                {
                                                 if($nameData->type=="PRIMARY") {
                                                    $fullName = $nameData->fullName;
                                                 }
                                                }

                                         }

                                             ////Events DOB
                                             $eventsArray = $resultArray->events;
                                             if(count($eventsArray) > 0) {
                                                 foreach($eventsArray as $eventsData)
                                                    {
                                                     if($eventsData->type=="BIRTH") {
                                                        $eventsDataDOB = $eventsData->fullDate;
                                                     }
                                                    }
                                             }


                                             ////Role Title
                                             $rolesArray = $resultArray->roles;
                                             if(count($rolesArray) > 0) {
                                                 foreach($rolesArray as $roleData)
                                                    {
                                                        $roleTitle.= $roleData->title.",";
                                                    }

                                             }


                                             ////weblinks
                                             $weblinksArray = $resultArray->weblinks;
                                             if(count($weblinksArray) > 0) {

                                                 foreach($weblinksArray as $weblinksData)
                                                    {
                                                     if($weblinksData->uri!="") {
                                                        $webLinks.= $weblinksData->uri."<br>";
                                                      }
                                                    }

                                             }

                                             /// Source Links
                                             $sourcesArray = $resultArray->sources;
                                             if(count($sourcesArray) > 0) {

                                                 foreach($sourcesArray as $sourcesData)
                                                    {
                                                     if($sourcesData->name!="") {
                                                        $sourcesName = $sourcesData->name;
                                                        $sourcesDescription = (string) $sourcesData->type->category->description;
                                                      }
                                                    }

                                             }

                                             ////Country Events
                                             $countryLinksArray = $resultArray->countryLinks;
                                             if(count($countryLinksArray) > 0) {
                                                 foreach($countryLinksArray as $countryLinksData)
                                                    {
                                                     if($countryLinksData->type=="NATIONALITY") {
                                                        $countryLinksName = $countryLinksData->country->name;
                                                     }
                                                    }
                                             }
                                             ///Identity
                                             $identityDocumentsArray = $resultArray->identityDocuments;
                                             $stringidentityDocuments = "";
                                             if(count($identityDocumentsArray) > 0) {

                                                 foreach($identityDocumentsArray as $identityDocumentsData)
                                                    {
                                                    $identityDocumentsType       = "";
                                                    $identityDocumentsNumber     = "";
                                                    $identityDocumentsissueDate  = "";
                                                    $identityDocumentsexpiryDate = "";
                                                    $identityDocumentsType = $identityDocumentsData->type;
                                                    $identityDocumentsNumber = $identityDocumentsData->number;
                                                    $identityDocumentsissueDate = $identityDocumentsData->issueDate;
                                                    $identityDocumentsexpiryDate = $identityDocumentsData->expiryDate;
                                                    $stringidentityDocuments.= "Type: ".$identityDocumentsType."<br>Number:".$identityDocumentsNumber."<br>Issue Date:".$identityDocumentsissueDate."<br>Expiry Date:".$identityDocumentsexpiryDate;
                                                    }
                                             }


                                             $detail = $fullName."<br>".$gender."<br>".$eventsDataDOB;


                                        ?>

                                            <tr>
                                                <td colspan="11" id="profileDetail_<?php echo $i;?>">
                                                <table class="table table-striped table-sm">
                                                     <thead>
                                                         <tr>
                                                             <th width="10%">ReferenceId</th>
                                                             <th width="15%">Detail</th>
                                                             <th width="15%">Role Title</th>
                                                             <th width="10%">Identity Documents</th>
                                                             <th width="15%">Country</th>
                                                             <th width="35%">Action</th>


                                                         </tr>
                                                     </thead>
                                                     <tbody id="similarRecords">
                                                         <tr>
                                                             <td width="10%" valign="top"><?php echo $referenceId?></td>
                                                             <td width="15%" valign="top"><?php echo $detail?></td>
                                                             <td width="15%" valign="top"><?php echo trim($roleTitle,",")?></td>
                                                             <td width="10%" valign="top"><?php echo $stringidentityDocuments?></th>
                                                             <td width="15%" valign="top"><?php echo $countryLinksName?></th>
                                                             <td width="35%" valign="top">
                                                                <a href="{{route('report_download',['userKycId' => $userKycId,'referenceId'=>$referenceId,])}}"><button type="button" class="btn btn-default btn-sm btn-approved" data-toggle="modal" data-target="#Approved_Action" >Download</button></a>|
                                                                <a type="button" class="btn btn-default btn-sm btn-approved" data-toggle="modal" data-target="#btn-approved-iframe"  data-url="{{route('individual_api_approve',['id'=>$userKycId])}}" data-height="200px" data-width="100%" data-placement="top">Approved</a> |
                                                                <a type="button" class="btn btn-default btn-sm btn-disapproved" data-toggle="modal" data-target="#btn-disapproved-iframe"  data-url="{{route('individual_api_disapprove',['id'=>$userKycId])}}" data-height="200px" data-width="100%" data-placement="top">Decline</a>

                                                                </td>
                                                         </tr>
                                                     </tbody>
                                                 </table>
                                                </td>
                                            </tr>
                                             <?php
                                                 } else { ?>
                                               <tr>
                                                <td colspan="11" id="profileDetail_<?php echo $i;?>">
                                                </td>
                                            </tr>
                                              <?php   }
                                             $i++;
                                             }
                                        }
                                     ?>
                            </tbody>
                        </table>

                    </div>


                    <?php } ?>
                @endif

                    <!--Similar records End-->

                </div>


<!-- World Check API Start-->


                  <div class="tab-pane container p-0 mt-3" id="tab06">

                    <div class="col-md-12 text-right">
                        <a class="btn btn-save btn-sm " data-toggle="modal" data-target="#searchCases" data-url="{{route('individual_searchcase',['id' => $userKycId,'searchfor' => 'individual'])}}" data-height="180px" data-width="100%" data-placement="top">Search Cases</a>
                        <a class="btn btn-save btn-sm " data-toggle="modal" data-target="#personalwcapi" data-url="{{route('individual_api_popup',['id' => $userKycId,'passportNumber' => $userPassportNumber,'userPassportExp' => $userPassportExp])}}" data-height="530px" data-width="100%" data-placement="top">Get Similar</a>
                    </div>

                    <div class="table-responsive mb-4">
                        <table class="table table-striped table-sm">
                            <thead>

                                @if($isKycComplete == 1)
                                <tr>
                                    <th width="30%">Name</th>
                                    <th width="30%">Gender</th>
                                    <th width="20%">DOB</th>
                                    <th width="20%" class="text-center">Country</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td width="30%">{{ ($apiFullName!='' && $apiFullName!=null ) ? $apiFullName : '' }}</td>
                                    <td width="30%">{{isset($userPersonalData->gender) ? $genger[$userPersonalData->gender]: ''}}</td>

                                    <td width="20%">{{Date('d/m/Y',strtotime($date_of_birthAPI))}}</td>
                                    <td width="20%" align="center">{{ isset($userPersonalData->birth_country_id) ? $arrCountry[$userPersonalData->birth_country_id] : ''}}</td>
                                </tr>
                               @else
                               <tr>
                                    <td colspan="4">Kyc Not completed Yet</td>
                                </tr>
                                @endif;

                            </tbody>

                           </table>
                         </div>

<!--if data exits -->

<!-- End Data Exits-->
<!--Seperator Start-->
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

                             $profiledataArray = Helpers::getAllKycprofileDataHistory($userKycId, 'screeningRequest');
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

                             $profiledataArray = Helpers::getAllKycprofileDataHistory($userKycId, 'profile');
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

<!-- Seperator Ends -->

            </div>

<!-- World Check API END -->


                <div class="tab-pane container  p-0 mt-4" id="tab04">

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
                                $docListExp=Helpers::getDocumentListExpired($userKycId, $document['user_req_doc_id'], 3);
                                @endphp
                                
                                @if(count($docList) > 0 )
                                    <table class="data-download full-width">
                                    @foreach($docList as $val)
                                        @php
                                            $docName ='';
                                            $docName = $val->doc_name.".".$val->doc_ext;
                                            $uploaded_on=date('d/m/Y',strtotime($val->created_at));
                                        @endphp

                                       
                                        @if($document['doc_no'] == 2 && $val->doc_id == 3)
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
                                                             <td width="33%" align="right"><a href="{{route('download_document',['enc_id' => $valexp->enc_id,'user_type'=>'1'])}}"><button type="button" class="btn btn-save btn-sm" data-toggle="modal" data-target="#Approved_Action">Download</button></a></td>
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
                                        <td width="33%" align="right"><a href="{{route('download_document',['enc_id' => $val->enc_id,'user_type'=>'1'])}}"><button type="button" class="btn btn-save btn-sm" data-toggle="modal" data-target="#Approved_Action">Download</button></a></td>
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
                                'url' => route('send_other_docreq',$benifinary),
                                'autocomplete' => 'off','class'=>'loginForm form form-cls'
                                ))
                            !!}
                            <div class="row">
                            <div class="col-md-8">
                            <div class="form-group">
                                <div class="form-group form-custom-cls">
                                    @php
                                    $otherDocs=Helpers::otherDocsDropDown('1');
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

                            {{ Form::submit('Send Request',['class'=>'btn btn-save','name'=>'save_next']) }}

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
                                        <td width="33%" align="right">
                                        Pending</td>

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
                            

                                $count = 1;
                             if(count($assesmentData) > 0) {
                                foreach($assesmentData as $assesmentDataRow) {
                                    $score      = $assesmentDataRow->avg_rank;
                                    $org_score      = $assesmentDataRow->org_avg_rank;
                                    $createdBy   = Helpers::getUserName($assesmentDataRow->updated_by);
                                    //$CreatedDate = date('d/m/Y D H:i:s',strtotime($assesmentscoreOldData->updated_at));
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
                                    //$CreatedDate = date('d/m/Y D H:i:s',strtotime($assesmentscoreOldData->updated_at));
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
                             }


                             if(count($assesmentData) <= 0 && count($assesmentOldData) <= 0) { ?>

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
                    <?php if($isKycApprove == 1 || $isKycApprove == 0 || $isKycApprove == 2) {
                        ?>
                    {!!
                    Form::open(
                    array(
                    'name' => 'assesmentForm',
                    'id' => 'assesmentForm',
                    'url' => route('save_assesment_rank',['user_id'=>$userByComapnyId,'user_type'=>$userType,'user_kyc_id'=>$userKycId]),
                    'autocomplete' => 'off','class'=>'loginForm form form-cls'
                    ))
                    !!}
                    
                    <!--Report Sectio -->
            <div class="row">
                <div class="col-md-9">
                        <table class="table table-striped table-sm table-error">
                            <tbody>
                                <tr>
                                    <td width="33.3%"><b>1. National Identity</b> </td>
                                    <td width="33.3%">{{isset($userNationality) ? "(".$userNationality.")" : ''}}</td>
                                    <td width="33.3%">
                                        <?php
                                        //echo "<pre>";
                                        // print_r($assesmentDetail); exit;

                                            $countryValue     = "";
                                            $machesWorldcheck = "";
                                            $passport         = "";
                                            $pep              = "";
                                            $socialMedia      = "";

                                        if (isset($assesmentDetail) && count($assesmentDetail)
                                            > 0) {
                                            $countryValue     = "";
                                            $machesWorldcheck = "";
                                            $passport         = "";
                                            $pep              = "";
                                            $socialMedia      = "";
                                          //  dd($assesmentDetail);

                                            if (isset($assesmentDetail[0]->assesment_id) == 1) {
                                                $countryValue = $assesmentDetail[0]->rank;
                                            }

                                            if (isset($assesmentDetail[1]->assesment_id) == 2) {
                                                $machesWorldcheck = $assesmentDetail[1]->rank;
                                            }

                                            if (isset($assesmentDetail[2]->assesment_id) == 3) {
                                                $passport = $assesmentDetail[2]->rank;
                                            }

                                            if (isset($assesmentDetail[3]->assesment_id) == 4) {
                                                $pep = $assesmentDetail[3]->rank;
                                            }

                                            if (isset($assesmentDetail[4]->assesment_id) == 5) {
                                                $socialMedia = $assesmentDetail[4]->rank;
                                            }
                                        } else {
                                            $countryValue = '';
                                            if(isset($userPersonalData->f_nationality_id)) {

                                                 $userCountry = $arrCountry[$userPersonalData->f_nationality_id];

                                            } else
                                            {
                                                $userCountry = isset($userData->country_id) ? $arrCountry[$userData->country_id] : '';
                                            }

                                            if ($userCountry == "United States") {
                                                $userCountry = "United States of America (USA)";
                                            }
                                            if($userCountry!='') {
                                                $userTypeCountryArray = Helpers::getCountryTypeByName($userCountry);
                                                if($userTypeCountryArray){
                                                    $countryValue = $userTypeCountryArray->country_score;
                                                }else{
                                                    $countryValue='';
                                                }
                                            } else {
                                               $countryValue='';
                                            }
                                        }

                                        // echo "==>".$countryValue; exit;
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
                                    <td><b>2. World-check Matches </b></td>
                                    <td>
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
                                        
                                        <div id="worldcheckStrength">{{(isset($machesStrngth))? "(".$machesStrngth.")" : (($kycStatus->is_finalapprove == '3') ? 'No Match' : '' )}}</div></td>
                                    <td>
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
                                    <td><b>3. Passport Verification</b></td>
                                    <td>
                                    <?php
                                        $passportNo = '';
                                       $k=1;
                                        foreach($documentsData as $passNo) {
                                            if($k==2) {
                                             $passportNo = $passNo->document_number;
                                              }
                                            $k++;
                                         }
                                        
                                   if(@$kycpersonalDetail->passport) { $Matchesval = @$kycpersonalDetail->passport;} else { $Matchesval = "No Match"; }
                                        echo "<b>Compliance Platform - </b>"."(".$passportNo.")<br>";
                                        echo "<b>World-check - </b>"."(".$Matchesval.")";
                                       // if($kycStatus->is_finalapprove == '3') { echo 'No Match'; };
                                        ?>
                                       </td>
                                    <td>
                                        <select class="form-control" name="passport_verification" id="passport_verification">
                                            <option value="">Please Select</option>
                                            <option value="1" {{isset($passport) ? (($passport == '1') ? 'selected':'') : ''}}>1</option>
                                            <option value="2" {{isset($passport) ? (($passport == '2') ? 'selected':'') : ''}}>2</option>
                                            <option value="3" {{isset($passport) ? (($passport == '3') ? 'selected':'') : ''}}>3</option>
                                            <option value="4" {{isset($passport) ? (($passport == '4') ? 'selected':'') : ''}}>4</option>
                                            <option value="5" {{isset($passport) ? (($passport == '5') ? 'selected':'') : ''}}>5</option>
                                        </select>
                                    </td>
                                </tr>

                                <tr>
                                    <td><b>4.World-check Exposure </b></td>

                                    <td><?php
                                    $pepstatus = '';
                                    if($machesWorldcheck == 1 || $machesWorldcheck == 2 || $machesWorldcheck == 3 || $machesWorldcheck == 4) {
                                                $pepstatus = "( Not Exposed )";
                                            } else {

                                            if(isset($kycpersonalDetail->pep) && $kycpersonalDetail->pep!='')  { $pepstatus = "(".$kycpersonalDetail->pep.")"; } else  { $pepstatus = 'No Match'; }

                                    }

                                    echo $pepstatus;
                                    ?>

                                    </td>
                                    <td>
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
                                    <td width="33.3%"><b>5. General Verification On Social Media</b></td>
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
                            <?php
                            if($benifinary['is_by_company'] == 1) {
                                ?>
                            <input type="hidden" name="corp_user_id" value="{{$benifinary['corp_user_id']}}">
                            <input type="hidden" name="corp_user_kyc_id" value="{{$benifinary['corp_user_kyc_id']}}">
                            <input type="hidden" name="is_by_company" value="{{$benifinary['is_by_company']}}">
                            <?php }
                           // echo "==>".$kycCompleteArray->is_finalapprove;
                            if($kycCompleteArray->is_finalapprove == 0 || $kycCompleteArray->is_finalapprove == 3) { ?>
                                 {{ Form::submit('Generate Score',['class'=>'btn btn-save','name'=>'save']) }}
                             <?php } ?>
                            
                        </div>
                    </div>
                    {{ Form::close() }}
                <?php } ?>
                </div>
            </div>
            </div>



            <div class="tab-pane container  p-0 mt-4" id="tab08">
                <?php if($userPersonalData && $userPersonalData->count()) {?>
                <div class="col-md-12 dashbord-white">
                    <div class="table-responsive">
                        <!-- Table start Here-->
                    {!!
                    Form::open(
                    array(
                    'name' => 'finalForm',
                    'id' => 'finalForm',
                    'url' => route('save_final_report',['user_kyc_id'=>$userKycId, 'user_type' =>'1','user_id' => $kycCompleteArray->user_id]),
                    'autocomplete' => 'off','class'=>'loginForm form form-cls'
                    ))
                    !!}

                    <table class="table table-striped table-sm brown-th">
                        <tbody>
                <!-- <tr><th align="left" style="padding-bottom: 0;"><h2 class="customer-head">1. Know Your Customer Details  
                    <a href="{{route('report_indicomplete_download',['userKycId'=>$userKycId,'user_id'=>$kycCompleteArray->user_id])}}">
             <button type="button" class="btn btn-save hover-green float-right" data-toggle="modal" data-target="#Approved_Action">Download</button></h2></a></th>
             </tr> -->

                           
             <tr>
                 <th align="left" style="padding-bottom: 0;"><h2 class="customer-head">1. Know Your Customer Details
                   <?php if(isset($complianceReport) && (@$complianceReport->compliance_report_id!='') ) { ?>
                         <button type="button" class="btn btn-save hover-green float-right" data-toggle="modal" data-target="#reportblockchain" data-height="250px" data-width="100%" data-url="{{route('individual_generate_report',['kycId'=>$userKycId])}}">Download</button>
                         <?php if($kycCompleteArray->is_finalapprove ==0 || $kycCompleteArray->is_finalapprove == 3) {?>
                         <button type="button" class="btn btn-save hover-green float-right" data-toggle="modal" data-target="#reportdownload" >Generate Report</button>
                         <?php } } ?>
                         </h2>
                 </th>
             </tr>


         <tr>
            <td align="center" style="">
             <table width="100%" border="1" style="border-collapse:collapse;max-width: 660px; margin:0 auto; background: #fff;border: 1px solid #ddd;padding: 20px;">
                <thead>
                    <tr>
                        <th colspan="2" align="center">Client Type</th>
                    </tr>
                    <tr>
                        <th colspan="2" align="left">Individual <input type="checkbox" checked="true" style="vertical-align: middle;"></th>
                    </tr>
                </thead>
                 <tbody>
                    <tr>
                        <td width="30%">Full Name</td>
                        <td width="70%">{{$userPersonalData->title.' '.$userPersonalData->f_name." ".$userPersonalData->m_name." ".$userPersonalData->l_name}}</td>
                    </tr>

                    <tr>
                        <td width="30%">Passport Number</td>
                        <td width="70%">{{$userPassportNumber}}</td>
                    </tr>

                    <tr>
                        <td width="30%">Passport Expiry Date</td>
                        <td width="70%">{{date('d/m/Y',strtotime($userPassportExp))}}</td>
                    </tr>


                    <tr>
                        <td>Identity Card Number</td>
                        <td><input type="text" class="form-control" name="emirates_id_no" id="emirates_id_no" value="{{$IdentityCardNumber}}" placeholder="Identity Card No"></td>
                    </tr>

                    <tr>
                        <td>Identity Card Expiry Date</td>
                        <td><input type="text" class="form-control emiratesdatepicker" name="emirates_exp_date" id="emirates_exp_date" value="{{date('d/m/Y',strtotime($IdentityCardExp))}}" placeholder="Identity Card Expiry Date"></td>
                    
                    </tr>

                    <tr>
                        <td>Residence Number</td>
                        <td><input type="text" class="form-control" name="residence_no" id="residence_no" value="{{ isset($complianceReport->residence_no) ? $complianceReport->residence_no : ''}}" placeholder="Residence Number"></td>
                    </tr>

                    <tr>
                        <td>Residency Expiry Date</td>
                        <td><input type="text" class="form-control residencydatepicker" name="residency_expiry_date" id="residency_expiry_date" value="{{ isset($complianceReport->residency_expiry_date) ? $complianceReport->residency_expiry_date : ''}}" placeholder="Residency Expiry Date"></td>
                    </tr>
                    <tr>
                        <td>Residence Type</td>
                        <td>{{($userPersonalData->residence_status>0)?$resStatus[$userPersonalData->residence_status]:''}}</td>
                    </tr>
                    
                   
                    <?php
                        $genger=['M'=>'Male','F'=>'Female'];
                        $date_of_birth = ($userPersonalData->date_of_birth != '' && $userPersonalData->date_of_birth != null) ? Helpers::getDateByFormat($userPersonalData->date_of_birth, 'Y-m-d', 'd/m/Y') : '';
                    ?>
                     <tr>
                        <td width="30%">Gender</td>
                        <td>{{isset($userPersonalData->gender) ? $genger[$userPersonalData->gender] : ''}}</td>
                    </tr>
                     <tr>
                        <td width="30%">Date of Birth</td>
                        <td>{{$date_of_birth}}</td>
                    </tr>
                    <tr>
                        <td width="30%">Country of Birth</td>
                        <td>{{ isset($arrCountry[$userPersonalData->birth_country_id]) ? $arrCountry[$userPersonalData->birth_country_id] : ''}}</td>
                    </tr>

                    

                    <?php
                    $completeAddress = '';
                    if(isset($residentialData->building_no)) {
                        $completeAddress = $residentialData->building_no.", ";
                    }
                    if(isset($residentialData->floor_no)) {
                        $completeAddress.=$residentialData->floor_no.", ";
                    }
                    if(isset($residentialData->street_addr)) {
                        $completeAddress.=$residentialData->street_addr.", ";
                    }

                    if(isset($residentialData->city_id)) {
                        $completeAddress.= $residentialData->city_id.", ";
                    }
                     if(isset($residentialData->region)) {
                        $completeAddress.= $residentialData->region.", ";
                    }

                    if(isset($residentialData->country_id) && isset($arrCountry[$residentialData->country_id])) {
                        $completeAddress.= $arrCountry[$residentialData->country_id].", ";
                    }

                    if(isset($residentialData->postal_code)) {
                        $completeAddress.= $residentialData->postal_code;
                    }

                    
                    ?>
                    
                    <tr>
                        <td>Registered Address</td>
                        <td>{{$completeAddress}}</td>
                    </tr>
                    
                    <tr>
                        <td>Telephone Number</td>
                        <td>{{isset($residentialData->addr_phone_no) ? "(+".$residentialData->tele_country_code.") ". $residentialData->addr_phone_no: ''}}</td>
                    </tr>
                    <tr>
                        <td>Mobile Number</td>
                        <td>{{isset($residentialData->addr_mobile_no) ? "(+".$residentialData->addr_country_code.") ". $residentialData->addr_mobile_no : ''}}</td>
                    </tr>
                    <tr>
                        <td>Email Address</td>
                        <td> {{isset($residentialData->addr_email)? $residentialData->addr_email:''}}</td>
                    </tr>
                    
                   
                    <tr>
                        <th colspan="2" align="left" style="padding:13px;">Bank Details</th>
                    </tr>


                   
                    <tr>
                        <td>Bank Name</td>
                        <td><input type="text" class="form-control" name="bank_name" id="bank_name" value="{{ isset($complianceReport->bank_name) ? $complianceReport->bank_name : ''}}" placeholder="Bank Name"></td>
                    </tr>
                    <tr>
                        <td>IBAN</td>
                        <td><input type="text" class="form-control" name="iban" id="iban" value="{{ isset($complianceReport->iban) ? $complianceReport->iban : ''}}" placeholder="IBAN"></td>
                    </tr>
                    <tr>
                        <td>RM Name</td>, 
                        <td><input type="text" class="form-control" name="rm_name" id="rm_name" value="{{ isset($complianceReport->rm_name) ? $complianceReport->rm_name : ''}}" placeholder="RM Name"></td>
                    </tr>
                    <tr>
                        <td>RM Mobile Number</td>
                        <td><input type="text" class="form-control" name="rm_mobile" id="rm_mobile" value="{{ isset($complianceReport->rm_mobile) ? $complianceReport->rm_mobile : ''}}" placeholder="RM Mobile Number"></td>
                    </tr>
                    
                    
                   
                   
                </tbody>
             </table>
            </td>
         </tr>


<!-- General Investigataion  Report -->
                            <tr>
                                <td>
                                    <table width="100%" border="1" style="border-collapse:collapse;max-width: 660px; margin:0 auto; background: #fff;border: 1px solid #ddd;padding: 20px;">

                                        <thead>
                                            <tr>
                                                <th colspan="2" align="center">2. World Check Report</th>
                                            </tr>

                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td width="100%" colspan="2">
                                                    <?php
                                                    $totalMatch   = "";
                                                    $statusName    = '';
                                                    $autoresolved  = 0;
                                                    $falsecount    = 0;
                                                    $possbilecount = 0;
                                                    $positivecount = 0;
                                                    $falsecounting = 0;
                                                    $unspecifiedcount = 0;
                                                    
                                                    $kycData=false;
                                                    if(isset($primaryId)){
                                                        $kycData = Helpers::getkycdataById($primaryId);
                                                    }

                                                    
                                                    
                                                    if($kycData)
                                                    {
                                                       // $totalMatch = $kycData->exactcount+$kycData->falsecount;
                                                        $caseid    =   $kycDetail->caseid;
                                                        $falsecount = $kycData->falsecount;
                                                        $autoresolved = $kycData->autoresolved;
                                                        $totalMatch = ($autoresolved+$falsecount);
                                                        echo "<h5>2.1.  ".ucfirst($kycData->org_name);
                                                        echo "Total Matches-"."( ".$totalMatch." )</h5>";
                                                        // echo "<br>Resolved Matches- ".$kycData->exactcount." (POSITIVE)";
                                                        // echo "<br>Resolved Matches - ".$kycData->falsecount." (FALSE)";
                                                        echo "<br>";
                                                    

                                                     //echo "==>".$primaryId;
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
                                                        $matchresultArray = [];
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
                                                        $ExactCount  = 0;
                                                        $FalseCount  = 0;

                                                    ?>
                                                    <table width="100%" cellspacing="0">
                                                         <tr bgcolor="#fff">
                                                            <td width="27%" style="font-size:14px;">Resolved Matches:</td>
                                                            <td width="5%" class="text-center"><div id="resolveMatch">{{$resolvedMatched}}</div></td>
                                                            <td width="68%" style="font-size:14px; text-align: left;padding:10px !important;"> <div style="margin-right:25px;display: inline;">Positive: {{$positivecount}}</div>
                                                                        <div style="margin-right:15px;display: inline;">Possible: {{$possbilecount}}</div>
                                                                        <div style="margin-right:15px;display: inline;">False: {{$autoresolvedfalse}}</div>
                                                                        <div style="margin-right:15px;display: inline;">Unspecified: {{$unspecifiedcount}}</div>

                                                            </td>
                                                            
                                                        </tr>
                                                        <tr bgcolor="#e4e4e3">
                                                            <td width="27%" style="font-size:14px;">Unresolved Matches:</td>
                                                            <td width="5%" class="text-center"><div id="totalMatch">{{$falsecount}}</div></td>
                                                            <td width="68%" style="font-size:14px;"></td>
                                                           
                                                        </tr>
                                                    </table>
                                                    <?php } ?>
                                                </td>
                                            </tr>


                                            <tr>
                                                <td width="30%">Passport Verify</td>
                                                <td width="70%">
                                                  


                                                   

                                                    <select name="passport_verify" id ="passport_verify" class='form-control'>
                                                        <option value="">Select</option>
                                                        <option value="Yes" <?php if(@$complianceReport->passport_verify == 'Yes') { echo 'selected'; } ?>>Yes</option>
                                                        <option value="No" <?php if(@$complianceReport->passport_verify == 'No') { echo 'selected'; } ?>>No</option>
                                                   </select>

                                                   

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
                                                <th colspan="2" align="center">3. Internet General Investigation Report</th>
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
                                                <th colspan="2" align="center">4. References Investigation Report</th>
                                            </tr>

                                        </thead>
                                        <tbody>
                                            <tr>
                                                

                                            <td> <div class="form-group">

                                                        {{Form::textarea('references_investigation',isset($complianceReport->references_investigation) ? $complianceReport->references_investigation : '',['class'=>'form-control','id'=>'references_investigation','rows'=>'7'])}}

                                                    </div></td>

                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>

                            <!-- General Investigataion  Report  End-->


                            <!-- Analysis of the findings start-->
                            <tr>
                                <td>
                                    <table width="100%" border="1" style="border-collapse:collapse;max-width: 660px; margin:0 auto; background: #fff;border: 1px solid #ddd;padding: 20px;">

                                        <thead>
                                            <tr>
                                                <th colspan="2" align="center">5. Analysis of the Findings</th>
                                            </tr>

                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td> <div class="form-group">

                                                        {{Form::textarea('analysis_findings',isset($complianceReport->analysis_findings) ? $complianceReport->analysis_findings : '',['class'=>'form-control','id'=>'specify_position','rows'=>'3'])}}

                                                    </div></td>

                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>

                            <!-- Analysis of the findings End-->



                            <tr>
                                <td>
                                    <table width="70%" border="1" style="border-collapse:collapse;max-width: 660px; margin:0 auto; background: #fff;border: 1px solid #ddd;padding: 20px;">

                                        <thead>
                                            <tr>
                                                <th colspan="2" align="center">6. Calculation of the final score according to the on boarding risk assessment:</th>
                                            </tr>

                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <table class="table table-striped table-sm">
                                                        <thead>

                                                            <tr>
                                                                <th width="70%">Parameter</th>
                                                                <th width="30%"style="border-left: 1px solid #dee2e6;"><center>Score</center></th>
                                                            </tr>
                                                           </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td width="70%">National Identity</td>
                                                                <td width="30%"style="border-left: 1px solid #dee2e6;"><center>{{@$countryValue}}</center></td>
                                                            </tr>
                                                             <tr>
                                                                <td width="70%">Name Matches – World Check</td>
                                                                <td width="30%"style="border-left: 1px solid #dee2e6;"><center>{{ @$machesWorldcheck }}</center></td>
                                                            </tr>
                                                             <tr>
                                                                <td width="70%">Passport Verification – World Check</td>
                                                                <td width="30%"style="border-left: 1px solid #dee2e6;"><center>{{ @$passport }}</center></td>
                                                            </tr>
                                                             <tr>
                                                                <td width="70%">Exposure (PEP)</td>
                                                                <td width="30%"style="border-left: 1px solid #dee2e6;"><center>{{ @$pep }}</center></td>
                                                            </tr>
                                                             <tr>
                                                                <td width="70%">General Verification on Website and Social Media</td>
                                                                <td width="30%"style="border-left: 1px solid #dee2e6;"><center>{{ @$socialMedia }}</center></td>
                                                            </tr>
                                                            <tr>
                                                                <td width="70%"style="border-left: 1px solid #dee2e6;"><strong>Final Score</strong></td>
                                                                <td width="30%"style="border-left: 1px solid #dee2e6;"><center><strong>{{ @$assesmentData[0]->org_avg_rank }}</strong><center></td>
                                                            </tr>
                                                       
                                                            </tbody>

                                                        </table>

                                                
                                                </td>

                                                <td>
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


                        <?php if($kycCompleteArray->is_finalapprove ==0 || $kycCompleteArray->is_finalapprove ==3) {?>
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

                <?php } else { ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-sm">
                                    <tr>
                                        <td width="100%" >No Record Found</td>
                                     
                                    </tr>
                                </table>
                            </div>
                <?php }?>
            </div>
            </div>
        </div>
    </div>
</div>



@endsection
@section('pageTitle')
User Detail
@endsection


@section('iframe')

<div class="modal" id="personalwcapi">

        <div class="modal-dialog modal-lg custom-modal">
          <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Get Similar</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body" style="padding: 0.5rem;">
              <iframe id ="frameset"  frameborder="0" scrolling="no"></iframe>
            </div>
          </div>
        </div>
</div>


<div class="modal" id="searchCases">

        <div class="modal-dialog modal-md custom-modal">
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
            <div class="modal-body" style="padding: 0.5rem;">
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
            <div class="modal-body">
              <iframe id ="frameset"  frameborder="0" scrolling="no"></iframe>
            </div>
          </div>
        </div>
    </div>

    <!--Generate download -->
     <div class="modal fade" id="reportdownload" role="dialog" style='padding-top:50px;' data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Generate Download</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                
                <form method='post' id='indiv_form_report_submi' action="{{route('report_indicomplete_download',['userKycId'=>$userKycId,'user_id'=>$kycCompleteArray->user_id])}}">
                    @csrf
                <div class='row'>
                    <input type='hidden' name="userKycId" value="{{$userKycId}}">
                    <input type='hidden' name="user_id" value="{{$kycCompleteArray->user_id}}">
                    <div class='col-md-4'>Select Report Format:</div>
                    <div class='col-md-3'>
                        <select name="report_type" id ="report_type" class='form-control'>
                            <option value="">Select</option>
                            <option value="Dexter">Dexter</option>
                            <option value="JuriDex">JuriDex</option>
                        </select>
                    </div>
                </div>


                    <div class='row' style="display:none;margin-top: 10px;" id='addressto'>
                    
                    <div class='col-md-4'>Client Name:</div>
                    <div class='col-md-3'>
                        <input type='text' name="indi_jur_addressto" id="indi_jur_addressto" value="" placeholder="Address To">
                    </div>
                </div> 

                <div class="row" style='padding-top: 20px;'>
                    <div class='col-md-3'></div>
                    <div class='col-md-3'><button class='btn btn-save float-right' id='reportformate'>Submit</button> </div>
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

@section('jscript')
<script src="{{ asset('frontend/multiselect/jquery.multiselect.js') }}"></script>
<script>

    $(function () {
        $('.multiselect_dropdown').multiselect({
            placeholder: 'Select',
            search: true,
            searchOptions: {
                'default': 'Search'
            },
            selectAll: true
        });



        $('#finalForm').validate({
               ignore: [],
               rules: {
                   passport_verify : {
                       required: true,
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

                    passport_verify: {
                        required: messages.req_this_field,
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


        //Report form submit
        $('#indiv_form_report_submi').validate({
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

                        //$('#reportdownload').modal('hide');
                    }
                    
                }


            });


    });
</script>

<style>

.custom-modal .modal-body {
    padding: 20px !important;
}
.btn-bg-green{    
    background-color: green;
    color: #fff;
    padding: 4px 5px;
    font-size: 11px;
    border-radius: 4px;
    text-transform: capitalize;
}
.btn-bg-green:hover{background-color: #21BA45;}

</style>
<script> 

var messages =   {
    get_users_wci: "{{ URL::route('get_users_wci_dummy') }}",
    get_users_wci_single: "{{ URL::route('get_users_wci_single') }}",
    update_kyc_Approve: "{{ URL::route('update_kyc_Approve') }}",

    delete_users: "{{ URL::route('delete_users') }}",
    data_not_found: "{{ trans('error_messages.data_not_found') }}",
    token: "{{ csrf_token() }}",
    token2: "{{ csrf_token() }}",
    token3: "{{ csrf_token() }}",
    APISecret: "{{config('common.APISecret')}}",
    gatwayurl: "{{config('common.gatwayurl')}}",
    contentType: "{{config('common.contentType')}}",
    gatwayhost: "{{config('common.gatwayhost')}}",
    apiKey: "{{config('common.apiKey')}}",
    groupId: "{{config('common.groupId')}}",
    content: "{{ $content }}",
    user_kyc_id: "{{ $userKycId }}",
    entityName: "{{$apiFullName}}",
    entityDOB: "{{ $date_of_birthAPI }}",
    get_resolution_toolkit : "{{ URL::route('get_resolution_toolkit') }}",
    //entityType: "{{ $userKycId }}",

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
   // alert(this);
    $(this).addClass('active');
});


        $('.emiratesdatepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        minDate: new Date()
        
        });

        $('.residencydatepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        minDate: new Date()

        });

 //document.body.style.backgroundColor = "transparent";
</script>
<style>
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
</style>
<link href="{{ asset('frontend/multiselect/jquery.multiselect.css')}}" rel="stylesheet" />
<script type="text/javascript" src="{{asset('common/js/jquery.validate.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>
<script src="{{ asset('backend/js/wciapicall.js') }}" type="text/javascript"></script>
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

