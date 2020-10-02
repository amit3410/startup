<!DOCTYPE html>
<html lang="en">
   <head>
      <title>Manage Users</title>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
   </head>
   <?php
      $linkDate = 'DCF-OMC-COR-'.date('d-Y');
      $poweredBy = '<img src="'.asset('frontend/outside/images/powredby.png').'"  alt="Dexter Capital" width="50"/>';
      ?>
   <style>
      table.table-b tbody tr td{ padding:5px 7px; box-sizing:border-box;}
      table.table-b tbody tr th{ padding:5px 7px; box-sizing:border-box;}
      .border-none{border:0px; height:100px;}
      /** Define the margins of your page **/
      @page {
      margin:100px 75px;
      }
      header {
      position: fixed;
      top: -60px;
      left:0px;
      right: 0px;
      height: 50px;
      /** Extra personal styles 
      background-color: #03a9f4;**/
      color: black;
      line-height: 35px;
      }
      footer {
      position: fixed;
      bottom: 30px;
      left: 0px;
      right: 0px;
      height: 10px;
      /** Extra personal styles **/
      color: #000;
      text-align: center;
      line-height: 35px;
      }
      .page-number:before {
      content: "Page - " counter(page);
      font-size: 13px;
      color: #531919;
      }
      .page-number:after {
      content: '<?php echo $linkDate; ?>';
      position:absolute;
      left:0;
      font-size: 13px;
      color: #531919;
      }

      .page-number:after {
      content: '<?php echo $linkDate; ?>';
      position:absolute;
      left:0;
      font-size: 13px;
      color: #531919;
      }
   </style>
   <body>
      <header>
         <img src="{{ asset('frontend/outside/images/dexter_logo.png') }}"  alt="Dexter Capital" />
        
      </header>
      <footer>
         <div class="page-number"></div>
      </footer>
      <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff"  style=" font-family: 'Roboto', sans-serif;  font-size:14px; font-weight:300;">
         <tbody>
            <tr>
               <td align="left" >
                  <h3 style="margin: 0 0 50px 0; padding-bottom:0px; font-weight: 300;color:#743939;"><i>Regulated by Securities and Commodities Authority (SCA)</i></h3>
               </td>
            </tr>
            <tr>
               <td height="120px;">&nbsp;</td>
            </tr>
            <tr>
               <td>
                  <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;  background: #fff;">
                     <tbody>
                        <tr>
                           <td>
                              <h4 style="font-size: 36px;background-color: #F7CA5A;color: #743939;font-weight: 500;text-align: center;padding:15px 10px;margin:0px;"> Compliance Report </h4>
                              <p style="color: #743939;font-size: 24px;font-weight:600;text-decoration: underline;text-align: center;margin:0px;">Individual Natural Person</p>
                              <p style="color: #743939;font-size: 24px;font-weight:600;text-decoration: underline;text-align: center;padding: 0px 25px;margin:0px;">({{$userPersonalData->title.' '.ucfirst($userPersonalData->f_name)." ".$userPersonalData->m_name." ".$userPersonalData->l_name}})</p>
                           </td>
                        </tr>
                        <tr>
                           <td height="100px;">&nbsp;</td>
                        </tr>
                        <tr>
                           <td height="150px;" style="color:#743939;">Addressed To : Internal Use</td>
                        </tr>
                        <tr>
                           <td height="100px;">&nbsp;</td>
                        </tr>
                     </tbody>
                  </table>
                  <table width="100%" cellpadding="0" cellspacing="0" class="table table-striped table-sm brown-th" style="font-family: 'Roboto', sans-serif;font-size:13px; ">
                     <tbody>
                        <tr>
                           <td height="">
                              <!--<p style="margin:0px;padding: 0px 0px;color:#743939;font-weight: 300;">Document prepared by {{$reportByName}} - Compliance, Office management and Legal advisor</p>-->
                              <!--<p style="margin:0px;padding: 0px 0px;color:#743939;font-weight: 300;">Document approved by Dr. Rebel Hanna - Chief Executive Officer</p>-->
                              <!--<p style="margin:0px;padding: 0px 0px;color:#743939;font-weight: 300;">Doc Ref: DCF - OMC-COR-Compliance Report-{{$userPersonalData->title.' '.ucfirst($userPersonalData->f_name)." ".$userPersonalData->m_name." ".$userPersonalData->l_name}}</p>-->
                              <p style="margin:0px;padding: 0px 0px;color:#743939;font-weight: 300;">Document prepared and approved by Compliance and AML Officer</p>
                              <p style="margin:0px;padding: 0px 0px;color:#743939;font-weight: 300;">Doc Ref: DCF - OMC-COR-<?php echo date("d-Y")?></p>
                              <p style="margin:0px;padding: 0px 0px;color:#743939;font-weight: 300;">Dated: on <?php echo date("d M Y")?></p>
                              <p style="margin:70px 0px 0px;font-size:11px;padding: 0px;color:#743939;font-weight: 300;text-align: center;">Office 1005, Sobha Ivory 2 Tower, Al Asayel Street, Business Bay, Dubai, United Arab Emirates</p>
                              <p style="margin:0px;font-size:11px;padding: 0px;color:#743939;font-weight: 300;text-align: center;">Telephone: +971-4244-8548 | Fax: +971-4-440-5140 | Mobile: +971-58-843-1489</p>
                              <p style="margin:0px;font-size:11px;padding: 0px;color:#743939;font-weight: 300;text-align: center;">P.O Box: 121496, Dubai, UAE</p>
                              <p style="margin:0px;font-size:11px;padding: 0px;color:#743939;font-weight: 300;text-align: center;">VAT TRN 100226516100003</p>
                              <p style="border-top:1px solid #531919; "></p>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
            <tr>
               <td height="10px">&nbsp;
               </td>
            </tr>
         </tbody>
      </table>
      <table width="100%" cellpadding="0" cellspacing="0" class="table table-striped table-sm brown-th" style="font-family: 'Roboto', sans-serif;font-size:13px; ">
         <tbody>
            <!--Compliance Report For Corporate Entity-->
            <tr>
               <td align="left" valign="top" height="400px">&nbsp;</td>
            </tr>
            <tr>
               <td>
                  <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;  background: #fff;text-align: center;">
                     <tbody>
                        <tr>
                           <td>
                              <p style="color: #743939;font-size: 16px;font-weight:600;text-align: center;background:#F7CA5A;padding: 5px 25px;margin:0px;">(Page left blank intentionally)</p>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
            <tr>
               <td height="405px;">&nbsp;</td>
            </tr>
         </tbody>
      </table>
      <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff"  style="margin:0 auto; font-family: 'Roboto', sans-serif; box-shadow: -1px -1px 11px 1px #ccc; font-size:13px; font-weight:300;">
         <tbody>
            <tr>
               <td>
                  <table width="100%" border="0" cellpadding="0" cellspacing="0"style="border-collapse:collapse;max-width: 650px; margin:0 auto; background: #fff; padding: 20px 0px 0px;">
                     <tbody>
                        <tr>
                           <td align="left" valign="top" height="0px"></td>
                        </tr>
                        <tr>
                           <td>
                              <p style="color: #743939;font-size: 16px;font-weight:500;padding: 0px;margin: 3px;"><strong>Table of Content</strong></p>
                              <p style="color: #743939;font-size: 14px;font-weight:500;padding: 0px;margin: 3px;">1. Know Your Customer</p>
                              <p style="color: #743939;font-size: 14px;font-weight:500;padding: 0px;margin: 3px;">2. World Check Report</p>
                              <p style="color: #743939;font-size: 14px;font-weight:500;padding: 0px;margin: 3px;">3. Internet General Investigation Report</p>
                              <p style="color: #743939;font-size: 14px;font-weight:500;padding: 0px;margin: 3px;">4. References investigation</p>
                              <p style="color: #743939;font-size: 14px;font-weight:500;padding: 0px;margin: 3px;">5. Calculation of the final score according to the Onboarding risk assesment</p>
                              <p style="color: #743939;font-size: 14px;font-weight:500;padding: 0px;margin: 3px;">6. Analysis of the findings investigation</p>
                              <p style="color: #743939;font-size: 14px;font-weight:500;padding: 0px;margin: 3px;">7. Conclusion and recommendation</p>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
            <tr>
               <td height="300px">&nbsp;
               </td>
            </tr>
         </tbody>
      </table>
      <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff"  style=" font-family: 'Roboto', sans-serif;  font-size:13px; font-weight:300;color:#743939;box-sizing:border-box;">
         <tbody>
            <tr>
               <td height="400px"></td>
            </tr>
            <tr>
               <td>
                  <table width="100%" border="0" style="border-collapse:collapse; background: #fff;padding:10px 0px;">
                     <tbody>
                        <tr>
                           <th  align="left" style="font-size: 16px;border:0px;padding-left: 0px; ">1. Identification Details- KYC</th>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
            <tr>
               <td  style="">

                  <table width="90%" border="1" style="border-collapse:collapse;background:#fff; box-sizing:border-box;" class="table-b">

                     <tbody>
                        <tr>
                           <td colspan="2" align="center" style="font-size: 16px;"><b>Client Type</b></td>
                        </tr>
                        <tr>
                           <td colspan="2" align="left" valign="center" style="font-weight:700;font-size: 16px;" > Individual <input type="checkbox" checked style="vertical-align: bottom;margin: 0px;padding: 0px;"></td>
                        </tr>
                        <tr>
                           <td width="25%">Full Name</td>
                           <td width="75%">{{$userPersonalData->title.' '.$userPersonalData->f_name." ".$userPersonalData->m_name." ".$userPersonalData->l_name}}</td>
                        </tr>
                        <tr>
                           <td width="25%">Passport Number</td>
                           <td width="75%">{{$userPassportNumber}}</td>
                        </tr>
                        <tr>
                           <td>Passport Expiry Date</td>
                           <td >{{$userPassportExp}}</td>
                        </tr>
                        <tr>
                           <td>Identity Card Number</td>
                           <td>{{ isset($IdentityCardNumber) ? $IdentityCardNumber : ''}}</td>
                        </tr>
                        <tr>
                           <td>Identity Card Expiry Date</td>
                           <td>{{ isset($IdentityCardExp) ? date('d/m/Y',strtotime($IdentityCardExp)) : ''}}</td>
                        </tr>
                        <tr>
                           <td>Residence Number</td>
                           <td>{{ isset($complianceReport->residence_no) ? $complianceReport->residence_no : ''}}</td>
                        </tr>
                        <tr>
                           <td>Residency Expiry Date</td>
                           <td>{{ isset($complianceReport->residency_expiry_date) ? $complianceReport->residency_expiry_date : ''}}</td>
                        </tr>
                        @php
                        $resStatus=['1'=>'Resident','2'=>'Non-Resident'];
                        @endphp
                        <tr>
                           <td>Residence Type</td>
                           <td>{{($userPersonalData->residence_status>0)?$resStatus[$userPersonalData->residence_status]:''}}</td>
                        </tr>
                        <?php
                           $genger        = ['M' => 'Male', 'F' => 'Female'];
                           $date_of_birth = ($userPersonalData->date_of_birth != ''
                               && $userPersonalData->date_of_birth != null) ? Helpers::getDateByFormat($userPersonalData->date_of_birth,
                                   'Y-m-d', 'd/m/Y') : '';
                           ?>
                        <tr>
                           <td width="30%">Gender</td>
                           <td>{{isset($userPersonalData->gender) ? $genger[$userPersonalData->gender] : ''}}</td>
                        </tr>
                        <tr>
                           <td width="30%">Date of Birth</td>
                           <td>{{$date_of_birth}}</td>
                        </tr>
                        <tr>
                           <td width="30%">Country of Birth</td>
                           <td>{{ isset($arrCountry[$userPersonalData->birth_country_id]) ? $arrCountry[$userPersonalData->birth_country_id] : ''}}</td>
                        </tr>
                        <tr>
                           <th colspan="2" align="left">Bank Details</th>
                        </tr>
                        <tr>
                           <td>Bank Name</td>
                           <td>{{ isset($complianceReport->bank_name) ? $complianceReport->bank_name : ''}}</td>
                        </tr>
                        <tr>
                           <td>IBAN</td>
                           <td>{{ isset($complianceReport->iban) ? $complianceReport->iban : ''}}</td>
                        </tr>
                        <tr>
                           <td>RM Name</td>
                           <td>{{ isset($complianceReport->rm_name) ? $complianceReport->rm_name : ''}}</td>
                        </tr>
                        <tr>
                           <td>RM Mobile Number</td>
                           <td>{{ isset($complianceReport->rm_mobile) ? $complianceReport->rm_mobile : ''}}</td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
            <!-- General Investigataion  Report -->
            <tr>
               <td>
                  <table width="100%" border="0" style="border-collapse:collapse;  background: #fff;padding: 20px 0px;">
                     <thead>
                        <tr>
                           <th align="left" style="font-size: 16px;">2. World Check Report</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                           <?php if($primaryId > 0) {?>
                           <td width="650px">
                            <?php
                              $totalMatch   = "";
                                $statusName    = "";
                                $autoresolved  = 0;
                                $falsecount    = 0;
                                $possbilecount = 0;
                                $positivecount = 0;
                                $falsecounting = 0;
                                $unspecifiedcount = 0;
                              $kycData = Helpers::getkycdataById($primaryId);
                              if($kycData)
                              {
                              $caseid    =   $kycData->caseid;
                              $falsecount = $kycData->falsecount;
                              $autoresolved = $kycData->autoresolved;
                              $totalMatch = ($autoresolved+$falsecount);
                              echo "<p style='font-size:14px;font-weight:300;margin:0px 0px 10px;padding:0px;'>2.1.  ".ucfirst($kycData->org_name);
                                 echo "Total Matches-"."( ".$totalMatch." )</p>";
                              }
                              
                               

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
                                 
                                ?>
                                <table width="100%" cellspacing="0">
                                     <tr bgcolor="#fff">
                                        <td width="25%" style="font-size:14px;">Resolved Matches:</td>
                                        <td width="15%" class="text-center"><div id="resolveMatch">{{$resolvedMatched}}</div></td>
                                        <td width="60%" style="font-size:14px; text-align: left;"> <div style="margin-right:25px;display: inline;">Positive: {{$positivecount}}</div>
                                            <div style="margin-right:25px;display: inline;">Possible: {{$possbilecount}}</div>
                                            <div style="margin-right:25px;display: inline;">False: {{$autoresolvedfalse}}</div>
                                            <div style="margin-right:25px;display: inline;">Unspecified: {{$unspecifiedcount}}</div>
                                        </td>
                                        
                                    </tr>
                                    <tr bgcolor="#fff">
                                        <td width="30%" style="font-size:14px;">Unresolved Matches:</td>
                                        <td width="15%" class="text-center"><div id="totalMatch">{{$falsecount}}</div></td>
                                        <td width="55%" style="font-size:14px;"></td>
                                        
                                    </tr>
                                </table>



                           </td>
                                    <?php }?>
                        </tr>
                        <tr>
                           <td align="left" >Passport Verified : {{isset($complianceReport->passport_verify) ? $complianceReport->passport_verify : ''}}</th>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
            <!-- General Investigataion  Report  End-->
            <!-- General Investigataion  Report -->
            <tr>
               <td>
                  <table width="100%" border="0" style="border-collapse:collapse;background: #fff;padding: 20px 0px;">
                     <thead>
                        <tr>
                           <th align="left" colspan="2" style="font-size: 16px;">3. Internet General Investigation Report</th>
                        </tr>
                     </thead>
                     <tr>
                        <td width="100%" colspan="2">&nbsp;</td>
                     </tr>
                     <tr >
                        <td width="20%"><b>Social Media Links URL:</b></td>
                        <td width="80%" align="left" style="font-weight: normal;">
                           @if($socialmediaData && $socialmediaData->count())
                           @foreach($socialmediaData as $objSocial)
                           @php $socialmediaName = Helpers::getSocialmediaNameById($objSocial->social_media);@endphp
                           {{$socialmediaName.": ". $objSocial->social_media_link}}
                           <?php echo "<br>";?>
                           @endforeach
                           @endif
                        </td>
                     </tr>
                     <tr >
                        <td width="20%" >Remarks:</td>
                        <td width="80%" style="font-weight: normal;" align="left">
                           {{isset($complianceReport->general_investigation) ? $complianceReport->general_investigation : ''}}
                        </td>
                     </tr>
                     <tr>
                        <th width="100%" colspan="2">&nbsp;</th>
                     </tr>
                  </table>
               </td>
            </tr>
            <!-- General Investigataion  Report  End-->
            <!-- General Investigataion  Report -->
            <tr>
               <td>
                  <table width="100%" border="0" style="border-collapse:collapse;max-width:100%;  background: #fff;padding: 20px 0px;">
                     <thead>
                        <tr>
                           <th align="left"  style="font-size: 16px;">4. References investigation</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                           <td>{{isset($complianceReport->references_investigation) ? $complianceReport->references_investigation : ''}}</td>
                        </tr>
                        <tr>
                           <th>&nbsp;</th>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
            <!-- General Investigataion  Report  End-->
            <tr>
               <td style="color:#743939;font-size:16px;" height="40"> <b>5. Calculation of the final score according to the Onboarding risk assessment</b></td>
            </tr>
            <tr>
               <td>

                  <table border="1" width="90%" style="border-collapse:collapse;  background: #fff;" class="table-b">

                     <tbody>
                        <tr>
                           <td width="400px"><b>Parameter</b></td>
                           <td width="210px"><b>Score</b></td>
                        </tr>
                        <?php
                           //echo "<pre>";
                           // print_r($assesmentDetail); exit;
                           
                           $countryValue     = "";
                           $machesWorldcheck = "";
                           $passport         = "";
                           $pep              = "";
                           $socialMedia      = "";
                           
                           if (isset($assesmentDetail) && count($assesmentDetail)
                               > 0) {
                               $countryValue     = "";
                               $machesWorldcheck = "";
                               $passport         = "";
                               $pep              = "";
                               $socialMedia      = "";
                           
                               if (isset($assesmentDetail[0]->assesment_id) == 1) {
                                   $countryValue = $assesmentDetail[0]->rank;
                               }
                           
                               if (isset($assesmentDetail[1]->assesment_id) == 2) {
                                   $machesWorldcheck = $assesmentDetail[1]->rank;
                               }
                           
                               if (isset($assesmentDetail[2]->assesment_id) == 3) {
                                   $passport = $assesmentDetail[2]->rank;
                               }
                           
                               if (isset($assesmentDetail[3]->assesment_id) == 4) {
                                   $pep = $assesmentDetail[3]->rank;
                               }
                           
                               if (isset($assesmentDetail[4]->assesment_id) == 5) {
                                   $socialMedia = $assesmentDetail[4]->rank;
                               }
                           }
                           ?>
                        <tr>
                           <td width="400px">National Identity</td>
                           <td width="210px">{{@$countryValue}}</td>
                        </tr>
                        <tr>
                           <td width="400px">Name Matches – World Check</td>
                           <td width="210px">{{ @$machesWorldcheck }}</td>
                        </tr>
                        <tr>
                           <td width="400px">Passport Verification – World Check</td>
                           <td width="210px">{{ @$passport }}</td>
                        </tr>
                        <tr>
                           <td width="400px">Exposure</td>
                           <td width="210px">{{ @$pep }}</td>
                        </tr>
                        <tr>
                           <td width="400px">General Verification on Social Media</td>
                           <td width="210px">{{ @$socialMedia }}</td>
                        </tr>
                        <tr>
                           <td width="400px"><strong>Final Score</strong></td>
                           <td width="210px">{{ @$assesmentData[0]->org_avg_rank }}</td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
            <tr>
               <td>

                  <table width="90%" border="0" style="border-collapse:collapse; background:#fff;padding:20px 0px 0px;">

                     <tbody>
                        <tr>
                           <td width="45%">
                              <table width="100%"   border="0" valign="top" cellspacing="0" cellpadding="0" style="border-color:#ffffff;" class="table-b">
                                 <tr>
                                    <td width="70%" valign="top" style="border:1px solid #531919 ">Very Low risk</td>
                                    <td width="30%" style="border:1px solid #531919 ">1</td>
                                 </tr>
                                 <tr>
                                    <td  style="border:1px solid #531919 ">Low risk</td>
                                    <td  style="border:1px solid #531919 ">2</td>
                                 </tr>
                                 <tr>
                                    <td  style="border:1px solid #531919 ">Medium risk</td>
                                    <td  style="border:1px solid #531919 ">3</td>
                                 </tr>
                                 <tr>
                                    <td  style="border:1px solid #531919 ">High risk</td>
                                    <td  style="border:1px solid #531919 ">4</td>
                                 </tr>
                                 <tr>
                                    <td  style="border:1px solid #531919 ">Very High risk</td>
                                    <td  style="border:1px solid #531919 ">5</td>
                                 </tr>
                                 <tr>
                                    <td style="border-color:#ffffff;">&nbsp;</td>
                                    <td style="border-color:#ffffff;">&nbsp;</td>
                                 </tr>
                              </table>
                           </td>
                           <td width="10%"></td>
                           <td  width="45%">
                              <table width="100%" border="1" cellspacing="0" cellpadding="0" class="table-b">
                                 <tr>
                                    <td><strong>Score</strong></td>
                                    <td><strong>Decision</strong></td>
                                 </tr>
                                 <tr>
                                    <td>1</td>
                                    <td>On Board</td>
                                 </tr>
                                 <tr>
                                    <td>2</td>
                                    <td>On Board</td>
                                 </tr>
                                 <tr>
                                    <td>3</td>
                                    <td>Case By Case</td>
                                 </tr>
                                 <tr>
                                    <td>4</td>
                                    <td>Declined</td>
                                 </tr>
                                 <tr>
                                    <td>5</td>
                                    <td>Declined</td>
                                 </tr>
                              </table>
                           </td>
                        </tr>
                        <tr>
                           <td  colspan="2"style="padding:0px;"> {{$complianceReport['comment_compliance']}}</td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
            <!-- Analysis of the findings start-->
            <tr>
               <td>
                  <table width="100%" border="0" style="border-collapse:collapse; background: #fff;padding: 20px 0px;">
                     <thead>
                        <tr>
                           <th  align="left" style="padding:0px;font-size: 16px;">6. Analysis of the findings</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                           <td style="padding:0px;"> {{$complianceReport['analysis_findings']}}</td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
            <!-- Analysis of the findings End-->
            <!-- Conclusion and recommendation start-->
            <tr>
               <td>
                  <table width="100%" border="0" style="border-collapse:collapse; background: #fff;padding: 20px 0px;">
                     <thead>
                        <tr>
                           <th  align="left" style="padding:0px;font-size: 16px;">7. Conclusion and Recommendation</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                           <td style="padding:0px;"> {{$complianceReport['conclusion_recommendation']}}</td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
            <!-- Conclusion and recommendation End-->
            <tr>
               <td>

                  <table width="90%" cellpadding="0" border="1" style="border-collapse:collapse;  background: #fff;" class="table-b">

                     <tbody>
                        <tr>
                           <td  width="50%" align="center">Compliance Final Recommendation</td>
                           <td  width="50%" align="center">CEO Final Decision</td>
                        </tr>
                        <tr>
                           <td width="50%" height="100px" align="center">
                              {{Form::textarea('analysis_findings','',['class'=>'form-control border-none','id'=>'specify_position','rows'=>'5'])}}
                           </td>
                           <td  width="50%" align="center">{{Form::textarea('analysis_findings','',['class'=>'form-control border-none','id'=>'specify_position','rows'=>'5'])}}</td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
         </tbody>
      </table>
   </body>
</html>