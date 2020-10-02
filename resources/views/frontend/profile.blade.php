@extends('layouts.app')

@section('content')


<section>
    <div class="container">
        <div class="row">
            <div id="header" class="col-md-3">
                @include('layouts.user-inner.left-menu')

            </div>
           
            <div class="col-md-9 dashbord-white">
                <div class="form-section">
                    <div class="row marB10">
                        <div class="col-md-12">
                            <h3 class="h3-headline">{{trans('forms.personal_Profile.Label.heading')}}</h3>
                        </div>
                    </div>

                    {!!
                    Form::open(
                    array(
                    'name' => 'personalInformationForm',
                    'id' => 'personalInformationForm',
                    'url' => route('update_personal_profile',['id'=>@$userPersonalData['user_personal_id'],'user_kyc_id'=>@$benifinary['user_kyc_id'],'corp_user_id'=>@$benifinary['corp_user_id'],'is_by_company'=>@$benifinary['is_by_company']]),
                    'autocomplete' => 'off','class'=>'loginForm form form-cls'
                    ))
                    !!}
                   
                   
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                {{Form::label('f_name',trans('forms.personal_Profile.Label.f_name'))}} <span class="mandatory">*<span>
                                    <div class="input-group input-action titleerror">
                                        <div class="input-group-btn">
                                            {!!
                                            Form::select('title',
                                            [''=>trans('forms.personal_Profile.plc_holder.select_title') ,'Mr.'=>'Mr.','Ms.'=>'Ms.','Mrs'=>'Mrs','Dr.'=>'Dr.'],@$userPersonalData['title'],
                                            array('id' => 'title','class'=>'form-control drop-title'))
                                            !!}
                                        </div>
                                       <!-- <input type="text" class="form-control"   placeholder="{{trans('forms.personal_Profile.Label.f_name')}}" name="f_name"  value="{{(@$userPersonalData['f_name'])}}">-->


                                        <?php
                                       
                                        if(isset($userPersonalData['f_name'])) {
                                            if(isset($userSignupdata->f_name)) {
                                                $firstName = $userSignupdata->f_name;
                                            } else {
                                                $firstName = $userPersonalData['f_name'];
                                            }
                                        } else {

                                            if(isset($userSignupdata->f_name)) {
                                                $firstName = $userSignupdata->f_name;
                                            } else if(isset($share_name)){
                                                $firstName = $share_name;
                                            } else {
                                               $firstName = '';
                                            }
                                           
                                        }


                                        if(isset($userPersonalData['m_name'])) {
                                            if(isset($userSignupdata->m_name)) {
                                                $midName = $userSignupdata->m_name;
                                            } else {
                                                $midName = $userPersonalData['m_name'];
                                            }
                                        } else {
                                            if(isset($userSignupdata->m_name)) {
                                                $midName = $userSignupdata->m_name;
                                            } else {
                                                $midName = '';
                                            }
                                        }

                                        if(isset($userPersonalData['l_name'])) {
                                            if(isset($userSignupdata->l_name)) {
                                                $lastName = $userSignupdata->l_name;
                                            } else {
                                                $lastName = $userPersonalData['l_name'];
                                            }
                                        } else {
                                            if(isset($userSignupdata->l_name)) {
                                                $lastName = $userSignupdata->l_name;
                                            } else {
                                                $lastName = '';
                                            }
                                        }

//$userSignupdata->country_id
                                        if(isset($userPersonalData['birth_country_id'])) {
                                            if(isset($userSignupdata->country_id)) {
                                                $countryId = $userSignupdata->country_id;
                                            } else {
                                                $countryId = $userPersonalData['birth_country_id'];
                                            }
                                        } else {
                                            if(isset($userSignupdata->country_id)) {
                                                $countryId = $userSignupdata->country_id;
                                            } else {
                                                $countryId = '';
                                            }
                                        }


                                         if(isset($userPersonalData['date_of_birth'])) {
                                            if(isset($userSignupdata->date_of_birth)) {
                                                $userDOB = $userSignupdata->date_of_birth;
                                                $userDOB = Helpers::getDateByFormat($userDOB, 'Y-m-d', 'd/m/Y');
                                            } else {
                                                $userDOB = $userPersonalData['date_of_birth'];
                                                $userDOB = Helpers::getDateByFormat($userDOB, 'Y-m-d', 'd/m/Y');
                                            }
                                        } else {
                                            if(isset($userSignupdata->date_of_birth)) {
                                                $userDOB = $userSignupdata->date_of_birth;
                                                $userDOB = Helpers::getDateByFormat($userDOB, 'Y-m-d', 'd/m/Y');
                                            } else {
                                                $userDOB = null;
                                            }
                                        }


                                        ?>
<input type="text" class="form-control"   placeholder="{{trans('forms.personal_Profile.Label.f_name')}}" name="f_name" value="{{$firstName}}" autocomplete="true">
                                    </div>
                                    <span class="text-danger">{{ $errors->first('f_name') }}</span>                
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                {{Form::label('m_name',trans('forms.personal_Profile.Label.m_name'))}}
                                <input type="text" class="form-control"  placeholder="{{trans('forms.personal_Profile.Label.m_name')}}" name="m_name" value="{{$midName}}">
                                <span class="text-danger">{{ $errors->first('m_name') }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{Form::label('l_name',trans('forms.personal_Profile.Label.l_name'))}} <span class="mandatory">*<span>
                                <input type="text" class="form-control"  placeholder="{{trans('forms.personal_Profile.Label.l_name')}}" name="l_name" value="{{$lastName}}">

                                <span class="text-danger">{{ $errors->first('l_name') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">

                                {{Form::label('gender',trans('forms.personal_Profile.Label.gender'))}} <span class="mandatory">*<span>
                                {!!
                                Form::select('gender',
                                [''=>trans('forms.personal_Profile.plc_holder.select_gender'),'M'=>'Male','F'=>'Female'],@$userPersonalData['gender'],
                                array('id' => 'gender','class'=>'form-control'))
                                !!}
                                <span class="text-danger">{{ $errors->first('gender') }}</span>
                            </div>
                        </div>
                       
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('date_of_birth',trans('forms.personal_Profile.Label.date_of_birth'), array('class' => ''))}} <span class="mandatory">*<span>
                                <div class="input-group">
                                    {{ Form::text('date_of_birth',$userDOB, ['class' => 'form-control datepicker','placeholder'=>trans('forms.personal_Profile.Label.date_of_birth'),'id' => 'date_of_birth']) }}
                                    <div class="input-group-append">
                                        <i class="fa fa-calendar-check-o"></i>
                                    </div>
                                </div>
                                <span class="text-danger">{{ $errors->first('date_of_birth') }}</span>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="pwd">{{trans('forms.personal_Profile.Label.birth_country_name')}}</label> <span class="mandatory">*<span>
                                {!!
                                Form::select('birth_country_id',
                                [''=>'Select']+Helpers::getCountryDropDown()->toArray(),
                                $countryId,
                                array('id' => 'birth_country',
                                'class'=>'form-control select2Cls'))
                                !!}
                                <span class="text-danger">{{ $errors->first('birth_country_id') }}</span>
                            </div>
                        </div>
                       

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="pwd">{{trans('forms.personal_Profile.Label.birth_city_id')}}</label> <span class="mandatory">*<span>

                                {{Form::text('birth_city_id',@$userPersonalData['birth_city_id'],['class'=>'form-control','placeholder'=>'Enter City of birth','id' => 'birth_city_id'])}}
                                <span class="text-danger">{{ $errors->first('birth_city_id') }}</span>
                            </div>
                        </div>

                    </div>


                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="pwd">{{trans('forms.personal_Profile.Label.father_name')}}</label> <span class="mandatory">*<span>
                                <input type="text" class="form-control"  placeholder="{{trans('forms.personal_Profile.Label.father_name')}}" name="father_name" value="{{@$userPersonalData['father_name']}}" >
                                <span class="text-danger">{{ $errors->first('father_name') }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="pwd">{{trans('forms.personal_Profile.Label.mother_f_name')}}</label> <span class="mandatory">*<span>
                                <input type="text" class="form-control"  placeholder="{{trans('forms.personal_Profile.Label.mother_f_name')}}" name="mother_f_name" value="{{@$userPersonalData['mother_f_name']}}" >
                                <span class="text-danger">{{ $errors->first('mother_f_name') }}</span>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="pwd">{{trans('forms.personal_Profile.Label.mother_m_name')}}</label> <span class="mandatory">*<span>
                                <input type="text" class="form-control"  placeholder="{{trans('forms.personal_Profile.Label.mother_m_name')}}" name="mother_m_name" value="{{@$userPersonalData['mother_m_name']}}">
                                <span class="text-danger">{{ $errors->first('mother_m_name') }}</span>
                            </div>
                        </div>

                    </div>


                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="reg_no">{{trans('forms.personal_Profile.Label.reg_no')}}</label> <span class="mandatory"> <span>
                                <input type="text" class="form-control"  placeholder="{{trans('forms.personal_Profile.Label.reg_no')}}" name="reg_no" id="registration_no" value="{{@$userPersonalData['reg_no']}}">
                                <span class="text-danger">{{ $errors->first('reg_no') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="reg_place">{{trans('forms.personal_Profile.Label.reg_place')}}</label> <span class="mandatory"> <span>
                                <input type="text" class="form-control"  placeholder="{{trans('forms.personal_Profile.Label.reg_place')}}" name="reg_place"  id="registration_place" value="{{@$userPersonalData['reg_place']}}">
                                <span class="text-danger">{{ $errors->first('reg_place') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                 
                                {{Form::label('f_nationality_id',trans('forms.personal_Profile.Label.f_nationality_id'),['class'=>''])}} <span class="mandatory">*<span>
                                {!!
                                Form::select('f_nationality_id',
                                [''=>'Select']+Helpers::getCountryDropDown()->toArray(),
                                @$userPersonalData['f_nationality_id'],
                                array('id' => 'nationality',
                                'class'=>'form-control select2Cls'))
                                !!}
                                <span class="text-danger">{{ $errors->first('f_nationality_id') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{Form::label('sec_nationality_id',trans('forms.personal_Profile.Label.sec_nationality_id'),['class'=>''])}} <span class="mandatory">*<span>
                                {!!
                                Form::select('sec_nationality_id',
                                [''=>'Select']+ ['9999'=>'Not Applicable'] +Helpers::getCountryDropDown()->toArray(),
                                @$userPersonalData['sec_nationality_id'],
                                array('id' => 'secondry_nationality',
                                'class'=>'form-control select2Cls'))
                                !!}
                                <span class="text-danger">{{ $errors->first('sec_nationality_id') }}</span>
                            </div>
                        </div>
                    </div>
                   
                     @if(is_array($userDocumentType))
                     @foreach($userDocumentType as $objDoc)
                     @php
                        $issuance_date[]=(@$objDoc['issuance_date']!='' && @$objDoc['issuance_date']!=null) ? Helpers::getDateByFormat($objDoc['issuance_date'], 'Y-m-d', 'd/m/Y') :'';
                        $expire_date[]=(@$objDoc['expire_date']!='' && @$objDoc['expire_date']!=null) ? Helpers::getDateByFormat($objDoc['expire_date'], 'Y-m-d', 'd/m/Y') :'';
                        $docnumber[] = isset($objDoc['document_number'])?$objDoc['document_number']:'';
                        $ids[] = isset($objDoc['id'])?$objDoc['id']:'';
                        $docIds[] = isset($objDoc['doc_id'])?$objDoc['doc_id']:'';
                        $docStatus[] = isset($objDoc['status'])?$objDoc['status']:'';
                        
                        @endphp
                        @endforeach
                     @endif
                   
                   


                   

                    <div id="childInfo">
                        <div id="TrainingPeriod0" class="trainingperiod">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">

                                        <label for="pwd">{{trans('forms.personal_Profile.Label.document_type')}} </label>


                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="pwd">{{trans('forms.personal_Profile.Label.document_number')}}</label>
                                       
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {{ Form::label('issuance_date',trans('forms.personal_Profile.Label.issuance_date'), array('class' => ''))}}
                                       
                                       
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {{ Form::label('expiry_date',trans('forms.personal_Profile.Label.expire_date'), array('class' => ''))}}
                                       
                                       
                                       
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


<!-- Start first form -->

<?php
$showPopup=0;
$j = 0;
$docType = 1;
foreach($resDocumentName  as $docName) {
?>

                    
                    <div id="childInfo">
                        <div id="TrainingPeriod0" class="trainingperiod">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">

                                        <label for="pwd">{{$docType}}) {{$docName->doc_name}}</label> <?php if($j==0 || $j==1) { ?> <span class="mandatory">*<span> <?php } ?>
                                        <input type="hidden" class="form-control"  placeholder="{{trans('forms.personal_Profile.Label.document_number')}}" name="document_type_id{{$docType}}" id="document_type_id{{$docType}}" value="{{$docName->id}}">
                                        <input type="hidden" class="form-control"  placeholder="" name="is_monitor{{$j}}" id="is_monitor{{$j}}" value="{{$docName->is_monitor}}">
                                        <input type="hidden" class="form-control"  placeholder="" name="doc_no{{$j}}" id="doc_no{{$j}}" value="{{$docName->doc_no}}">
                                        <span class="text-danger">{{ $errors->first('document_type_id[]') }}</span>
                                        
                                        @if($docName->doc_no==1 && isset($docStatus[$j]) && $docStatus[$j]==1)
                                        <?php
                                        $param['doc_id']=$docIds[$j];
                                        $param['id']=$ids[$j];
                                        $param['doc_no']=$docName->doc_no;
                                        $showPopup=1;
                                        ?>
                                        <button type="button" class="btn btn-save" id="btOtp" data-toggle="modal" data-target="#docModal">Update</button>
                                        
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                      <?php if($j!=1) {
                                        $share_passportval = '';
                                      } else {
                                        $share_passportval = $share_passportnumber;
                                      }

                                      if(isset($docnumber[$j])) {
                                           $docval = @$docnumber[$j];
                                          }
                                      else if(isset($share_passportval) ) {
                                               $docval = $share_passportval;
                                              }
                                        else {
                                        $docval =  '';
                                              }
                                      ?>
                                        <input type="text" value = "{{ $docval }}" class="form-control"  placeholder="{{trans('forms.personal_Profile.Label.document_number')}}" name="document_number{{$j}}" id="document_number{{$j}}">
                                        <span class="text-danger">{{ $errors->first('document_number.0') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                       
                                        <div class="input-group">
                                           
                                            {{ Form::text('issuance_date'.$j,isset($issuance_date[$j]) ? $issuance_date[$j] :  '', ['class' => 'form-control datepicker issuance-date'.$j,'placeholder'=>'Issuance Date','id' => 'issuance_date'.$j]) }}
                                            <div class="input-group-append">
                                                <i class="fa fa-calendar-check-o"></i>
                                            </div>
                                        </div>
                                        <span class="text-danger">{{ $errors->first('issuance_date.0') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                       
                                        <div class="input-group">
                                            {{ Form::text('expiry_date'.$j,isset($expire_date[$j]) ? $expire_date[$j] :  '', ['class' => 'form-control datepicker expiry_date'.$j,'placeholder'=>trans('forms.personal_Profile.Label.expire_date'),'id' => 'expiry_date'.$j]) }}
                                            <div class="input-group-append">
                                                <i class="fa fa-calendar-check-o"></i>
                                            </div>
                                        </div>
                                        <span class="text-danger">{{ $errors->first('expiry_date.'.$j) }}</span>
                                        <div class="deleteDocumentbtn remove text-right mt-1"  style="display: none;"><i class="fa fa-trash-o deleteSkill" title="Remove" aria-hidden="true"></i></div>
                                    </div>
                                    
                                    
                                </div>
                            </div>
                        </div>
                    </div>

<?php
$docType++;
$j++; } ?>

 
                    @if(is_array($userSocialMedia) && count($userSocialMedia))
                    @php
                    $j=0;
                    @endphp
                    @foreach($userSocialMedia as $objSocial)
                    <div class="row clonedclonedSocialmedias" id="clonedSocialmedias0">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pwd">{{trans('forms.personal_Profile.Label.social_media')}}</label> <span class="mandatory">*<span>
                                {!!
                                Form::select('social_media_id[]',
                                [''=>'Select Social Media'] + Helpers::getSocialmediaDropDown()->toArray(),
                                $objSocial['social_media'],
                                array('id' => 'social_media_id'.$j,'data' => $j,
                                'class'=>'form-control social'))
                                !!}
                                <span class="text-danger">{{ $errors->first('social_media_id.0') }}</span>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group postion-delete-icon">
                                <label for="pwd">{{trans('forms.personal_Profile.Label.social_media_link')}}</label> <span class="mandatory">*<span>
                                <input type="text" class="form-control"  placeholder="{{trans('forms.personal_Profile.Label.f_name')}}" name='social_media_link[]' value="{{$objSocial['social_media_link']}}" id='social_media_link{{$j}}'>
                                <span class="text-danger">{{ $errors->first('social_media_link[]') }}</span>
                                <div class="deleteSkillbtn remove text-right mt-1 "  style="display:block;"><i class="fa fa-trash-o deleteSkill" title="Remove" aria-hidden="true" style="cursor: pointer;"></i></div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                        <input type="text" class="form-control" name="social_other[0]" id='other_{{$j}}' placeholder="Enter Other Social Media" value="{{$objSocial['social_media_other']}}"/>
                        </div>
                    
                    </div>
                     @php 
                       
                       $j++; 

                    @endphp
                    @endforeach


                    <div class="row">
                        <div class="col-md-12">
                            <span class="add-socialmedia pull-right marT10 text-color" style="">{{trans('master.personalProfile.add')}}</span>
                        </div>
                    </div>
                    @else
                    <div class="row clonedclonedSocialmedias" id="clonedSocialmedias0">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pwd">{{trans('forms.personal_Profile.Label.social_media')}}</label> <span class="mandatory">*<span>
                                {!!
                                Form::select('social_media_id[]',
                                [''=>'Select Social Media'] + Helpers::getSocialmediaDropDown()->toArray(),
                                null,
                                array('id' => 'social_media_id0','data' => 0,
                                'class'=>'form-control social'))
                                !!}

                                <span class="text-danger">{{ $errors->first('social_media_id.0') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group postion-delete-icon">
                                <label for="pwd">{{trans('forms.personal_Profile.Label.social_media_link')}}</label> <span class="mandatory">*<span>
                                <input type="text" class="form-control"  placeholder="{{trans('forms.personal_Profile.Label.social_media_link')}}" name='social_media_link[]' id='social_media_link0'>
                                <span class="text-danger">{{ $errors->first('social_media_link.0') }}</span>
                                <div class="deleteSkillbtn remove text-right mt-1 "  style="display: none;"><i class="fa fa-trash-o deleteSkill" title="Remove" aria-hidden="true" style="cursor: pointer;"></i></div>
                            </div>
                        </div>
                         
                        <div class="col-md-6">
                        <input type="text" class="form-control" name="social_other[0]" id="other_0" placeholder="Enter Other Social Media" value=""/>
                        </div>
                    
                    </div>

                   

                    <div class="row">
                        <div class="col-md-12">
                            <span class="add-socialmedia pull-right marT10 text-color" style="">{{trans('master.personalProfile.add')}}</span>
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-12">
                            <hr></hr>
                        </div>
                    </div>
                    {{ (isset($userPersonalData->residence_status) ? ( (1 == $userPersonalData->residence_status) ? 'selected' : '' ) : '')}}

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {{Form::label('residence_status',trans('forms.personal_Profile.Label.residence_status'),['class'=>''])}} <span class="mandatory">*<span>

                                {!!
                                Form::select('residence_status',
                                [''=>'Select Residence Status','1'=>'Resident','2'=>'Non-Resident'],
                                @$userPersonalData['residence_status'],
                                array('id' => 'residence_status',
                                'class'=>'form-control'))
                                !!}
                                <span class="text-danger">{{ $errors->first('residence_status') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{Form::label('family_status',trans('forms.personal_Profile.Label.family_status'),['class'=>''])}} <span class="mandatory">*<span>

                                {!!
                                Form::select('family_status',
                                [''=>'Select Family Status','1'=>'Single','2'=>'Married','3'=>'Divorced','4'=>'Separated','5'=>'Minor','6'=>'Engaged','7'=>'Widowed'],
                                @$userPersonalData['family_status'],
                                array('id' => 'family_status',
                                'class'=>'form-control'))
                                !!}
                                <span class="text-danger">{{ $errors->first('family_status') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {{Form::label('educational_level',trans('forms.personal_Profile.Label.educational_level'),['class'=>''])}} <span class="mandatory">*<span>

                                {!!
                                Form::select('educational_level',
                                [''=>'Select Educational Level']+Helpers::getEducationLevel(),
                                @$userPersonalData['educational_level'],
                                array('id' => 'educational_level',
                                'class'=>'form-control'))
                                !!}  
                                <span class="text-danger">{{ $errors->first('educational_level') }}</span>
                            </div>
                        </div>
                    </div>
                   
                   

                    <div class="row" style='display: none' id="education_other_div">
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="text" class="form-control" name="education_other" id="education_other"
                                value="{{@$userPersonalData['educational_level_other']}}" placeholder="{{trans('forms.personal_Profile.plc_holder.enter_other_edu')}}" maxlength="50">
                                <span class="text-danger">   </span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {{Form::label('is_residency_card',trans('forms.personal_Profile.Label.is_residency_card'),['class'=>''])}} <span class="mandatory">*<span>

                                {!!
                                Form::select('is_residency_card',
                                [''=>'Select ','1'=>'No','2'=>'Resident Alien Card (Green Card)','3'=>'Carte de sejour (France)','4'=>'Permanent Residency Card (Canada)']+['5'=>'Others'],
                                @$userPersonalData['is_residency_card'],
                                array('id' => 'is_residency_card','class'=>'form-control'))
                                !!}
                                <span class="text-danger">{{ $errors->first('is_residency_card') }}</span>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="pwd">{{trans('forms.personal_Profile.Label.do_you_hold_held')}}  <span class="mandatory">*<span></label>
                                   
                                {!!
                                Form::select('Select',
                                [''=>'Select','1'=>'Yes','0'=>'No',],
                                @$userPersonalData['do_you_hold'],
                                array('id' => 'do_you_hold', 'name' => 'do_you_hold',
                                'class'=>'form-control'))
                                !!}
                            </div>
                        </div>
                    </div>

            <!--yes and No div show-->  

            <div id=do_you_hold_hide_show style="display: none">          
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">

                                <div class="clearfix marB15"></div>
                                <div class="form-check-inline">
                             <label class="form-check-label errornew" for="check1">

                               
                            @php
                                $chk1='';
                                $chk2='';
                               if(isset($userPersonalData['political_position'])){
                                $political=explode(',' ,$userPersonalData['political_position']);
                                    if(is_array($political) && count($political)){
                                       if(isset($political[0])){
                                            if($political[0]=='1'){
                                               $chk1=true;
                                            }else{
                                                 $chk1=false;
                                            }

                                       }

                                       if(isset($political[1])){
                                            if($political[1]=='2'){
                                               $chk2=true;
                                            }else{
                                                 $chk2=false;
                                            }

                                       }
                                    }
                                 }
                            @endphp

                            {{Form::checkbox('political_position_hold[]','1',$chk1,['class'=>''])}}
                            {{trans('forms.personal_Profile.Label.senior_position_sector')}}
                             </label>
                            </div>


                           
                        <div class="form-check-inline">
                         <label class="form-check-label" for="check2">
                           
                        {{Form::checkbox('political_position_hold[]','2',$chk2,['class'=>''])}}
                         
                        {{trans('forms.personal_Profile.Label.political_position')}}
                                    </label>
                                </div>
                                <span class="text-danger">{{ $errors->first('political_position_dec') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {{Form::label('',trans('forms.personal_Profile.Label.political_position_dec'),['class'=>''])}}
                            {{Form::textarea('political_position_dec',@$userPersonalData['political_position_dec'],['class'=>'form-control','id'=>'specify_position','rows'=>'3'])}}
                            <span class="text-danger">{{ $errors->first('political_position_dec') }}</span>
                        </div>
                    </div>
                </div>

            </div>
            <!--yes and No div show end-->



            <!--related directly start from here-->

            <div class="row" >
                <div class="col-md-12">
                        <div class="form-group">
                             <label for="pwd">{{trans('forms.personal_Profile.Label.are_you_related_directly_or_indirectly')}} <span class="mandatory">*<span></label>
                               
                            {!!
                            Form::select('Select',
                            [''=>'Select','1'=>'Yes','0'=>'No',],
                            @$userPersonalData['you_related_directly'],
                            array('id' => 'are_you_directly', 'name' => 'are_you_directly',
                            'class'=>'form-control'))
                            !!}
                        </div>
                </div>
            </div>


                <div id="are_you_related_directly_hide_show" style="display: none">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                           
                            <div class="clearfix marB15"></div>
                            <div class="form-check-inline">
                                <label class="form-check-label errornew" for="check3">

                                @php
                                if(isset($userPersonalData['related_political_position']))
                                {
                                  $related_political=explode(',', $userPersonalData['related_political_position']);
                                  if(is_array($related_political) && count($related_political)){
                                       if(isset($related_political[0])){
                                            if($related_political[0]=='3'){
                                               $chk1=true;
                                            }else{
                                                 $chk1=false;
                                            }

                                       }

                                       if(isset($related_political[1])){
                                            if($related_political[1]=='4'){
                                               $chk2=true;
                                            }else{
                                                 $chk2=false;
                                            }

                                       }
                                    }
                                 
                                }
                                @endphp
                                {{Form::checkbox('related_political_position[]','3',$chk1,['class'=>''])}}
                               
                                {{trans('forms.personal_Profile.Label.senior_position_sector')}}
                                </label>
                            </div>
                            <div class="form-check-inline">
                                <label class="form-check-label" for="check4">
                               
                                    {{Form::checkbox('related_political_position[]','4',$chk2,['class'=>''])}}
                                    {{trans('forms.personal_Profile.Label.political_position')}}
                                </label>
                            </div>
                            <span class="text-danger">{{ $errors->first('related_political_position') }}</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {{Form::label('',trans('forms.personal_Profile.Label.political_position_dec'),['class'=>''])}}
                            {{Form::textarea('related_political_position_dec',@$userPersonalData['related_political_position_dec'],['class'=>'form-control','id'=>'related_political_position_dec','rows'=>'3'])}}
                            <span class="text-danger">{{ $errors->first('related_political_position_dec') }}</span>
                        </div>
                    </div>
                </div>
            </div>    

                <div class="row marT60">
                    <div class="col-md-12 text-right">
                    @if($kycApproveStatus==0)
                        {{ Form::submit('Save',['class'=>'btn btn-save','name'=>'save']) }}
                        {{ Form::submit('Save & Next',['class'=>'btn btn-save','name'=>'save_next']) }}

                    @else
                        
                        <a href="{{$benifinary['next_url']}}" class="btn btn-save">Next</a>
                    @endif
                    

                    </div>
                </div>
            {{ Form::close() }}
            </div>
        </div>

    </div>
</div>
   
</section>
@if($showPopup==1)
 <div class="modal mod" id="docModal">
  <div class="modal-dialog">
      <div class="modal-content mod-content" style="width:500px;">

      <!-- Modal Header -->
      <div class="modal-header mod-header">
        <h4 class="modal-title">Update Passport</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
       {!!
            Form::open(
            array(
            'name' => 'updateDocForm',
            'id' => 'updateDocForm',
            'autocomplete' => 'off','class'=>'loginForm form form-cls','files' => true))
        !!}
      <!-- Modal body -->
      <div class="modal-body mod-body">
       <div id="errormsg"></div>
        <div class="row">
           <div class="col-md-12"> 
               <div class="form-group">
                   <label for="pwd">{{trans('forms.personal_Profile.Label.document_number')}}</label>
                   <input type="text" value = "" class="form-control"  placeholder="{{trans('forms.personal_Profile.Label.document_number')}}" name="document_number" id="document_number">
                    <span class="text-danger" id="errordocument_number"></span>
               </div>
           </div>
        </div>
        <div class="row">
           <div class="col-md-12"> 
               <div class="form-group">
                   <label for="pwd">{{trans('forms.personal_Profile.Label.issuance_date')}}</label>
                    <div class="input-group">
                                           
                    {{ Form::text('issuance_date','', ['class' => 'form-control datepicker issuance-date','placeholder'=>trans('forms.personal_Profile.Label.issuance_date'),'id' => 'issuance_date']) }}
                    <div class="input-group-append">
                    <i class="fa fa-calendar-check-o"></i>
                    </div>
                    </div>
                    <span class="text-danger" id="errorissuance_date"></span>
               </div>
           </div>
        </div>
        <div class="row">
           <div class="col-md-12"> 
               <div class="form-group">
                    <label for="pwd">{{trans('forms.personal_Profile.Label.expire_date')}}</label>
                    <div class="input-group">
                    {{ Form::text('expiry_date','', ['class' => 'form-control datepicker expiry_date','placeholder'=>trans('forms.personal_Profile.Label.expire_date'),'id' => 'expiry_date']) }}
                    <div class="input-group-append">
                    <i class="fa fa-calendar-check-o"></i>
                    </div>
                    </div>
                    <span class="text-danger" id="errorexpiry_date"></span>                
               </div>
           </div>
        </div>
          
        <div class="row">
           
        <div class="col-md-6">
                       
        <ul class="fileList"></ul>
        <span class="text-danger" id="errorfiles"></span> 
        </div> 
        <div class="col-md-6 files position-relate pull-right" id="files0">
        
        <span class="upload-btn-wrapper">
 
         <label for="pwd">   Upload  </label>
         <input id="file_upload" type="file"  name="files[]" data-id="{{'1#'.'1#'.'1#1'}}" multiple class="upload"/>
        
        </span>
        
        
        </div> 
           
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="pwd">{{trans('master.otpForm.otp_heading')}}</label>
                    <input type="text" class="form-control"  placeholder="{{trans('master.otpForm.enter_otp')}}" name="otp" id="otp" required>
                    <span class="text-danger" id="errorotp"></span>
                </div>
                <p class="p-conent">{{trans('master.otpForm.enter_otp_below')}}</p> 
            </div>
        </div>
          
        <div class="row">
            <div class="col-md-12">
           
               {{ Form::button('Resend OTP',['class'=>'btn btn-prev','name'=>'btresend','id'=>'btresend']) }}
            </div>
        </div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        {{ Form::hidden('user_kyc_id',$benifinary['user_kyc_id'], ['class' => 'form-control']) }}
        {{ Form::hidden('id',$param['id'], ['class' => 'form-control']) }}
        {{ Form::hidden('doc_id',$param['doc_id'], ['class' => 'form-control']) }}
        {{ Form::hidden('doc_no',$param['doc_no'], ['class' => 'form-control']) }}
        
        {{ Form::button('Close',['class'=>'btn btn-prev','name'=>'close','data-dismiss'=>"modal"]) }}
        {{ Form::button('Update',['class'=>'btn btn-save','name'=>'save','id'=>'btupdate']) }}
        
      </div>
      
      {{ Form::close() }}
      <script>
          $('#file_upload').change(function() {
             var output = []; 
             var arrFile = $('#file_upload')[0].files;
             var len    =   $('#file_upload')[0].files.length;
             for(var i = 0; i < len; i++){
                 var removeLink='';
                 //var removeLink = "<a class=\"removeDoc\" href=\"#\"  data-fileid=\"" + i + "\">Remove</a>";

                 output.push("<li id='"+ i +"'><strong>", escape($('#file_upload')[0].files[i].name), "</strong>  ", removeLink, "</li> ");
             }
             $(".fileList").html();
             $(".fileList").append(output.join(""));
          });
        
        $('#btupdate').on('click',function(){

            var form = $('#updateDocForm')[0];

            var formData = new FormData(form);

            $.ajax({
                url: "{{route('update_passport')}}",
                data: formData,
                method: "POST",
                processData: false,
                contentType: false,
                success: function (data) {
                
            var result=JSON.parse(data); 
                console.log(result);
               
                if(result['status']=="success"){
                 
                   window.location.reload();
                }else{

                    var messages=result['message'];
                    var messagekey=result['messagekey'];
                    
                       $.each(messages, function(key,value) {
                            console.log(key);
                           $('#'+messagekey[key]).html(value);
                       }); 

                }            
               
               
            },
                error: function (data) {
                    alert("ERROR - " + data.responseText);
                }
            });
        });
        
        
       
      </script>
      
    </div>
  </div>
</div>

@endif
@include('frontend.modal')


@endsection

@section('pageTitle')
Personal Information
@endsection
@section('additional_css')

  <style>
  .mod-header {
    display: -ms-flexbox;
    display: flex;
    -ms-flex-align: start;
    align-items: flex-start;
    -ms-flex-pack: justify;
    justify-content: space-between;
    padding: 1rem 1rem;
    border-bottom: 1px solid #0b0202;
    border-top-left-radius: calc(.3rem - 1px);
    border-top-right-radius: calc(.3rem - 1px);
}    
  .mod .mod-body {
    min-height: 300px;
    font-size: 14px;
    color: #010101;
    padding: 1rem 2.188rem 2.4rem 2.188rem;
    color: #0b0202;
    line-height: 22px;
  }    
  .mod .mod-content {
    background-color: #fff;
}    
  .errornew label.error {
    position: absolute;
    top: 15px;
   
}

.titleerror label.error{
        position: absolute;
    top: 29px;
}

.issuance-date {
      z-index: 1600 !important; /* has to be larger than 1050 */
    }

.expiry_date {
      z-index: 1600 !important; /* has to be larger than 1050 */
    }



</style>
<!--<link rel="stylesheet" href="{{ asset('backend/theme/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css') }}">-->

<link href="{{ asset('frontend/inside/plugin/datepicker/jquery-ui.css') }}" rel="stylesheet">

@endsection
@section('jscript')
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

<script type="text/javascript" src="{{ asset('frontend/outside/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('frontend/outside/js/validation/socialmedia.js')}}"></script>
<script src="{{ asset('frontend/outside/js/validation/personalForm.js')}}"></script>


<script src="{{ asset('frontend/inside/plugin/datepicker/jquery-ui.js') }}"></script>  
<!-- <script src="{{ asset('frontend/outside/js/validation/popup.js')}}"></script> -->
<script>
$(document).ready(function () {
    var disabledAll='{{$kycApproveStatus}}';
    if(disabledAll!=0){
        $("#personalInformationForm :input"). prop("disabled", true); 
        $("#btOtp").prop("disabled",false); 
    }
    
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });
    
    $('#docModal').on('shown.bs.modal', function() {
      $('.issuance-date').datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        maxDate: new Date(),
        container: '#docModal .modal-body'
        });
        
       $('.expiry_date').datepicker({
        
        minDate:'+30D',
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        container: '#docModal .modal-body'
    }); 
    });

    $('#date_of_birth').datepicker({dateFormat: 'dd/mm/yy', maxDate: new Date(), changeMonth: true, changeYear: true});
    $('#legal_maturity_date').datepicker({dateFormat: 'dd/mm/yy', maxDate: new Date(), changeMonth: true, changeYear: true});
    
     
    
    $('.issuance-date0').datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        maxDate: new Date(),
    });

    $('.issuance-date1').datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        maxDate: new Date(),
    });

     $('.issuance-date2').datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        maxDate: new Date(),
    });

     $('.issuance-date3').datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        maxDate: new Date(),
    });

    

    $('.expiry_date0').datepicker({
        
       minDate : 0,
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        
    });

    $('.expiry_date1').datepicker({
        minDate : 0,
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        
    });

    $('.expiry_date2').datepicker({
        minDate : 0,
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        
    });

    $('.expiry_date3').datepicker({
        minDate : 0,
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        
    });



   
   

    // $('#myModal').modal('show');



    var baseurl="{{url('')}}";

    $.get(baseurl+'/ajax/modal-ajax', function(data){
           
        if(data==0 || data==null){
            $('#myModal').modal('show');
        }
    });


    //ajax call for update user
    $('.modalclose').click(function(){
        $.get(baseurl+'/ajax/user_first_time_popup', function(data){
           
           
        });
    });


    $('#btOtp').on('click',function(){
        $.get("{{route('send_otp')}}", function( data ) {
            //  alert(data);
        });

    });
    
    $('#btresend').on('click',function(){
        $.get("{{route('resend_otps')}}", function( data ) {
            var result=JSON.parse(data); 
            console.log(result);
            if(result['status']=='success'){
                var errorMs='<div class="my-alert-success alert bg-success base-reverse alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+result['message']+'</div>';
                $("#errormsg").html(errorMs);  
                setTimeout(function(){  $('#errormsg').html('');  }, 6000);
            }else{
                var errorMs='<div class=" my-alert-danger alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+result['message']+'</div>';
                $("#errormsg").html(errorMs); 
              
               setTimeout(function(){ $('#errormsg').html(''); }, 6000); 
            }
        });

    });
    
});
</script>

@endsection