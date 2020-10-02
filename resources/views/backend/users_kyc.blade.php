@extends('layouts.admin')

@section('content')




<link href="{{ asset('frontend/inside/css/site.css') }}" rel="stylesheet">
<div class="form-section">
    <div class="row marB10">
        <div class="col-md-12">
            <h3 class="h3-headline">Manage Users</h3>
        </div>
    </div>
    <?php $curouteName=Route::currentRouteName();?>
                
    <div class="filter">
        <div class="d-md-flex mt-3 mb-3">
            {!! Form::open(
                array(
                'name' => 'filtercustomer',
                'id' => 'filtercustomer',
                'autocomplete' => 'off',
                'class' => 'form',
                'url' => route($curouteName),
                'method' => 'post'
                )
                )
                !!}
            <div class="filter-bg">
                
                <ul class="filter-sec">
                        <li>
                            {{ Form::text('search_keyword', isset($search_keyword)?$search_keyword:'', ['class' => 'form-control','placeholder'=>'Search by First name, Last name and Email', 'id'=> 'search_keyword']) }}
                            
                        </li>
                        <li>
                             {{ Form::select('kyc_status', [''=>'Select KYC Status','0'=>'Incomplete', '1'=>'Approved', '2'=>'Pending',
                        '3'=>'Disapproved'], isset($kyc_status) ? $kyc_status : '', ['class' => 'form-control', 'id' => 'kyc_status']) }}
                            
                        </li>
                        <li>
                           <button type="submit" value="search" class="btn btn-primary btn-sm"><i class="fa fa-search"></i></button>
                        </li>
                    </ul>
                
               
            </div>
            <div class="ml-md-auto1 mt-1">
                <button type="button" class="btn btn-default btn-sm">Clear Filters</button>
            </div>
             {!! Form::close()!!}
        </div>
    </div>

    
 
    <div id="table_data">
        @include('backend.pagination_data') 
    </div>
</div>



@endsection
@section('pageTitle')
User list
@endsection
@section('jscript')
<script>
    $(document).ready(function () {

// $(document).on('click', '.pagination a', function(event){
//  event.preventDefault(); 
//  var page = $(this).attr('href').split('page=')[1];
//  fetch_data(page);
// });

//        function fetch_data(page)
//        {
//            $.ajax({
//                url: "/dashboard/user_paginate?page=" + page,
//                success: function (data)
//                {
//                    $('#table_data').html(data);
//                }
//            });
//        }

    });
</script>
<script src="{{ asset('frontend/inside/js/popper.min.js') }}"></script>
<script src="{{ asset('frontend/inside/js/bootstrap.min.js') }}"></script>
@endsection
