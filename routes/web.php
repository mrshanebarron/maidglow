<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Tech\DashboardController as TechDashboard;
use App\Http\Controllers\Customer\PortalController as CustomerPortal;
use App\Http\Controllers\Customer\AuthController as CustomerAuth;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return redirect()->route('booking.calculator');
});

// Booking routes (public)
Route::prefix('book')->name('booking.')->group(function () {
    Route::get('/', [BookingController::class, 'calculator'])->name('calculator');
    Route::post('/calculate', [BookingController::class, 'calculate'])->name('calculate');
    Route::get('/form', [BookingController::class, 'book'])->name('form');
    Route::post('/store', [BookingController::class, 'store'])->name('store');
    Route::get('/confirmation/{job}', [BookingController::class, 'confirmation'])->name('confirmation');
});

// Dashboard redirect based on role
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->isManager()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('tech.dashboard');
})->middleware(['auth'])->name('dashboard');

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::get('/calendar', [AdminDashboard::class, 'calendar'])->name('calendar');
    Route::get('/calendar/events', [AdminDashboard::class, 'calendarEvents'])->name('calendar.events');

    // Jobs
    Route::get('/jobs/get-price', [JobController::class, 'getPrice'])->name('jobs.getPrice');
    Route::resource('jobs', JobController::class);
    Route::post('/jobs/{job}/assign', [JobController::class, 'assign'])->name('jobs.assign');

    // Customers
    Route::resource('customers', CustomerController::class);

    // Employees
    Route::resource('employees', EmployeeController::class);
});

// Tech routes (mobile-friendly)
Route::prefix('tech')->name('tech.')->middleware(['auth'])->group(function () {
    Route::get('/', [TechDashboard::class, 'index'])->name('dashboard');
    Route::get('/job/{job}', [TechDashboard::class, 'job'])->name('job');
    Route::post('/job/{job}/clock-in', [TechDashboard::class, 'clockIn'])->name('job.clockIn');
    Route::post('/job/{job}/clock-out', [TechDashboard::class, 'clockOut'])->name('job.clockOut');
    Route::post('/job/{job}/note', [TechDashboard::class, 'addNote'])->name('job.note');
    Route::get('/earnings', [TechDashboard::class, 'earnings'])->name('earnings');
});

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Customer Portal routes
Route::prefix('my-account')->name('customer.')->group(function () {
    Route::get('/login', [CustomerAuth::class, 'showLogin'])->name('login');
    Route::post('/login', [CustomerAuth::class, 'login']);
    Route::post('/logout', [CustomerAuth::class, 'logout'])->name('logout');

    Route::middleware('auth:customer')->group(function () {
        Route::get('/', [CustomerPortal::class, 'dashboard'])->name('dashboard');
        Route::get('/book', [CustomerPortal::class, 'bookNew'])->name('book');
        Route::get('/history', [CustomerPortal::class, 'history'])->name('history');
    });
});

require __DIR__.'/auth.php';
