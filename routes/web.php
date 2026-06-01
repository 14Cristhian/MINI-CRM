<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;

// Rutas públicas (solo para invitados)
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);

    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

// Rutas protegidas
Route::middleware('auth')->group(function () {
    Route::post('/logout', LogoutController::class)->name('logout');

    Route::get('/', fn () => redirect()->route('dashboard'));
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    // Vistas Blade para clientes
    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
    Route::get('/clients/create', [ClientController::class, 'create'])->name('clients.create');
    // Exportaciones — deben ir antes del wildcard {client}
    Route::get('/clients/export/excel', [ExportController::class, 'excel'])->name('clients.export.excel');
    Route::get('/clients/export/pdf',   [ExportController::class, 'pdf'])->name('clients.export.pdf');
    Route::get('/clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
    Route::get('/clients/{client}', [ClientController::class, 'show'])->name('clients.show');

    // API JSON para Vue (protegidas)
    Route::prefix('api')->name('api.')->group(function () {

        Route::controller(ClientController::class)->prefix('clients')->name('clients.')->group(function () {
            Route::get('stats',       'apiStats')->name('stats');
            Route::get('/',           'apiIndex')->name('index');
            Route::post('/',          'store'   )->name('store');
            Route::get('{client}',    'apiShow' )->name('show');
            Route::put('{client}',    'update'  )->name('update');
            Route::delete('{client}', 'destroy' )->name('destroy');
        });

        Route::apiResource('clients.contacts', ContactController::class);
    });
});
