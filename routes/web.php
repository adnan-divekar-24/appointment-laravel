<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



// Home - List of appointments
Route::get('/', [AppointmentController::class, 'index'])->name('appointments.index');

// Show booking form
Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');

// Store new appointment
Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');

// Delete appointment
Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');

Route::get('/appointments/{appointment}/edit', [AppointmentController::class, 'edit'])->name('appointments.edit');
Route::put('/appointments/{appointment}', [AppointmentController::class, 'update'])->name('appointments.update');

