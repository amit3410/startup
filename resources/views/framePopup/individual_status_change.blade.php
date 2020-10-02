@extends('layouts.frame')

@section('content')


{!! Form::open(

            array(
                'name' => 'statusFrm',
                'id' => 'statusFrm',
                'autocomplete' => 'off',
                'class' => 'form',
                'url' => route('change_status',['user_id'=>$userId,'current_status'=>$current_status,'curouteName'=>$curouteName,'user_type' => $user_type]),
                'method' => 'post',
                
                )
            )
    !!}
<?php
if($current_status == 0) {
    $statusDisplay = 'Active';
} else {
   $statusDisplay = 'Inactive';
}

if($user_type == 1) {
    $profileType = 'User';
} else {
     $profileType = 'Company';
}

  
?>

<div class="row">
   
    
    
    <div class="col-sm-12 text-center">
        <h4 class="heading-brown mt-3">Do you want to {{$statusDisplay}} the {{$profileType}}?</h4>
            <input class="btn btn-save btn-sm perwcapi" name="saveyes" type="submit" value="Yes">
            <input class="btn btn-save btn-sm perwcapi" name="save" type="button" value="No" onclick="callclose()">
        

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
        parent.jQuery("#changeStatus").modal('hide');
        window.parent.jQuery('#my-loading').css('display', 'block');
        window.parent.location.href = messages.individual_user;
    }


  
    

    
});
function callclose() {
       parent.jQuery("#changeStatus").modal('hide');
  }


</script>

@endsection