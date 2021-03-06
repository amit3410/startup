@extends('layouts.app')

@section('content')



<section>
    <div class="container">
        <div class="row">
            <div id="header" class="col-md-3">
               @include('layouts.user-inner.left-corp-menu')

            </div>
            
            <div class="col-md-9 dashbord-white">
                <div class="form-section">
                    <div class="row marB10">
                        <div class="w-100 thanku-bg m-auto d-flex h-70vh justify-content-center align-items-center">
                            <div class="thanku-page text-center">
                        	<i class="fa fa-thumbs-up"></i>
                            <h3 class="h3-headline"> Thank You</h3>
                          
                          <p align="justify"> 
                            {{$benifinary['user']->f_name.' '.$benifinary['user']->l_name}}, {{trans('forms.corperate_uploaddoc.success.thanks_msg')}}</p>	  
                          </div>   
                    </div>
                   </div>
                </div>
        </div>

        </div>
</div>
</section>
<style>
	.h-70vh{
		height: 70vh;
	}
  .thanku-bg {
    background: #531919;
   }
   .thanku-page i{
   	 font-size: 72px;
   }
    .thanku-page h3{
    color: #fff;
    font-size: 32px;
    margin: 20px 0px;
    }
    .thanku-page {
    width: 70%;
}



	   
</style>


@endsection
