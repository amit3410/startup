@extends('layouts.frame')
@section('content')
{!!
Form::open(
array(
'name' => 'personalcasesForm',
'id' => 'personalcasesForm',
'url' => route('individual_searchcase',['id'=>$userKycId,'searchfor' => $searchfor,'user_id'=>$userID, 'user_type'=> $userType, 'is_by_company' => $is_by_company]),
'autocomplete' => 'off','class'=>'loginForm form form-cls'
))
!!}

<div class="row">
    <div id="my-loading" style="display:none">
        <div class="square-blocks">
                <img src="{{ asset('frontend/inside/images/ajax-loader.gif') }}" alt="Loading...">
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label for="pwd">Case ID</label> <span class="mandatory">*</span>
               <input type="text" class="form-control required" placeholder="Case ID" name="caseid" id="caseid" value="">
               <span class="text-danger"></span>{{$nodata}}
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <input type="hidden" name='is_by_company' value="{{$is_by_company}}">
            <input class="btn btn-save btn-sm perwcapi m-0" name="save" type="submit" value="Search">
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
    searchfor: "{{ $searchfor }}",
    individual_user: "{{ URL::route('user_detail_similar',['user_kyc_id' => $userKycId,'wc_id' => $wc_id, 'user_id' =>$userID ,'user_type' =>$userType, 'user_kyc_id' =>$userKycId,'is_by_company'=>$is_by_company]) }}",
    corporate_user: "{{ URL::route('corp_detail_similar',['user_kyc_id' => $userKycId,'wc_id' => $wc_id]) }}",
    token       : "{{ csrf_token() }}",
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
        parent.jQuery("#searchCases").modal('hide');
        window.parent.jQuery('#my-loading').css('display', 'block');
        
        if(messages.searchfor == 'individual') {
            window.parent.location.href = messages.individual_user;
        } else {
            window.parent.location.href = messages.corporate_user;
        }
    }
   
});
</script>

@endsection