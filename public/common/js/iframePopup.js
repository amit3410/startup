$(document).ready(function(){
   
    //on frontend
    //open my account on profile tab
     $("#viewMyAccoutPopup").on('show.bs.modal', function (e) {
                var parent = $(e.relatedTarget);
                var height = parent.attr('data-height');
                var url = parent.attr('data-url');
                var width = parent.attr('data-width');
                $("#viewMyAccoutPopup iframe").attr(
                        {
                            'src': url,
                            'height': height,
                            'width': width
                        }
                );
        });
        
      
        
        
     //
     $("#personalwcapi").on('show.bs.modal', function (e) {
                var parent = $(e.relatedTarget);
                var height = parent.attr('data-height');
                var url = parent.attr('data-url');
                var width = parent.attr('data-width');
                $("#personalwcapi iframe").attr(
                        {
                           
                            'src': url,
                            'height': height,
                            'width': width,
                        }
                );
        });
        
        
        
     $("#btn-approved-iframe").on('show.bs.modal', function (e) {
                var parent = $(e.relatedTarget);
                var height = parent.attr('data-height');
                var url = parent.attr('data-url');
                var width = parent.attr('data-width');
                $("#btn-approved-iframe iframe").attr(
                        {
                           
                            'src': url,
                            'height': height,
                            'width': width,
                        }
                );
        });  
        
        
        
    $("#btn-disapproved-iframe").on('show.bs.modal', function (e) {
                var parent = $(e.relatedTarget);
                var height = parent.attr('data-height');
                var url = parent.attr('data-url');
                var width = parent.attr('data-width');
                $("#btn-disapproved-iframe iframe").attr(
                        {
                           
                            'src': url,
                            'height': height,
                            'width': width,
                        }
                );
        });    
        
        
       $("#corpwcapi").on('show.bs.modal', function (e) {
                var parent = $(e.relatedTarget);
                var height = parent.attr('data-height');
                var url = parent.attr('data-url');
                var width = parent.attr('data-width');
                $("#corpwcapi iframe").attr(
                        {
                           
                            'src': url,
                            'height': height,
                            'width': width,
                        }
                );
        });
        
    
    
    
    $("#termcondition").on('show.bs.modal', function (e) {
                var parent = $(e.relatedTarget);
                var height = parent.attr('data-height');
                var url = parent.attr('data-url');
                var width = parent.attr('data-width');
                $("#termcondition iframe").attr(
                        {
                           
                            'src': url,
                            'height': height,
                            'width': width,
                        }
                );
        });
        
        
     
        
        
       $("#addRoleFrm").on('show.bs.modal', function (e) { 
                var parent = $(e.relatedTarget);
                var height = parent.attr('data-height');
                var url = parent.attr('data-url');
                var width = parent.attr('data-width');
                $("#addRoleFrm iframe").attr(
                        {
                            'src': url,
                            'height': height,
                            'width': width
                        }
                );
        });
        
         $("#manageUserRole").on('show.bs.modal', function (e) {
                var parent = $(e.relatedTarget);
                var height = parent.attr('data-height');
                var url = parent.attr('data-url');
                var width = parent.attr('data-width');
                $("#manageUserRole iframe").attr(
                        {
                            'src': url,
                            'height': height,
                            'width': width
                        }
                );
        });
        
    $("#addmanageUserRole").on('show.bs.modal', function (e) {
                var parent = $(e.relatedTarget);
                var height = parent.attr('data-height');
                var url = parent.attr('data-url');
                var width = parent.attr('data-width');
                $("#addmanageUserRole iframe").attr(
                        {
                            'src': url,
                            'height': height,
                            'width': width
                        }
                );
        }); 
        
        
       //open my send to Trading Plateform
     $("#sendtoTrading").on('show.bs.modal', function (e) {
                var parent = $(e.relatedTarget);
                var height = parent.attr('data-height');
                var url = parent.attr('data-url');
                var width = parent.attr('data-width');
                $("#sendtoTrading iframe").attr(
                        {
                            'src': url,
                            'height': height,
                            'width': width
                        }
                );
        });
        
        
        
        $("#btn-nomatch-iframe").on('show.bs.modal', function (e) {
                var parent = $(e.relatedTarget);
                var height = parent.attr('data-height');
                var url = parent.attr('data-url');
                var width = parent.attr('data-width');
                $("#btn-nomatch-iframe iframe").attr(
                        {
                           
                            'src': url,
                            'height': height,
                            'width': width,
                        }
                );
        });    
        
        
        
        $("#changeStatus").on('show.bs.modal', function (e) {
                var parent = $(e.relatedTarget);
                var height = parent.attr('data-height');
                var url = parent.attr('data-url');
                var width = parent.attr('data-width');
                $("#changeStatus iframe").attr(
                        {
                           
                            'src': url,
                            'height': height,
                            'width': width,
                        }
                );
        });  
        
        
        
        $("#btn-resolution-iframe").on('show.bs.modal', function (e) {
                var parent = $(e.relatedTarget);
                var height = parent.attr('data-height');
                var url = parent.attr('data-url');
                var width = parent.attr('data-width');
                $("#btn-resolution-iframe iframe").attr(
                        {
                           
                            'src': url,
                            'height': height,
                            'width': width,
                        }
                );
        });    
        
        
        
        $("#searchCases").on('show.bs.modal', function (e) {
                var parent = $(e.relatedTarget);
                var height = parent.attr('data-height');
                var url = parent.attr('data-url');
                var width = parent.attr('data-width');
                $("#searchCases iframe").attr(
                        {
                           
                            'src': url,
                            'height': height,
                            'width': width,
                        }
                );
        });
        
        
        
        $("#reportblockchain").on('show.bs.modal', function (e) {
                var parent = $(e.relatedTarget);
                var height = parent.attr('data-height');
                var url = parent.attr('data-url');
                var width = parent.attr('data-width');
                $("#reportblockchain iframe").attr(
                        {
                           
                            'src': url,
                            'height': height,
                            'width': width,
                        }
                );
        });
        
        
    
});