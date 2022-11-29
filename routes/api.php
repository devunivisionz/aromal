<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/
//route for web




 
 /*Route::group(['namespace' => 'Driver', 'prefix' => 'driver/v1', 'middleware' => 'auth:device-api'], function() {

    // define your routes here for the "drivers"
});*/

/*Route::prefix('auth/device-api')->middleware('auth:device-api')->group(function () {

    Route::get('/getstate1' , function($request){
        dd($request->user());
    });

});

    Route::group(
        [
            'prefix'=>'auth/device'
        ],function ()
        {
            //routes for device auth
            Route::post('signupdevice','API\DeviceController@signupDevice');

            Route::post('logindevice','API\DeviceController@login');
             Route::get('getstates','API\DeviceController@getstates');

         Route::group(
             [
                 'middleware'=>'auth:device'
             ],function(){
              //all the routes that go  throught middleware
             
            //Route::get('/getstate' , function($request){
       
  //  });
            

           });
        }); */
