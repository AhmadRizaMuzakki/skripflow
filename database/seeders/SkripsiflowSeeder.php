<?php

namespace Database\Seeders;

use App\Enums\BabSkripsi;
use App\Enums\JenisMilestone;
use App\Enums\MilestoneStatus;
use App\Enums\ProgressSkripsiStatus;
use App\Models\MahasiswaProfile;
use App\Models\Milestone;
use App\Models\ProgressSkripsi;
use App\Models\User;
use App\Notifications\ProgressReviseNotification;
use App\Notifications\ProgressSubmittedNotification;
use App\Notifications\WelcomeNotification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SkripsiflowSeeder extends Seeder
{
    public function run(): void
    {
        $permission = Permission::create([
            'name' => 'kelola skripsi',
            'guard_name' => 'web',
        ]);

        $roleMahasiswa = Role::create(['name' => 'mahasiswa', 'guard_name' => 'web']);
        $roleDosen = Role::create(['name' => 'dosen', 'guard_name' => 'web']);
        $roleAdmin = Role::create(['name' => 'admin', 'guard_name' => 'web']);

        $roleMahasiswa->givePermissionTo($permission);
        $roleAdmin->givePermissionTo($permission);

        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'nomor_induk' => 'ADMIN001',
            'password' => Hash::make('root'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole($roleAdmin);

        $dosen = User::create([
            'name' => 'Venera Gania S.Si., M.T.I.',
            'email' => 'budi_santoso@gmail.com',
            'nomor_induk' => '198001012010011001',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $dosen->assignRole($roleDosen);

        $mahasiswa1 = User::create([
            'name' => 'Ahmad Riza Muzakki',
            'email' => 'ahmad_fauzi@gmail.com',
            'nomor_induk' => '2211501001',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $mahasiswa1->assignRole($roleMahasiswa);

        $mahasiswa2 = User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti_nurhaliza@gmail.com',
            'nomor_induk' => '2211501002',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $mahasiswa2->assignRole($roleMahasiswa);

        $mahasiswa3 = User::create([
            'name' => 'Budi Pratama',
            'email' => 'budi_pratama@gmail.com',
            'nomor_induk' => '2211501003',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $mahasiswa3->assignRole($roleMahasiswa);

        MahasiswaProfile::create([
            'user_id' => $mahasiswa1->id,
            'dosen_pembimbing_id' => $dosen->id,
            'judul_skripsi' => 'Sistem Informasi Monitoring Progress Skripsi Berbasis Web',
            'total_progress' => 35,
            'created_at' => '2026-03-10',
        ]);

        MahasiswaProfile::create([
            'user_id' => $mahasiswa2->id,
            'dosen_pembimbing_id' => $dosen->id,
            'judul_skripsi' => 'Aplikasi Mobile E-Learning Berbasis Android',
            'total_progress' => 10,
            'created_at' => '2026-02-15',
        ]);

        MahasiswaProfile::create([
            'user_id' => $mahasiswa3->id,
            'dosen_pembimbing_id' => $dosen->id,
            'judul_skripsi' => 'Analisis Sentimen Media Sosial Menggunakan Machine Learning',
            'total_progress' => 20,
            'created_at' => '2026-02-20',
        ]);

        foreach ([$mahasiswa1, $mahasiswa2, $mahasiswa3] as $mhs) {
            Milestone::create([
                'mahasiswa_id' => $mhs->id,
                'jenis_milestone' => JenisMilestone::Proposal,
                'tanggal_pelaksanaan' => '2026-04-29',
                'status' => MilestoneStatus::Selesai,
                'keterangan' => 'Seminar proposal lulus.',
            ]);

            Milestone::create([
                'mahasiswa_id' => $mhs->id,
                'jenis_milestone' => JenisMilestone::SeminarHasil,
                'status' => MilestoneStatus::Belum,
                'keterangan' => null,
            ]);

            Milestone::create([
                'mahasiswa_id' => $mhs->id,
                'jenis_milestone' => JenisMilestone::SidangAkhir,
                'status' => MilestoneStatus::Belum,
                'keterangan' => null,
            ]);
        }

        // Ahmad: Bab1 ACC, Bab2 perlu revisi (kritis)
        ProgressSkripsi::create([
            'mahasiswa_id' => $mahasiswa1->id,
            'bab' => BabSkripsi::Bab1,
            'status' => ProgressSkripsiStatus::Disetujui,
            'file_path' => 'skripsi/bab1-ahmad-riza.pdf',
            'catatan_revisi' => 'Bab 1 sudah sesuai.',
            'updated_at' => '2026-05-10',
        ]);

        ProgressSkripsi::create([
            'mahasiswa_id' => $mahasiswa1->id,
            'bab' => BabSkripsi::Bab2,
            'status' => ProgressSkripsiStatus::PerluRevisi,
            'file_path' => 'skripsi/bab2-ahmad-riza.pdf',
            'catatan_revisi' => 'Penambahan sub-bab dan perbaikan kutipan.',
            'deadline_revisi' => '2026-06-28',
            'updated_at' => '2026-05-20',
        ]);

        // Siti: Bab1 menunggu review dosen
        ProgressSkripsi::create([
            'mahasiswa_id' => $mahasiswa2->id,
            'bab' => BabSkripsi::Bab1,
            'status' => ProgressSkripsiStatus::Bimbingan,
            'file_path' => 'skripsi/bab1-siti.pdf',
            'catatan_revisi' => 'Sudah menyelesaikan draft bab 1, mohon direview.',
            'updated_at' => now()->subDays(2),
        ]);

        // Budi: Bab1 ACC
        ProgressSkripsi::create([
            'mahasiswa_id' => $mahasiswa3->id,
            'bab' => BabSkripsi::Bab1,
            'status' => ProgressSkripsiStatus::Disetujui,
            'file_path' => 'skripsi/bab1-budi.pdf',
            'catatan_revisi' => 'Bab 1 lengkap dan sesuai.',
            'updated_at' => '2026-05-15',
        ]);

        $this->seedSampleFiles([
            'skripsi/bab1-ahmad-riza.pdf',
            'skripsi/bab2-ahmad-riza.pdf',
            'skripsi/bab1-siti.pdf',
            'skripsi/bab1-budi.pdf',
        ]);

        $mahasiswa1->notify(new WelcomeNotification);

        $progressSiti = ProgressSkripsi::query()
            ->where('mahasiswa_id', $mahasiswa2->id)
            ->where('bab', BabSkripsi::Bab1)
            ->first();

        if ($progressSiti) {
            $dosen->notify(new ProgressSubmittedNotification($progressSiti));
        }

        $progressAhmadBab2 = ProgressSkripsi::query()
            ->where('mahasiswa_id', $mahasiswa1->id)
            ->where('bab', BabSkripsi::Bab2)
            ->first();

        if ($progressAhmadBab2) {
            $mahasiswa1->notify(new ProgressReviseNotification($progressAhmadBab2));
        }
    }

    /**
     * @param  array<int, string>  $paths
     */
    private function seedSampleFiles(array $paths): void
    {
        $disk = Storage::disk('public');
        $samplePdf = "%PDF-1.4\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj\n2 0 obj<</Type/Pages/Kids[3 0 R]/Count 1>>endobj\n3 0 obj<</Type/Page/Parent 2 0 R/MediaBox[0 0 612 792]>>endobj\nxref\n0 4\ntrailer<</Size 4/Root 1 0 R>>\nstartxref\n149\n%%EOF";

        foreach ($paths as $path) {
            if (! $disk->exists($path)) {
                $disk->put($path, $samplePdf);
            }
        }
    }
}
