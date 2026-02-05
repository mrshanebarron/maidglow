<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'hourly_rate',
        'color',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'hourly_rate' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function assignedJobs(): HasMany
    {
        return $this->hasMany(CleaningJob::class, 'assigned_to');
    }

    public function timeEntries(): HasMany
    {
        return $this->hasMany(TimeEntry::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isManager(): bool
    {
        return in_array($this->role, ['admin', 'manager']);
    }

    public function isTech(): bool
    {
        return $this->role === 'tech';
    }

    public function todaysJobs()
    {
        return $this->assignedJobs()
            ->whereDate('scheduled_date', today())
            ->orderBy('scheduled_time')
            ->get();
    }

    public function calculateEarnings($startDate, $endDate)
    {
        $entries = $this->timeEntries()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at')
            ->get();

        $totalMinutes = 0;
        $clockIn = null;

        foreach ($entries as $entry) {
            if ($entry->type === 'clock_in') {
                $clockIn = $entry->created_at;
            } elseif ($entry->type === 'clock_out' && $clockIn) {
                $totalMinutes += $clockIn->diffInMinutes($entry->created_at);
                $clockIn = null;
            }
        }

        $hours = $totalMinutes / 60;
        return round($hours * ($this->hourly_rate ?? 0), 2);
    }
}
