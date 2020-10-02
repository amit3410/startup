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

.modal-content{
       background:#fff;
        body{
          background:#fff;
        }
 	}
    .modal-title {
        font-size: 16px;
       color: #fff;
       font-weight: 600;

    }

    
a {
    color: #000;
}

</style>

<?php
$userKycId      =   $benifinary['user_kyc_id'];
$corpUserId     =   $benifinary['corp_user_id'];
$isBycompany    =   $benifinary['is_by_company'];

?>
<section>
  <div class="container">
   <div class="row">
  
    <div id="header" class="col-md-3">
        @include('layouts.user-inner.left-menu')

     
    </div>

    <div class="col-md-9 dashbord-white">

     <div class="form-section">
        <h3 id="msg" style='color:green;'>   </h3>
        <div id="errormsg"></div>

       <div class="row marB10">
           <div class="col-md-12">
             <h3 class="h3-headline">Documents & Declaration</h3>
           </div>
           

          
            <form id="documentform_indi" enctype="multipart/form-data" class="needs-validation form" novalidate method="post" autocomplete = 'off'>
            @csrf
            <div class="row marT15 marB10">
             <div class="col-md-12">
                <div class="form-group">
                  <label for="pwd">Documents to be provided along with this form</label> 
                </div>
              </div>

            </div>
            

        
@php
 $ii = 0;
 $g=0;
 $status='';
@endphp


@if($documentArray!=null)
@foreach($documentArray as $document)
@php $g = $ii++ @endphp



<div class="row marB20">
        <div class="col-md-6">
          <!-- mandatory field -->
    @if($document['is_required'] == 1)
        <p class="text-color bullet marB5">{{$document['upload_doc_name']}}   <span class="mandatory">*<span></p>   
        <span class="text-danger" id="doc_{{$document['user_req_doc_id']}}"></span>
    @else 
        <p class="text-color bullet marB5">{{$document['upload_doc_name']}}</p> 
        <span class="text-danger" id="doc_{{$document['user_req_doc_id']}}"></span>
    @endif
@php
$docList = [];


$docList=Helpers::getDocumentList($benifinary['user_kyc_id'], $document['user_req_doc_id'] );
@endphp
@if(count($docList) > 0 )
    @foreach($docList as $val)
        @php
          $docName ='';
            $docName = $val->doc_name.".".$val->doc_ext;
        @endphp
       <ul class="document-list">
            <li>
                
                <a href="{{route('import_document',['enc_id' => $val->enc_id])}}">
         
                         {{$docName}} &nbsp; &nbsp; Download  
                </a> 
                
            </li>
        </ul>
    @endforeach
@endif




            
        </div>
        <label>   </label> 
        

    <div class="col-md-6 text-right row files position-relate" id="files{{$g}}">
        
    <span class="upload-btn-wrapper">
        @if($kycApproveStatus==0)
         <label for="pwd">   Upload  </label><input id="pics{{$g}}" type="file"  name="files[]" data-id="{{$document['user_req_doc_id'].'#'.$document['user_kyc_id'].'#'.$document['doc_id'].'#'.$document['is_required']}}" multiple class="upload"/>
        @endif
        
    </span>
        <br />
        <ul class="fileList"></ul>
    </div>
     




</div>  
@if($document['doc_no']==2)
<?php 
$issuance_date='';
$document_number='';    
$param['id']='';
$param['doc_id']='';
$param['doc_no']='';

if($resMstDoc && $resMstDoc->count()){
   foreach($resMstDoc as $obj){
       $issuance_date   =  (@$obj->issuance_date!='' && $obj->issuance_date!=null) ? Helpers::getDateByFormat($obj->issuance_date, 'Y-m-d', 'd/m/Y') :'';
       $document_number =   $obj->document_number;
       $param['id']     =   $obj->id;
       $param['doc_id'] =   $obj->doc_id;
       $param['doc_no'] =   $obj->doc_no;
       $status =   $obj->status;
   } 

}

?>
<div class="row marB20">
    <div class="col-md-3">
        <div class="form-group">
                   <label for="pwd">{{trans('forms.personal_Profile.Label.document_number')}}</label>
                   <input type="text" value = "{{$document_number}}" class="form-control"  placeholder="{{trans('forms.personal_Profile.Label.document_number')}}" name="document_number" id="document_number">
                    <span class="text-danger" id="errordocument_number"></span>
               </div>
    </div>
    <div class="col-md-3">
      <div class="form-group">
                   <label for="pwd">{{trans('forms.personal_Profile.Label.issuance_date')}}</label>
                    <div class="input-group">
                                           
                    {{ Form::text('issuance_date',$issuance_date, ['class' => 'form-control datepicker issuance-date','placeholder'=>trans('forms.personal_Profile.Label.issuance_date'),'id' => 'issuance_date']) }}
                    <div class="input-group-append">
                    <i class="fa fa-calendar-check-o"></i>
                    </div>
                    </div>
                    <span class="text-danger" id="errorissuance_date"></span>
               </div>   
    </div>
    <div class="col-md-6">
     @if($status==1)   
     <button type="button" class="btn btn-save pull-right" id="btOtp" data-toggle="modal" data-target="#docModal">Update</button>   
     @endif
    </div>    
</div>
@endif




<input type = "text" value = "{{$document['user_req_doc_id'].'#'.$document['user_id'].'#'.$document['doc_id'].'#'.$document['is_required']}}" id ="filId{{$g}}" style="display: none;">
@endforeach
@endif



<input type = "text" value = "{{$g}}" id ="gval" style='display: none'>

    
        <div class="row marB25 marT25">
              <div class="col-md-12">
               <div class="form-group">
                  <label for="pwd">Declaration</label> 
                  <p class="text-color font-13">We hereby declare that the particulars given herein are true, correct &amp; complete to the best of our knowledge &amp; belief. We undertake to promptly inform Dexter Capital Financial Consultancy LLC of any changes or information provided hereinabove.</p>
                </div>
            </div>    
        </div>


     @if(!isset($isBycompany) || $isBycompany == 0)
        <div class="row">
            <div class="col-md-12">
               <div class="form-group">
                  <div class="form-check-inline form-check-link">
                     @php
                     $termCond = Helpers::getTermCondition(Auth()->user()->user_id);
                     @endphp           
                    @if($termCond==0)
                    
                    <input type="checkbox" class="form-check-input" id="check2" name="termcondition" value="1">
                    @else
                    <input type="checkbox" class="form-check-input" id="check2" name="termcondition" value="1" disabled="true" checked>
                    @endif
                  

                    <a  href="#" data-toggle="modal" data-target="#termcondition"  data-url="{{route('term_condition',['id'=>$userKycId])}}" data-height="500px" data-width="770px" data-placement="top"> I accept the Terms and Conditions</a>
                    
                    </div>
                   <p class="text-danger" id="termError"></p>
              </div>
            </div>
            
        </div>
    @endif

        
        <div class="row marT30">
        <div class="col-md-12 text-right">
        <a href="{{$benifinary['prev_url']}}" class="btn btn-prev pull-left">Previous</a>
        @if($kycApproveStatus==0)
        <button  type="submit"  id="indivisualuploadBtn" class="btn btn-save">{{trans('common.Button.submit')}}</button>
              
        @endif
        </div>
        </div>   
             
         </form>
      </div>
    </div>
    
   </div>   
  </div>
</div>



  <!-- The Modal -->
  @if($status==1)  

      <div class="modal mod" id="docModal">
      <div class="modal-dialog">
      <div class="modal-content mod-content" style="width:500px;">

      <!-- Modal Header -->
      <div class="modal-header mod-header">
        <h4 class="modal-title">Update Utility Bill</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
       {!!
            Form::open(
            array(
            'name' => 'updateDocForm',
            'id' => 'updateDocForm',
            'autocomplete' => 'off','class'=>'loginForm form form-cls','files' => true))
        !!}
      <!-- Modal body -->
      <div class="modal-body mod-body">
       <div id="errormsg"></div>
        <div class="row">
           <div class="col-md-12"> 
               <div class="form-group">
                   <label for="pwd">{{trans('forms.personal_Profile.Label.document_number')}}</label>
                   <input type="text" value = "" class="form-control"  placeholder="{{trans('forms.personal_Profile.Label.document_number')}}" name="document_number1" id="document_number1">
                    <span class="text-danger" id="errordocument_number1"></span>
               </div>
           </div>
        </div>
        <div class="row">
           <div class="col-md-12"> 
               <div class="form-group">
                   <label for="pwd">{{trans('forms.personal_Profile.Label.issuance_date')}}</label>
                    <div class="input-group">
                                           
                    {{ Form::text('issuance_date1','', ['class' => 'form-control datepicker issuance-date0','placeholder'=>trans('forms.personal_Profile.Label.issuance_date'),'id' => 'issuance_date1']) }}
                    <div class="input-group-append">
                    <i class="fa fa-calendar-check-o"></i>
                    </div>
                    </div>
                    <span class="text-danger" id="errorissuance_date1"></span>
               </div>
           </div>
        </div>
        
          
        <div class="row">
           
        <div class="col-md-6">
                       
        <ul class="fileList"></ul>
        <span class="text-danger" id="errorfiles"></span> 
        </div> 
        <div class="col-md-6 files position-relate pull-right" id="files0">
        
        <span class="upload-btn-wrapper">
 
         <label for="pwd">   Upload  </label>
         <input id="file_upload" type="file"  name="files[]" data-id="{{'1#'.'1#'.'1#1'}}" multiple class="upload"/>
        
        </span>
        
        
        </div> 
           
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="pwd">{{trans('master.otpForm.otp_heading')}}</label>
                    <input type="text" class="form-control"  placeholder="{{trans('master.otpForm.enter_otp')}}" name="otp" id="otp" required>
                    <span class="text-danger" id="errorotp"></span>
                </div>
                <p class="p-conent">{{trans('master.otpForm.enter_otp_below')}}</p> 
            </div>
        </div>
          
        <div class="row">
            <div class="col-md-12">
           
               {{ Form::button('Resend OTP',['class'=>'btn btn-prev','name'=>'btresend','id'=>'btresend']) }}
            </div>
        </div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        {{ Form::hidden('user_kyc_id',$benifinary['user_kyc_id'], ['class' => 'form-control']) }}
        {{ Form::hidden('id',$param['id'], ['class' => 'form-control']) }}
        {{ Form::hidden('doc_id',$param['doc_id'], ['class' => 'form-control']) }}
        {{ Form::hidden('doc_no',$param['doc_no'], ['class' => 'form-control']) }}
        {{ Form::button('Close',['class'=>'btn btn-prev','name'=>'close','data-dismiss'=>"modal"]) }}
        {{ Form::button('Update',['class'=>'btn btn-save','name'=>'save','id'=>'btupdate']) }}
        
      </div>
      
      {{ Form::close() }}
      <script>
          $('#file_upload').change(function() {
             var output = []; 
             var arrFile = $('#file_upload')[0].files;
             var len    =   $('#file_upload')[0].files.length;
             for(var i = 0; i < len; i++){
                 var removeLink='';
                 //var removeLink = "<a class=\"removeDoc\" href=\"#\"  data-fileid=\"" + i + "\">Remove</a>";

                 output.push("<li id='"+ i +"'><strong>", escape($('#file_upload')[0].files[i].name), "</strong>  ", removeLink, "</li> ");
             }
             $(".fileList").html();
             $(".fileList").append(output.join(""));
          });
        
        $('#btupdate').on('click',function(){

            var form = $('#updateDocForm')[0];

            var formData = new FormData(form);

            $.ajax({
                url: "{{route('update_utilitybill')}}",
                data: formData,
                method: "POST",
                processData: false,
                contentType: false,
                success: function (data) {
                
                var result=JSON.parse(data); 
                console.log(result);
               
                if(result['status']=="success"){
                 
                   window.location.reload();
                }else{

                    var messages=result['message'];
                    var messagekey=result['messagekey'];
                    
                       $.each(messages, function(key,value) {
                            console.log(key);
                           $('#'+messagekey[key]).html(value);
                       }); 

                }            
               
               
            },
                error: function (data) {
                    alert("ERROR - " + data.responseText);
                }
            });
        });
        
        
       
      </script>
      
    </div>
  </div> 
    
  </div>
    
   @endif 
    
    
    
   <div class="modal fade in" id="termcondition" >
        <div class="modal-dialog modal-lg custom-modal">
          <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Terms and Conditions</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body" style="padding: 0.5rem;">
              <iframe id ="frameset"  frameborder="0" scrolling="no"></iframe>
            </div>
          </div>
        </div>
    </div>
    
    
    
    
    
    
    
    
    
</section>





<link href="{{ asset('frontend/inside/plugin/datepicker/jquery-ui.css') }}" rel="stylesheet">
<script src="{{ asset('frontend/inside/plugin/datepicker/jquery-ui.js') }}"></script> 
<script src="{{ asset('frontend/outside/js/validation/socialmedia.js')}}"></script>
<script>
$(document).ready(function () {
    var disabledAll = '{{$kycApproveStatus}}';
    if (disabledAll != 0) {
        $("#documentform_indi :input").prop("disabled", true);
        $("#btOtp").prop("disabled",false); 
    }

    $('.issuance-date').datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        maxDate: new Date(),
        minDate:'-89D',
    });
    
    $('#docModal').on('shown.bs.modal', function() {
      $('.issuance-date0').datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        maxDate: new Date(),
        minDate:'-89D',
        container: '#docModal .modal-body'
        });
        
       
    });
});


var globlvar={
    "save_doc_url":"{{ route('upload_document',['user_kyc_id' => $userKycId, 'corp_user_id' => $corpUserId, 'is_by_company' => $isBycompany]) }}",
};

$('#btOtp').on('click',function(){
        $.get("{{route('send_otp')}}", function( data ) {
            //  alert(data);
        });

    });
    
    $('#btresend').on('click',function(){
        $.get("{{route('resend_otp')}}", function( data ) {
            var result=JSON.parse(data); 
            console.log(result);
            if(result['status']=='success'){
                var errorMs='<div class="my-alert-success alert bg-success base-reverse alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+result['message']+'</div>';
                $("#errormsg").html(errorMs);  
                setTimeout(function(){  $('#errormsg').html('');  }, 6000);
            }else{
                var errorMs='<div class=" my-alert-danger alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+result['message']+'</div>';
                $("#errormsg").html(errorMs); 
              
               setTimeout(function(){ $('#errormsg').html(''); }, 6000); 
            }
        });

    });

</script>

<!-- <style type="text/css">
.set-error-msg .error {
    position: absolute;
    top: 19px;
}
</style> -->

@include('frontend.company.documentfileuploadjs')

@endsection

@section('additional_css')
<link href="{{ asset('frontend/inside/css/bk/bootstrap.min.css') }}" rel="stylesheet">

  <style>
  .mod-header {
    display: -ms-flexbox;
    display: flex;
    -ms-flex-align: start;
    align-items: flex-start;
    -ms-flex-pack: justify;
    justify-content: space-between;
    padding: 1rem 1rem;
    border-bottom: 1px solid #0b0202;
    border-top-left-radius: calc(.3rem - 1px);
    border-top-right-radius: calc(.3rem - 1px);
}    
  .mod .mod-body {
    min-height: 300px;
    font-size: 14px;
    padding: 1rem 2.188rem 2.4rem 2.188rem;
    color: #0b0202 !important;
    line-height: 22px;
  }    
  .mod .mod-content {
    background-color: #fff !important;
}    


.issuance-date0 {
      z-index: 1600 !important; /* has to be larger than 1050 */
    }


</style>

@endsection
