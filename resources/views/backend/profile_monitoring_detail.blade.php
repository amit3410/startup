@extends('layouts.admin')

@section('content')

<div class="col-md-12 dashbord-white">

    <div class="form-section">

        <div class="breadcrumbs">
            <div class="d-md-flex mb-3">
                <div class="breadcrumbs-bg">

                    <ul>
                       
                        <li>
                            <a onclick="window.location.href = '{{ route('profile_monitoring') }}'"> Profile Monitoring</a>
                        </li>
                        <li>
                            Document Details
                        </li>
                       

                    </ul>


                </div>


            </div>
        </div>
        <?php
        $reqStatus=Helpers::getReqStatusDropDown();
        $docStatus=Helpers::getDocStatusDropDown();
        $docMonitor=Helpers::getMonitoringDropDown();
       
        ?>
         <table class="table table-striped table-sm">
            <tbody>
                <tr>
                    <td>
                    <div class="row mb-4">
                        <div class="heading col-md-12">
                            <h4>User Detail</h4>
                        </div>

                    </div> 
                    @if($docdetail->user_type==2)    
                    <div class="row mb-4">
                    <div class="col-md-6 view-detail">
                        <label>Company Name</label>
                        <span>{{$docdetail->f_name}}</span>
                    </div>
                    <div class="col-md-6 view-detail">
                        <label>Company Trade License Number</label>
                        <span>{{$docdetail->l_name}}</span>
                    </div>
                    @endif
                    
                    </div>    
                    <div class="row mb-4">
                    <div class="col-md-6 view-detail">
                        <label>First Name</label>
                        <span>{{$docdetail->f_name}}</span>
                    </div>
                    <div class="col-md-6 view-detail">
                        <label>Last Name</label>
                        <span>{{$docdetail->l_name}}</span>
                    </div>
                    
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6 view-detail brown-i">
                            <label>Official Email</label>

                            <?php
                            
                           $emailVarified = '<i class="fa fa-check-square green-color"></i>';
                           $mobileVarified = '<i class="fa fa-check-square green-color"></i>';
                                
                            ?>


                            <span>{{$docdetail->email}} &nbsp;&nbsp;<?php echo  $emailVarified;?></span>

                        </div>
                        <div class="col-md-6 view-detail brown-i">
                            <label>Official Mobile No.</label>

                            <span>{{$docdetail->phone_no}} &nbsp;&nbsp;<?php echo $mobileVarified;?></span>

                        </div>

                    </div>
                    <div class="row mb-4">
                        <div class="heading col-md-12">
                            <h4>Document Detail</h4>
                        </div>

                    </div>    
                    
                    <div class="row mb-4">
                    <div class="col-md-6 view-detail">
                        <label>Document Name</label>
                        <span>{{$docMonitor[$docdetail->doc_no]}}</span>
                    </div>
                    <div class="col-md-6 view-detail">
                        <label>Document Number</label>
                        <span>{{$docdetail->document_number}}</span>
                    </div>
                    
                    </div> 
                     <?php
                    $issuance_date=($docdetail->issuance_date!='' && $docdetail->issuance_date!=null) ? Helpers::getDateByFormat($docdetail->issuance_date, 'Y-m-d', 'd/m/Y') :'';
                    $expire_date=($docdetail->expire_date!='' && $docdetail->expire_date!=null) ? Helpers::getDateByFormat($docdetail->expire_date, 'Y-m-d', 'd/m/Y') :'';
                         
                     ?>   
                    <div class="row mb-4">
                    <div class="col-md-6 view-detail">
                        <label>Issuance Date</label>
                        <span>{{$issuance_date}}</span>
                    </div>
                    <div class="col-md-6 view-detail">
                        <label>Expiry Date</label>
                        <span>{{$expire_date}}</span>
                    </div>
                    
                    </div>
                        
                    <div class="row mb-4">
                    <div class="col-md-6 view-detail">
                        <label>Request Status</label>
                        <span>{{$reqStatus[$docdetail->status]}}</span>
                    </div>
                    <div class="col-md-6 view-detail">
                        <label>Document Status</label>
                        <span>{{$docStatus[$docdetail->doc_status]}}</span>
                    </div>
                    
                    </div> 
                    @php
                    $docList = [];
                    $docList=Helpers::getDocumentList($docdetail->user_kyc_id, $docdetail->user_req_doc_id );
                    @endphp
                    @if(count($docList) > 0 )
                    <div class="row mb-4">
                    <div class="col-md-12 view-detail">
                        <table class="data-download full-width">
                        @foreach($docList as $val)
                            @php
                            $docName ='';
                            $docName = $val->doc_name.".".$val->doc_ext;
                            $uploaded_on=date('d/m/Y',strtotime($val->created_at));
                            @endphp

                            <tr>
                            <td width="50%"><span class="db_uplad-file"> {{$docName}}  &nbsp; &nbsp;</span></td>
                            <td width="10%" align="right"><a href="{{route('download_document',['enc_id' => $val->enc_id,'user_type'=>$docdetail->user_type])}}"><button type="button" class="btn btn-default btn-sm btn-approved" data-toggle="modal" data-target="#Approved_Action">Download</button></a></td>
                            <td width="40%"></td>
                            </tr>

                        @endforeach
                        </table>
                    </div>
                    </div>
                                @endif
                    @if($docdetail->doc_status==0 && $docdetail->status==2)
                    <div class="row marT30">
                   
                        <div class="col-md-1">
                             {!! Form::open(
                            array(
                                'name' => 'statusFrm',
                                'id' => 'statusFrm',
                                'autocomplete' => 'off',
                                'class' => 'form',
                                'url' => route('update_docsuatus',['id'=>$docdetail->id,'doc_status'=>1]),
                                'method' => 'post',
                                'onsubmit'=>'return confirm("Do you really want to change status?");'
                                )
                            )
                        !!}
                                            
                        {{ Form::submit('Approve',['class'=>'btn btn-default btn-sm btn-approved','name'=>'approve']) }}
                                            
                                            
                        {!! Form::close()!!}
                            
                        
                        </div>
                        <div class="col-md-1">
                            
                            
                        
                        {!! Form::open(
                            array(
                                'name' => 'statusFrm',
                                'id' => 'statusFrm',
                                'autocomplete' => 'off',
                                'class' => 'form',
                                'url' => route('update_docsuatus',['id'=>$docdetail->id,'doc_status'=>2]),
                                'method' => 'post',
                                'onsubmit'=>'return confirm("Do you really want to change status?");'
                                )
                            )
                        !!}
                                            
                        {{ Form::submit('Disapprove',['class'=>'btn btn-default btn-sm btn-disapproved','name'=>'disapprove']) }}
                                            
                                            
                        {!! Form::close()!!}
                        </div>
                        
                        
                        <div class="col-md-10"></div>
                    
                    </div>
                    @endif
                    </td>
                </tr>
            <tbody> 
        </table>            
        

    </div>

</div>

@endsection
@section('pageTitle')
Profile Monitoring
@endsection
@section('jscript')
<script>
    $(document).ready(function () {
   
    });
    
    
</script>
<script src="{{ asset('frontend/inside/js/popper.min.js') }}"></script>
<script src="{{ asset('frontend/inside/js/bootstrap.min.js') }}"></script>
@endsection
