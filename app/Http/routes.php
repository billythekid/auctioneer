<?php

use App\Events\UserSignedUp;

Route::get('/', function ()
{
    event(new UserSignedUp(Request::query('name')));
    return view('welcome');
});

Route::auth();

Route::get('/home', 'HomeController@index');

// TODO wrap this in subdomains?
Route::resource('/item', 'ItemController');


Route::post('/bid/{item}', 'BidController@makeBid')
    ->middleware('auth')
    ->name('bid');