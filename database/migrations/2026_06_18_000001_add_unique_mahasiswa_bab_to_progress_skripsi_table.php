<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $duplicates = DB::table('progress_skripsi')
            ->select('mahasiswa_id', 'bab', DB::raw('MAX(id) as keep_id'))
            ->groupBy('mahasiswa_id', 'bab')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $row) {
            DB::table('progress_skripsi')
                ->where('mahasiswa_id', $row->mahasiswa_id)
                ->where('bab', $row->bab)
                ->where('id', '!=', $row->keep_id)
                ->delete();
        }

        Schema::table('progress_skripsi', function (Blueprint $table) {
            $table->unique(['mahasiswa_id', 'bab'], 'progress_skripsi_mahasiswa_bab_unique');
        });
    }

    public function down(): void
    {
        Schema::table('progress_skripsi', function (Blueprint $table) {
            $table->dropUnique('progress_skripsi_mahasiswa_bab_unique');
        });
    }
};
