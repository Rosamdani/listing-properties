<?php

use App\Enum\Property\TourStatus;
use App\Enum\Property\TourType;
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
        Schema::create('property_tours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('agent_id')->nullable()->constrained('agents');
            $table->enum('tour_type', TourType::cases());
            $table->timestamp('tour_date');
            $table->integer('duration')->default(30);
            $table->enum('status', TourStatus::cases())->default(TourStatus::REQUESTED);
            $table->text('notes')->nullable();
            $table->string('cancelled_reason', 255)->nullable();
            $table->text('feedback')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_tours');
    }
};