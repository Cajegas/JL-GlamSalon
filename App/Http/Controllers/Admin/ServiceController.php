<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ServiceController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Services', [
            'services' => Service::latest()->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required',
            'name' => 'required',
            'price' => 'required|numeric',
            'duration' => 'required|integer',
        ]);

        Service::create($request->all());

        return back()->with('success', 'Service added');
    }

      public function update(Request $request, Service $service)
    {
        $data = $request->validate([
            'category' => 'required|string',
            'name' => 'required|string',
            'price' => 'required|numeric',
            'duration' => 'required|integer',
        ]);

        $service->update($data);

        return redirect()->back()->with('success', 'Service updated!');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return back()->with('success', 'Service deleted successfully.');
    }

}
 
