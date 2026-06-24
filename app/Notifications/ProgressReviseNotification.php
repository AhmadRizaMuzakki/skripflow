<?php

namespace App\Notifications;

use App\Models\ProgressSkripsi;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProgressReviseNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly ProgressSkripsi $progress) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $deadline = $this->progress->deadline_revisi
            ? ' Batas revisi: '.$this->progress->deadline_revisi->translatedFormat('d M Y').'.'
            : '';

        return [
            'title' => 'Perlu Revisi',
            'message' => $this->progress->bab->fullLabel().' perlu diperbaiki.'.$deadline,
            'url' => route('progress-skripsi.create'),
        ];
    }
}
