<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

// f.vsmuta.com/t/333-dnevniki-vampira
// f.vsmuta.com/f/333-novosoti
// f.vmsuta.com/s/333-vremena-smuti

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::get('/main', 'MainController@index');

Route::get('/t/{id}', 'TopicController@index');
    Route::any('/t/{id}/post','TopicController@post');

Route::get('/f/{id}', 'ForumController@index');
Route::get('/f/{id}/topic', 'ForumController@topic');
    Route::any('/f/{id}/topic/save', 'ForumController@save');

Route::get('/s/{id}', 'SectionController@index');
