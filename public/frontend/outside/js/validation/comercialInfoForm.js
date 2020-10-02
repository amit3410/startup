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
        
        //validate phone no
        $.validator.addMethod('invalid_number',
        function (value) { 
                 return Number(value) > 0;
             }, messages.enter_invalid_number

        );
        
        $.validator.addMethod('alpha_num', function (value) { 
            return /^[A-Z a-z]+$/.test(value); 
        },messages.only_character);

        $('#commercialInformationForm').validate({
	   ignore: [], 
	   rules: {
    	        comm_name: {
    	           required: true,
                    maxlength:60,
                     pattern:"^[a-z A-Z 0-9 .\s]+$",
    	        },
                date_of_establish:{
                    required:true,
                },
                country_establish_id:{
                    required:true,
                },
                comm_reg_no:{
                    required:true,
                    pattern:"^[a-z A-Z 0-9\s]+$",
                    maxlength:30,
                },
                comm_reg_place:{
                    pattern:"^[a-z A-Z\s]+$",
                    maxlength:30,
                },
                comm_country_id:{
                    required:true,
                },
                country_activity:{
                    required:true,
                },
                syndicate_no:{
                    maxlength:30,
                    digits:true,
                },
                taxation_no:{
                    invalid_number:true,
                     maxlength:30,
                    required:true,
                },
                taxation_id:{
                    invalid_number:true,
                     maxlength:30,
                    required:true,
                },
                annual_turnover:{
                    invalid_number:true,
                     maxlength:30,
                    required:true,
                },
                main_suppliers:{
                   pattern:"^[a-z A-Z\s]+$",
                   maxlength:60,
                },
                main_clients:{
                   pattern:"^[a-z A-Z\s]+$",
                   maxlength:60,
                },
                authorized_signatory:{
                    alpha_num:true,
                    maxlength:60,
                    required:true,
                },
                buss_country_id:{
                    required:true,
                },
                buss_city_id:{
                    alpha_num:true,
                     maxlength:30,
                    required:true,
                },
                buss_region:{
                    required:true,
                    alpha_num:true,
                    maxlength:30,
                },
                buss_building:{
                   pattern: /^[a-zA-Z0-9\s,-]*$/,
                    maxlength:30,
                    required:true,
                },
                buss_floor:{
                    pattern: /^[a-zA-Z0-9\s,-]*$/,
                    maxlength:30,
                    required:true,
                },
                buss_street:{
                    pattern: /^[a-zA-Z0-9\s,-]*$/,
                    maxlength:30,
                    required:true,
                },
               
                buss_email:{
                    required:true,
                    pattern:/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/,
                    maxlength:60,
                },
                buss_telephone_no:{
                    minlength:8,
                    maxlength:20,
                    required:true,
                    invalid_number:true,
                },
                country_code_phone :{
                    required: true,
                },
                country_code :{
                    required: true,
                },
                
                buss_mobile_no:{
                    minlength:8,
                    maxlength:20,
                    required:true,
                    invalid_number:true,
                },    
                buss_fax_no:{
                  digits:true,

                },       
        },
	    messages:{
	        
                comm_name: {
                required:messages.req_this_field, 
                maxlength:messages.max_60_chars,
                pattern:messages.alpha_num,
              
	             },
                date_of_establish:{
                    required:messages.req_this_field, 
                },
                country_establish_id:{
                    required:messages.req_this_field, 
                },
                comm_reg_no:{
                   maxlength:messages.max_30_chars,
                   required:messages.req_this_field,
                },
                comm_reg_place:{
                    pattern:messages.only_character,
                     maxlength:messages.max_30_chars,
                },
                comm_country_id:{
                    required:messages.req_this_field, 
                },
                country_activity:{
                    required:messages.req_this_field, 
                },
                syndicate_no:{
                    maxlength:messages.max_30_chars,
                    required:messages.req_this_field, 
                },
                taxation_no:{
                    maxlength:messages.max_30_chars,
                    required:messages.req_this_field, 
                },
                taxation_id:{
                    maxlength:messages.max_30_chars,
                    required:messages.req_this_field, 
                },
                annual_turnover:{
                    maxlength:messages.max_30_chars,
                    required:messages.req_this_field, 
                },
                main_suppliers:{
                     maxlength:messages.max_60_chars,
                     pattern:messages.only_character,
                },
                main_clients:{
                     maxlength:messages.max_60_chars,
                     pattern:messages.only_character,
                },
                authorized_signatory:{
                     maxlength:messages.max_60_chars,
                    required:messages.req_this_field, 
                },
                buss_country_id:{
                    required:messages.req_this_field, 
                },
                buss_city_id:{
                    maxlength:messages.max_30_chars,
                    required:messages.req_this_field, 
                },
                buss_region:{
                    maxlength:messages.max_30_chars,
                    required:messages.req_this_field, 
                },
                buss_building:{
                     maxlength:messages.max_30_chars,
                    pattern:messages.alpha_num,
                    required:messages.req_this_field, 
                },
                buss_floor:{
                     maxlength:messages.max_30_chars,
                     pattern:messages.alpha_num,
                    required:messages.req_this_field, 
                },
                buss_street:{
                     maxlength:messages.max_30_chars,
                     pattern:messages.alpha_num,
                    required:messages.req_this_field, 
                },
                
               
                buss_email:{
                    maxlength:messages.max_60_chars,
                    required:messages.req_this_field,
                    pattern:messages.invalid_email, 
                },
                buss_telephone_no:{
                    minlength:messages.min_8_chars,
                    required:messages.req_this_field, 
                },
                
                 country_code :{
                    required:messages.req_this_field, 
                },
                
                
                 country_code_phone :{
                    required:messages.req_this_field, 
                },
                buss_mobile_no:{
                    required:messages.req_this_field, 
                    minlength:messages.min_8_chars,
                }, 
                buss_fax_no:{
                    digits:messages.only_number,
                },
               
	    }
		  
	});
    });

} catch (e) {
    if (typeof console !== 'undefined') {
        console.log(e);
    }
}
