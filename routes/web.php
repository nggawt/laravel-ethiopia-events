<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Route::get('/api/home', ['middleware' => 'cors',function(){
	
// 	return ['status'=>'success']->toJson();
// }]);

Route::get('/', function () {
	//return {"hiuiidsfijkkjdsf":"kjkjkjkjkj"};
	// return User::all()->toJson();
	//return redirect()->guest('/app');
    // return view('wellcome');
});
// Route::group(['middleware' => 'cors'], function() {
//    Route::get('/api/home', function () {
// 	   return ['status'=>'success'];
// 	});
// });
// Auth::routes();
//Route::post('/login', 'UserController@getLogin')->name('login');
//Route::get('/customers', 'CustomerController@index')->name('customers');

// Route::resource('customers','CustomersController');
// Route::group(['middleware' => 'cors'], function() {
// 	// return User::all()->toJson();
//    //Route::post('/api/your_url','YourController@function' );
   
// });

