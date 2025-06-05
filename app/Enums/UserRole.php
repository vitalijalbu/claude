<?php

declare(strict_types=1);

namespace App\Enums;

use App\Helpers\Concerns\EnumSerializable;

enum UserRole: string
{
    use EnumSerializable;

    case TECHNICIAN = 'technician';
    case MANAGER = 'manager';
    case INSPECTOR = 'inspector';
    case CLIENT = 'client';

    public function getPermissions(): array
    {
        return match ($this) {
            self::TECHNICIAN => [
                'suppliers.view',
                'visits.create',
                'visits.update',
                'capacity_tests.create',
                'capacity_tests.update',
                'reports.create',
                'reports.update',
            ],
            self::MANAGER => [
                'suppliers.view',
                'suppliers.update',
                'visits.view',
                'visits.create',
                'visits.update',
                'visits.approve',
                'visits.warning',
                'capacity_tests.view',
                'capacity_tests.create',
                'capacity_tests.update',
                'capacity_tests.approve',
                'reports.view',
                'reports.create',
                'reports.update',
                'reports.approve',
                'certificates.generate',
                'certificates.download',
            ],
            self::INSPECTOR => [
                'visits.view',
                'visits.execute',
                'questionnaires.fill',
            ],
            self::CLIENT => [
                '*',
            ],
        };
    }
}
