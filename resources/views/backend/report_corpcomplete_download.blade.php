<!DOCTYPE html>
<html lang="en">

<head>
	<title>Manage Users</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
</head>
<?php
$linkDate = 'DCF-OMC-COR-'.date('d-Y');
?>
<style>

table.table-b tbody tr  td{ padding:4px 7px;}
table.table-b tr  th{ padding:5px 7px;}
.border-none{border:0px; height:100px;}

@page {
          margin: 100px 75px;
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
                text-align: center;
                line-height: 35px;
            }

            footer {
                position: fixed;
                bottom: 30px;
                left: 0px;
                right: 0px;
                height: 5px;

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
            .page-break {
    page-break-after: always;
}


</style>

<body>

    <?php
    $averageScore = 0;
    $TotalScore = 0;
    ?>

        <header>
            <img   src="{{ asset('frontend/outside/images/dexter_logo.png') }}"  alt="Dexter Capital" align="left" />
            
        </header>


        <footer>
             <div class="page-number"></div>
        </footer>

     <table width="100%" class="table table-striped table-sm brown-th" style="font-family: 'Roboto', sans-serif;font-size:13px; ">
        <tbody>
            <!--Compliance Report For Corporate Entity-->
            
               <tr>
                   <td>
              <table width="100%" border="0" style="border-collapse:collapse;  background: #fff;text-align: center;">
                  <tbody>
				<tr><td align="left" ><h3 style="margin:0; padding-bottom:0px; font-weight: 300;color:#743939;"><i>Regulated by Securities and Commodities Authority (SCA)</i></h3></td></tr>

				    <tr><td align="left" valign="top" height="150px">&nbsp;</td></tr>
				  
                     <tr>
                         <td>
                       <h4 style="font-size: 36px;background-color: #F7CA5A;color: #743939;font-weight: 500;text-align: center;padding:10px;margin:0px 0px 30px 0px;"> Compliance Report</h4>
                       <p style="color: #743939;font-size: 24px;font-weight:600;text-decoration: underline;text-align: center;padding: 0px;margin:0px;">{{isset($clientSubmitionData['corporationName'])?ucfirst($clientSubmitionData['corporationName']):''}}</p>
                       </td>
                      </tr>  
                       </tbody>
                      </table> 
                 </td>
               </tr>
                <tr>
                   <td height="100px;">&nbsp;</td>
               </tr>
               <tr>
                   <td height="150px;" style="color: #743939;">Addressed to: Internal Use</td>
               </tr>
               <tr>
                   <td height="120px;">&nbsp;</td>
               </tr>
        </tbody>
     </table>

     <table width="100%" class="table table-striped table-sm brown-th" style="font-family: 'Roboto', sans-serif;font-size:13px; ">
        <tbody>

               <tr>
                   <td height="">

                       <!--<p style="margin:0px;padding: 0px 0px;color:#743939;font-weight: 300;">Document prepared by {{$reportByName}} - Comliance, Office management and Legal advisor</p>-->
                       <!--<p style="margin:0px;padding: 0px 0px;color:#743939;font-weight: 300;">Document approved by Dr. Rebel Hanna - Chief Executive Officer</p>-->
                      <!-- <p style="margin:0px;padding: 0px 0px;color:#743939;font-weight: 300;">Doc Ref: DCF-OMC-COR-Compliance Report-Corporate-{{$clientSubmitionData['corporationName']}}</p>-->
                       <p style="margin:0px;padding: 0px 0px;color:#743939;font-weight: 300;">Document prepared and approved by Compliance and AML Officer</p>
                       <p style="margin:0px;padding: 0px 0px;color:#743939;font-weight: 300;">Doc Ref: DCF-OMC-COR-<?php echo date("d-Y")?></p>
                       <p style="margin:0px 0 80px 0;padding: 0px 0px;color:#743939;font-weight: 300;">Dated: on <?php echo date("d M Y")?></p>



                       <p style="margin:20px 0px 0px;font-size:11px;padding: 0px;color:#743939;font-weight: 300;text-align: center;">Office 1005, Sobha Ivory 2 Tower, Al Asayel Street, Business Bay, Dubai, United Arab Emirates</p>
                       <p style="margin:0px;font-size:11px;padding: 0px;color:#743939;font-weight: 300;text-align: center;">Telephone: +971-4244-8548 | Fax: +971-4-440-5140 | Mobile: +971-58-843-1489</p>
                       <p style="margin:0px;font-size:11px;padding: 0px;color:#743939;font-weight: 300;text-align: center;">P.O Box: 121496, Dubai, UAE</p>
                       <p style="margin:0px;font-size:11px;padding: 0px;color:#743939;font-weight: 300;text-align: center;">VAT TRN 100226516100003</p>
                       <p style="border-bottom:1px solid #531919;margin:20px 0px 0px;padding:10px 0px 0px;"></p>

                   </td>
                </tr>
        </tbody>
        </table>

		 <table width="100%" class="table table-striped table-sm brown-th" style="font-family: 'Roboto', sans-serif;font-size:13px; ">
        <tbody>
            <!--Compliance Report For Corporate Entity-->
              <tr><td align="left" valign="top" height="400px">&nbsp;</td></tr>
               <tr>
                   <td>
              <table width="100%" border="0" style="border-collapse:collapse;  background: #fff;">
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
		
		
		
		
		
		
		
		
		
        <table width="100%" class="table table-striped table-sm brown-th" style="font-family: 'Roboto', sans-serif;font-size:13px; ">
                <tbody>
                <tr>
                 <td>
                <table  border="0" style="border-collapse:collapse; background: #fff;">
                  <tbody>
                    <tr><td align="left" valign="top" height="50px"><</td></tr>
                     <tr>
                         <td>

                             <p style="color: #743939;font-size: 16px;font-weight:500;padding: 0px;margin: 3px;"><strong>Table of Content</strong></p>
                        <p style="color: #743939;font-size: 14px;font-weight:500;padding: 0px;margin: 3px;">1. Know Your Customer Details</p>
                        <p style="color: #743939;font-size: 14px;font-weight:500;padding: 0px;margin: 3px;">2. Shareholding Chart Structure</p>
                        <p style="color: #743939;font-size: 14px;font-weight:500;padding: 0px;margin: 3px;">3. World Check report</p>
                        <p style="color: #743939;font-size: 14px;font-weight:500;padding: 0px;margin: 3px;">4. Internet General Investigation Report</p>
                        <p style="color: #743939;font-size: 14px;font-weight:500;padding: 0px;margin: 3px;">5. References investigation</p>
                        <p style="color: #743939;font-size: 14px;font-weight:500;padding: 0px;margin: 3px;">6. Calculation of the final score according to the Onboarding risk assessment</p>
                        <p style="color: #743939;font-size: 14px;font-weight:500;padding: 0px;margin: 3px;">7. Analysis of the findings</p>
                        <p style="color: #743939;font-size: 14px;font-weight:500;padding: 0px;margin: 3px;">8. Conclusion and recommendation</p>
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

    <table width="100%" class="table table-striped table-sm brown-th" style="font-family: 'Roboto', sans-serif;font-size:13px; color:#743939;">
                <tbody>
              
         

                <tr>
                    <td align="center">
                        <table width="100%" border="1" style="border-collapse:collapse; background: #fff;" class="table-b">
                            <thead>
							     <tr>
									<td colspan="2" height="30" style="color: #743939;font-size: 16px;font-weight:700; border:1px solid #fff;">
									  1. Know Your Customer Details.
									</td>
								  </tr>
                                <tr>
                                    <th colspan="2" align="center" style="font-size: 16px;">Client Type</th>
                                </tr>
                                <tr>
                                    <td colspan="2" align="left">Company <input type="checkbox" checked style="vertical-align: bottom;margin: 0px;padding: 0px;"></td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td width="30%">Company Name</td>
                                    <td width="70%">{{isset($clientSubmitionData['corporationName'])?$clientSubmitionData['corporationName']:''}}</td>
                                </tr>
                                <tr>
                                    <td width="30%">Company Registration Number</td>
                                    <td>{{isset($clientSubmitionData['registration_no'])  ? $clientSubmitionData['registration_no'] : ''}}</td>
                                </tr>
                                <tr>
                                    <td width="30%">Registration Date</td>
                                    <td>{{$clientSubmitionData['registration_date']}}</td>
                                </tr>
                               <tr>
                                    <td width="30%">Tax Registration Number</td>
                                    <td>{{isset($complianceReport['tax_registration_no']) ? $complianceReport['tax_registration_no'] : ''}}</td>
                                </tr>
                                 <tr>
                                    <td><strong>License T.C.R. Regulater Number (If applicable)</strong></td>
                                    <td>{{isset($complianceReport['license']) ? $complianceReport['license'] : ''}}</td>
                                </tr>
                                <tr>
                                    <td width="30%">Tenancy Contract Expiry Date</td>
                                    <td>{{isset($complianceReport['tenancy_exp_date']) ? $complianceReport['tenancy_exp_date'] : ''}}</td>
                                </tr>
                                <tr>
                                    <td>Registration of Tenancy Contract</td>
                                    <td>{{isset($complianceReport['registration_tenancy']) ? $complianceReport['registration_tenancy'] : ''}}</td>
                                </tr>
                                <tr>
                                    <td>Full Address</td>
                                    <td>
                                       <?php echo $clientSubmitionData['corporateAddress'];?>
                                    </td>
                                </tr>
                               <!--  <tr>
                                    <td>Office Address</td>
                                    <td><?php echo $clientSubmitionData['resAddress'];?></td>
                                </tr> -->
                                <tr>
                                    <td>Telephone Number</td>
                                    <td>{{$clientSubmitionData['telephoneNumber']}} </td>
                                </tr>
                                <tr>
                                    <td>Mobile Number</td>
                                    <td>{{$clientSubmitionData['mobileNo']}} </td>
                                </tr>

                                
                                <tr>
                                    <td>Email</td>
                                    <td>{{$clientSubmitionData['Email']}}</td>
                                </tr>
                                <tr>
                                    <td>Website</td>
                                    <td>{{isset($complianceReport['website']) ? $complianceReport['website'] : ''}}</td>
                                </tr>
                                <tr>
                                    <th colspan="2" align="left">Bank Details</th>
                                </tr>
                                <tr>
                                    <td>Bank Name</td>
                                    <td>{{isset($complianceReport['bank_name']) ? $complianceReport['bank_name'] : ''}}</td>
                                </tr>
                                <tr>
                                    <td>Account Number</td>
                                    <td>{{isset($complianceReport['accouunt_number']) ? $complianceReport['accouunt_number'] : ''}}</td>
                                </tr>
                                <tr>
                                    <td>Correspondent Account Number</td>
                                    <td>{{isset($complianceReport['corr_accouunt_number']) ? $complianceReport['corr_accouunt_number'] : ''}}</td>
                                </tr>
                                
                            </tbody>
                            </table>
                        </td>
                        </tr>
                        </tbody>
         </table>
    
       <table width="100%" class="table table-striped table-sm brown-th" style="font-family: 'Roboto', sans-serif;font-size:13px; color:#743939; margin-top:15px;">
                <tbody>

                <tr>
                    <td>
                        <table width="640px" style="border-collapse:collapse; background: #fff;border:1px solid #743939;" class="table-b">
                                <tr>
                                    <td style="border-bottom:0px;" ><b>Shareholder Structure - Ultimate Beneficial Owner</b></td>
                                </tr>
                           </table>
                                @if($beficiyerArray && $beficiyerArray->count())
                                <?php $i = 1; ?>
                                @foreach($beficiyerArray as $obj)

                                <?php
                                //$documentsData = Helpers::getDocumentTypeInfowithpassport($obj->owner_kyc_id, $obj->passport_no);
                                $documentsData = Helpers::getDocumentTypeInfo($obj->owner_kyc_id);
                                if ($documentsData) {
                                        $passportArray = Helpers::getPassportNumber($documentsData,'passport');
                                        $passportArrays = explode("#",$passportArray);
                                        $userPassportNumber = @$passportArrays[0];
                                        $userPassportExp    = @$passportArrays[1];
                                    }
                                
                                $residentialData = Helpers::getCommercialInfo($obj->owner_kyc_id);
                                //echo "<pre>";
                               //print_r($residentialData);
                                $fullAddress = '';
                                if($residentialData['floor_no']){
                                        $fullAddress = $residentialData['floor_no'].",";
                                }
                                if($residentialData['building_no']){
                                        $fullAddress.=$residentialData['building_no'].",";
                                }
                                if($residentialData['street_addr']){
                                        $fullAddress.=$residentialData['street_addr'].",";
                                }
                                if($residentialData['region']){
                                        $fullAddress.=$residentialData['region'].",";
                                }
                                if($residentialData['city_id']){
                                        $fullAddress.=$residentialData['city_id'].",";
                                }

                                if($residentialData['country_id']){
                                    $userCountryName = $arrCountry[$residentialData->country_id];
                                    $fullAddress.=$userCountryName.",";
                                }
                                if($residentialData['postal_code ']){
                                        $fullAddress.=$residentialData['postal_code '];
                                }

                                $fullAddress = trim($fullAddress,",");

                                
                                ?>
                                <table width="640px" border="1" style="border-collapse:collapse; background: #fff; border:1px solid #743939;border-bottom: #743939;border-top:0px;" class="table-b">
                                <?php if($i == 1) { 
                                    $toptd = "style='border-top:0px;border-bottom:0px;'";
                                    
                                } else {
                                    $toptd = '';
                                }?>
                                    <tr>
                                    <th width="100%" colspan="2" align="left" <?php echo $toptd;?>>Individuals <input type="checkbox" checked="" style="vertical-align: bottom;margin: 0px;padding: 0px;"></th>
                                </tr>
                                <tr>
                                    <th width="100%" colspan="2" align="left">Shareholder {{$i}}</th>
                                </tr>
                                <tr>
                                    <td width="30%">Full Name</td>
                                    <td width="70%">{{$obj->company_name}}</td>
                                </tr>
                                <tr>
                                    <td>Passport No</td>
                                    <td>{{($userPassportNumber) ? $userPassportNumber : ''}}</td>
                                </tr>
                                <tr>
                                    <td>Passport Expiry Date.</td>
                                    <td>{{ isset($userPassportExp) ? $userPassportExp : ''}}</td>
                                </tr>
                                <tr>
                                    <td>Full Address</td>
                                    <td>{{ $fullAddress }}</td>
                                </tr>
                                <tr>
                                    <td>Mobile No.</td>
                                    <td>{{ isset($residentialData['addr_country_code']) ? "(".$residentialData['addr_country_code'].")". $residentialData['addr_mobile_no'] : ''}}</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>{{ isset($residentialData['addr_email']) ? $residentialData['addr_email'] : ''}}</td>
                                </tr>
                                </table>
                                <?php $i++; ?>
                                
                                @endforeach
                                @endif


                            
                       
                    </td>
                </tr>

                <!-- Share Holding Structure start -->

                  <tr>
                    <td>
                        <table width="700px" border="0" style="border-collapse:collapse; background: #fff;padding: 20px 0px;">
                         <thead>
                                <tr>
                                    <th colspan="2" align="left" style="font-size: 16px;">2. Shareholding Chart Structure</th>
                                </tr>

                            </thead>
                            <tbody>
                                    
                                        <?php
                                            $shareresult  =   Helpers::getCorpShareStructure($user_id);
                                            //$newresult = $beficiyerData;
                                            $category = array(
                                                'shares' => array(),
                                                'parent_share' => array()
                                            );
                                            if($shareresult && $shareresult->count()){
                                                foreach($shareresult as $row){

                                                    //creates entry into categories array with current category id ie. $categories['categories'][1]
                                                    $category['shares'][$row['corp_shareholding_id']] = $row;
                                                    //creates entry into parent_cats array. parent_cats array contains a list of all categories with children
                                                    $category['parent_share'][$row['share_parent_id']][] = $row['corp_shareholding_id'];
                                                }
                                            }
                                        ?>

                                    <tr>
                                        <td width="700px" colspan="2" style="padding:5px 0px;">{{Helpers::buildCategory(0, $category)}}</td>
                                    </tr>

                            </tbody>
                            </table>
                    </td>
                </tr>
                <!-- Share Holding Structure End -->


                <!-- General Investigataion  Report -->
                



                <tr>
                    <td>
                        <table width="700px" border="0" style="border-collapse:collapse;background: #fff;padding:20px 0px;">

                            <tbody>
				       <tr>
                                    <td align="left" style="font-size: 16px;"><b>3. World Check Report</b></td>
                                </tr>
                                <tr>
                                   <td width="100%">
                                        <?php
                                        $totalMatch = "";
                                        $statusName = '';
                                        $autoresolved = 0;
                                        $falsecount = 0;
                                        $kycData=false;
                                       // echo "==>".$primaryId; exit;
                                        if(isset($primaryId) && $primaryId > 0){
                                            $kycData = Helpers::getkycdataById($primaryId);
                                        }
                                        if($kycData)
                                        {
                                            $j = 1;
                                            $caseid     =   $kycData->caseid;
                                            $falsecount =   $kycData->falsecount;
                                            $autoresolved = $kycData->autoresolved;
                                            $totalMatch = ($autoresolved+$falsecount);
                                            echo "<h5>3.".$j." ".ucfirst($kycData->org_name);
                                            echo  str_repeat('&nbsp;', 2);
                                            echo "Total Matches-"."( ".$totalMatch." )</h5>";
                                            echo "<br>";


                                        $possbilecount = 0;
                                        $positivecount = 0;
                                        $falsecounting = 0;
                                        $unspecifiedcount = 0;
                                        //echo "==>".$primaryId;
                                       //  $primaryId = 255;
                                            $i           = 0;
                                            $j ++;
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
                                            $resolvedMatched =0;
                                            $autoresolvedfalse = 0;
                                            if($caseid!='') {
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
                                            }

                                            $ExactCount  = 0;
                                            $FalseCount  = 0;
                                        ?>
                                                    <table width="100%" cellspacing="0" border="0">
                                                        <tr>
                                                           <td width="20%" style="font-size:14px;">Resolved Matches:</td>
                                                           <td width="10%" class="text-center"><div id="resolveMatch">{{$resolvedMatched}}</div></td>
                                                           <td width="70%" style="font-size:14px; text-align: left;"> <div style="margin-right:20px;display: inline;">Positive: {{$positivecount}}</div>
                                                               <div style="margin-right:20px;display: inline;">Possible: {{$possbilecount}}</div>
                                                               <div style="margin-right:20px;display: inline;">False: {{ ($autoresolved!='') ? $autoresolved : '0'}}</div>
                                                               <div style="margin-right:20px;display: inline;">Unspecified: {{$unspecifiedcount}}</div>
                                                           </td>

                                                       </tr>
                                                       <tr >
                                                           <td width="25%" style="font-size:14px;">Unresolved Matches:</td>
                                                           <td width="10%" class="text-center"><div id="totalMatch">{{$falsecount}}</div></td>
                                                           <td width="65%" style="font-size:14px;" >&nbsp;</td>

                                                       </tr>
                                                    </table>
                                                    <?php
                                                    if($beficiyerData && $beficiyerData->count()) {
                                                    //$j = 1;
                                                    foreach($beficiyerData as $obj) {
                                                      // echo "==>".$obj['owner_kyc_id'];
                                                       // $kycData = Helpers::getKycDataByKycId($obj['owner_kyc_id'],'screeningRequest');
                                                         $kycDataprofile = Helpers::getDataByKycId($obj['owner_kyc_id'],'screeningRequest');
                                                         $kycData = Helpers::getKycDatabyparentId($kycDataprofile['wcapi_req_res_id']);

                                                        if($kycData)
                                                        {
                                                            $childcaseid =  @$kycData->caseid;
                                                            $childwcid =  @$kycData->wcapi_req_res_id;
                                                            $falsecount = $kycData->falsecount;
                                                            $autoresolved = $kycData->autoresolved;
                                                            $response  = $kycData->api_responce; //exit
                                                            $dataArray = json_decode($response, JSON_UNESCAPED_UNICODE);
                                                            $totalMatch = $falsecount+$autoresolved;
                                                            echo "<br>";
                                                            echo "<h5>3.".$j." ".ucfirst($kycData->f_name)." ".$kycData->m_name." ".$kycData->l_name;
                                                            echo " Total Matches-"."( ".$totalMatch." )</h5>";
                                                         $j++;

                                                    $possbilecount = 0;
                                                    $positivecount = 0;
                                                    $falsecounting = 0;
                                                    $unspecifiedcount = 0;
                                                    $statusName = '';
                                                    //echo "==>".$primaryId;
                                                   //  $primaryId = 255;
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
                                                        $matchresultArray  = [];
                                                        $resolutionDatas   = Helpers::getResolutionDatabycaseID($childcaseid);
                                                        $resolvedMatched   = ($autoresolved) ? $autoresolved : 0;
                                                        $autoresolvedfalse = ($autoresolved) ? $autoresolved : 0;

                                                       if(isset($dataArray['results']) && count($dataArray['results']) > 0) {
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

                                                        }

                                                        $ExactCount  = 0;
                                                        $FalseCount  = 0;
                                                    ?>
                                                    <table width="100%" cellspacing="0" border="0">
                                                        <tr>
                                                           <td width="20%" style="font-size:14px;">Resolved Matches:</td>
                                                           <td width="10%" class="text-center"><div id="resolveMatch">{{$resolvedMatched}}</div></td>
                                                           <td width="70%" style="font-size:14px; text-align: left;"> <div style="margin-right:20px;display: inline;">Positive: {{$positivecount}}</div>
                                                               <div style="margin-right:20px;display: inline;">Possible: {{$possbilecount}}</div>
                                                               <div style="margin-right:20px;display: inline;">False: {{$autoresolvedfalse}}</div>
                                                               <div style="margin-right:20px;display: inline;">Unspecified: {{$unspecifiedcount}}</div>
                                                           </td>
                                                           
                                                       </tr>
                                                       <tr >
                                                           <td width="25%" style="font-size:14px;">Unresolved Matches:</td>
                                                           <td width="10%" class="text-center"><div id="totalMatch">{{$falsecount}}</div></td>
                                                           <td width="65%" style="font-size:14px;" >&nbsp;</td>

                                                       </tr>
                                                    </table>
                                                    <?php } } } } ?>
                                                </td>
                                </tr>

                            </tbody>
                        </table>
                    </td>
                </tr>
                <!-- General Investigataion  Report  End-->
                <!-- General Investigataion  Report -->
                <tr>
                    <td>
                        <table width="100%" border="0" style="border-collapse:collapse; background: #fff;padding:20px 0px;">
                            <tbody>
                                 <tr>
                                    <th colspan="2" align="left" style="font-size: 16px;"><b>4. Internet General Investigation Report</b></th>
                                </tr>
                                <tr>
                                     <td width="100%" colspan="2">&nbsp;</td>
                                </tr>
                                <tr >
                                    <td width="20%"><b>Social Media Links URL:</b></td>
                                    <td width="80%" align="left">
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
                                    <td width="20%"><b>Remarks:</b></td>
                                    <td width="80%" align="left">
                                        {{isset($complianceReport['general_investigation']) ? $complianceReport['general_investigation'] : ''}}
                                    </td>
                                </tr>
                            
                            </tbody>
                        </table>
                    </td>
                </tr>

                <!-- General Investigataion  Report  End-->


                <!-- General Investigataion  Report -->
                <tr>
                    <td>
                        <table width="700px" border="0" style="border-collapse:collapse; background: #fff;padding:20px 0px;">

                           <tbody>
                                <tr>
                                    <td align="left" style="font-size: 16px;"><b>5. References Investigation</b></td>
                                </tr>
                                <tr >
                                    
                                    <td style="margin-left:20px;" width="100%"> {{isset($complianceReport['references_investigation']) ? $complianceReport['references_investigation'] : ''}}</td>
                                </tr>
                     
                            </tbody>
                        </table>
                    </td>
                </tr>



                <!-- General Investigataion  Report  End-->

                    <tr>
                    <td>
                        <table width="700px" border="0" style="border-collapse:collapse; background: #fff;padding:20px 0px;">

                            <tbody>
				<tr>
                                    <td align="left" style="font-size: 16px;" width="700px;" colspan="2" height="30"><b>6. Calculation of the final score according to the Onboarding risk assessment</b></td>
                                </tr>

                                <tr>

                                    <td style="margin-left:20px;" width="100%">

                                    <?php
                                            $countryValue     = "";
                                            $machesWorldcheck = "";
                                            $passport         = "";
                                            $pep              = "";
                                            $socialMedia      = "";

                                        if (isset($assesmentDetail) && count($assesmentDetail) > 0) {
                                            

                                            if (isset($assesmentDetail[0]->assesment_id) == 1) {
                                                $countryValue = $assesmentDetail[0]->rank;
                                            }

                                            if (isset($assesmentDetail[1]->assesment_id)  == 2) {
                                                $machesWorldcheck = $assesmentDetail[1]->rank;
                                            }



                                            if (isset($assesmentDetail[2]->assesment_id) == 4) {
                                                $pep = $assesmentDetail[2]->rank;
                                            }

                                            if (isset($assesmentDetail[3]->assesment_id) == 5) {
                                                $socialMedia = $assesmentDetail[3]->rank;
                                            }
                                        }


                                        ?>

                                        <table class="table table-striped table-sm table-b" width="640px" border="1" style="border-collapse:collapse; background: #fff;" >
                                                        <thead>
                                                            <tr>
                                                                <th width="70%"><center>Parameter</center></th>
                                                                <th width="30%" style="border-left: 1px solid #dee2e6;"><center>Score</center></th>
                                                            </tr>
                                                           </thead>
                                                            <tbody>

                                                                <tr>
                                                                <td width="70%"><strong>{{isset($clientSubmitionData['corporationName'])? ucfirst($clientSubmitionData['corporationName']):''}}</strong></td>
                                                                <td width="30%" style="border-left: 1px solid #dee2e6;">&nbsp;</td>
                                                            </tr>
                                                            <tr>
                                                                <td width="70%">Country of Registration</td>
                                                                <td width="30%" style="border-left: 1px solid #dee2e6;"><center>{{@$countryValue}}</center></td>
                                                            </tr>
                                                             <tr>
                                                                <td width="70%">Matches and Risk (World Check)</td>
                                                                <td width="30%" style="border-left: 1px solid #dee2e6;"><center>{{ @$machesWorldcheck }}</center></td>
                                                            </tr>

                                                             <tr>
                                                                <td width="70%">Type and Exposure</td>
                                                                <td width="30%" style="border-left: 1px solid #dee2e6;"><center>{{ @$pep }}</center></td>
                                                            </tr>
                                                             <tr>
                                                                <td width="70%">General Verification on Website and Social Media</td>
                                                                <td width="30%" style="border-left: 1px solid #dee2e6;"><center>{{ @$socialMedia }}</center></td>
                                                            </tr>
                                                            <tr>
                                                                <td width="70%"><strong>Score</strong></td>
                                                                <td width="30%" style="border-left: 1px solid #dee2e6;"><center><strong>{{ @$assesmentData[0]->org_avg_rank }}</strong></center></td>
                                                            </tr>
                                                            

                                                            </tbody>

                                                    </table>
                                    </td>
                                </tr>
                                <tr>
                                                <td colspan="2" bgcolor="#fff">
                                                    

                                                    @if($beficiyerData && $beficiyerData->count())
                                                    @php @$j = 1; @endphp
                                                    @foreach($beficiyerData as $obj)
                                                        @php
                                                        $IndividualAssesmentData = Helpers::getAssesmentData($obj->owner_kyc_id);
                                                        $getAssesmentRankData = Helpers::getAssesmentRankData($obj->owner_kyc_id, 1);
                                                        $beneficiary = Helpers::UserpersonalData($obj->owner_kyc_id);
                                                        if($IndividualAssesmentData)
                                                        { @endphp

                                                        <?php
                                                        if($j == 4) {
                                                            $addcss = 'page-break';
                                                             } else {
                                                                $addcss = '';
                                                             }

                                                        //echo "<pre>";
                                                        //print_r($getAssesmentRankData);

                                                            $countryValue     = "";
                                                            $machesWorldcheck = "";
                                                            $passport         = "";
                                                            $pep              = "";
                                                            $socialMedia      = "";

                                                        if (isset($IndividualAssesmentData) && count($IndividualAssesmentData)
                                                            > 0) {


                                                            if (isset($IndividualAssesmentData[0]->assesment_id) == 1) {
                                                                $countryValue = $IndividualAssesmentData[0]->rank;
                                                            }

                                                            if (isset($IndividualAssesmentData[1]->assesment_id) == 2) {
                                                                $machesWorldcheck = $IndividualAssesmentData[1]->rank;
                                                            }
                                                            if (isset($IndividualAssesmentData[2]->assesment_id)
                                                                == 3) {
                                                                $passport = $IndividualAssesmentData[2]->rank;
                                                               }

                                                            if (isset($IndividualAssesmentData[3]->assesment_id) == 4) {
                                                                $pep = $IndividualAssesmentData[3]->rank;
                                                            }

                                                            if (isset($IndividualAssesmentData[4]->assesment_id) == 5) {
                                                                $socialMedia = $IndividualAssesmentData[4]->rank;
                                                            }
                                                        }

                                                        if(isset($getAssesmentRankData[0]->org_avg_rank)) {
                                                         $TotalScore = ($TotalScore+$getAssesmentRankData[0]->org_avg_rank);
                                                        }


                                                        ?>
                                                        <table class="table table-striped table-sm table-b <?php echo $addcss;?>" width="640px" border="1" style="border-collapse:collapse; background: #fff; margin-top:15px;" >
                                                            <tr>
                                                                <td width="70%"><strong>{{isset($beneficiary->f_name)? ucfirst($beneficiary->f_name." ".$beneficiary->l_name):'Profile Incomplete'}}</strong></td>
                                                                <td width="30%" style="border-left: 1px solid #dee2e6;"></td>
                                                            </tr>
                                                            <tr>
                                                                <td width="70%">National Identity</td>
                                                                <td width="30%" style="border-left: 1px solid #dee2e6;"><center>{{ @$countryValue }}</center></td>
                                                            </tr>
                                                             <tr>
                                                                <td width="70%">Name Matches – World Check</td>
                                                                <td width="30%" style="border-left: 1px solid #dee2e6;"><center>{{@$machesWorldcheck}}</center></td>
                                                            </tr>
                                                            <tr>
                                                                <td width="70%">Passport Verification – World Check</td>
                                                                <td width="30%"><center>{{ @$passport }}</center></td>
                                                            </tr>

                                                             <tr>
                                                                <td width="70%">Exposure</td>
                                                                <td width="30%" style="border-left: 1px solid #dee2e6;"><center>{{@$pep}}</center></td>
                                                            </tr>
                                                             <tr>
                                                                <td width="70%">General Verification on Website and Social Media</td>
                                                                <td width="30%" style="border-left: 1px solid #dee2e6;"><center>{{@$socialMedia}}</center></td>
                                                            </tr>

                                                            <tr>
                                                                <td width="70%"><strong>Score</strong></td>
                                                                <td width="30%" style="border-left: 1px solid #dee2e6;"><center><strong>{{ isset($getAssesmentRankData[0]->org_avg_rank) ? $getAssesmentRankData[0]->org_avg_rank : '' }}</strong></center></td>
                                                            </tr>
                                                            </table>
                                                        @php }
                                                         @$j++;
                                                        @endphp
                                                    @endforeach
                                                    @endif

                                                    <?php
                                                   // echo "<pre>";
                                                    //print_r($assesmentData);
                                                    if($TotalScore) {
                                                        isset($assesmentData[0]->org_avg_rank) ? $asData = $assesmentData[0]->org_avg_rank : $asData = 0;
                                                      $TotalScore =   ($TotalScore+$asData);
                                                      $beficiyerCount = $beficiyerData->count();
                                                      $noofUsers = $beficiyerCount+1;
                                                      $averageScore = $TotalScore/$noofUsers;
                                                    }
                                                    ?>
                                                    <table class="table table-striped table-sm" width="640px" border="1" style="border-collapse:collapse; ">
                                                    <tr>
                                                       <td colspan="2">
                                                        <table class="table table-striped table-sm table-b" width="640px" border="0" style="border-collapse:collapse; ">
                                                            <tr>
                                                            <td width="70%"><strong>Final Score</strong></td>
                                                        <td width="30%" style="border-left: 1px solid #dee2e6;border-right: 1px solid #dee2e6;"><center><strong>{{ number_format($averageScore,2) }}</strong></center></td>
                                                        </tr>
                                                        </table>
                                                        </td>
                                                    </tr>
                                                    </table>

                                                </td>
                                            </tr>

                           

                            </tbody>
                        </table>
                    </td>
                </tr>



                <tr>
                    <td>
                        <table width="640px" border="0" style="border-collapse:collapse; background: #fff;padding: 20px 0px;">

                            <tbody>
			                    	

                                <tr>

                                  <td width="45%">


                                        <table width="100%" height="auto" border="0" valign="top" cellspacing="0" cellpadding="0" style="border-color:#ffffff;" class="table-b">
                                        <tbody>
                                            <tr>
                                                <td width="70%"  style="border:1px solid #531919 ">Very Low risk</td>
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
                                          </tbody>
                                        </table>	
										
										
                                    </td>
                                    <td width="10%">&nbsp;</td>
                                    <td width="45%">

                                        <table width="100%" border="1" cellspacing="0" cellpadding="0" class="table-b">
										<tbody>
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
					</tbody>
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

                <!-- General Investigataion  Report  End-->

                    <tr>
                    <td>
                        <table width="700px" border="0" style="border-collapse:collapse; background: #fff;padding: 20px 0px;">

                            <tbody>
				<tr>
                                    <td align="left" style="font-size: 16px;"><b>7. Analysis of the findings</b></td>
                                </tr>
                           
                                <tr >

                                    <td style="margin-left:20px;" width="100%"> {{isset($complianceReport['analysis_findings']) ? $complianceReport['analysis_findings'] : ''}}</td>
                                </tr>
                               
                            </tbody>
                        </table>
                    </td>
                </tr>
                <!-- Analysis of the findings start-->
                

                <!-- Analysis of the findings End-->

                <!-- Analysis of the findings start-->

                <tr>
				 <td height="20px"></td>
				</tr>

               <tr>
                    <td>
                        <table width="640px" border="0" style="border-collapse:collapse; background: #fff;padding:20px 0px;">

                            <tbody>
                                <tr>
                                    <th align="left" style="font-size: 16px;">8. Conclusion and Recommendation</th>
                                </tr>
                                <tr >

                                    <td style="margin-left:20px;" width="100%"> {{ isset($complianceReport['conclusion_recommendation']) ? $complianceReport['conclusion_recommendation'] : ''}}</td>
                                </tr>
                                 <tr>
                                     <td width="100%" >&nbsp;</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>

                

                <!-- Analysis of the findings End-->





                <tr>
                    <td>
                        <table width="640px" border="1" style="border-collapse:collapse; background: #fff;padding:10px 0px;" class="table-b">

                            <thead>
                                <tr>
                                    <th  width="50%" align="center">Compliance Final Recommendation</th>
                                    <th  width="50%" align="center">CEO Final Decision</th>
                                </tr>

                                <tr>
                                    <th  width="50%" align="center">

                                        {{Form::textarea('analysis_findings','',['class'=>'form-control border-none','id'=>'specify_position','rows'=>'20'])}}


                                    </th>
                                    <th  width="50%" align="center">{{Form::textarea('analysis_findings','',['class'=>'form-control border-none','id'=>'specify_position','rows'=>'20'])}}</th>
                                </tr>

                            </thead>
                            
                        </table>
                    </td>
                </tr>
            </tbody>

             </table>

      
    </body>
</html>