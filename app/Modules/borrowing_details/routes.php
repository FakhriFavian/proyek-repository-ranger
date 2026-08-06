<?php

use Illuminate\Support\Facades\Route;
use App\Modules\borrowing_details\Controllers\borrowing_detailsController;

Route::controller(borrowing_detailsController::class)->middleware(['web','auth'])->name('borrowing_details.')->group(function(){
	Route::get('/borrowing_details', 'index')->name('index');
	Route::get('/borrowing_details/data', 'data')->name('data.index');
	Route::get('/borrowing_details/create', 'create')->name('create');
	Route::post('/borrowing_details', 'store')->name('store');
	Route::get('/borrowing_details/{borrowing_details}', 'show')->name('show');
	Route::get('/borrowing_details/{borrowing_details}/edit', 'edit')->name('edit');
	Route::patch('/borrowing_details/{borrowing_details}', 'update')->name('update');
	Route::get('/borrowing_details/{borrowing_details}/delete', 'destroy')->name('destroy');
});
