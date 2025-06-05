<?php

declare(strict_types=1);

namespace App\Enums;

use App\Helpers\Concerns\EnumSerializable;

enum VisitResult: string
{
    use EnumSerializable;

    case PASSED = 'passed';

    case NOT_PASSED = 'not_passed';

    case NEED_TEST = 'need_test';

}
