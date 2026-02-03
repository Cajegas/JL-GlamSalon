<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class CustomerBookController extends Controller
{
    // Show booking page
    public function index()
    {
        return Inertia::render('Customer/BookAppointment', [
            'services' => Service::latest()->get(),
        ]);
    }

    // Store new booking
    public function store(Request $request)
    {
        $appointment = Appointment::create([
            'user_id' => auth()->id(),
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'total_price' => $request->total_price,
            'status' => 'pending',
        ]);

        foreach ($request->services as $service) {
            $appointment->services()->attach(
                $service['id'],
                ['price' => $service['price']]
            );
        }

        return redirect()->back()->with('success', 'Booking confirmed!');
    }

    // Show customer appointments with cancellation rules
    public function appointments()
    {
        $appointments = Appointment::with('services')
            ->where('user_id', auth()->id())
            ->latest()
            ->get()
            ->map(function ($appointment) {
                $appointmentDateTime = Carbon::parse("{$appointment->appointment_date} {$appointment->appointment_time}");
                $now = Carbon::now();

                $diffInHours = $appointmentDateTime->diffInHours($now, false);

                // Determine cancellation rules
                $appointment->can_cancel = $diffInHours >= 24; // Can cancel 24+ hours before
                $appointment->short_notice = $diffInHours < 24 && $diffInHours > 0; // View-only for <24h
                $appointment->past_appointment = $diffInHours <= 0; // Already started/past

                $appointment->services = $appointment->services->map(function ($s) {
                    return [
                        'id' => $s->id,
                        'category' => $s->category,
                        'name' => $s->name,
                        'duration' => $s->duration,
                        'price' => $s->price,
                    ];
                })->toArray();

                return $appointment;
            });

        return Inertia::render('Customer/Appointments', [
            'appointments' => $appointments->toArray(),
            'services' => Service::all(),
        ]);
    }

    // Cancel appointment
    public function cancel(Appointment $appointment)
    {
        if ($appointment->user_id !== auth()->id()) {
            abort(403);
        }

        $appointmentDateTime = Carbon::parse(
            "{$appointment->appointment_date} {$appointment->appointment_time}"
        );

        if (now()->addHours(24)->gte($appointmentDateTime)) {
            return back()->with('error', 'Cannot cancel within 24 hours.');
        }

        $appointment->update(['status' => 'cancelled']);

        return back()->with('success', 'Appointment cancelled successfully.');
    }

}
