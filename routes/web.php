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
//     // return view('welcome');
//     return view('home');
// });

Route::get('/', 'HomeController@index');

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/orders/active', 'OrderController@activeOrders')->name('orders.active');

Route::get('/orders/completed', 'OrderController@completedOrders');

Route::get('/orders/index', 'OrderController@index')->name('orders.index');

Route::get('/orders/create', 'OrderController@create')->name('orders.create');

Route::post('/orders/store', 'OrderController@store')->name('orders.store');

Route::get('/orders/{id}', 'OrderController@view')->name('orders.view');

Route::get('/users', 'UserController@index')->name('users.index');

Route::post('/users/aadmin/{id}', 'UserController@addAdmin')->name('users.aadmin');

Route::post('/users/radmin/{id}', 'UserController@removeAdmin')->name('users.radmin');

Route::delete('/users/{id}', 'UserController@delete')->name('users.delete');

Route::get('/guzzle', 'GuzzleController@index');

Route::get('/google', 'GoogleController@index');

//Route::get('/trello', 'TrelloController@index');
