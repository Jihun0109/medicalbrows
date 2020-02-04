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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResources([
    'rank' => 'API\RankController',
    'user' => 'API\UserController',
    'role' => 'API\RoleController',
    'clinic' => 'API\ClinicController',
    'staff' => 'API\StaffController',
    'staff-type' => 'API\StaffTypeController',
    'region' => 'API\RegionController',
    'operable-part' => 'API\OperablePartController',
    ]);
Route::apiResources(['tax' => 'API\TaxController']);


