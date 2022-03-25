<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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
    if(Auth::user())
        return view('front');
    else
        return view('welcome');
})->name('homepage');

Route::get('/p/{slug}', function () {
    if(Auth::user())
        return view('front');
    else
        return view('welcome');
})->name('test');

Auth::routes();

Route::middleware('auth')
    ->namespace('Admin')
    ->name('admin.')
    ->prefix('admin')
    ->group(function () {
        Route::get('/' , 'HomeController@index')->name('home');
        Route::resource('/posts' , 'PostController');
        Route::resource('/tags' , 'TagController');
        Route::resource('/users' , 'UserController');
        Route::get("/user" , function (){
            return response()->json(['name' => Auth::user()->name , 'id' => Auth::user()->id]);
        }); //prende utente attuale
    });