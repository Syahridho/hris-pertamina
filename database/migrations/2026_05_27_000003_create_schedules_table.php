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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->longText('description')->nullable();
            // Menyimpan detail jadwal kerja (shift, hari, jam masuk/pulang) dalam format JSON
            $table->json('datetimes')->nullable()->comment('JSON: shift schedule detail (days, clock_in, clock_out)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
