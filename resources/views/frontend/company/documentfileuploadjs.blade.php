<script>

$.fn.fileUploader = function (filesToUpload, sectionIdentifier) {
    var fileIdCounter = 0;
    //$("#files0")
    this.closest(".files").change(function (evt) {
        var output = [];
        
        
        for (var i = 0; i < evt.target.files.length; i++) {
            fileIdCounter++;
            var file = evt.target.files[i];
            //var fileId = sectionIdentifier + fileIdCounter;
            var fileId = sectionIdentifier;
            var lastChar = fileId[fileId.length -1];
            var fid= $('#pics'+lastChar).attr('data-id');
            var array = fid.split('#');
            $('#doc_'+array[0]).html('');
            filesToUpload.push({
                id:$('#pics'+lastChar).attr('data-id'),
                file: Object.assign(file , { picsId : $('#pics'+lastChar).attr('data-id')})
            });
//console.log(file);
            var removeLink = "<a class=\"removeFile\" href=\"#\" data-fileid=\"" + fileId + "\">Remove</a>";

            output.push("<li><strong>", decodeURI(file.name), "</strong> - ", removeLink, "</li> ");
        };

       // console.log(file);

        $(this).children(".fileList")
            .append(output.join(""));

        //reset the input to null - nice little chrome bug!
        evt.target.value = null;
    });

    $(this).on("click", ".removeFile", function (e) {
        e.preventDefault();

        var fileId = $(this).parent().children("a").data("fileid");

        // loop through the files array and check if the name of that file matches FileName
        // and get the index of the match
        for (var i = 0; i < filesToUpload.length; ++i) {
            if (filesToUpload[i].id === fileId)
                filesToUpload.splice(i, 1);
        }

        $(this).parent().remove();
    });

    this.clear = function () {
        for (var i = 0; i < filesToUpload.length; ++i) {
            if (filesToUpload[i].id.indexOf(sectionIdentifier) >= 0)
                filesToUpload.splice(i, 1);
        }

        $(this).children(".fileList").empty();
    }

    return this;
};




(function () {
	
    var filesToUpload = [];
    var fileDataId = [];
     var filesToUploadArray = [];
    let gval = $("#gval").val();
   
    for (k = 0; k <=gval;  k++) {
  	var filesUploader = $("#files"+k).fileUploader(filesToUpload, "files"+k);
    //let filId = $("#pics"+k).attr('data-id');
    //fileDataId.push(filId);
}
   
   

   $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }); 
    

    $('#documentform').validate({
            rules: {
                
            
        },
        messages:{
                
        },

        submitHandler: function (myform) {
            $('.text-danger').html('');        
            baseurl="{{url('')}}";
            var formData = new FormData();
               
            for (var i = 0, len = filesToUpload.length; i < len; i++) {
               
                formData.append("files["+filesToUpload[i].id+"][]", filesToUpload[i].file);
                    
            }
            
            formData.append('document_number',$('#document_number').val()); 
            formData.append('issuance_date',$('#issuance_date').val()); 

            if ($('#check2').is(":checked"))
                {
                    var checkbox = 1;
                } else {
                   var checkbox = 0;
                }
                formData.append('checkboxval',checkbox);

        /* if(filesToUpload.length > 0) {
             
            } else {
                 let error= "{{trans('forms.corperate_uploaddoc.client_error.error')}}";
                $('#errormsg').text(error);
                return false;
                
            }*/

            $('#uploadBtn').attr("disabled", true);
            $.ajax({
            url: globlvar.save_corpdoc_url,
            data: formData,
            method: "POST",
            processData: false,
            contentType: false,
            success: function (data) {

                 $('#uploadBtn').attr("disabled",false);
                let message= "{{trans('forms.corperate_uploaddoc.success.msg_success')}}";
                let error= "{{trans('forms.corperate_uploaddoc.client_error.server_error')}}";
                var result=JSON.parse(data); 
                console.log(result); 
                
                if(result['status']=="success"){
                    
                window.location.replace(result['redirect']);
                }else if(result['status'] == 'kyccompleted')
                {
                  // window.location.href = baseurl+ '/profile/thanku';
                   location.reload();
                }else{
                    var Isarray=result['is_array'];
                    var messages=result['message'];
                    
                    // console.log(messages);
                    if(Isarray=='1'){
                       $.each(messages, function(key,value) {
                          // console.log(key);
                           $('#'+key).html(value);
                       }); 
                    }else{
                        var errorMs='<div class=" my-alert-danger alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+result['message']+'</div>';
                        $("#errormsg").html(errorMs);
                    }
                }
             
               

    
                },
                error: function (data) {
                alert("ERROR - " + data.responseText);
                }
            });
        },
});



//ajax for indivisual

     $('#documentform_indi').validate({
            rules: {
                

        },
        messages:{
                
        },

        submitHandler: function (myform) {
  
            $('.text-danger').html('');    
            baseurl="{{url('')}}";
            var formData = new FormData();
               
            for (var i = 0, len = filesToUpload.length; i < len; i++) {
               
                formData.append("files["+filesToUpload[i].id+"][]", filesToUpload[i].file);
                    
            }
            
            formData.append('document_number',$('#document_number').val()); 
            formData.append('issuance_date',$('#issuance_date').val());
            if ($('#check2').is(":checked"))
                {
                    var checkbox = 1;
                } else {
                   var checkbox = 0;
                }
            formData.append('checkboxval',checkbox);
           
            //$('#indivisualuploadBtn').attr("disabled", true);
            //$('#my-loading').css('display','block');

            $.ajax({
            url: globlvar.save_doc_url,
            
            data: formData,
            method: "POST",
             processData: false,
             contentType: false,
              beforeSend: function(){
               $('#indivisualuploadBtn').attr("disabled", true);
               $('#my-loading').css('display','block');
              },
              complete: function(){
                //$("#loading").hide();
                $('#indivisualuploadBtn').attr("disabled", false);
                $('#my-loading').css('display','none');
              },

            success: function (data) {
                
            var result=JSON.parse(data); 
                //console.log(result);
                $('#uploadBtn').attr("disabled",false);
                if(result['status']=="success"){
                    console.log(result['redirect']);
                   window.location.replace(result['redirect']);
                }else{
                    var Isarray=result['is_array'];
                    var messages=result['message'];
                    //var arrext=result['ext'];
                   //console.log(arrext);
                    if(Isarray=='1'){
                       $.each(messages, function(key,value) {
                           // console.log(key);
                           $('#'+key).html(value);
                       }); 
                    }else{
                        var errorMs='<div class=" my-alert-danger alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+result['message']+'</div>';
                        $("#errormsg").html(errorMs);
                    }
                  
                  
                }            
               
               
            },
            error: function (data) {
                alert("ERROR - " + data.responseText);
            }
        });
    },
});
//
//ajax for indivisual

     $('#otherDocsIndvForm').validate({
            rules: {
               
        },
        messages:{
                
        },

        submitHandler: function (myform) {

            
            $('.text-danger').html('');    
            var formData = new FormData();
               
            for (var i = 0, len = filesToUpload.length; i < len; i++) {
               
                formData.append("files["+filesToUpload[i].id+"][]", filesToUpload[i].file);
                    
            }
           
           // $('#indivisualuploadBtn').attr("disabled", true);
           
            $.ajax({
            url: globlvar.save_doc_url,
            
            data: formData,
            method: "POST",
             processData: false,
             contentType: false,

             beforeSend: function(){
               $('#indivisualuploadBtn').attr("disabled", true);
               $('#my-loading').css('display','block');
              },
              complete: function(){
                //$("#loading").hide();
                $('#indivisualuploadBtn').attr("disabled", false);
                $('#my-loading').css('display','none');
              },

            success: function (data) {
                
            var result=JSON.parse(data); 
                console.log(result);
                $('#uploadBtn').attr("disabled",false);
                if(result['status']=="success"){
                 
                   window.location.replace(result['redirect']);
                }else{
                    var Isarray=result['is_array'];
                    var messages=result['message'];
                    //var arrext=result['ext'];
                   //console.log(arrext);
                    if(Isarray=='1'){
                       $.each(messages, function(key,value) {
                          //  console.log(key);
                            //console.log(value);
                           $('#'+key).html(value);
                       }); 
                    }else{
                        var errorMs='<div class=" my-alert-danger alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+result['message']+'</div>';
                        $("#errormsg").html(errorMs);
                    }
                  
                  
                }            
               
               
            },
            error: function (data) {
                alert("ERROR - " + data.responseText);
            }
        });
    },
});
     //other Corp Doc upload
 $('#otherDocsCorpForm').validate({
            rules: {
                

        },
        messages:{
                
        },

        submitHandler: function (myform) {
           /*if($("#check2"). is(":checked")){
                $('#termError').html('');
            }else{
                $('#termError').html("{{trans('forms.corperate_uploaddoc.client_error.required')}}");
                return false;
            }*/
            $('.text-danger').html('');    
            var formData = new FormData();
               
            for (var i = 0, len = filesToUpload.length; i < len; i++) {
               
                formData.append("files["+filesToUpload[i].id+"][]", filesToUpload[i].file);
                    
            }
           
           // $('#indivisualuploadBtn').attr("disabled", true);
            $('#indivisualuploadBtn').attr("disabled", true);
            $('#my-loading').css('display','block');
            
            $.ajax({
            url: globlvar.save_otherdoc_corp_url,
            
            data: formData,
            method: "POST",
             processData: false,
             contentType: false,
            success: function (data) {
               console.log(data); 
            var result=JSON.parse(data); 
                
                $('#uploadBtn').attr("disabled",false);
                if(result['status']=="success"){
                 
                   window.location.replace(result['redirect']);
                }else{
                    var Isarray=result['is_array'];
                    var messages=result['message'];
                    //var arrext=result['ext'];
                   //console.log(arrext);
                    if(Isarray=='1'){
                       $.each(messages, function(key,value) {
                            console.log(key);
                            console.log(value);
                           $('#'+key).html(value);
                       }); 
                    }else{
                        var errorMs='<div class=" my-alert-danger alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+result['message']+'</div>';
                        $("#errormsg").html(errorMs);
                    }
                  
                  
                }            
               
               
            },
            error: function (data) {
                alert("ERROR - " + data.responseText);
            }
        });
    },
});

})()

</script>