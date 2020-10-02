@extends('layouts.frame')

@section('content')

<?php
if (isset($userPersonalData['date_of_birth'])) {
    if (isset($userSignupdata->date_of_birth)) {
        $userDOB = $userSignupdata->date_of_birth;
        $userDOB = Helpers::getDateByFormat($userDOB, 'Y-m-d', 'Y-m-d');
    } else {
        $userDOB = $userPersonalData['date_of_birth'];
        $userDOB = Helpers::getDateByFormat($userDOB, 'Y-m-d', 'Y-m-d');
    }
}

if (isset($userPersonalData['birth_country_id'])) {
    $countryId = $userPersonalData['birth_country_id'];
}


$countryCode = Helpers::getCountryCodeById($countryId);
?>
{!!
Form::open(
array(
'name' => 'personalWcapiForm',
'id' => 'personalWcapiForm',
'url' => '',
'autocomplete' => 'off','class'=>'loginForm form form-cls'
))
!!}

<div class="row">


    <div id="my-loading" style="display:none">
            <div class="square-blocks">
                <img src="{{ asset('frontend/inside/images/ajax-loader.gif') }}" alt="Loading...">
            </div>
        </div>
    
    <div class="col-sm-6">
        <div class="form-group">
            <label for="pwd">First Name </label> <span class="mandatory">*</span>
                    <input type="text" class="form-control required" placeholder="First Name" name="f_name" id="f_name" value="{{isset($userPersonalData->f_name) ? $userPersonalData->f_name : ''}}">
                    <span class="text-danger"></span>
                </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="pwd">Middle Name </label>
                    <input type="text" class="form-control" placeholder="Middle Name" name="m_name" id="m_name" value="{{isset($userPersonalData->m_name) ? $userPersonalData->m_name : ''}}">
                    <span class="text-danger"></span>

                </div>
    </div>


    <div class="col-sm-6">
        <div class="form-group">
            <label for="pwd">Last Name </label>
                    <input type="text" class="form-control" id="l_name" placeholder="Last Name" name="l_name" value="{{isset($userPersonalData->l_name) ? $userPersonalData->l_name : ''}}">
                    <span class="text-danger"></span>
               </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="pwd">Date of Birth </label> <span class="mandatory">*</span>
                    {{ Form::text('date_of_birth',$userDOB, ['class' => 'form-control datepicker','placeholder'=>trans('forms.personal_Profile.Label.date_of_birth'),'id' => 'date_of_birth']) }}
                    <span class="text-danger"></span>
                </div>
    </div>



    <div class="col-sm-6">
        <div class="form-group">
            <label for="pwd" class="d-block">Gender </label>
            </div>
        <div class="form-check-inline">
          <label class="form-check-label">
             <input type="radio" class="form-check-input v-align-sub" id="gender" name="gender" value="MALE" {{isset($userPersonalData->gender) ? $userPersonalData->gender == 'M' ? 'checked' :'' : ''}}> Male
                <input type="radio" class="form-check-input v-align-sub" id="gender" name="gender" value="FEMALE" {{isset($userPersonalData->gender) ? $userPersonalData->gender == 'F' ? 'checked' :'' : ''}}> Female
          </label>
          <span class="text-danger"></span>
         </div>
    </div>
    
    <div class="col-sm-6">
        <div class="form-group">
            <label for="pwd">Country</label> <span class="mandatory">*</span>
                    {!!
                    Form::select('birth_country_id',
                    [''=>'Select']+Helpers::getCountryDropDown()->toArray(),
                    $countryId,
                    array('id' => 'birth_country',
                    'class'=>'form-control select2Cls required'))
                    !!}
                    <span class="text-danger"></span>
                </div>
    </div>

    <div class="col-sm-6">
        <div class="form-group d-flex mb-2 mt-3">
            <input type="checkbox" name="passportcheck" id="passportcheck" value="1" class="passportcheck mr-2">
            <label for="pwd" class="d-block">Passport-Check</label>
            </div>
    </div>

    <div class="col-sm-6 text-right" id="submitone">
        <div class="form-group">

            <input type='hidden' name='countrycode' id="countrycode" value='{{$countryCode}}'>
            <input type='hidden' name='issuecountrycode' id="issuecountrycode" value='{{$countryCode}}'>
            <input type='hidden' name='nationality' id="nationality" value='{{$countryCode}}'>
            
            <input class="btn btn-save btn-sm perwcapi" name="save" type="submit" value="Submit">
        </div>

    </div>
    
</div>

<!-- Passport check form Start-->
    <div id="passportform" style="display:none;">
        <div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="pwd">Issuing State </label> <span class="mandatory">*</span>

            {!!
                    Form::select('passport_issue_state',
                    [''=>'Select']+Helpers::getCountryDropDown()->toArray(),
                    $countryId,
                    array('id' => 'passport_issue_state',
                    'class'=>'form-control select2Cls required'))
                    !!}

            <span class="text-danger"></span>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
        <label for="pwd">Nationality </label> <span class="mandatory">*</span>
        {!!
            Form::select('passport_nationality',
            [''=>'Select']+Helpers::getCountryDropDown()->toArray(),
            $countryId,
            array('id' => 'passport_nationality',
            'class'=>'form-control select2Cls required'))
        !!}
        <span class="text-danger"></span>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
        <label for="pwd">Passport Number </label> <span class="mandatory">*</span>
        <input type="text" class="form-control" placeholder="Passport Number" name="passportNumber" id="passportNumber" value="{{isset($passportNumber) ? $passportNumber : ''}}">
        <span class="text-danger"></span>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
        <label for="pwd">Passport Expiry Date</label> <span class="mandatory">*</span>
        {{ Form::text('date_of_expiry',(isset($userPassportExp) ? $userPassportExp : ''), ['class' => 'form-control datepicker','placeholder'=>trans('forms.personal_Profile.Label.date_of_expiry'),'id' => 'date_of_expiry']) }}
        <span class="text-danger"></span>
        </div>
    </div>

    <div class="col-sm-12 text-right" id="submitboth">
        <div class="form-group">
            <input class="btn btn-save btn-sm perwcapi" name="save" type="submit" value="Submit">
        </div>
    </div>
        </div>
    </div>


    <!-- Passport check form End-->


{{ Form::close() }}


<style>
    .btn.btn-save {
        color: #743939;
        background: #F7CA5A;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
        padding: 7px 30px 7px 30px;
        margin: 10px 5px;
        font-size: 14px;
        font-weight: 700;
        text-transform: capitalize;
    }
    .v-align-sub{vertical-align: sub}
    iframe body{background-color: #fff;}
    .mandatory {
    font-size: 12px;
    color: red;
}
</style>



@endsection
@section('pageTitle')
User Detail
@endsection



@section('jscript')

<script type="text/javascript" src="{{ asset('frontend/outside/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('frontend/inside/plugin/datepicker/jquery-ui.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>
<script src="{{ asset('backend/js/individual_api.js') }}"></script>

<script>
document.body.style.background = "transparent";
var messages = {
    is_accept: "{{ Session::get('is_accept') }}",
    individual_user: "{{ URL::route('user_detail_similar',['user_kyc_id' => $userPersonalData->user_kyc_id]) }}",
    APISecret   : "{{config('common.APISecret')}}",
    gatwayurl   : "{{config('common.gatwayurl')}}",
    contentType : "{{config('common.contentType')}}",
    gatwayhost  : "{{config('common.gatwayhost')}}",
    apiKey      : "{{config('common.apiKey')}}",
    token       : "{{ csrf_token() }}",
    get_groupID : "{{ URL::route('get_groupID') }}",



};

</script>
<script src="{{ asset('common/js/common.js') }}"></script>
<script>
$(document).ready(function () {
   
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });



    $('.passportcheck').click(function(){
        if($(this).is(':checked')){
           
           $('#passportform').show();
           $('#submitone').hide();
          
        } else {
           $('#passportform').hide();
           $('#submitone').show();
           
        }
    });

    $('#date_of_birth').datepicker({dateFormat: 'yy-mm-dd', maxDate: new Date(), changeMonth: true, changeYear: true});
    $('#date_of_expiry').datepicker({dateFormat: 'yy-mm-dd', changeMonth: true, changeYear: true});

    if (messages.is_accept == 1) {
        var parent = window.parent;
        parent.jQuery("#personalwcapi").modal('hide');
        window.parent.jQuery('#my-loading').css('display', 'block');
        window.parent.location.href = messages.individual_user;
    }


    

    $("#birth_country").on('change', function () {
        var Country = $('#birth_country').val();
        var token = "{{ csrf_token() }}";
        var myKeyVals = {_token: token, country_id: Country};
        var get_country_code = "{{ URL::route('get_country_code') }}";
        $.ajax({
             type: 'POST',
             url: get_country_code,
             data: myKeyVals,
             dataType: "text",
             success: function (resultData) {
                     $('#countrycode').val(resultData.trim());
             }
         });
    });

    $("#passport_issue_state").on('change', function () {
        var Country = $('#passport_issue_state').val();
        var token = "{{ csrf_token() }}";
        var myKeyVals = {_token: token, country_id: Country};
        var get_country_code = "{{ URL::route('get_country_code') }}";
        $.ajax({
             type: 'POST',
             url: get_country_code,
             data: myKeyVals,
             dataType: "text",
             success: function (resultData) {
                     $('#issuecountrycode').val(resultData.trim());
             }
         });
    });




    $("#passport_nationality").on('change', function () {
        var Country = $('#passport_nationality').val();
        var token = "{{ csrf_token() }}";
        var myKeyVals = {_token: token, country_id: Country};
        var get_country_code = "{{ URL::route('get_country_code') }}";
        $.ajax({
             type: 'POST',
             url: get_country_code,
             data: myKeyVals,
             dataType: "text",
             success: function (resultData) {
                     $('#nationality').val(resultData.trim());
             }
         });
    });




    $("#personalWcapiForm").on('submit', function(e){
        
     // $("#personalWcapiForm").valid();
    //   checkgroupId();
       //return false;
     
   if($("#personalWcapiForm").valid() == false) { return false;}
    e.preventDefault();

   // $(".perwcapi").on('click', function () {
        var f_name = $("#f_name").val();
        var m_name = $("#m_name").val();
        var l_name = $("#l_name").val();
        var gender = $('input[name=gender]:checked').val();
        var countryId = $('#birth_country').val();
        var countryCode = $('#countrycode').val();
        var entityName = f_name + ' ' + m_name + ' ' + l_name;
        var date_of_birth = $("#date_of_birth").val();
        var entityDOB = date_of_birth;

        var passportcheck = $('input[name=passportcheck]:checked').val();
        if(passportcheck == 1) {
            var passportNumber          = $("#passportNumber").val();
            var date_of_expiry          = $("#date_of_expiry").val(); //(yyyy-mm-dd) format
            var passport_issue_state    = $("#issuecountrycode").val();
            var passport_nationality    = $("#nationality").val();
            var PASSPORT = 'PASSPORT';
            
        }




        var get_users_wci_list = "{{ URL::route('get_users_wci_list') }}";
        var APISecret = "{{config('common.APISecret')}}";
        var gatwayurl = "{{config('common.gatwayurl')}}";
        var contentType = "{{config('common.contentType')}}";
        var gatwayhost = "{{config('common.gatwayhost')}}";
        var apiKey = "{{config('common.apiKey')}}";
        var groupId = "{{Helpers::getgroupId()}}";
        var entityType = 'INDIVIDUAL';
        var token = "{{ csrf_token() }}";
        var user_kyc_id = "{{ $userPersonalData->user_kyc_id }}";
      //  alert(groupId); return false;
        
////////////////

      //  var date = new Date().toGMTString();
        var date = '<?php echo gmdate('D, d M Y H:i:s \G\M\T', time());?>';
        // console.log("==>"+date);

        // return false;
        //var content1 = '{\n  \"groupId\":\"0a3687cf-68e5-171f-9a3a-1654000000d5\",\n  \"entityType\": \"INDIVIDUAL\",\n  \"providerTypes\": [\n    \"WATCHLIST\"\n  ],\n  \"name\": \"putin\",\n  \"secondaryFields\":[{\"typeId\": \"SFCT_2\",\"dateTimeValue\":\"1952-07-10\"}],\n  \"customFields\":[]\n}';
        if(passportcheck == 1) {
            var content = '{\n  \"groupId\":\"' + groupId + '\",\n  \"entityType\": \"' + entityType + '\",\n  \"providerTypes\": [\n    \"WATCHLIST\", \"PASSPORT_CHECK\"\n  ],\n  \"name\": \"' + entityName + '\",\n  \"secondaryFields\":[{\"typeId\": \"SFCT_1\",\"value\":\"' + gender + '\"},{\"typeId\": \"SFCT_2\",\"dateTimeValue\":\"' + entityDOB + '\"},{\"typeId\": \"SFCT_4\",\"value\":\"' + countryCode + '\"},{\"typeId\": \"SFCT_8\",\"value\":\"' + f_name + '\"},{\"typeId\": \"SFCT_9\",\"value\":\"' + l_name + '\"},{\"typeId\": \"SFCT_11\",\"value\":\"' + passport_issue_state + '\"},{\"typeId\": \"SFCT_12\",\"value\":\"' + countryCode + '\"},{\"typeId\": \"SFCT_14\",\"value\":\"' + PASSPORT + '\"},{\"typeId\": \"SFCT_15\",\"value\":\"' + passportNumber + '\"},{\"typeId\": \"SFCT_16\",\"dateTimeValue\":\"' + date_of_expiry + '\"}],\n  \"customFields\":[]\n}';
            var is_passport = '1';
        } else {
            var content = '{\n  \"groupId\":\"' + groupId + '\",\n  \"entityType\": \"' + entityType + '\",\n  \"providerTypes\": [\n    \"WATCHLIST\"\n  ],\n  \"name\": \"' + entityName + '\",\n  \"secondaryFields\":[{\"typeId\": \"SFCT_1\",\"value\":\"' + gender + '\"},{\"typeId\": \"SFCT_2\",\"dateTimeValue\":\"' + entityDOB + '\"},{\"typeId\": \"SFCT_4\",\"value\":\"' + countryCode + '\"}],\n  \"customFields\":[]\n}';
            var is_passport = '0';
        }
       // date.getMinutes
            //console.log(content1);
        var contentLength = unescape(encodeURIComponent(content)).length;
        var dataToSign = "(request-target): post " + gatwayurl + "cases/screeningRequest\n" +
                "host: " + gatwayhost + "\n" +
                "date: " + date + "\n" +
                "content-type: " + contentType + "\n" +
                "content-length: " + contentLength + "\n" + content;
        var hmac = generateAuthHeader(dataToSign, APISecret);
        var authorisation = "Signature keyId=\"" + apiKey + "\",algorithm=\"hmac-sha256\",headers=\"(request-target) host date content-type content-length\",signature=\"" + hmac + "\"";
        var myKeyVals = {_token: token, authorisation: authorisation, currentDate: date, Signature: hmac, ContentLength: contentLength, content: content, user_kyc_id: user_kyc_id,f_name:f_name,m_name:m_name,l_name:l_name,gender:gender,countryCode:countryId,date_of_birth:entityDOB,is_passport:is_passport};
        console.log(date);
        console.log(content);
        console.log(authorisation);

        $.ajax({
            type: 'POST',
            url: get_users_wci_list,
            data: myKeyVals,
            dataType: "text",
            beforeSend: function() {
               $('#my-loading').css('display', 'block');
            },
            success: function (resultData) {
               // console.log(resultData); return false;
                if (resultData.trim() == "Success") {
                    $("#personalwcapi").modal('hide');
                    window.parent.location.href = messages.individual_user;
                }
            }
        });

///////////////

    });




    
});




</script>

@endsection