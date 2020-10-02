@extends('layouts.admin')

@section('content')





<div class="form-section">
    <div class="row marB10">
        <div class="col-md-12">
            <h3 class="h3-headline">Profile Monitoring</h3>
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
    <div class="filter">
        <div class="d-md-flex mt-3 mb-3">
            <div class="filter-bg">
                
                <ul class="filter-sec">
                        <li>
                            {{ Form::text('search_keyword', isset($filter['search_keyword'])?$filter['search_keyword']:'', ['class' => 'form-control','placeholder'=>'Search by First name, Last name and Email', 'id'=> 'search_keyword']) }}
                            
                        </li>
                        <li>
                             {{ Form::select('req_status',[''=>'Select Request Status']+Helpers::getReqStatusDropDown(), isset($filter['req_status']) ? $filter['req_status'] : '', ['class' => 'form-control', 'id' => 'req_status']) }}
                            
                        </li>
                        <li>
                             {{ Form::select('doc_status',[''=>'Select Profile Status']+Helpers::getDocStatusDropDown(), isset($filter['doc_status']) ? $filter['doc_status'] : '', ['class' => 'form-control', 'id' => 'doc_status']) }}

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
        @include('backend.profile_momnitoring_pagination') 
    </div>
    
</div>



@endsection
@section('pageTitle')
Profile Monitoring
@endsection
@section('jscript')
<script>
    $(document).ready(function () {

// $(document).on('click', '.pagination a', function(event){
//  event.preventDefault(); 
//  var page = $(this).attr('href').split('page=')[1];
//  fetch_data(page);
// });
        
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
             $('#req_status').val('');   
             $('#doc_status').val('');
            window.location.href = '{{ route($curouteName) }}';
        });
        
        
    });
    
    
</script>
<script src="{{ asset('frontend/inside/js/popper.min.js') }}"></script>
<script src="{{ asset('frontend/inside/js/bootstrap.min.js') }}"></script>
@endsection
