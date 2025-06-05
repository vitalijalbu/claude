<?php

declare(strict_types=1);

namespace App\Enums;

use App\Helpers\Concerns\EnumSerializable;

enum TestStatus: string
{
    use EnumSerializable;

    case TO_PLAN = 'to_plan';
    case PLANNED = 'planned';
    case TO_REVIEW = 'to_review';
    case COMPLETED = 'completed';

    public function canTransitionTo(self $newStatus): bool
    {
        return match ($this) {
            self::PLANNED => in_array($newStatus, [self::PLANNED]),
            self::PLANNED => in_array($newStatus, [self::TO_REVIEW, self::PLANNED]),
            self::TO_REVIEW => in_array($newStatus, [self::COMPLETED, self::PLANNED]),
            self::COMPLETED => false,
        };
    }
}
