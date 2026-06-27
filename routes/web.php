<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/setup-database', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate:fresh', ['--force' => true, '--seed' => true]);
        return "Database migrations and seeding completed successfully! You can now log in.";
    } catch (\Exception $e) {
        return "Error running migrations: " . $e->getMessage();
    }
});

use App\Http\Controllers\CitizenController;
use App\Http\Controllers\ResponderController;
use App\Http\Controllers\AdminController;

Route::get('/dashboard', function () {
    $role = auth()->user()->role_type;
    if ($role === 'Admin') return redirect()->route('admin.dashboard');
    if ($role === 'Responder') return redirect()->route('responder.dashboard');
    return redirect()->route('citizen.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Citizen Routes
Route::middleware(['auth', 'role:Citizen'])->group(function () {
    Route::get('/citizen', [CitizenController::class, 'index'])->name('citizen.dashboard');
    Route::get('/citizen/active-incidents', [CitizenController::class, 'getActiveIncidentsHTML'])->name('citizen.active.incidents');
    Route::post('/citizen/sos', [CitizenController::class, 'storeSOS'])->name('citizen.sos');
    Route::post('/citizen/profile', [CitizenController::class, 'updateProfile'])->name('citizen.profile.update');
});

// Responder Routes
Route::middleware(['auth', 'role:Responder'])->group(function () {
    Route::get('/responder', [ResponderController::class, 'index'])->name('responder.dashboard');
    Route::post('/responder/status/{incident}', [ResponderController::class, 'updateStatus'])->name('responder.status');
});

// Admin Routes
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/alert', [AdminController::class, 'storeAlert'])->name('admin.alert');
    Route::delete('/admin/alert/{alert}', [AdminController::class, 'destroyAlert'])->name('admin.alert.destroy');
    Route::post('/admin/responder', [AdminController::class, 'storeResponder'])->name('admin.responder.store');
    Route::put('/admin/responder/{user}', [AdminController::class, 'updateResponder'])->name('admin.responder.update');
    Route::delete('/admin/responder/{user}', [AdminController::class, 'destroyResponder'])->name('admin.responder.destroy');
});

use App\Http\Controllers\MessageController;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Chat Routes
    Route::get('/incidents/{incident}/messages', [MessageController::class, 'fetchMessages'])->name('messages.fetch');
    Route::post('/incidents/{incident}/messages', [MessageController::class, 'sendMessage'])->name('messages.send');
    
    // Responder Tracking Routes
    Route::get('/incidents/{incident}/responder-location', [ResponderController::class, 'getResponderLocation'])->name('responder.location.get');
    Route::post('/incidents/{incident}/responder-location', [ResponderController::class, 'updateResponderLocation'])->name('responder.location.update');
});

require __DIR__.'/auth.php';
