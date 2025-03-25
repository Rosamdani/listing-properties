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
        Schema::create('property_value_estimates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained();
            $table->decimal('estimated_value', 15, 2);
            $table->timestamp('estimate_date');
            $table->decimal('confidence_score', 5, 2)->nullable();
            $table->decimal('high_estimate', 15, 2)->nullable();
            $table->decimal('low_estimate', 15, 2)->nullable();
            $table->string('algorithm_version', 50)->nullable();
            $table->json('factors')->nullable();
            $table->string('created_by', 50)->default('system');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_value_estimates');
    }
};