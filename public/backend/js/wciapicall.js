/* global messages, message */

try {
    jQuery(document).ready(function ($){
        var oTable;
        var APISecret   = messages.APISecret;
        var gatwayurl   = messages.gatwayurl;
        var contentType = messages.contentType;
        var gatwayhost  = messages.gatwayhost;
        var apiKey      = messages.apiKey;
        var groupId     = messages.groupId;
        var content     = messages.content;
        var token       = messages.token;
        var token2      = messages.token2;
        var token3      = messages.token3;
       
        
        var user_kyc_id = messages.user_kyc_id;
        var entityName  = messages.entityName;
        //var entityName  = "putin";
        var entityDOB   = messages.entityDOB;
        //var entityDOB   = "1952-07-10";
        var entityType  = 'INDIVIDUAL';
        var update_kyc_Approve = messages.update_kyc_Approve;
        var get_users_wci = messages.get_users_wci;
         function generateAuthHeader(dataToSign) {
            var hash = CryptoJS.HmacSHA256(dataToSign, APISecret);
            return hash.toString(CryptoJS.enc.Base64);
        }
        
        

       function getServerTime() {
                return $.ajax({async: false}).getResponseHeader( 'Date' );
            }
           
        var date = getServerTime();
        var content = '{\n  \"groupId\":\"'+groupId+'\",\n  \"entityType\": \"'+entityType+'\",\n  \"providerTypes\": [\n    \"WATCHLIST\"\n  ],\n  \"name\": \"'+entityName+'\",\n  \"secondaryFields\":[{\"typeId\": \"SFCT_2\",\"dateTimeValue\":\"'+entityDOB+'\"}],\n  \"customFields\":[]\n}';
        
        
        //console.log(content);
        //console.log(content1);
        
        var contentLength = unescape(encodeURIComponent(content)).length;

        var dataToSign = "(request-target): post " + gatwayurl + "cases/screeningRequest\n" +
                "host: " + gatwayhost + "\n" +
                "date: " + date + "\n" +
                "content-type: " + contentType + "\n" +
                "content-length: " + contentLength + "\n" +content;


        var hmac = generateAuthHeader(dataToSign);
        var authorisation = "Signature keyId=\"" + apiKey + "\",algorithm=\"hmac-sha256\",headers=\"(request-target) host date content-type content-length\",signature=\"" + hmac + "\"";
        
        var myKeyVals = {_token: token, authorisation: authorisation, currentDate: date, Signature: hmac, ContentLength: contentLength, content: content, user_kyc_id: user_kyc_id};
        
        $(".getSimilarWCI").on('click',function(){ 
            $.ajax({
                type: 'POST',
                url: get_users_wci,
                data: myKeyVals,
                dataType: "text",
                success: function (resultData) {
                    $('#similarRecords').html(resultData);

                }
            });
        });
        
        $(document).on('click', '.getfullDetail22', function () {
            var getfullDetailID = $(this).attr('id');
            var radioValue = $("input[name='kycdetailID']:checked").val();
            var radioID    = $("input[name='kycdetailID']:checked").attr('id');
            //
        
            var getfullDetailIDArray = radioID.split('_');
            var DynamicIdval = getfullDetailIDArray[1];
            var resultDataArray = radioValue.split('#');
            
            var ReferenceId             = resultDataArray[0];
            var Name                    = resultDataArray[1];
            var resourceType            = resultDataArray[5];
           
            
            
            var matchStrenght   = $("#matchStrenght").val();
            var pep             = $("#pep").val();
            var passport        = $("#passport").val();
            var primaryId       = $("#primaryIdval").val();
            
            var caseId          = $("#caseId").val();
            var systemcaseId    = $("#systemcaseId").val();
            var resultId        = $("#resultId").val();
            
           // alert(caseId);
            //alert(systemcaseId);
           // alert(resultId);  
            //return false;
          
            
            
           // alert(primaryId); return false;
           
            var user_kyc_id             = messages.user_kyc_id;
            var get_users_wci_single = messages.get_users_wci_single;
           // var date = messages.datetime;
            
            //var date = new Date().toGMTString();
            //var date = '<?php echo gmdate('D, d M Y H:i:s \G\M\T', time());?>';
           // Mon, 17 Aug 2020 15:08:08 GMT
           // Tue Aug 18 2020 13:53:37 GMT+0530 (India Standard Time)
           function getServerTime() {
                return $.ajax({async: false}).getResponseHeader( 'Date' );
            }
           
            var date = getServerTime();
           // return false;
            var gatwayurl   = messages.gatwayurl;
            var contentType = messages.contentType;
            var gatwayhost  = messages.gatwayhost;
            var apiKey      = messages.apiKey;
            var groupId     = messages.groupId;
            var profileId   = ReferenceId;
            var encoded = encodeURIComponent(profileId);
            var dataToSign = "(request-target): get " + gatwayurl + "reference/profile/" + encoded + "\n" +
                    "host: " + gatwayhost + "\n" +
                    "date: " + date;
            var hmac = generateAuthHeader(dataToSign);
            var authorisation = "Signature keyId=\"" + apiKey + "\",algorithm=\"hmac-sha256\",headers=\"(request-target) host date\",signature=\"" + hmac + "\"";
            console.log(date);


/////////////////////////////
            var myKeyVals = {_token: token2, authorisation: authorisation, currentDate: date, Signature: hmac, profileID: profileId, user_kyc_id: user_kyc_id, matchStrenght: matchStrenght, pep: pep, passport: passport,primaryId:primaryId,caseId:caseId,systemcaseId:systemcaseId,resultId:resultId};
            $.ajax({
                type: 'POST',
                url: get_users_wci_single,
                data: myKeyVals,
                dataType: "text",
                
                beforeSend: function() {
                    $('#my-loading').css('display', 'block');
                },
                success: function (resultData) {
                    $('#my-loading').css('display', 'none');
                    $('#profileDetail_'+resourceType + "_" + DynamicIdval).html(resultData);
                }
            });
        });
        
        
        
        
        
        
      
        $(document).on('click', '#btn-approved', function () {
            var myKeyVals = {_token: token3, user_kyc_id: user_kyc_id};
            $.ajax({
                type: 'POST',
                url: update_kyc_Approve,
                data: myKeyVals,
                dataType: "text",
                success: function (resultData) {
                    //$('#profileDetail_' + DynamicIdval).html(resultData);
                    alert("Success");
                }
            });
        });
        
        //all
        $(document).on('click', '#checkAll', function() {

            if ($(this).val() == 'Check All') {
                $('#result_id').val('');
                $('.similarRecordsAll input').prop('checked', true);
                $(this).val('Uncheck All');
                var resultId = '';
                    $('.similarRecordsAll input[type="checkbox"]:checked').each(function(){
                     resultId = resultId+$(this).val()+",";
                   });
                   $('#result_id').val(resultId);
            } else {
                $('.similarRecordsAll input').prop('checked', false);
                $('#result_id').val('');
                $(this).val('Check All');
            }
        });
        
        
        //Resolved
        $(document).on('click', '#checkAllResolved', function() {

            if ($(this).val() == 'Check All') {
                $('#result_id').val('');
                $('.similarRecordsResolved input').prop('checked', true);
                $(this).val('Uncheck All');
                var resultId = '';
                    $('.similarRecordsResolved input[type="checkbox"]:checked').each(function(){
                     resultId = resultId+$(this).val()+",";
                   });
                   $('#result_id').val(resultId);
            } else {
                $('.similarRecordsResolved input').prop('checked', false);
                $('#result_id').val('');
                $(this).val('Check All');
            }
        });
        
        
        
        ///Auto resolved
        $(document).on('click', '#checkAllAutoRes', function() {

            if ($(this).val() == 'Check All') {
                $('#result_id').val('');
                $('.similarRecordsAutoRes input').prop('checked', true);
                $(this).val('Uncheck All');
                var resultId = '';
                    $('.similarRecordsAutoRes input[type="checkbox"]:checked').each(function(){
                     resultId = resultId+$(this).val()+",";
                   });
                   $('#result_id').val(resultId);
            } else {
                $('.similarRecordsAutoRes input').prop('checked', false);
                $('#result_id').val('');
                $(this).val('Check All');
            }
        });
        
        
        ///Positive
        $(document).on('click', '#checkAllPositive', function() {

            if ($(this).val() == 'Check All') {
                $('#result_id').val('');
                $('.similarRecordsPositive input').prop('checked', true);
                $(this).val('Uncheck All');
                var resultId = '';
                    $('.similarRecordsPositive input[type="checkbox"]:checked').each(function(){
                     resultId = resultId+$(this).val()+",";
                   });
                   $('#result_id').val(resultId);
            } else {
                $('.similarRecordsPositive input').prop('checked', false);
                $('#result_id').val('');
                $(this).val('Check All');
            }
        });
        
         $(document).on('click', '#checkAllPossible', function() {

            if ($(this).val() == 'Check All') {
                $('#result_id').val('');
                $('.similarRecordsPossible input').prop('checked', true);
                $(this).val('Uncheck All');
                var resultId = '';
                    $('.similarRecordsPossible input[type="checkbox"]:checked').each(function(){
                     resultId = resultId+$(this).val()+",";
                   });
                   $('#result_id').val(resultId);
            } else {
                $('.similarRecordsPossible input').prop('checked', false);
                $('#result_id').val('');
                $(this).val('Check All');
            }
        });
        
        $(document).on('click', '#checkAllUnspecified', function() {

            if ($(this).val() == 'Check All') {
                $('#result_id').val('');
                $('.similarRecordsUnspecified input').prop('checked', true);
                $(this).val('Uncheck All');
                var resultId = '';
                    $('.similarRecordsUnspecified input[type="checkbox"]:checked').each(function(){
                     resultId = resultId+$(this).val()+",";
                   });
                   $('#result_id').val(resultId);
            } else {
                $('.similarRecordsUnspecified input').prop('checked', false);
                $('#result_id').val('');
                $(this).val('Check All');
            }
        });
        
        
        


        $('.result_id_checkbox').click(function () {
            var resultId = '';
            $('#similarRecords input[type="checkbox"]:checked').each(function(){
             resultId = resultId+$(this).val()+",";
           });
           $('#result_id').val(resultId);
           //alert(resultId);
        });
        
       
        $("#personalResolveFormAll").on('submit', function(e){
            
           // if($("#personalResolveFormAll").valid() == false) { return false;}
           // e.preventDefault();
            var resolutionStatus = $("#statuskeyValue").val();
            var resolutionRisk   = $("#riskkeyvalue").val();
            var resolutionReason = $("#reasonkeyalue").val();
            var resolutionReasoncomment = $("#resolutionReasoncomment").val();
            var case_id         = $("#case_id").val();
            var case_system_id  = $("#case_system_id").val();
            var result_id = $("#result_id").val();
            //console.log(result_id);
                if(result_id == '') {
                    $("#CoustomError").html("Please select at Least one case.");
                    return false;
                } else {
                    $("#CoustomError").html(""); 
                }
        
            var result_id_Array = result_id.split(',');
            var result_idStore = '';
            for(var i = 0; i < result_id_Array.length; i++) {
                result_id_Array[i] = result_id_Array[i].replace(/^\s*/, "").replace(/\s*$/, "");
                if(result_id_Array[i]!='') {
                     result_idStore+= '\"' + result_id_Array[i] + '\"'+' , ';
                }
            }
        
            var userKycId = $("#userKycId").val();
            var primaryId = $("#primaryId").val();
            var contentType = "{{config('common.contentType')}}";
            var resolutionRemark =resolutionReasoncomment;
            
            //var date = new Date().toGMTString();
            function getServerTime() {
                return $.ajax({async: false}).getResponseHeader( 'Date' );
            }
            var date = getServerTime();
            var APISecret   = messages.APISecret;
            var gatwayurl   = messages.gatwayurl;
            var contentType = messages.contentType;
            var gatwayhost  = messages.gatwayhost;
            var apiKey      = messages.apiKey;
            var groupId     = messages.groupId;
            var token       = messages.token;
            var get_users_resolved_all = messages.get_users_resolved_all;
           
            result_idStore = result_idStore.replace(/,\s*$/, "");
            //var content = '{\n  \"resultIds\":\"' + result_id + '\",\n  \"statusId\": \"' + resolutionStatus + '\",\n  \"riskId\": \"' + resolutionRisk + '\",\n  \"reasonId\": \"' + resolutionReason + '\",\n  \"resolutionRemark\": \"' + resolutionRemark + '\" \n}';
            //var content = '{\n  \"resultIds\":[\n  \"' + result_id + '\"\n , \"' + result_id2 + '\"\n ],\n  \"statusId\": \"' + resolutionStatus + '\",\n  \"riskId\": \"' + resolutionRisk + '\",\n  \"reasonId\": \"' + resolutionReason + '\",\n  \"resolutionRemark\":\" '+ resolutionRemark +'\"\n}';
            var content = '{\n  \"resultIds\":[\n  '+result_idStore+' ],\n  \"statusId\": \"' + resolutionStatus + '\",\n  \"riskId\": \"' + resolutionRisk + '\",\n  \"reasonId\": \"' + resolutionReason + '\",\n  \"resolutionRemark\":\" '+ resolutionRemark +'\"\n}';
            var contentLength = unescape(encodeURIComponent(content)).length;
          //  console.log(content);
            //return false;
            var dataToSign = "(request-target): put " + gatwayurl + "cases/" + case_system_id + "/results/resolution" + "\n" +
                "host: " + gatwayhost + "\n" +
                "date: " + date + "\n" +
                "content-type: " + contentType +"\n" + 
                "content-length: " + contentLength + "\n" + 
                content;
            var hmac = generateAuthHeader(dataToSign, APISecret);
            var authorisation = "Signature keyId=\"" + apiKey + "\",algorithm=\"hmac-sha256\",headers=\"(request-target) host date content-type content-length\",signature=\"" + hmac + "\"";
           // return false;
            var myKeyVals = {_token: token, authorisation: authorisation, currentDate: date, Signature: hmac, caseId: case_id, contentLength: contentLength, content: content, case_system_id: case_system_id,userKycId:userKycId, primaryId:primaryId,result_id : result_idStore};
            console.log(myKeyVals);
            $.ajax({
                    type: 'POST',
                    url: get_users_resolved_all,
                    data: myKeyVals,
                    dataType: "text",
                    beforeSend: function() {
                      $('#my-loading').css('display', 'block');
                    },
                    success: function (resultData) {
                        // $('#my-loading').css('display', 'none'); 
                       // alert(resultData.trim());
                        if (resultData.trim() == "Success") {
                            $('#my-loading').hide();
                        }
                    }
                }); 
               // return false;
        });
        
    });
} catch (e) {
    if (typeof console !== 'undefined') {
        console.log(e);
    }
}    