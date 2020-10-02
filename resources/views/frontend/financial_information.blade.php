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
<h3 class="h3-headline">Financial Information</h3>
</div>
</div>   
{!!
Form::open(
array(
'name' => 'financialInformationForm',
'id' => 'financialInformationForm',
'url' => route('financial_information',['id'=>@$userData['user_financial_info_id'],'user_kyc_id'=>@$benifinary['user_kyc_id'],'corp_user_id'=>@$benifinary['corp_user_id'],'is_by_company'=>@$benifinary['is_by_company']]),
'autocomplete' => 'off','class'=>'loginForm form form-cls'
))
!!}


<div class="row">


<div class="col-md-12">
<div class="form-group form-select-field custom-validation">
@php
$arrarVal=[];
$sourceDropVal=Helpers::getFundSourceDropDown();

if(isset($userData['source_funds'])) {
$arrarVal = explode(',',$userData['source_funds']);
}
else {
$arrarVal=[];

}
@endphp
{{Form::label('source_funds','Source of Funds',['class'=>''])}} <span class="mandatory"> *<span> 
<select multiple="multiple" name="source_funds[]" id="source_funds" class='form-control multiselect_dropdown'>
@foreach($sourceDropVal as $Dval)

@if(in_array($Dval, $arrarVal))

<option value="{{$Dval}}" selected="">{{$Dval}}</option>
@else
<option value="{{$Dval}}">{{$Dval}}</option>

@endif     
@endforeach    
</select>
<span class="text-danger">{{ $errors->first('source_funds') }}</span>
</div>
</div>

</div>
<div class="row" id="otherSource">
<div class="col-md-12">
<div class="form-group">

{{ Form::label('other_source','Other Source Of Funds',['class'=>''])}}<span class="mandatory">*<span> 
{{ Form::text('other_source',@$userData['other_source'],['class'=>'form-control','id'=>'other_source','placeholder'=>'Other Source Of Funds'])}}
<span class="text-danger">{{ $errors->first('other_source') }}</span>   
</div>

</div>
</div>

<div class="row">
<div class="col-md-12">
<div class="form-group">
<div class="form-group form-select-field custom-validation">
@php
$arraVal=[];
$countryVal=Helpers::getCountryDropDown()->toArray();
if(isset($userData['jurisdiction_funds'])) {
$arraVal = explode(',',$userData['jurisdiction_funds']);

}
else {
$arraVal=[];

}
@endphp

{{ Form::label('jurisdiction_funds','Jurisdiction of Funds',['class'=>''])}} <span class="mandatory">*<span> 
<select multiple="multiple" name="jurisdiction_funds[]" id="jurisdiction_funds" class='form-control multiselect_dropdown'>
@foreach($countryVal as $key=>$Jval)

@if(in_array($key, $arraVal))

<option value="{{$key}}" selected="">{{$Jval}}</option>
@else
<option value="{{$key}}">{{$Jval}}</option>

@endif     
@endforeach    
</select>    
<span class="text-danger">{{ $errors->first('jurisdiction_funds') }}</span>   
</div>
</div>
</div>
</div>  

<div class="row">
<div class="col-md-12">
<div class="form-group">

{{ Form::label('annual_income','Annual Income (in USD)',['class'=>''])}} <span class="mandatory">*<span> 
{!!Form::select('annual_income',
[''=>'Select']+Helpers::getAnnualIncomeDropDown(),@$userData['annual_income'],array('id' => 'annual_income','class'=>'form-control select2Cls'))
!!}
<span class="text-danger">{{ $errors->first('annual_income') }}</span>
</div>
</div>
</div>   

<div class="row">
<div class="col-md-12">
<div class="form-group">


{{ Form::label('estimated_wealth','Estimated Wealth (in USD)',['class'=>''])}} <span class="mandatory">*<span> 


{!!Form::select('estimated_wealth',
[''=>'Select']+Helpers::getEstimatedWealthDropDown(),@$userData['estimated_wealth'],array('id' => 'estimated_wealth','class'=>'form-control select2Cls'))
!!}
<span class="text-danger">{{ $errors->first('estimated_wealth') }}</span>
</div>
</div>
</div>   
<div class="row">
<div class="col-md-12">
<div class="form-group form-select-field custom-validation">
@php

$wealthSource=Helpers::getWealthSourceDropDown();

if(isset($userData['wealth_source'])) {
$selectedVal=explode(',',$userData['wealth_source']);
} else {
$selectedVal=[];
}


@endphp

{{ Form::label('wealth_source','Kindly provide details on the source(s) of your wealth',['class'=>''])}} <span class="mandatory">*<span> 

<select multiple="multiple" name="wealth_source[]" id="wealth_source" class='form-control multiselect_dropdown'>
@foreach($wealthSource as $welthsource)

@if(in_array($welthsource, $selectedVal))

<option value="{{$welthsource}}" selected="">{{$welthsource}}</option>
@else
<option value="{{$welthsource}}">{{$welthsource}}</option>

@endif     
@endforeach    
</select>
<span class="text-danger">{{ $errors->first('wealth_source') }}</span>
</div>
</div>
</div>



<div class="row" id="otherSourcewealth">
    <div class="col-md-12">
        <div class="form-group">

{{ Form::label('other_wealth_source','Other source(s) of your wealth',['class'=>''])}} <span class="mandatory">*<span> 
        {{ Form::text('other_wealth_source',@$userData['other_wealth_source'],['class'=>'form-control','id'=>'other_wealth_source','placeholder'=>'Other source(s) of your wealth'])}}
        <span class="text-danger">{{ $errors->first('other_wealth_source') }}</span>   
        </div>

        </div>
        </div> 

        <div class="row">
            <div class="col-md-12">
                <hr/>
            </div>
        </div>     

        <div class="row marT10 marB20">
            <div class="col-md-12">
                <label for="pwd"><b>Please fill the following details (If Applicable)</b></label>
            </div>  
        </div> 


        <div class="row">
            <div class="col-md-12"> 
                {{ Form::label('Subject_to_US_FATCA','Subject to US FATCA regulation',['class'=>''])}} <span class="mandatory">*<span>
                        {{Form::select('us_fetch_regulation',
[''=>'Select','1'=>'Yes','0'=>'No'],@$userData['us_fetch_regulation'],array('id' =>'us_fetch_regulation','class'=>'form-control select2Cls'))
                        }}
                        <span class="text-danger">{{ $errors->first('tin_code') }}</span>
                        </div> 
                        </div>  
                        <br>
                        <div class="row" style="display: none" id="subject_to_us_yes">
                            <div class="col-md-6">
                                <div class="form-group">

                                    {{ Form::label('please_specify','Please Specify',['class'=>''])}} <span class="mandatory">*<span>

                                            {{Form::select('please_specify',
[''=>'Select']+Helpers::UsfetchYesDropDown(),@$userData['please_specify'],array('id' =>'please_specify','class'=>'form-control select2Cls'))
                                            }}
                                            <span class="text-danger">{{ $errors->first('tin_code') }}</span>
                                            </div>
                                            </div>

                                            <div class="col-md-6">
                                            <div class="form-group">

                                            {{ Form::label('tin_code','US TIN Code',['class'=>''])}} <span class="mandatory">*<span>

                                            {{ Form::text('tin_code',@$userData['tin_code'],['class'=>'form-control','id'=>'tin_code','placeholder'=>'Enter US TIN Code','maxlength'=>'20'])}}
                                            <span class="text-danger">{{ $errors->first('tin_code') }}</span>
                                            </div>
                                            </div>
                                            </div>



                                            <div  style='display: none;'  id="subject_to_us_No">

                                            <div class="row country-inner-box">
                                            <div class="col-md-6 country-inner">
                                            <div class="form-group">
                                            {{ Form::label('tin_country_name','TIN Country ',['class'=>''])}} <span class="mandatory">*<span> 


                                            {!!
                                            Form::select('tin_country_name',
                                            [''=>'Select','400'=>'Not Applicable']+Helpers::getCountryDropDown()->toArray(),@$userData['tin_country_name'],array('id' => 'tin_country_name','class'=>'form-control select2Cls'))
                                            !!}
                                            <span class="text-danger">{{ $errors->first('tin_country_name') }}</span>
                                            </div>

                                            </div>
                                            <div class="col-md-3 country-inner">
                                            <div class="form-group mandatory-group">

                                            {{ Form::label('tin_number','TIN (Taxpayer Identification Number) or functional equivalent of the TIN',['class'=>''])}}<span class="mandatory">*<span> 
                                            {{ Form::text('tin_number',@$userData['tin_number'],['class'=>'form-control tin_number','id'=>'tin_number','placeholder'=>'Enter TIN no.','maxlength'=>'20'])}}
                                            <span class="text-danger">{{ $errors->first('tin_number') }}</span>
                                            </div>
                                            </div>

                                  <div class="col-md-3 country-inner">
                                    <div class="form-group">

                                    {{ Form::label('Not applicable','Not Applicable',['class'=>''])}}
                                    <span class="mandatory" style="vertical-align: -webkit-baseline-middle;">  <span> 
                                    @if(isset($userData['not_applicable']) && $userData['not_applicable'] == 1)
                                    <input type="checkbox" name="not_applicable_tincode" id="not_applicable_tincode"  value="1" checked="">
                                    @else
                                    <input type="checkbox" name="not_applicable_tincode" id="not_applicable_tincode"  value="0">
                                    @endif
                                    <span class="text-danger">{{ $errors->first('not_applicable_checkbox') }}</span>
                                    </div>
                                  </div>

                                            </div>  
                                            <div class="row">
                                            <div class="col-md-12">


                                            {{Form::label('is_abandoned','Was US citizenship abandoned after June 2014?')}} <span class="mandatory">*<span> 
                                            {!!Form::select('is_abandoned',
                                            [''=>'Select','1'=>'Yes','0'=>'No'],@$userData['is_abandoned'],array('id' => 'is_abandoned','class'=>'form-control select2Cls'))
                                            !!}
                                            <span class="text-danger">{{ $errors->first('is_abandoned') }}</span>

                                            </div>
                                            </div>
                                            </div>  


                                                                                                                                                <?php
                                                                                                                                                $date_of_abandonment = (@$userData['date_of_abandonment'] != '' && @$userData['date_of_abandonment'] != null) ? Helpers::getDateByFormat(@$userData['date_of_abandonment'], 'Y-m-d', 'd/m/Y') : '';
                                                                                                                                                ?>
<div class="row abandonment mt-3" id="citizenship_abandonment" style='display: none'>         
<div class="col-md-6">
<div class="form-group">
<label for="pwd"></label>
{{ Form::label('date_of_abandonment','Please specify date of abandonment',['class'=>''])}} <span class="mandatory">*<span> 
<div class="input-group">
{{ Form::text('date_of_abandonment',$date_of_abandonment,['class'=>'form-control datepicker','id'=>'date_of_abandonment','placeholder'=>''])}}
<div class="input-group-append">
<i class="fa fa-calendar-check-o"></i>
</div>

</div>
<span class="text-danger">{{ $errors->first('date_of_abandonment') }}</span>

</div>
</div>
<div class="col-md-6">
<div class="form-group">
{{ Form::label('abandonment_reason','Reason',['class'=>''])}} <span class="mandatory">*<span> 

{{ Form::text('abandonment_reason',@$userData['abandonment_reason'],['class'=>'form-control','id'=>'abandonment_reason','placeholder'=>'Enter Reason','maxlength'=>'50'])}}
<span class="text-danger">{{ $errors->first('abandonment_reason') }}</span>
</div>

</div> 
</div>



<div class="row marT60">
<div class="col-md-12 text-right">
<a href="{{$benifinary['prev_url']}}" class="btn btn-prev pull-left">Previous</a>                                                                                                                                                                              @if($kycApproveStatus==0)
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



                                                                                                                                                                                                        @endsection
                                                                                                                                                                                                        @section('pageTitle')
Financial Information
@endsection
@section('additional_css')
<link href="{{ asset('frontend/inside/plugin/datepicker/jquery-ui.css') }}" rel="stylesheet">

<link href="{{ asset('frontend/multiselect/jquery.multiselect.css')}}" rel="stylesheet" />

@endsection
@section('jscript')
<script src="{{ asset('frontend/multiselect/jquery.multiselect.js') }}"></script>
<script src="{{ asset('frontend/inside/plugin/datepicker/jquery-ui.js') }}"></script>
<script src="{{ asset('frontend/outside/js/validation/indivisualFinancial.js')}}"></script>
<script>
$(document).ready(function () {
    var disabledAll = '{{$kycApproveStatus}}';
    if (disabledAll != 0) {
        $("#financialInformationForm :input").prop("disabled", true);
    }

    $(function () {
        $('.multiselect_dropdown').multiselect({
            placeholder: 'Select',
            search: true,
            searchOptions: {
                'default': 'Search'
            },
            selectAll: true
        });


    });

    $('.datepicker').datepicker({dateFormat: 'dd/mm/yy', maxDate: new Date(), changeMonth: true, changeYear: true});

    if ($("#source_funds option:selected").text() == 'Other') {
        $('#otherSource').show();
    } else {
        $('#otherSource').hide();
    }
    $("#source_funds").on('change', function () {
        if ($("#source_funds option:selected").text() == 'Other') {
            $('#otherSource').show();
        } else {
            $('#otherSource').hide();
        }
    });

    //Jurisdiction of Funds 
    if ($("#jurisdiction_funds option:selected").text() == 'Other') {
        $('#jurisdiction_funds').show();
    } else {
        $('#jurisdiction_funds').hide();
    }
    $("#jurisdiction_funds").on('change', function () {
        if ($("#jurisdiction_funds option:selected").text() == 'Other') {
            $('#jurisdiction_funds').show();
        } else {
            $('#jurisdiction_funds').hide();
        }
    });



    if ($("#wealth_source option:selected").text() == 'Other') {
        $('#otherSourcewealth').show();
    } else {
        $('#otherSourcewealth').hide();
    }
    $("#wealth_source").on('change', function () {
        if ($("#wealth_source option:selected").text() == 'Other') {
            $('#otherSourcewealth').show();
        } else {
            $('#otherSourcewealth').hide();
        }
    });

});


                                                                                                                                                                                                        </script>
                                                                                                                                                                                                        @endsection


