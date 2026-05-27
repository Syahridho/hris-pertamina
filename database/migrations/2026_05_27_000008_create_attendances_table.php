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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            // Nullable karena bisa diisi oleh manpower ATAU supervisor (tidak keduanya sekaligus)
            $table->foreignId('manpower_id')->nullable()->constrained('manpowers')->onDelete('set null');
            $table->foreignId('supervisor_id')->nullable()->constrained('supervisors')->onDelete('set null');
            $table->dateTime('clock_in')->nullable();
            $table->dateTime('clock_out')->nullable();
            $table->date('date');
            $table->string('status', 20)->default('present')->comment('present, late, absent');
            $table->timestamps();

            // Recommended indexes
            $table->index('date');
            $table->index('manpower_id');
            $table->index('supervisor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
