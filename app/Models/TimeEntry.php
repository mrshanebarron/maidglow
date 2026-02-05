<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cleaning_job_id',
        'type',
        'latitude',
        'longitude',
        'address',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cleaningJob(): BelongsTo
    {
        return $this->belongsTo(CleaningJob::class);
    }

    public function hasLocation(): bool
    {
        return $this->latitude !== null && $this->longitude !== null;
    }

    public function googleMapsUrl(): ?string
    {
        if (!$this->hasLocation()) {
            return null;
        }
        return "https://www.google.com/maps?q={$this->latitude},{$this->longitude}";
    }
}
