<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'base_price',
        'price_per_bedroom',
        'price_per_bathroom',
        'price_per_sqft',
        'estimated_minutes',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'base_price' => 'decimal:2',
            'price_per_bedroom' => 'decimal:2',
            'price_per_bathroom' => 'decimal:2',
            'price_per_sqft' => 'decimal:4',
            'is_active' => 'boolean',
        ];
    }

    public function cleaningJobs(): HasMany
    {
        return $this->hasMany(CleaningJob::class);
    }

    public function calculatePrice(int $bedrooms, int $bathrooms, int $sqft): float
    {
        $price = $this->base_price;
        $price += $bedrooms * $this->price_per_bedroom;
        $price += $bathrooms * $this->price_per_bathroom;
        $price += $sqft * $this->price_per_sqft;

        return round($price, 2);
    }

    public function formattedDuration(): string
    {
        $hours = floor($this->estimated_minutes / 60);
        $minutes = $this->estimated_minutes % 60;

        if ($hours > 0 && $minutes > 0) {
            return "{$hours}h {$minutes}m";
        } elseif ($hours > 0) {
            return "{$hours}h";
        }
        return "{$minutes}m";
    }
}
