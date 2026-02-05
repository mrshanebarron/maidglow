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
        Schema::create('cleaning_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->date('scheduled_date');
            $table->time('scheduled_time');
            $table->integer('estimated_duration')->default(120); // minutes
            $table->decimal('quoted_price', 8, 2);
            $table->decimal('final_price', 8, 2)->nullable();
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled', 'no_show'])->default('scheduled');
            $table->text('notes')->nullable();
            $table->text('tech_notes')->nullable(); // Notes from the tech
            $table->boolean('is_recurring')->default(false);
            $table->enum('recurrence_frequency', ['weekly', 'biweekly', 'monthly', 'one_time'])->default('one_time');
            $table->foreignId('parent_job_id')->nullable()->constrained('cleaning_jobs')->onDelete('set null');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('rating')->nullable(); // 1-5 stars
            $table->text('review')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cleaning_jobs');
    }
};
