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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::group(['prefix' => 'cadmin'], function () {
    Voyager::routes();    
});

Auth::routes();

Route::get('/admin', 'HomeController@index')->name('home');
Route::get('/', 'CustomerController@index')->name('customer');
// Left Sidebar Main Category
// Route::get('reservations', 'ReservationsController@index')->name('reservations.index');
// Route::group(['prefix' => 'settings'], function() {
//     Route::get('/clinics', 'SettingsController@clinics')->name('settings.clinics');
//     Route::get('/ranks', 'SettingsController@ranks')->name('settings.ranks');
// });
// Route::get('logs', 'LogsController@index')->name('logs.index');

Route::get('/admin/{path}','HomeController@index')->where('path','[A-Za-z0-9_-]+' );

// API
Route::group(['prefix' => 'v1', 'middleware' => ['cors']], function() {

    Route::get('/staff/operators', 'API\StaffController@operators');
    Route::get('/staff/counselors', 'API\StaffController@counselors');
    Route::post('/reservation/staff_list', 'ReservationsController@staff_list');
    Route::post('/reservation/staff_rank_list', 'ReservationsController@staff_rank_list');
    Route::post('/reservation/counselor_list', 'ReservationsController@counselor_list');

    Route::post('/order-create', 'ReservationsController@orderCreate');
    Route::post('/order-update', 'ReservationsController@orderUpdate');
    Route::post('/order-statusupdate', 'ReservationsController@orderStatusUpdate');

    Route::get('/shift/clinic/{clinic_id}/{staff_type}', 'ShiftController@listStaffList');
    Route::post('/shift/update', 'ShiftController@updateShift');
    Route::post('/shift/get', 'ShiftController@getShift');
//     // Clinics
//     Route::get('/clinic/list', 'ApiController@clinicList');
//     Route::post('/clinic/add', 'ApiController@clinicAdd');
//     Route::post('/clinic/remove', 'ApiController@clinicRemove');
//     Route::post('/clinic/update', 'ApiController@clinicUpdate');
});
