<?php

namespace App\Models;

use App\Enums\JenisMilestone;
use App\Enums\MilestoneStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Milestone extends Model
{
    protected $fillable = [
        'mahasiswa_id',
        'jenis_milestone',
        'tanggal_pelaksanaan',
        'status',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'jenis_milestone' => JenisMilestone::class,
            'status' => MilestoneStatus::class,
            'tanggal_pelaksanaan' => 'date',
        ];
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }
}
