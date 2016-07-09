<?php

use App\Events\UserSignedUp;

Route::get('/', 'HomeController@index');

Route::auth();

Route::get('/home', 'HomeController@index');

// TODO wrap this in subdomains?
Route::resource('/item', 'ItemController');


Route::post('/bid/{item}', 'BidController@makeBid')
    ->middleware('auth')
    ->name('bid');

Route::get('login-as/{id}', "HomeController@fakeLogin")->name('loginAs');