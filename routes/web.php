<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\InformeController;



Route::get('/', [DashboardController::class, 'index'])->name('home');

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('home');
});

//USUARIOS

Route::middleware(['auth'])->group(function () {
    Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/ajax', [UserController::class, 'ajax'])->name('usuarios.ajax');
    Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios/{user}/edit', [UserController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{user}', [UserController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{id}', [UserController::class, 'destroy'])->name('usuarios.destroy');
    Route::resource('users', UserController::class);
    Route::post('/usuarios/{user}/toggle-admin', [UserController::class, 'toggleAdmin'])->name('usuarios.toggleAdmin');
});


//TAREAS

Route::middleware(['auth'])->group(function () {
    Route::get('/tareas/ajax', [TareaController::class, 'ajax'])->name('tareas.ajax');
    Route::post('/tareas', [TareaController::class, 'store'])->name('tareas.store');
    Route::get('/tareas/usuario', [TareaController::class, 'getByUser'])->name('tareas.byUser');
    Route::get('/tareas/calendario', [TareaController::class, 'calendario'])->name('tareas.calendario');
    Route::get('/tareas', [TareaController::class, 'index'])->name('tareas.index');
    Route::get('/tareas/ajax/{user}', [TareaController::class, 'getTareas']);
    Route::get('/tareas/informe', [TareaController::class, 'pdf'])->name('tareas.pdf');
});

//PROYECTOS

Route::middleware(['auth'])->group(function () {
    Route::get('/proyectos', [ProyectoController::class, 'index'])->name('proyectos.index');
    Route::post('/proyectos', [ProyectoController::class, 'store'])->name('proyectos.store');
});

// Ruta para obtener proyectos (AJAX)
Route::get('/proyectos/lista', [ProyectoController::class, 'list'])->name('proyectos.list');
Route::get('/proyectos/{id}', [ProyectoController::class, 'show']);
Route::put('/proyectos/{id}', [ProyectoController::class, 'update']);
Route::delete('/proyectos/{id}', [ProyectoController::class, 'destroy']);


//INFORME PDF

Route::middleware(['auth'])->group(function () {
    Route::get('/pdf', [InformeController::class, 'generarInforme'])->name('informes.pdf');
});


//LOGIN, REGISTRO

Route::get('/login', [UserController::class, 'login'])->name('login');
Route::get('/registro', [UserController::class, 'registro'])->name('registro');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
