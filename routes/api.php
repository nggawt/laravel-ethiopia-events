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
//Route::middleware('auth:api')->post('/customers', 'CustomerController');


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
    // Route::post('store', 'UserController@store');
	Route::get('messages', 'MessagesController@index');
    // Route::get('users', 'UserController@index');
    // Route::delete('users/{user}', 'UserController@destroy');
    
    Route::post('auth-user', 'UserController@getLoggedUser');
    Route::post('auth-admin', 'AdminController@authAdmin');
    Route::post('contact', 'UserController@contact');
    Route::resource('users','UserController')->except(['create', 'edit', 'show']);
    Route::patch('users/{user}/change_password', 'UserController@changePassword');
    Route::patch('users/{user}/change_email', 'UserController@changeEmail');

    // Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

    Route::resource('admins','AdminController')->except(['create', 'edit']);

    Route::post('admin-register', 'Auth\RegisterAdminController@register');
    Route::post('admin-login', 'Auth\LoginAdminController@login');
    Route::post('admin-logout', 'Auth\LoginAdminController@logout');

    Route::resource('events','ScheduleEventController')->except(['create', 'edit']);
    Route::resource('customers','CustomersController')->except(['create', 'edit']);
    Route::resource('blog','PostController')->except(['create', 'edit']);//->middleware('can:posts,App\Post');//->middleware('can:view,App\Post')//;
    Auth::routes();
});