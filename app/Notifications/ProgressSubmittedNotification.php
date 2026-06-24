<?php

namespace App\Notifications;

use App\Models\ProgressSkripsi;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProgressSubmittedNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly ProgressSkripsi $progress) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $this->progress->loadMissing('mahasiswa.mahasiswaProfile');
        $profile = $this->progress->mahasiswa->mahasiswaProfile;

        return [
            'title' => 'Progress Skripsi Baru',
            'message' => $this->progress->mahasiswa->name.' mengirim '.$this->progress->bab->fullLabel().'.',
            'url' => $profile
                ? route('mahasiswa-bimbingan.show', $profile)
                : route('mahasiswa-bimbingan.index'),
        ];
    }
}
