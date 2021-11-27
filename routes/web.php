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
    return view('welcome');
});

Route::prefix('articles')->group(function () {

    Route::get('','App\Http\Controllers\ArticleController@index')->name('article.index')->middleware("auth");
    Route::get('create', 'App\Http\Controllers\ArticleController@create')->name('article.create')->middleware("auth");
    Route::post('store', 'App\Http\Controllers\ArticleController@store')->name('article.store')->middleware("auth");
    Route::get('edit/{article}', 'App\Http\Controllers\ArticleController@edit')->name('article.edit')->middleware("auth");
    Route::post('update/{article}', 'App\Http\Controllers\ArticleController@update')->name('article.update')->middleware("auth");
    Route::post('delete/{article}', 'App\Http\Controllers\ArticleController@destroy')->name('article.destroy')->middleware("auth");
    Route::get('show/{article}', 'App\Http\Controllers\ArticleController@show')->name('article.show')->middleware("auth");
    Route::post('storeAjax', 'App\Http\Controllers\ArticleController@storeAjax')->name('article.storeAjax');
    Route::get('showAjax/{article}', 'App\Http\Controllers\ArticleController@showAjax')->name('article.showAjax');
});

Route::prefix('types')->group(function () {

    Route::get('','App\Http\Controllers\TypeController@index')->name('type.index')->middleware("auth");
    Route::get('create', 'App\Http\Controllers\TypeController@create')->name('type.create')->middleware("auth");
    Route::post('store', 'App\Http\Controllers\TypeController@store')->name('type.store')->middleware("auth");
    Route::get('edit/{type}', 'App\Http\Controllers\TypeController@edit')->name('type.edit')->middleware("auth");
    Route::post('update/{type}', 'App\Http\Controllers\TypeController@update')->name('type.update')->middleware("auth");
    Route::post('delete/{type}', 'App\Http\Controllers\TypeController@destroy')->name('type.destroy')->middleware("auth");
    Route::post('deleteAjax/{type}', 'App\Http\Controllers\TypeController@destroyAjax' )->name('type.destroyAjax');
    Route::get('show/{type}', 'App\Http\Controllers\TypeController@show')->name('type.show')->middleware("auth");
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');