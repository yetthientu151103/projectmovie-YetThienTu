<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/search', function () {
    return view('search');
});
Route::get('/ranking', function () {
    return view('ranking');
});
Route::get('/get-options', 'MovieController@getOptions')->name('getOptions');
Route::get('/get-value', 'MovieController@getValue')->name('getValue');
Route::get('/get-rank', 'MovieController@getRank')->name('getRank');