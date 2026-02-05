<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'customer';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'city',
        'state',
        'zip',
        'notes',
        'access_instructions',
        'bedrooms',
        'bathrooms',
        'square_feet',
        'has_pets',
        'pet_details',
        'stripe_customer_id',
        'default_payment_method',
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
            'has_pets' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function cleaningJobs(): HasMany
    {
        return $this->hasMany(CleaningJob::class);
    }

    public function upcomingJobs()
    {
        return $this->cleaningJobs()
            ->where('scheduled_date', '>=', today())
            ->where('status', 'scheduled')
            ->orderBy('scheduled_date')
            ->orderBy('scheduled_time');
    }

    public function pastJobs()
    {
        return $this->cleaningJobs()
            ->where('status', 'completed')
            ->orderByDesc('scheduled_date');
    }

    public function getFullAddressAttribute(): string
    {
        return "{$this->address}, {$this->city}, {$this->state} {$this->zip}";
    }

    public function calculatePrice(Service $service): float
    {
        $price = $service->base_price;
        $price += ($this->bedrooms ?? 0) * $service->price_per_bedroom;
        $price += ($this->bathrooms ?? 0) * $service->price_per_bathroom;
        $price += ($this->square_feet ?? 0) * $service->price_per_sqft;

        return round($price, 2);
    }
}
