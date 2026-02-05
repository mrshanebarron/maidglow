<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CleaningJob;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $todaysJobs = CleaningJob::with(['customer', 'service', 'assignedTech'])
            ->whereDate('scheduled_date', today())
            ->orderBy('scheduled_time')
            ->get();

        $stats = [
            'today_jobs' => $todaysJobs->count(),
            'today_revenue' => $todaysJobs->sum('quoted_price'),
            'in_progress' => $todaysJobs->where('status', 'in_progress')->count(),
            'completed_today' => $todaysJobs->where('status', 'completed')->count(),
            'total_customers' => Customer::count(),
            'active_techs' => User::where('role', 'tech')->where('is_active', true)->count(),
        ];

        $upcomingJobs = CleaningJob::with(['customer', 'service', 'assignedTech'])
            ->where('scheduled_date', '>', today())
            ->where('status', 'scheduled')
            ->orderBy('scheduled_date')
            ->orderBy('scheduled_time')
            ->limit(10)
            ->get();

        $techs = User::where('role', 'tech')
            ->where('is_active', true)
            ->withCount(['assignedJobs as today_jobs_count' => function ($q) {
                $q->whereDate('scheduled_date', today());
            }])
            ->get();

        return view('admin.dashboard', compact('todaysJobs', 'stats', 'upcomingJobs', 'techs'));
    }

    public function calendar()
    {
        return view('admin.calendar');
    }

    public function calendarEvents(Request $request)
    {
        $start = $request->input('start');
        $end = $request->input('end');

        $jobs = CleaningJob::with(['customer', 'service', 'assignedTech'])
            ->whereBetween('scheduled_date', [$start, $end])
            ->get();

        $events = $jobs->map(function ($job) {
            $color = $job->assignedTech?->color ?? '#6B7280';

            return [
                'id' => $job->id,
                'title' => $job->customer->name . ' - ' . $job->service->name,
                'start' => $job->scheduled_date->format('Y-m-d') . 'T' . $job->scheduled_time->format('H:i:s'),
                'end' => $job->scheduled_date->format('Y-m-d') . 'T' . $job->scheduled_time->addMinutes($job->estimated_duration)->format('H:i:s'),
                'backgroundColor' => $color,
                'borderColor' => $color,
                'extendedProps' => [
                    'customer' => $job->customer->name,
                    'address' => $job->customer->fullAddress(),
                    'service' => $job->service->name,
                    'tech' => $job->assignedTech?->name ?? 'Unassigned',
                    'status' => $job->status,
                    'price' => '$' . number_format($job->quoted_price, 2),
                ],
            ];
        });

        return response()->json($events);
    }
}
