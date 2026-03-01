<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Inertia\Inertia;


class AppointmentController extends Controller
{
   public function index(Request $request)
    {
        $currentYear = Carbon::now()->year;

        $query = Appointment::with('user', 'services');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('appointment_date', $request->date);
        }else{
            $query->whereYear('appointment_date', $currentYear);
        }

        $appointments = $query->orderBy('appointment_date', 'asc')->get()
            ->map(function ($appointment) {

                //Combine date and appointment time
                $start = Carbon::parse($appointment->appointment_date . ' ' . $appointment->appointment_time);
                $totalDuration = $appointment->services->sum('duration');
                $end = $start->copy()->addMinutes($totalDuration);
                $servicesFormatted = $appointment->services->pluck('name')->implode(' | ');


        

                // $services = $appointment->services;
                // $firstService = $services->first()?->name ?? '';
                // $extraCount = $services->count() - 1;

                // $servicesList = $appointment->services->map(function ($service) {
                //     return $service->name;
                // })->implode("|");
                // $title = $appointment->user->name . "|" . $servicesList;

                // if($extraCount > 0) {
                //     $title .= "+{$extraCount}";
                // }
                return [
                    'id' => $appointment->id,
                    'title' => $appointment->user->name,
                    'services' => $servicesFormatted,
                    'start' => $start->toDateTimeString(),
                    'end' => $end->toDateTimeString(),
                    'status' => $appointment->status,

                    'color' => match($appointment->status) {
                        'confirmed' => '#22c55e',
                        'completed' => '#facc15',
                        'cancelled' => '#ef4444',
                        default => '#3b82f6'
                    },
                ];
            });

        return Inertia::render('Admin/Appointments', [
            'appointments' => $appointments,
            'filters' => $request->only(['status', 'date']),
        ]);
    }


    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'status' => 'required|in:confirmed,completed,cancelled',
        ]);

        $appointment->status = $validated['status'];
        $appointment->save();

        return redirect()->back()->with('success', 'Appointment status updated successfully!');

    }
}
