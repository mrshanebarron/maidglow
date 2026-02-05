<?php

namespace App\Http\Controllers\Tech;

use App\Http\Controllers\Controller;
use App\Models\CleaningJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $todaysJobs = CleaningJob::with(['customer', 'service'])
            ->where('assigned_to', $user->id)
            ->whereDate('scheduled_date', today())
            ->orderBy('scheduled_time')
            ->get();

        $upcomingJobs = CleaningJob::with(['customer', 'service'])
            ->where('assigned_to', $user->id)
            ->where('scheduled_date', '>', today())
            ->where('status', 'scheduled')
            ->orderBy('scheduled_date')
            ->orderBy('scheduled_time')
            ->limit(5)
            ->get();

        // Calculate earnings for current pay period (this week)
        $weekStart = now()->startOfWeek();
        $weekEnd = now()->endOfWeek();
        $weeklyEarnings = $user->calculateEarnings($weekStart, $weekEnd);

        return view('tech.dashboard', compact('todaysJobs', 'upcomingJobs', 'weeklyEarnings'));
    }

    public function job(CleaningJob $job)
    {
        if ($job->assigned_to !== Auth::id()) {
            abort(403);
        }

        $job->load(['customer', 'service', 'timeEntries']);

        return view('tech.job', compact('job'));
    }

    public function clockIn(Request $request, CleaningJob $job)
    {
        if ($job->assigned_to !== Auth::id()) {
            abort(403);
        }

        if (!$job->canClockIn()) {
            return back()->with('error', 'Cannot clock in to this job.');
        }

        $job->clockIn(
            Auth::user(),
            $request->input('latitude'),
            $request->input('longitude')
        );

        return back()->with('success', 'Clocked in successfully!');
    }

    public function clockOut(Request $request, CleaningJob $job)
    {
        if ($job->assigned_to !== Auth::id()) {
            abort(403);
        }

        if (!$job->canClockOut()) {
            return back()->with('error', 'Cannot clock out of this job.');
        }

        $job->clockOut(
            Auth::user(),
            $request->input('latitude'),
            $request->input('longitude')
        );

        return back()->with('success', 'Job completed! Great work!');
    }

    public function addNote(Request $request, CleaningJob $job)
    {
        if ($job->assigned_to !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'tech_notes' => 'required|string|max:1000',
        ]);

        $job->update($validated);

        return back()->with('success', 'Notes saved.');
    }

    public function earnings()
    {
        $user = Auth::user();

        // This week
        $thisWeekStart = now()->startOfWeek();
        $thisWeekEnd = now()->endOfWeek();
        $thisWeekEarnings = $user->calculateEarnings($thisWeekStart, $thisWeekEnd);

        // Last week
        $lastWeekStart = now()->subWeek()->startOfWeek();
        $lastWeekEnd = now()->subWeek()->endOfWeek();
        $lastWeekEarnings = $user->calculateEarnings($lastWeekStart, $lastWeekEnd);

        // This month
        $thisMonthStart = now()->startOfMonth();
        $thisMonthEnd = now()->endOfMonth();
        $thisMonthEarnings = $user->calculateEarnings($thisMonthStart, $thisMonthEnd);

        // Recent completed jobs
        $recentJobs = CleaningJob::with(['customer', 'service'])
            ->where('assigned_to', $user->id)
            ->where('status', 'completed')
            ->orderByDesc('completed_at')
            ->limit(10)
            ->get();

        return view('tech.earnings', compact(
            'thisWeekEarnings',
            'lastWeekEarnings',
            'thisMonthEarnings',
            'recentJobs'
        ));
    }
}
