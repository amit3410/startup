

try {

        
        $("#ProCountryMaster").validate({
            rules: {
                
            },
            messages: {
               
            },
            submitHandler: function (form) {
                console.log("Submitted!");
                $('#otherDocSave').prop('disabled', true);
                form.submit();
            }
    });
    
} catch (e) {
    if (typeof console !== 'undefined') {
        console.log(e);
    }
}
