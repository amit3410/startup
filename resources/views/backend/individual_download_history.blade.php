@extends('layouts.frame')
@section('content')
        <form method='post' id='indiv_form_report_submit'>
            @csrf
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th width="10%">Sr. No.</th>
                            <th width="30%">Transaction</th>
                            <th width="20%">Date</th>
                            <th width="20%">Status</th>
                            <th width="20%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $reportTrails = Helpers::getcomplincestrail($userKycId);
                        if(isset($reportTrails) && count($reportTrails) > 0) {
                            $sno = 1;
                            foreach($reportTrails as $reportTrail) {
                                $transationStatus = '';
                                if($reportTrail->eth_status == 1) {
                                    $transationStatus = 'Pending';
                                } else if($reportTrail->eth_status == 2) {
                                    $transationStatus = 'Committed';
                                } else if($reportTrail->eth_status == 3) {
                                    $transationStatus = 'Rejected';
                                }
                                
                        ?>
                        <tr>
                        <td width="10%">{{ $sno }}.</td>
                        <td width="30%" style="width:150px; word-break: break-all;">{{ ($reportTrail->transaction_hash) ? $reportTrail->transaction_hash : '--'}}</td>
                        <td width="20%">{{date('d/m/Y',strtotime($reportTrail->created_at))}}</td>
                        <td width="20%"><span class="text-approved">{{ ($transationStatus) ? $transationStatus : ''}}</span></td>
                        <td width="20%"><button class="btn btn-save"><a href="{{route('historical_pdf_download',['userKycId'=>$userKycId])}}">Download</a></button></td>
                        </tr>
                        <?php $sno++; } } else { ?>
                        <tr>
                            <td colspan="5">No Transactions Found</td>
                       </tr>
                       <?php } ?>
                    </tbody>
                </table>
            </div>
        </form>
<style>
    .btn.btn-save {
        color: #743939 !important;
        background: #F7CA5A;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
        padding: 7px 30px 7px 30px;
        margin: 10px 5px;
        font-size: 14px;
        font-weight: 700;
        text-transform: capitalize;
    }
	    .btn.btn-default{
    color: #1B1C1D;
    border: solid 1px #1B1C1D;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 4px;
    background: transparent;
    padding: 4px 25px;
    text-transform: capitalize;
    font-weight: normal;
	box-shadow: none !important;
    }
  .heading-brown{ color: #743939; font-size: 18px;}
</style>



@endsection
@section('pageTitle')
User Detail
@endsection



@section('jscript')

<script type="text/javascript" src="{{ asset('frontend/outside/js/bootstrap.min.js') }}"></script>
<script>
document.body.style.background = "transparent";
function callclose() {
       parent.jQuery("#btn-disapproved-iframe").modal('hide');
  }


</script>

@endsection