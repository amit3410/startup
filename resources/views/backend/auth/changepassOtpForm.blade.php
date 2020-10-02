@extends('layouts.withought_login')
@section('content')
        @if (count($errors) > 0)
        <div class="alertMsgBox">
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
            <div class="content-wrap height-auto">
                <div class="login-section">
                    <div class="logo-box text-center">
                        <a href="{{url('/')}}"><img src="{{ asset('frontend/outside/images/00_dexter.svg') }}" class="img-responsive"></a>
                    </div>

                    <div class="thanks-conent marT50">
                        <h2 class="head-line2 marT20">{{trans('master.changepassOtpForm.thanks_email_verify')}}</h2>
                        <p class="p-conent">{{trans('master.changepassOtpForm.enter_otp_below')}}</p>
                    </div>

                    <!--startotp-->

                    <div class="sign-up-box">

                        
                        <form class="registerForm form form-cls" autocomplete="off" enctype="multipart/form-data" method="POST" action="{{ route('changepass_otp',['token'=>$tokenarr['token']]) }}" id="changepassOtpForm">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="pwd">{{trans('master.changepassOtpForm.otp_heading')}}</label>
                                        <input type="text" class="form-control"  placeholder="{{trans('master.otpForm.enter_otp')}}" name="otp" id="otp" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 d-flex justify-content-between">
                                     <input type='submit' class='btn btn-next btn-fill btn-warning btn-wd submit-btns' name='next' value="{{trans('master.changepassOtpForm.verify_otp')}}" />
                                    <input type="hidden" name="token" id="token" value="{{$tokenarr['token'] }}" />
                                    <a href="{{ route('resend_otp',['token' => $tokenarr['token']]) }}" class="btn btn-next btn-fill btn-warning btn-wd submit-btns">Resend OTP</a>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>

        <style type="text/css">
                 .btn-warning {
                    color: #212529 !important;
                    background-color: #ffc107;
                    border-color: #ffc107;
                    font-size: 14px !important;
                    font-weight: 600 !important;
                }
            </style>
<script>
    var messages = {
        req_otp: "{{ trans('error_messages.req_otp') }}",
        invalid_otp: "{{ trans('error_messages.invalid_otp') }}",

    };
</script>
<script  type="text/javascript" src="{{ asset('frontend/outside/js/validation/otp.js') }}"></script>
@endsection
@section('jscript')
<script type="text/javascript" src="{{ asset('frontend/outside/js/jquery-3.2.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('frontend/outside/js/bootstrap.min.js') }}"></script>
 @endsection

    

