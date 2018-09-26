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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
//// });
//Route::middleware('auth:api')->post('/costumers', 'CostumerController');


/*Route::group([

    'middleware' => 'api',
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});*/

Route::group([
	// 'middleware' => 'cors',
    // 'namespace' => 'App\Http\Controllers',
    // 'prefix' => 'auth'

], function () {
	// Route::post('getlogin', 'UserController@getLogin');
	Route::post('store', 'UserController@store');
    Route::post('isauth', 'UserController@getUserLogged');

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::resource('costumers','CostumersController');
});