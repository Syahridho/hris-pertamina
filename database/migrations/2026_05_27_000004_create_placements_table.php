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
        Schema::create('placements', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->longText('description')->nullable();
            // Format: "latitude,longitude" — digunakan untuk validasi geofencing absensi
            $table->string('coordinate', 225)->nullable()->comment('Format: latitude,longitude');
            $table->integer('radius')->default(100)->comment('Radius geofence dalam meter');
            $table->foreignId('schedule_id')->constrained('schedules')->onDelete('restrict');
            $table->foreignId('project_id')->constrained('projects')->onDelete('restrict');
            $table->timestamps();

            // Recommended indexes
            $table->index('project_id');
            $table->index('schedule_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('placements');
    }
};
