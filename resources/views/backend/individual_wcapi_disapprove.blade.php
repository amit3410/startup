@extends('layouts.frame')

@section('content')


{!!
Form::open(
array(
'name' => 'personalWcapiForm',
'id' => 'personalWcapiForm',
'url' => route('individual_api_disapprove',['id'=>$userKycId,'profileId' => $profileId,'responceProfileId'=>$responceProfileId]),
'autocomplete' => 'off','class'=>'loginForm form form-cls'
))
!!}
<?php


  if($userData && $userType == 1){
    if($is_by_company == 1) {
      $route=route("user_detail",['user_id' => $userData,'user_type' => $userType,'user_kyc_id'=>$userKycId,'is_by_company' => '1','tab' => 'tab06']);
    } else {
      $route=route("user_detail",['user_id' => $userData,'user_type' => $userType,'user_kyc_id'=>$userKycId,'is_by_company' => '0','tab' => 'tab06']);
    }

  } else if($userData && $userType == 2){
    $route=route("corp_user_detail",['user_id' => $userData,'user_type' => $userType,'user_kyc_id'=>$userKycId,'tab' => 'tab06']);
  }
?>

<div class="row">
   
    
    
    <div class="col-sm-12 text-center">
        <h4 class="heading-brown">Do you want to Disapprove the User?</h4>
            <input class="btn btn-save btn-sm perwcapi" name="saveyes" type="submit" value="Yes">
            <input class="btn btn-default btn-sm perwcapi" name="save" type="button" value="No" onclick="callclose()">
        

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

<script type="text/javascript" src="{{ asset('frontend/outside/js/bootstrap.min.js') }}"></script>
<script>
document.body.style.background = "transparent";
var messages = {
    is_accept: "{{ Session::get('is_accept') }}",
    individual_user:"{{ $route }}",
};
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

   
    if (messages.is_accept == 1) {
        var parent = window.parent;
        parent.jQuery("#btn-disapproved-iframe").modal('hide');
        window.parent.jQuery('#my-loading').css('display', 'block');
        window.parent.location.href = messages.individual_user;
    }
   
});
function callclose() {
       parent.jQuery("#btn-disapproved-iframe").modal('hide');
  }


</script>

@endsection