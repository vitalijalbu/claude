<?php

declare(strict_types=1);

namespace App\Enums;

use App\Helpers\Concerns\EnumSerializable;

enum InnovationLevel: string
{
    use EnumSerializable;

    case A = 'a';
    case B = 'b';
    case C = 'c';

}
