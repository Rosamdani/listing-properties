<?php

use App\Enum\PropertyStatus;
use App\Enum\PropertyType;
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
        Schema::create('properties', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->enum('type', PropertyType::getValues());
            $table->unsignedBigInteger('price');
            $table->string('location');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->integer('bedrooms')->default(0);
            $table->integer('bathrooms')->default(0);
            $table->integer('garages')->default(0);
            $table->integer('area')->default(0);
            $table->boolean('furnished')->default(false);
            $table->date('available_from')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', PropertyStatus::getValues());
            $table->boolean('featured')->default(false);
            $table->json('amenities')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('slug')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
