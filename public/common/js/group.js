
  
    function generateAuthHeader(dataToSign, APISecret) {
        var hash = CryptoJS.HmacSHA256(dataToSign, APISecret);
        return hash.toString(CryptoJS.enc.Base64);
    }

     function checkgroupId() {
        //var date = messages.datetime;
        var date = $.ajax({async: false}).getResponseHeader( 'Date' );
            //console.log(content1);
        var dataToSign = "(request-target): get " + messages.gatwayurl + "groups\n" +
        "host: " + messages.gatwayhost + "\n" +
        "date: " + date;
        var hmac = generateAuthHeader(dataToSign, messages.APISecret);
        var authorisation = "Signature keyId=\"" + messages.apiKey + "\",algorithm=\"hmac-sha256\",headers=\"(request-target) host date\",signature=\"" + hmac + "\"";
        var myKeyVals = {_token: messages.token, authorisation: authorisation, currentDate: date};
        //alert(authorisation);
       console.log(myKeyVals);
        $.ajax({
            type: 'POST',
            url: messages.get_groupID,
            data: myKeyVals,
            dataType: "text",
            success: function (resultData) {
             //console.log(resultData); return false;
                if (resultData.trim() == "Success") {
                    //alert("debug3");
                   // $("#personalwcapi").modal('hide');
                  //  window.parent.location.href = messages.individual_user;
                }
            }
        });
    }  
    