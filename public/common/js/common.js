
  
    function generateAuthHeader(dataToSign, APISecret) {
        var hash = CryptoJS.HmacSHA256(dataToSign, APISecret);
        return hash.toString(CryptoJS.enc.Base64);
    }

    
    $('#personalResolveForm').validate({
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
    
    
    $(document).on('click', '.resolvesubmit', function () {
        
        var resolutionStatus = $("#statuskeyValue").val();
        var resolutionRisk   = $("#riskkeyvalue").val();
        var resolutionReason = $("#reasonkeyalue").val();
        var resolutionReasoncomment = $("#resolutionReasoncomment").val();
        
        var case_id = $("#case_id").val();
        var case_system_id = $("#case_system_id").val();
        
        var result_id = $("#result_id").val();
        var userKycId = $("#userKycId").val();
        var primaryId = $("#primaryId").val();
        var responceProfileId = $("#responceProfileId").val();
        
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
            var token     = messages.token;
            var get_users_resolved = messages.get_users_resolved;
            //return false;
            //var content = '{\n  \"resultIds\":\"' + result_id + '\",\n  \"statusId\": \"' + resolutionStatus + '\",\n  \"riskId\": \"' + resolutionRisk + '\",\n  \"reasonId\": \"' + resolutionReason + '\",\n  \"resolutionRemark\": \"' + resolutionRemark + '\" \n}';
            
            var content = '{\n  \"resultIds\":[\n  \"' + result_id + '\"\n ],\n  \"statusId\": \"' + resolutionStatus + '\",\n  \"riskId\": \"' + resolutionRisk + '\",\n  \"reasonId\": \"' + resolutionReason + '\",\n  \"resolutionRemark\":\" '+ resolutionRemark +'\"\n}';
           // var content = '{\n  \"resultIds\":[\n  '+result_idStore+' ],\n  \"statusId\": \"' + resolutionStatus + '\",\n  \"riskId\": \"' + resolutionRisk + '\",\n  \"reasonId\": \"' + resolutionReason + '\",\n  \"resolutionRemark\":\" '+ resolutionRemark +'\"\n}';
            var contentLength = unescape(encodeURIComponent(content)).length;
            //console.log(content);
            //return false;
            var dataToSign = "(request-target): put " + gatwayurl + "cases/" + case_system_id + "/results/resolution" + "\n" +
                "host: " + gatwayhost + "\n" +
                "date: " + date + "\n" +
                "content-type: " + contentType +"\n" + 
                "content-length: " + contentLength + "\n" + 
                content;
         
            var hmac = generateAuthHeader(dataToSign, APISecret);
            var authorisation = "Signature keyId=\"" + apiKey + "\",algorithm=\"hmac-sha256\",headers=\"(request-target) host date content-type content-length\",signature=\"" + hmac + "\"";
            console.log(dataToSign);
            console.log(authorisation);
/////////////////////////////
            var myKeyVals = {_token: token, authorisation: authorisation, currentDate: date, Signature: hmac, caseId: case_id, contentLength: contentLength, content: content, case_system_id: case_system_id,userKycId:userKycId, primaryId:primaryId, responceProfileId:responceProfileId};
          
        console.log(myKeyVals);
        $.ajax({
                type: 'POST',
                url: get_users_resolved,
                data: myKeyVals,
                dataType: "text",
                beforeSend: function() {
                  $('#my-loading').css('display', 'block');
                },
                success: function (resultData) {
                    // $('#my-loading').css('display', 'none'); 
                    if (resultData.trim() == "Success") {
                        //alert(resultData.trim());
                        $('#my-loading').hide();
                    }
                }
            }); 
            //return false;
        });
        
        
        
        $('#personalcasesForm').validate({
         ignore: [], 
            rules: {

                  caseid : {
                    required: true,
                 },
             },
            messages:{
                caseid: {
                    required: "Required", 
                },
            }
        });
    
