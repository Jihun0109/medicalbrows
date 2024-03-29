<?php

use App\Mail\WelcomeMail;
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

Route::get('/email', 'TestController@email');
Route::get('/pdfdown', 'API\ClientController@getDocument');

Route::group(['prefix' => 'cadmin'], function () {
    Voyager::routes();
});

Auth::routes();

Route::get('/admin', function(){
    return redirect('/admin/reservations');
});
Route::get('/admin/reservations', 'HomeController@index')->name('home');

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
    Route::post('/reservation/menu_list', 'ReservationsController@menu_list');

    Route::post('/order-create', 'ReservationsController@orderCreate');
    Route::post('/order-update', 'ReservationsController@orderUpdate');
    Route::post('/order-statusupdate', 'ReservationsController@orderStatusUpdate');

    Route::get('/shift/clinic/{clinic_id}/{staff_type}', 'ShiftController@listStaffList');
    Route::post('/shift/update', 'ShiftController@updateShift');
    Route::post('/shift/get', 'ShiftController@getShift');

    Route::get('/clinic/get-email', 'API\UserController@getClinicIdsWithEmail');
    Route::post('/send_mail', 'ReservationsController@sendMail');

    Route::get('/logs', 'LogsController@index');
//     // Clinics
//     Route::get('/clinic/list', 'ApiController@clinicList');
//     Route::post('/clinic/add', 'ApiController@clinicAdd');
//     Route::post('/clinic/remove', 'ApiController@clinicRemove');
//     Route::post('/clinic/update', 'ApiController@clinicUpdate');
    //client APIs
    Route::post('/client/rank_list', 'API\ClientController@rank_list');
    Route::post('/client/staff_list', 'API\ClientController@staff_list');
    Route::post('/client/clinic_list', 'API\ClientController@clinic_list');
    Route::post('/client/menu_list', 'API\ClientController@menu_list');
    Route::post('/client/canledar_info', 'API\ClientController@canledar_info');
    Route::post('/client/staff_list_withdate', 'API\ClientController@staff_list_withdate');
    Route::post('/client/order_create', 'API\ClientController@order_create');
    Route::post('/client/get_orderinfo', 'API\ClientController@get_orderinfo');
    Route::post('/client/order_update', 'API\ClientController@order_update');
    Route::post('/client/order_cancel', 'API\ClientController@order_cancel');
    Route::post('/client/send_mail', 'API\ClientController@sendmail');
    Route::get('/client/download', 'API\ClientController@download');
});