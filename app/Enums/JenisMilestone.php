<?php

namespace App\Enums;

enum JenisMilestone: string
{
    case Proposal = 'proposal';
    case SeminarHasil = 'seminar_hasil';
    case SidangAkhir = 'sidang_akhir';

    public function label(): string
    {
        return match ($this) {
            self::Proposal => 'Proposal',
            self::SeminarHasil => 'Seminar Hasil',
            self::SidangAkhir => 'Sidang Akhir',
        };
    }
}
