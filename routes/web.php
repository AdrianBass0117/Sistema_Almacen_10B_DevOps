<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\RegistroController;

// Página principal
Route::get('/', function () {
    return redirect()->route('productos.index');
});

// CRUD de productos
Route::resource('productos', ProductoController::class);

// Solo vista de historial de registros
Route::get('/registros', [RegistroController::class, 'index'])->name('registros.index');
