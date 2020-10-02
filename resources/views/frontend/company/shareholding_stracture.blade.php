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

                    <?php
                   
                    ?>
                    {!!
                    Form::open(
                    array(
                    'name' => 'shareholderForm',
                    'id' => 'shareholderFormAjax',
                    'autocomplete' => 'true','class'=>'needs-validation form'
                    ))
                    !!}

                    @if(is_array($nextShare) && count($nextShare))
                    <?php
                    $cnt=0;
                    foreach ($nextShare as $obj) {
                        
                        ?>  
                        <div class="row marB10">
                            <div class="col-md-12">
                                <h3 class="h3-headline">{{trans('forms.sharehoding_str.Label.heading')}} - {{$obj['company_name']}} [{{$obj['passport_no']}}]</h3>
                            </div>
                        </div>

                        {{Form::hidden('share_parent_id[]',$obj['corp_shareholding_id'])}}
                        {{Form::hidden('LicenseNo_'.$obj['corp_shareholding_id'],$obj['passport_no'])}}
                        {{Form::hidden('share_level[]',($obj['share_level']+1))}}

                        <div id="childInfo_{{$cnt}}" class="is_child">


                            <div class="alertMsgBox"  id="msgBlockError_{{$cnt}}"></div> 
                            <div class="clonedclonedSocialmedias-{{$cnt}} Shareholding-cls marB40 padB30 border-bottom" id="clonedSocialmedias{{$cnt}}_0">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                                                
                                            {{ Form::label('shareType'.$obj['corp_shareholding_id'].'_0', trans('forms.sharehoding_str.Label.lbl_ind_corp'), array('class' => ''))}} <span class="mandatory">*<span>
                                            {!!
                                            Form::select('shareType'.$cnt.'_0',
                                            [''=>'Select Individual/company','1'=>'Individual','2'=>'Company'],'',
                                            array('id'=>'shareType'.$cnt.'_0','class'=>'form-control'))
                                            !!}
                                        
                                            <span class="text-danger" id="errorshareType{{$obj['corp_shareholding_id']}}_0"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">

                                            {{ Form::label('companyName'.$cnt.'_0', trans('forms.sharehoding_str.Label.company_name'), array('class' => ''))}} <span class="mandatory">*<span>
                                            {{ Form::text('companyName'.$cnt.'_0','', ['class' => 'form-control','placeholder'=>trans('forms.sharehoding_str.plc_holder.enter_name'),'id'=>'companyName'.$cnt.'_0']) }}
                                            <span class="text-danger" id="errorcompanyName{{$cnt}}_0"></span>
                                        </div>
                                    </div>
                                </div>					

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">

                                            {{ Form::label('passportNo'.$cnt.'_0', trans('forms.sharehoding_str.Label.passport_no'), array('class' => ''))}} <span class="mandatory">*<span>
                                            {{ Form::text('passportNo'.$cnt.'_0','', ['class' => 'form-control','placeholder'=>trans('forms.sharehoding_str.plc_holder.enter_passport_no'),'id'=>'passportNo'.$cnt.'_0']) }}
                                            <span class="text-danger" id="errorpassportNo{{$cnt}}_0"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">

                                            {{ Form::label('sharePercentage'.$cnt.'_0', trans('forms.sharehoding_str.Label.share_percentage'), array('class' => ''))}} <span class="mandatory">*<span>
                                            {{ Form::text('sharePercentage'.$cnt.'_0','', ['class' => 'form-control shareper','placeholder'=>trans('forms.sharehoding_str.plc_holder.enter_percent'),'data'=>'','id'=>'sharePercentage'.$cnt.'_0']) }}
                                            <span class="text-danger" id="errorsharePercentage{{$cnt}}_0"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group inputborder-left postion-delete-icon">
                                            {{ Form::label('shareValue'.$cnt.'_0',trans('forms.sharehoding_str.Label.share_value'), array('class' => ''))}} <span class="mandatory">*<span>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input type="text" class="form-control sharevalue" name="shareValue{{$cnt}}_0" placeholder="{{trans('forms.sharehoding_str.plc_holder.enter_value')}}" data="" id="shareValue{{$cnt}}_0">
                                            </div>
                                            <span class="text-danger" id="errorshareValue{{$cnt}}_0"></span>
                                           <div class="deleteSkill-{{$cnt}} deleteSkillbtn remove" data="{{$cnt}}" style="display: none;"><i class="fa fa-trash-o" title="Remove" aria-hidden="true"></i></div>
                                        </div>
                                    </div>
                                   
                                </div>	
                            </div>	
                            {{Form::hidden('rows'.$cnt.'','1')}}
                            <div class="row marT20 marB5">
                                <div class="col-md-12">
                                    <span class="add-socialmedia addshare-{{$cnt}} pull-right marT10 text-color" data="{{$cnt}}" data-row="1"  style="">{{trans('forms.sharehoding_str.Label.add_sharehonder')}}</span>
                                </div>
                            </div>
                        </div>
                        <?php
                        $cnt++;
                    }
                    ?>
                    {{Form::hidden('nexted',$cnt)}}    
                    @else 
                    <div class="row marB10">
                        <div class="col-md-12">
                            <h3 class="h3-headline">{{trans('forms.sharehoding_str.Label.heading')}}</h3>
                        </div>
                    </div>
                    {{Form::hidden('share_parent_id[]','0')}}
                    {{Form::hidden('share_level[]','0')}}
                     {{Form::hidden('LicenseNo_0','0')}}
                    <div id="childInfo_0" class="is_child">
                        <div class="alertMsgBox"  id="msgBlockError_0"></div> 
                        <div id="clonedSocialmedias0_0"  class="clonedclonedSocialmedias-0 Shareholding-cls marB40 padB30 border-bottom">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">

                                        {{ Form::label('shareType0_0', trans('forms.sharehoding_str.Label.lbl_ind_corp'), array('class' => ''))}}  <span class="mandatory">*<span>
                                        {!!
                                        Form::select('shareType0_0',
                                        [''=> trans('forms.sharehoding_str.plc_holder.select_type'),'1'=>'Individual','2'=>'Company'],'',
                                        array('class'=>'form-control'))
                                        !!}

                                    <span class="text-danger" id="errorshareType0_0"></span>  
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">

                                        {{ Form::label('companyName0_0', trans('forms.sharehoding_str.Label.company_name'), array('class' => ''))}} <span class="mandatory">*<span>
                                        {{ Form::text('companyName0_0','', ['class' => 'form-control','placeholder'=> trans('forms.sharehoding_str.plc_holder.enter_name'),'id'=>'companyName0_0']) }}
                                        <span class="text-danger" id="errorcompanyName0_0"></span> 
                                    </div>
                                </div>
                            </div>					

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">

                                        {{ Form::label('passportNo0_0', trans('forms.sharehoding_str.Label.passport_no'), array('class' => ''))}} <span class="mandatory">*<span>
                                        {{ Form::text('passportNo0_0','', ['class' => 'form-control','placeholder'=> trans('forms.sharehoding_str.plc_holder.enter_passport_no'),'id'=>'passportNo0_0']) }}
                                        <span class="text-danger" id="errorpassportNo0_0"></span> 
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">

                                        {{ Form::label('sharePercentage0_0', trans('forms.sharehoding_str.Label.share_percentage'), array('class' => ''))}} <span class="mandatory">*<span>
                                        {{ Form::text('sharePercentage0_0','', ['class' => 'form-control share-per-0 shareper','placeholder'=> trans('forms.sharehoding_str.plc_holder.enter_percent'),'id'=>'sharePercentage0_0','data'=>'0']) }}
                                        <span class="text-danger" id="errorsharePercentage0_0"></span> 
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group inputborder-left postion-delete-icon">
                                        {{ Form::label('shareValue0_0',trans('forms.sharehoding_str.Label.share_value'), array('class' => ''))}} <span class="mandatory">*<span>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="text" class="form-control sharevalue" name="shareValue0_0" id="shareValue0_0" data="0" placeholder="{{trans('forms.sharehoding_str.plc_holder.enter_value')}}">
                                            
                                        </div>
                                        <span class="text-danger" id="errorshareValue0_0"></span> 
                                         <div class="deleteSkill-0 deleteSkillbtn remove ggg" data="0" style="display: none;">
                                        <i class="fa fa-trash-o" title="Remove" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>	
                        </div>	

                        <div class="row marT20 marB5">
                            <div class="col-md-12">
                                <span class="addshare-0 add-socialmedia pull-right marT10 text-color" data="0" style="">{{trans('forms.sharehoding_str.Label.add_sharehonder')}}</span>
                            </div>
                        </div>


                    </div>
                     {{Form::hidden('rows0','1')}}
                     {{Form::hidden('nexted','1')}}
                    @endif






                    <div class="row marT60 marB20">
                        <div class="col-md-12 text-right">

                            <a href="{{route('company-address-show')}}" class="btn btn-prev">{{trans('common.Button.pre')}}</a>	
                            {{ Form::submit('Save',['class'=>'btn btn-save','name'=>'save']) }}
                            {{ Form::submit('Save & Next',['class'=>'btn btn-save','name'=>'save_next']) }}
                        </div>
                    </div>

                    {{Form::close()}}
                </div>
            </div>

        </div>	
    </div>

</section>
@include('frontend.shareholdingmodal')
@endsection

@section('pageTitle')
{{trans('forms.sharehoding_str.Label.heading')}}
@endsection

@section('jscript')
<script src="{{ asset('backend/theme/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('frontend/outside/js/validation/shareHolding.js')}}"></script>
<script src="{{ asset('frontend/outside/js/validation/shareholderForm.js')}}"></script>
<script>

    $(document).ready(function(){

        $('input.form-control').attr('autocomplete',true);
    });
var arr_messages = {
    social_media_form_limit: "{{ config('common.SOCIAL_MEDIA_LINK') }}",
    document_form_limit: "{{ config('common.DOCUMENT_LIMIT') }}",
    shareholder_save_ajax: "{{URL::route('shareholder_save_ajax')}}",
    companyShareSaveConfirm:"{{trans('common.alert_message.companyShareSaveConfirm')}}",
};

</script>
@endsection

