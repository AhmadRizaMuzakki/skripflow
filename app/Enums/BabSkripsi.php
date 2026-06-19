<?php

namespace App\Enums;

enum BabSkripsi: string
{
    case Bab1 = 'bab_1';
    case Bab2 = 'bab_2';
    case Bab3 = 'bab_3';
    case Bab4 = 'bab_4';
    case Bab5 = 'bab_5';

    public function label(): string
    {
        return match ($this) {
            self::Bab1 => 'Bab 1',
            self::Bab2 => 'Bab 2',
            self::Bab3 => 'Bab 3',
            self::Bab4 => 'Bab 4',
            self::Bab5 => 'Bab 5',
        };
    }

    public function fullLabel(): string
    {
        return match ($this) {
            self::Bab1 => 'Bab 1 - Pendahuluan',
            self::Bab2 => 'Bab 2 - Landasan Teori',
            self::Bab3 => 'Bab 3 - Tinjauan Pustaka',
            self::Bab4 => 'Bab 4 - Metodologi Penelitian',
            self::Bab5 => 'Bab 5 - Hasil & Pembahasan',
        };
    }
}
