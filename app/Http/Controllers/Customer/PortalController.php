<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortalController extends Controller
{
    public function dashboard()
    {
        $customer = Auth::guard('customer')->user();

        $upcomingJobs = $customer->cleaningJobs()
            ->with(['service', 'assignedTech'])
            ->where('scheduled_date', '>=', today())
            ->whereIn('status', ['scheduled', 'in_progress'])
            ->orderBy('scheduled_date')
            ->orderBy('scheduled_time')
            ->get();

        $pastJobs = $customer->cleaningJobs()
            ->with(['service', 'assignedTech'])
            ->where('status', 'completed')
            ->orderByDesc('scheduled_date')
            ->limit(5)
            ->get();

        return view('customer.dashboard', compact('customer', 'upcomingJobs', 'pastJobs'));
    }

    public function bookNew()
    {
        $customer = Auth::guard('customer')->user();
        $services = Service::where('is_active', true)->orderBy('sort_order')->get();

        return view('customer.book', compact('customer', 'services'));
    }

    public function history()
    {
        $customer = Auth::guard('customer')->user();

        $jobs = $customer->cleaningJobs()
            ->with(['service', 'assignedTech'])
            ->orderByDesc('scheduled_date')
            ->paginate(20);

        return view('customer.history', compact('jobs'));
    }
}
