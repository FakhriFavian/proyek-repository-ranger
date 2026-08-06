<?php

use Illuminate\Support\Facades\Route;
use App\Modules\borrowings\Controllers\borrowingsController;

Route::controller(borrowingsController::class)->middleware(['web','auth'])->name('borrowings.')->group(function(){
	Route::get('/borrowings', 'index')->name('index');
	Route::get('/borrowings/data', 'data')->name('data.index');
	Route::get('/borrowings/create', 'create')->name('create');
	Route::post('/borrowings', 'store')->name('store');
	Route::get('/borrowings/{borrowings}', 'show')->name('show');
	Route::get('/borrowings/{borrowings}/edit', 'edit')->name('edit');
	Route::patch('/borrowings/{borrowings}', 'update')->name('update');
	Route::get('/borrowings/{borrowings}/delete', 'destroy')->name('destroy');
});
