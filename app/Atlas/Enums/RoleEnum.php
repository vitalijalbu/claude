<?php

declare(strict_types=1);

namespace App\Atlas\Enums;

enum RoleEnum: string
{
    // Add here roles needed for the app
    case TECH_MANAGER = 'TECH_MANAGER';
    case TECH_USER = 'TECH_USER';

    public function label(): string
    {
        return match ($this) {
            self::TECH_MANAGER => 'Technical Manager',
            self::TECH_USER => 'Technical User',
        };
    }
}
