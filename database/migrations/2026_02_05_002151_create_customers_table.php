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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password')->nullable(); // For customer portal
            $table->string('phone')->nullable();
            $table->text('address');
            $table->string('city');
            $table->string('state', 2);
            $table->string('zip', 10);
            $table->text('notes')->nullable();
            $table->text('access_instructions')->nullable(); // Gate code, key location, etc.
            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->integer('square_feet')->nullable();
            $table->boolean('has_pets')->default(false);
            $table->string('pet_details')->nullable();
            $table->string('stripe_customer_id')->nullable();
            $table->string('default_payment_method')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
