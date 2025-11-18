<?php

use App\Http\Controllers\Documento\TipoDocumentoController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth', 'verified')->group(function () {

    Route::get('/administracion/documento/tipo', [TipoDocumentoController::class, 'index'])->name('administracion-documento-tipo-index');
    Route::post('/administracion/documento/tipo/save', [TipoDocumentoController::class, 'save'])->name('administracion-documento-tipo-save');
    Route::delete('/administracion/documento/tipo/{id}/delete', [TipoDocumentoController::class, 'delete'])->name('administracion-documento-tipo-delete');
});
