@extends('layouts.withought_login')
@section('content')

<div class="d-flex justify-content-center align-items-center error-page">
    <div class="content-wrap height-auto p-4 pt-5 pb-5">
   

        <div class="logo-box text-center marB20">
            <a href="{{url('/')}}"><img src="{{ asset('frontend/outside/images/00_dexter.svg') }}" class="img-responsive"></a>
            <p class="text-left mt-4">
                @if(Session::has("error"))
                         <p>{{ Session::get('error') }}</p>
                @endif 
            </p>
        </div>
      
     </div>
</div>

@endsection



