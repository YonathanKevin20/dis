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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {
	// Route::group(['prefix' => 'book'], function() {
	// 	Route::get('get-data', 'BookController@getData');
	// 	Route::get('get-chart', 'BookController@getChart');
	// 	Route::post('import', 'BookController@import');
	// 	Route::get('download-template-xlsx', 'BookController@downloadTemplateXlsx');
	// 	Route::get('download-template-csv', 'BookController@downloadTemplateCsv');
	// });

	Route::group(['prefix' => 'product'], function() {
		Route::get('get-data', 'ProductController@getData');
	});

	Route::group(['prefix' => 'user'], function() {
		Route::get('get-spv', 'UserController@getSpv');
		Route::get('get-sales', 'UserController@getSales');
	});

	Route::group(['prefix' => 'delivery-order'], function() {
		Route::get('get-datatables', 'DeliveryOrderController@getDatatables');
	});

	Route::resource('delivery-order', 'DeliveryOrderController');
	Route::apiResource('invoice', 'InvoiceController');
});