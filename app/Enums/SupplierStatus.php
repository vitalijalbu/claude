<?php

declare(strict_types=1);

namespace App\Enums;

use App\Helpers\Concerns\EnumSerializable;

enum SupplierStatus: string
{
    use EnumSerializable;

    case NEW = 'new';

    case EXISTING = 'existing';

    case INACTIVE = 'inactive';

}
