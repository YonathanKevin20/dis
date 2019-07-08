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

Route::post('/reset-password', 'Auth\ResetPasswordController@resetPassword');
Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {
	Route::group(['prefix' => 'import-target'], function() {
		Route::get('/get-datatables', 'ImportTargetController@getDatatables');
		Route::post('/import', 'ImportTargetController@import');
		Route::get('/download-template-xlsx', 'ImportTargetController@downloadTemplateXlsx');
	});

	Route::group(['prefix' => 'statistic'], function() {
		Route::get('/get-revenue', 'HomeController@getRevenue');
		Route::get('/get-revenue-product', 'HomeController@getRevenueProduct');
		Route::get('/get-items-sold', 'HomeController@getItemsSold');
		Route::get('/get-items-sold-store', 'HomeController@getItemsSoldStore');
		Route::get('/get-store', 'HomeController@getStore');
		Route::get('/get-store-location', 'HomeController@getStoreLocation');
		Route::get('/get-avg-time-sales', 'HomeController@getAvgTimeSales');
		Route::get('/get-chart', 'HomeController@getChart');
	});

	Route::group(['prefix' => 'product'], function() {
		Route::get('/get-data', 'ProductController@getData');
	});

	Route::group(['prefix' => 'status'], function() {
		Route::get('/get-data', 'StatusController@getData');
	});

	Route::group(['prefix' => 'store'], function() {
		Route::get('/get-data', 'StoreController@getData');
		Route::get('/get-datatables', 'StoreController@getDatatables');
	});

	Route::group(['prefix' => 'vehicle'], function() {
		Route::get('/get-data', 'VehicleController@getData');
	});

	Route::group(['prefix' => 'user'], function() {
		Route::get('/get-spv', 'UserController@getSpv');
		Route::get('/get-sales', 'UserController@getSales');
		Route::get('/change-password', 'UserController@changePasswordForm')->name('user.changePasswordForm');
		Route::post('/change-password', 'UserController@changePassword');
		Route::get('/view-avg-time/{sales_id}', 'UserController@avgTimeSales');
	});

	Route::group(['prefix' => 'delivery-order'], function() {
		Route::get('/get-data', 'DeliveryOrderController@getData');
		Route::get('/get-data-product/{delivery_order}', 'DeliveryOrderController@getDataProduct');
		Route::get('/get-datatables', 'DeliveryOrderController@getDatatables');
		Route::get('/view/{delivery_order}', 'DeliveryOrderController@view');
	});

	Route::group(['prefix' => 'invoice'], function() {
		Route::get('/get-datatables', 'InvoiceController@getDatatables');
		Route::get('/view/{invoice}', 'InvoiceController@view');
	});

	Route::resource('delivery-order', 'DeliveryOrderController');
	Route::resource('invoice', 'InvoiceController');
	Route::apiResource('store', 'StoreController');
	Route::apiResource('import-target', 'ImportTargetController');
});