@extends('layouts.withought_login')
@section('content')
            <div class="content-wrap height-auto">
    <div class="login-section">
	  <div class="logo-box text-center marB20">
	    <a href="{{url('/')}}"><img src="{{ asset('frontend/outside/images/00_dexter.svg') }}" class="img-responsive"></a>
		<h2 class="head-line2 marT25">{{trans('master.chgPassForm.heading')}}</h2>
	  </div>

	  <div class="sign-up-box">
                @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif



    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


		<form  id="changepassword" class="form-horizontal form form-cls" method="POST" action="{{ url('password/change') }}">

                            {{ csrf_field() }}

		 <div class="row">
		  <div class="col-md-12">
			<div class="form-group">
			  <label for="pwd">{{trans('master.chgPassForm.old_pass')}}</label>
			  <input type="password" class="form-control"  placeholder="{{trans('master.chgPassForm.enter_old_pass')}}" name="current-password" id="current-password">

                        </div>
		  </div>
         </div>

		 <div class="row">
		   <div class="col-md-12">
			<div class="form-group">
			  <label for="pwd">{{trans('master.chgPassForm.new_pass')}}</label>
			  <input type="password" class="form-control" placeholder="{{trans('master.chgPassForm.enter_new_pass')}}" name="new-password" id="new-password">

                        </div>
		  </div>

		  </div>

		 <div class="row">
		  <div class="col-md-12">
			<div class="form-group">
			  <label for="pwd">{{trans('master.chgPassForm.conf_new_pass')}}</label>
			  <input type="password" class="form-control"  placeholder="{{trans('master.chgPassForm.enter_conf_pass')}}" name="new-password_confirmation" id="new-password_confirmation" >

                        </div>
		  </div>
         </div>


		 <div class="row">
            <div class="col-md-12 back-btn-bg">
                 @if(Auth::user()->is_pwd_changed == 1 && Auth::user()->user_type == 1)
               	  <a href="{{ url('profile') }}" class="btn btn-sign verify-btn">Back</a>
                @elseif(Auth::user()->is_pwd_changed == 1 && Auth::user()->user_type == 2)
               	  <a href="{{route('company_profile-show')}}" class="btn btn-sign verify-btn">Back</a>
                @endif
                
                <!--a class="btn btn-sign verify-btn"--><a class=""><input type='submit' class='btn btn-sign verify-btn' name='Submit' value="{{trans('master.chgPassForm.submit')}}" /></a>
             </div>

		</div>
               

	</form>


	  </div>


	</div>
 </div>



<script type="text/javascript" src="{{ asset('frontend/outside/js/jquery-3.2.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('frontend/outside/js/bootstrap.min.js') }}"></script>
@endsection
