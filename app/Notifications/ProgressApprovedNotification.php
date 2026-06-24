<?php

namespace App\Notifications;

use App\Models\ProgressSkripsi;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProgressApprovedNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly ProgressSkripsi $progress) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Progress Disetujui',
            'message' => $this->progress->bab->fullLabel().' telah disetujui (ACC) oleh dosen pembimbing.',
            'url' => route('progress-skripsi.index'),
        ];
    }
}
