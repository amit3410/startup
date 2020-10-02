@extends('layouts.admin')

@section('content')
@php
$apiFullName ='';
$date_of_birthAPI = '';

@endphp
<style>
    #header{
        padding: 30px 30px 30px 30px;
        position: -webkit-sticky !important;
        position: sticky !important;
        top: 0;
    }
    .dashbord-white {
        min-height: 90vh !important;
        height:auto;
        overflow: auto;
   }
</style>
<div class="col-md-12 dashbord-white">

    <div class="form-section">

        <div class="breadcrumbs">
            <div class="d-md-flex mb-3">
                <div class="breadcrumbs-bg">
                    <ul>
                        <li>
                            <a onclick="window.location.href = '{{ route('individual_user') }}'"> Manage Users</a>
                        </li>
                        <li>
                            Corporate Details
                        </li>
                    </ul>
                </div>
                <div class="ml-md-auto d-flex form action-btns">
                    <div>
                        <button type="button" class="btn btn-default btn-sm btn-disapproved" data-toggle="modal" data-target="#btn-disapproved-iframe"  data-url="{{route('individual_final_nomatch',['id'=>$userKycId])}}" data-height="200px" data-width="100%" data-placement="top">No Match</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="tabs-section">
            <ul class="nav nav-tabs tabs-cls">
                <li class="nav-item">
                    <a class="nav-link parent-tab active" data-toggle="tab" href="#tab01">Similar Records</a>
                </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane container p-0 mt-3 active" id="tab01">
                    <!--Similar records  Start-->
                <?php
                if(!isset($kycDetail)) {
                ?>
                    <div class="table-responsive">

                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th width="10%">ReferenceId</th>
                                    <th width="15%">Name</th>
                                    <th width="15%">Category</th>
                                    <th width="15%">Provider Types</th>
                                    <th width="10%">Gender</th>
                                    <th width="10%">DOB</th>
                                    <th width="15%">Country</th>
                                    <th class="text-right" width="10%">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="similarRecords">
                            </tbody>
                        </table>

                    </div>
                <?php } else {
                      //@header('Content-Type: text/html; charset=utf-8');
                      $statusName ='';
                       $riskName   ='';
                       $resonName  ='';
                       $reson_desp ='';
                       $matchresult_id = '';
                       $resolvedon = '';
                       $matchresult = '';
                       $possbilecount = 0;
                       $positivecount = 0;
                       $falsecounting = 0;
                       $unspecifiedcount = 0;
                       $totalMatch = 0;
                       $resolvedMatched = 0;
                       $autoresolvedfalse = 0;
                        $caseid    =   $kycDetail['caseid'];
                        $response  = $kycDetail['api_responce']; //exit;
                        $userKycId = $kycDetail['user_kyc_id'];
                        $primaryId = $kycDetail['wcapi_req_res_id'];
                        $exactcount = $kycDetail['exactcount'];
                        $falsecount = $kycDetail['falsecount'];
                        $autoresolved = $kycDetail['autoresolved'];
                        $totalMatch = ($autoresolved+$falsecount);
                       //$response = (string)$response;

                      // $response= iconv('windows-1256', 'utf-8', ($response));
                       //$dataArray = json_decode(strip_tags($response),JSON_UNESCAPED_UNICODE);
                       $dataArray = json_decode($response, JSON_UNESCAPED_UNICODE);


                         $i           = 0;
                        $ExactCount  = 0;
                        $FalseCount  = 0;
                        $possibleStatus    = '';
                        $positiveStatus    = '';
                        $falseStatus       = '';
                        $unspecifiedStatus = '';
                        $Possible_Array    = [];
                        $Positive_Array    = [];
                        $False_Array       = [];
                        $Unspecified_Array = [];
                        $matchresultArray = [];
                       $resolutionDatas = Helpers::getResolutionDatabycaseID($caseid);
                       $resolvedMatched   = $autoresolved;
                        $autoresolvedfalse = $autoresolved;
                       foreach($resolutionDatas as $resolutionData) {
                        if($resolutionData['status_mark']!=''){
                            $status = $resolutionData['status_mark'];
                            $risk   = $resolutionData['risk_level'];
                            $reson  = $resolutionData['reson'];
                            $reson_desp  = $resolutionData['reson_desp'];

                            $statusName  = Helpers::toolkettypebyId($status);
                            $riskName    = Helpers::toolkettypebyId($risk);
                            $resonName   = Helpers::toolkettypebyId($reson);
                            $resolvedon = $resolutionData['created_at'];
                            // echo $statusName."==>".$riskName."===>".$resonName;
                            //echo "===>".$matchresultCount;
                        }




                        if($statusName!='') {
                             if($statusName =="POSSIBLE") {
                                $possibleStatus = 'POSSIBLE';

                                if($resolutionData['from_resolve'] == '2') {
                                    $matchresult    = $resolutionData['result_id'];
                                    $explodeList = explode(',',$matchresult);
                                    foreach($explodeList as $key => $value) {
                                         if (empty($value)) {
                                                unset($explodeList[$key]);
                                            } else {
                                                array_push($Possible_Array,$value);
                                            }
                                    }

                                    $possbilecount  = ($possbilecount)+count($Possible_Array);
                                } else if($resolutionData['from_resolve'] == '1') {
                                   $matchresult      = $resolutionData['result_id'];
                                   $matchresult = trim($matchresult,",");
                                   $Possible_Array[] = $matchresult;
                                   $possbilecount    = $possbilecount+1;
                                }
                                $matchresultArray = array_merge($matchresultArray,$Possible_Array);

                             } else if($statusName =="POSITIVE") {
                                $positiveStatus = 'POSITIVE';
                                if($resolutionData['from_resolve'] == '2') {
                                    $matchresult   = $resolutionData['result_id'];
                                    $explodeList = explode(',',$matchresult);
                                    foreach($explodeList as $key => $value) {
                                         if (empty($value)) {
                                                unset($explodeList[$key]);
                                            } else {
                                                array_push($Positive_Array,$value);
                                            }
                                    }

                                    $positivecount = ($positivecount)+count($Positive_Array);
                                } else if($resolutionData['from_resolve'] == '1'){
                                   $matchresult   = $resolutionData['result_id'];
                                   $matchresult = trim($matchresult,",");
                                   $Positive_Array[] = $matchresult;
                                   $positivecount = $positivecount+1;
                                }
                                $matchresultArray = array_merge($matchresultArray,$Positive_Array);




                             } else if($statusName =="FALSE") {

                                $falseStatus = 'FALSE';
                                if($resolutionData['from_resolve'] == '2') {
                                    $matchresult    = $resolutionData['result_id'];
                                    $explodeList = explode(',',$matchresult);
                                    foreach($explodeList as $key => $value) {
                                         if (empty($value)) {
                                                unset($explodeList[$key]);
                                            } else {
                                                array_push($False_Array,$value);
                                            }
                                    }


                                    $falsecounting  = ($falsecounting)+count($False_Array);
                                } else if($resolutionData['from_resolve'] == '1'){
                                   $matchresult   = $resolutionData['result_id'];
                                   $matchresult = trim($matchresult,",");
                                   $False_Array[] = $matchresult;
                                   $falsecounting = $falsecounting+1;
                                }
                                $matchresultArray = array_merge($matchresultArray,$False_Array);
                             } else if($statusName =="UNSPECIFIED") {
                                 $unspecifiedStatus = 'UNSPECIFIED';
                                 if($resolutionData['from_resolve'] == '2') {
                                    $matchresult          = $resolutionData['result_id'];

                                    $explodeList = explode(',',$matchresult);
                                    foreach($explodeList as $key => $value) {
                                         if (empty($value)) {
                                                unset($explodeList[$key]);
                                            } else {
                                                array_push($Unspecified_Array,$value);
                                            }
                                    }
                                    $unspecifiedcount     = ($unspecifiedcount)+(count($Unspecified_Array));
                                } else if($resolutionData['from_resolve'] == '1'){
                                   $matchresult           = $resolutionData['result_id'];
                                   $matchresult = trim($matchresult,",");
                                   $Unspecified_Array[]   = $matchresult;
                                   $unspecifiedcount      = $unspecifiedcount+1;
                                }
                                $matchresultArray = array_merge($matchresultArray,$Unspecified_Array);
                             }
                         }

                        }





                        $falsecount = $falsecount-($possbilecount+$positivecount+$falsecounting+$unspecifiedcount);
                        $resolvedMatched = $resolvedMatched + ($possbilecount+$positivecount+$falsecounting+$unspecifiedcount);
                        $autoresolvedfalse = $autoresolvedfalse+$falsecounting;
                        $ExactCount  = 0;
                        $FalseCount  = 0;
                        $i = 0;
                    ?>

                    <div class="table-responsive">
                        <table class="w-100 mb-3">
                            <?php
                            $userArray = Helpers::getKycDetailsByKycID($userKycId);
                            if(isset($dataArray['results']) && $dataArray!='' && count(@$dataArray['results']) > 0) {

                                if($dataArray['caseId']) {
                                     $caseId = $dataArray['caseId'];
                                  }
                                  if(isset($dataArray['caseSystemId'])) {
                                     $caseSystemId = $dataArray['caseSystemId'];
                                  }
                                  $wcapi_req_res_id = $kycDetail['wcapi_req_res_id'];

                                ?>
                            <tr class="bg-remove">

                                    <td colspan="6" valign="top">
                                        <table class="w-80 float-right table-info-cls color-brown" style="width:600px !important;" border="0">
                                            <tr>
                                                <td colspan="3" style="color:#ec952d;" style="padding:0px;"><h4 style="font-size: 20px;font-weight: 600;margin: 10px 0px;padding: 0px;">KEY FINDINGS</h4></td>
                                            </tr>
                                            <tr bgcolor="#e4e4e3">
                                                <td width="20%" style="font-size:14px;">Total Matches:</td>
                                                <td width="15%" class="text-center"><div id="totalMatch">{{$totalMatch}}</div></td>
                                                <td width="65%" colspan="2"></td>
                                            </tr>
                                             <tr bgcolor="#fff">
                                                <td width="20%" style="font-size:14px;">Resolved Matches:</td>
                                                <td width="15%" class="text-center">
                                                    <div id="resolveMatch">{{$resolvedMatched}}</div></td>
                                                <td width="65%" style="font-size:14px; text-align: left;">
                                                    <div style="margin-right:12px;display: inline;">Positive: {{$positivecount}}</div>
                                                    <div style="margin-right:12px;display: inline;">Possible: {{$possbilecount}}</div>
                                                    <div style="margin-right:12px;display: inline;">False: {{$autoresolvedfalse}}</div>
                                                    <div style="margin-right:12px;display: inline;">Unspecified: {{$unspecifiedcount}}</div>
                                                    
                                                </td>
                                                
                                            </tr>
                                            <tr bgcolor="#e4e4e3">
                                                <td width="20%" style="font-size:14px;">Unresolved Matches:</td>
                                                <td width="15%" class="text-center"><div id="totalMatch">{{$falsecount}}</div></td>
                                                <td width="65%" class="text-center"></td>
                                            </tr>

                                            <tr>
                                                <td width="20%">
                                                    <a class="btn btn-sm btn-save" onclick="window.location.href = '{{ route("corp_user_detail",['user_id' => $userArray->user_id,'user_type' => 1,'is_by_company'=>$userArray->is_by_company,'tab' => 'tab06']) }}'">Back</a>
                                                </td>
                                                <td width="15%">
                                                    <input type="button" value="Generate Report" id="getfullDetailCorp" name="getfullDetailCorp" class="getfullDetailCorp btn btn-save btn-sm">
                                                    <input type="hidden" value="" name="matchStrenght" id="matchStrenght">
                                                    <input type="hidden" value="" name="pep" id="pep">
                                                    <input type="hidden" value="" name="caseId" id="caseId">
                                                    <input type="hidden" value="" name="systemcaseId" id="systemcaseId">
                                                    <input type="hidden" value="" name="resultId" id="resultId">
                                                    <input type="hidden" value="{{$primaryId}}" name="parent_id" id="parent_id">
                                                </td>
                                                <td width="65%"><a href="{{route('similar_download',['primaryId' => $primaryId])}}"><button type="button" class="btn btn-save btn-sm" >Download</button></a></td>
                                            </tr>
                                        </table>
                                    </td>


                                    <td colspan="5">
                                        {!!
                                            Form::open(
                                            array(
                                            'name' => 'corpResolveFormAll',
                                            'id' => 'corpResolveFormAll',
                                            'url' => route('individual_api_resolve_all'),
                                            'autocomplete' => 'off','class'=>'loginForm form form-cls'
                                            ))
                                        !!}

                                        <table class="w-80 float-right table-info-cls color-brown" style="width:400px !important;" border="0">
                                            <tr>
                                                <td colspan="2" style="color:#ec952d;" style="padding:0px;"><h4 style="font-size: 20px;font-weight: 600;margin: 10px 0px;padding: 0px;">Resolution</h4></td>
                                            </tr>
                                            <tr>
                                            <td style="color:#743939">STATUS<span class="mandatory">*</span></td>
                                             <td>
                                                 <select class="form-control" name="resolutionStatus" id="resolutionStatus">
                                                 <option value="">Select Status</option>
                                                 <?php
                                                 foreach($StatusArray as $status) {
                                                 ?>
                                                 <option value="{{$status->id}}">{{$status->type}}</option>
                                                 <?php } ?>
                                                  </select>
                                             </td>

                                        </tr>
                                         <tr>
                                             <td style="color:#743939"> RISK LEVEL <span class="mandatory">*</span></td>
                                             <td ><select class="form-control" name="resolutionRisk" id="resolutionRisk">
                                                    <option value="">Select Risk Level</option>
                                                        <?php
                                                        foreach($RiskArray as $Risk) {
                                                        ?>
                                                        <option value="{{$Risk->id}}" class="resolutionRisk" disabled>{{$Risk->type}}</option>
                                                        <?php } ?>
                                                   </select>
                                             </td>

                                        </tr>
                                         <tr>
                                            <td style="color:#743939">REASON <span class="mandatory">*</span></td>
                                            <td >
                                                <select name="resolutionReason" class="form-control" id="resolutionReason">
                                                    <option value="">Select Reason</option>
                                                    <?php  foreach($ReasonArray as $Reason) { ?>
                                                            <option value="{{$Reason->id}}" class="resolutionReason" disabled>{{$Reason->type}}</option>
                                                    <?php } ?>
                                                </select>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td >&nbsp;</td>
                                            <td >
                                                <textarea class="form-control" name="resolutionReasoncomment" id="resolutionReasoncomment" placeholder="Reason Description"></textarea></td>
                                        </tr>

                                         <tr>
                                            <td >&nbsp;</td>
                                            <td >
                                                 <input type="hidden" value="1" name="is_save">
                                            <input type="hidden" value="" name="statuskeyValue" id="statuskeyValue">
                                            <input type="hidden" value="" name="riskkeyvalue" id="riskkeyvalue">
                                            <input type="hidden" value="" name="reasonkeyalue" id="reasonkeyalue">
                                            <input type="hidden" value="" name="result_id" id="result_id">
                                            <input type="hidden" value="{{$caseId}}" name="case_id" id="case_id">
                                            <input type="hidden" value="{{$caseSystemId}}" name="case_system_id" id="case_system_id">
                                            <input type="hidden" value="{{$userKycId}}" name="userKycId" id="userKycId">
                                            <input type="hidden" name="primaryId" id="primaryId" value="{{$wcapi_req_res_id}}">

                                            <input class="btn btn-save btn-sm resolvesubmit" name="saveyes" type="submit" value="Save">
                                            <span id="CoustomError"></span>
                                            </td>
                                        </tr>
                                        </table>



                                            {{ Form::close() }}
                                    </td>

                                </tr>
                            <?php } else {

                                
                                ?>

                                <tr>
                                    <th class="text-center" width="30%" colspan="3"><a class="btn btn-sm btn-save" onclick="window.location.href = '{{ route("corp_user_detail",['user_id' => $userArray->user_id,'user_type' => 1,'is_by_company'=>$userArray->is_by_company,'tab' => 'tab06']) }}'">Back</a></th>
                                    <th class="text-center" width="40%" colspan="2">No Records Found</th>

                                    <th class="text-center" width="30%" colspan="3"><!--<button type="button" class="btn btn-default btn-sm btn-disapproved" data-toggle="modal" data-target="#btn-disapproved-iframe"  data-url="{{route('individual_final_nomatch',['id'=>$userKycId])}}" data-height="200px" data-width="100%" data-placement="top">No Match</button>--></th>
                                </tr>

                           <?php } ?>
                            
                            </table>
                        <!-- Tab section Start-->

                    <div class="tabs-section">

                    <ul class="nav nav-tabs tabs-cls">
                        <!--<li class="nav-item active">
                            <a class="nav-link parent-tab  active" data-toggle="tab" href="#tab011">All</a>
                        </li>-->
                        <li class="nav-item active">
                            <a class="nav-link parent-tab  active" data-toggle="tab" href="#tab011">Unresolved</a>
                        </li>
                         <!--<li class="nav-item">
                            <a class="nav-link parent-tab" data-toggle="tab" href="#tab021"></a>
                        </li>-->
                        <li class="nav-item">
                            <a class="nav-link parent-tab" data-toggle="tab" href="#tab031"><!--Auto Resolved-->False</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link parent-tab" data-toggle="tab" href="#tab041">Positive</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link parent-tab" data-toggle="tab" href="#tab051">Possible</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link parent-tab" data-toggle="tab" href="#tab061">Unspecified</a>
                        </li>
                    </ul>

            <!-- Tab panes -->
            <div class="tab-content">
            


                   <div class="tab-pane container p-0 mt-3 active" id="tab011">
                      <table class="table table-striped table-sm font-size-13 brown-light-th">
                               <thead>
                                <tr>
                                    <th class="text-right" width="3%">&nbsp;</th>
                                    <th class="text-right" width="4%"><input id="checkAllResolved" type="checkbox" value="Check All" class='checkAll'></th>
                                    <th width="10%">WC UID</th>
                                    <th width="8%">Matching Entity</th>
                                    <th width="15%">Type</th>
                                    <th width="15%">Category</th>
                                    <th width="15%">Provider Types</th>
                                    <th width="15%">Registerd  Country</th>
                                    <th width="14%">Match Strength</th>
                                </tr>

                            </thead>
                            <tbody id="similarRecords" class='similarRecordsResolved'>
                                <?php
                                 $ExactCount = 0;
                                    $FalseCount = 0;

                                    if(isset($dataArray['results']) && $dataArray!='') {
                                      if($dataArray['caseId']) {
                                            $caseId = $dataArray['caseId'];
                                        }
                                        if(isset($dataArray['caseSystemId'])) {
                                            $caseSystemId = $dataArray['caseSystemId'];
                                        }

                                foreach($dataArray['results'] as $resultArray) {
                                    $resultId      = $resultArray['resultId'];
                                       $matchStrength = $resultArray['matchStrength'];
                                       $primaryName = $resultArray['primaryName'];
                                       $category    = $resultArray['category'];
                                       $gender      = $resultArray['gender'];
                                       $referenceId = $resultArray['referenceId'];
                                       $wcUID = str_replace('e_tr_wco_','',$referenceId);
                                       $providerTypes = $resultArray['providerType'];
                                       $resolutionArray = $resultArray['resolution'];
                                       ////Events DOB
                                       $eventsDataDOB = "";
                                       $countryLocation = "";
                                       $identityDocumentsNumber = "";
                                       $identityDocumentsType = "";
                                       $nationality = "";

                                       $eventsArray = $resultArray['events'];



                                       // PEP Type
                                       $pepTypeArray = [];
                                       $type = "";
                                       $pepcategoryName = "";
                                       $pepTypeArray = $resultArray['categories'];

                                       foreach($pepTypeArray as $pepTypeData) {
                                           $allType= $pepTypeData;
                                        }

                                       //$allType = "";
                                       $allType = str_replace('Law Enforcement', 'LE', $allType);
                                       $allType = str_replace('Regulatory Enforcement', 'RE', $allType);
                                       $allType = str_replace('Other Bodies', 'OB', $allType);
                                       $type    = $allType;


                                       ////Country Events
                                       $countryLinksArray = $resultArray['countryLinks'];
                                       if(count($countryLinksArray) > 0) {
                                           foreach($countryLinksArray as $countryLinksData)
                                              {
                                               if($countryLinksData['type']=="NATIONALITY") {
                                                  $nationality = $countryLinksData['country']['name'];
                                               }
                                               if($countryLinksData['type']=="LOCATION") {
                                                  $countryLocation = $countryLinksData['country']['name'];
                                               }

                                               if($countryLinksData['type']=="REGISTEREDIN") {
                                                  $countryRegisterd = $countryLinksData['country']['name'];
                                               }
                                              }
                                       }
                                       ///Identity
                                       $identityDocumentsArray = $resultArray['identityDocuments'];
                                       $BindData = '';
                                       //$BindData = $referenceId."#".$primaryName."#".$category."#".$providerTypes."#".$gender."#".$eventsDataDOB."#".$countryLinksName."#".$identityDocumentsType."#".$identityDocumentsNumber;
                                       $BindData = $referenceId."#".$primaryName."#".$matchStrength."#".$type."#unresolved";


                                       if(is_null($resolutionArray) && !in_array($resultId,$matchresultArray)) {
                                       ?>
                                        <tr class="bg-color-gray">
                                            <td class="text-right" width="3%">
                                                 <div class="d-flex align-items-center">
                                                 <input type="radio" name="kycdetailID" id="kycdetailID_<?php echo $i;?>" value="<?php echo $BindData;?>" class="kycdetailID">
                                                 <input type="hidden" name="hiddenval" value="<?php echo $BindData;?>">

                                                 <!--<input type="button" value="Generate Report" id="getfullDetail_<?php echo $i;?>" name="getfullDetail" class="getfullDetail btn btn-save btn-sm">-->
                                                 </div>
                                              </td>
                                              <td width="4%"><input class="result_id_checkbox" id="result_id_checkbox" name="result_id_checkbox" type="checkbox" value="{{$resultId}}"></td>

                                             <td width="10%"><?php echo $wcUID;?></td>
                                             <td width="8%"><?php echo $primaryName;?></td>
                                             <td width="15%"><?php echo $type;?></td>
                                             <td width="15%"><?php echo $category;?></td>
                                             <td width="15%"><?php echo $providerTypes;?></td>

                                             <td width="15%"><?php echo $countryRegisterd;?></td>

                                             <td width="14%"><?php echo $matchStrength;?>
                                             <input type="hidden" name="matchCombine" id="matchCombine_<?php echo $i;?>" value="<?php echo $matchStrength."#".$type."#".$caseId."#".$caseSystemId."#".$resultId;?>">
                                             </td>
                                        </tr>
                                        <tr>
                                            <td colspan="8" id="profileDetail_unresolved_<?php echo $i;?>"></td>
                                        </tr>

                                       <?php }

                                            
                                             $i++;
                                             }
                                        } else { ?>
                                                <tr>
                                                    <td colspan="10"> No Match Found</td>
                                                </tr>

                                       <?php }
                                        
                                     ?>
                            </tbody>
                        </table>


                   </div>



                <div class="tab-pane container  p-0 mt-4" id="tab031">

                    <table class="table table-striped table-sm font-size-13 brown-light-th">
                               <thead>
                                <tr>
                                    <th class="text-right" width="3%">&nbsp;</th>
                                    <th class="text-right" width="4%">
                                        <?php if($falseStatus =="FALSE" && count($False_Array) > 1)
                                 { ?>

                                <input id="checkAllAutoRes" type="checkbox" value="Check All" class='checkAll'>
                                 <?php } ?>

                                    </th>
                                    <th width="10%">WC UID</th>
                                    <th width="8%">Matching Entity</th>
                                    <th width="15%">Type</th>
                                    <th width="15%">Category</th>
                                    <th width="15%">Provider Types</th>
                                    <th width="15%">Registerd  Country</th>
                                    <th width="14%">Match Strength</th>
                                </tr>

                            </thead>
                            <tbody id="similarRecords" class="similarRecordsAutoRes">
                                <?php
                                 $ExactCount = 0;
                                    $FalseCount = 0;

                                    if(isset($dataArray['results']) && $dataArray!='') {
                                     if($dataArray['caseId']) {
                                            $caseId = $dataArray['caseId'];
                                        }
                                        if(isset($dataArray['caseSystemId'])) {
                                            $caseSystemId = $dataArray['caseSystemId'];
                                        }

                                foreach($dataArray['results'] as $resultArray) {
                                    $resultId      = $resultArray['resultId'];
                                       $matchStrength = $resultArray['matchStrength'];
                                       $primaryName = $resultArray['primaryName'];
                                       $category    = $resultArray['category'];
                                       $gender      = $resultArray['gender'];
                                       $referenceId = $resultArray['referenceId'];
                                       $wcUID = str_replace('e_tr_wco_','',$referenceId);
                                       $providerTypes = $resultArray['providerType'];
                                       $resolutionArray = $resultArray['resolution'];
                                       ////Events DOB
                                       $eventsDataDOB = "";
                                       $countryLocation = "";
                                       $identityDocumentsNumber = "";
                                       $identityDocumentsType = "";
                                       $nationality = "";

                                       $eventsArray = $resultArray['events'];



                                       // PEP Type
                                       $pepTypeArray = [];
                                       $type = "";
                                       $pepcategoryName = "";
                                       $pepTypeArray = $resultArray['categories'];

                                       foreach($pepTypeArray as $pepTypeData) {
                                           $allType= $pepTypeData;
                                        }

                                       //$allType = "";
                                       $allType = str_replace('Law Enforcement', 'LE', $allType);
                                       $allType = str_replace('Regulatory Enforcement', 'RE', $allType);
                                       $allType = str_replace('Other Bodies', 'OB', $allType);
                                       $type    = $allType;


                                       ////Country Events
                                       $countryLinksArray = $resultArray['countryLinks'];
                                       if(count($countryLinksArray) > 0) {
                                           foreach($countryLinksArray as $countryLinksData)
                                              {
                                               if($countryLinksData['type']=="NATIONALITY") {
                                                  $nationality = $countryLinksData['country']['name'];
                                               }
                                               if($countryLinksData['type']=="LOCATION") {
                                                  $countryLocation = $countryLinksData['country']['name'];
                                               }

                                               if($countryLinksData['type']=="REGISTEREDIN") {
                                                  $countryRegisterd = $countryLinksData['country']['name'];
                                               }
                                              }
                                       }
                                       ///Identity
                                       $identityDocumentsArray = $resultArray['identityDocuments'];
                                       $BindData = '';
                                       //$BindData = $referenceId."#".$primaryName."#".$category."#".$providerTypes."#".$gender."#".$eventsDataDOB."#".$countryLinksName."#".$identityDocumentsType."#".$identityDocumentsNumber;
                                       $BindData = $referenceId."#".$primaryName."#".$matchStrength."#".$type."#falsecheck";
                                        if($falseStatus =="FALSE" && in_array($resultId,$False_Array)) { ?>
                                        <tr class="bg-color-gray">
                                            <td class="text-right" width="3%">
                                                 <div class="d-flex align-items-center">
                                                 <input type="radio" name="kycdetailID" id="kycdetailID_<?php echo $i;?>" value="<?php echo $BindData;?>" class="kycdetailID">
                                                 <input type="hidden" name="hiddenval" value="<?php echo $BindData;?>">

                                                 <!--<input type="button" value="Generate Report" id="getfullDetail_<?php echo $i;?>" name="getfullDetail" class="getfullDetail btn btn-save btn-sm">-->
                                                 </div>
                                              </td>
                                              <td width="4%"><input class="result_id_checkbox" id="result_id_checkbox" name="result_id_checkbox" type="checkbox" value="{{$resultId}}"></td>

                                             <td width="10%"><?php echo $wcUID;?></td>
                                             <td width="8%"><?php echo $primaryName;?></td>
                                             <td width="15%"><?php echo $type;?></td>
                                             <td width="15%"><?php echo $category;?></td>
                                             <td width="15%"><?php echo $providerTypes;?></td>

                                             <td width="15%"><?php echo $countryRegisterd;?></td>

                                             <td width="14%"><?php echo $matchStrength;?>
                                             <input type="hidden" name="matchCombine" id="matchCombine_<?php echo $i;?>" value="<?php echo $matchStrength."#".$type."#".$caseId."#".$caseSystemId."#".$resultId;?>">
                                             </td>
                                        </tr>
                                        <tr>
                                            <td colspan="8" id="profileDetail_falsecheck_<?php echo $i;?>"></td>
                                        </tr>

                                        <?php }
                                        if(!is_null($resolutionArray)) {
                                            $resolutionStatusID = $resultArray['resolution']['statusId'];
                                            $resolutionRiskID = $resultArray['resolution']['riskId'];
                                            $resolutionresaonID = $resultArray['resolution']['reasonId'];
                                            if(($resolutionStatusID!='' && is_null($resolutionRiskID) && is_null($resolutionresaonID))) {
                                       ?>
                                        <tr class="bg-color-gray">
                                            <td class="text-right" width="3%">
                                                 <div class="d-flex align-items-center">
                                                 <input type="radio" name="kycdetailID" id="kycdetailID_<?php echo $i;?>" value="<?php echo $BindData;?>" class="kycdetailID">
                                                 <input type="hidden" name="hiddenval" value="<?php echo $BindData;?>">

                                                 <!--<input type="button" value="Generate Report" id="getfullDetail_<?php echo $i;?>" name="getfullDetail" class="getfullDetail btn btn-save btn-sm">-->
                                                 </div>
                                              </td>
                                              <td width="4%"><!--<input class="result_id_checkbox" id="result_id_checkbox" name="result_id_checkbox" type="checkbox" value="{{$resultId}}">--></td>

                                             <td width="10%"><?php echo $wcUID;?></td>
                                             <td width="8%"><?php echo $primaryName;?></td>
                                             <td width="15%"><?php echo $type;?></td>
                                             <td width="15%"><?php echo $category;?></td>
                                             <td width="15%"><?php echo $providerTypes;?></td>
                                             <td width="15%"><?php echo $countryRegisterd;?></td>
                                             <td width="14%"><?php echo $matchStrength;?>
                                             <input type="hidden" name="matchCombine" id="matchCombine_<?php echo $i;?>" value="<?php echo $matchStrength."#".$type."#".$caseId."#".$caseSystemId."#".$resultId;?>">
                                             </td>
                                        </tr>
                                        <tr>
                                            <td colspan="8" id="profileDetail_falsecheck_<?php echo $i;?>"></td>
                                        </tr>

                                        <?php }
                                            }
                                           $i++;
                                         }
                                        } else { ?>
                                            <tr>
                                                <td colspan="10" id="profileDetail_<?php echo $i;?>"></td>
                                            </tr>
                                       <?php } ?>
                            </tbody>
                        </table>
                </div>

                <div class="tab-pane container  p-0 mt-4" id="tab041">

                    <table class="table table-striped table-sm font-size-13 brown-light-th">
                               <thead>
                                <tr>
                                    <th class="text-right" width="3%">&nbsp;</th>
                                    <th class="text-right" width="4%">
                                        <input id="checkAllPositive" type="checkbox" value="Check All" class='checkAll'>

                                    </th>
                                    <th width="10%">WC UID</th>
                                    <th width="8%">Matching Entity</th>
                                    <th width="15%">Type</th>
                                    <th width="15%">Category</th>
                                    <th width="15%">Provider Types</th>
                                    <th width="15%">Registerd  Country</th>
                                    <th width="14%">Match Strength</th>
                                </tr>

                            </thead>
                            <tbody id="similarRecords" class='similarRecordsPositive'>
                                <?php
                                 $ExactCount = 0;
                                    $FalseCount = 0;

                                    if(isset($dataArray['results']) && $dataArray!='') {
                                     if($dataArray['caseId']) {
                                            $caseId = $dataArray['caseId'];
                                        }
                                        if(isset($dataArray['caseSystemId'])) {
                                            $caseSystemId = $dataArray['caseSystemId'];
                                        }

                                foreach($dataArray['results'] as $resultArray) {
                                    $resultId      = $resultArray['resultId'];
                                       $matchStrength = $resultArray['matchStrength'];
                                       $primaryName = $resultArray['primaryName'];
                                       $category    = $resultArray['category'];
                                       $gender      = $resultArray['gender'];
                                       $referenceId = $resultArray['referenceId'];
                                       $wcUID = str_replace('e_tr_wco_','',$referenceId);
                                       $providerTypes = $resultArray['providerType'];
                                       $resolutionArray = $resultArray['resolution'];
                                       ////Events DOB
                                       $eventsDataDOB = "";
                                       $countryLocation = "";
                                       $identityDocumentsNumber = "";
                                       $identityDocumentsType = "";
                                       $nationality = "";

                                       $eventsArray = $resultArray['events'];



                                       // PEP Type
                                       $pepTypeArray = [];
                                       $type = "";
                                       $pepcategoryName = "";
                                       $pepTypeArray = $resultArray['categories'];

                                       foreach($pepTypeArray as $pepTypeData) {
                                           $allType= $pepTypeData;
                                        }

                                       //$allType = "";
                                       $allType = str_replace('Law Enforcement', 'LE', $allType);
                                       $allType = str_replace('Regulatory Enforcement', 'RE', $allType);
                                       $allType = str_replace('Other Bodies', 'OB', $allType);
                                       $type    = $allType;


                                       ////Country Events
                                       $countryLinksArray = $resultArray['countryLinks'];
                                       if(count($countryLinksArray) > 0) {
                                           foreach($countryLinksArray as $countryLinksData)
                                              {
                                               if($countryLinksData['type']=="NATIONALITY") {
                                                  $nationality = $countryLinksData['country']['name'];
                                               }
                                               if($countryLinksData['type']=="LOCATION") {
                                                  $countryLocation = $countryLinksData['country']['name'];
                                               }

                                               if($countryLinksData['type']=="REGISTEREDIN") {
                                                  $countryRegisterd = $countryLinksData['country']['name'];
                                               }
                                              }
                                       }
                                       ///Identity
                                       $identityDocumentsArray = $resultArray['identityDocuments'];
                                       $BindData = '';
                                       //$BindData = $referenceId."#".$primaryName."#".$category."#".$providerTypes."#".$gender."#".$eventsDataDOB."#".$countryLinksName."#".$identityDocumentsType."#".$identityDocumentsNumber;
                                       $BindData = $referenceId."#".$primaryName."#".$matchStrength."#".$type."#positive";

                                        $resolutionStatusID = $resultArray['resolution']['statusId'];
                                        $resolutionRiskID   = $resultArray['resolution']['riskId'];
                                        $resolutionresaonID = $resultArray['resolution']['reasonId'];
                                        if($positiveStatus =="POSITIVE" && in_array($resultId,$Positive_Array)) {
                                            ?>

                                        <tr class="bg-color-gray">
                                            <td class="text-right" width="3%">
                                                 <div class="d-flex align-items-center">
                                                 <input type="radio" name="kycdetailID" id="kycdetailID_<?php echo $i;?>" value="<?php echo $BindData;?>" class="kycdetailID">
                                                 <input type="hidden" name="hiddenval" value="<?php echo $BindData;?>">

                                                 <!--<input type="button" value="Generate Report" id="getfullDetail_<?php echo $i;?>" name="getfullDetail" class="getfullDetail btn btn-save btn-sm">-->
                                                 </div>
                                              </td>
                                              <td width="4%"><input class="result_id_checkbox" id="result_id_checkbox" name="result_id_checkbox" type="checkbox" value="{{$resultId}}"></td>

                                             <td width="10%"><?php echo $wcUID;?></td>
                                             <td width="8%"><?php echo $primaryName;?></td>
                                             <td width="15%"><?php echo $type;?></td>
                                             <td width="15%"><?php echo $category;?></td>
                                             <td width="15%"><?php echo $providerTypes;?></td>

                                             <td width="15%"><?php echo $countryRegisterd;?></td>

                                             <td width="14%"><?php echo $matchStrength;?>
                                             <input type="hidden" name="matchCombine" id="matchCombine_<?php echo $i;?>" value="<?php echo $matchStrength."#".$type."#".$caseId."#".$caseSystemId."#".$resultId;?>">
                                             </td>
                                        </tr>
                                        <tr>
                                            <td colspan="8" id="profileDetail_positive_<?php echo $i;?>"></td>
                                        </tr>

                                        <?php }
                                             $i++;
                                             }
                                        } else { ?>
                                            <tr>
                                                <td colspan="10" id="profileDetail_<?php echo $i;?>"></td>
                                            </tr>
                                       <?php } ?>
                            </tbody>
                        </table>
                </div>

                <div class="tab-pane container  p-0 mt-4" id="tab051">

                    <table class="table table-striped table-sm font-size-13 brown-light-th">
                               <thead>
                                <tr>
                                    <th class="text-right" width="3%">&nbsp;</th>
                                    <th class="text-right" width="4%">
                                        <input id="checkAllPossible" type="checkbox" value="Check All" class='checkAll'>

                                    </th>
                                    <th width="10%">WC UID</th>
                                    <th width="8%">Matching Entity</th>
                                    <th width="15%">Type</th>
                                    <th width="15%">Category</th>
                                    <th width="15%">Provider Types</th>
                                    <th width="15%">Registerd  Country</th>
                                    <th width="14%">Match Strength</th>
                                </tr>

                            </thead>
                            <tbody id="similarRecords" class='similarRecordsPossible'>
                                <?php
                                 $ExactCount = 0;
                                    $FalseCount = 0;

                                    if(isset($dataArray['results']) && $dataArray!='') {
                                     if($dataArray['caseId']) {
                                            $caseId = $dataArray['caseId'];
                                        }
                                        if(isset($dataArray['caseSystemId'])) {
                                            $caseSystemId = $dataArray['caseSystemId'];
                                        }

                                foreach($dataArray['results'] as $resultArray) {
                                    $resultId      = $resultArray['resultId'];
                                       $matchStrength = $resultArray['matchStrength'];
                                       $primaryName = $resultArray['primaryName'];
                                       $category    = $resultArray['category'];
                                       $gender      = $resultArray['gender'];
                                       $referenceId = $resultArray['referenceId'];
                                       $wcUID = str_replace('e_tr_wco_','',$referenceId);
                                       $providerTypes = $resultArray['providerType'];
                                       $resolutionArray = $resultArray['resolution'];
                                       ////Events DOB
                                       $eventsDataDOB = "";
                                       $countryLocation = "";
                                       $identityDocumentsNumber = "";
                                       $identityDocumentsType = "";
                                       $nationality = "";

                                       $eventsArray = $resultArray['events'];



                                       // PEP Type
                                       $pepTypeArray = [];
                                       $type = "";
                                       $pepcategoryName = "";
                                       $pepTypeArray = $resultArray['categories'];

                                       foreach($pepTypeArray as $pepTypeData) {
                                           $allType= $pepTypeData;
                                        }

                                       //$allType = "";
                                       $allType = str_replace('Law Enforcement', 'LE', $allType);
                                       $allType = str_replace('Regulatory Enforcement', 'RE', $allType);
                                       $allType = str_replace('Other Bodies', 'OB', $allType);
                                       $type    = $allType;


                                       ////Country Events
                                       $countryLinksArray = $resultArray['countryLinks'];
                                       if(count($countryLinksArray) > 0) {
                                           foreach($countryLinksArray as $countryLinksData)
                                              {
                                               if($countryLinksData['type']=="NATIONALITY") {
                                                  $nationality = $countryLinksData['country']['name'];
                                               }
                                               if($countryLinksData['type']=="LOCATION") {
                                                  $countryLocation = $countryLinksData['country']['name'];
                                               }

                                               if($countryLinksData['type']=="REGISTEREDIN") {
                                                  $countryRegisterd = $countryLinksData['country']['name'];
                                               }
                                              }
                                       }
                                       ///Identity
                                       $identityDocumentsArray = $resultArray['identityDocuments'];
                                       $BindData = '';
                                       //$BindData = $referenceId."#".$primaryName."#".$category."#".$providerTypes."#".$gender."#".$eventsDataDOB."#".$countryLinksName."#".$identityDocumentsType."#".$identityDocumentsNumber;
                                       $BindData = $referenceId."#".$primaryName."#".$matchStrength."#".$type."#posssible";

                                        $resolutionStatusID = $resultArray['resolution']['statusId'];
                                        $resolutionRiskID   = $resultArray['resolution']['riskId'];
                                        $resolutionresaonID = $resultArray['resolution']['reasonId'];
                                        if($possibleStatus =="POSSIBLE" && in_array($resultId,$Possible_Array)) { ?>


                                        <tr class="bg-color-gray">
                                            <td class="text-right" width="3%">
                                                 <div class="d-flex align-items-center">
                                                 <input type="radio" name="kycdetailID" id="kycdetailID_<?php echo $i;?>" value="<?php echo $BindData;?>" class="kycdetailID">
                                                 <input type="hidden" name="hiddenval" value="<?php echo $BindData;?>">

                                                 <!--<input type="button" value="Generate Report" id="getfullDetail_<?php echo $i;?>" name="getfullDetail" class="getfullDetail btn btn-save btn-sm">-->
                                                 </div>
                                              </td>
                                              <td width="4%"><input class="result_id_checkbox" id="result_id_checkbox" name="result_id_checkbox" type="checkbox" value="{{$resultId}}"></td>

                                             <td width="10%"><?php echo $wcUID;?></td>
                                             <td width="8%"><?php echo $primaryName;?></td>
                                             <td width="15%"><?php echo $type;?></td>
                                             <td width="15%"><?php echo $category;?></td>
                                             <td width="15%"><?php echo $providerTypes;?></td>

                                             <td width="15%"><?php echo $countryRegisterd;?></td>

                                             <td width="14%"><?php echo $matchStrength;?>
                                             <input type="hidden" name="matchCombine" id="matchCombine_<?php echo $i;?>" value="<?php echo $matchStrength."#".$type."#".$caseId."#".$caseSystemId."#".$resultId;?>">
                                             </td>
                                        </tr>
                                        <tr>
                                            <td colspan="8" id="profileDetail_posssible_<?php echo $i;?>"></td>
                                        </tr>

                                        <?php }
                                             $i++;
                                             }
                                        } else { ?>
                                            <tr>
                                                <td colspan="10" id="profileDetail_<?php echo $i;?>"></td>
                                            </tr>
                                       <?php } ?>
                            </tbody>
                        </table>
                </div>

                <div class="tab-pane container  p-0 mt-4" id="tab061">

                    <table class="table table-striped table-sm font-size-13 brown-light-th">
                               <thead>
                                <tr>
                                    <th class="text-right" width="3%">&nbsp;</th>
                                    <th class="text-right" width="4%">
                                        <input id="checkAllUnspecified" type="checkbox" value="Check All" class='checkAll'>

                                    </th>
                                    <th width="10%">WC UID</th>
                                    <th width="8%">Matching Entity</th>
                                    <th width="15%">Type</th>
                                    <th width="15%">Category</th>
                                    <th width="15%">Provider Types</th>
                                    <th width="15%">Registerd  Country</th>
                                    <th width="14%">Match Strength</th>
                                </tr>

                            </thead>
                            <tbody id="similarRecords" class='similarRecordsUnspecified'>
                                <?php
                                 $ExactCount = 0;
                                    $FalseCount = 0;

                                    if(isset($dataArray['results']) && $dataArray!='') {
                                     if($dataArray['caseId']) {
                                            $caseId = $dataArray['caseId'];
                                        }
                                        if(isset($dataArray['caseSystemId'])) {
                                            $caseSystemId = $dataArray['caseSystemId'];
                                        }

                                foreach($dataArray['results'] as $resultArray) {
                                    $resultId      = $resultArray['resultId'];
                                       $matchStrength = $resultArray['matchStrength'];
                                       $primaryName = $resultArray['primaryName'];
                                       $category    = $resultArray['category'];
                                       $gender      = $resultArray['gender'];
                                       $referenceId = $resultArray['referenceId'];
                                       $wcUID = str_replace('e_tr_wco_','',$referenceId);
                                       $providerTypes = $resultArray['providerType'];
                                       $resolutionArray = $resultArray['resolution'];
                                       ////Events DOB
                                       $eventsDataDOB = "";
                                       $countryLocation = "";
                                       $identityDocumentsNumber = "";
                                       $identityDocumentsType = "";
                                       $nationality = "";

                                       $eventsArray = $resultArray['events'];



                                       // PEP Type
                                       $pepTypeArray = [];
                                       $type = "";
                                       $pepcategoryName = "";
                                       $pepTypeArray = $resultArray['categories'];

                                       foreach($pepTypeArray as $pepTypeData) {
                                           $allType= $pepTypeData;
                                        }

                                       //$allType = "";
                                       $allType = str_replace('Law Enforcement', 'LE', $allType);
                                       $allType = str_replace('Regulatory Enforcement', 'RE', $allType);
                                       $allType = str_replace('Other Bodies', 'OB', $allType);
                                       $type    = $allType;


                                       ////Country Events
                                       $countryLinksArray = $resultArray['countryLinks'];
                                       if(count($countryLinksArray) > 0) {
                                           foreach($countryLinksArray as $countryLinksData)
                                              {
                                               if($countryLinksData['type']=="NATIONALITY") {
                                                  $nationality = $countryLinksData['country']['name'];
                                               }
                                               if($countryLinksData['type']=="LOCATION") {
                                                  $countryLocation = $countryLinksData['country']['name'];
                                               }

                                               if($countryLinksData['type']=="REGISTEREDIN") {
                                                  $countryRegisterd = $countryLinksData['country']['name'];
                                               }
                                              }
                                       }
                                       ///Identity
                                       $identityDocumentsArray = $resultArray['identityDocuments'];
                                       $BindData = '';
                                       //$BindData = $referenceId."#".$primaryName."#".$category."#".$providerTypes."#".$gender."#".$eventsDataDOB."#".$countryLinksName."#".$identityDocumentsType."#".$identityDocumentsNumber;
                                       $BindData = $referenceId."#".$primaryName."#".$matchStrength."#".$type."#unspecified";

                                        $resolutionStatusID = $resultArray['resolution']['statusId'];
                                        $resolutionRiskID   = $resultArray['resolution']['riskId'];
                                        $resolutionresaonID = $resultArray['resolution']['reasonId'];
                                        if($unspecifiedStatus =="UNSPECIFIED" && in_array($resultId,$Unspecified_Array)) { ?>


                                        <tr class="bg-color-gray">
                                            <td class="text-right" width="3%">
                                                 <div class="d-flex align-items-center">
                                                 <input type="radio" name="kycdetailID" id="kycdetailID_<?php echo $i;?>" value="<?php echo $BindData;?>" class="kycdetailID">
                                                 <input type="hidden" name="hiddenval" value="<?php echo $BindData;?>">

                                                 <!--<input type="button" value="Generate Report" id="getfullDetail_<?php echo $i;?>" name="getfullDetail" class="getfullDetail btn btn-save btn-sm">-->
                                                 </div>
                                              </td>
                                              <td width="4%"><input class="result_id_checkbox" id="result_id_checkbox" name="result_id_checkbox" type="checkbox" value="{{$resultId}}"></td>

                                             <td width="10%"><?php echo $wcUID;?></td>
                                             <td width="8%"><?php echo $primaryName;?></td>
                                             <td width="15%"><?php echo $type;?></td>
                                             <td width="15%"><?php echo $category;?></td>
                                             <td width="15%"><?php echo $providerTypes;?></td>

                                             <td width="15%"><?php echo $countryRegisterd;?></td>

                                             <td width="14%"><?php echo $matchStrength;?>
                                             <input type="hidden" name="matchCombine" id="matchCombine_<?php echo $i;?>" value="<?php echo $matchStrength."#".$type."#".$caseId."#".$caseSystemId."#".$resultId;?>">
                                             </td>
                                        </tr>
                                        <tr>
                                            <td colspan="8" id="profileDetail_unspecified_<?php echo $i;?>"></td>
                                        </tr>

                                        <?php }
                                             $i++;
                                             }
                                        } else { ?>
                                            <tr>
                                                <td colspan="10" id="profileDetail_<?php echo $i;?>"></td>
                                            </tr>
                                       <?php } ?>
                            </tbody>
                        </table>
                </div>

            </div>
        </div>
                    <!-- Tab Section End -->
                    </div>
                    <?php } ?>
                    <!--Similar records End-->
                </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection
@section('pageTitle')
User Detail
@endsection




@section('iframe')
 <div class="modal" id="btn-resolution-iframe">
        <div class="modal-dialog modal-md custom-modal">
          <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Action</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body" style="padding: 0.5rem;">
              <iframe id ="frameset"  frameborder="0" scrolling="no"></iframe>
            </div>
          </div>
        </div>
    </div>

<div class="modal" id="btn-disapproved-iframe">
        <div class="modal-dialog modal-md custom-modal">
          <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">Action</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body" style="padding: 0.5rem;">
              <iframe id ="frameset"  frameborder="0" scrolling="no"></iframe>
            </div>
          </div>
        </div>
    </div>



@endsection

@section('jscript')

<style>
.custom-modal .modal-content{
    background: #f1f1f1;
    padding: 0px;
}
.custom-modal .modal-body {
    padding: 20px !important;
}


</style>


<script>
 var messages = {
        get_users_wci_corp: "{{ URL::route('get_users_wci_corp') }}",
        get_users_wci_corp_single: "{{ URL::route('get_users_wci_corp_single') }}",
        delete_users: "{{ URL::route('delete_users') }}",
        data_not_found: "{{ trans('error_messages.data_not_found') }}",
        token: "{{ csrf_token() }}",
        token2: "{{ csrf_token() }}",
        APISecret: "{{config('common.APISecret')}}",
        gatwayurl: "{{config('common.gatwayurl')}}",
        contentType: "{{config('common.contentType')}}",
        gatwayhost: "{{config('common.gatwayhost')}}",
        apiKey: "{{config('common.apiKey')}}",
        groupId: "{{config('common.groupId')}}",
        user_kyc_id: "{{ $userKycId }}",
        datetime : "<?php echo gmdate('D, d M Y H:i:s \G\M\T', time());?>",
        get_users_resolved_all : "{{ URL::route('get_users_resolved_all') }}",
     
    };


$('.sub-tab').on('click',function(){
    $('.sub-tab').removeClass('active');
    $(this).addClass('active');
});

$('.parent-tab').on('click',function(){
    $('.parent-tab').removeClass('active');
    $(this).addClass('active');
    $('.checkAll').prop('checked', false);
    $('#similarRecords input').prop('checked', false);
    $('#result_id').val('');
});
$('.kycdetailID').on('click',function(){
   var radioID    = $("input[name='kycdetailID']:checked").attr('id');
   var getfullDetailIDArray = radioID.split('_');
   var matchVal  = $("#matchCombine_"+getfullDetailIDArray[1]).val();
   $splitmatchArray = matchVal.split("#");
   $("#matchStrenght").val($splitmatchArray[0]);
   $("#pep").val($splitmatchArray[1]);
   $("#caseId").val($splitmatchArray[2]);
   $("#systemcaseId").val($splitmatchArray[3]);
   $("#resultId").val($splitmatchArray[4]);
});


$("#resolutionStatus").change(function(event) {
   // Get the week just selected.
   var status = $("#resolutionStatus").val();
   if(status == 1) {
       var disable = 5;
       $("option.resolutionRisk").removeAttr("disabled");
       $("option.resolutionReason").removeAttr("disabled");
       $("option.resolutionRisk[value="+disable+"]").attr("disabled", "disabled");
       $("option.resolutionReason[value=9]").attr("disabled", "disabled");
       $("option.resolutionReason[value=11]").attr("disabled", "disabled");
       $("option.resolutionReason[value=12]").attr("disabled", "disabled");
   } else if(status == 2) {
       var disable = 5;
       $("option.resolutionRisk").removeAttr("disabled");
       $("option.resolutionReason").removeAttr("disabled");
       $("option.resolutionReason[value=9]").attr("disabled", "disabled");
       $("option.resolutionReason[value=10]").attr("disabled", "disabled");
       $("option.resolutionReason[value=12]").attr("disabled", "disabled");

   } else if(status == 3) {
        var disable1 = 6;
       var disable2 = 7;
       var disable3 = 8;
       $("option.resolutionRisk").removeAttr("disabled");
       $("option.resolutionReason").removeAttr("disabled");
       $("option.resolutionRisk[value="+disable1+"]").attr("disabled", "disabled");
       $("option.resolutionRisk[value="+disable2+"]").attr("disabled", "disabled");
       $("option.resolutionRisk[value="+disable3+"]").attr("disabled", "disabled");
       $("option.resolutionReason[value=10]").attr("disabled", "disabled");
       $("option.resolutionReason[value=11]").attr("disabled", "disabled");
       $("option.resolutionReason[value=12]").attr("disabled", "disabled");
   } else if(status == 4) {
       var disable1 = 6;
       var disable2 = 7;
       var disable3 = 8;
       $("option.resolutionRisk").removeAttr("disabled");
       $("option.resolutionReason").removeAttr("disabled");
       $("option.resolutionRisk[value="+disable1+"]").attr("disabled", "disabled");
       $("option.resolutionRisk[value="+disable2+"]").attr("disabled", "disabled");
       $("option.resolutionRisk[value="+disable3+"]").attr("disabled", "disabled");
        $("option.resolutionReason[value=9]").attr("disabled", "disabled");
       $("option.resolutionReason[value=10]").attr("disabled", "disabled");
       $("option.resolutionReason[value=11]").attr("disabled", "disabled");
   } else if(status == '') {
       $("option.resolutionRisk").attr("disabled", "disabled");
        $("option.resolutionReason").attr("disabled", "disabled");
   }
    var token       = "{{ csrf_token() }}";
    var get_toolkit_value = "{{ URL::route('get_toolkit_value') }}";

        var myKeyVals = {_token: token, id: status};
        $.ajax({
            type: 'POST',
            url: get_toolkit_value,
            data: myKeyVals,
            dataType: "text",

            success: function (resultData) {
               // console.log(resultData); return false;
                    $("#statuskeyValue").val(resultData.trim());

            }
        });
});

$("#resolutionRisk").change(function(event) {
   // Get the week just selected.
   var status = $("#resolutionRisk").val();
   var token       = "{{ csrf_token() }}";
    var get_toolkit_value = "{{ URL::route('get_toolkit_value') }}";

        var myKeyVals = {_token: token, id: status};
        $.ajax({
            type: 'POST',
            url: get_toolkit_value,
            data: myKeyVals,
            dataType: "text",

            success: function (resultData) {
               // console.log(resultData); return false;
                    $("#riskkeyvalue").val(resultData.trim());
            }
        });

});



$("#resolutionReason").change(function(event) {
   // Get the week just selected.
   var status = $("#resolutionReason").val();
   var token       = "{{ csrf_token() }}";
    var get_toolkit_value = "{{ URL::route('get_toolkit_value') }}";

        var myKeyVals = {_token: token, id: status};
        $.ajax({
            type: 'POST',
            url: get_toolkit_value,
            data: myKeyVals,
            dataType: "text",

            success: function (resultData) {
               // console.log(resultData); return false;
                    $("#reasonkeyalue").val(resultData.trim());
            }
        });

});
$('#corpResolveFormAll').validate({
        ignore: [],
        rules: {

             resolutionStatus : {
               required: true,
            },

            resolutionRisk : {
               required: true,
            },
            resolutionReason : {
               required: true,
            },


        },
        messages:{


            resolutionStatus: {
                required: messages.req_this_field,
            },

             resolutionRisk: {
                required: messages.req_this_field,
            },
            resolutionReason: {
                required: messages.req_this_field,
            },
        }

        });

//
</script>
<style>
    .font-size-13 tr td, .font-size-13 tr th{
        font-size: 13px !important;
    }
  .dashbord-white .form-section {
    padding: 30px 15px 35px 20px;
}
.background-heading{
     background: #743939de;
    color: #fff !important;
    padding: 5px 10px;
}
.btn.btn-sm.btn-approved{
    font-size: 13px;
}
.set-label .view-detail {
    padding: 0px 35px;
}
.bg-color-gray {
    background: #855152 !important;
}
.bg-color-gray td{
    color:#fff !important;
}
.table.brown-light-th th {
    /*background: #a06868de;*/
    color: #855152;
}
.table-info-cls tr:nth-child(even) {background: #CCC}
.table-info-cls tr:first-child{border:0px;}
.table-info-cls tr{ border:1px solid #ccc;}
.table-info-cls td, .table-info-cls th{padding: 5px;}
.color-brown{color: #531919; width:80%;}
.table-info-cls h4{font-weight: 700;}

</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>
<script src="{{ asset('backend/js/wciapicall_corporate.js') }}" type="text/javascript"></script>
@endsection

