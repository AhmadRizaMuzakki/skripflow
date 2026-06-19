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
        Schema::create('progress_skripsi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('users')->cascadeOnDelete();
            $table->enum('bab', ['bab_1', 'bab_2', 'bab_3', 'bab_4', 'bab_5'])->nullable();
            $table->enum('status', ['draft', 'bimbingan', 'perlu_revisi', 'disetujui'])->default('draft');
            $table->string('file_path')->nullable();
            $table->text('catatan_revisi')->nullable();
            $table->date('deadline_revisi')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress_skripsi');
    }
};
