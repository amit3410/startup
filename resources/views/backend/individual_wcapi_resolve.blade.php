@extends('layouts.frame')

@section('content')


{!!
Form::open(
array(
'name' => 'personalResolveForm',
'id' => 'personalResolveForm',
'url' => route('individual_api_resolve',['id'=>$userKycId,'profileId' => $profileId,'responceProfileId'=>$responceProfileId]),
'autocomplete' => 'off','class'=>'loginForm form form-cls'
))
!!}
<?php



  if($userData && $userType == 1){
    if($is_by_company == 1) {
      $route=route("user_detail_similar",['user_id' => $userData,'user_type' => $userType,'user_kyc_id'=>$userKycId,'is_by_company' => '1','tab' => 'tab06']);
    } else {
      $route=route("user_detail_similar",['user_id' => $userData,'user_type' => $userType,'user_kyc_id'=>$userKycId,'is_by_company' => '0','tab' => 'tab06']);
    }

  } else if($userData && $userType == 2){
    $route=route("user_detail_similar",['user_id' => $userData,'user_type' => $userType,'user_kyc_id'=>$userKycId,'tab' => 'tab06']);
  }
?>

<div class="row">
   
    
    
    <div class="col-sm-12 text-center">
       <table width="100%">
                <tr>
                    <td colspan="3" style="color:#743939">Resolution</td>
                </tr>
                <tr>
                   <td style="color:#743939">STATUS<span class="mandatory">*</span></td>
                    <td>
                        <select class="form-control" name="resolutionStatus" id="resolutionStatus">
                        <option value="">Select Status</option>
                        <?php
                        foreach($StatusArray as $status) {
                        ?>
                        <option value="{{$status->id}}">{{$status->type}}</option>
                        <?php } ?>
                         </select>
                    </td>

               </tr>
                <tr>
                    <td style="color:#743939"> RISK LEVEL <span class="mandatory">*</span></td>
                    <td ><select class="form-control" name="resolutionRisk" id="resolutionRisk">
                           <option value="">Select Risk Level</option>
                               <?php
                               foreach($RiskArray as $Risk) {
                               ?>
                               <option value="{{$Risk->id}}" class="resolutionRisk" disabled>{{$Risk->type}}</option>
                               <?php } ?>
                       </select></td>

               </tr>
                <tr>
                   <td style="color:#743939">REASON <span class="mandatory">*</span></td>
                   <td >
                       <select name="resolutionReason" class="form-control" id="resolutionReason">
                           <option value="">Select Reason</option>
                           <?php
                               foreach($ReasonArray as $Reason) {
                               ?>
                               <option value="{{$Reason->id}}" class="resolutionReason" disabled>{{$Reason->type}}</option>
                               <?php } ?>

                       </select></td>

               </tr>
               <tr>
                   <td >&nbsp;</td>
                   <td >
                       <textarea class="form-control" name="resolutionReasoncomment" id="resolutionReasoncomment" placeholder="Reason Description"></textarea></td>
               </tr>
                </table>
                <input type="hidden" value="1" name="is_save">
                <input type="hidden" value="" name="statuskeyValue" id="statuskeyValue">
                <input type="hidden" value="" name="riskkeyvalue" id="riskkeyvalue">
                <input type="hidden" value="" name="reasonkeyalue" id="reasonkeyalue">
                <input type="hidden" value="{{$result_id}}" name="result_id" id="result_id">
                <input type="hidden" value="{{$case_id}}" name="case_id" id="case_id">
                <input type="hidden" value="{{$caseSystemId}}" name="case_system_id" id="case_system_id">
                <input type="hidden" value="{{$userKycId}}" name="userKycId" id="userKycId">
                

                <input type="hidden" name="primaryId" id="primaryId" value="{{$wcapi_req_res_id}}">
                <input type="hidden" name="responceProfileId" id="responceProfileId" value="{{$responceProfileId}}">
                <input class="btn btn-save btn-sm resolvesubmit" name="saveyes" type="submit" value="Save">
                <input class="btn btn-default btn-sm resolvecancel" name="save" type="button" value="Cancel" onclick="callclose()">
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
	    .btn.btn-default{
    color: #1B1C1D;
    border: solid 1px #1B1C1D;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 4px;
    background: transparent;
    padding: 4px 25px;
    text-transform: capitalize;
    font-weight: normal;
	box-shadow: none !important;
    }
  .heading-brown{ color: #743939; font-size: 18px;}
</style>

@endsection
@section('pageTitle')
User Detail
@endsection



@section('jscript')
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>
<script type="text/javascript" src="{{ asset('frontend/outside/js/bootstrap.min.js') }}"></script>
<script>
document.body.style.background = "transparent";
var messages = {
    is_accept: "{{ Session::get('is_accept') }}",
    individual_user:"{{ $route }}",
    APISecret   : "{{config('common.APISecret')}}",
    gatwayurl   : "{{config('common.gatwayurl')}}",
    contentType : "{{config('common.contentType')}}",
    gatwayhost  : "{{config('common.gatwayhost')}}",
    apiKey      : "{{config('common.apiKey')}}",
    groupId     : "{{Helpers::getgroupId()}}",
    token       : "{{ csrf_token() }}",
    get_users_resolved : "{{ URL::route('get_users_resolved') }}",
    
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
        parent.jQuery("#btn-resolution-iframe").modal('hide');
        window.parent.jQuery('#my-loading').css('display', 'none');
       // window.parent.location.href = messages.individual_user;
        window.parent.location.reload();
    }

    



$("#resolutionStatus").change(function(event) {
   // Get the week just selected.
   var status = $("#resolutionStatus").val();
   if(status == 1) {
       var disable = 5;
       $("option.resolutionRisk").removeAttr("disabled");
       $("option.resolutionReason").removeAttr("disabled");
       $("option.resolutionRisk[value="+disable+"]").attr("disabled", "disabled");
       $("option.resolutionReason[value=9]").attr("disabled", "disabled");
       $("option.resolutionReason[value=11]").attr("disabled", "disabled");
       $("option.resolutionReason[value=12]").attr("disabled", "disabled");
   } else if(status == 2) {
       var disable = 5;
       $("option.resolutionRisk").removeAttr("disabled");
       $("option.resolutionReason").removeAttr("disabled");
       $("option.resolutionReason[value=9]").attr("disabled", "disabled");
       $("option.resolutionReason[value=10]").attr("disabled", "disabled");
       $("option.resolutionReason[value=12]").attr("disabled", "disabled");
      
   } else if(status == 3) {
        var disable1 = 6;
       var disable2 = 7;
       var disable3 = 8;
       $("option.resolutionRisk").removeAttr("disabled");
       $("option.resolutionReason").removeAttr("disabled");
       $("option.resolutionRisk[value="+disable1+"]").attr("disabled", "disabled");
       $("option.resolutionRisk[value="+disable2+"]").attr("disabled", "disabled");
       $("option.resolutionRisk[value="+disable3+"]").attr("disabled", "disabled");
       $("option.resolutionReason[value=10]").attr("disabled", "disabled");
       $("option.resolutionReason[value=11]").attr("disabled", "disabled");
       $("option.resolutionReason[value=12]").attr("disabled", "disabled");
   } else if(status == 4) {
       var disable1 = 6;
       var disable2 = 7;
       var disable3 = 8;
       $("option.resolutionRisk").removeAttr("disabled");
       $("option.resolutionReason").removeAttr("disabled");
       $("option.resolutionRisk[value="+disable1+"]").attr("disabled", "disabled");
       $("option.resolutionRisk[value="+disable2+"]").attr("disabled", "disabled");
       $("option.resolutionRisk[value="+disable3+"]").attr("disabled", "disabled");
        $("option.resolutionReason[value=9]").attr("disabled", "disabled");
       $("option.resolutionReason[value=10]").attr("disabled", "disabled");
       $("option.resolutionReason[value=11]").attr("disabled", "disabled");
   } else if(status == '') {
       $("option.resolutionRisk").attr("disabled", "disabled");
        $("option.resolutionReason").attr("disabled", "disabled");
   }
    var token       = "{{ csrf_token() }}";
    var get_toolkit_value = "{{ URL::route('get_toolkit_value') }}";

        var myKeyVals = {_token: token, id: status};
        $.ajax({
            type: 'POST',
            url: get_toolkit_value,
            data: myKeyVals,
            dataType: "text",
            
            success: function (resultData) {
               // console.log(resultData); return false;
                    $("#statuskeyValue").val(resultData.trim());
               
            }
        });
});

$("#resolutionRisk").change(function(event) {
   // Get the week just selected.
   var status = $("#resolutionRisk").val();
   var token       = "{{ csrf_token() }}";
    var get_toolkit_value = "{{ URL::route('get_toolkit_value') }}";

        var myKeyVals = {_token: token, id: status};
        $.ajax({
            type: 'POST',
            url: get_toolkit_value,
            data: myKeyVals,
            dataType: "text",

            success: function (resultData) {
               // console.log(resultData); return false;
                    $("#riskkeyvalue").val(resultData.trim());
            }
        });

});



$("#resolutionReason").change(function(event) {
   // Get the week just selected.
   var status = $("#resolutionReason").val();
   var token       = "{{ csrf_token() }}";
    var get_toolkit_value = "{{ URL::route('get_toolkit_value') }}";

        var myKeyVals = {_token: token, id: status};
        $.ajax({
            type: 'POST',
            url: get_toolkit_value,
            data: myKeyVals,
            dataType: "text",

            success: function (resultData) {
               // console.log(resultData); return false;
                    $("#reasonkeyalue").val(resultData.trim());
            }
        });

});


   
});
function callclose() {
       parent.jQuery("#btn-resolution-iframe").modal('hide');
  }

  //////////////////


</script>

@endsection