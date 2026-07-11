<?php
use App\Http\Controllers\AuthController;use App\Http\Controllers\Admin\{AsistenciaController,EstudianteController};use App\Http\Controllers\Profesor\ScannerController;use Illuminate\Support\Facades\Route;
Route::redirect('/','/login');
Route::middleware('guest')->group(function(){Route::get('/login',[AuthController::class,'showLogin'])->name('login');Route::post('/login',[AuthController::class,'login'])->name('login.attempt');});
Route::post('/logout',[AuthController::class,'logout'])->middleware('auth')->name('logout');
Route::prefix('admin')->name('admin.')->middleware(['auth','role:admin'])->group(function(){Route::view('/dashboard','admin.dashboard')->name('dashboard');Route::resource('estudiantes',EstudianteController::class)->only(['index','create','store','show']);Route::get('/asistencias',[AsistenciaController::class,'index'])->name('asistencias.index');});
Route::prefix('profesor')->name('profesor.')->middleware(['auth','role:profesor'])->group(function(){Route::view('/dashboard','profesor.dashboard')->name('dashboard');Route::get('/escanear',[ScannerController::class,'index'])->name('escanear');Route::post('/verificar',[ScannerController::class,'verificar'])->name('verificar');});
Route::prefix('estudiante')->name('estudiante.')->middleware(['auth','role:estudiante'])->group(function(){Route::view('/dashboard','estudiante.dashboard')->name('dashboard');});
