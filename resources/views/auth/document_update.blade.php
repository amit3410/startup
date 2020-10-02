@extends('layouts.withought_login')
@section('content')

<div class="content-wrap height-100">
    <div class="login-section sign-box-bk">
        <div class="logo-box text-center marB20">
            <a href="{{url('/')}}"><img src="{{ asset('frontend/outside/images/00_dexter.svg') }}" class="img-responsive"></a>
            <h2 class="head-line2 marT25">{{trans('master.sign_up_indivisual')}}</h2>
        </div>

        <div class="sign-up-box">

            <form class="registerForm form form-cls" autocomplete="off" enctype="multipart/form-data" method="POST" action="{{ route('user_register_open') }}" id="registerForm">

                {{ csrf_field() }}

                

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="pwd">{{trans('forms.UpdateDocument.Label.passport_no')}}</label>
                            <input type="text" class="form-control"  placeholder="{{trans('forms.UpdateDocument.Label.passport_no')}}" name="document_number" id="document_number">

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                              <label for="pwd">{{trans('forms.UpdateDocument.Label.issue_date')}}</label>

                            <div class="input-group">
                                <input type="text" class="form-control datepicker"  placeholder="{{trans('forms.UpdateDocument.Label.issue_date')}}" name="issuance_date" id="issuance_date" value="{{old('issuance_date')}}" autocomplete="off" >
                                <div class="input-group-append">
                                    <i class="fa fa-calendar-check-o"></i>
                                </div>
                            </div>  
                            {{$errors->first('issuance_date')}} 
                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                              <label for="pwd">{{trans('forms.UpdateDocument.Label.expiry_date')}}</label>

                            <div class="input-group">
                                <input type="text" class="form-control expirydate"  placeholder="{{trans('forms.UpdateDocument.Label.expiry_date')}}" name="expiry_date" id="dob" value="{{old('expiry_date')}}" autocomplete="off" >
                                <div class="input-group-append">
                                    <i class="fa fa-calendar-check-o"></i>
                                </div>
                            </div>  
                            {{$errors->first('expiry_date')}} 
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="pwd">{{trans('forms.UpdateDocument.Label.upload')}}</label>
                            <input id="document_file" type="file"  name="document_file[]" multiple class="upload"/>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <a class="btn btn-sign verify-btn"><input type='submit' class='btn btn-sign verify-btn' name='Submit' value="{{trans('master.chgPassForm.submit')}}" /></a>

                    </div>

                </div>
            </form>


        </div>


    </div>
    <script>
       
    </script>
    <link href="{{ asset('frontend/inside/plugin/datepicker/jquery-ui.css') }}" rel="stylesheet">
 
    <script src="{{ asset('frontend/inside/plugin/datepicker/jquery-ui.js') }}"></script>

   
    <script>
        $(document).ready(function () {


            var date = $('.datepicker').datepicker({dateFormat: 'dd/mm/yy', maxDate: new Date(),changeMonth: true, changeYear: true});

            $('.datepicker').keydown(function (e) {
                e.preventDefault();
                return false;
            });

            $('.datepicker').on('paste', function (e) {
                e.preventDefault();
                return false;
            });
            
            var date = $('.expirydate').datepicker({dateFormat: 'dd/mm/yy', minDate: new Date(),changeMonth: true, changeYear: true});

            $('.expirydate').keydown(function (e) {
                e.preventDefault();
                return false;
            });

            $('.expirydate').on('paste', function (e) {
                e.preventDefault();
                return false;
            });
        });



    </script>
    @endsection



