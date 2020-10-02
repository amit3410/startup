@extends('layouts.app')

@section('content')


<section>
    <div class="container">
        <div class="row">
            <div id="header" class="col-md-3">
                @include('layouts.user-inner.left-menu')
            </div>
            <div class="col-md-9 dashbord-white">
                <div class="form-section">
                    <div class="row marB10">
                        <div class="col-md-12">
                            <h3 class="h3-headline">Family Information</h3>
                        </div>
                    </div>   

                    {!!
                    Form::open(
                    array(
                    'name' => 'familyInformationForm',
                    'id' => 'familyInformationForm',
                    'url' => route('family_information',['id'=>@$userData['user_kyc_family_id'],'user_kyc_id'=>@$benifinary['user_kyc_id'],'corp_user_id'=>@$benifinary['corp_user_id'],'is_by_company'=>@$benifinary['is_by_company']]),
                    'autocomplete' => 'off','class'=>'loginForm form form-cls'
                    ))
                    !!}

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">

                                {{ Form::label('spouse_f_name', 'Spouse First Name', array('class' => ''))}} 
                                {{ Form::text('spouse_f_name',@$userData['spouse_f_name'], ['class' => 'form-control','placeholder'=>'Enter First Name','id' => 'spouse_f_name']) }}

                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('spouse_m_name', 'Spouse Maiden Name', array('class' => ''))}} 
                                {{ Form::text('spouse_m_name',@$userData['spouse_m_name'], ['class' => 'form-control','placeholder'=>'Enter Middle Name','id' => 'spouse_m_name']) }}

                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('is_professional_status', 'Spouse Professional Status', array('class' => ''))}} 
                                {!!
                                Form::select('is_professional_status',
                                [''=>'Select Professional Status'] + Helpers::getProfStatusDropDown()->toArray(),
                                @$userData['is_spouse_profession'],
                                array('id' => 'is_professional_status','class'=>'form-control'))
                                !!}

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="form-group">
                                    {{ Form::label('spouse_profession', "Spouse’s Profession (if only)", array('class' => ''))}}
                                    {{ Form::text('spouse_profession',@$userData['spouse_profession'], ['class' => 'form-control','placeholder'=>'Enter Profession Name','id' => 'spouse_profession']) }}

                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"> 
                                <div class="form-group">

                                    {{ Form::label('spouse_employer', "Spouse’s Employer (if only)", array('class' => ''))}}
                                    {{ Form::text('spouse_employer',@$userData['spouse_employer'], ['class' => 'form-control','placeholder'=>'Enter Employer Name','id' => 'spouse_employer']) }}


                                </div>
                            </div>
                        </div>



                    </div>					

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="pwd">Children Information </label>
                            </div>
                        </div>	  	
                    </div>

                  
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                               @if(@$userData['is_child']==1)
                                <label for="pwd"> No Children <input type="checkbox" name="is_child" value="1" checked=""></label>
                                @else
                                <label for="pwd"> No Children <input type="checkbox" name="is_child" value="0"></label>
                                @endif
                                
                            </div>
                        </div>	  	
                    </div>

                    <?php
                    $childInfo = @$userData['spouce_child_info'];
                    
                    $arrChildInfo = [];
                    if ($childInfo != '' && $childInfo != null) {
                        $arrChildInfo = json_decode($childInfo);
                    }
                    if (count($arrChildInfo) > 0) {
                        ?>
                        <div id="childInfo" class="is_child">
                            <?php
                            foreach ($arrChildInfo as $key => $chinfo) {
                                ?>



                                <div id="TrainingPeriod0" class="trainingperiod">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">

                                                {{ Form::label('child_name', "Child 1", array('class' => 'lblname'))}}
                                                {{ Form::text('child_name[]',$chinfo->child_name, ['class' => 'form-control','placeholder'=>'Enter Child Name','id' => 'child_name'.$key]) }}
                                                <span class="text-danger">{{ $errors->first('child_name.'.$key) }}</span>

                                            </div>
                                        </div>
                                            
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {{ Form::label('child_dob', "Date of Birth", array('class' => ''))}}
                                                <div class="input-group">
                                                    {{ Form::text('child_dob[]',$chinfo->child_dob, ['class' => 'form-control datepicker','placeholder'=>'Select Date of Birth','id' => 'child_dobaa0']) }}
                                                    <div class="input-group-append">
                                                        <i class="fa fa-calendar-check-o"></i>
                                                    </div>
                                                    <span class="text-danger">{{ $errors->first('child_dob.0') }}</span>
                                                </div>

                                                <div class="deleteChildbtnbck remove"  style="display: none;"><i class="fa fa-trash-o deleteFamily" title="Remove" aria-hidden="true"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

        <?php
    }
    ?>
                        </div>
                            <?php
                        } else {
                            ?>

                        <div id="childInfo" class="is_child">
                            <div id="TrainingPeriod0" class="trainingperiod">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">

                                            {{ Form::label('child_name', "Child 1", array('class' => 'lblname'))}}
                                            {{ Form::text('child_name[]','', ['class' => 'form-control','placeholder'=>'Enter Child Name','id' => 'child_name0']) }}
                                            <span class="text-danger">{{ $errors->first('child_name.0') }}</span>

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('child_dob', "Date of Birth", array('class' => ''))}}
                                            <div class="input-group">
                                                {{ Form::text('child_dob[]','', ['class' => 'form-control datepicker','placeholder'=>'Select Date of Birth','id' => 'child_dobaa0']) }}
                                                <div class="input-group-append">
                                                    <i class="fa fa-calendar-check-o"></i>
                                                </div>
                                                <span class="text-danger">{{ $errors->first('child_dob.0') }}</span>
                                            </div>
                                            <!--<a href="#" class="add-skills pull-right marT10 text-color">+Add</a>>-->
                                            <div class="deleteChildbtnbck remove"  style="display: none;"><i class="fa fa-trash-o deleteFamily" title="Remove" aria-hidden="true"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                  
    <?php
}
?>


                    <div class="row is_child">
                        <div class="col-md-8"></div>
                        <div class="col-md-1">
                            <span class="add-child pull-right marT10 text-color" style="">+Add</span>
                        </div>
                        <div class="col-md-3"></div>
                    </div>

                    <div class="row marT60">
                        <div class="col-md-12 text-right">
                            <a href="{{$benifinary['prev_url']}}" class="btn btn-prev pull-left">Previous</a>

                            @if($kycApproveStatus==0)
                            {{ Form::submit('Save',['class'=>'btn btn-save','name'=>'save']) }}
                            {{ Form::submit('Save & Next',['class'=>'btn btn-save','name'=>'save_next']) }}

                            @else

                           <a href="{{$benifinary['next_url']}}" class="btn btn-save">Next</a>
                            @endif
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>

        </div>	
    </div>
</section>

@endsection
@section('pageTitle')
Family Information
@endsection
@section('additional_css')
<link href="{{ asset('frontend/inside/plugin/datepicker/jquery-ui.css') }}" rel="stylesheet">
@endsection
@section('jscript')
<script src="{{ asset('frontend/inside/plugin/datepicker/jquery-ui.js') }}"></script>
<script src="{{ asset('frontend/outside/js/validation/familyInfo.js')}}"></script>
<script src="{{ asset('frontend/outside/js/validation/familyInfoForm.js')}}"></script>
<script>
$(document).ready(function () {
    var disabledAll='{{$kycApproveStatus}}';
    if(disabledAll!=0){
        $("#familyInformationForm :input"). prop("disabled", true); 
    }
    // $('.datepicker').datepicker();
    $('.datepicker').datepicker({dateFormat: 'dd/mm/yy'});
    var date = $('.maturityDate').datepicker({dateFormat: 'dd/mm/yy'});

});


</script>
<style>
.deleteChildbtnbck {
    position: absolute;
    right: -25px;
    top: 30px;
    font-size: 16px;
    cursor: pointer;
    color: #743939;
}
</style>
@endsection