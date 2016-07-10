<?php

use App\Events\UserSignedUp;

Route::get('/', 'HomeController@index');

Route::auth();

Route::get('/home', 'HomeController@index');

// TODO wrap this in subdomains - valet doesn't make for nice sub-domaining, neither does letsencrypt which…
// TODO (cont) …you kinda need for a site that takes payments like an auctoin site might. Payments may need
// TODO (cont) …to be handled on a non-subdomain or 'payments.' subdomain etc.
Route::resource('/item', 'ItemController');


Route::post('/bid/{item}', 'BidController@makeBid')
    ->middleware('auth')
    ->name('bid');

Route::get('login-as/{id}', "HomeController@fakeLogin")->name('loginAs');