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
        // 1. Safe Schema (Should NOT trigger False Positives):
        // Non-monetary floats: rating, latitude, longitude
        // Properly indexed foreign key: user_id with ->index()
        Schema::create('venue_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->index();
            $table->string('venue_name');
            $table->float('rating'); // Safe: non-monetary rating metric
            $table->float('latitude'); // Safe: geographic coordinate
            $table->float('longitude'); // Safe: geographic coordinate
            $table->timestamps();
        });

        // 2. Flawed Migration Patterns (Should be flagged accurately):
        // Flaw A: Monetary column using float ($table->float('amount'))
        // Flaw B: Foreign key without index ($table->foreignId('order_id')->constrained())
        Schema::create('billings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained(); // Missing index on FK
            $table->float('amount'); // Monetary float risk
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billings');
        Schema::dropIfExists('venue_reviews');
    }
};
