<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Standard Clean, Deep Clean, Move-Out, etc.
            $table->text('description')->nullable();
            $table->decimal('base_price', 8, 2);
            $table->decimal('price_per_bedroom', 8, 2)->default(0);
            $table->decimal('price_per_bathroom', 8, 2)->default(0);
            $table->decimal('price_per_sqft', 8, 4)->default(0);
            $table->integer('estimated_minutes')->default(120);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
