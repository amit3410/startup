@extends('layouts.admin')

@section('content')

<div class="form-section">
	<div class="row marB10">
        <div class="col-md-12">
            <h3 class="h3-headline">Manage Users</h3>
        </div>
    </div>

<div class="row">
                    
                <div class="col-lg-4 col-md-4 mb-3">
                    <div class="panel custom-checks panel-brown">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-sm-12 flex-center">
                                    <div class="inner-box">
                                    <h3 class="h3-headline yellow-color">Approved Individual Users</h3>
                                    <p>{{$userStatusSummary['IndvApproved']}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div> 
             <div class="col-lg-4 col-md-4 mb-3">
                    <div class="panel custom-checks panel-brown">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-sm-12 flex-center">
                                    <div class="inner-box">
                                    <h3 class="h3-headline yellow-color">Approved Corporate Users</h3>
                                    <p>{{$userStatusSummary['CorpApproved']}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>  

                <div class="col-lg-4 col-md-4 mb-3">
                    <div class="panel custom-checks panel-brown">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-sm-12 flex-center">
                                   <div class="inner-box">
                                   <h3 class="h3-headline yellow-color">Individual Approval Pending</h3>
                                   <p>{{$userStatusSummary['IndvApprPending']}}</p>
                                   </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div> 

                <div class="col-lg-4 col-md-4 mb-3">
                    <div class="panel custom-checks panel-brown">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-sm-12 flex-center">
                                    <div class="inner-box">
                                    <h3 class="h3-headline yellow-color">Corporate Approval Pending</h3>
                                    <p>{{$userStatusSummary['CorpApprPending']}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div> 
                <div class="col-lg-4 col-md-4 mb-3">
                    <div class="panel custom-checks panel-brown">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-sm-12 flex-center">
                                    <div class="inner-box">
                                    <h3 class="h3-headline yellow-color">Individual KYC Pending</h3>
                                    <p>{{$userStatusSummary['IndvKycPending']}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-lg-4 col-md-4 mb-3">
                    <div class="panel custom-checks panel-brown">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-sm-12 flex-center">
                                    <div class="inner-box">
                                    <h3 class="h3-headline yellow-color">Corporate KYC Pending</h3>
                                    <p>{{$userStatusSummary['CorpKycPending']}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                 
   </div>


</div>

@endsection
@section('pageTitle')
Dashboard
@endsection


@section('jscript')

<script src="{{ asset('frontend/inside/plugin/datepicker/jquery-ui.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>

<script>
document.body.style.background = "transparent";
var messages = {
    is_accept: "{{ Session::get('is_accept') }}",
    APISecret   : "{{config('common.APISecret')}}",
    gatwayurl   : "{{config('common.gatwayurl')}}",
    contentType : "{{config('common.contentType')}}",
    gatwayhost  : "{{config('common.gatwayhost')}}",
    apiKey      : "{{config('common.apiKey')}}",
    token       : "{{ csrf_token() }}",
    get_groupID : "{{ URL::route('get_groupID') }}",
    get_resolution_toolkit : "{{ URL::route('get_resolution_toolkit') }}",
    datetime : "<?php echo gmdate('D, d M Y H:i:s \G\M\T', time());?>",



};

</script>
<script src="{{ asset('common/js/group.js') }}"></script>
<script>
$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

 checkgroupId();
});
</script>
<script>
var messagesG = {
     groupId : "{{Helpers::getgroupId()}}",
};

$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

 //checktoolkit();
});
</script>
@endsection


