<?php

use App\Enum\User\AreaUnit;
use App\Enum\User\DistanceUnit;
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
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->boolean('notification_email')->default(true);
            $table->boolean('notification_sms')->default(false);
            $table->boolean('notification_push')->default(true);
            $table->string('currency', 3)->default('IDR');
            $table->string('language', 5)->default('id-ID');
            $table->enum('distance_unit', DistanceUnit::cases())->default('km');
            $table->enum('area_unit', AreaUnit::cases())->default('m');
            $table->string('theme', 20)->default('light');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_preferences');
    }
};