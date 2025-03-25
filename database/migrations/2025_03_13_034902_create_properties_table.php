<?php

use App\Enum\Property\Furnished;
use App\Enum\Property\ListingType;
use App\Enum\Property\Status;
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
            $table->id();
            $table->foreignId('owner_id')->constrained('users');
            $table->foreignId('agent_id')->nullable()->constrained('agents');
            $table->enum('property_type', PropertyType::cases());
            $table->enum('listing_type', ListingType::cases());
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2);
            $table->string('currency', 3)->default('IDR');
            $table->integer('bedrooms')->nullable();
            $table->decimal('bathrooms', 3, 1)->nullable();
            $table->decimal('building_size', 10, 2)->nullable();
            $table->decimal('land_size', 10, 2)->nullable();
            $table->integer('year_built')->nullable();
            $table->integer('floors')->nullable();
            $table->integer('parking_spots')->nullable();
            $table->enum('furnished', Furnished::cases())->nullable();
            $table->enum('status', Status::cases())->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->integer('view_count')->default(0);
            $table->string('slug', 255)->unique();
            $table->string('virtual_tour_url', 255)->nullable();
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