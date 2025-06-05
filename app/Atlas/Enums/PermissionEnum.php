<?php

declare(strict_types=1);

namespace App\Atlas\Enums;

enum PermissionEnum: string
{
    // Add here permissions managed by the app
    case UNKNOWN = 'unknown';

    public function label(): string
    {
        return match ($this) {
            self::UNKNOWN => 'Unknown'
        };
    }
}
