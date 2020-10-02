/* global messages, message */
try {
    jQuery(document).ready(function ($) {

        $('#prof_status').change(function(){
          var selectedVal=$('#prof_status').val();
             // console.log(selectedVal);
          if(selectedVal == 6){

             $('#retried_div').show();
             $('#business_self_employee').hide(); 
             $('#other_status').hide();
             $('#business_owner').hide();

          } else  if(selectedVal == '1' || selectedVal == '4') {
console.log("ddddd");
            $('#retried_div').hide();
            $('#other_status').hide();
            $('#business_owner').hide();
            $('#business_self_employee').show(); 
            $('#dateofemployment').show(); 
            

          
           } else if(selectedVal == '8') {
                      $('#other_status').show();
                      $('#retried_div').hide();
                      $('#business_owner').hide();
                      $('#business_self_employee').hide(); 
           } else if(selectedVal == '3') {
                 $('#retried_div').hide();
                 $('#other_status').hide();
                 $('#business_self_employee').hide(); 
                 $('#business_owner').show();

           } 
           
            
            else {
                    $('#retried_div').hide();
                    $('#business_self_employee').hide(); 
                    $('#other_status').hide();
               }
         
        });
        //loading time 
          var pro_status=$('#prof_status').val();
        if(pro_status == '6') {
             $('#retried_div').show(); 
               $('#business_self_employee').hide(); 
               $('#other_status').hide();

        } else if (pro_status == '1' || pro_status == '4' ){

            $('#retried_div').hide();
            $('#other_status').hide();
            $('#business_self_employee').show(); 
        } else if(pro_status == '8') {
          $('#other_status').show();
        } else if(pro_status == '3') {
           
              //$('#business_self_employee').hide(); 
             // $('#other_status').hide();
              $('#business_owner').show();
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
        
        $.validator.addMethod('enter_amount', function (value) { 

            var prof=$("#prof_status").val();
            if(prof==6){
               
                 return Number(value) > 0;
            } 
            return true;

            
        },messages.enter_invalid_number);

       
        
 /*       $.validator.addMethod('req_other_prof_status', function (value) { 
           var prof=$("#prof_status").val();
           if(prof=='8' && value ==''){
               return false;
           }
           return true;
        },messages.req_this_field);*/
        
        $.validator.addMethod('req_prof_detail', function (value) { 
           var prof=$("#prof_status").val();
           if((prof=='1' || prof=='3'||prof=='4') && value ==''){
               return false;
           }
           return true;
        },messages.req_this_field);
        
        $.validator.addMethod('req_employment', function (value) { 
           var prof=$("#prof_status").val();
          
           if((prof=='1' ||prof=='4'||prof=='6') && value ==''){
               return false;
           }
           return true;
        },messages.req_this_field);
        
      
        $.validator.addMethod('Finformvalidate', function (value) { 
           var prof=$('#prof_status').val();
          
           if( prof=='6' && value ==''){
               return false;
           }
           return true;
        },messages.req_this_field);

          $.validator.addMethod('required_buss_emp_unemp', function (value) { 
           var prof=$('#prof_status').val();
          
           if( (prof=='1' || prof=='4')&& value ==''){
               return false;
           }
           return true;
        },messages.req_this_field);

          $.validator.addMethod('required_bussiness', function (value) { 
           var prof=$('#prof_status').val();
          
           if(prof=='3' && value ==''){
               return false;
           }
           return true;
        },messages.req_this_field);    


          $.validator.addMethod('required_other', function (value) { 
           var prof=$('#prof_status').val();
          
           if( prof== '8' && value ==''){
               return false;
           }
           return true;
        },messages.req_this_field);


        $('#professionalInformationForm').validate({
	   ignore: [], 
	   rules: {
    	        prof_status: {
    	           required: true,
    	        },
                
                prof_detail:{
                    Finformvalidate : true,
                   
                },
                position_title:{
                    Finformvalidate : true,
                 
                },
                date_retirement:{
                    Finformvalidate : true,
                    
                },
                last_monthly_salary:{
                    Finformvalidate : true,
                    enter_amount : true,
                },
                prof_occupation :{
                  required_buss_emp_unemp : true,
                },
                position_job_title :{
                  required_buss_emp_unemp : true,
                },
                date_employment :{
                  required_buss_emp_unemp : true,         
               },
               other_prof_status :{
                   required_other : true,         
               },
               additional_activity:{
                 pattern:"^[a-z A-Z\s]+$",
                 maxlength:60,
               },
               prof_occupation_business :{
                     required_bussiness : true,
               },
               position_job_title_business :{
                    required_bussiness : true,
               },
	    },
	    messages:{
	        prof_status: {              
	          required:messages.req_this_field,         
	        },
            prof_detail: {              
              required:messages.req_this_field,         
            },
            position_title: {              
              required:messages.req_this_field,         
            },
            date_retirement: {              
              required:messages.req_this_field,         
            },
            last_monthly_salary: {              
              required:messages.req_this_field,         
            },
            prof_occupation :{
                  required:messages.req_this_field,         
            },
            position_job_title :{
              required:messages.req_this_field,         
            },
            date_employment :{
              required:messages.req_this_field,         
            },
            additional_activity :{
                pattern:messages.only_character,
                maxlength:messages.max_60_chars,
            } , 
             prof_occupation_business :{
              // required:messages.req_this_field,
            },
            position_job_title_business :{
                //required:messages.req_this_field,
            },
	    }
		  
	});
    });

} catch (e) {
    if (typeof console !== 'undefined') {
        console.log(e);
    }
}
