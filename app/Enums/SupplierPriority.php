<?php

declare(strict_types=1);

namespace App\Enums;

use App\Helpers\Concerns\EnumSerializable;

enum SupplierPriority: string
{
    use EnumSerializable;

    case HIGH = 'high';

    case LOW = 'low';

}
