@extends('layouts.app')

@section('content')

<section>
    <div class="container">
        <div class="row">
            <div id="header" class="col-md-3">
                @include('layouts.user-inner.left-menu')
            </div>
            <div class="col-md-9 dashbord-white">
                <div class="form-section">
                    <div class="row marB10">
                        <div class="col-md-12">
                            <h3 class="h3-headline">For Sole Proprietorship/Self Employed, Please Specify</h3>
                        </div>
                    </div>   
 

                      

                    {!!
                    Form::open(
                    array(
                    'name' => 'commercialInformationForm',
                    'id' => 'commercialInformationForm',
                    'url' => route('commercial_information',['id'=>@$userData['user_kyc_propr_id'],'user_kyc_id'=>@$benifinary['user_kyc_id'],'corp_user_id'=>@$benifinary['corp_user_id'],'is_by_company'=>@$benifinary['is_by_company'],'buss_addr_id' => isset($bussData['buss_addr_id']) ? $bussData['buss_addr_id'] : null]),
                    'autocomplete' => 'off','class'=>'loginForm form form-cls'
                    ))
                    !!}
                    
                  

                  @if(Auth()->user()->user_type == 2)
                    <div class="row mb-3">
                      <div class="col-md-12">
                       
                        <input type='checkbox' name='populatedata_checkbox'  id='populatedata_checkbox' value="{{isset($userData['same_business'])?1:''}}"><span class="h3-headline"> Same Business Details as KYC</span>
                        </div>
                    </div>
                    @endif
                    

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {{Form::label('comm_name','Commercial Name',['class'=>''])}} <span class="mandatory">*<span>
                            {{Form::text('comm_name',@$userData['comm_name'],['class'=>'form-control','id'=>'comm_name','placeholder'=> trans('forms.commercial_Info.plc_holder.comm_name') ]) }}
                                <span class="text-danger">{{ $errors->first('comm_name') }}</span>
                            </div>
                        </div>

                    </div>
                        <?php
                        $date_of_establish  =   (@$userData['date_of_establish']!='' && @$userData['date_of_establish']!=null) ? Helpers::getDateByFormat(@$userData['date_of_establish'], 'Y-m-d', 'd/m/Y') :'';
                        ?>
                        <!-- Signup date of Establishment-->
                        <?php
                        $regis_date_of_establish  =   (@$corpRegis->corp_date_of_formation!='' && @$corpRegis->corp_date_of_formation!=null) ? Helpers::getDateByFormat(@$corpRegis->corp_date_of_formation, 'Y-m-d', 'd/m/Y') :'';
                        ?>
                        <input type="hidden" id="signup_date_establish" value='{{$regis_date_of_establish}}'>
                        <!-- Signup date of Establishment-->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">

                                {{Form::label('date_of_establish','Date of Establishment',['class'=>''])}} <span class="mandatory">*<span>
                                <div class="input-group">

                                    {{Form::text('date_of_establish',$date_of_establish,['class'=>'form-control datepicker','id'=>'date_of_establish','placeholder'=>'Select Date'])}}
                                    <div class="input-group-append">
                                        <i class="fa fa-calendar-check-o"></i>
                                    </div>
                                </div>
                                <span class="text-danger">{{ $errors->first('date_of_establish') }}</span>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">

                                {{Form::label('country_establish_id','Country of Establishment',['class'=>''])}} <span class="mandatory">*<span>
                                {!!
                                Form::select('country_establish_id',
                                [''=>'Select']+Helpers::getCountryDropDown()->toArray(),
                                @$userData['country_establish_id'],
                                array('id' => 'country_establish_id','class'=>'form-control select2Cls'))
                                !!}
                                <span class="text-danger">{{ $errors->first('country_establish_id') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="form-group">

                                    {{Form::label('comm_reg_no','Commercial Register No.',['class'=>''])}}<span class="mandatory">*<span>
                                    {{Form::text('comm_reg_no',@$userData['comm_reg_no'],['class'=>'form-control','id'=>'comm_reg_no','placeholder'=> trans('forms.commercial_Info.plc_holder.country_establish_id')])}}
                                    <span class="text-danger">{{ $errors->first('comm_reg_no') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="form-group">
                                    {{Form::label('comm_reg_place','Commercial Register Place',['class'=>''])}}
                                    {{Form::text('comm_reg_place',@$userData['comm_reg_place'],['class'=>'form-control','id'=>'comm_reg_place','placeholder'=>trans('forms.commercial_Info.plc_holder.comm_reg_place')])}}
                                    <span class="text-danger">{{ $errors->first('comm_reg_place') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                
                                {{Form::label('comm_country_id','Commercial Register Country',['class'=>''])}}<span class="mandatory">*<span>
                                {{
                                Form::select('comm_country_id',[''=>'Select Country']+Helpers::getCountryDropDown()->toArray(),@$userData['comm_country_id'],['class'=>'form-control'])
                                }}
                                <span class="text-danger">{{ $errors->first('comm_country_id') }}</span>
                            </div>
                        </div>
                    </div>	
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-select-field custom-validation">
                              @php
                              $arrarVal=[];
                              $sourceDropVal=Helpers::getCountryDropDown();

                              if(isset($userData['country_activity'])) {
                              $arrarVal = explode(',',$userData['country_activity']);
                              }
                              else {
                              $arrarVal=[];

                              }
                              @endphp
                             {{Form::label('country_activity','Country of Activity',['class'=>''])}} <span class="mandatory">*<span>
                              <select multiple="multiple" name="country_activity[]" id="country_activity" class='form-control multiselect_dropdown'>
                              @foreach($sourceDropVal as $Dval)

                              @if(in_array($Dval, $arrarVal))

                              <option value="{{$Dval}}" selected="">{{$Dval}}</option>
                              @else
                              <option value="{{$Dval}}">{{$Dval}}</option>

                              @endif     
                              @endforeach    
                              </select>
                              <span class="text-danger">{{ $errors->first('country_activity') }}</span>
                              </div>
                        </div>
                    </div>

                    <!-- <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                
                                {{Form::label('country_activity','Country of Activity',['class'=>''])}} <span class="mandatory">*<span>
                                {{
                                Form::select('country_activity',[''=>'Select Country']+Helpers::getCountryDropDown()->toArray(),@$userData['country_activity'],['class'=>'form-control','id'=>'country_activity'])
                                }}
                                <span class="text-danger">{{ $errors->first('country_activity') }}</span>
                            </div>
                        </div>
                    </div>	 --> 

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">   
                            {{Form::label('syndicate_no','Syndicate No.',['class'=>''])}}
                            {{Form::text('syndicate_no',@$userData['syndicate_no'],['class'=>'form-control','id'=>'syndicate_no','placeholder'=>'Enter Syndicate No.'])}}
                            <span class="text-danger">{{ $errors->first('syndicate_no') }}</span>
                            </div>
                        </div>
                    </div>	 

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="pwd"></label>
                                 
                                {{Form::label('taxation_no','Taxation ID No.',['class'=>''])}} <span class="mandatory">*<span>
                                {{Form::text('taxation_no',@$userData['taxation_no'],['class'=>'form-control','id'=>'taxation_no','placeholder'=>'Enter Taxation ID No.'])}}
                                <span class="text-danger">{{ $errors->first('taxation_no') }}</span>
                                </div>
                            </div>
                        </div>
                        
                    </div>		 

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                
                                {{Form::label('annual_turnover','Annual Business Turnover (in $)',['class'=>''])}} <span class="mandatory">*<span>
                                {{Form::text('annual_turnover',@$userData['annual_turnover'],['class'=>'form-control','id'=>'annual_turnover','placeholder'=>'Enter Annual Business Turnover (in $)'])}}
                                <span class="text-danger">{{ $errors->first('annual_turnover') }}</span>
                            </div>
                        </div>
                    </div>	 

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {{Form::label('main_suppliers','Main Suppliers',['class'=>''])}}
                                {{Form::text('main_suppliers',@$userData['main_suppliers'],['class'=>'form-control','id'=>'main_suppliers','placeholder'=>'Enter Main Suppliers'])}}
                                <span class="text-danger">{{ $errors->first('main_suppliers') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                
                                {{Form::label('main_clients','Main Clients',['class'=>''])}}
                                {{Form::text('main_clients',@$userData['main_clients'],['class'=>'form-control','id'=>'main_clients','placeholder'=>'Enter Main Clients'])}}
                                <span class="text-danger">{{ $errors->first('main_clients') }}</span>
                            </div>
                        </div>
                    </div>	 

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                
                                {{Form::label('authorized_signatory','Name of authorized signatory',['class'=>''])}} <span class="mandatory">*<span>
                                {{Form::text('authorized_signatory',@$userData['authorized_signatory'],['class'=>'form-control','id'=>'authorized_signatory','placeholder'=>'Enter Name of authorized signatory'])}}
                                <span class="text-danger">{{ $errors->first('authorized_signatory') }}</span>
                            </div>
                        </div>
                    </div>	 

                    <div class="row marT25 marB10">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="pwd"><b>Business Address</b></label>
                                
                            </div>
                        </div>
                    </div>	 

                    

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                
                                {{Form::label('buss_country_id','Country',['class'=>''])}}<span class="mandatory">*<span>
                                {{
                                Form::select('buss_country_id',[''=>'Select Country']+Helpers::getCountryDropDown()->toArray(),@$bussData['buss_country_id'],['class'=>'form-control','id'=>'buss_country_id'])
                                }}
                                <span class="text-danger">{{ $errors->first('buss_country_id') }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                              
                                {{Form::label('buss_city_id','City',['class'=>''])}}<span class="mandatory">*<span>
                                
                                {{Form::text('buss_city_id',@$bussData['buss_city_id'],['class'=>'form-control','id'=>'buss_city_id','Placeholder'=> trans('forms.commercial_Info.plc_holder.buss_city_id')])}}
                                <span class="text-danger">{{ $errors->first('buss_city_id') }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                
                                {{Form::label('buss_region','Region',['class'=>''])}}<span class="mandatory">*<span>
                                {{Form::text('buss_region',@$bussData['buss_region'],['class'=>'form-control','id'=>'buss_region','placeholder'=> trans('forms.commercial_Info.plc_holder.buss_region')])}}
                                <span class="text-danger">{{ $errors->first('buss_region') }}</span>
                            </div>
                        </div>

                    </div>	

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                
                                {{Form::label('buss_building','Building',['class'=>''])}}<span class="mandatory">*<span>
                                {{Form::text('buss_building',@$bussData['buss_building'],['class'=>'form-control','id'=>'buss_building','placeholder'=>trans('forms.commercial_Info.plc_holder.buss_building')])}}
                                <span class="text-danger">{{ $errors->first('buss_building') }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                
                                
                                {{Form::label('buss_floor','Floor/Office',['class'=>''])}}<span class="mandatory">*<span>
                                {{Form::text('buss_floor',@$bussData['buss_floor'],['class'=>'form-control','id'=>'buss_floor','Placeholder'=>trans('forms.commercial_Info.plc_holder.buss_floor')])}}
                                <span class="text-danger">{{ $errors->first('buss_floor') }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                               
                                
                                {{Form::label('buss_street','Street',['class'=>''])}}<span class="mandatory">*<span>
                                {{Form::text('buss_street',@$bussData['buss_street'],['class'=>'form-control','id'=>'buss_street','Placeholder'=>trans('forms.commercial_Info.plc_holder.buss_street')])}}
                                <span class="text-danger">{{ $errors->first('buss_street') }}</span>
                            </div>
                        </div>

                    </div>	

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                
                                
                                {{Form::label('buss_postal_code','Postal Code',['class'=>''])}}
                                {{Form::text('buss_postal_code',@$bussData['buss_postal_code'],['class'=>'form-control','id'=>'buss_postal_code','placeholder'=>trans('forms.commercial_Info.plc_holder.buss_postal_code')])}}
                                <span class="text-danger">{{ $errors->first('buss_postal_code') }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                              
                                {{Form::label('buss_po_box_no','P.O Box',['class'=>''])}}
                                {{Form::text('buss_po_box_no',@$bussData['buss_po_box_no'],['class'=>'form-control','id'=>'buss_po_box_no','placeholder'=>trans('forms.commercial_Info.plc_holder.buss_po_box_no')])}}
                                <span class="text-danger">{{ $errors->first('buss_po_box_no') }}</span>
                            </div>
                        </div>
                    </div>	

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                
                                {{Form::label('buss_email','Email Address',['class'=>''])}}<span class="mandatory">*<span>
                                {{Form::email('buss_email',@$bussData['buss_email'],['class'=>'form-control','id'=>'buss_email','placeholder'=>trans('forms.commercial_Info.plc_holder.buss_email')])}}
                                <span class="text-danger">{{ $errors->first('buss_email') }}</span>
                            </div>
                        </div>
                    </div>	

                    <div class="row">


                        <div class="col-md-2">
                            <div class="form-group">
                            <label for="uname">{{trans('master.country_code')}} </label> <span class="mandatory">*<span>
                            @php
                            $countrycode=Helpers::getCountryCode();
                            @endphp

                            <select name="country_code_phone" id='country_code_phone' class="form-control select2Cls">
                                <option value="">{{trans('master.select')}}</option>
                                @foreach($countrycode as $code)

                        <option value="{{$code->phonecode}}" {{ (@$bussData['country_code_phone']==$code->phonecode)? 'selected' : 'Select'}}>{{$code->country_name.'(+'.$code->phonecode. ')'}}</option>
                                @endforeach
                               </select>
                                <span class="text-danger">{{ $errors->first('country_code') }}</span>
                            </div>
                        </div>





                        <div class="col-md-4">
                            <div class="form-group">
                           
                                {{Form::label('buss_telephone_no','Telephone Number',['class'=>''])}} <span class="mandatory">*<span>
                                {{Form::text('buss_telephone_no',@$bussData['buss_telephone_no'],['class'=>'form-control','id'=>'buss_telephone_no','placeholder'=>trans('forms.commercial_Info.plc_holder.buss_telephone_no') ,'maxlength'=>'20'])}}
                                <span class="text-danger">{{ $errors->first('buss_telephone_no') }}</span>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                            <label for="uname">{{trans('master.country_code')}} </label> <span class="mandatory">*<span>
                            @php
                            $countrycode=Helpers::getCountryCode();
                            @endphp
                                
                            <select name="country_code" id='country_code' class="form-control select2Cls">
                                <option value="">{{trans('master.select')}}</option>
                                @foreach($countrycode as $code)
                        <option value="{{$code->phonecode}}" {{ (@$bussData['buss_country_code']==$code->phonecode)? 'selected' : 'Select'}}>{{$code->country_name.'(+'.$code->phonecode. ')'}}</option>
                        @endforeach
                               </select>
                                <span class="text-danger">{{ $errors->first('country_code') }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                
                                {{Form::label('buss_mobile_no','Mobile Number',['class'=>''])}} <span class="mandatory">*<span>
                                {{Form::text('buss_mobile_no',@$bussData['buss_mobile_no'],['class'=>'form-control','id'=>'buss_mobile_no','placeholder'=>trans('forms.commercial_Info.plc_holder.buss_mobile_no'),'maxlength'=>'20'])}}
                                <span class="text-danger">{{ $errors->first('buss_mobile_no') }}</span>
                            </div>
                        </div>



                        <div class="col-md-2">
                            <div class="form-group">
                            <label for="uname">{{trans('master.country_code')}} </label> 
                            @php
                            $countrycode=Helpers::getCountryCode();
                            @endphp

                            <select name="country_code_fax" id='country_code_fax' class="form-control select2Cls">
                                <option value="">{{trans('master.select')}}</option>
                                @foreach($countrycode as $code)
                                    <option value="{{$code->phonecode}}" {{ (@$bussData['country_code_fax']==$code->phonecode)? 'selected' : 'Select'}}>{{$code->country_name.'(+'.$code->phonecode. ')'}}</option>
                                @endforeach
                               </select>
                                <span class="text-danger">{{ $errors->first('country_code') }}</span>
                            </div>
                        </div>



                        <div class="col-md-3">
                            <div class="form-group">
                                
                                {{Form::label('buss_fax_no','Fax Number',['class'=>''])}}
                                {{Form::text('buss_fax_no',@$bussData['buss_fax_no'],['class'=>'form-control','id'=>'buss_fax_no','placeholder'=> trans('forms.commercial_Info.plc_holder.buss_fax_no'),'maxlength'=>'20'])}}
                                <span class="text-danger">{{ $errors->first('buss_fax_no') }}</span>
                            </div>
                        </div>
                    </div>	
                    

                    <div class="row marT25 marB10">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="pwd"><b>Mailing Address</b></label>	
                            </div>
                        </div>
                    </div>			 

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                
                                
                                {{Form::label('is_hold_mail','Hold Mail',['class'=>''])}}
            {{Form::select('is_hold_mail',[''=>'select','1'=>'Yes','0'=>'No'],@$userData['is_hold_mail'],['class'=>'form-control','id'=>'is_hold_mail'])}}
                                <span class="text-danger">{{ $errors->first('is_hold_mail') }}</span>
                            </div>
                        </div>
                    </div>	

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                               
                                {{Form::label('mailing_address','In case of sending documents through mail, please specify mailing address',['class'=>''])}}
                                {{Form::select('mailing_address',[''=>'select','1'=>'Residential Address','2'=>'Secondary Address','3'=>'Business Address'],@$userData['mailing_address'],['class'=>'form-control','id'=>'mailing_address'])}}
                                <span class="text-danger">{{ $errors->first('mailing_address') }}</span>
                            </div>
                        </div>
                    </div>		 

                    <div class="row marT25">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="pwd"></label>
                                {{Form::label('','Relation with Exchange Company/ Establishment')}}
                                <p class="text-color font-normal marT15">Are you or your spouse or any of your dependents (ascendants and descendants) the owner or shareholder or partner or director or signatory of an exchange establishment/ company? If yes please disclose the full names of the concerned parties and the full name and details of the establishment / company</p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                
                                
                                {{Form::select('relation_exchange_company',[''=>'select','1'=>'Yes','0'=>'No'],@$userData['relation_exchange_company'],['class'=>'form-control','id'=>'relation_exchange_company'])}}
                                <span class="text-danger">{{ $errors->first('relation_exchange_company') }}</span>
                            </div>	
                        </div>		  
                    </div>			 
                    <div class="row relation-yes">
                        <div class="col-md-12">
                            <div class="form-group">
                                
                                {{Form::label('concerned_party','Name of Concerned Party',['class'=>''])}}
                                {{Form::textarea('concerned_party',@$userData['concerned_party'],['class'=>'form-control','id'=>'concerned_party','placeholder'=>'Enter Name of Concerned Party','rows'=>'3'])}}
                                <span class="text-danger">{{ $errors->first('concerned_party') }}</span>
                            </div>
                        </div>
                    </div>	 
                    <div class="row relation-yes">
                        <div class="col-md-12">
                            <div class="form-group">

                                {{Form::label('details_of_company','Name/Details of Establishment/Company',['class'=>''])}}
                                {{Form::textarea('details_of_company',@$userData['details_of_company'],['class'=>'form-control','id'=>'details_of_company','placeholder'=>'Enter Name/Details of Establishment/Company','rows'=>'3'])}}
                                <span class="text-danger">{{ $errors->first('details_of_company') }}</span>
                            </div>
                        </div>
                    </div> 

                    <div class="row marT60">
                        <div class="col-md-12 text-right">
                           <a href="{{$benifinary['prev_url']}}" class="btn btn-prev pull-left">Previous</a>          

                            @if($kycApproveStatus==0)
                            {{ Form::submit('Save',['class'=>'btn btn-save','name'=>'save']) }}
                            {{ Form::submit('Save & Next',['class'=>'btn btn-save','name'=>'save_next']) }}

                            @else
                        
                            <a href="{{$benifinary['next_url']}}" class="btn btn-save">Next</a>
                            @endif
                            
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>

        </div>	
    </div>
</section>

<!--models-->
<div class="modal model-popup" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <h4 class="headline-h4 marB15">Dear Applicant;</h4>
                <p>Welcome to the Compliance platform of Dexter Capital Financial Consultancy LLC. </p>

                <p> According to the United Arab Emirates rules and regulations and the International applicable laws, you are kindly requested to proceed with the due diligence application allowing you to validate your profile and access many financial platforms.</p>
                <p> Dexter Capital Financial Consultancy LLC being regulated by Securities and Commodities Authority in the UAE, is committed to maintain all your information confidential and highly protected by the most sophisticated security tools and is in full compliance with the requirements of the European Union related to the General Data Protection Regulation (GDPR). <a href="https://ec.europa.eu/info/law/law-topic/data-protection/data-protection-eu_en" target="_blank"> https://ec.europa.eu/info/law/law-topic/data-protection/data-protection-eu_en</a></p>
            </div>

        </div>
    </div>
</div>

@endsection

@section('pageTitle')
Proprietorship Information
@endsection
@section('additional_css')
<link href="{{ asset('frontend/inside/plugin/datepicker/jquery-ui.css') }}" rel="stylesheet">
<link href="{{ asset('frontend/multiselect/jquery.multiselect.css')}}" rel="stylesheet" />
@endsection
@section('jscript')
<script src="{{ asset('frontend/multiselect/jquery.multiselect.js') }}"></script>
<script src="{{ asset('frontend/inside/plugin/datepicker/jquery-ui.js') }}"></script>
<script src="{{ asset('frontend/outside/js/validation/comercialInfoForm.js')}}"></script>

<script>
$(document).ready(function () {
    var disabledAll='{{$kycApproveStatus}}';
    if(disabledAll!=0){
        $("#commercialInformationForm :input"). prop("disabled", true); 
    }
    
    // $('.datepicker').datepicker();
    $('.datepicker').datepicker({dateFormat: 'dd/mm/yy', maxDate: new Date(), changeMonth: true, changeYear: true});
    if($("#relation_exchange_company").val()=='1'){
        $('.relation-yes').show();
    }else{
        $('.relation-yes').hide();
    }
    $("#relation_exchange_company").on('change',function(){
        if($(this).val()=='1'){
            $('.relation-yes').show();
        }else{
            $('.relation-yes').hide();
        }
    });


    $(function () {
        $('.multiselect_dropdown').multiselect({
            placeholder: 'Select',
            search: true,
            searchOptions: {
                'default': 'Search'
            },
            selectAll: true
        });


    });

    $('#populatedata_checkbox').click(function()
    {

        let checkboxval = $('#populatedata_checkbox').val();
       if(checkboxval == false) 
        {
            
            
            $('#comm_name').val('<?php echo isset($corpRegis->corp_name)?$corpRegis->corp_name:''; ?>');
            $('#country_establish_id').val('<?php echo isset($corpRegis->country_id)?$corpRegis->country_id:''; ?>', "selected");
               
        
            $('#date_of_establish').val($('#signup_date_establish').val());
          $('#comm_country_id').val('<?php echo isset($corpRegis->country_id)?$corpRegis->country_id:''; ?>', "selected");
          $('#comm_reg_no').val('<?php echo isset($companyprofile->corp_license_number)?$companyprofile->corp_license_number:''; ?>');
            // For Business
            $('#buss_country_id').val('<?php echo isset($address->country_id)?$address->country_id:''; ?>', "selected");
            $('#buss_city_id').val('<?php echo isset($address->city_id)?$address->city_id:'';?>');
            $('#buss_region').val('<?php echo isset($address->region)?$address->region:'';?>');
            $('#buss_building').val('<?php echo isset($address->building)?$address->building:'';?>');
            $('#buss_floor').val('<?php echo isset($address->floor)?$address->floor:'';?>');
            $('#buss_street').val('<?php echo isset($address->street)?$address->street:'';?>');
            $('#buss_postal_code').val('<?php echo isset($address->postal_code)?$address->postal_code:'';?>');
            $('#buss_po_box_no').val('<?php echo isset($address->po_box)?$address->po_box:'';?>');
            $('#buss_email').val('<?php echo isset($address->email)?$address->email:'';?>');

            $('#country_code_phone').val('<?php echo isset($address->area_code)?$address->area_code:'';?>', "selected");
            $('#buss_telephone_no').val('<?php echo isset($address->telephone)?$address->telephone:'';?>');

            $('#country_code').val('<?php echo isset($address->country_code)?$address->country_code:'';?>', "selected");
            $('#buss_mobile_no').val('<?php echo isset($address->mobile)?$address->mobile:'';?>');

            $('#country_code_fax').val('<?php echo isset($address->fax_countrycode)?$address->fax_countrycode:'';?>', "selected");
            $('#buss_fax_no').val('<?php echo isset($address->fax)?$address->fax:'';?>');

             $('#populatedata_checkbox').val('1');
             $('#populatedata_checkbox').prop('checked', true);
            
        }
        

    })

      /* checkbox checked show  */
    if($('#populatedata_checkbox').val()=='1') {
      $('#populatedata_checkbox').prop('checked', true);
     
    }
    /* checkbox checked show  */




});


</script>
@endsection
