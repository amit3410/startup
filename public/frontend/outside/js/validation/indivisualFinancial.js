/* global messages, message */
try {
    jQuery(document).ready(function ($) {


    	$('#us_fetch_regulation').change(function(){
    		let subjectusVal= $('#us_fetch_regulation').val();
    		
    		if(subjectusVal == '1'){
    			$('#subject_to_us_No').hide();
    			$('#citizenship_abandonment').hide();
    			$('#subject_to_us_yes').show();
    		} else if(subjectusVal == '0') {

                        $('#subject_to_us_yes').hide();
                        $('#citizenship_abandonment').hide();
                        $('#subject_to_us_No').show();
                } else if(subjectusVal == '') {
                    
                    $('#subject_to_us_No').hide();
    			$('#citizenship_abandonment').hide();
    			$('#subject_to_us_yes').hide();
                }
    	});

        var subjectusVal = "";
    	subjectusVal= $('#us_fetch_regulation').val();
        
       
    	if(subjectusVal == '1'){
    			
    			$('#subject_to_us_No').hide();

    			$('#subject_to_us_yes').show();
    		} else if(subjectusVal == '0' ){
                    
                    $('#subject_to_us_No').show();
                    $('#subject_to_us_yes').hide();
                   var citizenshipVal= $('#is_abandoned').val();
                   if(citizenshipVal == 1){
                   $('#citizenship_abandonment').show();
                   } else {
                    $('#citizenship_abandonment').hide();
                    }
            } else if(subjectusVal == ""){
                 $('#subject_to_us_No').hide();
                 $('#subject_to_us_yes').hide();
                
            } 

           /* else {
                    
                     //$('#subject_to_us_No').show();

                }*/


    	//citizenship abandonment change function
    	$('#is_abandoned').change(function(){

    		var citizenshipVal= $('#is_abandoned').val();
    		
    		if(citizenshipVal == 1 ){
    			
    			$('#citizenship_abandonment').show();
    		} 
            if(citizenshipVal == 0){
                
                $('#citizenship_abandonment').hide();
            } 


    	});

    	var citizenshipVal= $('#is_abandoned').val();
       // console.log(citizenshipVal);
		if(citizenshipVal == 1 && subjectusVal == 0){
    			
    		$('#citizenship_abandonment').show();
    	} else {
					 $('#citizenship_abandonment').hide();
    			}


    	/*$.validator.addMethod('us_tin_code',
        function (value) { 
                 return Number(value) > 0;
             }, messages.enter_invalid_number

        );*/
        //click on not applicable checkbox
        $('#not_applicable_tincode').click(function(){

        	    let checkedVal= $('#not_applicable_tincode').val();
        	    console.log(checkedVal);

               

        	    if(checkedVal == 1) {
        	    	$('#tin_number').val('');
                    $(this).attr('value', '0');
        	    	$('#tin_number').prop('disabled',false);
        	    }
        	     if(checkedVal == 0) {
        	    	$('#tin_number').val('');
                    $(this).attr('value', '1');
        	    	$('#tin_number').prop('disabled', true);
        	    }
        	    
        });
        //loading  time checkbox
        if($('#not_applicable_tincode').is(":checked")){

                $('#tin_number').prop('disabled',true);
            }
           else {
           		 $('#tin_number').prop('disabled',false);
            }

        $('#financialInformationForm').validate({
		   ignore: [], 
		   rules: {

               
		        us_fetch_regulation: {
		           required: true,
		          
		        },
		        please_specify : {
		        	required: function(element){
                           
                     return $('#us_fetch_regulation').val() == "1";
                    }

		        },

		        tin_code :{
		        		digits:true,
		        		//us_tin_code:true,
		        	    required: function(element){
                        return $('#us_fetch_regulation').val() == "1";
                    }
		        },

		        tin_country_name :{
		        		
		        	    required: function(element){
                        return $('#us_fetch_regulation').val() == "0";
                    }
		        },
		         tin_number :{
		        		digits:true,
		        	    required: function(element){
                        return $('#us_fetch_regulation').val() == "0";
                    }
		        },
		        is_abandoned :{
		        		
		        	    required: function(element){
                        return $('#us_fetch_regulation').val() == "0";
                    }
		        },
		        date_of_abandonment : {
		        		required: function(element){
                        return $('#is_abandoned').val() == "1";
                    }
		        },
		        abandonment_reason :{
		        	pattern:"^[a-z A-Z\s]+$",
		        	required: function(element){
                        return $('#is_abandoned').val() == "1";
                    }
		        },


			},
		    messages:{

               
                us_fetch_regulation: {
                   required: true,
                  
                },

		        us_fetch_regulation: {              
		          required:messages.req_this_field, 
		        },
		        please_specify :{
					required:messages.req_this_field, 
		        },
		        tin_code :{
		        	digits:messages.only_number,
		        	us_tin_code:messages.enter_invalid_number,
					required:messages.req_this_field,
		        },
		        tin_country_name :{
					required:messages.req_this_field,
		        },
		        tin_number :{
		        	digits:messages.only_number,
		        	required:messages.req_this_field,
		        },
		        is_abandoned : {
		        	required:messages.req_this_field,
		        },
		        date_of_abandonment :{
		           required:messages.req_this_field,	
		        },
		         abandonment_reason :{
		         	pattern:messages.only_character,
		           required:messages.req_this_field,	
		        }

		    }
			  
		});
    });

} catch (e) {
    if (typeof console !== 'undefined') {
        console.log(e);
    }
}
