<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Safe table with indexed foreign key and clean types (Safe, zero FP):
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained()->index();
            $table->string('name');
            $table->float('rating'); // Safe non-monetary float metric
            $table->timestamps();
        });

        // Flawed table with floating point currency and missing foreign key index:
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained(); // Flaw: Unindexed FK
            $table->float('total_amount'); // Flaw: Floating point currency
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('stores');
    }
};
