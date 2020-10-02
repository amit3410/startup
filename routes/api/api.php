<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
   //return $request->user();
   

});


 Route::get(
                    'get-user-detail', [
                    'as' => 'get_user_detail',
                    'uses' => 'Api\UserController@detail'
                    ]
            );

 Route::post('apilogin', 'Api\Auth\LoginController@login');
 Route::post('apiregister', 'Api\Auth\RegisterController@apiusercreate');


/*--my Api Route -----*/
  Route::post('user_email', 'Api\Auth\ApiController@apiuseremail');
  Route::get('user_kyccompleted_approved', 'Api\Auth\ApiController@kycCompleteApproved');
/*--my Api Route End-----*/

 Route::group(['middleware' => 'auth.jwt'], function () {


 

            



});


   
        
        
        
        
        
      
