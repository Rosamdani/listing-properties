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
        Schema::create('property_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained();
            $table->integer('views_last_7d')->default(0);
            $table->integer('views_last_30d')->default(0);
            $table->integer('views_total')->default(0);
            $table->integer('saves_last_7d')->default(0);
            $table->integer('saves_last_30d')->default(0);
            $table->integer('saves_total')->default(0);
            $table->integer('inquiries_last_7d')->default(0);
            $table->integer('inquiries_last_30d')->default(0);
            $table->integer('inquiries_total')->default(0);
            $table->decimal('listing_quality_score', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_analytics');
    }
};