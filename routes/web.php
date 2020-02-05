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


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

// Left Sidebar Main Category
// Route::get('reservations', 'ReservationsController@index')->name('reservations.index');
// Route::group(['prefix' => 'settings'], function() {
//     Route::get('/clinics', 'SettingsController@clinics')->name('settings.clinics');
//     Route::get('/ranks', 'SettingsController@ranks')->name('settings.ranks');
// });
// Route::get('logs', 'LogsController@index')->name('logs.index');

Route::get('{path}','HomeController@index')->where('path','[A-Za-z0-9_-]+' );

// API
Route::group(['prefix' => 'v1', 'middleware' => ['cors']], function() {

    Route::get('/staff/operators', 'API\StaffController@operators');
    Route::get('/staff/counselors', 'API\StaffController@counselors');
    Route::get('/reservation/staffs_ranks', 'ReservationsController@staffs_ranks');

//     // Clinics
//     Route::get('/clinic/list', 'ApiController@clinicList');
//     Route::post('/clinic/add', 'ApiController@clinicAdd');
//     Route::post('/clinic/remove', 'ApiController@clinicRemove');
//     Route::post('/clinic/update', 'ApiController@clinicUpdate');
});
