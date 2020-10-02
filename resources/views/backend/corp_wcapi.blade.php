@extends('layouts.frame')
@section('content')
<?php
$countryCode = Helpers::getCountryCodeById($companyCountry);
?>
{!!
Form::open(
array(
'name' => 'corpWcapiForm',
'id' => 'corpWcapiForm',
'url' => route('personal_wcapi_call',['id'=>isset($companyProfile['corp_profile_id']) ? $companyProfile['corp_profile_id'] : '']),
'autocomplete' => 'off','class'=>'loginForm form form-cls'
))
!!}

<div class="row mt-3">
    <div class="col-sm-12">
        <div class="form-group">
            <label for="pwd">Company Name</label> <span class="mandatory">*</span>
                    <input type="text" class="form-control required" placeholder="Company Name" name="corp_name" id="corp_name" value="{{isset($companyName) ? $companyName : ''}}">
                    <span class="text-danger"></span>
                
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label for="pwd">Registered Country</label> <span class="mandatory">*</span>
                   {!!
                    Form::select('country_id',
                    [''=>'Select']+Helpers::getCountryDropDown()->toArray(),
                    (isset($companyCountry) && !empty($companyCountry)) ? $companyCountry : '',
                    array('id' => 'country_id','name' => 'country',
                    'class'=>'form-control select2Cls required'))
                    !!}
                    <span class="text-danger"></span>
                
        </div>
    </div>

    <div class="col-sm-12 text-right">
        <div class="form-group">
            <input type='hidden' name='countrycode' id="countrycode" value='{{$countryCode}}'>
            <input class="btn btn-save btn-sm corpwcapi" name="save" type="submit" value="Submit">
        </div>

    </div>
</div>
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
	.form-group label {
    font-size: 13px;
    color: #743939;
    font-weight: 600;
}
.form-control{
    font-size: 12px;
    font-weight: 500;
    outline: none !important;
    color: #495057;
    padding: 0.275rem 0.75rem;
    line-height: 20px;
    height: auto;
    border: 1px solid #9fa0a0;
}	
.mandatory {
    color: red;
    vertical-align: middle;
    font-size: 13px;
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
;
var messages = {
    is_accept: "{{ Session::get('is_accept') }}",
    corp_detail_similar: "{{ URL::route('corp_detail_similar',['user_kyc_id' => isset($userKycId) ? $userKycId : '']) }}",
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

   
    if (messages.is_accept == 1) {
        var parent = window.parent;
        parent.jQuery("#personalwcapi").modal('hide');
        window.parent.jQuery('#my-loading').css('display', 'block');
        window.parent.location.href = messages.individual_user;
    }


    function generateAuthHeader(dataToSign, APISecret) {
        var hash = CryptoJS.HmacSHA256(dataToSign, APISecret);
        return hash.toString(CryptoJS.enc.Base64);
    }


    $("#country_id").on('change', function () {
        var Country = $('#country_id').val();
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
                 //$('#similarRecords').html(resultData);
             }
         });
    });

    //$(".corpwcapi").on('click', function () {

         $("#corpWcapiForm").on('submit', function(e){
         

        if($("#corpWcapiForm").valid() == false) { return false;}
         e.preventDefault();
        var CorpName    = $("#corp_name").val();
        var CorpCountry = $('#countrycode').val();
        var countryId = $('#country_id').val();
        var get_users_wci_corp = "{{ URL::route('get_users_wci_corp') }}";
        var APISecret   = "{{config('common.APISecret')}}";
        var gatwayurl   = "{{config('common.gatwayurl')}}";
        var contentType = "{{config('common.contentType')}}";
        var gatwayhost  = "{{config('common.gatwayhost')}}";
        var apiKey      = "{{config('common.apiKey')}}";
        var groupId     = "{{Helpers::getgroupId()}}";
        var token       = "{{ csrf_token() }}";
        var user_kyc_id = "{{ isset($userKycId) ? $userKycId : '' }}";
        
        ////////////////
        var entityType  = 'ORGANISATION';
         function generateAuthHeader(dataToSign) {
            var hash = CryptoJS.HmacSHA256(dataToSign, APISecret);
            return hash.toString(CryptoJS.enc.Base64);
        }



       // var date = new Date().toGMTString();
        var date = '<?php echo gmdate('D, d M Y H:i:s \G\M\T', time());?>';
        //var content = '{\n  \"groupId\":\"'+groupId+'\",\n  \"entityType\": \"'+entityType+'\",\n  \"providerTypes\": [\n    \"WATCHLIST\"\n  ],\n  \"name\": \"'+CorpName+'\",\n  \"secondaryFields\":[{\"typeId\": \"SFCT_6\",\"value\":\"'+CorpCountry+'\"}],\n  \"customFields\":[]\n}';

        var content = '{\n  \"groupId\":\"'+groupId+'\",\n  \"entityType\": \"'+entityType+'\",\n  \"providerTypes\": [\n    \"WATCHLIST\"\n  ],\n  \"name\": \"'+CorpName+'\",\n  \"secondaryFields\":[{\"typeId\": \"SFCT_6\",\"value\":\"'+CorpCountry+'\"}],\n  \"customFields\":[]\n}';

        //console.log(content);
        //console.log(content1);

        var contentLength = unescape(encodeURIComponent(content)).length;

        var dataToSign = "(request-target): post " + gatwayurl + "cases/screeningRequest\n" +
                "host: " + gatwayhost + "\n" +
                "date: " + date + "\n" +
                "content-type: " + contentType + "\n" +
                "content-length: " + contentLength + "\n" +content;


        var hmac = generateAuthHeader(dataToSign);
        var authorisation = "Signature keyId=\"" + apiKey + "\",algorithm=\"hmac-sha256\",headers=\"(request-target) host date content-type content-length\",signature=\"" + hmac + "\"";

        var myKeyVals = {_token: token, authorisation: authorisation, currentDate: date, Signature: hmac, ContentLength: contentLength, content: content, user_kyc_id: user_kyc_id,CorpName: CorpName,CorpCountry: countryId};
        console.log(date);
        
            $.ajax({
                type: 'POST',
                url: get_users_wci_corp,
                data: myKeyVals,
                dataType: "text",
                success: function (resultData) {
                     $("#personalwcapi").modal('hide');
                     window.parent.location.href = messages.corp_detail_similar;
                     $('#similarRecords').html(resultData);

                }
            });
      

///////////////

    });
});
document.body.style.backgroundColor = 'transparent';
document.body.style.overflowX  = 'hidden';

</script>

@endsection