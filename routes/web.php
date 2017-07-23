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
Route::resource('admin/organizations', 'Organizations\\OrganizationsController');
Route::resource('admin/customers', 'Customers\\CustomersController');
Route::resource('admin/customer-device', 'CustomerDevice\\CustomerDeviceController');
Route::resource('admin/transactions', 'Transaction\\TransactionsController');
Route::resource('admin/screens', 'Screens\\ScreensController');
Route::resource('admin/loan', 'Loans\\LoanController');
Route::resource('admin/payments', 'Payments\\PaymentsController');