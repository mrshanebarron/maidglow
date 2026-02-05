<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CleaningJob;
use App\Models\Customer;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $query = CleaningJob::with(['customer', 'service', 'assignedTech']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tech')) {
            $query->where('assigned_to', $request->tech);
        }

        if ($request->filled('date')) {
            $query->whereDate('scheduled_date', $request->date);
        }

        $jobs = $query->orderByDesc('scheduled_date')
            ->orderBy('scheduled_time')
            ->paginate(20);

        $techs = User::where('role', 'tech')->where('is_active', true)->get();

        return view('admin.jobs.index', compact('jobs', 'techs'));
    }

    public function create()
    {
        $customers = Customer::where('is_active', true)->orderBy('name')->get();
        $services = Service::where('is_active', true)->orderBy('sort_order')->get();
        $techs = User::where('role', 'tech')->where('is_active', true)->get();

        return view('admin.jobs.create', compact('customers', 'services', 'techs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_id' => 'required|exists:services,id',
            'assigned_to' => 'nullable|exists:users,id',
            'scheduled_date' => 'required|date',
            'scheduled_time' => 'required',
            'quoted_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'is_recurring' => 'boolean',
            'recurrence_frequency' => 'nullable|in:weekly,biweekly,monthly,one_time',
        ]);

        $service = Service::find($validated['service_id']);
        $validated['estimated_duration'] = $service->estimated_minutes;
        $validated['status'] = 'scheduled';

        $job = CleaningJob::create($validated);

        return redirect()->route('admin.jobs.show', $job)
            ->with('success', 'Job scheduled successfully.');
    }

    public function show(CleaningJob $job)
    {
        $job->load(['customer', 'service', 'assignedTech', 'timeEntries.user']);
        $techs = User::where('role', 'tech')->where('is_active', true)->get();

        return view('admin.jobs.show', compact('job', 'techs'));
    }

    public function edit(CleaningJob $job)
    {
        $customers = Customer::where('is_active', true)->orderBy('name')->get();
        $services = Service::where('is_active', true)->orderBy('sort_order')->get();
        $techs = User::where('role', 'tech')->where('is_active', true)->get();

        return view('admin.jobs.edit', compact('job', 'customers', 'services', 'techs'));
    }

    public function update(Request $request, CleaningJob $job)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_id' => 'required|exists:services,id',
            'assigned_to' => 'nullable|exists:users,id',
            'scheduled_date' => 'required|date',
            'scheduled_time' => 'required',
            'quoted_price' => 'required|numeric|min:0',
            'status' => 'required|in:scheduled,in_progress,completed,cancelled,no_show',
            'notes' => 'nullable|string',
        ]);

        $job->update($validated);

        return redirect()->route('admin.jobs.show', $job)
            ->with('success', 'Job updated successfully.');
    }

    public function destroy(CleaningJob $job)
    {
        $job->delete();

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Job deleted successfully.');
    }

    public function assign(Request $request, CleaningJob $job)
    {
        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $job->update($validated);

        return back()->with('success', 'Technician assigned successfully.');
    }

    public function getPrice(Request $request)
    {
        $service = Service::find($request->service_id);
        $customer = Customer::find($request->customer_id);

        if (!$service || !$customer) {
            return response()->json(['price' => 0]);
        }

        $price = $service->calculatePrice(
            $customer->bedrooms ?? 0,
            $customer->bathrooms ?? 0,
            $customer->square_feet ?? 0
        );

        return response()->json(['price' => $price]);
    }
}
