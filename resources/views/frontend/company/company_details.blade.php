@extends('layouts.app')

@section('content')

<section>
  <div class="container">
   <div class="row">

   	<div id="header" class="col-md-3">
	   @include('layouts.user-inner.left-corp-menu')
	</div>


	<div class="col-md-9 dashbord-white">
	 <div class="form-section">
	   <div class="row marB10">
		   <div class="col-md-12">
		     <h3 class="h3-headline">{{trans('forms.corp_company_details.Label.heading')}}</h3>
		   </div>
		</div>

	  <form id="companydetails" autocomplete="off" action="{{route('company_profile')}}" class="needs-validation form" novalidate method="post">


		@csrf

		<div class="row">
		  <div class="col-md-12">
			<div class="form-group">

			  <label for="pwd"> {{trans('forms.corp_company_details.Label.company_name')}}</label> <span class="mandatory">*<span>


			 <input type="text"class="form-control"  placeholder="{{trans('forms.corp_company_details.plc_holder.comp_name')}}" name="companyname"
			  value="{{isset($companyprofile)?$companyprofile->company_name:$userSignupdata->corp_name,old('companyname')}}">


		      <i style="color:red">{{$errors->first('companyname')}}</i>
			</div>
		  </div>
		</div>
		<div class="row">
		  <div class="col-md-12">
			<div class="form-group">

			  <label for="pwd">{{trans('forms.corp_company_details.Label.corp_name')}}</label> <span class="mandatory">*<span>


			 <input type="text"class="form-control"  placeholder="{{trans('forms.corp_company_details.plc_holder.corp_name')}}" name="customername"
			  value="{{isset($companyprofile)?$companyprofile->customer_name:Helpers::getUserName($userSignupdata->user_id),old('customername')}}">


		      <i style="color:red">{{$errors->first('customername')}}</i>
			</div>
		  </div>
		</div>


		 	<div class="row">
	      <div class="col-md-6">
			<div class="form-group">

			  <label for="pwd">{{trans('forms.corp_company_details.Label.corp_license_number')}}</label> <span class="mandatory">*<span>



			  <input type="text" class="form-control"  placeholder="{{trans('forms.corp_company_details.plc_holder.corp_license_number')}}" name="regisno"
			  value="{{isset($companyprofile)?$companyprofile->registration_no: $userSignupdata->corp_license_number,old('regisno')}}">
			  <i style="color:red">{{$errors->first('regisno')}}</i>
			</div>
		  </div>
		  <div class="col-md-6">
			<div class="form-group">
			  <label for="pwd">{{trans('forms.corp_company_details.Label.corp_date_of_formation')}}</label> <span class="mandatory">*<span>
			  <div class="input-group">
			  	@php
			  	$regdate=date('d/m/Y', strtotime($userSignupdata->corp_date_of_formation));
			  	@endphp
			  <input type="text" class="form-control datepicker"  placeholder="{{trans('forms.corp_company_details.plc_holder.corp_date_of_formation')}}" name="regisdate"
			  value="{{isset($companyprofile)?$companyprofile->registration_date: $regdate,old('regisdate')}}" autocomplete="off">
			      <div class="input-group-append">
					<!-- <i class="fa fa-calendar-check-o"></i> -->
				</div>
				<i style="color:red">{{$errors->first('regisdate')}}</i>
	          </div>

			</div>
		  </div>

		</div>

		<div class="row">
		  <div class="col-md-12">
			<div class="form-group">
			  <label for="pwd">{{trans('forms.corp_company_details.Label.status')}}</label> <span class="mandatory">*<span>
			 <select class="form-control" name="status" id="status" >

			  	<option value="" disabled selected>{{trans('forms.corp_company_details.plc_holder.select_status')}}</option>
			  	{{$status=Helpers::getCorpStatus()}}
			  	@foreach($status as $s)
			  	@if($companyprofile)

				<option value="{{$s->sid}}" {{($companyprofile->status==$s->sid)? 'selected':'Select Status'}}>{{$s->status}}</option>

				@else

				<option value="{{$s->sid}}">{{$s->status}}</option>

				@endif
				@endforeach
			  </select>
			  <i style="color:red">{{$errors->first('status')}}</i>
			</div>
		  </div>
		</div>




		<div class="row" style='display: none' id="company_status_other">
		  <div class="col-md-12">
			<div class="form-group">
                <input type="text" class="form-control" name="comp_status_other" id="comp_status_other" 
                value="{{@$companyprofile['status_other']}}" placeholder="{{trans('forms.corp_company_details.plc_holder.enter_status')}}" maxlength="50">
                <span class="text-danger">   </span>
            </div>
		  </div>
		</div>

		<div class="row">

		  <div class="col-md-12">
			<div class="form-group">

			  <label for="pwd">{{trans('forms.corp_company_details.Label.buss_nature')}}</label> <span class="mandatory">*<span>
			    <textarea  class="form-control" rows="3" name="naturebusiness">{{isset($companyprofile)?trim($companyprofile->business_nature) : ''}}</textarea>

			</div>
			<i style="color:red">{{$errors->first('naturebusiness')}}</i>
		  </div>
		</div>

		            <!--Social media imlemented -->


 
                    @if(is_array($userSocialMedia) && count($userSocialMedia))
                    @php
                    $j=0;
                    @endphp
                    @foreach($userSocialMedia as $objSocial)
                    <div class="row clonedclonedSocialmedias" id="clonedSocialmedias0">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pwd">{{trans('forms.personal_Profile.Label.social_media')}}</label> <span class="mandatory">*<span>
                                {!!
                                Form::select('social_media_id[]',
                                [''=>'Select Social Media'] + Helpers::getSocialmediaDropDown()->toArray(),
                                $objSocial['social_media'],
                                array('id' => 'social_media_id'.$j,'data' => $j,
                                'class'=>'form-control social'))
                                !!}
                                <span class="text-danger">{{ $errors->first('social_media_id.0') }}</span>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group postion-delete-icon">
                                <label for="pwd">{{trans('forms.personal_Profile.Label.social_media_link')}}</label> <span class="mandatory">*<span>
                                <input type="text" class="form-control"  placeholder="{{trans('forms.personal_Profile.Label.f_name')}}" name='social_media_link[]' value="{{$objSocial['social_media_link']}}" id='social_media_link{{$j}}'>
                                <span class="text-danger">{{ $errors->first('social_media_link[]') }}</span>
                                <div class="deleteSkillbtn remove text-right mt-1 "  style="display:block;"><i class="fa fa-trash-o deleteSkill" title="Remove" aria-hidden="true" style="cursor: pointer;"></i></div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                        <input type="text" class="form-control" name="social_other[]" id='other_{{$j}}' placeholder="Enter Other Social Media" value="{{$objSocial['social_media_other']}}"/>
                        </div>
                    
                    </div>
                     @php 
                       
                       $j++; 

                    @endphp
                    @endforeach


                    <div class="row">
                        <div class="col-md-12">
                            <span class="add-socialmedia pull-right marT10 text-color" style="">{{trans('master.personalProfile.add')}}</span>
                        </div>
                    </div>
                    @else
                    <div class="row clonedclonedSocialmedias" id="clonedSocialmedias0">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pwd">{{trans('forms.personal_Profile.Label.social_media')}}</label> <span class="mandatory">*<span>
                                {!!
                                Form::select('social_media_id[]',
                                [''=>'Select Social Media'] + Helpers::getSocialmediaDropDown()->toArray(),
                                null,
                                array('id' => 'social_media_id0','data' => 0,
                                'class'=>'form-control social'))
                                !!}

                                <span class="text-danger">{{ $errors->first('social_media_id.0') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group postion-delete-icon">
                                <label for="pwd">{{trans('forms.personal_Profile.Label.social_media_link')}}</label> <span class="mandatory">*<span>
                                <input type="text" class="form-control"  placeholder="{{trans('forms.personal_Profile.Label.social_media_link')}}" name='social_media_link[]' id='social_media_link0'>
                                <span class="text-danger">{{ $errors->first('social_media_link.0') }}</span>
                                <div class="deleteSkillbtn remove text-right mt-1 "  style="display: none;"><i class="fa fa-trash-o deleteSkill" title="Remove" aria-hidden="true" style="cursor: pointer;"></i></div>
                            </div>
                        </div>
                         
                        <div class="col-md-6">
                        <input type="text" class="form-control" name="social_other[0]" id="other_0" placeholder="Enter Other Social Media" value=""/>
                        </div>
                    
                    </div>

                   

                    <div class="row">
                        <div class="col-md-12">
                            <span class="add-socialmedia pull-right marT10 text-color" style="">{{trans('master.personalProfile.add')}}</span>
                        </div>
                    </div>
                    @endif	

                    	<br/><br/>






		 <div class="row">
         <div class="col-md-12 text-right">
		  
                   @if($kycApproveStatus==0)

		    {{ Form::submit(trans('common.Button.save'),['class'=>'btn btn-save','name'=>'save']) }}
                    {{ Form::submit(trans('common.Button.save_next'),['class'=>'btn btn-save','name'=>'save_next']) }}
                    @else
                    <a href="{{$next_url}}" class="btn btn-save">{{trans('common.Button.next')}}</a>
                    @endif

                 </div>
		</div>
	 </form>
	  </div>
	</div>

   </div>
  </div>

</section>

@include('frontend.modal')
 @section('additional_css')
 <link href="{{ asset('frontend/inside/plugin/datepicker/jquery-ui.css') }}" rel="stylesheet">
 @endsection
 @section('jscript')

 <script type="text/javascript" src="{{ asset('frontend/outside/js/bootstrap.min.js') }}"></script>
 <script src="{{ asset('frontend/inside/plugin/datepicker/jquery-ui.js') }}"></script>  
<script src="{{ asset('frontend/outside/js/validation/socialmedia.js')}}"></script>
 <script>
 $(document).ready(function(){
      $('input.form-control').attr('autocomplete',true);//Autocomplete off 
     var disabledAll='{{$kycApproveStatus}}';
    if(disabledAll!=0){
        $("#companydetails :input"). prop("disabled", true); 
    }



	    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
        });
        var baseurl="{{url('')}}";
    $('.datepicker').datepicker({
         dateFormat: 'dd/mm/yy',
         maxDate: new Date(),
         changeMonth : true,
         changeYear :  true,

     });
    $('.datepicker').keydown(function (e) {
                e.preventDefault();
                return false;
            });
     $.get(baseurl+'/ajax/modal-ajax', function(data){

	        if(data==0 || data==null){
	            $('#myModal').modal('show');
	        }
	    });
    //ajax call for update user
     $('.modalclose').click(function(){
        $.get(baseurl+'/ajax/user_first_time_popup', function(data){
        });
    });
  });
</script>
@endsection
@include('frontend.company.companyscript')
@endsection
@section('jscript')

<script src="{{ asset('frontend/outside/js/validation/corporatevalidation.js') }}"></script>

 <script src="{{ asset('frontend/inside/plugin/datepicker/jquery-ui.js') }}"></script>


@endsection