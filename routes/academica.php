<?php

use App\Http\Controllers\Academica\SedeController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth', 'verified')->group(function () {

    Route::get('/academica/sede', [SedeController::class, 'index'])->name('academica-sede');
    Route::post('/academica/sede/save', [SedeController::class, 'save'])->name('academica-sede-save');
});
