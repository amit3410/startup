/* global messages, message */

try {

  
    jQuery(document).ready(function ($) {
        
      $('#personalWcapiForm').validate({
       ignore: [], 
       rules: {
            
             f_name : {
               required: true,
            },
            
            date_of_birth : {
               required: true,
            },
            birth_country_id : {
               required: true,
            },
            passport_issue_state : {
               required: true,
            },
            
            passport_nationality : {
               required: true,
            },
            passportNumber : {
               required: true,
            },
            date_of_expiry : {
               required: true,
            },
            
        },
        messages:{
            
           
            f_name: {
                required: messages.req_this_field, 
            },
            
             date_of_birth: {
                required: messages.req_this_field, 
            },
            birth_country_id: {
                required: messages.req_this_field, 
            },
            passport_issue_state: {
                required: messages.req_this_field, 
            },
            passport_nationality: {
                required: messages.req_this_field, 
            },
            passportNumber: {
                required: messages.req_this_field, 
            },
            date_of_expiry: {
                required: messages.req_this_field, 
            },
            
        }
          
    });
    
    
    
     $('#assesmentForm').validate({
       ignore: [], 
       rules: {
            
             national_identity : {
               required: true,
            },
            
            worldcheck_match : {
               required: true,
            },
            passport_verification : {
               required: true,
            },
             is_peep : {
               required: true,
            },
             is_socialmedia : {
               required: true,
            },
        },
        messages:{
            
           
            national_identity: {
                required: messages.req_this_field, 
            },
            
             worldcheck_match: {
                required: messages.req_this_field, 
            },
            passport_verification: {
                required: messages.req_this_field, 
            },
            is_peep: {
                required: messages.req_this_field, 
            },
            is_socialmedia: {
                required: messages.req_this_field, 
            },
        }
          
    });
    
    
    
    
    
    
    $('#corpWcapiForm').validate({
       ignore: [], 
       rules: {
            
             corp_name : {
               required: true,
            },
            
            country_id : {
               required: true,
            },
        },
        messages:{
            
           
            corp_name: {
                required: messages.req_this_field, 
            },
            
            country_id: {
                required: messages.req_this_field, 
            },
        }
          
    });
    
    
    // Report Section Validation for individual Here
    
     $('#assesmentForm').validate({
       ignore: [], 
       rules: {
            
             national_identity : {
               required: true,
            },
            worldcheck_match : {
               required: true,
            },
            passport_verification : {
               required: true,
            },
            is_peep : {
               required: true,
            },
            is_socialmedia : {
               required: true,
            },
            
        },
        messages:{
            
           
            national_identity: {
                required: messages.req_this_field, 
            },
            worldcheck_match: {
                required: messages.req_this_field, 
            },
            passport_verification: {
                required: messages.req_this_field, 
            },
            is_peep: {
                required: messages.req_this_field, 
            },
            is_socialmedia: {
                required: messages.req_this_field, 
            },
            
           
        }
          
    });
    
    
    
    // Report Section Validation for Coroparation Here
    
     $('#assesmentFormcorp').validate({
       ignore: [], 
       rules: {
            
             national_identity : {
               required: true,
            },
            worldcheck_match : {
               required: true,
            },
            passport_verification : {
               required: true,
            },
            is_peep : {
               required: true,
            },
            is_socialmedia : {
               required: true,
            },
            
        },
        messages:{
            
           
            national_identity: {
                required: messages.req_this_field, 
            },
            worldcheck_match: {
                required: messages.req_this_field, 
            },
            passport_verification: {
                required: messages.req_this_field, 
            },
            is_peep: {
                required: messages.req_this_field, 
            },
            is_socialmedia: {
                required: messages.req_this_field, 
            },
            
           
        }
          
    });
    
        $('#report_type').change(function(){ 
            var reportType =  $("#report_type").val();
            if(reportType == 'JuriDex') {
                 $("#addressto").show();
                  $('#indi_jur_addressto').rules("add", {
                        required: true
                    });
             } else {
                 $("#addressto").hide();
             }
        });
        
        $('#report_type_corp').change(function(){ 
            var reportType =  $("#report_type_corp").val();
            if(reportType == 'JuriDex') {
                 $("#addressto").show();
                  $('#corp_jur_addressto').rules("add", {
                        required: true
                    });
             } else {
                 $("#addressto").hide();
             }
        });
        
         $('#worldcheck_match').change(function(){ 
            var matchtypeType =  $("#worldcheck_match").val();
            if(matchtypeType == '1') {
                 $("#worldcheckStrength").html('(Negative)');
             } else if(matchtypeType == '2'){
                 $("#worldcheckStrength").html('(False)');
             } else if(matchtypeType == '3'){
                 $("#worldcheckStrength").html('(Resolved as Unspecified)');
             } else if(matchtypeType == '4'){
                 $("#worldcheckStrength").html('(Resolved as Possible)');
             } else if(matchtypeType == '5'){
                 $("#worldcheckStrength").html('(Positive)');
             }
        });
        
        
        
        ////////////////////API call ////
    
    
     
       ////////////////////API call End////

    });
} catch (e) {
    if (typeof console !== 'undefined') {
        console.log(e);
    }
}
