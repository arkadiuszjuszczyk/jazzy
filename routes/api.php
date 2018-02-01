<?php

Route::get('/gnomes', 'GnomesController@getGnomes')->name('getGnomes');
Route::post('/gnome/', 'GnomesController@createGnome')->name('createGnome');
Route::get('/gnome/{id}', 'GnomesController@getGnome')->name('getGnome');
Route::put('/gnome/{id}', 'GnomesController@updateGnome')->name('updateGnome');
Route::delete('/gnome/{id}', 'GnomesController@deleteGnome')->name('deleteGnome');

