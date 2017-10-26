<?php

Route::get('/', 'PlaceController@index');
Route::get('/home', 'PlaceController@index')->name('home');
Route::get('/about', 'AboutController@index')->name('about');

Route::get('/places', 'PlaceController@index');
Route::get('/places/create', 'PlaceController@create');
Route::get('/places/{place}', 'PlaceController@show');

Route::get('/geo-search', 'GeoController@index');
Route::get('/api/geo-search', 'Api\GeoController@index');
Route::get('/api/countries', 'Api\CountryController@index');

Route::get('/api/places', 'Api\PlaceController@index');
Route::post('/api/places', 'Api\PlaceController@store');
Route::get('/api/places/{place}', 'Api\PlaceController@show');
Route::patch('/api/places/{place}', 'Api\PlaceController@update')->middleware('must-be-confirmed');
Route::delete('/api/places/{place}', 'Api\PlaceController@destroy')->middleware('must-be-confirmed');

Route::post('/api/places/{place}/images', 'Api\PlaceImageController@store')->middleware('must-be-confirmed');
Route::delete('/api/places/{place}/images', 'Api\PlaceImageController@destroy')->middleware('must-be-confirmed');

Route::get('/api/favorites', 'Api\FavoriteController@index');
Route::post('/api/places/{place}/favorites', 'Api\FavoriteController@store')->middleware('must-be-confirmed');
Route::delete('/api/places/{place}/favorites', 'Api\FavoriteController@destroy')->middleware('must-be-confirmed');


Auth::routes();

Route::get('/confirmation/token/confirm', 'ConfirmationTokenController@confirm')->name('confirm.email');
Route::post('/confirmation/token/resend', 'ConfirmationTokenController@resend')->name('send.confirmation.token');
