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

Route::get('/', 'HomeController@index')->name('home');
Route::get('/ad-dashboard', 'HomeController@adDashboard')->name('ad-dashboard');

Auth::routes();

Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/otp', 'Auth\ForgotPasswordController@showOtpForm')->name('password.otp');
Route::post('password/verify-otp', 'Auth\ForgotPasswordController@verifyOtp')->name('password.verify-otp');
Route::get('password/reset/form', 'Auth\ForgotPasswordController@showResetForm')->name('password.reset.form');
Route::post('password/update', 'Auth\ForgotPasswordController@reset')->name('password.update');


Route::group(['middleware' => 'auth'], function () {

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/transactions','TransactionController@index')->name('transactions');
Route::get('/ad-transactions','TransactionController@adTransactions')->name('ad-transactions');
Route::delete('/transactions/{id}', 'TransactionController@destroy')->name('transactions.destroy');
Route::post('/transactions/bulk-delete', 'TransactionController@bulkDelete')->name('transactions.bulkDelete');

Route::get('/get-province-details', 'HomeController@getProvinceDetails')->name('province.details');

Route::get('/about', 'HomeController@about')->name('about');
Route::get('/product', 'ProductController@index')->name('product.index');
Route::post('/product', 'ProductController@store')->name('product.store');
Route::delete('/product/{product}', 'ProductController@destroy')->name('product.destroy');

Route::get('/storelocation', 'HomeController@storelocation')->name('storelocation');
Route::get('/api/locations-map', 'HomeController@getLocationsForMap')->name('locations.map');
Route::get('/api/location-details/{id}/{type}', 'HomeController@getLocationDetails')->name('location.details');
Route::get('/home/monthly-data', 'HomeController@getMonthlyDataAjax')->name('home.monthly-data');
Route::get('/home/chart-data', 'HomeController@getChartDataAjax')->name('home.chart-data');

Route::post('/store-transaction','TransactionController@store')->name('new-transaction');
Route::post('/store-transaction-admin','TransactionController@storeAdmin')->name('new-transaction');
Route::get('user-profile','UserController@view');
Route::get('get-user/{id}','CustomerController@getUser');

Route::get('/search', 'SearchController@search')->name('search');
Route::get('/search/suggestions', 'SearchController@searchSuggestions')->name('search.suggestions');
Route::get('/profile/{id}/{type}', 'SearchController@viewProfile')->name('profile.view');
Route::post('/notification/save', 'NotificationController@saveNotification')->name('notification.save');
Route::post('/notifications/mark-all-read', 'NotificationController@markAllAsRead')->name('notifications.markAllAsRead');

Route::get('/users','EditUserController@index')->name('users');
Route::put('edit-users/{id}', 'EditUserController@update')->name('edit-users');
Route::post('/new-admin','EditUserController@store')->name('new-admin');
Route::post('admin-privillege/{id}', 'EditUserController@updatePrivilege')->name('admin.privilege.update');
Route::get('/users-data', 'EditUserController@datatable')->name('users.data');

// Area Distributor 
Route::get('/ads','AreaDistributorController@index')->name('ads');
Route::post('/new-ad','AreaDistributorController@store')->name('new-ad');
Route::put('edit-ads/{id}', 'AreaDistributorController@update')->name('edit-ads');
Route::post('/geocode-location', 'AreaDistributorController@geocodeLocation')->name('geocode.location');

// Dealers Area Distributor
Route::get('/dealer-ads','AreaDistributorController@myDealer')->name('dealer-ads');

// Orders
Route::get('/orders','OrderController@index')->name('orders');   

Route::get('/dealers','DealerController@index')->name('dealers');
Route::post('/new-dealer','DealerController@newDealer');
Route::get('view-dealer/{id}', 'DealerController@view')->name('dealer.view');
Route::post('/change-avatar-dealer/{id}', 'DealerController@changeAvatar')->name('dealer.view');
Route::post('valid-id-dealer/{id}', 'DealerController@uploadValidId')->name('dealer.view');
Route::post('/submit-contract-dealer/{id}','DealerController@contractSign')->name('sign');
Route::get('/dashboard-dealer','DealerController@show')->name('Dealer');
Route::post('/dealer/update/{id}', 'DealerController@update')->name('dealer.update');

Route::get('/customers','CustomerController@index')->name('customers');
Route::get('/customer','CustomerController@view')->name('customer');
Route::get('/dashboard-customer','CustomerController@show')->name('customer');
Route::get('/new-customer','CustomerController@newCustomer')->name('newcustomer');
Route::get('view-client/{id}', 'CustomerController@view')->name('client.view');
Route::post('new-customer','CustomerController@saveCustomer')->name('saveCustomer');
Route::post('/change-avatar/{id}','CustomerController@changeAvatar')->name('changeAvatar');
Route::post('/valid-id/{id}','CustomerController@uploadValidId')->name('uploadValidId');
Route::post('/submit-contract/{id}','CustomerController@contractSign')->name('sign');

Route::get('/signature/{id}','CustomerController@sign');
Route::get('/signature-dealer/{id}','DealerController@sign');

Route::get('/rewards', 'RewardController@index')->name('rewards');
Route::post('/rewards', 'RewardController@store')->name('rewards.store');
Route::put('/rewards/{reward}', 'RewardController@update')->name('rewards.update');
Route::delete('/rewards/{reward}', 'RewardController@destroy')->name('rewards.destroy');

Route::post('/rewards/{id}/update-status', 'RewardController@updateStatus')->name('rewards.updateStatus');
});

