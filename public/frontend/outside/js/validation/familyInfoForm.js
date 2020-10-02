/* global messages, message */
try {
    jQuery(document).ready(function ($) {




        $('#familyInformationForm').validate({
       ignore: [], 
       rules: {
            
             spouse_f_name : {
               pattern :"^[A-Z a-z]+$",
               maxlength:60,
            },
            spouse_m_name : {
               pattern :"^[A-Z a-z]+$",
               maxlength:60,
            },

             spouse_profession : {
               pattern :"^[A-Z a-z]+$",
               maxlength:60,
            },
            spouse_employer : {
               pattern :"^[A-Z a-z]+$",
               maxlength:60,
            },
            'child_name[]':{
                    pattern:"^[A-Z a-z]+$",
            },
 
        },
        messages:{
            
           
            spouse_f_name: {
                maxlength:messages.max_60_chars,
                pattern:messages.only_character,
            },

            spouse_m_name: {
                maxlength:messages.max_60_chars,
                pattern:messages.only_character,
                
            },

            spouse_profession: {
                maxlength:messages.max_60_chars,
                pattern:messages.only_character,
                
            },
            spouse_employer: {
               maxlength:messages.max_60_chars,
                pattern:messages.only_character,
            },
            'child_name[]':{
               pattern:messages.only_character,
            },
        }
          
    });


        $('.datepicker').keydown(function (e) {
            e.preventDefault();
            return false;
        });
        
        $('.datepicker').on('paste',function (e) {
            e.preventDefault();
            return false;
        });
        
        
        $.validator.addMethod('alphnum_hyp_uscore_space', function (value) { 
            return /^[A-Za-z0-9\s_-]+$/.test(value); 
        },messages.alphnum_hyp_uscore_space);
        
        $.validator.addMethod('alpha_num', function (value) { 
            return /^[A-Za-z0-9]+$/.test(value); 
        },messages.alpha_num);
        
        $.validator.addMethod('req_spouse_profession', function (value) { 
            var is_professional =   $("#is_professional_status").val();
            if(is_professional=='1' ||is_professional=='3'){
                if(value==''||value==null || value==undefined){
                    return false;
                }
            }
            
            return true;
        },messages.req_this_field);
        
        $.validator.addMethod('req_spouse_employer', function (value) { 
            var is_professional =   $("#is_professional_status").val();
            if(is_professional=='1'){
                if(value==''||value==null || value==undefined){
                    return false;
                }
            }
            
            return true;
        },messages.req_this_field);

        
    });

} catch (e) {
    if (typeof console !== 'undefined') {
        console.log(e);
    }
}
