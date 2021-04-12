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

Route::post('uploadSales', 'API\ApiController@uploadSales');
Route::post('cancelSales', 'API\ApiController@cancelSales');
Route::post('changeStatusIpla', 'API\ApiController@changeStatusIpla');
Route::get('changeStatusIpla', function () {
    return '';
});
Route::post('sftpresponse', 'API\ApiController@sftpResponse');
Route::get('sftpresponse', function () {
    return '';
});
Route::post('viamaticaresponse', 'API\ApiController@viamaticaResponse');
Route::get('viamaticaresponse', function () {
    return '';
});
Route::post('emisionresponse', 'API\ApiController@ssEmisionResponse');
Route::get('emisionresponse', function () {
    return '';
});
Route::post('tokenApp', 'API\ApiController@tokenApp');
Route::get('tokenApp', function () {
    return '';
});
Route::post('loginApp', 'API\ApiController@loginApp');
Route::get('loginApp', function () {
    return '';
});
Route::post('customerApp', 'API\ApiController@customerApp');
Route::get('customerApp', function () {
    return '';
});
Route::post('vehiclesApp', 'API\ApiController@vehiclesApp');
Route::get('vehiclesApp', function () {
    return '';
});
Route::post('productsR1App', 'API\ApiController@productsR1App');
Route::get('productsR1App', function () {
    return '';
});
Route::post('productsR2App', 'API\ApiController@productsR2App');
Route::get('productsR2App', function () {
    return '';
});
Route::post('productsR4App', 'API\ApiController@productsR4App');
Route::get('productsR4App', function () {
    return '';
});
Route::post('rubrosR4App', 'API\ApiController@rubrosR4App');
Route::get('rubrosR4App', function () {
    return '';
});
Route::post('linksApp', 'API\ApiController@linksApp');
Route::get('linksApp', function () {
    return '';
});
Route::post('salesApp', 'API\ApiController@salesApp');
Route::get('salesApp', function () {
    return '';
});
Route::post('inspections', 'API\ApiController@inspections');
Route::get('inspections', function () {
    return '';
});
//Route::post('storeUploadSales', 'API\ApiController@storeUploadSales');
//Route::post('register', 'API\UserController@register');
//
//Route::group(['middleware' => 'auth:api'], function() {
//    Route::post('details', 'API\UserController@details');
//});

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

//Route::post('/uploadSales', 'SalesController@uploadSales')->name('sales.uploadSaless');
