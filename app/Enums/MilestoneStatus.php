<?php

namespace App\Enums;

enum MilestoneStatus: string
{
    case Belum = 'belum';
    case Dijadwalkan = 'dijadwalkan';
    case Selesai = 'selesai';

    public function label(): string
    {
        return match ($this) {
            self::Belum => 'Belum',
            self::Dijadwalkan => 'Dijadwalkan',
            self::Selesai => 'Selesai',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Belum => 'slate',
            self::Dijadwalkan => 'blue',
            self::Selesai => 'emerald',
        };
    }
}
