@extends('layouts.admin')

@section('content')

<div class="form-section">
    <div class="row marB10">
        <div class="col-md-12">
            <h3 class="h3-headline">Manage Users</h3>
        </div>
    </div>
    <?php $curouteName = Route::currentRouteName(); ?>
    {!! Form::open(
    array(
    'name' => 'filtercustomer',
    'id' => 'filtercustomer',
    'autocomplete' => 'off',
    'class' => 'form',
    'url' => route($curouteName),
    'method' => 'get'
    )
    )
    !!}
    <div class="filter filter-section-cls">
        <div class="d-md-flex mt-3 mb-3">
            <div class="filter-bg">

                <ul class="filter-sec">
                    <li>
                        {{ Form::text('search_keyword', isset($filter['search_keyword'])?$filter['search_keyword']:'', ['class' => 'form-control','placeholder'=>'Search by First name, Last name and Email', 'id'=> 'search_keyword']) }}

                    </li>
                    <li>
                        {{ Form::select('kyc_status',[''=>'Select KYC Status']+Helpers::getKycStatusDropDown(), isset($filter['kyc_status']) ? $filter['kyc_status'] : '', ['class' => 'form-control', 'id' => 'kyc_status']) }}

                    </li>

                    <li>
                        {{ Form::select('user_source',[''=>'Select User Source']+Helpers::getuserSource(), isset($filter['user_source']) ? $filter['user_source'] : '', ['class' => 'form-control', 'id' => 'user_source']) }}

                    </li>
                    
                    <li>
                        {{ Form::select('profile_status',[''=>'Select Profile Status']+Helpers::getProfileStatusSource(), isset($filter['profile_status']) ? $filter['profile_status'] : '', ['class' => 'form-control', 'id' => 'profile_status']) }}

                    </li>

                    <li>
                        <button type="submit" value="search" class="btn btn-primary btn-sm"><i class="fa fa-search"></i></button>
                    </li>
                </ul>
            

            </div>
        <div class="ml-md-auto1 mt-1">
                <button type="button" class="btn btn-default btn-sm" id="reset">Clear Filters</button>
            </div>
        </div>
         

    </div>
    {!! Form::close()!!}
    <div id="table_data">
        @include('backend.pagination_data') 
    </div>

</div>
@endsection
@section('iframe')
<div class="modal" id="sendtoTrading">
    <div class="modal-dialog modal-lg custom-modal">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Send To Trading Plateform</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <iframe id ="frameset"  frameborder="0" style="background: #FFFFFF;"></iframe>
            </div>
        </div>
    </div>
</div>

@endsection
@section('pageTitle')
User list
@endsection
@section('jscript')
<script>
document.body.style.overflowX = "hidden";
    (function($){
    $(document).ready(function () {
        function fetch_data(page)
        {
            $.ajax({
                url: "/dashboard/user_paginate?page=" + page,
                success: function (data)
                {
                    $('#table_data').html(data);
                }
            });
        }

        $('#reset').click(function () {
            $('#search_keyword').val('');
            $('#kyc_status').val('');

            window.location.href = '{{ route($curouteName) }}';
        });

        
    });
})(jQuery);
</script>
<style>
  .filter-section-cls ul.filter-sec select {
    min-width: 165px;
  }
.filter-section-cls ul.filter-sec input {
    min-width: 305px;
}
</style>
<script src="{{ asset('backend/theme/assets/plugins/bootstrap/bootstrap.min.js') }}"></script>
@endsection
