@extends('layouts.frame')

@section('content')

<div class="modal-body text-left p-0">           
    {!!
    Form::open(
    array(
    'route' => 'save_user_role',
    'name' => 'saveRoleForm',
    'autocomplete' => 'off', 
    'id' => 'addRoleForm',
    'target' => '_top',
    'method'=> 'POST',
    'class' => 'form form-cls'
    )
    )
    !!}
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="txtCreditPeriod">First Name
                    <span class="mandatory">*</span>
                </label>
                <input type="text" name="f_name" id="f_name" value="" class="form-control" tabindex="1" placeholder="First Name" required="" autocomplete="off">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="txtAnchorName">Last Name
                    <span class="mandatory">*</span>
                </label>
                <input type="text" name="l_name" id="l_name" value="" class="form-control" tabindex="2" placeholder="Last Name" required="" autocomplete="off">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="txtCreditPeriod">Mobile Number
                    <span class="mandatory">*</span>
                </label>
                <input type="text" name="mobile_no" id="mobile_no" value="" class="form-control" tabindex="3" placeholder="Enter Mobile" required="" autocomplete="off">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="txtAnchorName">Username
                    <span class="mandatory">*</span>
                </label><tr>

                <input type="text" name="username" id="username" value="" class="form-control" tabindex="4" placeholder="Enter Username" required="" autocomplete="off">
            </div>
        </div>



        <div class="col-sm-12">
            <div class="form-group">
                <label for="txtAnchorName">E-mail
                    <span class="mandatory">*</span>
                </label>
                <input type="text" name="email" id="email" value="" class="form-control" tabindex="5" placeholder="Enter E-mail" required="" autocomplete="off">
            </div>
        </div>
    </div>
    <div class="row">


        <div class="col-sm-6">
            <div class="form-group">
                <label for="txtMobile">Password
                    <span class="mandatory">*</span>
                </label>
            <input type="password" class="form-control" id="password" name="password"  tabindex="6" placeholder="Enter Password"  autocomplete="off">
            </div>                      
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <label for="txtMobile">Confirm Password
                    <span class="mandatory">*</span>
                </label>

                <input type="password" class="form-control numbercls" name="conf_password"   tabindex="7" placeholder="Enter Confirm Password" required="" autocomplete="off">
                <div class="failed">

                </div>
            </div>                      
        </div>	
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="txtMobile">Is Active
                    <span class="mandatory">*</span>
                </label>
                {!!
                Form::select('is_active',
                [''=>'Please select','0'=> 'Inactive','1'=>'Active'],
                null,
                array('id' => 'is_active',
                'class'=>'form-control'))
                !!}
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="txtMobile">Role
                    <span class="mandatory">*</span>
                </label>
                {!!
                Form::select('role_id',
                [''=>'Select Role']+Helpers::getAllRole()->toArray(),
                null,
                array('id' => 'role',
                'class'=>'form-control'))
                !!}
            </div>
        </div>
    </div>



    <button type="submit" class="btn btn-save btn-sm perwcapi float-right">Submit</button>  

    {!! Form::hidden('_token',csrf_token()) !!}

    {!!
    Form::close()
    !!}
</div>
@endsection
@section('jscript')
<style>
.btn.btn-save {
    color: #743939;
    background: #F7CA5A;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 4px;
    padding: 7px 30px 7px 30px;
    margin: 10px 5px;
    font-size: 14px;
    font-weight: 700;
    text-transform: capitalize;
}
.mandatory {
    font-size: 12px;
    color: red;
}
.modal-body{background-color: #fff;}
</style>
<script src="{{ asset('common/js/jquery.validate.js') }}"></script>
<script src="{{ asset('backend/js/lead.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/additional-methods.min.js"></script>
<script>

var messages = {
    
    data_not_found: "{{ trans('error_messages.data_not_found') }}",
    token: "{{ csrf_token() }}",
    is_accept: "{{ Session::get('is_accept') }}",

};
</script>
<script type="text/javascript">
// Wait for the DOM to be ready
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        }); 

       var baseurl = window.location.origin;
    $(function () {
        $("form[name='saveRoleForm']").validate({
            rules: {
                f_name: {
                  required: true,
                  lettersonly:true,
                  maxlength:50,
                 },
                l_name: {
                 required:true,
                 lettersonly:true,
                 maxlength:50,
                 },
               
               mobile_no:{
                required:true,
                minlength:8,
                maxlength:20,
                number: true,
                },
                email:{
                 required:true,
                  email:true,
                  maxlength:50,
                    remote:{
                          url:baseurl+'/ajax/checkemail',
                          type:'POST',
                          data:{
                            userdata:function()
                            {
                              return $('#email').val()
                            },
                            datatype:'email'
                        }
                    }
                },
                
                username:{
                  required:true,
                  maxlength:50,
                  remote:{
                          url:baseurl+'/ajax/username',
                          type:'POST',
                          data:{
                            userdata:function()
                            {
                              return $('#username').val()
                            },
                            
                        }
                    }
                },
                is_active: "required",
                role_id: "required",
                password:{
                   required: true, 
                   minlength:8,
                   maxlength:15,
                },
                conf_password: {
                    equalTo: "#password",
                    required:true,
                  }
            
            
            },
            // Specify validation error messages
             messages: {
                  f_name:{
                    required:"This field is required.",
                    lettersonly:"Please enter only alphabetical characters.",
                    maxlength: "Please enter maximum 50 characters.",
                    },
                    l_name:{
                    required:"This field is required.",
                    lettersonly:"Please enter only alphabetical characters.",
                    maxlength: "Please enter maximum 50 characters.",
                    },
                    email:{
                        required:"This field is required.",
                        maxlength:"Please enter maximum 50 characters.",
                        remote: "The email is already registered."
                    },
                    password:{
                        required:"This field is required.",
                        minlength:"Password must contain atleast 8 characters.",
                        maxlength:"Password must contain atmost 15 characters.",  
                    },
                    conf_password: {
                        equalTo:"Your new password and Confirm new password do not match.",
                    },
                    username:{
                        required:"This field is required.",
                        maxlength:"Please enter maximum 50 characters.",
                        remote: "Username is already registered."
                    },
                    mobile_no:{
                      required:"This field is required.",
                      minlength:"Please enter minimum 8 characters.",
                      maxlength:"Please enter maximum 20 characters.",
                      number:"Please enter only number.",  
                    },
                role: "Please enter role.",               
                description: "Please enter role description.",
                is_active: "Please select status"
            },
            // Make sure the form is submitted to the destination defined
            // in the "action" attribute of the form when valid
            submitHandler: function (form) {
                form.submit();
            }
        });
        
        if(messages.is_accept == 1){
       var parent =  window.parent;     
       parent.jQuery("#addmanageUserRole").modal('hide');  
       //window.parent.jQuery('#my-loading').css('display','block');
       
        parent.oTables1.draw();
       //window.parent.location.href = messages.paypal_gatway;
    }
    });
document.body.style.background = "transparent";
document.body.style.overflowX  = "hidden";
</script>
@endsection