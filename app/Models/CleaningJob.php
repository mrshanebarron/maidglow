<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CleaningJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'service_id',
        'assigned_to',
        'scheduled_date',
        'scheduled_time',
        'estimated_duration',
        'quoted_price',
        'final_price',
        'status',
        'notes',
        'tech_notes',
        'is_recurring',
        'recurrence_frequency',
        'parent_job_id',
        'started_at',
        'completed_at',
        'rating',
        'review',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_date' => 'date',
            'scheduled_time' => 'datetime:H:i',
            'quoted_price' => 'decimal:2',
            'final_price' => 'decimal:2',
            'is_recurring' => 'boolean',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function assignedTech(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function timeEntries(): HasMany
    {
        return $this->hasMany(TimeEntry::class);
    }

    public function parentJob(): BelongsTo
    {
        return $this->belongsTo(CleaningJob::class, 'parent_job_id');
    }

    public function childJobs(): HasMany
    {
        return $this->hasMany(CleaningJob::class, 'parent_job_id');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('scheduled_date', today());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('scheduled_date', '>=', today())
            ->where('status', 'scheduled')
            ->orderBy('scheduled_date')
            ->orderBy('scheduled_time');
    }

    public function scopeForTech($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function canClockIn(): bool
    {
        if ($this->status !== 'scheduled') {
            return false;
        }

        $lastEntry = $this->timeEntries()->latest()->first();
        return !$lastEntry || $lastEntry->type === 'clock_out';
    }

    public function canClockOut(): bool
    {
        if (!in_array($this->status, ['scheduled', 'in_progress'])) {
            return false;
        }

        $lastEntry = $this->timeEntries()->latest()->first();
        return $lastEntry && $lastEntry->type === 'clock_in';
    }

    public function clockIn(User $user, ?float $latitude = null, ?float $longitude = null): TimeEntry
    {
        $this->update(['status' => 'in_progress', 'started_at' => now()]);

        return $this->timeEntries()->create([
            'user_id' => $user->id,
            'type' => 'clock_in',
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);
    }

    public function clockOut(User $user, ?float $latitude = null, ?float $longitude = null): TimeEntry
    {
        $this->update(['status' => 'completed', 'completed_at' => now()]);

        return $this->timeEntries()->create([
            'user_id' => $user->id,
            'type' => 'clock_out',
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);
    }

    public function totalMinutesWorked(): int
    {
        $entries = $this->timeEntries()->orderBy('created_at')->get();
        $total = 0;
        $clockIn = null;

        foreach ($entries as $entry) {
            if ($entry->type === 'clock_in') {
                $clockIn = $entry->created_at;
            } elseif ($entry->type === 'clock_out' && $clockIn) {
                $total += $clockIn->diffInMinutes($entry->created_at);
                $clockIn = null;
            }
        }

        return $total;
    }

    public function formattedTimeWorked(): string
    {
        $minutes = $this->totalMinutesWorked();
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;

        if ($hours > 0) {
            return "{$hours}h {$mins}m";
        }
        return "{$mins}m";
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'scheduled' => 'blue',
            'in_progress' => 'yellow',
            'completed' => 'green',
            'cancelled' => 'gray',
            'no_show' => 'red',
            default => 'gray',
        };
    }
}
