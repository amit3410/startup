/* global messages, message */
try {
    jQuery(document).ready(function ($) {

    //Do you hold or ever


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
    
    $('#do_you_hold').change(function(){
   
    //console.log($('#do_you_hold').val());
    if($('#do_you_hold').val()==1){
    $('#do_you_hold_hide_show').toggle();
    } else {
    $('#do_you_hold_hide_show').hide();
    }

    });

    if($('#do_you_hold').val() == 1){
   
    $('#do_you_hold_hide_show').toggle();
    }


    //directly related start

    $('#are_you_directly').change(function(){
   
   
    if($('#are_you_directly').val()==1){
    $('#are_you_related_directly_hide_show').toggle();
    } else {
    $('#are_you_related_directly_hide_show').hide();
    }

    });
    if($('#are_you_directly').val() == 1){
   
    $('#are_you_related_directly_hide_show').toggle();
    }

    //directly related end


    $('#educational_level').on('change',function(){

    let edu_level=$('#educational_level').val();
           // console.log(edu_level);
    if(edu_level=='9'){
    $('#education_other_div').toggle();
    } else {
    $('#education_other_div').hide();
    }
       
    });
   


    let edit_education_other=$('#educational_level').val();
    console.log(edit_education_other);
    if(edit_education_other=='9') {
    $('#education_other_div').toggle();
    }

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

       

    $('#personalInformationForm').validate({
        ignore: [],
        rules: {
          title :{
          required: true,
          },
        f_name: {
          required: true,
          lettersonly:true,
          minlength:3,
          maxlength:60,
        },
        m_name: {
           lettersonly:true,
         
           maxlength:60,
        },
        l_name: {
           required: true,
           lettersonly:true,
           minlength:3,
           maxlength:60,
        },
        gender: {
           required: true,
        },
        date_of_birth: {
           validDOB : true, 
           required: true,

        },
        father_name: {
           required: true,
            pattern :"^[A-Z a-z]+$",
            minlength:3,
           maxlength:60,
        },
        mother_f_name: {
           required: true,
          lettersonly: true,
        },
        mother_m_name: {
            required: true,
            lettersonly: true,
           
            maxlength:60,
        },
        birth_country_id: {
           required: true,
        },
        birth_city_id: {
            required: true,
            pattern:"^[A-Z a-z]+$",
            minlength:3,
            maxlength:60,
        },
        reg_no: {
         
           digits:true,
           minlength:3,
           maxlength:20,
        },
        reg_place: {
         pattern:"^[A-Z a-z]+$",
         minlength:3,
         maxlength:60,
        },
        f_nationality_id: {
           required: true,
        },

         sec_nationality_id: {
           required: true,
        },
        residence_status: {
           required: true,
        },
        family_status: {
           required: true,
        },
        educational_level: {
           required: true,
        },
        education_other: {

        pattern:"^[a-z A-Z\s]+$",
        required: function(element){
                           
                return $("#educational_level option:selected").text() == "Other";
                }
            },

            do_you_hold: {

            required : true,
            },
            political_position_dec : {
            required: function(element){
                           
                return $("#do_you_hold option:selected").text() == "Yes";
                }
            },
            'political_position_hold[]' : {
            required: function(element){
                return $("#do_you_hold option:selected").text() == "Yes";
                }
            },

            are_you_directly: {

            required : true,
            },
            related_political_position_dec : {
            required: function(element){
                           
                return $("#are_you_directly option:selected").text() == "Yes";
                }
            },
            'related_political_position[]' : {
            required: function(element){
                           
                return $("#are_you_directly option:selected").text() == "Yes";
                }
            },

            is_residency_card: {
           required: true,
       },
                'document_type_id[]': {
           required: true,
       },
     
                'social_media_id[]': {
           required: true,
       },
                'social_media_link[]': {
           url: true,
                    required:true,
       },
                'document_number0': {
                    required: true,
                    maxlength:20,
                    pattern:"^[a-z A-Z 0-9]+$",
       },
                'document_number1': {
                    required: true,
                    maxlength:20,
                    pattern:"^[a-z A-Z 0-9]+$",
       },
                'document_number2': {
                 
                    maxlength:20,
                    pattern:"^[a-z A-Z 0-9]+$",
       },
                'document_number3': {

                  maxlength:20,
                  pattern:"^[a-z A-Z 0-9]+$",
       },
               
                'issuance_date0': {
                    required: true,
         
       },
               
                'issuance_date1': {
                    required: true,
         
       },
               
                'issuance_date2': {
       },
              
                'expiry_date0': {
                    required: true,
            },
               
                'expiry_date1': {
                    required: true,
         
       },
               
                'expiry_date2': {
       },
               
       'social_other[0]': {

        pattern:"^[a-z A-Z\s]+$",
        maxlength:20,
        required: function(element){
                           
                return $(".social option:selected").text() == "Other";
                }
            },        
               
               
               
       
 
   },
   messages:{
   
       f_name: {              
         required:messages.req_this_field,
         lettersonly:messages.invalid_name,  
         minlength:messages.least_3_chars,
         maxlength:messages.max_60_chars,          
       },
               m_name: {              
          lettersonly:messages.invalid_name,  
         
          maxlength:messages.max_60_chars,        
       },
                l_name: {              
          required:messages.req_this_field,
          lettersonly:messages.invalid_name,  
          minlength:messages.least_3_chars,
          maxlength:messages.max_60_chars,          
       },
        gender: {
           required:messages.req_this_field,
       },
        date_of_birth: {
            validDOB: messages.invalid_age,
            required: messages.req_this_field,
        },
        father_name: {
            required:messages.req_this_field,
            pattern:messages.invalid_name,    
            minlength:messages.least_3_chars,
            maxlength:messages.max_60_chars,
        },
        mother_f_name: {
            required: messages.req_this_field,
            lettersonly: messages.invalid_name,
            minlength:messages.least_3_chars,
            maxlength:messages.max_60_chars,  
        },
        mother_m_name: {
            required: messages.req_this_field,
            lettersonly: messages.invalid_name,  
            
            maxlength:messages.max_60_chars,  
        },
        birth_country_id: {
            required: messages.req_this_field,
        },
        birth_city_id: {
            required: messages.req_this_field,
            pattern:messages.invalid_name,  
            minlength:messages.least_3_chars,
            maxlength:messages.max_60_chars,
        },
        reg_no: {
         
           digis:messages.only_number,  
           minlength:messages.least_3_chars,
           maxlength:messages.max_20_chars,
        },
        reg_place: {
           
           pattern:messages.invalid_name, 
           minlength:messages.least_3_chars,
           maxlength:messages.max_60_chars,
        },
        f_nationality_id: {
           required: messages.req_this_field,
        },
       
        sec_nationality_id: {
           required: messages.req_this_field,
        },         
      residence_status: {
           required: messages.req_this_field,
       },
                family_status: {
           required: messages.req_this_field,
       },
                educational_level: {
           required: messages.req_this_field,
       },

       education_other :{
            pattern:messages.only_character,  
            required:messages.req_this_field,
            },

            do_you_hold :{
            required:messages.req_this_field,
            },
            political_position_dec :{
            required:messages.req_this_field,
            },

            'political_position_hold[]' : {
            required:messages.req_this_field,
            },

             are_you_directly :{
            required:messages.req_this_field,
            },
            related_political_position_dec :{
            required:messages.req_this_field,
            },

            'related_political_position[]' : {
            required:messages.req_this_field,
            },


                is_residency_card: {
           required:messages.req_this_field,
       },
                'document_type_id[]': {
           required: messages.req_this_field,
       },
        'document_number[]': {
           required: messages.req_this_field,
       },
                'social_media_id[]': {
           required: messages.req_this_field,
       },
                'social_media_link[]': {
                   required: messages.req_this_field,
           url: messages.enter_valid_url,
       },
                'document_number0': {
                   maxlength:messages.max_20_chars,
                     required: messages.req_this_field,
                      pattern:messages.alpha_num,
       },
                'document_number1': {
                   maxlength:messages.max_20_chars,
                     required: messages.req_this_field,
                    pattern:messages.alpha_num,
                },
                'document_number2': {
                   maxlength:messages.max_20_chars,
                   pattern:messages.alpha_num,
       },
                'document_number3': {
                  maxlength:messages.max_20_chars, 
                  pattern:messages.alpha_num,
       },
               
                 'issuance_date0': {
                     required: messages.req_this_field,
         
       },
               
                 'issuance_date1': {
                     required: messages.req_this_field,
         
       },
               
                 'issuance_date2': {
                     required: messages.req_this_field,
         
       },
               
                 'issuance_date3': {
                     required: messages.req_this_field,
         
       },
               
               
                 'expiry_date0': {
                     required: messages.req_this_field,
         
       },
               
                 'expiry_date1': {
                     required: messages.req_this_field,
         
       },
               
                 'expiry_date2': {
                     required: messages.req_this_field,
         
       },
               
                 'expiry_date3': {
                     required: messages.req_this_field,
         
       },
       'social_other[]':{
          required: messages.req_this_field,
          pattern:messages.only_character,
          maxlength:messages.max_20_chars,
       },
       
   }
 
});

    
    });
    


    

} catch (e) {
    if (typeof console !== 'undefined') {
        console.log(e);
    }

   


}   