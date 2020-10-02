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
		     <h3 class="h3-headline">{{trans('forms.corp_financial.Label.heading')}}</h3>
		   </div>
		</div>   
	  <form id="financialform" autocomplete="off" method="post" action="{{route('financial')}}" class="needs-validation form" novalidate>
	 	@csrf
						
		
	   <div class="row">
		  <div class="col-md-6">
			<div class="form-group inputborder-left">
			  <label for="pwd">{{trans('forms.corp_financial.Label.total_debts_usd')}}</label> <span class="mandatory">*<span>
			  <div class="input-group mb-3">
				<div class="input-group-prepend">
				  <span class="input-group-text">{{trans('forms.corp_financial.Label.sym')}}</span>
				</div>
				<input type="text" class="form-control number" name="total_debts_usd" placeholder="{{trans('forms.corp_financial.plc_holder.enter_value')}}" value="{{isset($financial)?$financial->total_debts_usd:old('total_debts_usd')}}">
				<i style="color:red">{{$errors->first('total_debts_usd')}}</i>
			  </div>
			</div>
		  </div>
		  <div class="col-md-6">
			<div class="form-group inputborder-left">
			  <label for="pwd">{{trans('forms.corp_financial.Label.total_cash')}}</label> <span class="mandatory">*<span>
			  <div class="input-group mb-3">
				<div class="input-group-prepend">
				  <span class="input-group-text">{{trans('forms.corp_financial.Label.sym')}}</span>
				</div>
				<input type="text" class="form-control number" name="total_cash_usd" placeholder="{{trans('forms.corp_financial.plc_holder.enter_value')}}" value="{{isset($financial)?$financial->total_cash:old('total_cash_usd')}}">
				<i style="color:red">{{$errors->first('total_cash_usd')}}</i>
			  </div>
			</div>
		  </div>
		</div>		
		
		<div class="row">
		  <div class="col-md-6">
			<div class="form-group inputborder-left">
			  <label for="pwd">{{trans('forms.corp_financial.Label.total_payables_usd')}}</label> <span class="mandatory">*<span>
			  <div class="input-group mb-3">
				<div class="input-group-prepend">
				  <span class="input-group-text">{{trans('forms.corp_financial.Label.sym')}}</span>
				</div>
				<input type="text" class="form-control number" name="total_payable_usd" placeholder="{{trans('forms.corp_financial.plc_holder.enter_value')}}" value="{{isset($financial)?$financial->total_payable_usd:old('total_payable_usd')}}">
				<i style="color:red">{{$errors->first('total_payable_usd')}}</i>
			  </div>
			</div>
		  </div>

		  	
			<div class="col-md-6">
				<div class="form-group inputborder-left">
			  <label for="pwd">{{trans('forms.corp_financial.Label.total_receivables_usd')}}</label> <span class="mandatory">*<span>
			  <div class="input-group mb-3">
				<div class="input-group-prepend">
				  <span class="input-group-text">{{trans('forms.corp_financial.Label.sym')}}</span>
				</div>
				<input type="text" class="form-control number"  name="total_recei_usd"placeholder="{{trans('forms.corp_financial.plc_holder.enter_value')}}" value="{{isset($financial)?$financial->total_receivables_usd:old('yearly_recei_usd')}}">
				<i style="color:red">{{$errors->first('total_recei_usd')}}</i>
			  </div>
			</div>
		  </div>
		</div>


		 <div class="row">
		  <div class="col-md-6">
			<div class="form-group inputborder-left">
			  <label for="pwd" class="error_msg">{{trans('forms.corp_financial.Label.total_salary_usd')}}</label> <span class="mandatory">*<span>
			  <div class="input-group mb-3">
				<div class="input-group-prepend">
				  <span class="input-group-text">{{trans('forms.corp_financial.Label.sym')}}</span>
				</div>
				<input type="text" class="form-control number" name="total_salary_usd" placeholder="{{trans('forms.corp_financial.plc_holder.enter_value')}}" value="{{isset($financial)?$financial->total_salary_usd:old('total_salary_usd')}}">
				<i style="color:red">{{$errors->first('total_salary_usd')}}</i>
			  </div>
			</div>
		  </div>

		  	
			<div class="col-md-6">
			<div class="form-group inputborder-left">
			  <label for="pwd" class="error_msg">{{trans('forms.corp_financial.Label.yearly_usd')}}</label> <span class="mandatory">*<span>
			  <div class="input-group mb-3">
				<div class="input-group-prepend">
				  <span class="input-group-text">{{trans('forms.corp_financial.Label.sym')}}</span>
				</div>
				<input type="text" class="form-control number" name="yearly_usd" placeholder="{{trans('forms.corp_financial.plc_holder.enter_value')}}" value="{{isset($financial)?$financial->yearly_usd:old('yearly_usd')}}">
				<i style="color:red">{{$errors->first('yearly_usd')}}</i>
			  </div>
			</div>
		  </div>
		</div>	




		<div class="row">
		  <div class="col-md-6">
			<div class="form-group inputborder-left">
			  <label for="pwd" class="error_msg">{{trans('forms.corp_financial.Label.yearly_profit_usd')}}</label> <span class="mandatory">*<span>
			  <div class="input-group mb-3">
				<div class="input-group-prepend">
				  <span class="input-group-text">{{trans('forms.corp_financial.Label.sym')}}</span>
				</div>
				<input type="text" class="form-control number" name="yearly_profit_usd" placeholder="{{trans('forms.corp_financial.plc_holder.enter_value')}}" value="{{isset($financial)?$financial->yearly_profit_usd:old('yearly_profit_usd')}}">
				<i style="color:red">{{$errors->first('yearly_profit_usd')}}</i>
			  </div>
			</div>
		  </div>

		  	
			<div class="col-md-6">
			<div class="form-group inputborder-left">
			  <label for="pwd" class="error_msg">{{trans('forms.corp_financial.Label.capital_company_usd')}}</label> <span class="mandatory">*<span>
			  <div class="input-group mb-3">
				<div class="input-group-prepend">
				  <span class="input-group-text">{{trans('forms.corp_financial.Label.sym')}}</span>
				</div>
				<input type="text" class="form-control number" name="capital_company_usd" placeholder="{{trans('forms.corp_financial.plc_holder.enter_value')}}" value="{{isset($financial)?$financial->capital_company_usd:old('capital_company_usd')}}">
				<i style="color:red">{{$errors->first('capital_company_usd')}}</i>
			  </div>
			</div>
		  </div>
		</div>	
		
		
		 
	<div class="row marT80 marB30">
        <div class="col-md-12 text-right">
		  
        @if($kycApproveStatus==0)
        <a href="{{$prev_url}}" class="btn btn-prev pull-left">{{trans('common.Button.pre')}}</a>	 
        {{ Form::submit(trans('common.Button.save'),['class'=>'btn btn-save','name'=>'save']) }}
        {{ Form::submit(trans('common.Button.save_next'),['class'=>'btn btn-save','name'=>'save_next']) }}

        @else
	<a href="{{$prev_url}}" class="btn btn-prev pull-left">{{trans('common.Button.pre')}}</a>
        <a href="{{$next_url}}" class="btn btn-save">{{trans('common.Button.next')}}</a>
        @endif
        </div>

	</div>
	
	 </form>
	  </div>
	</div>
	
   </div>	
  </div>
    <script>
 $(document).ready(function(){ 
    var disabledAll='{{$kycApproveStatus}}';
    if(disabledAll!=0){
        $("#financialform :input"). prop("disabled", true); 
    }
    });  
  </script>  
</section>


@include('frontend.company.companyscript')
@endsection