@extends('layouts.app')
@section('content')

<style type="text/css">
	
.btn-file {
    position: relative;
    overflow: hidden;
}

    .btn-file input[type=file] {
        position: absolute;
        top: 0;
        right: 0;
        min-width: 100%;
        min-height: 100%;
        font-size: 100px;
        text-align: right;
        filter: alpha(opacity=0);
        opacity: 0;
        outline: none;
        background: white;
        cursor: inherit;
        display: block;
    }
</style>


<section>
  <div class="container">
   <div class="row">
  
  	<div id="header" class="col-md-3">
	   <div class="list-section">
	     <div class="kyc">
		   <h2>{{trans('forms.corperate_uploaddoc.Label.lbl_kyc')}}</h2>
		   <p class="marT15 marB15">{{trans('forms.corperate_uploaddoc.Label.lbl_para')}}</p>
		   <ul class="menu-left">
		     <li><a href="#">{{trans('forms.corperate_uploaddoc.Label.item1')}}</a></li>
			 <li><a href="#">{{trans('forms.corperate_uploaddoc.Label.item2')}}</a></li>
			 <li><a href="#">{{trans('forms.corperate_uploaddoc.Label.item3')}}</a></li>
			 <li><a href="#">{{trans('forms.corperate_uploaddoc.Label.item4')}}</a></li>
			  <li><a class="active" href="#">{{trans('forms.corperate_uploaddoc.Label.item5')}}</a></li>
		   </ul>
		 
		</div>
	   </div>
	</div>

	<div class="col-md-9 dashbord-white">

	 <div class="form-section">
        <h3 id="msg" style='color:green;'>   </h3>
	   <div class="row marB10">
		   <div class="col-md-12">
		     <h3 class="h3-headline">{{trans('forms.corperate_uploaddoc.Label.heading')}}</h3>
		   </div>
		   

		  
		  		    <form id="documentform" enctype="multipart/form-data" class="needs-validation form" novalidate method="post">
			@csrf
			<div class="row marT15 marB10">
			 <div class="col-md-12">
				<div class="form-group">
				  <label for="pwd">{{trans('forms.corperate_uploaddoc.Label.provided_docs')}}</label> 
				</div>
			  </div>

			</div>
		
@php
 $ii = 0;
@endphp
@foreach($documentArray as $document)
@php $g = $ii++ @endphp




<div class="row marB20">
		<div class="col-md-9">
			<p class="text-color bullet marB5">{{$document['upload_doc_name']}}</p>

@php
$docList = [];

$docList=Helpers::getDocumentList(Auth()->user()->user_id, $document['user_req_doc_id'] );
@endphp
@if(count($docList) > 0 )
	@foreach($docList as $val)
	    @php
	      $docName ='';
	  		$docName = $val->doc_name.".".$val->doc_ext;
	    @endphp
	   <ul class="document-list">
		    <li>
				
     <a href="{{route('import_doc',['enc_id' => $val->enc_id])}}">
     	 
					     {{$docName}}
				</a>
			</li>
	    </ul>
	@endforeach
@endif




			
		</div>
		<label>   </label>
		

	<div class="col-md-3 text-right row files" id="files{{$g}}">
        
        <span class="btn btn-default btn-file">
		{{trans('forms.corperate_uploaddoc.Label.lbl_browse')}}  <input id="pics{{$g}}" type="file"  name="files[]" data-id="{{$document['user_req_doc_id'].'#'.$document['user_id'].'#'.$document['doc_id']
	}}" multiple class="upload"/>
        </span>
        <br />
        <ul class="fileList"></ul>
    </div>

</div>	





<input type = "text" value = "{{$document['user_req_doc_id'].'#'.$document['user_id'].'#'.$document['doc_id']
            }}" id ="filId{{$g}}" style="display: none;">
@endforeach




<input type = "text" value = "{{$g}}" id ="gval" style='display: none'>

	
		<div class="row marB25 marT25">
			  <div class="col-md-12">
	           <div class="form-group">
				  <label for="pwd">{{trans('forms.corperate_uploaddoc.Label.lbl_dec')}}/label> 
				  <p class="text-color font-13">{{trans('forms.corperate_uploaddoc.Label.lbl_para1')}} &amp; {{trans('forms.corperate_uploaddoc.Label.lbl_para2')}} &amp; {{trans('forms.corperate_uploaddoc.Label.lbl_para3')}}</p>
				</div>
			</div>	  
		</div>


	 
		<div class="row marT30">
		    <div class="col-md-6">
			   <div class="form-group">
				  <div class="form-check-inline">
				  	<label class="form-check-label" for="check2">
				  	<input type="checkbox" class="form-check-input" id="check2" name="termcondition" value="something">{{trans('forms.corperate_uploaddoc.Label.lbl_terms_cond')}}
					  
						
					</label>
					</div>
			  </div>
			</div>
	         <div class="col-md-6 text-right">
			  <a href="#" class="btn btn-prev">{{trans('common.Button.pre')}}</a>	
	          <button  type="button"  id="uploadBtn" class="btn btn-save">{{trans('common.Button.submit')}}</button>

			 </div>
		</div>
			
			
		 </form>
	  </div>
	</div>
	
   </div>	
  </div>
</div>
</section>


@include('frontend.company.companyscript')
@include('frontend.company.documentfileuploadjs')


@endsection