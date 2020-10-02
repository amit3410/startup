@extends('layouts.admin')

@section('content')

<div class="form-section">
    <div class="row marB30">
        <div class="col-md-10">
            <h3 class="h3-headline">Manage Other Documents</h3>
        </div>
        <div class="col-md-2"> <a class="btn btn-save btn-sm" href="{{route('add_other_document')}}">+ Add Document</a></div>
       
    </div>

    <?php 
    
    
    $curouteName = Route::currentRouteName(); ?>
    {!! Form::open(
    array(
    'name' => 'filterdoc',
    'id' => 'filterdoc',
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
                            {{ Form::text('doc_name', isset($filter['doc_name'])?$filter['doc_name']:'', ['class' => 'form-control','placeholder'=>'Search by Document Name', 'id'=> 'search_keyword']) }}
                            
                        </li>
                        <li>
                             {{ Form::select('doc_for',[''=>'Select Type']+Helpers::getDocForDropDown(), isset($filter['doc_for']) ? $filter['doc_for'] : '', ['class' => 'form-control', 'id' => 'doc_for']) }}
                            
                        </li>
                        <li>
                             {{ Form::select('type',[''=>'Select Type']+Helpers::getDocTypeDropDown(), isset($filter['type']) ? $filter['type'] : '', ['class' => 'form-control', 'id' => 'doc_for']) }}
                            
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
        <div class="d-md-flex mt-3 mb-3"></div>
    </div>
    {!! Form::close()!!}
    <div id="table_data">
        @include('backend.master.requested_document.pagination_data') 
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
</script>

@endsection
