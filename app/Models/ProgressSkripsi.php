<?php

namespace App\Models;

use App\Enums\BabSkripsi;
use App\Enums\ProgressSkripsiStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgressSkripsi extends Model
{
    public const CREATED_AT = null;

    protected $table = 'progress_skripsi';

    protected $fillable = [
        'mahasiswa_id',
        'bab',
        'status',
        'file_path',
        'catatan_revisi',
        'deadline_revisi',
    ];

    protected function casts(): array
    {
        return [
            'bab' => BabSkripsi::class,
            'status' => ProgressSkripsiStatus::class,
            'deadline_revisi' => 'date',
        ];
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }
}
