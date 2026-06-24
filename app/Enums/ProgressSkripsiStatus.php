<?php

namespace App\Enums;

enum ProgressSkripsiStatus: string
{
    case Draft = 'draft';
    case Bimbingan = 'bimbingan';
    case PerluRevisi = 'perlu_revisi';
    case Disetujui = 'disetujui';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Bimbingan => 'Ditinjau',
            self::PerluRevisi => 'Perlu Revisi',
            self::Disetujui => 'Disetujui',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft => 'slate',
            self::Bimbingan => 'blue',
            self::PerluRevisi => 'amber',
            self::Disetujui => 'emerald',
        };
    }
}
