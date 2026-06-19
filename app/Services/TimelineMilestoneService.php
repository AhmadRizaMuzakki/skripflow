<?php

namespace App\Services;

use App\Enums\JenisMilestone;
use App\Enums\MilestoneStatus;
use App\Enums\ProgressSkripsiStatus;
use App\Models\User;
use Illuminate\Support\Collection;

class TimelineMilestoneService
{
  /**
   * @return Collection<int, array{
   *     key: string,
   *     label: string,
   *     step_number: int,
   *     status: string,
   *     status_label: string,
   *     tanggal: ?string,
   *     keterangan: ?string,
   *     deadline: ?string,
   *     is_active: bool
   * }>
   */
  public function buildForUser(User $user): Collection
  {
    $profile = $user->mahasiswaProfile?->load('dosenPembimbing');
    $milestones = $user->milestones->keyBy(fn ($m) => $m->jenis_milestone->value);
    $progressList = $user->progressSkripsi;

    $steps = [
      [
        'key' => 'pengajuan_topik',
        'label' => 'Pengajuan Topik & SK Pembimbing',
        'resolve' => fn () => $this->resolvePengajuanTopik($profile),
      ],
      [
        'key' => 'proposal',
        'label' => 'Seminar Proposal (Sempro)',
        'resolve' => fn () => $this->resolveFromMilestone($milestones->get(JenisMilestone::Proposal->value)),
      ],
      [
        'key' => 'pengerjaan_naskah',
        'label' => 'Pengerjaan Naskah & Bimbingan',
        'resolve' => fn () => $this->resolvePengerjaanNaskah($progressList, $milestones->get(JenisMilestone::Proposal->value)),
      ],
      [
        'key' => 'seminar_hasil',
        'label' => 'Seminar Hasil (Semhas)',
        'resolve' => fn () => $this->resolveFromMilestone($milestones->get(JenisMilestone::SeminarHasil->value)),
      ],
      [
        'key' => 'sidang_akhir',
        'label' => 'Sidang Akhir Skripsi',
        'resolve' => fn () => $this->resolveFromMilestone($milestones->get(JenisMilestone::SidangAkhir->value)),
      ],
    ];

    $resolved = collect($steps)->map(function ($step, $index) {
      $data = $step['resolve']();

      return [
        'key' => $step['key'],
        'label' => $step['label'],
        'step_number' => $index + 1,
        ...$data,
      ];
    });

    $activeIndex = $resolved->search(fn ($step) => $step['status'] === 'sedang_berjalan');

    if ($activeIndex === false) {
      $activeIndex = $resolved->search(fn ($step) => $step['status'] === 'belum_dimulai');
    }

    return $resolved->values()->map(function ($step, $index) use ($activeIndex, $resolved) {
      $step['is_active'] = $activeIndex !== false && $index === $activeIndex;

      if ($step['status'] === 'belum_dimulai' && blank($step['keterangan'])) {
        $step['keterangan'] = $this->hintForLockedStep($index, $resolved);
      }

      return $step;
    });
  }

  /**
   * @return array{
   *     total_steps: int,
   *     completed_steps: int,
   *     active_step: ?string,
   *     milestone_records: int,
   *     progress_percent: int
   * }
   */
  public function summary(Collection $timelineSteps, int $milestoneRecords): array
  {
    $completed = $timelineSteps->where('status', 'selesai')->count();
    $total = $timelineSteps->count();
    $active = $timelineSteps->firstWhere('is_active', true);

    return [
      'total_steps' => $total,
      'completed_steps' => $completed,
      'active_step' => $active['label'] ?? null,
      'milestone_records' => $milestoneRecords,
      'progress_percent' => $total > 0 ? (int) round(($completed / $total) * 100) : 0,
    ];
  }

  /**
   * @return array{status: string, status_label: string, tanggal: ?string, keterangan: ?string, deadline: ?string}
   */
  private function resolvePengajuanTopik($profile): array
  {
    if ($profile?->dosen_pembimbing_id) {
      return [
        'status' => 'selesai',
        'status_label' => 'Selesai',
        'tanggal' => $profile->created_at?->translatedFormat('d F Y'),
        'keterangan' => $profile->judul_skripsi,
        'deadline' => null,
      ];
    }

    return [
      ...$this->belumDimulai(),
      'keterangan' => 'Ajukan judul skripsi dan tunggu penetapan dosen pembimbing.',
    ];
  }

  /**
   * @return array{status: string, status_label: string, tanggal: ?string, keterangan: ?string, deadline: ?string}
   */
  private function resolveFromMilestone($milestone): array
  {
    if (! $milestone) {
      return $this->belumDimulai();
    }

    return match ($milestone->status) {
      MilestoneStatus::Selesai => [
        'status' => 'selesai',
        'status_label' => 'Selesai',
        'tanggal' => $milestone->tanggal_pelaksanaan?->translatedFormat('d F Y'),
        'keterangan' => $milestone->keterangan,
        'deadline' => null,
      ],
      MilestoneStatus::Dijadwalkan => [
        'status' => 'sedang_berjalan',
        'status_label' => 'Sedang Berjalan',
        'tanggal' => $milestone->tanggal_pelaksanaan?->translatedFormat('d F Y'),
        'keterangan' => $milestone->keterangan ?? 'Jadwal telah ditetapkan, persiapkan dokumen yang diperlukan.',
        'deadline' => null,
      ],
      MilestoneStatus::Belum => [
        'status' => 'belum_dimulai',
        'status_label' => 'Belum Dimulai',
        'tanggal' => null,
        'keterangan' => 'Menunggu penjadwalan dari dosen pembimbing.',
        'deadline' => null,
      ],
    };
  }

  /**
   * @return array{status: string, status_label: string, tanggal: ?string, keterangan: ?string, deadline: ?string}
   */
  private function resolvePengerjaanNaskah(Collection $progressList, $proposalMilestone): array
  {
    if ($proposalMilestone?->status !== MilestoneStatus::Selesai) {
      return [
        ...$this->belumDimulai(),
        'keterangan' => 'Dimulai setelah seminar proposal dinyatakan lulus.',
      ];
    }

    if ($progressList->isEmpty()) {
      return [
        'status' => 'sedang_berjalan',
        'status_label' => 'Sedang Berjalan',
        'tanggal' => now()->translatedFormat('d F Y'),
        'keterangan' => 'Mulai unggah draft bab skripsi untuk bimbingan.',
        'deadline' => null,
      ];
    }

    $allApproved = $progressList->every(fn ($p) => $p->status === ProgressSkripsiStatus::Disetujui);

    if ($allApproved && $progressList->count() >= 5) {
      return [
        'status' => 'selesai',
        'status_label' => 'Selesai',
        'tanggal' => $progressList->max('updated_at')?->translatedFormat('d F Y'),
        'keterangan' => 'Seluruh bab skripsi telah disetujui dosen pembimbing.',
        'deadline' => null,
      ];
    }

    $latest = $progressList->sortByDesc('updated_at')->first();
    $nearestDeadline = $progressList
      ->filter(fn ($p) => $p->deadline_revisi)
      ->sortBy('deadline_revisi')
      ->first();

    $babLabel = $latest->bab?->label() ?? '-';
    $catatan = $latest->catatan_revisi ? rtrim($latest->catatan_revisi, '.').'.' : '';

    return [
      'status' => 'sedang_berjalan',
      'status_label' => 'Sedang Berjalan',
      'tanggal' => $latest->updated_at?->translatedFormat('d F Y'),
      'keterangan' => trim("Bab terakhir: {$babLabel}. {$catatan}"),
      'deadline' => $nearestDeadline?->deadline_revisi?->translatedFormat('d F Y'),
    ];
  }

    private function hintForLockedStep(int $index, Collection $resolved): string
    {
        $current = $resolved->get($index);

        if ($index === 0) {
            return 'Lengkapi pengajuan topik dan SK pembimbing terlebih dahulu.';
        }

        $previous = $resolved->get($index - 1);

        if ($previous && $previous['status'] !== 'selesai') {
            return 'Menunggu tahap sebelumnya selesai.';
        }

        return match ($current['key'] ?? '') {
            'seminar_hasil' => 'Dapat dijadwalkan setelah naskah skripsi disetujui lengkap.',
            'sidang_akhir' => 'Dapat dijadwalkan setelah seminar hasil dinyatakan lulus.',
            default => 'Siap dijadwalkan — hubungi dosen pembimbing Anda.',
        };
    }

  /**
   * @return array{status: string, status_label: string, tanggal: ?string, keterangan: ?string, deadline: ?string}
   */
  private function belumDimulai(): array
  {
    return [
      'status' => 'belum_dimulai',
      'status_label' => 'Belum Dimulai',
      'tanggal' => null,
      'keterangan' => null,
      'deadline' => null,
    ];
  }
}
