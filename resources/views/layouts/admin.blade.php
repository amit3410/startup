<!DOCTYPE html>
<html class="no-js" lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes" />
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Page Title --}}
        <title>@yield('pageTitle')</title>

        {{-- Favicon --}}
        <link rel="shortcut icon" type="image/icon" href="{{ asset('frontend/outside/images/favicon.ico') }}">
        {{-- project theme css --}}

        <!--Datepicker-->
        <link rel='stylesheet' href="{{ asset('frontend/inside/plugin/datepicker/jquery-ui.css') }}">

        <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('backend/theme/assets/plugins/fontawesome/css/font-awesome.min.css')}}" type="text/css" />
        <link rel="stylesheet" href="{{ asset('backend/theme/assets/plugins/animate/animate.min.css')}}" type="text/css" />        
        <link rel="stylesheet" type="text/css" href="{{ asset('backend/theme/assets/plugins/datatables/css/datatables.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ asset('backend/theme/assets/plugins/datatables/css/dataTables.bootstrap.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ asset('backend/theme/assets/plugins/datatables/Buttons/css/buttons.dataTables.min.css')}}">
        <!--        <link rel="stylesheet" type="text/css" href="{{ asset('backend/theme/assets/css/pratham.min.css')}}">-->
        <!--<link rel="stylesheet" type="text/css" href="{{ asset('backend/theme/assets/css/backend-common.css')}}">-->
        <link rel="stylesheet" type="text/css" href="{{ asset('backend/theme/assets/css/site.css')}}">
        <link rel="stylesheet" type="text/css" href="{{ asset('backend/theme/assets/css/developer.css')}}">
        <!--<link href="{{ asset('frontend/inside/css/site.css') }}" rel="stylesheet">-->
        @yield('addtional_css')

    </head>
    <style>
        .sidebar-menu li > a:hover {
            background-color: #489fbb;
        }
        .sidebar-menu li.active > a {
            background-color: #48a0bc;
        }
        .table-border-top {
            padding: 5px;
        }
        input#delete_selected_user {
            margin-top: 10px;
            margin-bottom: 10px;
            margin-left: 10px;
        }
        .navbar {
            background-color: #48a0bc;
        }
        .btn-success.search:hover,  .btn-success.search:focus,  .btn-success.search:active ,.btn-success.search:active:hover {
            color: #fff;
            background-color: #20465a;
            border-color: #20465a;
        }
        .btn-success.search {
            color: #fff;
            background-color: #48a0bc;
            border-color: #48a0bc;
        }
        a {
            color: #48a0bc;
        }

        .mandatory {
            color: #e2440a;
            font-size: 12px;
            font-weight: 700;
        }
    </style>
    <?php $curouteName = Route::currentRouteName(); ?>
    <body>

        <header>
            <div class="container-fluid header-bg">
                <div class="logo">
                    <a href="{{url('/')}}"><img src="{{ asset('frontend/outside/images/00_dexter.svg') }}" class="img-responsive"></a>
                </div>
            </div>
        </header>
         <div id="my-loading" style="display:none;">
            <div class="square-blocks">
                <img src="{{ asset('frontend/inside/images/ajax-loader.gif') }}" alt="Loading...">
            </div>
        </div>


        <section>
            <div class="container-fluid">
                <div class="row">

                    <!--sidebar-->
                    <div id="header" class="col-md-2">
                        @include('layouts.user-inner.admin-left-menu')

                    </div>
                    <!--sidebar-->
                    <div class="col-md-10 dashbord-white">
                        @yield('content')
                    </div>
                </div>
            </div>
        </section>
        @yield('iframe')

        {{-- Theme JS --}}
        
        <script src="{{ asset('backend/theme/assets/plugins/jquery/jquery-2.2.4.min.js') }}" type="text/javascript"></script>
        <?php if( in_array(\Request::route()->getName() ,['individual_user', 'corporate_user','other_document'])){?>
        <script src="{{ asset('frontend/inside/js/popper.min.js') }}"></script>
        <script src="{{ asset('frontend/inside/js/bootstrap.min.js') }}"></script>
        <?php } ?>
        <script src="{{ asset('backend/theme/assets/plugins/jquery-ui/jquery-ui.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('backend/theme/assets/plugins/bootstrap/bootstrap.min.js') }}" type="text/javascript"></script>
        
        
        
        
        <script src="{{ asset('backend/theme/assets/plugins/bootstrap-hover/bootstrap-hover.js') }}" type="text/javascript"></script>
        <script src="{{ asset('backend/theme/assets/plugins/datatables/js/datatable.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('common/js/iframePopup.js') }}" type="text/javascript"></script>
        <script src="{{ asset('common/js/jquery.validate.js') }}" type="text/javascript"></script>
        
        <script>
        window.setTimeout(function () {
            $(".alert").fadeTo(500, 0).slideUp(500, function () {
                $(this).remove();
            });
        }, 2000);



$(function () {

    var curroute = "{{$curouteName}}";

    $(".submenu").hide();
    if (curroute == 'individual_user' || curroute == 'corporate_user' || curroute == 'user_detail' || curroute == 'corp_user_detail') {
        $(".mgr-user").show();
    } else {
        $(".mgr-user").hide();
    }

    $(".parent").click(function (e) {
        e.preventDefault();
        if (!$(e.target).closest("ul").is(".submenu")) {
            $(".submenu", this).toggle();
            $(this).siblings(".parent").find(".submenu").hide();
        }
    });
});
        </script>

        @yield('jscript')
        
    </body>
</html>
