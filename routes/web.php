<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas de usuarios sin el método show
    Route::resource('usuarios', UsuarioController::class)->except(['show']);
    
    // Ruta para exportar usuarios a Excel
    Route::get('/export-users', [UserController::class, 'export']);

    Route::get('/usuarios/export', [UsuarioController::class, 'export'])->name('usuarios.export');

    Route::get('/usuarios/export', [UsuarioController::class, 'export'])
    ->name('usuarios.export')
    ->middleware('auth');
});

require __DIR__.'/auth.php';
