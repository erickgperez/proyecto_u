<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::redirect('dashboard', '/')->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/', function () {
    return Inertia::render('Home');
})->middleware(['auth', 'verified'])->name('home');

Route::get('/informe/example', function () {
    return Inertia::render('InformeExample');
})->middleware(['auth', 'verified'])->name('informe-example');

Route::get('/crud/example', function () {
    return Inertia::render('CrudExample');
})->middleware(['auth', 'verified'])->name('crud-example');

Route::get('/c', function () {
    return Inertia::render('Home');
})->middleware(['auth', 'verified'])->name('close');

require __DIR__ . '/settings.php';
require __DIR__ . '/public.php';
require __DIR__ . '/ingreso.php';
require __DIR__ . '/workflow.php';
require __DIR__ . '/academico.php';
require __DIR__ . '/administracion.php';
require __DIR__ . '/auth.php';
