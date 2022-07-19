<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckOnline;
use App\User;

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

Route::middleware([CheckOnline::class])->group(function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/main', 'MainController@index')->name('main');

    Route::get('/t/{id}', 'TopicController@index');
    Route::any('/t/{id}/post', 'TopicController@post');
    Route::any('/t/{id}/edit', 'TopicController@edit');
    Route::any('/t/{id}/move', 'TopicController@move');

    Route::get('/p/{id}/edit', 'PostController@index');
    Route::any('/p/{id}/save', 'PostController@edit');
    Route::any('/p/{id}/premod', 'PostController@premod');
    Route::any('/p/{id}/unhide', 'PostController@unhide');

    Route::any('/p/{id}/like', 'LikeController@like');
    Route::any('/p/{id}/likem', 'LikeController@likem');
    Route::any('/p/{id}/dislike', 'LikeController@dislike');
    Route::any('/p/{id}/dislikem', 'LikeController@dislikem');

    Route::get('/fs', 'AllForumController@index');

    Route::get('/f/{id}', 'ForumController@index');
    Route::get('/f/{id}/topic', 'ForumController@topic');
    Route::any('/f/{id}/t/save', 'ForumController@save');

    Route::get('/s/{id}', 'SectionController@index');

    Route::post('ckeditor/image_upload', 'CkeditorController@upload')->name('upload');

});

