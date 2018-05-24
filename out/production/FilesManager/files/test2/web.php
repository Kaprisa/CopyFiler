<?php

Route::get('/', 'WelcomeController@landing');
//Route::get('/academy', 'WelcomeController@index');
Route::get('/{a}', 'WelcomeController@index');
Route::get('/{a}/{b}', 'WelcomeController@index');
Route::get('/{a}/{b}/{c}', 'WelcomeController@index');
Route::get('/{a}/{b}/{c}/{d}', 'WelcomeController@index');
