<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\StaffController;
use App\Models\SubDepartment;
use App\Http\Controllers\LeaveController;


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



// // Home - List of appointments
// Route::get('/', [AppointmentController::class, 'index'])->name('appointments.index');

// // Show booking form
// Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');

// // Store new appointment
// Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');

// // Delete appointment
// Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');

// Route::get('/appointments/{appointment}/edit', [AppointmentController::class, 'edit'])->name('appointments.edit');
// Route::put('/appointments/{appointment}', [AppointmentController::class, 'update'])->name('appointments.update');




// Route::prefix('staff')->group(function () {
//     Route::get('/', [StaffController::class, 'index'])->name('staff.index');
//     Route::get('/create', [StaffController::class, 'create'])->name('staff.create');
//     Route::post('/store', [StaffController::class, 'store'])->name('staff.store');
//     Route::get('/edit/{id}', [StaffController::class, 'edit'])->name('staff.edit');
//     Route::put('/update/{id}', [StaffController::class, 'update'])->name('staff.update');
//     Route::delete('/destroy/{id}', [StaffController::class, 'destroy'])->name('staff.destroy');

//     // Soft Delete - View Deleted Staff
//     Route::get('/deleted', [StaffController::class, 'deleted'])->name('staff.deleted');
    
//     // Restore Soft Deleted Staff
//     Route::put('/restore/{id}', [StaffController::class, 'restore'])->name('staff.restore');

//     // Permanent Delete
//     Route::delete('/force-delete/{id}', [StaffController::class, 'forceDelete'])->name('staff.forceDelete');
// });

// Home - Show Dashboard with Cards for Navigation
Route::get('/', function () {
    return view('dashboard'); // This will contain the two cards
})->name('dashboard');

// Appointment Routes
Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
Route::get('/appointments/{appointment}/edit', [AppointmentController::class, 'edit'])->name('appointments.edit');
Route::put('/appointments/{appointment}', [AppointmentController::class, 'update'])->name('appointments.update');
Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');

// Staff Routes
Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
Route::get('/staff/create', [StaffController::class, 'create'])->name('staff.create');
Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
Route::get('/staff/{staff}/edit', [StaffController::class, 'edit'])->name('staff.edit');
Route::put('/staff/{staff}', [StaffController::class, 'update'])->name('staff.update');
Route::delete('/staff/{staff}', [StaffController::class, 'destroy'])->name('staff.destroy');
Route::get('/staff/deleted', [StaffController::class, 'deleted'])->name('staff.deleted');
Route::put('/staff/{staff}/restore', [StaffController::class, 'restore'])->name('staff.restore');
Route::delete('staff/force-delete/{id}', [StaffController::class, 'forceDelete'])->name('staff.forceDelete');


Route::get('/get-subdepartments/{department}', function ($department) {
    return SubDepartment::where('department_id', $department)->get();
});

Route::prefix('leaves')->group(function () {
    Route::get('/', [LeaveController::class, 'index'])->name('leaves.index'); // List all leaves
    Route::get('/create', [LeaveController::class, 'create'])->name('leaves.create'); // Show form to apply for leave
    Route::post('/', [LeaveController::class, 'store'])->name('leaves.store'); // Store new leave

    Route::get('/{id}/edit', [LeaveController::class, 'edit'])->name('leaves.edit'); // Show edit form (admin only)
    Route::put('/{id}', [LeaveController::class, 'update'])->name('leaves.update'); // Update leave (admin only)
    Route::delete('/{id}', [LeaveController::class, 'destroy'])->name('leaves.destroy'); // Delete leave (admin only)
});
