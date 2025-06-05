<?php

declare(strict_types=1);

namespace App\Enums;

use App\Helpers\Concerns\EnumSerializable;

enum SupplierScore: string
{
    use EnumSerializable;

    case A = 'A';
    case B = 'B';
    case C = 'C';
    case D = 'D';
    case E = 'E';

    public function getNumericValue(): int
    {
        return match ($this) {
            self::A => 5,
            self::B => 4,
            self::C => 3,
            self::D => 2,
            self::E => 1,
        };
    }

    public function getPercentage(): float
    {
        $max = 5;

        return round(($this->getNumericValue() / $max) * 100, 2);
    }
}
