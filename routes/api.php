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

    /* Messages */
    Route::get('messages', 'MessagesController@index');
    Route::post('contact', 'MessagesController@contact')->middleware('banned');
    Route::post('replay', 'MessagesController@replay');
    Route::post('trashed', 'MessagesController@trashedItems');
    Route::post('messages/{id}', 'MessagesController@trashedItems');
    Route::post('messages/{id}/block', 'MessagesController@banned');
    Route::resource('messages','MessagesController')->except(['create', 'edit']);

    /* Users */
    Route::resource('users','UserController')->except(['create', 'edit', 'show'])->middleware('banned');
    Route::post('register', 'Auth\RegisterController@register')->middleware('banned');
    Route::post('login', 'Auth\LoginController@login')->middleware('banned');
    Route::post('logout', 'Auth\LoginController@logout');

    Route::post('auth-user', 'Auth\LoginController@getLoggedUser')->middleware('banned');
    Route::post('refresh', 'Auth\LoginController@refresh')->middleware('banned');
    // Route::post('me', 'Auth\LoginController@me');
    Route::patch('users/{user}/change_password', 'UserController@changePassword');
    Route::patch('users/{user}/change_email', 'UserController@changeEmail');
    
    /* Admins */
    Route::resource('admins','AdminController')->except(['create', 'edit', 'show']);
    Route::post('admin-register', 'Auth\RegisterAdminController@register');
    Route::post('auth-admin', 'AdminController@authAdmin');
    Route::post('admin-login', 'Auth\LoginAdminController@login');
    Route::post('admin-logout', 'Auth\LoginAdminController@logout');

    /* Events */
    Route::resource('events','ScheduleEventController')->except(['create', 'edit']);

    /* Customers */
    Route::resource('customers','CustomersController')->except(['create', 'edit']);

    /* Blog */
    Route::resource('articles','ArticleController')->except(['create', 'edit']);//->middleware('can:posts,App\Post');//->middleware('can:view,App\Post')//;

    /* Auth */
    // Auth::routes();
});