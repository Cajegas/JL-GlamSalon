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

        $appointments = $query->orderBy('appointment_date', 'asc')->get();

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
