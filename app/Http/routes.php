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
use Illuminate\Support\Facades\Input;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/home', function () {
    return view('welcome');
});
Route::get('/login', function () {
    return view('welcome');
});
Route::get('/albumtracks', ["uses"=>"HomeController@getAlbumTracks","as"=>"albumtracks"]);

/*Route::get('/albumtracks', function () {
	$album_id=Input::get("albumid") ;
    return view('albumtracks',["album_id"=>$album_id]);
});*/
