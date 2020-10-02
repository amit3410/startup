@extends('layouts.frame')

@section('content')

<div class="modal-body text-left p-0">           
    {!!
    Form::open(
    array(
    'route' => 'save_add_role',
    'name' => 'editRoleForm',
    'autocomplete' => 'off', 
    'id' => 'editRoleForm',
    'target' => '_top',
    'method'=> 'POST',
    'class'=> 'form form-cls'
    )
    )
    !!}
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="role">Role Name
                    <span class="mandatory">*</span>
                </label>
                <input type="text" name="role" id="role" value="@if($roleInfo){{$roleInfo->name}}@endif" class="form-control employee" tabindex="1" placeholder="Role Name" >
            </div>
        </div>
		
        <div class="col-md-6">
            <div class="form-group">
                <label for="role_description" class="">Description
                    <span class="mandatory">*</span>
                </label>
                <textarea type="text" class="form-control" name="description" placeholder="Enter Description">@if($roleInfo){{$roleInfo->description}}@endif</textarea>
            </div>
        </div>
    </div>    
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="" class="">Status  <span class="mandatory">*</span></label>	
                <select class="form-control" name="is_active" id="is_active" >
                    <option value="">Select Status</option>
                    <option value="1" @if(($roleInfo) && ($roleInfo->is_active==1))selected @else @endif>Active</option>
                    <option value="0" @if(($roleInfo) && ($roleInfo->is_active==0))selected @else @endif>Inactive</option>
                </select>
            </div>
        </div>
    </div>

    {!! Form::hidden('role_id',$role_id) !!}
    <button type="submit" class="btn btn-save btn-sm perwcapi float-right" id="saveAnch">Submit</button>  
    {!!
    Form::close()
    !!}
</div>




@endsection

@section('jscript')
<style type="text/css">
    .modal-body{background-color: #fff;}
        .mandatory {
        font-size: 12px;
        color: red;
    }
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
    iframe body{background-color: #fff; }
</style>
<script src="{{ asset('common/js/jquery.validate.js') }}"></script>
<script src="{{ asset('backend/js/lead.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/additional-methods.min.js"></script>
<script>

var messages = {

    data_not_found: "{{ trans('error_messages.data_not_found') }}",
    token: "{{ csrf_token() }}",

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
$(function() {
  $("form[name='editRoleForm']").validate({
    rules: {
      role: {
         required:true,
         pattern:"^[a-z A-Z\s]+$",
         maxlength:50,
         remote:{
                   url:baseurl+'/ajax/userrole',
                   type:'POST',
                   data:{
                    userdata:function()
                    {
                      return $('#role').val()
                    },
                    
                }
            }

        },
        description:{
           required:true,
           maxlength:250,
        },
        is_active:"required",
    },
    // Specify validation error messages
    messages: {
      role:{
        required:"This field is required",
        pattern:"Please enter only alphabetical character",
        maxlength:"Please enter maximum 50 character",
        remote:"Role is Already exists",
      }, 
      description:{ 
       required:"This field is required",
        maxlength:"Please enter maximum 250 character",
       
     },
      is_active:"Please select status"
     },
    // Make sure the form is submitted to the destination defined
    // in the "action" attribute of the form when valid
    submitHandler: function(form) {
      form.submit();
    }
  });
});
    
document.body.style.backgroundColor = 'transparent';
document.body.style.overflowX  = 'hidden';
//$("iframe body").css("background-color", "yellow");
</script>
@endsection