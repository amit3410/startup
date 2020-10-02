<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/icon" href="{{ asset('frontend/outside/images/favicon.ico') }}" />
    <!-- Bootstrap CSS -->
     <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('/frontend/outside/lending-asset/css/font.css') }}">
    <link rel="stylesheet" href="{{ asset('/frontend/outside/lending-asset/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/frontend/inside/css/site.css') }}">
 <link href="https://fonts.googleapis.com/css?family=Lato:400,700,700i&display=swap" rel="stylesheet">
    <title>DexterCapital</title>
</head>



<script src="{{ asset('frontend/outside/js/jquery-2.2.4.min.js') }}"></script>
<script src="{{ asset('common/js/jquery.validate.js') }}"></script>
<script type="text/javascript" src="{{ asset('frontend/outside/js/bootstrap.min.js') }}"></script>
<script>
$( document ).ready(function() {
  $('input.form-control').attr('autocomplete',true);
});

</script>

<!-- dashboard part -->
<body class="login-page">

    <header>
<div class="container">
<div class="row">
<div class="col-sm-5">
<span>BETA</span>
<div id="logo">
<a href="http://localhost/vantabay/index.php?route=common/home"><img src="http://localhost/vantabay/image/catalog/logo.png" title="Vantabay" alt="Vantabay" class="img-responsive" /></a>
</div>
</div>
<div class="col-sm-7"><p>Find the <span style="color:#7ED321;">best deals</span> and compare prices</p>
</div>

</div>
<div class="row">
<div class="col-sm-12">
<div id="search" class="input-group">
<input type="text" name="search" value="" placeholder="What are you looking for?" class="form-control input-lg" />
<span class="input-group-btn">
<button type="button" class="btn btn-default btn-lg"><i class="fa fa-search"></i></button>
</span>
</div>
<!--<div class="absolute1"><a href="index.php?route=product/dailydeals" id="wishlist-total" title="Daily Deals" class="wishlist-show">Daily Deals</a></div>
<div class="absolute1"><a href="index.php?route=product/weeklydeals" id="wishlist-total" title="Daily Deals" class="wishlist-show">Weekly Deals</a></div>-->
<div class="divCmain">
<div class="divclass" style="margin-left: 5px;">
<a href="index.php?route=product/dailydeals" id="wishlist-total" title="Daily Deals" class="wishlist-show">Daily Deals</a>
</div>
<div class="divclass" style="margin-left: 5px;">
<a href="index.php?route=product/weeklydeals" id="wishlist-total" title="Daily Deals" class="wishlist-show">Weekly Deals</a>
</div >
</div>
</div>
</div>
</div>
</header>
<section>

    
    @yield('content')
 
</section>

<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/5f046da1760b2b560e6fe609/default';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->

</body>

</html>


