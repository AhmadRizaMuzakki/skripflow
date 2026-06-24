<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MahasiswaProfile extends Model
{
    protected $fillable = [
        'user_id',
        'dosen_pembimbing_id',
        'judul_skripsi',
        'total_progress',
    ];

    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'dosen_pembimbing_id' => 'integer',
            'total_progress' => 'integer',
        ];
    }

    public function scopeSupervisedBy(Builder $query, User $viewer): Builder
    {
        if ($viewer->isDosen()) {
            return $query->where('dosen_pembimbing_id', $viewer->id);
        }

        if ($viewer->isAdmin()) {
            return $query;
        }

        return $query->whereRaw('0 = 1');
    }

    public function resolveRouteBinding($value, $field = null)
    {
        $user = auth()->user();

        $query = static::query()->where($field ?? $this->getRouteKeyName(), $value);

        if ($user?->isDosen()) {
            $query->supervisedBy($user);
        }

        return $query->first();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dosenPembimbing(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dosen_pembimbing_id');
    }
}
