@extends('layouts.withought_login')
@section('content')
<div class="container">
<div class="row"> <div id="content" class="col-sm-12">
<style>
.ebaybye{
content: "";
background: url(catalog/view/theme/default/image/ebay.png) no-repeat;
position:absolute;
top: 445;
left: 0;
width: 50%;
height: 130px;
}
.ebayUrl {
width: 80px;
float: right;
margin: 1px 4px 0 0;
}
.ebayUrl a {
padding: 0px 9px 5px 13px;
display: inline-block;
background: #7ED321;
color: #fff;
text-transform: uppercase;
border-radius: 3px;
}
</style>
<h3 style="margin-top:50px;">Amazon Deals</h3>
<!--<div class="product-layout col-lg-3 col-md-3 col-sm-6 col-xs-12">
<div class="row">
<div class="product-thumb transition">
<div class="image"><a href="http://localhost/vantabay/index.php?route=product/product&amp;product_id=43"><img src="http://localhost/vantabay/image/cache/catalog/demo/macbook_1-200x200.jpg" alt="MacBook" title="MacBook" class="img-responsive" /></a></div>
<div class="caption">
<h4><a href="http://localhost/vantabay/index.php?route=product/product&amp;product_id=43">MacBook</a></h4>
<p>
Intel Core 2 Duo processor
Powered by an Intel Core 2 Duo processor at speeds up to 2.1..</p>
<p class="price">
$602.00 <span class="price-tax">Ex Tax: $500.00</span>
</p>
</div>
<div class="button-group">
<button type="button" onclick="cart.add('43');"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md">Add to Cart</span></button>
<button type="button" data-toggle="tooltip" title="Add to Wish List" onclick="wishlist.add('43');"><i class="fa fa-heart"></i></button>
<button type="button" data-toggle="tooltip" title="Compare this Product" onclick="compare.add('43');"><i class="fa fa-exchange"></i></button>
</div>
</div>
</div>
<div class="product-thumb transition">
<div class="image"><a href="http://localhost/vantabay/index.php?route=product/product&amp;product_id=40"><img src="http://localhost/vantabay/image/cache/catalog/demo/iphone_1-200x200.jpg" alt="iPhone" title="iPhone" class="img-responsive" /></a></div>
<div class="caption">
<h4><a href="http://localhost/vantabay/index.php?route=product/product&amp;product_id=40">iPhone</a></h4>
<p>
iPhone is a revolutionary new mobile phone that allows you to make a call by simply tapping a n..</p>
<p class="price">
$123.20 <span class="price-tax">Ex Tax: $101.00</span>
</p>
</div>
<div class="button-group">
<button type="button" onclick="cart.add('40');"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md">Add to Cart</span></button>
<button type="button" data-toggle="tooltip" title="Add to Wish List" onclick="wishlist.add('40');"><i class="fa fa-heart"></i></button>
<button type="button" data-toggle="tooltip" title="Compare this Product" onclick="compare.add('40');"><i class="fa fa-exchange"></i></button>
</div>
</div>
</div>
<div class="product-thumb transition">
<div class="image"><a href="http://localhost/vantabay/index.php?route=product/product&amp;product_id=42"><img src="http://localhost/vantabay/image/cache/catalog/demo/apple_cinema_30-200x200.jpg" alt="Apple Cinema 30&quot;" title="Apple Cinema 30&quot;" class="img-responsive" /></a></div>
<div class="caption">
<h4><a href="http://localhost/vantabay/index.php?route=product/product&amp;product_id=42">Apple Cinema 30&quot;</a></h4>
<p>
The 30-inch Apple Cinema HD Display delivers an amazing 2560 x 1600 pixel resolution. Designed sp..</p>
<p class="price">
<span class="price-new">$110.00</span> <span class="price-old">$122.00</span>
<span class="price-tax">Ex Tax: $90.00</span>
</p>
</div>
<div class="button-group">
<button type="button" onclick="cart.add('42');"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md">Add to Cart</span></button>
<button type="button" data-toggle="tooltip" title="Add to Wish List" onclick="wishlist.add('42');"><i class="fa fa-heart"></i></button>
<button type="button" data-toggle="tooltip" title="Compare this Product" onclick="compare.add('42');"><i class="fa fa-exchange"></i></button>
</div>
</div>
</div>
<div class="product-thumb transition">
<div class="image"><a href="http://localhost/vantabay/index.php?route=product/product&amp;product_id=30"><img src="http://localhost/vantabay/image/cache/catalog/demo/canon_eos_5d_1-200x200.jpg" alt="Canon EOS 5D" title="Canon EOS 5D" class="img-responsive" /></a></div>
<div class="caption">
<h4><a href="http://localhost/vantabay/index.php?route=product/product&amp;product_id=30">Canon EOS 5D</a></h4>
<p>
Canon's press material for the EOS 5D states that it 'defines (a) new D-SLR category', while we'r..</p>
<p class="price">
<span class="price-new">$98.00</span> <span class="price-old">$122.00</span>
<span class="price-tax">Ex Tax: $80.00</span>
</p>
</div>
<div class="button-group">
<button type="button" onclick="cart.add('30');"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md">Add to Cart</span></button>
<button type="button" data-toggle="tooltip" title="Add to Wish List" onclick="wishlist.add('30');"><i class="fa fa-heart"></i></button>
<button type="button" data-toggle="tooltip" title="Compare this Product" onclick="compare.add('30');"><i class="fa fa-exchange"></i></button>
</div>
</div>
</div>
<div class="product-layout col-lg-2 col-md-3 col-sm-6 col-xs-12" >
<div class="product-thumb transition">
<iframe style="width:120px;height:240px;margin-left: 25px;" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" src="//ws-na.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&OneJS=1&Operation=GetAdHtml&MarketPlace=US&source=ac&ref=tf_til&ad_type=product_link&tracking_id=vantabay-20&marketplace=amazon&region=US&placement=B071YV6RXD&asins=B071YV6RXD&linkId=c1201706aeebf8f4c7da7c19ce869481&show_border=false&link_opens_in_new_window=false&price_color=333333&title_color=0066c0&bg_color=ffffff">
</iframe>
</div>
</div>
<div class="product-layout col-lg-2 col-md-3 col-sm-6 col-xs-12" >
<div class="product-thumb transition">
<iframe style="width:120px;height:240px;margin-left: 25px;" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" src="//ws-na.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&OneJS=1&Operation=GetAdHtml&MarketPlace=US&source=ac&ref=tf_til&ad_type=product_link&tracking_id=vantabay-20&marketplace=amazon&region=US&placement=B071YV6RXD&asins=B071YV6RXD&linkId=c1201706aeebf8f4c7da7c19ce869481&show_border=false&link_opens_in_new_window=false&price_color=333333&title_color=0066c0&bg_color=ffffff">
</iframe>
</div>
</div>
<div class="product-layout col-lg-2 col-md-3 col-sm-6 col-xs-12" >
<div class="product-thumb transition">
<iframe style="width:120px;height:240px;margin-left: 25px;" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" src="//ws-na.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&OneJS=1&Operation=GetAdHtml&MarketPlace=US&source=ac&ref=tf_til&ad_type=product_link&tracking_id=vantabay-20&marketplace=amazon&region=US&placement=B071YV6RXD&asins=B071YV6RXD&linkId=c1201706aeebf8f4c7da7c19ce869481&show_border=false&link_opens_in_new_window=false&price_color=333333&title_color=0066c0&bg_color=ffffff">
</iframe>
</div>
</div>
<div class="product-layout col-lg-2 col-md-3 col-sm-6 col-xs-12" >
<div class="product-thumb transition">
<iframe style="width:120px;height:240px;margin-left: 25px;" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" src="//ws-na.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&OneJS=1&Operation=GetAdHtml&MarketPlace=US&source=ac&ref=tf_til&ad_type=product_link&tracking_id=vantabay-20&marketplace=amazon&region=US&placement=B071YV6RXD&asins=B071YV6RXD&linkId=c1201706aeebf8f4c7da7c19ce869481&show_border=false&link_opens_in_new_window=false&price_color=333333&title_color=0066c0&bg_color=ffffff">
</iframe>
</div>
</div>
<div class="product-layout col-lg-2 col-md-3 col-sm-6 col-xs-12">
<div class="product-thumb transition">
<iframe style="width:120px;height:240px;margin-left: 25px;" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" src="//ws-na.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&OneJS=1&Operation=GetAdHtml&MarketPlace=US&source=ac&ref=tf_til&ad_type=product_link&tracking_id=vantabay-20&marketplace=amazon&region=US&placement=B071YV6RXD&asins=B071YV6RXD&linkId=c1201706aeebf8f4c7da7c19ce869481&show_border=false&link_opens_in_new_window=false&price_color=333333&title_color=0066c0&bg_color=ffffff">
</iframe>
</div>
</div>
<div class="product-layout col-lg-2 col-md-3 col-sm-6 col-xs-12">
<div class="product-thumb transition">
<iframe style="width:120px;height:240px;margin-left: 25px;" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" src="//ws-na.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&OneJS=1&Operation=GetAdHtml&MarketPlace=US&source=ac&ref=tf_til&ad_type=product_link&tracking_id=vantabay-20&marketplace=amazon&region=US&placement=B071YV6RXD&asins=B071YV6RXD&linkId=c1201706aeebf8f4c7da7c19ce869481&show_border=false&link_opens_in_new_window=false&price_color=333333&title_color=0066c0&bg_color=ffffff">
</iframe>
</div>
</div>
</div>-->
<div id="carousel0" class="owl-carousel">
<div class="item text-center">
<div class="product-thumb transition">
<iframe style="width:120px;height:240px;" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" src="//ws-na.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&OneJS=1&Operation=GetAdHtml&MarketPlace=US&source=ac&ref=qf_sp_asin_til&ad_type=product_link&tracking_id=vantabay-20&marketplace=amazon&region=US&placement=B07C5JWRMW&asins=B07C5JWRMW&linkId=cdb8440ba0fd3477db3f028f895aac92&show_border=false&link_opens_in_new_window=true&price_color=333333&title_color=0066c0&bg_color=ffffff">
</iframe>
</div>
</div>
<div class="item text-center">
<div class="product-thumb transition">
<iframe style="width:120px;height:240px;" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" src="//ws-na.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&OneJS=1&Operation=GetAdHtml&MarketPlace=US&source=ac&ref=qf_sp_asin_til&ad_type=product_link&tracking_id=vantabay-20&marketplace=amazon&region=US&placement=B079HBWDNG&asins=B079HBWDNG&linkId=b94b56f5dd218a3f4b35016f44903436&show_border=false&link_opens_in_new_window=true&price_color=333333&title_color=0066c0&bg_color=ffffff">
</iframe>
</div>
</div>
<div class="item text-center">
<div class="product-thumb transition">
<iframe style="width:120px;height:240px;" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" src="//ws-na.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&OneJS=1&Operation=GetAdHtml&MarketPlace=US&source=ac&ref=qf_sp_asin_til&ad_type=product_link&tracking_id=vantabay-20&marketplace=amazon&region=US&placement=B0006ZTEQK&asins=B0006ZTEQK&linkId=1ce8ab78cb63b4474341e13e4beb2a83&show_border=false&link_opens_in_new_window=true&price_color=333333&title_color=0066c0&bg_color=ffffff">
</iframe>
</div>
</div>
<div class="item text-center">
<div class="product-thumb transition">
<iframe style="width:120px;height:240px;" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" src="//ws-na.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&OneJS=1&Operation=GetAdHtml&MarketPlace=US&source=ac&ref=tf_til&ad_type=product_link&tracking_id=vantabay-20&marketplace=amazon&region=US&placement=B00ZO4WWBM&asins=B00ZO4WWBM&linkId=c2e1e330f65198e1452cda8cff02a1b5&show_border=false&link_opens_in_new_window=true&price_color=333333&title_color=0066c0&bg_color=ffffff">
</iframe>
</div>
</div>
<div class="item text-center">
<div class="product-thumb transition">
<iframe style="width:120px;height:240px;" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" src="//ws-na.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&OneJS=1&Operation=GetAdHtml&MarketPlace=US&source=ac&ref=tf_til&ad_type=product_link&tracking_id=vantabay-20&marketplace=amazon&region=US&placement=B00HSGGZLC&asins=B00HSGGZLC&linkId=c7c66b82267dbc403d2b7f3210d530bd&show_border=false&link_opens_in_new_window=true&price_color=333333&title_color=0066c0&bg_color=ffffff">
</iframe>
</div>
</div>
<div class="item text-center">
<div class="product-thumb transition">
<iframe style="width:120px;height:240px;margin-left: 25px;" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" src="//ws-na.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&OneJS=1&Operation=GetAdHtml&MarketPlace=US&source=ac&ref=tf_til&ad_type=product_link&tracking_id=vantabay-20&marketplace=amazon&region=US&placement=B071YV6RXD&asins=B071YV6RXD&linkId=c1201706aeebf8f4c7da7c19ce869481&show_border=false&link_opens_in_new_window=true&price_color=333333&title_color=0066c0&bg_color=ffffff">
</iframe>
</div>
</div>
<div class="item text-center">
<div class="product-thumb transition">
<iframe style="width:120px;height:240px;" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" src="//ws-na.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&OneJS=1&Operation=GetAdHtml&MarketPlace=US&source=ac&ref=tf_til&ad_type=product_link&tracking_id=vantabay-20&marketplace=amazon&region=US&placement=B01GEW27DA&asins=B01GEW27DA&linkId=9363f066595e8a95dc3f27727777a85d&show_border=false&link_opens_in_new_window=true&price_color=333333&title_color=0066c0&bg_color=ffffff">
</iframe>
</div>
</div>
<div class="item text-center">
<div class="product-thumb transition">
<iframe style="width:120px;height:240px;" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" src="//ws-na.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&OneJS=1&Operation=GetAdHtml&MarketPlace=US&source=ac&ref=tf_til&ad_type=product_link&tracking_id=vantabay-20&marketplace=amazon&region=US&placement=B01NAW98VS&asins=B01NAW98VS&linkId=4d74dff3d66b88255bd9a74d42958e33&show_border=false&link_opens_in_new_window=true&price_color=333333&title_color=0066c0&bg_color=ffffff">
</iframe>
</div>
</div>
<div class="item text-center">
<div class="product-thumb transition">
<iframe style="width:120px;height:240px;" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" src="//ws-na.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&OneJS=1&Operation=GetAdHtml&MarketPlace=US&source=ac&ref=tf_til&ad_type=product_link&tracking_id=vantabay-20&marketplace=amazon&region=US&placement=B00YD54HZ2&asins=B00YD54HZ2&linkId=a710794b448c4cdf0a985fb2c32369f3&show_border=false&link_opens_in_new_window=true&price_color=333333&title_color=0066c0&bg_color=ffffff">
</iframe>
</div>
</div>
<div class="item text-center">
<div class="product-thumb transition">
<iframe style="width:120px;height:240px;" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" src="//ws-na.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&OneJS=1&Operation=GetAdHtml&MarketPlace=US&source=ac&ref=tf_til&ad_type=product_link&tracking_id=vantabay-20&marketplace=amazon&region=US&placement=B01M311ACN&asins=B01M311ACN&linkId=9fabdff1348a1a22e12817e7a3f10808&show_border=false&link_opens_in_new_window=true&price_color=333333&title_color=0066c0&bg_color=ffffff">
</iframe>
</div>
</div>
<div class="item text-center">
<div class="product-thumb transition">
<iframe style="width:120px;height:240px;" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" src="//ws-na.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&OneJS=1&Operation=GetAdHtml&MarketPlace=US&source=ac&ref=tf_til&ad_type=product_link&tracking_id=vantabay-20&marketplace=amazon&region=US&placement=B0787P86ZZ&asins=B0787P86ZZ&linkId=a1c82b67ca3438fb6147f9840168a633&show_border=false&link_opens_in_new_window=true&price_color=333333&title_color=0066c0&bg_color=ffffff">
</iframe>
</div>
</div>
<div class="item text-center">
<div class="product-thumb transition">
<iframe style="width:120px;height:240px;" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" src="//ws-na.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&OneJS=1&Operation=GetAdHtml&MarketPlace=US&source=ac&ref=tf_til&ad_type=product_link&tracking_id=vantabay-20&marketplace=amazon&region=US&placement=B078GD3DRG&asins=B078GD3DRG&linkId=5ddaa9cb23bad18d1df0bee51814b72e&show_border=false&link_opens_in_new_window=true&price_color=333333&title_color=0066c0&bg_color=ffffff">
</iframe>
</div>
</div>
<div class="item text-center">
<div class="product-thumb transition">
<iframe style="width:120px;height:240px;" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" src="//ws-na.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&OneJS=1&Operation=GetAdHtml&MarketPlace=US&source=ac&ref=tf_til&ad_type=product_link&tracking_id=vantabay-20&marketplace=amazon&region=US&placement=B00006IEI4&asins=B00006IEI4&linkId=3f8ae820f817f13ebd4acc78cd0535c7&show_border=false&link_opens_in_new_window=true&price_color=333333&title_color=0066c0&bg_color=ffffff">
</iframe>
</div>
</div>
<div class="item text-center">
<div class="product-thumb transition">
<iframe style="width:120px;height:240px;" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" src="//ws-na.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&OneJS=1&Operation=GetAdHtml&MarketPlace=US&source=ac&ref=tf_til&ad_type=product_link&tracking_id=vantabay-20&marketplace=amazon&region=US&placement=B00FMWWN6U&asins=B00FMWWN6U&linkId=652caa7844bdacb7f29c7c1bb09e0916&show_border=false&link_opens_in_new_window=true&price_color=333333&title_color=0066c0&bg_color=ffffff">
</iframe>
</div>
</div>
<div class="item text-center">
<div class="product-thumb transition">
<iframe style="width:120px;height:240px;" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" src="//ws-na.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&OneJS=1&Operation=GetAdHtml&MarketPlace=US&source=ac&ref=tf_til&ad_type=product_link&tracking_id=vantabay-20&marketplace=amazon&region=US&placement=B000820YD8&asins=B000820YD8&linkId=c5838bffaf0faa8b838e562b7d83e65b&show_border=false&link_opens_in_new_window=true&price_color=333333&title_color=0066c0&bg_color=ffffff">
</iframe>
</div>
</div>
<div class="item text-center">
<div class="product-thumb transition">
<iframe style="width:120px;height:240px;" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" src="//ws-na.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&OneJS=1&Operation=GetAdHtml&MarketPlace=US&source=ac&ref=tf_til&ad_type=product_link&tracking_id=vantabay-20&marketplace=amazon&region=US&placement=B0018OMIPC&asins=B0018OMIPC&linkId=e48eb11f3d588d7e8b439a9d7558c562&show_border=false&link_opens_in_new_window=true&price_color=333333&title_color=0066c0&bg_color=ffffff">
</iframe>
</div>
</div>
</div>
<h3 style="margin-top:50px;">eBay Deals</h3>
<div id="carousel0ebay" class="owl-carousel">
<b>Notice</b>: Trying to get property of non-object in <b>E:\xampp\htdocs\vantabay\catalog\view\theme\default\template\extension\module\featured.tpl</b> on line <b>266</b><b>Warning</b>: Invalid argument supplied for foreach() in <b>E:\xampp\htdocs\vantabay\catalog\view\theme\default\template\extension\module\featured.tpl</b> on line <b>266</b>
</div>
<h3 style="margin-top:50px;">Walmart Deals</h3>
<div id="carousel0walmart" class="owl-carousel">
<div class="item text-center">
<div class="product-thumb transition">
<!--<a href="http://linksynergy.walmart.com/fs-bin/click?id=LcNJrOrG00g&offerid=223073.10007213&subid=0&type=4" target="_blank"><IMG border="0" alt="Wal-Mart.com USA, LLC" src="http://ad.linksynergy.com/fs-bin/show?id=LcNJrOrG00g&bids=223073.10007213&subid=0&type=4&gridnum=13"><img src="//beacon.affil.walmart.com/affil/ttap.gif?affillt=4&affilwmls=LcNJrOrG00g&affilsid=0&affiloid=223073.10007213" /></a>-->
<a href="http://linksynergy.walmart.com/fs-bin/click?id=LcNJrOrG00g&offerid=223073.10007239&subid=0&type=4"><IMG border="0" alt="Wal-Mart.com USA, LLC" src="http://ad.linksynergy.com/fs-bin/show?id=LcNJrOrG00g&bids=223073.10007239&subid=0&type=4&gridnum=8"><img src="//beacon.affil.walmart.com/affil/ttap.gif?affillt=4&affilwmls=LcNJrOrG00g&affilsid=0&affiloid=223073.10007239" style="display:none;"/></a>
</div>
</div>
<div class="item text-center">
<div class="product-thumb transition">
<a href="http://linksynergy.walmart.com/fs-bin/click?id=LcNJrOrG00g&offerid=223073.10007215&subid=0&type=4" target="_blank"><IMG border="0" alt="Wal-Mart.com USA, LLC" src="http://ad.linksynergy.com/fs-bin/show?id=LcNJrOrG00g&bids=223073.10007215&subid=0&type=4&gridnum=8"><img src="//beacon.affil.walmart.com/affil/ttap.gif?affillt=4&affilwmls=LcNJrOrG00g&affilsid=0&affiloid=223073.10007215" /></a>
</div>
</div>
<div class="item text-center">
<div class="product-thumb transition">
<a href="http://linksynergy.walmart.com/fs-bin/click?id=LcNJrOrG00g&offerid=223073.10005386&subid=0&type=4" target="_blank"><IMG border="0" alt="Wal-Mart.com USA, LLC" src="http://ad.linksynergy.com/fs-bin/show?id=LcNJrOrG00g&bids=223073.10005386&subid=0&type=4&gridnum=8"><img src="//beacon.affil.walmart.com/affil/ttap.gif?affillt=4&affilwmls=LcNJrOrG00g&affilsid=0&affiloid=223073.10005386"/></a>
</div>
</div>
<div class="item text-center">
<div class="product-thumb transition">
<a href="http://linksynergy.walmart.com/link?id=LcNJrOrG00g&offerid=223073.19218410&type=2&murl=http%3A%2F%2Fwww.walmart.com%2Fip%2FGigaTent-Cooper-2-7-x-7-Dome-Tent-Sleeps-3-4%2F19218410" target="_blank"><IMG border=0 src="https://i5.walmartimages.com/asr/3ffb9f4a-c9ca-490c-910b-c4eefa62d4fc_1.184b178d6a32f7bf085a78e7d545cc38.jpeg?odnHeight=200&odnWidth=200&odnBg=ffffff" ></a><IMG border=0 width=1 height=1 src="http://ad.linksynergy.com/fs-bin/show?id=LcNJrOrG00g&bids=223073.19218410&type=2&subid=0" >
</div>
</div>
</div>
<script type="text/javascript"><!--
$('#carousel0').owlCarousel({
items: 6,
autoPlay: false,
navigation: true,
navigationText: ['<i class="fa fa-chevron-left fa-5x"></i>', '<i class="fa fa-chevron-right fa-5x"></i>'],
pagination: true
});
$('#carousel0ebay').owlCarousel({
items: 6,
autoPlay: false,
navigation: true,
navigationText: ['<i class="fa fa-chevron-left fa-5x"></i>', '<i class="fa fa-chevron-right fa-5x"></i>'],
pagination: true
});
$('#carousel0walmart').owlCarousel({
items: 6,
autoPlay: false,
navigation: true,
navigationText: ['<i class="fa fa-chevron-left fa-5x"></i>', '<i class="fa fa-chevron-right fa-5x"></i>'],
pagination: true
});
--></script></div>
</div>
</div>
<footer>
<div class="container">
<div class="row">
<div class="col-sm-3">
<h5>Information</h5>
<ul class="list-unstyled">
<li><a href="http://localhost/vantabay/index.php?route=information/information&amp;information_id=4">About Us</a></li>
<li><a href="http://localhost/vantabay/index.php?route=information/information&amp;information_id=6">Delivery Information</a></li>
<li><a href="http://localhost/vantabay/index.php?route=information/information&amp;information_id=3">Privacy Policy</a></li>
<li><a href="http://localhost/vantabay/index.php?route=information/information&amp;information_id=5">Terms &amp; Conditions</a></li>
</ul>
</div>
<div class="col-sm-3">
<h5>Customer Service</h5>
<ul class="list-unstyled">
<li><a href="http://localhost/vantabay/index.php?route=information/contact">Contact Us</a></li>
<!--<li><a href="http://localhost/vantabay/index.php?route=account/return/add">Returns</a></li>
<li><a href="http://localhost/vantabay/index.php?route=information/sitemap">Site Map</a></li>-->
</ul>
</div>
<!--<div class="col-sm-3">
<h5>Extras</h5>
<ul class="list-unstyled">
<li><a href="http://localhost/vantabay/index.php?route=product/manufacturer">Brands</a></li>
<li><a href="http://localhost/vantabay/index.php?route=account/voucher">Gift Certificates</a></li>
<li><a href="http://localhost/vantabay/index.php?route=affiliate/account">Affiliates</a></li>
<li><a href="http://localhost/vantabay/index.php?route=product/special">Specials</a></li>
</ul>
</div>
<div class="col-sm-3">
<h5>My Account</h5>
<ul class="list-unstyled">
<li><a href="http://localhost/vantabay/index.php?route=account/account">My Account</a></li>
<li><a href="http://localhost/vantabay/index.php?route=account/order">Order History</a></li>
<li><a href="http://localhost/vantabay/index.php?route=account/wishlist">Wish List</a></li>
<li><a href="http://localhost/vantabay/index.php?route=account/newsletter">Newsletter</a></li>
</ul>
</div> -->
</div>
</div>
</footer>
@endsection