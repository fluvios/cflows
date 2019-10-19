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

// Route::get('/', 'WelcomeController@index');

// Route::get('home', 'HomeController@index');

// Route::controllers([
// 	'auth' => 'Auth\AuthController',
// 	'password' => 'Auth\PasswordController',
// ]);
Route::get('/', 'ParseController@index');
Route::post('/', 'ParseController@load');
Route::get('/filelist/{id?}', 'ParseController@readDirectory');
Route::get('/filelist/{id?}/{filename?}/{fileindex?}', 'ParseController@readFile');
Route::get('/find/{id?}/{filename?}/{fileindex?}', 'ParseController@findFile');
Route::get('/analyze/{id?}', 'ParseController@analyze');
Route::get('/analyze/{syntax?}', 'ParseController@readSyntax');