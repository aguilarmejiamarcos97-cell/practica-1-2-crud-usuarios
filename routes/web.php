<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/usuarios');

// ---------- Login ----------
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])
        ->middleware('throttle:10,1')
        ->name('login.attempt');
});

// ---------- Rutas con sesión iniciada ----------
Route::middleware('auth')->group(function () {

    Route::redirect('/dashboard', '/usuarios')->name('dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Administrador Y empleado: ver, registrar y actualizar usuarios
    Route::middleware('role:admin,empleado')->group(function () {
        Route::resource('usuarios', UserController::class)
            ->parameters(['usuarios' => 'user'])
            ->only(['index', 'create', 'store', 'show', 'edit', 'update']);
    });

    // SOLO administrador: eliminar (soft delete / permanente), papelera y restaurar.
    // Un empleado que escriba estas URLs recibe 403 porque lo bloquea el middleware.
    Route::middleware('role:admin')->group(function () {
        Route::get('/papelera', [UserController::class, 'trashed'])->name('usuarios.papelera');
        Route::delete('/usuarios/{user}', [UserController::class, 'destroy'])->name('usuarios.destroy');
        Route::patch('/usuarios/{id}/restaurar', [UserController::class, 'restore'])->name('usuarios.restore');
        Route::delete('/usuarios/{id}/permanente', [UserController::class, 'forceDelete'])->name('usuarios.forceDelete');
    });
});
