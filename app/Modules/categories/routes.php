<?php

use Illuminate\Support\Facades\Route;
use App\Modules\categories\Controllers\categoriesController;

Route::controller(categoriesController::class)->middleware(['web','auth'])->name('categories.')->group(function(){
	Route::get('/categories', 'index')->name('index');
	Route::get('/categories/data', 'data')->name('data.index');
	Route::get('/categories/create', 'create')->name('create');
	Route::post('/categories', 'store')->name('store');
	Route::get('/categories/{categories}', 'show')->name('show');
	Route::get('/categories/{categories}/edit', 'edit')->name('edit');
	Route::patch('/categories/{categories}', 'update')->name('update');
	Route::get('/categories/{categories}/delete', 'destroy')->name('destroy');
});
