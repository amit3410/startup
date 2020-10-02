@extends('layouts.admin')

@section('content')

<div class="prtm-page-bar">
    <ul class="breadcrumb">
        <li class="breadcrumb-item text-capitalize">
            <h3>User Profile</h3> </li>
        <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
        <li class="breadcrumb-item"><a href="javascript:;">User Profile</a></li>
    </ul>
</div>
<div class="user-setting row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
        <div class="prtm-full-block text-center">
            <div class="pad-all-md">
                <div class="mrgn-b-lg">
                    @if(isset($userDetails->user_photo) &&  $userDetails->user_photo!="")
                        <img class="img-responsive img-circle display-ib" src="{{ asset("storage/$userDetails->user_photo") }}" id="preview_image" width="140" height="140" alt="user profile image">
                    @else
                        <img class="img-responsive img-circle display-ib" src="{{ asset('backend/theme/assets/img/dami-user.png') }}" id="preview_image" width="140" height="140" alt="user profile image">
                    @endif
                </div>
                    <form action="{{ route('upload_image') }}" method="post" enctype="multipart/form-data" id="upload_form">
                    {{ csrf_field() }}
                    <input type="file" id="file"/>
                    <input type="hidden" id="file_name"/>
                    </form>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-9">
        <div class="prtm-block">
            <div class="pad-b-md border-btm mrgn-b-lg">
                <h3 class="mrgn-all-none">Update profile</h3> </div>
                {!!
                    Form::open(
                        array(
                        'name' => 'frmUpdateProfile',
                        'id' => 'frmUpdateProfile',
                        'url' => route('update_profile'),
                        )
                    )
                !!}
                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 {{ $errors->has('first_name') ? ' has-error' : '' }}">
                            <label><span class="text-danger">*</span>First name</label>
                            {!!
                                Form::text('first_name',
                                isset($userDetails->first_name) ? $userDetails->first_name : '',
                                [
                                'class' => 'form-control',
                                'placeholder' => 'First name',
                                'id'=>'first_name'
                                ])
                            !!}
                            @if ($errors->has('first_name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('first_name') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 {{ $errors->has('last_name') ? ' has-error' : '' }}">
                            <label><span class="text-danger">*</span>Last name</label>
                            {!!
                                Form::text('last_name',
                                isset($userDetails->last_name) ? $userDetails->last_name : '',
                                [
                                'class' => 'form-control',
                                'placeholder' => 'Last name',
                                'id'=>'last_name',
                                ])
                            !!}
                            @if ($errors->has('last_name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('last_name') }}</strong>
                            </span>
                            @endif
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 {{ $errors->has('email') ? ' has-error' : '' }}">
                            <label><span class="text-danger">*</span>Email</label>
                            {!!
                                Form::text('email',
                                isset($userDetails->email) ? $userDetails->email : '',
                                [
                                'class' => 'form-control',
                                'placeholder' => 'Email',
                                'id'=>'email',
                                'readonly' => 'readonly'
                                ])
                            !!}
                            @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg">Save</button>
                </div>
             {!! Form::close() !!}
        </div>
    </div>
</div>
         
@endsection
@section('pageTitle')
Dashboard
@endsection
@section('jscript')
<script>
    var messages = {
        _token: "{{ csrf_token() }}",
        url: "{{ route('upload_image') }}",
        assets_path: "{{ asset('storage') }}",
    };
</script>
<script src="{{ asset('js/common/jquery.validate.js') }}"></script>
<script src="{{ asset('js/backend/validation/user.js') }}"></script>
@endsection