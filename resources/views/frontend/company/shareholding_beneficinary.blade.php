@extends('layouts.app')

@section('content')

<section>
    <div class="container">
        <div class="container">
            <div class="alertMsgBox hide"  id="msgBlockSuccess">

            </div>   
        </div> 
        <div class="container">
            <div class="alertMsgBox hide"  id="msgBlockError"></div>   
        </div>
        <div class="row">

            <div id="header" class="col-md-3">
                @include('layouts.user-inner.left-corp-menu')
                
            </div>
           <div class="col-md-9 dashbord-white">
	 <div class="form-section">
	   <div class="row marB10">
		   <div class="col-md-12">
		     <h3 class="h3-headline">{{trans('forms.shareholding_beneficiary.Label.heading')}}</h3>
		   </div>
		</div>   
		
	 <div class="row marB10 marT20">
	  <div class="table-responsive">
		<table class="table table-ultimate">
			<thead>
			  <tr>
				<th>{{trans('forms.shareholding_beneficiary.Label.th_name')}}</th>
				<th>{{trans('forms.shareholding_beneficiary.Label.th_sharehol')}}</th>
				<th>{{trans('forms.shareholding_beneficiary.Label.th_actions')}}</th>
				<th>{{trans('forms.shareholding_beneficiary.Label.th_status')}}</th>
			  </tr>
			</thead>
			<tbody>
                            @if($beficiyerData && $beficiyerData->count())
                            @foreach($beficiyerData as $obj)
                      @php   
                        if($obj->is_kyc_completed==1) {
                         $boun = 'Completed';
                         $boun_status = 'st_complete';
                        } else {
                         $boun = 'Pending';
                         $boun_status = 'st_not_complete';
                        }
                        if($obj->is_approve==1) {
                         $boun_appr = 'Approved';
                         $boun_appr_status = 'text-approved';
                        } else {
                         $boun_appr = 'Not Approved';
                         $boun_appr_status = 'text-disapproved';
                        }
                        
                    @endphp
                            <tr>
				<td>{{$obj->company_name}}</td>
				<td>{{$obj->actual_share_percent}} {{trans('forms.shareholding_beneficiary.Label.sym')}}<?php //echo "==>".$obj->user_id."2==>".$obj->owner_kyc_id."3==>".$obj->company_name."4==>".$obj->passport_no?></td>
				<td>@if($obj->actual_share_percent>=5)<a href="{{route('profile',['corp_user_id'=>$obj->user_id,'user_kyc_id'=>$obj->owner_kyc_id,'share_name' => str_replace(".","",$obj->company_name),'share_passportnumber' => isset($obj->passport_no) ? $obj->passport_no : ""])}}" class="kyc-color">{{trans('forms.shareholding_beneficiary.Label.lbl_kyc')}}</a>@else {{trans('forms.shareholding_beneficiary.Label.is_kyc')}} @endif</td>
				<td><span class="status-icon {{@$boun_status}}"> </span> {{ isset($boun) ? $boun : '' }}</td>
			    </tr>
                            @endforeach
                            @endif

			</tbody>
		  </table>
	    </div>
	   </div>
	
	
	<div class="row marT140">{{$data['kycApproveStatus']}}
         <div class="col-md-12 text-right">
		  
         @if($data['kycApproveStatus']==0)
                    
                  <a href="{{$data['prev_url']}}" class="btn btn-prev pull-left">{{trans('common.Button.pre')}}</a>		 

		<a href="{{route('financial-show')}}" class="btn btn-save">{{trans('common.Button.next')}}</a>  
                            @else
<a href="{{$data['prev_url']}}" class="btn btn-prev pull-left">{{trans('common.Button.pre')}}</a>	
                           <a href="{{$data['next_url']}}" class="btn btn-save">{{trans('common.Button.next')}}</a>
                            @endif
         </div>
   </div>
	  
	  
	  
	  </div>
	</div>

        </div>	
    </div>

</section>
@endsection

@section('pageTitle')
{{trans('forms.shareholding_beneficiary.Label.page_tittle')}}
@endsection

@section('jscript')
<script src="{{ asset('backend/theme/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('frontend/outside/js/validation/shareHolding.js')}}"></script>
<script src="{{ asset('frontend/outside/js/validation/shareholderForm.js')}}"></script>
<script>
var messages = {
    social_media_form_limit: "{{ config('common.SOCIAL_MEDIA_LINK') }}",
    document_form_limit: "{{ config('common.DOCUMENT_LIMIT') }}",
    shareholder_save_ajax: "{{URL::route('shareholder_save_ajax')}}",
};

</script>
@endsection

