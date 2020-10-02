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

    


 <div class="table-responsive">
<table width="650px" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff"  style="padding:0px; margin:0 auto; font-family: 'Roboto', sans-serif; box-shadow: -1px -1px 11px 1px #ccc; font-size:13px;">


<tr>
  <td>
    <table class="table-s" width="700px" bgcolor="#C2C2C2" cellpadding="0" cellspacing="0" border="0" style="margin-top:10px; color: #636363;">
         <tr>
<td colspan="2" style="font-weight:100;color:#636363; font-size:38px; padding: 40px 25px 10px;">WORLD-CHECK ONE</td>
</tr>
<tr>
<td style="font-weight:100;color:#636363; font-size:15px;padding: 10px 25px 10px;">WORLD-CHECK MATCH DETAILS REPORT</td>
<td align="right" style="font-weight:100;color:#636363; font-size:15px;text-align: right;padding: 10px 25px 10px;">STRICTLY CONFIDENTIAL</td>
</tr>
    </table>
  </td>
</tr>




<tbody>
<tr>
<td>
<table class="table-s" width="700px" cellpadding="0" cellspacing="0" border="0" style="margin-top:10px; color: #636363;">
<tr>
<td colspan="4"><h3 style="color:#ec952d; font-size:18px; ">WORLD-CHECK PROFILE UID: {{$referenceId}}</h3></td>
</tr>
<tr>
<td width="20%" bgcolor="#e4e4e3"><b>Name</b></td>
<td width="30%" bgcolor="#e4e4e3" style="border-top:0px;border-right:7px solid #fff;">{{$name}}</td>
<td width="20%" bgcolor="#e4e4e3" ><b>Date Printed</b></td>
<td width="30%" bgcolor="#e4e4e3" ></td>
</tr>
<tr>
<td valign="top"><b>Printed By</b></td>
<td valign="top">Dr. Rebel Hanna</td>
<td  valign="top"><b>Group</b></td>
<td  valign="top">Dexter Capital Financial Consultancy L.L.C - API (P)</td>
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
<table class="table-s"  style="width: 700px" cellpadding="0" cellspacing="0" border="0" style="margin-top:10px;font-family: 'Roboto', sans-serif;color: #636363;">
<tr>
<td colspan="3"><h3 style="color:#ec952d; font-size:18px; padding: 10px 0px;">CASE AND COMPARISON DATA</h3></td>
</tr>
<tr>
<td width="20%" bgcolor="#cbcccb"></td>
<td  width="40%" bgcolor="#cbcccb" style="color: #636363">Client/Submitted Data</td>
<td  width="40%" bgcolor="#cbcccb" style="color: #636363">World-Check Data</td>

</tr>
<tr>
<td valign="top" ><b>Name</b></td>
<td valign="top" style="color: #636363"> {{$name}}</td>
<td  valign="top" style="color: #636363">{{$r_name}}</td>
</tr>

<?php
if($gender){
 if($gender == 'M') {
    $gender = "MALE";
 } else if($gender == 'F')  {

     $gender = "Fe-Male";
 }
}

if($gender == $r_gender) {
   $gMatch = "<span style='color:#309054';>Y</span>";
} else {
  $gMatch = "<span style='color:#309054';>x</span>";
}
?>

<tr>
<td valign="top" bgcolor="#e4e4e3"><b>Gender</b></td>
<td valign="top" bgcolor="#e4e4e3" style="color: #636363">{{$gender}}</td>
<td  valign="top" bgcolor="#e4e4e3" style="color: #636363">{{$r_gender}}</td>
</tr>

<tr>
<td valign="top" ><b>Date of Birth</b></td>
<td valign="top" style="color: #636363">{{$dob}} </td>
<td  valign="top" style="color: #636363">{{$r_dob}}</td>
</tr>

<tr>
<td valign="top" bgcolor="#e4e4e3"><b>Nationality</b></td>
<td valign="top" bgcolor="#e4e4e3" style="color: #636363">{{$nationality}}</td>
<td  valign="top" bgcolor="#e4e4e3" style="color: #636363">{{$r_nationality}}</td>
</tr>




</table>
</td>
</tr>








<tr>
<td>
<table class="table-s" width="700px" cellpadding="0" cellspacing="0" border="0" style="margin-top:10px;font-family: 'Roboto', sans-serif;color: #636363;">
<tr>
<td colspan="2"><h3 style="color:#ec952d; font-size:18px; ">KEY DATA</h3></td>
</tr>

<tr>
<td valign="top" bgcolor="#e4e4e3" width="200px"><b>Source Type</b></td>
<td valign="top" bgcolor="#e4e4e3" width="480px">{{$r_sourcesName}}</td>
</tr>

<tr>
<td valign="top"  width="200px"><b>Category</b></td>
<td valign="top"  width="60%">{{$r_category}}</td>
</tr>

<tr>
<td valign="top" bgcolor="#e4e4e3" width="40%"><b>Name</b></td>
<td valign="top" bgcolor="#e4e4e3" width="60%">{{$r_name}}</td>
</tr>

<tr>
<td valign="top"  width="40%"><b>Gender</b></td>
<td valign="top"  width="60%">{{$r_gender}}</td>
</tr>

<tr>
<td valign="top" bgcolor="#e4e4e3" width="40%"><b>Date of Birth</b></td>
<td valign="top" bgcolor="#e4e4e3" width="60%">{{$r_dob}}</td>
</tr>

<tr>
<td valign="top"  width="40%"><b>Nationality</b></td>
<td valign="top"  width="60%">{{$r_nationality}}</td>
</tr>

<tr>
<td valign="top" bgcolor="#e4e4e3" width="40%"><b>Country Location(s)</b></td>
<td valign="top" bgcolor="#e4e4e3" width="60%">

</td>
</tr>

<tr>
    <td colspan="2">
        <table class="table-s" width="700px" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td width="30%"></td>
                <td width="40%"><b>City</b></td>
                <td width="30%">-</td>
            </tr>
            <tr>
                <td width="30%"></td>
                <td width="40%"><b>Region</b></td>
                <td width="30%">-</td>
            </tr>
        </table>
    </td>
</tr>


<tr>
<td valign="top" bgcolor="#e4e4e3" width="40%"><b>Title</b></td>
<td valign="top" bgcolor="#e4e4e3"  width="60%">{{$r_title}}</td>
</tr>

<tr>
<td valign="top" bgcolor="" width="40%"><b>Position</b></td>
<td valign="top" bgcolor="" width="60%">{{$r_position}}</td>
</tr>

<tr>
<td valign="top" bgcolor="#e4e4e3" width="40%"><b>Entered Date</b></td>
<td valign="top" bgcolor="#e4e4e3" width="60%">{{$r_creationDate}}</td>
</tr>

<tr>
<td valign="top" bgcolor="" width="40%"><b>Updated Date</b></td>
<td valign="top" bgcolor="" width="60%">{{$r_modificationDate}}</td>
</tr>

<tr>
<td valign="top" bgcolor="#e4e4e3" width="40%"><b>Update Categorization</b></td>
<td valign="top" bgcolor="#e4e4e3"  width="60%">{{$r_updateCategory}}</td>
</tr>

</table>
</td>
</tr>




<tr>
<td>
<table class="table-s" width="700px" cellpadding="5" cellspacing="0" border="0" style="margin-top:10px;font-family: 'Roboto', sans-serif;color: #636363;">
<tr>
<td colspan="2"><h3 style="color:#ec952d; font-size:18px; ">ALIASES</h3></td>
</tr>





<tr>
<td valign="top" bgcolor="#e4e4e3" width="200px"><b>Aliases</b></td>
<td valign="top" bgcolor="#e4e4e3" width="480px">
    

   </td>
</tr>

<tr>
<td valign="top"  width="40%"><b>Native Character Names</b></td>
<td valign="top"  width="60%">

   

</td>
</tr>

</table>
</td>
</tr>



<tr>
<td>
<table class="table-s" width="700px" cellpadding="10" cellspacing="0" border="0" style="margin-top:10px;font-family: 'Roboto', sans-serif;color: #636363;">
<tr>
<td colspan="3"><h3 style="color:#ec952d; font-size:18px; ">KEYWORDS</h3></td>
</tr>
<tr>
<td valign="top" colspan="3"><b>World-Check Keyword(s)</b></td>

</tr>
<tr>
<td valign="top" bgcolor="#cbcccb" width="150px"><b>Keyword</b></td>
<td valign="top" bgcolor="#cbcccb" width="380px">Description </td>
<td valign="top" bgcolor="#cbcccb" width="150px">Country </td>
</tr>





</table>
</td>
</tr>



<tr>
<td>
<table class="table-s" width="700px" cellpadding="0" cellspacing="0" border="0" style="margin-top:10px;font-family: 'Roboto', sans-serif;color: #636363;">
<tr>
<td colspan="2"><h3 style="color:#ec952d; font-size:18px; ">BIOGRAPHY</h3></td>
</tr>

<tr>
<td valign="top"  width="200px"><b>Details</b></td>
<td valign="top"  width="480px">






</td>

</tr>

<tr>
<td valign="top"  width="200px"><b>Sub-Category</b></td>
<td valign="top"  width="480px"><?php echo $pep;?></td>

</tr>



</table>
</td>
</tr>



<tr>
<td>
<table class="table-s" width="625px" cellpadding="0" cellspacing="0" border="0" style="margin-top:10px;font-family: 'Roboto', sans-serif;color: #636363;">
<tr>
<td colspan="3" ><h3 style="color:#ec952d; font-size:18px; ">CONNECTIONS / RELATIONSHIPS</h3></td>
</tr>
<tr>
<td valign="top" colspan="3"><b>Linked companies</b></td>

</tr>
<tr>
<td valign="top" bgcolor="#e4e4e3" width="140px"><b>Linked individuals</b></td>
<td valign="top" bgcolor="#e4e4e3" width="300px"></td>
<td valign="top" bgcolor="#e4e4e3" width="200px"></td>
</tr>
</table>
</td>
</tr>


<tr>
<td>
<table class="table-s" width="650px" cellpadding="0" cellspacing="0" border="0" style="margin-top:10px;font-family: 'Roboto', sans-serif;color: #636363;"z>
<tr>
    <td colspan="3"><h3 style="color:#ec952d; font-size:18px; ">SOURCES</h3></td>
</tr>

<tr>
<td valign="top"  width="200px"><b>Name</b></td>
<td valign="top"  width="500px">{{$name}}</td>
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
  <?php if(count($r_weblinksArray) > 0) {
      foreach($r_weblinksArray as $linkarray) {

         echo $linkarray['uri']."<br>";
      }
      
  }?>
</td>
</tr>
</table>
</td>
</tr>

<tr>
    <td>
        <table class="table-s" width="650px" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td>
                    <p style="font-size: 9px;margin:50px 0px 25px;color:#636363;">Legal Notice</p>
                    <p style="font-size: 9px;margin-bottom: 0px;color:#636363;">The contents of this record are private and confidential and should not be disclosed to third parties unless: (i) the terms of your agreement with Refinitiv allow you to
do so; (ii) the record subject requests any data that you may hold on them, and such data includes their World-Check record; or (iii) you are under some other legal
obligation to do so. You must consider and abide by your own obligations in relation to the data privacy rights of individuals and must notify them of your intention to
search against World-Check and provide them with information contained in the World-Check privacy statement :<a style="color:#ec952d;" href="https://www.refinitiv.com/en/products/world-check-
kyc-screening/privacy-statement">https://www.refinitiv.com/en/products/world-check-
kyc-screening/privacy-statement</a>. You shall not rely upon the content of this report without making independent checks to verify the information contained therein.
Information correlated is necessarily brief and should be read by you in the context of the fuller details available in the external sources to which links are provided.
The accuracy of the information found in the underlying sources must be verified with the record subject before any action is taken and you should inform us if any
links to the sources are broken. If this record contains negative allegations, it should be assumed that such allegations are denied by the subject. You should not
draw any negative inferences about individuals or entities merely because they are identified in the database, nor because they are shown as "Reported being linked
to" others identified in the database. The nature of linking varies considerably. Many persons are included solely because they hold or have held prominent political
positions or are connected to such individuals.</p>
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
                   <td width="250px" style="font-size:10px;font-color:#000;"><?php echo ucfirst($name);?></td>
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
</div>


    

</body>
</html>