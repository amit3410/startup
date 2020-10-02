
  
    function generateAuthHeader(dataToSign, APISecret) {
        var hash = CryptoJS.HmacSHA256(dataToSign, APISecret);
        return hash.toString(CryptoJS.enc.Base64);
    }

    
    function checktoolkit() { 
         //var date = new Date().toGMTString();
         //var date = "<?php echo gmdate('D, d M Y H:i:s \G\M\T', time());?>";
         //alert("debug");
         var groupId = messagesG.groupId;
      
            var date = $.ajax({async: false}).getResponseHeader( 'Date' );
       //  console.log(groupId);
         var dataToSign = "(request-target): get " + messages.gatwayurl + "groups/"+groupId+"/resolutionToolkits\n" +
                "host: " + messages.gatwayhost + "\n" +
                "date: " + date;
        var hmac = generateAuthHeader(dataToSign, messages.APISecret);
        var authorisation = "Signature keyId=\"" + messages.apiKey + "\",algorithm=\"hmac-sha256\",headers=\"(request-target) host date\",signature=\"" + hmac + "\"";
        var myKeyVals = {_token: messages.token, authorisation: authorisation, currentDate: date, Signature: hmac,groupId: groupId};

        $.ajax({
            type: 'POST',
            url: messages.get_resolution_toolkit,
            data: myKeyVals,
            dataType: "text",
            beforeSend: function() {
               $('#my-loading').css('display', 'block');
            },
            success: function (resultData) {
                $("#my-loading").hide();
                if (resultData.trim() == "Success") {
                   

                }
            }
        }); 
    }
    
    
