/* global messages, message */

try {
    jQuery(document).ready(function ($) {

        
        /*validation for Login Registration page */

        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        }); 

       var baseurl = window.location.origin;
      
        $("#compregisterForm").validate({
            rules: {
                    company_name: {
                    required: true,
                    minlength:2,
                    maxlength: 50,
                    pattern:"^[a-z A-Z .\s]+$",

                   },
                   comp_trade_in :{
                    maxlength: 30,
                    pattern:"^[a-z A-Z 0-9\s]+$",
                   },
                  
                first_name: {
                    required: true,
                    lettersonly:true,
                    

                },
                middle_name: {
                    lettersonly:true,
                    

                },
                last_name: {
                    lettersonly:true,
                    required: true,
                    
                },
                
                dob: {
                   validDOB : true, 
                   required :true,
                },
                email: {
                    required: true,
                    pattern:/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/,
                    maxlength: 100,
                    remote:{
                          url:baseurl+'/checkuseremail',
                          type:'POST',
                          data:{
                            userdata:function()
                            {
                              return $('#email').val()
                            },
                            datatype:'email'
                          }
                        }
                   
                },
                country_code :{
                    required :true,
                },

                phone: {
                    required :true,
                    minlength: 8,
                    maxlength: 20,
                    phone_number : true,
                },
                country_id: {
                    required: true,
                    number: true
                },
               
            },
            messages: {


                company_name: {
                    pattern  : messages.invalid_name,
                    minlength:messages.company_minlength,
                    maxlength: messages.invalid_len,
                },
                
                comp_trade_in: {
                    maxlength: messages.trade_maxlength,
                    pattern: 'Enter Only Alphanumeric',
                },
                first_name: {
                    required: messages.req_first_name,
                    lettersonly: messages.invalid_first_name,
                    
                },
                middle_name: {
                    
                    maxlength: messages.middle_name_max_length,
                    lettersonly: messages.invalid_middle_name,
                },
                last_name: {
                    required: messages.req_last_name,
                    lettersonly: messages.invalid_last_name,
                    
                },
                dob: {
                    validDOB: messages.invalid_age,
                    required: messages.req_dob,
                },
                email: {
                    required: messages.req_email,
                    pattern: messages.invalid_email,
                    maxlength: messages.email_max_length,
                    remote: messages.email_exist,
                },
                country_code :{
                    required : messages.req_country_code,
                },
                 
                phone: {
                    minlength: messages.phone_minlength,
                    maxlength: messages.phone_maxlength,
                    number: messages.invalid_phone,
                    phone_number:messages.positive_phone_no,
                },
                country_id: {
                    required: messages.req_country,
                    number: messages.invalid_country
                },
                
            }  
               
            
        });

        /*End*/

        //Preview profile pic
       

        //Function to show image before upload
         //validate phone no


         $.validator.addMethod(
        "validDOB",
        function(value, element){              
            var from = value.split("/"); // DD MM YYYY

            var year = from[2];
            var month = from[1];
            var day = from[0];
            var age = 18;

            var mydate = new Date();
            mydate.setFullYear(year, month-1, day);

            var currdate = new Date();
            var setDate = new Date();

            setDate.setFullYear(mydate.getFullYear() + age, month-1, day);

            if ((currdate - setDate) > 0){
                return true;
            }else{
                return false;
            }
        },
         messages.invalid_age
    );
        $.validator.addMethod('phone_number',
        function (value) { 
                 return Number(value) > 0;
             }, messages.positive_phone_no

        );

        
        
        
        
            //allow only number....
            $(".numcls").keypress(function (evt) {
                var iKeyCode = (evt.which) ? evt.which : evt.keyCode
             if (iKeyCode = 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
                 return false;

             return true;
           });
    });
} catch (e) {
    if (typeof console !== 'undefined') {
        console.log(e);
    }
}
