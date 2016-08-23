<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::get('/login', 'LoginController@login');
Route::post('/login', 'LoginController@checkLogin');


Route::group(['middleware' => ['permission']], function () {
    //主页
    Route::get('/', 'MainController@index');
    
    //个人信息
    Route::get('/user', 'UserController@index');
});