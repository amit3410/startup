@extends('layouts.withought_login')
@section('content')
<div class="content-wrap height-auto">

    <div class="login-section">
            @if(Session::has('message_div'))
        <div class="logo-box text-center marB20">
            <a href="{{url('/')}}"><img src="{{ asset('frontend/outside/images/00_dexter.svg') }}" class="img-responsive"></a>
            
        </div>
        <div class="success-msg mt-5 mb-5 success-font" role="alert">
            <strong>{{ Session::get('message_div') }}</strong>
        </div>

           <div class="form-group">
                <p class=" have-account marB15">
                <a class="lnk-toggler have-account marB15 underline" data-panel=".panel-login" href="{{url('login')}}" >Already have an account?</a>
                </p>
            </div>
            <div class="form-group">
                    <p class=" have-account marB15">
                    <a  class=" marB15 underline" href="{{url('/')}}">Don’t have an account?</a>
                    </p>
            </div>
        @else
        <div class="logo-box text-center marB20">
            <a href="{{url('/')}}"><img src="{{ asset('frontend/outside/images/00_dexter.svg') }}" class="img-responsive"></a>
            
        </div>
        <h2 class="head-line2 marT25">Reset Password</h2>



        <div class="sign-up-box">

             <div class="authfy-panel panel-forgot">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12">
                                <div class="authfy-heading">
                                    
                                    <p>Fill in your e-mail address below and we will send you an email with further instructions.</p>
                                </div>
                                <form class="forgetForm form form-cls" autocomplete="off" method="POST" action="{{ url('password/email') }}" id="forgotPassFrm">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <input id="email" autocomplete="off" type="email" autofocus="" class="form-control" placeholder="Email address" name="email" value="{{ old('email') ? old('email') : '' }}">
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-sign verify-btn" type="submit">Reset my password</button>
                                    </div>
                                    <div class="form-group">
                                        <p class=" have-account marB15">
                                        <a class="lnk-toggler have-account marB15 underline" data-panel=".panel-login" href="{{url('login')}}" >Already have an account?</a>
                                        </p>
                                    </div>
                                    <div class="form-group">
                                        <p class=" have-account marB15">
                                        <a  class=" marB15 underline" href="{{url('/')}}">Don’t have an account?</a>
                                        </p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
        </div>
    @endif
    </div>
</div>

<style>
.success-msg {
    color: #fff;
    font-size: 13px;
    background: #579a57;
    border-radius: 4px;
    padding: 7px 8px;
    margin-bottom: 10px;
    font-weight: 700;
}
</style>

<script>
    var messages = {
        req_email: "{{ trans('error_messages.req_email') }}",
        req_password: "{{ trans('error_messages.req_password') }}",
        req_confirm_password: "{{ trans('error_messages.req_confirm_password') }}"

    };
</script>
<script src="{{ asset('frontend/outside/js/validation/login.js') }}"></script>

@endsection



