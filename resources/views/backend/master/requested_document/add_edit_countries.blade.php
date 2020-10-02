@extends('layouts.admin')

@section('content')
<?php 
       if($resData && $resData->count()){
           $id=$resData->id;
           $doc_name=$resData->doc_name;
           $doc_for=$resData->doc_for;
           $type=$resData->type;
       }else{
           $id=null;
           $doc_name='';
           $doc_for='';
           $type='';
       }
    ?>
<div class="form-section">
    <div class="row marB10">
        <div class="col-md-12">
            <h3 class="h3-headline">@if($id!='' && $id!=null) {{trans('forms.OtherDocument.Label.edit')}} @else {{trans('forms.OtherDocument.Label.add')}} @endif {{trans('forms.OtherDocument.Label.heading')}}</h3>
        </div>
    </div>
    
    {!!
        Form::open(
            array('name' => 'ProCountryMaster',
            'autocomplete' => 'off', 
            'id' => 'ProCountryMaster',  
            'class' => 'form-horizontal',
            'url' => route('save_other_document',['id'=>$id])
            )
        ) 
    !!}
    
    
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('doc_name',trans('forms.OtherDocument.Label.document_name'))}}
                <input type="text" class="form-control"  placeholder="{{trans('forms.OtherDocument.Label.document_name')}}" name="doc_name" value="{{($id!=null)?$doc_name:''}}">
                <span class="text-danger">{{ $errors->first('doc_name') }}</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('doc_for',trans('forms.OtherDocument.Label.document_for'))}}
                 {{ Form::select('doc_for',[''=>trans('forms.OtherDocument.Label.document_for')]+Helpers::getDocForDropDown(),($id!=null)?$doc_for:'', ['class' => 'form-control', 'id' => 'doc_for']) }}
                <span class="text-danger">{{ $errors->first('doc_for') }}</span>
            </div>
        </div>
    
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('type',trans('forms.OtherDocument.Label.document_type'))}}
                {{ Form::select('type',[''=>trans('forms.OtherDocument.Label.document_type')]+Helpers::getDocTypeDropDown(),($id!=null)?@$type:'', ['class' => 'form-control', 'id' => 'type']) }}
                <span class="text-danger">{{ $errors->first('type') }}</span>
            </div>
        </div>
    
    </div>
    <div class="row">
        <div class="col-md-6 marT20">
            {{ Form::submit(trans('forms.OtherDocument.Label.submit'),['class'=>'btn btn-save','name'=>'save_next']) }}
        </div>
   </div>
    {!! Form::close() !!}
  
</div>

@endsection
@section('pageTitle')
Add Other Document
@endsection
@section('jscript')
<script>
    var messages = {
        country_name: "{{trans('master.country.country_name')}}",
        country_code: "{{trans('master.country.country_code')}}",
        is_active: "{{trans('master.country.is_active')}}"
    };
</script>
<script src="{{ asset('common/js/jquery.validate.js') }}"></script>
<script src="{{ asset('backend/js/master/country.js') }}" ></script>

@endsection
