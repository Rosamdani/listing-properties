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
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained();
            $table->string('license_number', 100)->unique();
            $table->string('agency_name', 255)->nullable();
            $table->integer('experience_years')->nullable();
            $table->json('specializations')->nullable();
            $table->json('service_areas')->nullable();
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->integer('total_sales')->default(0);
            $table->decimal('total_sales_volume', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};