<?php

use App\Enum\Neighborhood\EducationLevel;
use App\Enum\Neighborhood\EducationType;
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
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->foreignId('neighborhood_id')->constrained();
            $table->string('name', 255);
            $table->enum('education_level', EducationLevel::values());
            $table->enum('type', EducationType::values());
            $table->string('address', 255)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('rating', 3, 2)->nullable();
            $table->text('description')->nullable();
            $table->string('website', 255)->nullable();
            $table->string('contact_phone', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};