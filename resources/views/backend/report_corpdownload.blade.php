<!DOCTYPE html>
<html lang="en">

<head>
	<title>Manage Users</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700&display=swap" rel="stylesheet">-->
</head>
<style>
body{padding:0; margin:0;text-align: left;font-weight: 400;}
.table-s tr td{ padding:3px 5px;}
.table-responsive {
    display: block;
    width: 100%;
    overflow-x: auto;
  }
  h3{ margin: 5px 0px;padding: 0px; }
  b{color: #636363;}

</style>

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

<body>
<table width="700px" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff"  style="padding:0px; margin:0 auto; font-family: 'Roboto', sans-serif; box-shadow: -1px -1px 11px 1px #ccc; font-size:13px;font-weight:100;">


<tr>
  <td>
    <table class="table-s" width="720px" bgcolor="#C2C2C2" cellpadding="0" cellspacing="0" border="0" style="margin-top:10px; color: #636363;">
         <tr>
<td colspan="2" style="font-weight:300;color:#636363; font-size:38px; padding: 50px 25px 10px;">WORLD-CHECK ONE</td>
</tr>
<tr>
<td style="font-weight:300;color:#636363; font-size:15px;padding: 10px 25px 20px;">WORLD-CHECK MATCH DETAILS REPORT</td>
<td align="right" style="font-weight:300;color:#636363; font-size:15px;text-align: right;padding: 10px 25px 20px;">STRICTLY CONFIDENTIAL</td>
</tr>
    </table>
  </td>
</tr>

<tbody>
<tr>
<td>
<table class="table-s" width="720px" cellpadding="0" cellspacing="0" border="0" style="margin-top:20px;">
<tr>
<td colspan="4"><h3 style="color:#ec952d; font-size:18px; ">WORLD-CHECK PROFILE UID: {{$referenceId}}</h3></td>
</tr>
<tr>
<td width="20%" bgcolor="#e4e4e3" ><b>Name</b></td>
<td width="30%" bgcolor="#e4e4e3" style="color: #ec952d; border-right:5px solid #fff;" >{{$corp_name}}</td>
<td width="20%" bgcolor="#e4e4e3" ><b>Date Printed</b></td>
<td width="30%" bgcolor="#e4e4e3" style="color: #636363;"></td>
</tr>
<tr>
<td valign="top"><b>Printed By</b></td>
<td valign="top" style="color: #636363;border-right:5px solid #fff;border-top:0px;">Dr. Rebel Hanna</td>
<td  valign="top"><b>Group</b></td>
<td  valign="top" style="color: #636363;">Dexter Capital Financial Consultancy L.L.C - API (P)</td>
</tr>
<tr>
<td  bgcolor="#e4e4e3" ><b>Assigned To</b></td>
<td  bgcolor="#e4e4e3" >----</td>
<td colspan="2"></td>

</tr>
</table>
</td>
</tr>



<tr>
<td>
<table class="table-s" width="720px" cellpadding="0" cellspacing="0" border="0" style="margin-top:20px; color: #636363;">
<tr>
<td colspan="3"><h3 style="color:#ec952d; font-size:18px; ">CASE AND COMPARISON DATA</h3></td>
</tr>
<tr>
<td width="30%" bgcolor="#cbcccb"></td>
<td  width="28%" bgcolor="#cbcccb" style="color: #636363;">Client/Submitted Data</td>
<td  width="42%" bgcolor="#cbcccb" style="color: #636363;">World-Check Data</td>

</tr>
<tr>
<td valign="top" width="30%"><b>Corp Name</b></td>
<td valign="top" width="28%" style="color: #636363;">{{$corp_name}}</td>
<td  valign="top" width="42%" style="color: #636363;">{{$r_name}}</td>
</tr>


<tr>
<td valign="top" width="30%" bgcolor="#e4e4e3"><b>Country</b></td>
<td valign="top" width="28%" bgcolor="#e4e4e3" style="color: #636363;">{{$registration}}</td>
<td  valign="top" width="42%" bgcolor="#e4e4e3" style="color: #636363;">{{$r_countryRegisterd}}</td>
</tr>

</table>
</td>
</tr>

<tr>
<td>
<table class="table-s" width="700px" cellpadding="10" cellspacing="0" border="0" style="margin-top:20px;">
<tr>
<td colspan="3"><h3 style="color:#ec952d; font-size:18px; ">KEY DATA</h3></td>
</tr>

<tr>
<td valign="top" bgcolor="#e4e4e3" width="200px"><b>Source Type</b></td>
<td valign="top" bgcolor="#e4e4e3" width="500px" style="color: #636363;">{{$r_sourcesName}}</td>
</tr>

<tr>
<td valign="top"  width="200px"><b>Category</b></td>
<td valign="top"  width="500px" style="color: #636363;" style="color: #636363;">{{$r_category}}</td>r_category
</tr>

<tr>
<td valign="top" bgcolor="#e4e4e3" width="200px"><b>Corporation Name</b></td>
<td valign="top" bgcolor="#e4e4e3" width="500px" style="color: #636363;">{{$r_name}}</td>
</tr>





<tr>
<td valign="top"  width="200px"><b>Registered Country</b></td>
<td valign="top"  width="500px" style="color: #636363;">{{$r_countryRegisterd}}</td>
</tr>

<tr>
<td valign="top" bgcolor="#e4e4e3" width="200px"><b>Country Location(s)</b></td>
<td valign="top" bgcolor="#e4e4e3" width="500px">
<table width="100%" border="0">
<?php
$i=1;
if(count($corpAddressArray) > 0) {
    foreach($corpAddressArray as $corpAddress) {

    
        
        ?>
        <tr>
            <td colspan="2" style="color: #636363;text-align:left;">Location <?=$i?></td>
            
        </tr>
        <tr>
            <td style="color: #636363;">City </td>
            <td style="color: #636363;"><?php echo $corpAddress['city']?></td>

        </tr>
        <tr>
            <td style="color: #636363;">Region <?=$i?></td>
            <td style="color: #636363;"><?php echo $corpAddress['region']?></td>
        </tr>
    <?php
    } $i++;
}
?>

</table>
</td>
</tr>

<tr>
<td valign="top"  width="200px"><b>Entered Date </b></td>
<td valign="top"  width="500px" style="color: #636363;">{{$r_creationDate}}</td>
</tr>
<tr>
<td valign="top"  width="200px"><b>Updated Date </b></td>
<td valign="top"  width="500px" style="color: #636363;">{{$r_modificationDate}}</td>
</tr>

<tr>
<td valign="top"  width="200px"><b>Update Categorization</b></td>
<td valign="top"  width="500px" style="color: #636363;">{{$r_updateCategory}}</td>
</tr>

</table>
</td>
</tr>




<tr>
<td>
<table class="table-s" width="700px" cellpadding="5" cellspacing="0" border="0" style="margin-top:20px;color: #636363;">
<tr>
<td colspan="3"><h3 style="color:#ec952d; font-size:18px; ">ALIASES</h3></td>
</tr>




<tr>
<td valign="top" bgcolor="#e4e4e3" width="180px"><b>Aliases</b></td>
<td valign="top" bgcolor="#e4e4e3" width="520px">
    <?php
    $NativeData = '';
    if(count($namesArray) > 0) {
        echo "<table>";
       foreach($namesArray as $names)  {
           if($names['type']!='NATIVE_AKA') {
           ?>
            <tr>
                <td></td>
                <td><?php echo $names['fullName']?></td>
            </tr>
       <?php

           } else {
               $NativeData = $names['fullName'];
           }
       }
       echo "</table>";
    }
    ?>
    <?php if($NativeData!='') { 
        //header('Content-type: text/html; charset=UTF-8');
        ?>
   <!-- <table>
        <tr>
            <td>Native Character Names</td>
            <td><?php echo $NativeData;?></td>
        </tr>
    </table>-->

    <?php } ?>

   </td>
</tr>

<tr>
<td valign="top"  width="180px"><b>Native Character Names</b></td>
<td valign="top"  width="520px"></td>
</tr>

</table>
</td>
</tr>



<tr>
<td>
<table class="table-s" width="690px" cellpadding="0" cellspacing="0" border="0" style="margin-top:20px;color: #636363;">
<tr>
<td colspan="3"><h3 style="color:#ec952d; font-size:18px; ">KEYWORDS</h3></td>
</tr>
<tr>
<td valign="top" colspan="3"><b>World-Check Keyword(s)</b></td>

</tr>
<tr>
<td valign="top" bgcolor="#cbcccb" width="190px"><b>Keyword</b></td>
<td valign="top" bgcolor="#cbcccb" width="350px">Description </td>
<td valign="top" bgcolor="#cbcccb" width="150px">Country </td>
</tr>
<?php
if(count($r_sourcesArray) > 0) {
    foreach($r_sourcesArray as $sources) { ?>

<tr>
<td ><b><?php echo $sources['abbreviation']?></b></td>
<td ><?php echo $sources['name']?> </td>
<td >&nbsp; </td>
</tr>
<?php
        
    }
}

?>




</table>
</td>
</tr>






<tr>
<td>
<table class="table-s" width="700px" cellpadding="10" cellspacing="0" border="0" style="margin-top:20px;color: #636363;">
<tr>
<td colspan="2"><h3 style="color:#ec952d; font-size:18px; ">BIOGRAPHY</h3></td>
</tr>

<tr>
<td valign="top"  width="200px"><b>Details</b></td>
<td valign="top"  width="500px">
<?php
if(count($detailsArray) > 0) {
    foreach($detailsArray as  $detail) {
    ?>
    <h4 style="margin:0px;padding: 0px;"><?php echo $detail['detailType']?></h4>
        <p style="margin:0px 0px 10px;padding: 0px;"><?php echo $detail['text']?></p>
    <?php } } ?>
</td>

</tr>

<tr>
<td valign="top"  width="200px"><b>Sub-Category</b></td>
<td valign="top"  width="500px"><?php echo $pep;?></td>

</tr>



</table>
</td>
</tr>




<tr>
<td>
<table class="table-s" width="700px" cellpadding="10" cellspacing="0" border="0" style="margin-top:20px;color: #636363;">
<tr>
<td colspan="3"><h3 style="color:#ec952d; font-size:18px; ">CONNECTIONS / RELATIONSHIPS</h3></td>
</tr>
<tr>
<td valign="top" ><b>Linked companies</b></td>
<td colspan="2">
<?php

/*
if(count($corpAssociates) > 0) {
    foreach($corpAssociates as $associates) {
            $targetEntityId = $associates['targetEntityId'];
            $checkRecord = [];
            $get_users_wci_single = URL::route('get_individual_wci_single');
            $checkRecord = Helpers::checkcallindividualApi($targetEntityId);
            if($checkRecord && $checkRecord->count()) {
            } else {
               
                Helpers::callindividualApi($targetEntityId,csrf_token(), $get_users_wci_single, $parent_id);
            }
    }
}

if(count($corpAssociates) > 0) {
    foreach($corpAssociates as $associates) {


        if((string)$associates['type'] == "AFFILIATED_COMPANY") {
            $targetEntityId = $associates['targetEntityId'];
            
            $checkRecord    = Helpers::checkcallindividualApi($targetEntityId);
            $dataArray      = json_decode($checkRecord->response, JSON_UNESCAPED_UNICODE);
            $nameArray      = $dataArray['names'];
            $category       = $dataArray['category'];
            if (count($nameArray) > 0) {
                echo "<table widht='600px'>";
                foreach ($nameArray as $nameData) {
                    if ($nameData['type'] == "PRIMARY") {
                        $fullName = $nameData['fullName'];
                         echo "<tr><td widht='250px'>$fullName<td><td widht='100px'>&nbsp;<td><td widht='250px'>$category<td></tr>";
                        
                    }
                }
                echo "</table>";
            }
        }

    }
} 
*/
//dd($corpAssociates);

?>
</td>

</tr>

<tr>
<td valign="top" bgcolor="#e4e4e3" width="200px"><b>Linked individuals</b></td>
<td valign="top" bgcolor="#e4e4e3" colspan="2">

<?php
/*
    if(count($corpAssociates) > 0) {
    foreach($corpAssociates as $associates) {


        if((string)$associates['type']!="AFFILIATED_COMPANY") {
            $targetEntityId = $associates['targetEntityId'];

            $checkRecord    = Helpers::checkcallindividualApi($targetEntityId);

            if($checkRecord && $checkRecord->count()) {
            $dataArray      = json_decode($checkRecord->response, JSON_UNESCAPED_UNICODE);
           
            $nameArray      = $dataArray['names'];
            $category       = $dataArray['category'];
            if (count($nameArray) > 0) {
                echo "<table widht='600px'>";
                foreach ($nameArray as $nameData) {
                    if ($nameData['type'] == "PRIMARY") {
                        $fullName = $nameData['fullName'];
                         echo "<tr><td widht='250px'>$fullName<td><td widht='100px'>&nbsp;<td><td widht='250px'>$category<td></tr>";

                    }
                }
                echo "</table>";
            }
            }
        }

    }
} */
?>



</td>

</tr>
</table>
</td>
</tr>


<tr>
<td>
<table class="table-s" width="700px" cellpadding="5" cellspacing="0" border="0" style="margin-top:20px;color: #636363;">
<tr>
<td colspan="3"><h3 style="color:#ec952d; font-size:18px; ">SOURCES</h3></td>
</tr>

<tr>
<td valign="top"  width="200px"><b>Corporation Name </b></td>
<td valign="top"  width="500px">{{$corp_name}}</td>
</tr>

<tr>
<td valign="top" width="200px"><b>Date Printed</b></td>
<td valign="top" width="500px"><?php echo date('d-m-y, h:i');?></td>
</tr>

<tr>
<td valign="top" width="200px"><b>Printed By</b></td>
<td valign="top" width="500px">Dr. Rebel Hanna</td>
</tr>

<tr>
<td valign="top" width="200px"><b>Group</b></td>
<td valign="top" width="500px">Dexter Capital Financial
Consultancy L.L.C - API
(P)</td>
</tr>

<tr>
<td valign="top" width="200px"><b>Information/External Sources</b></td>
<td valign="top" width="500px">
    <?php
    echo $r_weblinksArray;
    ?>
    
</td>
</tr>
</table>
</td>
</tr>


</tbody>
</table>


    <footer>
           <table width="650px">
               <tr>
                   <td width="50px" style="font-size:10px;font-color:#000;font-weight:bold;">Name :
                   </td>
                   <td width="250px" style="font-size:10px;font-color:#000;"><?php echo ucfirst($corp_name);?></td>
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



</body>


</html>