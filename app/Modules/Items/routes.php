<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Items\Controllers\ItemsController;

Route::controller(ItemsController::class)->middleware(['web','auth'])->name('items.')->group(function(){
	Route::get('/items', 'index')->name('index');
	Route::get('/items/data', 'data')->name('data.index');
	Route::get('/items/create', 'create')->name('create');
	Route::post('/items', 'store')->name('store');
	Route::get('/items/{items}', 'show')->name('show');
	Route::get('/items/{items}/edit', 'edit')->name('edit');
	Route::patch('/items/{items}', 'update')->name('update');
	Route::get('/items/{items}/delete', 'destroy')->name('destroy');
});
