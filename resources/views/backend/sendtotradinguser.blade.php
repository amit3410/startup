@extends('layouts.frame')

@section('content')


{!!
Form::open(
array(
'name' => 'sendtotradingForm',
'id' => 'sendtotradingForm',
'url' => route('sendtotrading_user'),
'autocomplete' => 'off','class'=>'loginForm form form-cls'
))
!!}

<div class="form-data">


    <div id="my-loading" style="display:none">
            <div class="square-blocks">
                <img src="{{ asset('frontend/inside/images/ajax-loader.gif') }}" alt="Loading...">
            </div>
        </div>

    

    <div class="row marT25 marB10" style="margin-right:0px !important; margin-left: 0px !important;">
                        <div class="">
                            <div class="form-group">
                                <h4 class="mess">Are you sure you want to generate ENEX Electronic Trading Portal Credentials for the user?</h4>
                            </div>
                        </div>
      </div>
   <div class="row"> 
    <div class="col-6">
        <div class="form-group">
            <label for="pwd">First Name </label> <span class="mandatory">*</span>
                    <input type="text" class="form-control required" name="f_name" id="f_name" value="{{isset($userArr['f_name']) ? $userArr['f_name'] : ''}}" readonly="">
                    <span class="text-danger"></span>
                </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label for="pwd">Middle Name </label>
                    <input type="text" class="form-control" name="m_name" id="m_name" value="{{isset($userArr['m_name']) ? $userArr['m_name'] : ''}}" readonly="">
                    <span class="text-danger"></span>

                </div>
    </div>


    <div class="col-6">
        <div class="form-group">
            <label for="pwd">Last Name </label>
                    <input type="text" class="form-control" id="l_name"  name="l_name" value="{{isset($userArr['l_name']) ? $userArr['l_name'] : ''}}" readonly="">
                    <span class="text-danger"></span>
               </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label for="pwd">Email </label>
            <input type="text" class="form-control" id="l_name" name="email" value="{{isset($userArr['email']) ? $userArr['email'] : ''}}" readonly="">
                    <span class="text-danger"></span>
               </div>
    </div>
   
    
    <div class="col-12 text-right">
        <div class="form-group">
            
            <input class="btn btn-save btn-sm perwcapi" name="save" type="submit" value="Yes">
            <input type="hidden" name='id' value="{{isset($userArr['user_id']) ? $userArr['user_id'] : ''}}">
            <input class="btn btn-default btn-sm" name="save" type="button" value="No" onclick="callclose()">
        </div>

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
	.btn.btn-default.btn-sm{
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
	
	.mess {
    font-size: 17px;
    color: #743939;
    font-weight: 600;
}
body{
	background:#ffffff;
}
	
    .v-align-sub{vertical-align: sub}
    iframe body{background-color: #fff;}
    .mandatory {
    font-size: 12px;
    color: red;
}

.form-group label {
    color: #743939;
    font-size: 13px;
    font-weight: 600;
}
.form-data .form-control {
    height: 34px;
    font-size: 13px;
}
</style>



@endsection




@section('jscript')

<script type="text/javascript" src="{{ asset('frontend/outside/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('frontend/inside/plugin/datepicker/jquery-ui.js') }}"></script>
<script>

document.body.style.overflowX = "hidden";

var messages = {
    is_accept: "{{ Session::get('is_accept') }}",
   
};
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    if (messages.is_accept == 1) {
        //var parent = window.parent;
//        parent.jQuery("#").modal('hide');
//        window.parent.jQuery('#my-loading').css('display', 'block');
       window.parent.location.reload();
    }






});
function callclose() {
     parent.jQuery("#sendtoTrading").modal('hide');
  }


</script>

@endsection