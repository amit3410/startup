@extends('layouts.app')

@section('content')


<section>
    <div class="container">
        <div class="row input-margin-cls">
            <div id="header" class="col-md-3">
                @include('layouts.user-inner.left-menu')
            </div>
            <div class="col-md-9 dashbord-white">
                <div class="form-section">
                    <div class="row marB10">
                        <div class="col-md-12">
                            <h3 class="h3-headline">Professional Information</h3>
                        </div>
                    </div>   
                    
                        {!!
                        Form::open(
                        array(
                        'name' => 'professionalInformationForm',
                        'id' => 'professionalInformationForm',
                        'url' => route('professional_information',['id'=>@$userData['user_kyc_prof_id'],'user_kyc_id'=>@$benifinary['user_kyc_id'],'corp_user_id'=>@$benifinary['corp_user_id'],'is_by_company'=>@$benifinary['is_by_company']]),
                        'autocomplete' => 'off','class'=>'loginForm form form-cls'
                        ))
                        !!}
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">

                                    {{ Form::label('prof_status','Professional Status',['class'=>''])}} <span class="mandatory">*<span> 
                                    {!!
                                    Form::select('prof_status',[''=>'Select Status','1'=>'Employed','2'=>'Unemployed','3'=>'Business Owner','4'=>'Self Employed','5'=>'At Home','6'=>'Retired','7'=>'Student','8'=>'Other'],@$userData['prof_status'],['id'=>'prof_status','class'=>'form-control']);
                                    !!}
                                    <span class="text-danger">{{ $errors->first('prof_status') }}</span>
                                </div>
                            </div>

                        </div>


                        <div id="retried_div" style='display: none'>
                       
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">

                                {{Form::label('prof_detail','Previous Profession/Occupation',['class'=>''])}}
                                 <span class="mandatory">*<span> 
                                    {{Form::textarea('prof_detail',@$userData['prof_detail'],['class'=>'form-control','id'=>'prof_detail','rows'=>'3'])}}
                                    <span class="text-danger">{{ $errors->first('prof_detail') }}</span>
                                </div>
                            </div>
                        </div>	

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">

                                    {{Form::label('position_title','Last Position/Job title',['class'=>''])}}
                                    <span class="mandatory">*<span>
                                    {{Form::textarea('position_title',@$userData['position_title'],['class'=>'form-control','id'=>'position_title','rows'=>'3'])}}
                                    <span class="text-danger">{{ $errors->first('position_title') }}</span>
                                </div>
                            </div>
                        </div>			
                        <?php
                            $date_retirement=(@$userData['date_retirement']!='' && @$userData['date_retirement']!=null) ? Helpers::getDateByFormat(@$userData['date_retirement'], 'Y-m-d', 'd/m/Y') :'';
                        ?>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">

                                    {{Form::label('date_employment','Date of retirement',['class'=>''])}}
                                    <span class="mandatory">*<span> 
                                    <div class="input-group">
                                        {{ Form::text('date_retirement',$date_retirement, ['class' => 'form-control datepicker','placeholder'=>'Select Date of Retirement','id' => 'date_retirement']) }}
                                        <div class="input-group-append">
                                            <i class="fa fa-calendar-check-o"></i>
                                        </div>
                                        <span class="text-danger">{{ $errors->first('date_retirement') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                             <div class="form-group inputborder-left">
                              {{Form::label('last_monthly_salary','Last month salary',['class'=>''])}}
                              <span class="mandatory">*<span> 
                              <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                  <span class="input-group-text">{{trans('forms.corp_financial.Label.sym')}}</span>
                                </div>
                                
                                {{Form::text('last_monthly_salary',@$userData['last_monthly_salary'],['class'=>'form-control','id'=>'last_monthly_salary','placeholder'=>'Enter here'])}}
                                <span class="text-danger">{{ $errors->first('last_monthly_salary') }}</span>
                              </div>
                             </div>
                            </div>
                        </div>	

                        </div>

                          <!--employee and self employee show -->

                 <div id="business_self_employee" style='display: none'>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">

                                {{Form::label('prof_occupation','Profession Occupation in detail',['class'=>''])}}
                                 <span class="mandatory">*<span> 
                                    {{Form::textarea('prof_occupation',@$userData['profession_occu'],['class'=>'form-control','id'=>'prof_occupation','rows'=>'3'])}}
                                    <span class="text-danger">{{ $errors->first('prof_occupation') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">

                                {{Form::label('position_job_title','Position/Job title',['class'=>''])}}
                                 <span class="mandatory">*<span> 
                    {{Form::textarea('position_job_title',@$userData['position_job_title'],['class'=>'form-control','id'=>'position_job_title','rows'=>'3'])}}
                                    <span class="text-danger">{{ $errors->first('position_job_title') }}</span>
                                </div>
                            </div>
                        </div>  

                                
                        <?php
                            $date_employment=(@$userData['date_employment']!='' && @$userData['date_employment']!=null) ? Helpers::getDateByFormat(@$userData['date_employment'], 'Y-m-d', 'd/m/Y') :'';
                        if(@$userData['prof_status'] ==3) {
                            $displayStyle= "style=display:none";
                        } else {
                            $displayStyle= "style=display:block";
                        }
                            ?>
                        <div class="row" id="dateofemployment" {{$displayStyle}}>
                           <div class="col-md-4">
                                <div class="form-group">

                                    {{Form::label('date_employment','Date of Employment',['class'=>''])}}
                                    <span class="mandatory">*<span> 
                                    <div class="input-group">
                                        {{ Form::text('date_employment',$date_employment, ['class' => 'form-control datepicker','placeholder'=>'Select Date of Employment','id' => 'date_employment']) }}
                                        <div class="input-group-append">
                                            <i class="fa fa-calendar-check-o"></i>
                                        </div>
                                        <span class="text-danger">{{ $errors->first('date_employment') }}</span>
                                    </div>
                                </div>
                            </div>
                            
                        </div>  

                        </div>


                         <!--Business owner  show -->

                 <div id="business_owner" style='display: none'>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">

                                {{Form::label('prof_occupation','Profession Occupation in detail',['class'=>''])}}
                                 <span class="mandatory">*<span> 
                                    {{Form::textarea('prof_occupation_business',@$userData['profession_occu'],['class'=>'form-control','id'=>'','rows'=>'3'])}}
                                    <span class="text-danger">{{ $errors->first('prof_occupation') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">

                                {{Form::label('position_job_title','Position/Job title',['class'=>''])}}
                                 <span class="mandatory">*<span> 
                    {{Form::textarea('position_job_title_business',@$userData['position_job_title'],['class'=>'form-control','id'=>'','rows'=>'3'])}}
                                    <span class="text-danger">{{ $errors->first('position_job_title') }}</span>
                                </div>
                            </div>
                        </div>  

                          </div>
                    <!--Business owner end code-->

                    <div id="other_status" style='display: none'>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">

                                {{Form::label('other_prof_status','Other professional status',['class'=>''])}}
                                 <span class="mandatory">*<span> 
            {{Form::textarea('other_prof_status',@$userData['other_prof_status'],['class'=>'form-control','rows'=>'3'])}}
                                    <span class="text-danger">{{ $errors->first('other_prof_status') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">

                            {{ Form::label('prof_status','Additional Activities',['class'=>''])}}
                    <input type="text" class="form-control" name="additional_activity" id="additional_activity" placeholder="Enter Additional Activities" value="{{isset($userData['additional_activities'])?$userData['additional_activities']:''}}">
                                    <span class="text-danger"> </span>
                            </div>
                        </div>
                    </div> 

                        <div class="row marT60">
                            <div class="col-md-12 text-right">
                            <a href="{{$benifinary['prev_url']}}" class="btn btn-prev pull-left">Previous</a>          
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

<!--models-->
<div class="modal model-popup" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <h4 class="headline-h4 marB15">Dear Applicant;</h4>
                <p>Welcome to the Compliance platform of Dexter Capital Financial Consultancy LLC. </p>

                <p> According to the United Arab Emirates rules and regulations and the International applicable laws, you are kindly requested to proceed with the due diligence application allowing you to validate your profile and access many financial platforms.</p>
                <p> Dexter Capital Financial Consultancy LLC being regulated by Securities and Commodities Authority in the UAE, is committed to maintain all your information confidential and highly protected by the most sophisticated security tools and is in full compliance with the requirements of the European Union related to the General Data Protection Regulation (GDPR). <a href="https://ec.europa.eu/info/law/law-topic/data-protection/data-protection-eu_en" target="_blank"> https://ec.europa.eu/info/law/law-topic/data-protection/data-protection-eu_en</a></p>
            </div>

        </div>
    </div>
</div>
@endsection

@section('pageTitle')
Professional Information
@endsection

@section('additional_css')
<link href="{{ asset('frontend/inside/plugin/datepicker/jquery-ui.css') }}" rel="stylesheet">
@endsection
@section('jscript')


    
<script src="{{ asset('frontend/inside/plugin/datepicker/jquery-ui.js') }}"></script>
<script src="{{ asset('frontend/outside/js/validation/professionalInfoForm.js')}}"></script>
<script>
$(document).ready(function () {
    var disabledAll='{{$kycApproveStatus}}';
    if(disabledAll!=0){
        $("#professionalInformationForm :input"). prop("disabled", true); 
    }
   var date = $('.datepicker').datepicker({dateFormat: 'dd/mm/yy', maxDate: new Date(), changeMonth: true, changeYear: true});

        $('.datepicker').keydown(function (e) {
            e.preventDefault();
            return false;
        });

        $('.datepicker').on('paste', function (e) {
            e.preventDefault();
            return false;
        });


});
//
    if($("#prof_status option:selected").text()=='Other'){
        $('#otherProfStatus').show();

    }else{
        $('#otherProfStatus').hide();
    }
        
        $("#prof_status").on('change',function(){
            var prof1    =   ['1','3','4'];
            var prof2    =   ['1','3','4','6'];
            if($("#prof_status option:selected").text()=='Other'){

                $('#otherProfStatus').show();
            }else{
                $('#otherProfStatus').hide();
            }
            var selVal  =   $("#prof_status option:selected").val();
            if($.inArray(selVal,prof1)){

            }

        });


</script>
@endsection