
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


<style>
           /** Define the margins of your page **/
           @page {
               margin-bottom: 100px;
           }

           header {
               position: fixed;
               top: -60px;
               left: 0px;
               right: 0px;
               height: 50px;

               /** Extra personal styles
               background-color: #03a9f4;**/
               color: black;
               text-align: center;
               line-height: 35px;
           }

           footer {
               position: fixed;
               bottom: -60px;
               left: 0px;
               right: 0px;
               height: 60px;

               /** Extra personal styles **/
               background-color: #fff;
               color: #000;
               text-align: center;
               line-height: 10px;
           }

           .table-td tr td{padding: 3px 5px;}
            .table-td-cls tr td{padding: 3px 5px;}
            .table-td-min tr td{padding: 3px 5px;}
       </style>
<div class="table-responsive">
                    <table width="700px"  cellspacing="0" border="0" bgcolor="#ffffff" style="margin:0 auto; font-family: 'Roboto', sans-serif; box-shadow: -1px -1px 11px 1px #ccc; font-size:14px;">
                    <thead>
                    <tr>
                    <th align="left" bgcolor="#bbbbbb" style="padding:2px;">
                        <table width="700px" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                        <td colspan="2" widht="700px;"><h2 style="font-weight:500;color:#636363; font-size:34px; padding: 0px 25px;">WORLD-CHECK ONE</h2></td>
                        </tr>
                        <tr>
                        <td widht="350px;"><h3 style="font-weight:300;color:#636363; font-size:18px;padding: 0px 25px;">CASE REPORT</h3></td>
                        <td widht="350px;"><h3 style="font-weight:300;color:#636363; font-size:18px;text-align: right;padding: 0px 25px;">STRICTLY CONFIDENTIAL </h3></td>
                        </tr>
                        </table>
                    </th>
                    </tr>
                    </thead>
                    </table>
                    <table width="70px" height="20px;">
                    <tr >
                        <td >&nbsp;</td>

                    </tr>
                    </table>
                    </div>
                
                
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
                     $totalMatch = 0;
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
                        $possibleStatus    = '';
                        $positiveStatus    = '';
                        $falseStatus       = '';
                        $unspecifiedStatus = '';
                        $Possible_Array    = [];
                        $Positive_Array    = [];
                        $False_Array       = [];
                        $Unspecified_Array = [];
                        $matchresultArray  = [];
                     $caseid    =   $kycDetail['caseid'];
                        $response  = $kycDetail['api_responce']; //exit;
                        $userKycId = $kycDetail['user_kyc_id'];
                        $primaryId = $kycDetail['wcapi_req_res_id'];
                        $exactcount = $kycDetail['exactcount'];
                        $falsecount = $kycDetail['falsecount'];
                        $autoresolved = $kycDetail['autoresolved'];

                        $totalMatch = ($autoresolved+$falsecount);
                       $fullName = $kycDetail['org_name'];
                       $dataArray = json_decode($response, JSON_UNESCAPED_UNICODE);
                       $countryArray = Helpers::getCountryById($kycDetail['org_country_id']);
                       $country_name = $countryArray->country_name;


                     

                       // echo "==>".$primaryId;
                      
                      $resolutionDatas = Helpers::getResolutionDatabycaseID($caseid);
                       $resolvedMatched   = $autoresolved;
                       $autoresolvedfalse = $autoresolved;
                       ////echo "<pre>";
                       //print_r($resolutionData);
                      // exit;

                       $reson_despArray =[];
                       $resolveDate_Array = [];
                       foreach($resolutionDatas as $resolutionData) {
                        if($resolutionData['status_mark']!=''){
                            $status = $resolutionData['status_mark'];
                            $risk   = $resolutionData['risk_level'];
                            $reson  = $resolutionData['reson'];
                            $reson_desp  = $resolutionData['reson_desp'];
                            $resolvedon = $resolutionData['created_at'];
                            $statusName  = Helpers::toolkettypebyId($status);
                            $riskName    = Helpers::toolkettypebyId($risk);
                            $resonName   = Helpers::toolkettypebyId($reson);

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
                        <table width="700px" class="table-td" style="margin:0 auto;font-family: 'Roboto', sans-serif;color:#636363; font-size: 14px;">
                            <tr bgcolor="#e4e4e3">
                                <td width="160px" style="font-size:14px;">Name</td>
                                <td colspan="3" width="480px" style="font-size:14px; color:#ec952d;"><?php echo ucfirst($fullName);?></td>
                            </tr>
                            <tr>
                                <td width="160px" style="font-size:14px;">World-Check Total Matches</td>
                                <td colspan="3" width="480px" style="font-size:14px;"><?php echo $totalMatch;?></td>
                            </tr>
                            <tr bgcolor="#e4e4e3">
                                <td style="font-size:14px;">Case ID</td>
                                <td colspan="3" style="font-size:14px;"><?php echo $dataArray['caseId'];?></td>
                            </tr>
                            <tr>
                                <td width="160px" style="font-size:14px;">Registered Country</td>
                                <td width="160px" style="font-size:14px;"><?php echo $country_name;?></td>
                                 <td width="160px" style="font-size:14px;">Current Group</td>
                                <td width="160px" style="font-size:14px;">Dexter Capital Financial Consultancy L.L.C - API (P)</td>
                            </tr>
                           
                            <tr bgcolor="#e4e4e3">
                                <td width="160px" style="font-size:14px;">Entity Type</td>
                                <td width="160px" style="font-size:14px;">Organisation</td>
                                <td width="160px"></td>
                                <td width="160px"></td>
                            </tr>
                        </table>
                        <table width="700px" class="table-td" cellpadding="0" cellspacing="0" style="margin:0 auto;font-family: 'Roboto', sans-serif;color:#636363; font-size: 14px;">
                                <tr class="bg-remove">

                                    <td >
                                        <table width="700px" cellspacing="0">
                                            <tr>
                                                <td colspan="4" style="color:#ec952d;" style="padding:0px;"><h4 style="font-size: 20px;font-weight: 600;margin: 10px 0px;padding: 0px;">KEY FINDINGS</h4></td>
                                            </tr>
                                            <tr bgcolor="#e4e4e3">
                                                <td width="25%" style="font-size:14px;">Total Matches:</td>
                                                <td width="15%" class="text-center"><div id="totalMatch">{{$totalMatch}}</div></td>
                                                <td width="25%" style="font-size:14px;"></td>
                                                <td width="35%" class="text-center"></td>
                                            </tr>

                                             <tr bgcolor="#fff">
                                                <td width="25%" style="font-size:14px;">Resolved Matches:</td>
                                                <td width="15%" class="text-center"><div id="resolveMatch">{{$resolvedMatched}}</div></td>
                                                <td width="50%" style="font-size:14px; text-align: left;"> <div style="margin-right:25px;display: inline;">Positive: {{$positivecount}}</div> <div style="margin-right:25px;display: inline;">Possible: {{$possbilecount}}</div> False: {{$autoresolvedfalse}}</td>
                                                <td width="10%" class="text-left" style="font-size:14px; text-align: center;">Unspecified: {{$unspecifiedcount}}</td>
                                            </tr>
                                            <tr bgcolor="#e4e4e3">
                                                <td width="30%" style="font-size:14px;">Unresolved Matches:</td>
                                                <td width="15%" class="text-center"><div id="totalMatch">{{$falsecount}}</div></td>
                                                <td width="20%" style="font-size:14px;"></td>
                                                <td width="35%" class="text-center"></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                        <table width="700px" class="table-td-cls" cellpadding="0" cellspacing="0" border="0" style="margin:0 auto;font-family: 'Roboto', sans-serif;color:#636363; font-size: 14px;">
                               <thead>
                                <tr>
                                    <td colspan="11" style="color:#ec952d;"><h4 style="font-size: 20px;font-weight: 600;margin: 10px 0px;padding: 0px;">WORLD-CHECK MATCHES</h4></td>
                                </tr>


                                <tr bgcolor="#e4e4e3">
                                    <td width="5%" style="font-size: 10px; font-weight: bold;">Resolution Status</td>
                                    <td style="font-size: 10px; font-weight: bold;">WC UID</td>
                                    <td style="font-size: 10px; font-weight: bold;">Matching Entity</td>
                                    <td style="font-size: 10px; font-weight: bold;">Type</td>
                                    <td style="font-size: 10px; font-weight: bold;">Category</td>
                                    <!--<th style="font-size: 11px; font-weight: bold;">Provider Types</th>-->
                                    <td style="font-size: 10px; font-weight: bold;">Registerd  Country</td>
                                    <td width="7%" style="font-size: 10px; font-weight: bold;">Resolved On</td>
                                    <td width="7%" style="font-size: 10px; font-weight: bold;">Risk</td>
                                    <td width="5%" style="font-size: 10px; font-weight: bold;">Resolution Reason</td>
                                    <td width="5%" style="font-size: 10px; font-weight: bold;">Resolution Comment</td>

                                    <td style="font-size: 10px; font-weight: bold;">Match Strength</td>
                                </tr>
                            
                           
                               
                            <tbody id="similarRecords">
                                <?php
                                
                                    if(isset($dataArray['results']) && $dataArray!='') {
                                      //  echo "===="; exit;
                                    $ExactCount = 0;
                                    $FalseCount = 0;
                                    $i=0;
                                    $numRows = 0;
                                foreach($dataArray['results'] as $resultArray) {
                                       $matchStrength = $resultArray['matchStrength'];
                                       $primaryName = $resultArray['primaryName'];
                                       $category    = $resultArray['category'];
                                       $gender      = $resultArray['gender'];
                                       $referenceId = $resultArray['referenceId'];
                                       $resolutionArray = @$resultArray['resolution'];

                                       $resultId    = $resultArray['resultId'];
                                       if(in_array($resultId,$matchresultArray)){


                                            $resolutionDatas = Helpers::getDatabycaseIDandresulid($caseid, $resultId);
                                            $status = $resolutionDatas[0]->status_mark;
                                            $risk = $resolutionDatas[0]->risk_level;
                                            $reson = $resolutionDatas[0]->reson;
                                            $reson_desp = $resolutionDatas[0]->reson_desp;
                                            $resolvedon = $resolutionDatas[0]->created_at;
                                          //  $statusArray = array_pop($statusArray);
                                            $statusName  = Helpers::toolkettypebyId($status);
                                            $riskName    = Helpers::toolkettypebyId($risk);
                                            $resonName   = Helpers::toolkettypebyId($reson);


                                        } else {
                                            if(!is_null($resolutionArray)) {
                                                $statusName  = 'False';
                                            }
                                            $reson_desp  = '';
                                            $matchresult_id   = '';
                                            $riskName    ='';
                                            $resonName   = '';
                                            $resolvedon = '';
                                        }

                                        if(!is_null($resolutionArray)) {

                                       $wcUID = str_replace('e_tr_wco_','',$referenceId);
                                       $providerTypes = $resultArray['providerType'];
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
                                       $BindData = $referenceId."#".$primaryName."#".$matchStrength."#".$type."#";
                                        if($matchStrength == "EXACT") {
                                            $ExactCount++;
                                        } else {
                                            $FalseCount++;
                                        }
                                        if (0 == $numRows % 2) {
                                              $style = 'bgcolor="#fff"';
                                            } else {
                                              $style = 'bgcolor="#e4e4e3"';
                                            }
                                            $numRows++;
                                       ?>
                                        <tr <?=$style?>>
                                            <td width="5%" style="font-size: 10px;"><?php echo $statusName;?></td>
                                            <td width="5%" style="font-size: 10px;"><?php echo $wcUID;?></td>
                                            <td width="18%" style="font-size: 10px;"><?php echo $primaryName;?></td>
                                            <td width="10%" style="font-size: 10px;"><?php echo $type;?></td>
                                            <td width="10%" style="font-size: 10px;"><?php echo $category;?></td>
                                            <!--<td width="15%" style="font-size: 11px;"><?php echo $providerTypes;?></td>-->
                                            <td width="10%" style="font-size: 10px;"><?php echo $countryRegisterd;?></td>

                                            <td width="7%" style="font-size: 10px;" ><?php echo $resolvedon;?></td>
                                            <td width="7%" style="font-size: 10px;" ><?php echo $riskName;?></td>
                                            <td width="5%" style="font-size: 10px;" ><?php echo $resonName;?></td>
                                            <td width="5%" style="font-size: 10px;" ><?php echo $reson_desp;?></td>


                                            <td width="17%" style="font-size: 10px;"><?php echo $matchStrength;?></td>
                                        </tr>
                                             <?php
                                             $i++;
                                              }
                                             } 
                                        }
                                       
                                      if(isset($dataArray['results']) && $dataArray!='') {
                                      //  echo "===="; exit;
                                    $ExactCount = 0;
                                    $FalseCount = 0;
                                    $i=0;
                                foreach($dataArray['results'] as $resultArray) {
                                       $matchStrength = $resultArray['matchStrength'];
                                       $primaryName = $resultArray['primaryName'];
                                       $category    = $resultArray['category'];
                                       $gender      = $resultArray['gender'];
                                       $referenceId = $resultArray['referenceId'];
                                       $resolutionArray = @$resultArray['resolution'];

                                       $resultId    = $resultArray['resultId'];
                                        if(in_array($resultId,$matchresultArray)){

                                                $matchresult_id   = $resolutionData['result_id'];
                                                $resolutionDatas = Helpers::getDatabycaseIDandresulid($caseid, $resultId);
                                                $status = $resolutionDatas[0]->status_mark;
                                                $risk = $resolutionDatas[0]->risk_level;
                                                $reson = $resolutionDatas[0]->reson;
                                                $reson_desp = $resolutionDatas[0]->reson_desp;
                                                $resolvedon = $resolutionDatas[0]->created_at;


                                                $statusName  = Helpers::toolkettypebyId($status);
                                                $riskName    = Helpers::toolkettypebyId($risk);
                                                $resonName   = Helpers::toolkettypebyId($reson);

                                        } else {
                                            if(is_null($resolutionArray)) {
                                                $statusName  = 'Unresolved';
                                            }
                                            $reson_desp  = '';
                                            $matchresult_id   = '';
                                            $riskName    ='';
                                            $resonName   = '';
                                            $resolvedon = '';
                                        }

                                        if(is_null($resolutionArray)) {

                                       $wcUID = str_replace('e_tr_wco_','',$referenceId);
                                       $providerTypes = $resultArray['providerType'];
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
                                       $BindData = $referenceId."#".$primaryName."#".$matchStrength."#".$type."#";
                                        if($matchStrength == "EXACT") {
                                            $ExactCount++;
                                        } else {
                                            $FalseCount++;
                                        }
                                        if (0 == $numRows % 2) {
                                              $style = 'bgcolor="#fff"';
                                            } else {
                                              $style = 'bgcolor="#e4e4e3"';
                                            }
                                            $numRows++;
                                       ?>
                                        <tr <?=$style?>>
                                            <td width="5%" style="font-size: 9px;"><?php echo $statusName;?></td>
                                            <td width="5%" style="font-size: 9px;"><?php echo $wcUID;?></td>
                                            <td width="18%" style="font-size: 9px;"><?php echo $primaryName;?></td>
                                            <td width="10%" style="font-size: 9px;"><?php echo $type;?></td>
                                            <td width="10%" style="font-size: 9px;"><?php echo $category;?></td>
                                            <!--<td width="15%" style="font-size: 11px;"><?php echo $providerTypes;?></td>-->
                                            <td width="10%" style="font-size: 9px;"><?php echo $countryRegisterd;?></td>

                                            <td width="7%" style="font-size: 9px;" ><?php echo $resolvedon;?></td>
                                            <td width="7%" style="font-size: 9px;" ><?php echo $riskName;?></td>
                                            <td width="5%" style="font-size: 9px;" ><?php echo $resonName;?></td>
                                            <td width="5%" style="font-size: 9px;" ><?php echo $reson_desp;?></td>


                                            <td width="17%" style="font-size: 9px;"><?php echo $matchStrength;?></td>
                                        </tr>
                                             <?php
                                             $i++;
                                              }
                                             } 
                                        }

                                        ?>
                            </tbody>
                        </table>
                    </div>
                    <?php } ?>
                    <!--Similar records End-->
                </div>
                




<footer>
           <table width="650px">
               <tr>
                   <td width="50px" style="font-size:10px;font-color:#000;font-weight:bold;">Name :
                   </td>
                   <td width="250px" style="font-size:10px;font-color:#000;"><?php echo ucfirst($fullName);?></td>
                   <td width="350px" rowspan="4"><img src="{{ asset('frontend/outside/images/powredby.png') }}"  alt="" style="float:right;" width="150"/></td>
               </tr>
               <tr>
                  <td width="50px" style="font-size:10px;font-color:#000;font-weight:bold;">
                   Date Printed
                   </td>
                   <td width="250px" style="font-size:10px;font-color:#000;"><?php echo date('m-d-y, h:i');?></td>

               </tr>
               <tr>
                  <td width="50px" style="font-size:10px;font-color:#000;font-weight:bold;">
                   Printed By
                   </td>
                   <td width="250px" style="font-size:10px;font-color:#000;">Dr. Rebel Hanna</td>

               </tr>
               <tr>
                  <td width="50px" style="font-size:10px;font-color:#000;font-weight:bold;">
                   Group
                   </td>
                   <td width="250px" style="font-size:10px;font-color:#000;">Dexter Capital Financial <br><br>
                    Consultancy L.L.C - API (P)</td>


               </tr>
               <table>
       </footer>

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

@endsection

