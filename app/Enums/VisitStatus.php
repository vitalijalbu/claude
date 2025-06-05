<?php

declare(strict_types=1);

namespace App\Enums;

use App\Helpers\Concerns\EnumSerializable;

enum VisitStatus: string
{
    use EnumSerializable;

    case CONFIRM_PLANNING = 'confirm_planning';
    case PLANNED = 'planned';
    case TO_REVIEW = 'to_review';
    case COMPLETED = 'completed';
    case TEST_NEEDED = 'test_needed';
    case REJECTED = 'rejected';
}
