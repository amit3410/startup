<script type='text/javascript'>
	
	$(document).ready(function(){

	$('.fileupload').click(function(){
		alert();
	})


	})
		//From company details page select other

	$('#status').on('change',function(){
    	 	
    	 	if($("#status option:selected").text()=='other'){
    	 		$('#company_status_other').toggle();
    	 	} else {
    	 		$('#company_status_other').hide();
    	 	}
    	    
    });
		
    	if($("#status option:selected").text()=='other') {
    	 	$('#company_status_other').toggle();
    	}

    

    $('.number').keypress(function(event) {

     if(event.which == 8 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46) 
          return true;

     else if((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57))
          event.preventDefault();

    });

    //continue zero not allowed
     //validate phone no
       
      

	$('#companydetails').validate({
	   ignore: [], 
	   rules: {

	   	    companyname: {
	           required: true,
	           pattern:"^[a-z A-Z .\s]+$",
	           minlength:2,
	           maxlength:50,
	        },

	        customername: {
	           required: true,
	           pattern:"^[a-z A-Z\s]+$",
	           minlength:2,
	           maxlength:50,
	        },
	      	regisno: {
	           required: true,
	           pattern:"^[a-z A-Z 0-9\s]+$",
	           maxlength:30,
	          
	        },
	     	regisdate: {
	           required: true,
	        },
	        status: {
	           required: true,
	           
	        },
	        comp_status_other: { 

	        	 pattern:"^[a-z A-Z\s]+$",
	        	required: function(element){
                           
                return $("#status option:selected").text() == "other";
                }
            },
	       
	        naturebusiness: {
	           required: true,
	        },

	        'social_media_id[]': {
	        	required: true,
             },
             'social_media_link[]': {
	        	url:true,
                required: true,
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


	    	companyname: {              
	           required:"{{trans('forms.corp_company_details.client_error.required')}}", 
	           pattern:"{{trans('forms.corp_company_details.client_error.pattern')}}",   
	           minlength:"{{trans('forms.corp_company_details.client_error.customername_minlength')}}",
	           maxlength:"{{trans('forms.corp_company_details.client_error.customername_maxlength')}}",          
	        },
	        customername: {              
	           required:"{{trans('forms.corp_company_details.client_error.required')}}", 
	           pattern:"{{trans('forms.corp_company_details.client_error.pattern')}}",   
	           minlength:"{{trans('forms.corp_company_details.client_error.customername_minlength')}}",
	           maxlength:"{{trans('forms.corp_company_details.client_error.customername_maxlength')}}",          
	        },

	        regisno: {              
	           required:"{{trans('forms.corp_company_details.client_error.required')}}", 
	           digits:"{{trans('forms.corp_company_details.client_error.regisno_alphanumeric')}}", 
	           minlength:"{{trans('forms.corp_company_details.client_error.regisno_minlength')}}",  
	           maxlength:"{{trans('forms.corp_company_details.client_error.regisno_maxlength')}}",          
	        },
	        regisdate: {              
	           required:"{{trans('forms.corp_company_details.client_error.required')}}", 
      	        },
	        status: {              
	           required:"{{trans('forms.corp_company_details.client_error.required')}}", 
            },

            comp_status_other :{ 
            	pattern:"{{trans('forms.corp_company_details.client_error.pattern')}}",   
            	required:"{{trans('forms.corp_company_details.client_error.required')}}",
            },
	        naturebusiness: {              
	          required:"{{trans('forms.corp_company_details.client_error.required')}}",         
	        },
	         'social_other[]':{
	          required: messages.req_this_field,
	          pattern:messages.only_character,
	          maxlength:messages.max_20_chars,
	       },
	       	'social_media_id[]': {
                required: messages.req_this_field,
            },
            'social_media_link[]': {
                required: messages.req_this_field,
                url: messages.enter_valid_url,
            },
	     
	    }
		  
	});


	$('#addressdetails').validate({
	   ignore: [], 
	   rules: {
	        country: {
	           required: true,
			},
	      	city: {
	           required: true,
	            pattern:"^[a-z A-Z\s]+$",
	            maxlength:50,
	         },
	     	region: {
	           required: true,
	           pattern:"^[a-z A-Z\s]+$",
	           maxlength:30,
	        },
	        building: {
	           required: true,
	           pattern: /^[a-zA-Z0-9\s,-]*$/,
	           maxlength:50
	        },

	        floor: {
	        	
	        	maxlength:20,
	        	pattern: /^[a-zA-Z0-9\s,-]*$/,
	        },
	        street: {
	           required: true,
	           pattern:/^[a-zA-Z0-9\s,-]*$/,
	           maxlength:20,
	        },
	        postalcode: {
	        	
	        	digits:true,
	        	maxlength:10,
	        	//invalid_number: true,
	        },
	        pobox: {
	        	maxlength:10,
	        },
	        email: {
	        	required: true,
	        	pattern:/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/,
	        	maxlength:50,
	        },
	        telephone: {
	        	required: true,
	        	digits:true,
	        	minlength:8,
	        	maxlength:20,
	        	invalid_number: true,
	        	
	        },
	        areacode :{
	        	required :true,
	        },

	        mobile: {
	        	required: true,
	        	minlength:8,
	        	maxlength:20,
	        	digits:true,
	        	invalid_number: true,
	        }, 
	        countrycode :{
	        	required :true,
	        },
	        
	        faxno: {
	        	maxlength:20,
	        },
	         corr_country: {              
                 required:true,
	        },
	        corr_city: {              
                required: true,
	            pattern:"^[a-z A-Z\s]+$",
	            maxlength:50,
	        },
	        corr_region: {
	           required: true,
	           pattern:"^[a-z A-Z\s]+$",
	           maxlength:30,
	        },
	        corr_building: {
	           required: true,
	           pattern:/^[a-zA-Z0-9\s,-]*$/,
	           maxlength:50,
	        },
	        corr_floor: {
	        	 pattern:/^[a-zA-Z0-9\s,-]*$/,
	             maxlength:20,
	        },
	        corr_street: {
	           required: true,
	           pattern:/^[a-zA-Z0-9\s,-]*$/,
	           maxlength:20,
	        },
	        corr_postal: {
	        	
	        	digits:true,
	        	maxlength:10,
	        
	        },
	         corr_pobox: {
	        	maxlength:20,
	        },
	        corr_email: {
	        	required: true,
	        	pattern:/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/,
	        	maxlength:50,
	        },
	        corre_areacode :{
	        	required :true,
	        },
	        corr_tele: {
	        	required: true,
	        	digits:true,
	        	minlength:8,
	        	maxlength:20,
	        	invalid_number: true,
	        },
	        corre_countrycode :{
	        	required :true,
	        },
	        corr_mobile: {
	        	required: true,
	        	minlength:8,
	        	maxlength:20,
	        	digits:true,
	        	invalid_number: true,
	        },
	         
	        corr_fax: {
	        	maxlength:20,
	        },

	    },
	    messages:{
	        	country: {              
	           required:"This field is required", 
	        },

	        city: {              
	           required:"This field is required", 
	           pattern:"Please enter valid city name",
	           maxlength:"Please enter maximum 50 characters",

      	    },
	        region: {              
	           required:"This field is required", 
	           pattern:"Please enter only alphabetical characters",
	           maxlength:"Please enter maximum 30 characters",
      	    },
      	    building: {
	           required:"This field is required", 
	           pattern:"Please enter only characters", 
	         //  minlength:"Please enter at least 2 characters",
	           maxlength:"Please enter maximum 50 characters", 
	        },

	        floor: {
	           
	           pattern:"Please enter only alphabetical characters", 
	           maxlength:"Please enter maximum 20 characters", 
	        },


	        street: {
	           required:"This field is required", 
	            pattern:"Please enter only alphabetical characters", 
	            maxlength:"Please enter maximum 20 characters",
	        },
	        postalcode: {
	        	required:"This field is required", 
	            digits:"Please enter valid number", 
	            maxlength:"Please enter maximum 10 characters",
	        },
	        pobox: {
	        	required:"This field is required", 
	        	digits:"Please enter valid number", 
	        	maxlength:"Please enter maximum 20 characters",
	        },
	        email: {
	        	required:"This field is required", 
	        	pattern:"Please enter a valid email address",
	        },
	        telephone: {
	        	required:"This field is required", 
	            minlength:"Please enter minimum 8 number",
	        	maxlength:"Please enter maximum 20 number",
	        	digits:"Please enter valid telephone no",
	        	regrex:"invalid number",
	        },
	        areacode :{
	        	required: "This field is required",
	        },

	        countrycode : {
	        	required:"This field is required", 
	        },
	        mobile: {
	        	required:"This field is required", 
	        	minlength:"Please enter minimum 8 number",
	        	maxlength:"Please enter maximum 20 number",
	        	digits:"Please enter valid mobile no",
	        	
	        },
	        
	        faxno: {
	        	 digits:"Please enter valid number", 
	             maxlength:"Please enter maximum 20 characters",

	        },
	        corr_country: {
	        	required:"This field is required", 	
	        },
	        corr_city: {
	        	required:"This field is required", 
	           pattern:"Please enter valid city name",
	           maxlength:"Please enter maximum 50 characters",
	        },

	        corr_region: {
	           required:"This field is required", 
	           pattern:"Please enterlayouts.app alphabetical only characters",
	           maxlength:"Please enter maximum 30 characters",
	           
	        },
	        corr_building: {
	           required:"This field is required",
	           pattern:"Please enter only characters", 
	           maxlength:"Please enter maximum 50 characters", 
	        },
	        corr_floor: {
	           
	           pattern:"Please enter only alphabetical characters", 
	           maxlength:"Please enter maximum 20 characters", 
	        },
	        corr_street: {
	            required:"This field is required",
	            pattern:"Please enter only alphabetical characters", 
	            maxlength:"Please enter maximum 20 characters",
	        },
	        corr_postal: {
	            required:"This field is required", 
	            digits:"Please enter valid number", 
	            maxlength:"Please enter maximum 10 characters",
	        },
	        corr_pobox: {
	        	required:"This field is required", 
	            digits:"Please enter valid number", 
	            maxlength:"Please enter maximum 20 characters",
	        },
	        corr_email: {
	        	required:"This field is required", 
	        	pattern:"Please enter a valid email address",
	        },
	        corre_areacode :{
	        	required :"This field is required",
	        },
	        corr_tele: {
	        	required:"This field is required", 
	            minlength:"Please enter minimum 8 number",
	        	maxlength:"Please enter maximum 20 number",
	        	digits:"Please enter valid telephone no",
	        },
	        corre_countrycode : {
	         required : "This field is required",
	        },
	        corr_mobile: {
	        	required:"This field is required", 
	        	minlength:"Please enter minimum 8 number",
	        	maxlength:"Please enter maximum 20 number",
	        	digits:"Please enter valid mobile no",
	        },
	        corr_fax: {
	        	digits:"Please enter valid number", 
	            maxlength:"Please enter maximum 20 characters",
	        },
	       
	       
	     
	    }
		  
	});
	
	$('#shareholding_struc').validate({
	   ignore: [], 
	   rules: {
	   		company: {
	   		   required: true,
	   		},
	        "cname[]": {
	           required: true,
	           pattern:"^[a-z A-Z\s]+$",
	           minlength:2,
	           maxlength:50,
	        },
	        passportno: {
	           required: true,
	           alphanumeric:true,
	           minlength:5,
	           maxlength:20,
	        },
	        share_percentage: {
	        	required:true,
	        },
	     	 usd: {
	           required: true,
	           digits:true,
	        },


	    },
	    messages:{
	    	company: {
	    	   required:"This field is required", 
	    	},
	        "cname[]": {              
	           required:"This field is required", 
	           pattern:"Please enter only characters",   
	           minlength:"Please enter at least 2 characters",
	           maxlength:"Please enter maximum 50 characters",          
	        },

	        passportno: {              
	           required:"This field is required", 
	           alphanumeric:"Please enter only alphabetical characters", 
	           maxlength:"Please enter maximum 20 characters",          
	        },
	        share_percentage: {
	          required:"This field is required", 
	        },
	       	usd: {              
	           required:"This field is required", 
	           digits:"Please enter only digits",
	           maxlength:"Please enter maximum 30 digits",  
      	        },

	    }
		  
	});

	$('#financialform').validate({
	    
	   rules: {
	        yearly_usd: {
	          required: true,
	          maxlength:20,
	           
	        },
	        
	        total_debts_usd: {
	        	required: true,
	        	maxlength:20,
	        },
	        total_payable_usd :{
	        	required :true,
	        	maxlength:20,
	        },
	        total_recei_usd: {
	        	required: true,
	        	maxlength:20,
	        },
	        total_salary_usd:{
	        	required:true,
	        	maxlength:20,
	        },
	        total_cash_usd: {
	        	required: true,
	        	maxlength:20,
	        },
	         yearly_profit_usd: {
	        	required: true,
	        	maxlength:20,
	        },
	         capital_company_usd: {
	        	required: true,
	        	maxlength:20,
	        }

	    },
	    messages:{
	        yearly_usd: {              
	           required:"{{trans('forms.corp_financial.client_error.required')}}",  
         	   maxlength:"{{trans('forms.corp_financial.client_error.max_len')}}",
	        },

	        total_debts_usd: {
	           required:"{{trans('forms.corp_financial.client_error.required')}}",  
         	   maxlength:"{{trans('forms.corp_financial.client_error.max_len')}}",
	        },
	        total_payable_usd :{
	           required:"{{trans('forms.corp_financial.client_error.required')}}",  
         	   maxlength:"{{trans('forms.corp_financial.client_error.max_len')}}",
	        },
	        total_recei_usd: {
	           required:"{{trans('forms.corp_financial.client_error.required')}}",  
         	   maxlength:"{{trans('forms.corp_financial.client_error.max_len')}}",
	        },
	        total_salary_usd :{
	           required:"{{trans('forms.corp_financial.client_error.required')}}",  
         	   maxlength:"{{trans('forms.corp_financial.client_error.max_len')}}",
	        },
	        total_cash_usd: {
	           required:"{{trans('forms.corp_financial.client_error.required')}}",  
         	   maxlength:"{{trans('forms.corp_financial.client_error.max_len')}}",
	        },
	        yearly_profit_usd: {
	        	 required:"{{trans('forms.corp_financial.client_error.required')}}",  
         	   maxlength:"{{trans('forms.corp_financial.client_error.max_len')}}",
	        },
	         capital_company_usd: {
	        	 required:"{{trans('forms.corp_financial.client_error.required')}}",  
         	   maxlength:"{{trans('forms.corp_financial.client_error.max_len')}}",
	        },

	    }
		  
	});


	//validate phone no
        $.validator.addMethod('phone_number',
        function (value) { 
                 return Number(value) > 0;
             }, 'invalid number'

        );

</script>