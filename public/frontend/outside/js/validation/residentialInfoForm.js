/* global messages, message */
try {
    jQuery(document).ready(function ($) {


        $('.datepicker').keydown(function (e) {
            e.preventDefault();
            return false;
        });
        
        $('.datepicker').on('paste',function (e) {
            e.preventDefault();
            return false;
        });
        
        
        $.validator.addMethod('alphnum_spacial1', function (value) { 
            return /^[A-Za-z0-9\s\/.&_-]+$/.test(value); 
        },messages.alphnum_spacial1);
        
        $.validator.addMethod('alpha_num_hype_uscor_space', function (value) { 
            return /^[A-Za-z0-9\s_-]+$/.test(value); 
        },messages.alphnum_hyp_uscore_space);
        
        $.validator.addMethod('alphnum_hyp_uscore_space_fslace', function (value) { 
            return /^[A-Za-z0-9\s\/.&_-]+$/.test(value); 
        },messages.alphnum_hyp_uscore_space_fslace);
        
        $.validator.addMethod('alphnum_spacial2', function (value) { 
            return /^[A-Za-z0-9\s\+_#.*!@&-]+$/.test(value); 
        },messages.alphnum_space_spacial_chars);
        
        $.validator.addMethod('num_hyp_space', function (value) { 
            return /^[0-9\s-]*$/.test(value); 
        },messages.num_hyp_space);
        
     //     ,   , 
        
        $.validator.addMethod('chk_post_box', function (value) { 
            return /^[A-Za-z0-9\s\/-]+$/.test(value);
         },messages.invalid_post_box);
        
       /* $.validator.addMethod('chk_mob_no', function (value) { 
            return /^(\+\d{1,3}[- ]?)?\d{10}$/.test(value); 
        },messages.invalid_mobile);*/
         //validate phone no
        $.validator.addMethod('invalid_number',
        function (value) { 
                 return Number(value) > 0;
             }, messages.enter_invalid_number

        );

        
        $.validator.addMethod('chk_fax_no', function (value) { 
            return /^\+?[0-9]+$/.test(value); 
        },messages.invalid_fax_no);


     //validate form 
     $('#residentialInformationForm').validate({
     ignore: [], 
     rules: {
            country_id: {
            required: true,
            },
            city_id: {
            required: true,
            pattern:"^[a-z A-Z\s]+$",
            maxlength:30,
            },
            region: {
               required: true,
               pattern:"^[a-z A-Z\s]+$",
               minlength:3,
               maxlength:30,
            },
            building_no: {
               required: true,
               alphnum_hyp_uscore_space_fslace:true,
               maxlength:30,
            },
            floor_no: {
               required: true,
                alphnum_hyp_uscore_space_fslace:true,
                maxlength:30,
            },
            street_addr: {
               required: true,
               alphnum_hyp_uscore_space_fslace:true,
               minlength:3,
               maxlength:30,
            },
            postal_code: {
               
               digits:true,
               maxlength:30,
            },
            post_box: {
            digits:true,
            maxlength:30,

            },
            country_code :{
            required:true,
            },

            addr_phone_no :{
             minlength:8,
             maxlength:20,
             digits:true,
             
            },

            tele_country_code :{
            //required:true,
            },
            addr_mobile_no: {
            required: true,
            invalid_number: true,  
            maxlength:20,
            },

            fax_country_code :{
           // required:true,
            },

            addr_fax_no: {

            digits:true,
            minlength:6,
            maxlength:15,
            },
            addr_email:{
            pattern:/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/,


            },
            },       
            messages:{
            country_id: {              
            required:messages.req_this_field,           
            },
                city_id: {
                pattern:messages.only_character,    
                required:messages.req_this_field,
                maxlength:messages.max_30_chars,
                   
            },
                region: {
                   pattern:messages.only_character,    
                   required:messages.req_this_field, 
                   maxlength:messages.max_30_chars,
                   minlength:messages.least_3_chars, 
            },
                building_no: {
                  required:messages.req_this_field, 
                   maxlength:messages.max_30_chars,
            },
                floor_no: {
                   required:messages.req_this_field, 
                   maxlength:messages.max_30_chars,
            },
                street_addr: {
                  required:messages.req_this_field, 
                   maxlength:messages.max_30_chars, 
                   minlength:messages.least_3_chars, 

            },
                postal_code: {
                   digits:messages.only_number,
                   maxlength:messages.max_20_chars, 
                   minlength:messages.least_6_chars,
            },
                post_box: {
                   digits:messages.only_number,
                   maxlength:messages.max_20_chars, 
                   minlength:messages.least_6_chars, 
            },
            country_code :{
            required:messages.req_this_field,
            },
            addr_phone_no: {

            digits:messages.only_number,
            maxlength:messages.max_15_chars, 
                   
            },
            tele_country_code :{
                 required:messages.req_this_field,
            },
            addr_mobile_no: {
                required:messages.req_this_field, 
                maxlength:messages.max_15_chars, 
                minlength:messages.least_10_chars, 
            },

            fax_country_code :{
                 required:messages.req_this_field,
            },
            addr_fax_no: {
                 digits:messages.only_number,
                 maxlength:messages.max_15_chars, 
                 minlength:messages.least_6_chars, 
            },
            addr_email:{
              pattern: messages.invalid_email,
              maxlength: messages.email_max_length,
            },
            }

            });

  







        
      
    });

} catch (e) {
    if (typeof console !== 'undefined') {
        console.log(e);
    }
}


 
