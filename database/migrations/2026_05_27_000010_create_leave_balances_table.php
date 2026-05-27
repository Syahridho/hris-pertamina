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
        Schema::create('leave_balances', function (Blueprint $table) {
            $table->id();
            // Salah satu diisi: manpower atau supervisor yang memiliki saldo cuti
            $table->foreignId('manpower_id')->nullable()->constrained('manpowers')->onDelete('cascade');
            $table->foreignId('supervisor_id')->nullable()->constrained('supervisors')->onDelete('cascade');
            $table->year('year')->comment('Tahun kuota cuti berlaku');
            $table->integer('total_days')->default(12)->comment('Total kuota cuti setahun');
            $table->integer('used_days')->default(0)->comment('Cuti yang sudah digunakan');
            $table->integer('remaining_days')->storedAs('total_days - used_days')->comment('Sisa cuti (computed)');
            $table->timestamps();

            $table->index(['manpower_id', 'year']);
            $table->index(['supervisor_id', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_balances');
    }
};
