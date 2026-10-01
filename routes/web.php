<?php

use App\Http\Controllers\ProductoController;
use App\Http\Controllers\RegistroController;
use Illuminate\Support\Facades\Route;

// Página de bienvenida
Route::view('/', 'inicio')->name('inicio');

// CRUD de productos
Route::resource('productos', ProductoController::class);

// Solo vista de historial de registros
Route::get('/registros', [RegistroController::class, 'index'])->name('registros.index');