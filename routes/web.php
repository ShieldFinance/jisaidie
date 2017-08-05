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


Auth::routes();
Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/sendMessages', 'Services\\MessagesController@sendQueuedMessages');
Route::post('/notifyPayment', 'Payments\\PaymentsController@receivePayment');
Route::group(['middleware' => 'auth'], function() {
  Route::post('/admin/sendMessage', 'Services\\MessagesController@sendMessage');
Route::get('admin', 'Admin\AdminController@index');
Route::get('admin/give-role-permissions', 'Admin\AdminController@getGiveRolePermissions');
Route::post('admin/give-role-permissions', 'Admin\AdminController@postGiveRolePermissions');
Route::resource('admin/roles', 'Admin\RolesController');
Route::resource('admin/permissions', 'Admin\PermissionsController');
Route::resource('admin/users', 'Admin\UsersController');
Route::get('admin/generator', ['uses' => '\Appzcoder\LaravelAdmin\Controllers\ProcessController@getGenerator']);
Route::post('admin/generator', ['uses' => '\Appzcoder\LaravelAdmin\Controllers\ProcessController@postGenerator']);

Route::resource('admin/settings', 'Setting\\SettingsController');
Route::resource('admin/services', 'Services\\ServicesController');
Route::resource('admin/service-commands', 'ServiceCommands\\ServiceCommandsController');
Route::resource('admin/customers', 'Customers\\CustomersController');
Route::resource('admin/customer-device', 'CustomerDevice\\CustomerDeviceController');
Route::resource('admin/transactions', 'Transaction\\TransactionsController');
Route::resource('admin/screens', 'Screens\\ScreensController');
Route::resource('admin/loan', 'Loans\\LoanController');
Route::resource('admin/payments', 'Payments\\PaymentsController');
Route::resource('admin/response-templates', 'Services\\ResponseTemplatesController');
Route::resource('admin/messages', 'Services\\MessagesController');

Route::post('admin/customers/reset_pin', 'Customers\\CustomersController@resetPin');
Route::post('admin/loans/process_loan', 'Loans\\LoanController@processLoan');
Route::post('admin/customers/activate', 'Customers\\CustomersController@activate');
Route::post('admin/customers/deactivate', 'Customers\\CustomersController@deactivate');
Route::post('admin/customers/verify', 'Customers\\CustomersController@verify');
Route::post('admin/customers/export', 'Customers\\CustomersController@export');
Route::resource('admin/reports', 'Admin\\ReportsController');
});


Route::resource('ussd/ussd', 'Ussd\\UssdController');
Route::post('ussd/process', 'Ussd\\UssdController@processRequest');
Route::resource('Organization/organizations', 'Organization\\OrganizationsController');
Route::resource('Organization/organizations', 'Organization\\OrganizationsController');


//ajax report calls
Route::post('admin/userRegistration', 'Admin\\ReportsController@userRegistration');
Route::post('admin/loanData', 'Admin\\ReportsController@loanStats');
Route::post('admin/loanDataAverages', 'Admin\\ReportsController@loanDataAverages');

