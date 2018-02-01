<?php

Route::get('/gnomes', 'GnomesController@getAllGnomes');
Route::post('/gnome', 'GnomesController@createGnome');
Route::get('/gnome/{id}', 'GnomesController@getGnome');
Route::put('/gnome/{id}', 'GnomesController@updateGnome');
Route::delete('/gnome/{id}', 'GnomesController@deleteGnome');

