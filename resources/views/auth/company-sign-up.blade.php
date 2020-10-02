@extends('layouts.withought_login')
@section('content')
<div class="content-wrap height-100">
    <div class="login-section sign-box-bk">
        <div class="logo-box text-center marB20">
            <a href="{{url('/')}}"><img src="{{ asset('frontend/outside/images/00_dexter.svg') }}" class="img-responsive"></a>
            <h2 class="head-line2 marT25">{{trans('master.sign_up_corporate')}}</h2>
        </div>

        <div class="sign-up-box">

            <form class="registerForm form form-cls" autocomplete="off" enctype="multipart/form-data" method="POST" action="{{ route('company_register_open') }}" id="compregisterForm">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="uname">{{trans('master.country_of_registration')}}</label>
                            <span class="mandatory">*<span>
                            {!!
                            Form::select('country_id',
                            [''=>'Select']+Helpers::getCountryDropDown()->toArray(),
                            (isset($userArr->country_id) && !empty($userArr->country_id)) ? $userArr->country_id : (old('country_id') ? old('country_id') : ''),
                            array('id' => 'country_id',
                            'class'=>'form-control'))
                            !!}

                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="pwd">{{trans('master.company_name')}} </label>
                            <span class="mandatory">*<span>
                            <input type="text" class="form-control"  placeholder="{{trans('master.company_name')}}" name="company_name" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="pwd">{{trans('master.company_date_of_formation')}}</label>

                            
                            <div class="input-group">
                                <input type="text" class="form-control datepicker"  placeholder="{{trans('master.company_date_of_formation')}}" name="comp_dof" id="comp_dof" value="{{old('comp_dof')}}">
                                <div class="input-group-append">
                                    <i class="fa fa-calendar-check-o"></i>
                                </div>
                            </div>
                            {{$errors->first('comp_dof')}} 
                        </div>

                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                        <label for="pwd">{{trans('master.company_trade_license_number')}}</label>
                        <span class="mandatory">*<span>
                            <input type="text" class="form-control required"  placeholder="{{trans('master.company_trade_license_number')}}" name="comp_trade_in" id="comp_trade_in" required>
                        </div>
                    </div>
                </div>




                <div class="row">
                    <div class="col-md-12 marT10">
                        <div class="form-group">
                            <label for="pwd">{{trans('master.signatory_company_authorized')}}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="pwd">{{trans('master.first_name')}} </label>
                            <span class="mandatory">*<span>
                            <input type="text" class="form-control"  placeholder="{{trans('master.first_name')}}" name="first_name" maxlength="50" autocomplete="off">
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="pwd">{{trans('master.middle_name')}}</label>
                            <input type="text" class="form-control" placeholder="{{trans('master.middle_name')}}" name="middle_name" maxlength="50" autocomplete="off">

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="pwd">{{trans('master.last_name')}}</label>
                            <span class="mandatory">*<span>
                            <input type="text" class="form-control required" placeholder="{{trans('master.last_name')}}" name="last_name" maxlength="50" autocomplete="off">

                        </div>
                    </div>
                </div>





                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="pwd">{{trans('master.authorized_signatory_date_of_birth')}}</label>
                            <span class="mandatory">*<span>
                            <div class="input-group">
                                <input type="text" class="form-control datepicker"  placeholder="{{trans('master.authorized_signatory_date_of_birth')}}" name="dob" autocomplete="off">
                                <div class="input-group-append">
                                    <i class="fa fa-calendar-check-o"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="pwd">{{trans('master.official_email_address')}}</label>
                            <span class="mandatory">*<span>
                            <input type="email" class="form-control required"  placeholder="{{trans('master.official_email_address')}}" name="email" id="email" autocomplete="off">
                            
                        </div>
                    </div>
                </div>

                <div class="row">

                     <div class="col-md-5">
                        <div class="form-group">
                            <label for="uname">{{trans('master.country_code')}}</label>
                            <span class="mandatory">*<span>
                            @php
                            $countrycode=Helpers::getCountryCode();

                            @endphp
                           
                          <select name="country_code" class="form-control select2Cls">
                                <option value="">{{trans('master.select')}}</option>
                                @foreach($countrycode as $code)
                                    <option value="{{$code->phonecode}}">{{$code->country_name.' (+'.$code->phonecode.')'}}</option>
                                @endforeach
                                
                            </select>
                            {{$errors->first('country_code')}}
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="form-group">
                            <label for="pwd">{{trans('master.official_mobile_no')}}</label>
                            <span class="mandatory">*<span>
                            <input type="text" class="form-control required"  placeholder="{{trans('master.official_mobile_no')}}" id="phone" name="phone" maxlength="20" autocomplete="off">

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <p class="text-center have-account marB15">{{trans('master.already_have_an_account')}} <a href="{{url('/')}}/login" class="underline">{{trans('master.sign_in')}}</a></p>
                    </div>
                    <div class="col-md-12">

                        <input type='submit' class='btn btn-sign verify-btn' name='next' value='{{trans('master.sign_up')}}' />
                    </div>
                </div>
            </form>


        </div>
    </div>
</div>



<script>
    var messages = {

        invalid_first_name: "{{ trans('error_messages.invalid_first_name') }}",
        req_first_name: "{{ trans('error_messages.req_first_name') }}",
    company_minlength:"{{trans('forms.corp_company_details.client_error.customername_minlength')}}",
        invalid_age: "{{ trans('error_messages.invalid_age')}}",
        invalid_middle_name: "{{ trans('error_messages.invalid_middle_name') }}",
        
        req_last_name: "{{ trans('error_messages.req_last_name') }}",
        
        invalid_last_name: "{{ trans('error_messages.invalid_last_name') }}",
        req_email: "{{ trans('error_messages.req_email') }}",
        invalid_email: "{{trans('error_messages.invalid_email')}}",

        req_email: "{{ trans('error_messages.req_email') }}",
        invalid_email: "{{ trans('error_messages.invalid_email') }}",
        invalid_name: "{{ trans('forms.Corperate_Reg.client_error.invalid_name') }}",
        invalid_len :  "{{ trans('forms.Corperate_Reg.client_error.invalid_len') }}",

        phone_minlength: "{{ trans('error_messages.phone_minlength') }}",
        phone_maxlength: "{{ trans('error_messages.phone_maxlength') }}",
        positive_phone_no: "{{ trans('error_messages.positive_phone_no') }}",
        invalid_phone: "{{ trans('error_messages.invalid_phone') }}",
        req_country_code:"{{ trans('forms.Corperate_Reg.client_error.req_country_code') }}",
        trade_maxlength :"{{ trans('forms.Corperate_Reg.client_error.maxlength') }}",
        email_exist:"{{trans('error_messages.email_already_exists')}}",

    };
</script>
<script src="{{ asset('frontend/outside/js/validation/compregister.js') }}"></script>
<link href="{{ asset('frontend/inside/plugin/datepicker/jquery-ui.css') }}" rel="stylesheet">
<script src="{{ asset('frontend/inside/plugin/datepicker/jquery-ui.js') }}"></script>
 <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/additional-methods.min.js"></script>
<script>
    $(document).ready(function () {
        $('input.form-control').attr('autocomplete',true);
        var date = $('.datepicker').datepicker({
            dateFormat: 'dd/mm/yy', 
            maxDate: '-1',
            changeMonth: true,
            changeYear: true
        });

        $('.datepicker').keydown(function (e) {
            e.preventDefault();
            return false;
        });

        $('.datepicker').on('paste', function (e) {
            e.preventDefault();
            return false;
        });

    });



</script>



@endsection

