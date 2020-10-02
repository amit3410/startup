/* global messages, message */

    

$(document).ready(function(){


    	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        }); 
        var baseurl="{{url('')}}";

        $('#date_of_birth').datepicker({dateFormat: 'dd/mm/yy', maxDate: new Date(), changeMonth: true, changeYear: true});
	    $('#legal_maturity_date').datepicker({dateFormat: 'dd/mm/yy', maxDate: new Date(), changeMonth: true, changeYear: true});
	    $('.issuance-date').datepicker({
	        dateFormat: 'dd/mm/yy',
	        maxDate: new Date(),
	    });

	    $('.datepicker').datepicker({
        dateFormat: 'dd/mm/yy',
        maxDate: new Date(),
       });

	    $('.datepicker').keydown(function (e) {
	                e.preventDefault();
	                return false;
	            });

	  
    	

	    $.get(baseurl+'/ajax/modal-ajax', function(data){
	            
	        if(data==0 || data==null){
	            $('#myModal').modal('show');
	        }
	    });


    //ajax call for update user
    $('.modalclose').click(function(){

        $.get(baseurl+'/ajax/user_first_time_popup', function(data){
           
           
        });
    });

});



   
