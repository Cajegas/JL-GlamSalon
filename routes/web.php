<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Customer\CustomerBookController;
use App\Models\Service;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Models\Appointment;
use Inertia\Inertia;
use Carbon\Carbon;

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('dashboard');
    }

     $services = Service::all();

    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'services' => $services,
    ]);
});

// Dashboard route
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $today = Carbon::today();

            $todaysAppointments = Appointment::whereDate('appointment_date',$today)
            ->where('status', 'confirmed')
            ->count();
            
            $pendingAppointments = Appointment::where('status', 'pending')
            ->count();

            $monthlyIncome = Appointment::where('status', 'completed')
            ->whereMonth('appointment_date', $today->month)
            ->sum('total_price');

            return Inertia::render('Admin/AdminDashboard', [
                'user' => $user,
                'todaysAppointments' => $todaysAppointments,
                'pendingAppointments' => $pendingAppointments,
                'monthlyIncome' => $monthlyIncome
            ]);
        }

        $appointments = Appointment::where('user_id', $user->id)
            ->with('services')
            ->get();

        return Inertia::render('Dashboard', [
            'user' => $user,
            'appointments' => $appointments,
        ]);
    })->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', fn () => Inertia::render('Admin/AdminDashboard'))->name('dashboard');
        Route::get('/reports', fn () => Inertia::render('Admin/Reports'))->name('reports');

        // Services
        Route::get('/services', [ServiceController::class, 'index'])->name('services');
        Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
        Route::put('/services/{service}', [ServiceController::class, 'update'])->name('services.update');
        Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');

        // Appointments
        Route::get('appointments', [AppointmentController::class, 'index'])->name('appointments');
        Route::put('appointments/{appointment}', [AppointmentController::class, 'update'])->name('appointments.update');
});

// Customer booking routes
Route::middleware('auth')->prefix('customer')->name('customer.')->group(function () {
    // Booking page
    Route::get('/booking', [CustomerBookController::class, 'index'])->name('bookappointment');
    Route::post('/booking', [CustomerBookController::class, 'store'])->name('booking.store');

    // Customer appointments page
    Route::get('/appointments', [CustomerBookController::class, 'appointments'])->name('appointments');
    Route::post('/appointments/{appointment}/cancel', [CustomerBookController::class, 'cancel'])->name('appointments.cancel');

});

require __DIR__.'/auth.php';
