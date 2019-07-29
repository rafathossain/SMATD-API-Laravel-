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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/isValidUser', 'LoginController@isValidUser');
Route::post('/createUser', 'LoginController@CreateUser');
Route::post('/updatePassword', 'LoginController@updatePassword');
Route::post('/loginUser', 'LoginController@loginUser');
Route::post('/studentInfo', 'StudentController@studentInfo');
Route::post('/attendanceRecord', 'StudentController@recordAttendance');
Route::post('/attendanceReport', 'StudentController@attendanceReport');
