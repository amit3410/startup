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
                            <h3 class="h3-headline">{{trans('forms.corp_address_details.Label.heading')}}</h3>
                        </div>
                    </div>

                    
<form id="addressdetails" autocomplete="off" action="{{'company-address'}}"  class="needs-validation form" novalidate method="post">
 @csrf
<div class="row marT20">
    <div class="col-md-12">
        <div class="form-group">
            <label for="pwd">{{trans('forms.corp_address_details.Label.permanent_add')}}</label>
        </div>
    </div>
</div>

<div class="row">
<div class="col-md-4">
    <div class="form-group">
        <label for="pwd">{{trans('forms.corp_address_details.Label.country_id')}}</label> <span class="mandatory">*<span>

                {!!
                Form::select('country',
                [''=>'Select']+Helpers::getCountryDropDown()->toArray(),
                (isset($address->country_id) && !empty($address->country_id)) ? $address->country_id : (old('country_id')),
                array('id' => 'country_id','name' => 'country',
                'class'=>'form-control select2Cls'))
                !!}
                <i style="color:red">{{$errors->first('country')}}</i>
    </div>
</div>


<div class="col-md-4">
    <div class="form-group">
        <label for="pwd">{{trans('forms.corp_address_details.Label.city_id')}}</label> <span class="mandatory">*<span>
    <input type="text" name="city" id="city" class="form-control" 
    value="{{isset($address->city_id)?$address->city_id:old('city')}}" placeholder="{{trans('forms.corp_address_details.plc_holder.city_id')}}">

                <i style="color:red">{{$errors->first('city')}}</i>
    </div>

</div>
<div class="col-md-4">
    <div class="form-group">
        <label for="pwd">{{trans('forms.corp_address_details.Label.region')}}</label> <span class="mandatory">*<span>
        <input type="text" class="form-control" name="region" id="region" placeholder="{{trans('forms.corp_address_details.plc_holder.region')}}" value="{{isset($address->region)?$address->region:old('region')}}">
        <i style="color:red">{{$errors->first('region')}}</i>
    </div>
</div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="pwd">{{trans('forms.corp_address_details.Label.building')}}</label> <span class="mandatory">*<span>
            <input type="text" class="form-control" name="building" id="building" placeholder="{{trans('forms.corp_address_details.plc_holder.building_no')}}" placeholder="{{trans('forms.corp_address_details.plc_holder.floor_no')}}"
                   value="{{isset($address->building)?$address->building:old('building')}}">
            <i style="color:red">{{$errors->first('building')}}</i>
        </div>

    </div>

<div class="col-md-4">
    <div class="form-group">
        <label for="pwd">{{trans('forms.corp_address_details.Label.floor_no')}}</label> <span class="mandatory">*<span>
        <input type="text" class="form-control" name="floor" id="floor" placeholder="Enter Floor" value="{{isset($address->floor)?$address->floor:old('floor')}}">
        <i style="color:red">{{$errors->first('floor')}}</i>
    </div>
</div>
<div class="col-md-4">
    <div class="form-group">
        <label for="pwd">{{trans('forms.corp_address_details.Label.street')}}</label> <span class="mandatory">*<span>
                <input type="text" class="form-control" name="street" id="street" placeholder="{{trans('forms.corp_address_details.plc_holder.street_addr')}}" value="{{isset($address->street)?$address->street:old('street')}}">
        <i style="color:red">{{$errors->first('street')}}</i>
    </div>

</div>


</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="pwd">{{trans('forms.corp_address_details.Label.postal_code')}}</label>
            <input type="text" class="form-control" name="postalcode"id="postalcode"  placeholder="{{trans('forms.corp_address_details.plc_holder.postal_code')}}" value="{{isset($address->postal_code)?$address->postal_code:old('postalcode')}}" maxlength="10">
            <i style="color:red">{{$errors->first('postalcode')}}</i>
        </div>

    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="pwd">{{trans('forms.corp_address_details.Label.po_box')}}</label> 
            <input type="text" class="form-control number" name="pobox" id="pobox" placeholder="{{trans('forms.corp_address_details.plc_holder.post_box')}}" value="{{isset($address->po_box)?$address->po_box:old('pobox')}}">
            <i style="color:red">{{$errors->first('pobox')}}</i>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="pwd">{{trans('forms.corp_address_details.Label.email')}}</label> <span class="mandatory">*<span>
                    <input type="email" class="form-control" name="email" id="email" placeholder="{{trans('forms.corp_address_details.plc_holder.addr_email')}}" value="{{isset($address->email)?$address->email:old('email')}}">
                    <i style="color:red">{{$errors->first('email')}}</i>
                    </div>

                    </div>
                    </div>


            <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="pwd">{{trans('forms.corp_address_details.Label.country_code')}}</label> <span class="mandatory">*<span>
                                    @php
                                    $countrycode=Helpers::getCountryCode();
                                    @endphp

                            <select name="areacode" id="areacode" class="form-control select2Cls">
                                <option value="">{{trans('master.select')}}</option>
                                @foreach($countrycode as $code)
                                <option value="{{$code->phonecode}}"  {{ (@$address->area_code==$code->phonecode)? 'selected' : 'Select'}}>
                                    {{$code->country_name.' '.'+'.$code->phonecode,old('areacode')}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="pwd">{{trans('forms.corp_address_details.Label.telephone')}}</label> <span class="mandatory">*<span><br>
                                <input type="text" class="form-control" name="telephone" id="telephone" placeholder="{{trans('forms.corp_address_details.plc_holder.addr_phone_no')}}" value="{{isset($address->telephone)?$address->telephone:old('telephone')}}">
                                <i style="color:red">{{$errors->first('telephone')}}</i>
                        </div>
                    </div>
            </div>


            <div class="row">
               <div class="col-md-4">
               <div class="form-group">
               <label for="pwd">{{trans('forms.corp_address_details.Label.country_code')}}</label> <span class="mandatory">*<span>
               @php
               $countrycode=Helpers::getCountryCode();

               @endphp

               <select name="countrycode" id="countrycode" class="form-control select2Cls">
               <option value="">{{trans('master.select')}}</option>
               @foreach($countrycode as $code)

               <option value="{{$code->phonecode}}"  {{ (@$address->country_code==$code->phonecode)? 'selected' : 'Select'}}>{{$code->country_name.' (+'.$code->phonecode.')',old('countrycode')}}</option>

               @endforeach

               </select>
               <i style="color:red">{{$errors->first('countrycode')}}</i>
               </div>
               </div>
               <div class="col-md-4">
               <div class="form-group">
               <label for="pwd">{{trans('forms.corp_address_details.Label.mobile')}}</label> <span class="mandatory">*<span>
               <input type="text" class="form-control" name="mobile" id="mobile" placeholder="{{trans('forms.corp_address_details.plc_holder.addr_mobile_no')}}" value="{{isset($address->mobile)?$address->mobile:old('mobile')}}">
               <i style="color:red">{{$errors->first('mobile')}}</i>
               </div>

               </div>
            </div>
             
            <div class="row">
             
              <div class="col-md-4">
              <div class="form-group">
              <label for="pwd">{{trans('forms.corp_address_details.Label.country_code')}}</label> 
              <select name="fax_countrycode" id="fax_countrycode" class="form-control select2Cls">
              <option value="">{{trans('master.select')}}</option>
              @foreach($countrycode as $code)
              <option value="{{$code->phonecode}}"  {{ (@$address->fax_countrycode==$code->phonecode)? 'selected' : 'Select'}}> {{$code->country_name.' (+'.$code->phonecode.')',old('fax_countrycode')}}</option>
              @endforeach
              </select>
              </div>
              </div>

              <div class="col-md-4">
              <div class="form-group">
              <label for="pwd">{{trans('forms.corp_address_details.Label.addr_fax_no')}}</label> 
              <input type="text" class="form-control number" name="faxno" id="faxno" placeholder="{{trans('forms.corp_address_details.plc_holder.addr_fax_no')}}" value="{{isset($address->fax)?$address->fax:old('faxno')}}">
              <i style="color:red">{{$errors->first('faxno')}}</i>
              </div>
              </div>

           </div>


<div class="row marT20 marB5">
<div class="col-md-12">
<div class="form-group">
<label for="pwd">{{trans('forms.corp_address_details.Label.corres_add')}}</label>
</div>
</div>
</div>

<div class="row">
<div class="col-md-12">
<div class="form-group">
<div class="form-check-inline">
<label class="form-check-label" for="check2">
<input type="checkbox" class="form-check-input" id="check2" name="is_same_corp_corres_address" value="1" {{ (@$address->is_same_corp_corres_address == 1)?'checked':''}}>{{trans('forms.corp_address_details.Label.same_add')}}
</label>
</div>
</div>
</div>
</div>

<div class="row">
<div class="col-md-4">
<div class="form-group">
<label for="pwd">{{trans('forms.corp_address_details.Label.corre_country')}}</label> <span class="mandatory">*<span>

{!!
Form::select('country',
[''=>'Select']+Helpers::getCountryDropDown()->toArray(),
(isset($address->corre_country) && !empty($address->corre_country)) ? $address->corre_country : (old('country_id') ? old('country_id') : ''),
array('id' => 'corr_countryid','name' => 'corr_country',
'class'=>'form-control formempty'))
!!}

<i style="color:red">{{$errors->first('corr_country')}}</i>
</div>
</div>
<div class="col-md-4">
<div class="form-group">
<label for="pwd">{{trans('forms.corp_address_details.Label.corre_city')}}</label> <span class="mandatory">*<span>
<input type="text"  class="form-control formempty" name="corr_city" id="corr_city" value="{{isset($address->corre_city)?$address->corre_city:old('corr_city')}}" placeholder="{{trans('forms.corp_address_details.plc_holder.city_id')}}" />

<i style="color:red">{{$errors->first('corr_city')}}</i>
</div>
</div>
<div class="col-md-4">
<div class="form-group">
<label for="pwd">{{trans('forms.corp_address_details.Label.corre_region')}}</label> <span class="mandatory">*<span>
<input type="text" class="form-control formempty" name="corr_region" id="corr_region" placeholder="{{trans('forms.corp_address_details.plc_holder.region')}}" value="{{isset($address->corre_region)?$address->corre_region:old('corr_region')}}">
<i style="color:red">{{$errors->first('corr_region')}}</i>
</div>
</div>
</div>

<div class="row">
<div class="col-md-4">
<div class="form-group">
<label for="pwd">{{trans('forms.corp_address_details.Label.corre_building')}}</label> <span class="mandatory">*<span>
<input type="text" class="form-control formempty" name="corr_building" id="corr_building"  placeholder="{{trans('forms.corp_address_details.plc_holder.building_no')}}" value="{{isset($address->corre_building)?$address->corre_building:old('corr_building')}}">
<i style="color:red">{{$errors->first('corr_building')}}</i>
</div>
</div>
<div class="col-md-4">
<div class="form-group">
<label for="pwd">{{trans('forms.corp_address_details.Label.corre_floor')}}</label> <span class="mandatory">*<span>
<input type="text" class="form-control formempty"  name="corr_floor" id="corr_floor"   placeholder="{{trans('forms.corp_address_details.plc_holder.floor_no')}}"value="{{isset($address->corre_floor)?$address->corre_floor:old('corr_floor')}}">
<i style="color:red">{{$errors->first('corr_floor')}}</i>
</div>
</div>
<div class="col-md-4">
<div class="form-group">
<label for="pwd">{{trans('forms.corp_address_details.Label.corre_street')}}</label> <span class="mandatory">*<span>
<input type="text" class="form-control formempty" name="corr_street" id="corr_street" placeholder="{{trans('forms.corp_address_details.plc_holder.street_addr')}}" value="{{isset($address->corre_street)?$address->corre_street:old('corr_street')}}">
<i style="color:red">{{$errors->first('corr_street')}}</i>
</div>
</div>
</div>

<div class="row">
<div class="col-md-4">
<div class="form-group">
<label for="pwd">{{trans('forms.corp_address_details.Label.corre_postal_code')}}</label>
<input type="text" class="form-control formempty" name="corr_postal" id="corr_postal" placeholder="{{trans('forms.corp_address_details.plc_holder.postal_code')}}" value="{{isset($address->corre_postal_code)?$address->corre_postal_code:old('corr_postal')}}" maxlength="10">
<i style="color:red">{{$errors->first('corr_postal')}}</i>
</div>
</div>
<div class="col-md-4">
<div class="form-group">
<label for="pwd">{{trans('forms.corp_address_details.Label.corre_po_box')}}</label> 
<input type="text"  name="corr_pobox" id="corr_pobox" class="form-control number formempty"  placeholder="{{trans('forms.corp_address_details.plc_holder.post_box')}}" value="{{isset($address->corre_po_box)?$address->corre_po_box:old('corr_pobox')}}">
<i style="color:red">{{$errors->first('corr_pobox')}}</i>
</div>
</div>
</div>

<div class="row">
<div class="col-md-12">
<div class="form-group">
<label for="pwd">{{trans('forms.corp_address_details.Label.corre_email')}}</label> <span class="mandatory">*<span>
<input type="email" class="form-control formempty" name="corr_email" id="corr_email"  placeholder="{{trans('forms.corp_address_details.plc_holder.addr_email')}}" value="{{isset($address->corre_email)?$address->corre_email:old('corr_email')}}">
<i style="color:red">{{$errors->first('corr_email')}}</i>
</div>
</div>
</div>

<div class="row">

    <div class="col-md-4">
    <div class="form-group">
    <label for="pwd">{{trans('forms.corp_address_details.Label.country_code')}}</label> <span class="mandatory">*<span>
    <select name="corre_areacode" id="corre_areacode" class="form-control select2Cls formempty">
    <option value="">{{trans('master.select')}}</option>
    @foreach($countrycode as $code)
    <option value="{{$code->phonecode}}"  {{ (@$address->corre_area_code==$code->phonecode)? 'selected' : 'Select'}}>
    {{$code->country_name.' '.'+'.$code->phonecode,old('corre_areacode')}}</option>
    @endforeach
    </select>
    </div>
    </div>
    <div class="col-md-4">
    <div class="form-group">
    <label for="pwd">{{trans('forms.corp_address_details.Label.corre_telephone')}}</label> <span class="mandatory">*<span>
    <input type="text" name="corr_tele" id="corr_tele" class="form-control formempty"  placeholder="{{trans('forms.corp_address_details.plc_holder.addr_phone_no')}}" value="{{isset($address->corre_telephone)?$address->corre_telephone:old('corr_tele')}}">
    <i style="color:red">{{$errors->first('corr_tele')}}</i>
    </div>
    </div>

 
</div>

<div class="row">
    <div class="col-md-4">
    <div class="form-group">
    <label for="pwd">{{trans('forms.corp_address_details.Label.country_code')}}</label> <span class="mandatory">*<span>

    <select name="corre_countrycode" id="corre_countrycode" class="form-control select2Cls formempty">
    <option value="">{{trans('master.select')}}</option>
    @foreach($countrycode as $codes)
    <option value="{{$codes->phonecode}}" {{ (@$address->corre_country_code==$codes->phonecode)? 'selected' : 'Select'}}>{{$codes->country_name.' (+'.$codes->phonecode.')',old('corre_countrycode')}}</option>

    @endforeach

    </select>
    <i style="color:red">{{$errors->first('corre_countrycode')}}</i>
    </div>
    </div>
    <div class="col-md-4">
    <div class="form-group">
    <label for="pwd">{{trans('forms.corp_address_details.Label.corre_mobile')}}</label> <span class="mandatory">*<span>
    <input type="text" name="corr_mobile" id="corr_mobile" class="form-control formempty"  placeholder="{{trans('forms.corp_address_details.plc_holder.addr_mobile_no')}}" value="{{isset($address->corre_mobile)?$address->corre_mobile:old('corr_mobile')}}">
    <i style="color:red">{{$errors->first('corr_mobile')}}</i>
    </div>
    </div>
</div>
<div class="row">
     <div class="col-md-4">
    <div class="form-group">
    <label for="pwd">{{trans('forms.corp_address_details.Label.country_code')}}</label> 
    <select name="corre_fax_countrycode" id="corre_fax_countrycode" class="form-control select2Cls formempty">
    <option value="">{{trans('master.select')}}</option>
    @foreach($countrycode as $key=>$code)
    <option value="{{$code->phonecode}}"  {{ (@$address->corre_fax_countrycode==$code->phonecode)? 'selected' : 'Select'}}>
    {{$code->country_name.' '.'+'.$code->phonecode,old('corre_fax_countrycode')}}</option>
    @endforeach
    </select>
    </div>
    </div>

    <div class="col-md-4">
    <div class="form-group">
    <label for="pwd">{{trans('forms.corp_address_details.Label.corre_fax')}}</label> 
    <input type="text" name="corr_fax" id="corr_fax" class="form-control number formempty"  placeholder="{{trans('forms.corp_address_details.plc_holder.addr_fax_no')}}" value="{{isset($address->corre_fax)?$address->corre_fax:old('corr_fax')}}">
    <i style="color:red">{{$errors->first('corr_fax')}}</i>
    </div>
    </div>
</div>


<div class="row marT40">
<div class="col-md-12 text-right">


@if($kycApproveStatus==0)

 <a href="{{$prev_url}}" class="btn btn-prev pull-left">{{trans('common.Button.pre')}}</a>

{{ Form::submit(trans('common.Button.save'),['class'=>'btn btn-save','name'=>'save']) }}
 {{ Form::submit(trans('common.Button.save_next'),['class'=>'btn btn-save','name'=>'save_next']) }}
                 
                            @else
<a href="{{$prev_url}}" class="btn btn-prev">{{trans('common.Button.pre')}}</a>

                           <a href="{{$next_url}}" class="btn btn-save">Next</a>
                            @endif
</div>
</div>

</form>
</div>
</div>

</div>
</div>
</section>
                                                                                                                                                                    <script>
$(document).ready(function () {
   $('input.form-control').attr('autocomplete',true);//Autocomplete off 

    var disabledAll='{{$kycApproveStatus}}';
    if(disabledAll!=0){
        $("#addressdetails :input"). prop("disabled", true); 
    }

$('#check2').click(function () {

//$('#idCheckbox').prop('checked', true);
//$('#idCheckbox').prop('checked', false);
let checkboxval = $('#check2').val();
if ($(checkboxval == false))
{


$('#corr_region').val($('#region').val());
$('#corr_building').val($('#building').val());
$('#corr_floor').val($('#floor').val());
$('#corr_street').val($('#street').val());
$('#corr_pobox').val($('#pobox').val());
$('#corr_postal').val($('#postalcode').val());
$('#corr_email').val($('#email').val());
$('#corr_tele').val($('#telephone').val());
$('#corr_mobile').val($('#mobile').val());
$('#corr_fax').val($('#faxno').val());

let country = $('#country_id').val();

$('#corr_countryid').val(country, "selected");
$('#corr_city').val($('#city').val());

$('#check2').val('1');
$('#check2').prop('checked', true);

$('#corre_areacode').val($('#areacode').val());
$('#corre_countrycode').val($('#countrycode').val());

$('#corre_fax_countrycode').val($('#fax_countrycode').val());

}
if (checkboxval == true)
{

$('#check2').val('0');
$('.formempty').val("");
$('#check2').prop('checked', false);
}


})


});



</script>

<script src="{{ asset('frontend/outside/js/validation/corporatevalidation.js') }}"></script>

@include('frontend.company.companyscript')

@endsection