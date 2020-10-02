/* global messages, message */

try {
    jQuery(document).ready(function ($) {

        
  
       //validate phone no
        $.validator.addMethod('invalid_number',
        function (value) { 
                 return Number(value) > 0;
             }, messages.enter_invalid_number

        );

      





    });
} catch (e) {
    if (typeof console !== 'undefined') {
        console.log(e);
    }
}
