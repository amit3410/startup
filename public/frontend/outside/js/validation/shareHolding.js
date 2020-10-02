/* global messages, message */

try {
    jQuery(document).ready(function ($) {

       $('.sharevalue').on('blur',function(){
           var id   =   $(this).attr('data');
           var curavl   =   $(this).val();
           var th       =   $(this);
           var curName  =   this.name;
            if(isNaN(Number(curavl))){
               th.val('');
               $("#error"+curName).html(messages.invalid_value);
               return;
           }else{
                $("#error"+curName).html(''); 
           }
        });
       
        $('.sharevalue').on('paste',function(){
           var id   =   $(this).attr('data');
           var curavl   =   $(this).val();
           var th       =   $(this);
           var curName  =   this.name;
           
           if(isNaN(Number(curavl))){
               th.val('');
               $("#error"+curName).html(messages.invalid_value);
               return;
           }else{
               $("#error"+curName).html(''); 
           }
       });
       
        $('.shareper').on('blur',function(){
           var id   =   $(this).attr('data');
           var cloned = $('#childInfo_'+id+' .clonedclonedSocialmedias-'+id).clone(true);
           var sumper   =   0;
           var curavl   =   $(this).val();
           var th       =   $(this);
           var curName  =   this.name;
           
           if(isNaN(Number(curavl))){
               th.val('');
               $("#error"+curName).html(messages.invalid_value);
               return;
           }else{
               $("#error"+curName).html(''); 
           }
           
           cloned.find('input.shareper').each(function (){
              var inputId   =   this.id;
              var inputName=this.name;
             // alert(inputName);
              sumper        =   sumper+parseFloat($("#"+inputId).val());
            
           });
           
          
           
           if(sumper>100){
               th.val('');
               $("#error"+curName).html(messages.sum_share_per);
               
           }else{
              cloned.find('input.shareper').each(function (){
              var inputId   =   this.id;
              $("#error"+inputId).html('');
            
              });
             
           }
          
        });
        
         $('.shareper').on('paste',function(){
           var id   =   $(this).attr('data');
           var cloned = $('#childInfo_'+id+' .clonedclonedSocialmedias-'+id).clone(true);
           var sumper   =   0;
           var curavl   =   $(this).val();
           var th       =   $(this);
           var curName  =   this.name;
           
           if(isNaN(Number(curavl))){
               th.val('');
               $("#error"+curName).html(messages.invalid_value);
               return;
           }else{
               $("#error"+curName).html(''); 
           }
           cloned.find('input.shareper').each(function (){
              var inputId   =   this.id;
              sumper        =   sumper+parseFloat($("#"+inputId).val());
            
           });
           
           
           if(sumper>100){
               
               $("#error"+curName).html('Sum of share percentage should be less than or equal to 100');
               
           }else{
              cloned.find('input.shareper').each(function (){
              var inputId   =   this.id;
              $("#error"+inputId).html('');
            
              });
             
           }
          
        });

        $('.add-socialmedia').on('click', function () {
            var id  = $(this).attr('data');
           
            var len = $('#childInfo_'+id+' .clonedclonedSocialmedias-'+id).length;

            var cloned = $('#childInfo_'+id+' .clonedclonedSocialmedias-'+id+':first').clone(true);
            
            var lastRepeatingGroup = $('#childInfo_'+id+' .clonedclonedSocialmedias-'+id).last();
            cloned.attr('id', 'clonedSocialmedias'+id+'_'+ len);
            
            cloned.find('input').each(function (){
               // var lastChar = this.id.match(/\d+/);
                //this.name = this.name;///this.name.replace('[' + lastChar + ']', '[' + len + ']');
                var strChar =this.id.split('_');
                this.name = strChar[0]+'_'+len;
                this.id = strChar[0]+'_'+len;//this.id = this.id.replace('' + lastChar + '', '' + len + ''); 
   
            });
            
            cloned.find('select').each(function (){
                //var lastChar = this.id.match(/\d+/);
               // this.name = this.name;//this.name.replace('[' + lastChar + ']', '[' + len + ']');
                var strChar =this.id.split('_');
                this.name = strChar[0]+'_'+len;
                this.id = strChar[0]+'_'+len;//this.id = this.id.replace('' + lastChar + '', '' + len + '');
                
           
            });
            
            cloned.find('span.text-danger').each(function (){
                //var lastChar = this.id.match(/\d+/);
               // this.name = this.name;//this.name.replace('[' + lastChar + ']', '[' + len + ']');
                var strChar =this.id.split('_');
                this.id = strChar[0]+'_'+len;//this.id = this.id.replace('' + lastChar + '', '' + len + '');
                
            });

            cloned.find('input[type=text]').val('');
            cloned.find('input[type=text]').val('');
            cloned.find('select').val('');
            cloned.find('select').attr('data',len);
            cloned.find('.deleteSkillbtn').show();
            cloned.find('.deleteSkillbtn').attr('data',id);
            cloned.find('.error').next().remove();               
            cloned.find('label').removeClass('error');
            cloned.find('select').removeClass("error");
            cloned.insertAfter(lastRepeatingGroup).addClass('new_formTwo');
            
            var Add_len = Number(len + 1);
            if(Add_len == messages.social_media_form_limit){
                $('.add-socialmedia').attr('data-row',Add_len);
                $('input[name=rows'+id+']').val(Add_len);
                $('.add-socialmedia').hide();
            }else{
                $('.add-socialmedia').attr('data-row',Add_len);
                $('input[name=rows'+id+']').val(Add_len);
                $('.add-socialmedia').show();
            }
        });

        
        /**
         * Delete added Social Media Link.
         */

        $(document).on('click', '.deleteSkillbtn', function () {
            var id  =   $(this).attr('data');
            
            $(this).parents('.clonedclonedSocialmedias-'+id).remove();

            $(".clonedclonedSocialmedias-"+id).each(function (index) {
                $(this).attr('id', 'clonedSocialmedias'+id+'_'+ index);
                $(this).find('input').each(function (){
               // var lastChar = this.id.match(/\d+/);
                //this.name = this.name;///this.name.replace('[' + lastChar + ']', '[' + len + ']');
                var strChar =this.id.split('_');
                this.name = strChar[0]+'_'+index;
                this.id = strChar[0]+'_'+index;//this.id = this.id.replace('' + lastChar + '', '' + len + ''); 
   
            });
            
            $(this).find('select').each(function (){
                //var lastChar = this.id.match(/\d+/);
               // this.name = this.name;//this.name.replace('[' + lastChar + ']', '[' + len + ']');
                var strChar =this.id.split('_');
                this.name = strChar[0]+'_'+index;
                this.id = strChar[0]+'_'+index;//this.id = this.id.replace('' + lastChar + '', '' + len + '');
                
               
            });
            
            $(this).find('span.text-danger').each(function (){
                //var lastChar = this.id.match(/\d+/);
               // this.name = this.name;//this.name.replace('[' + lastChar + ']', '[' + len + ']');
                var strChar =this.id.split('_');
                this.id = strChar[0]+'_'+index;//this.id = this.id.replace('' + lastChar + '', '' + len + '');
                
            });
            
            

                var Add_len = Number(index + 1);
                if (Add_len < messages.social_media_form_limit) {
                    $('.add-socialmedia').show();
                    $('.add-socialmedia').attr('data-row',Add_len);
                    $('input[name=rows'+id+']').val(Add_len);
                } else {
                    $('.add-socialmedia').attr('data-row',Add_len);
                    $('input[name=rows'+id+']').val(Add_len);
                    $('.add-socialmedia').hide();
                  
                    
                }
            });
        });
        

    });
} catch (e) {
    if (typeof console !== 'undefined') {
        console.log(e);
    }
}

