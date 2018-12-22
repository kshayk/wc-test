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
    return view('register');
});

Route::post('/register', 'UserController@register');
Route::post('/token', 'UserController@authenticate');

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('/account', 'UserController@getAuthenticatedUser');

    Route::get('/entity', 'EntityController@getEntities');
    Route::post('/entity', 'EntityController@createEntity');
    Route::put('/entity', 'EntityController@updateEntity');
    Route::delete('/entity', 'EntityController@deleteEntity');
});

