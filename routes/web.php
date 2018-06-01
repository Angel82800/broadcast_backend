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

Route::match(['get', 'post'], '/users','BroadcastApi@userRegister');
Route::match(['get', 'post'], '/upload','BroadcastApi@uploads');
Route::match(['get', 'post'], '/getvideolist','BroadcastApi@getvideolist');
Route::match(['get', 'post'], '/push','BroadcastApi@push');

