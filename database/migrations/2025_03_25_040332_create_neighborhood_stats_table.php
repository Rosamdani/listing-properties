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
        Schema::create('neighborhood_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('neighborhood_id')->constrained();
            $table->decimal('median_price', 15, 2)->nullable();
            $table->decimal('avg_price_sqm', 15, 2)->nullable();
            $table->decimal('price_change_1y', 5, 2)->nullable();
            $table->integer('avg_days_on_market')->nullable();
            $table->integer('total_properties')->nullable();
            $table->integer('total_sales_3m')->nullable();
            $table->decimal('walk_score', 5, 2)->nullable();
            $table->decimal('transit_score', 5, 2)->nullable();
            $table->decimal('safety_score', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('neighborhood_stats');
    }
};