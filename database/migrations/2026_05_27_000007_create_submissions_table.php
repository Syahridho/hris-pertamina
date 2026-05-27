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
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->longText('description')->nullable();
            $table->enum('type', ['cuti', 'sakit', 'izin']);
            // Salah satu dari manpower_id atau supervisor_id yang mengajukan (nullable karena bisa dari keduanya)
            $table->foreignId('manpower_id')->nullable()->constrained('manpowers')->onDelete('set null');
            $table->foreignId('supervisor_id')->nullable()->constrained('supervisors')->onDelete('set null');
            // Menyimpan path file lampiran (surat dokter, surat izin, dsb.)
            $table->longText('file')->nullable()->comment('Path/URL file lampiran');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();

            // Recommended indexes
            $table->index('type');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
