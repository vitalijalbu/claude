<?php

declare(strict_types=1);

namespace App\Filters;

class SupplierFilters extends BaseFilter
{
    public static function getFilterableFields(): array
    {
        return [
            'id',
            'status',
            'country',
            'email',
            'name',
            'pre_assessment_score',
            'created_at',
            'updated_at',
            'priority',
        ];
    }

    public static function getFilterableRelations(): array
    {
        return [
            'organization_name' => ['organization', 'name'],
        ];
    }

    public static function getSortableFields(): array
    {
        return [
            'id',
            'name',
            'country',
            'status',
            'pre_assessment_score',
            'created_at',
            'updated_at',
        ];
    }

    public static function getIncludableRelations(): array
    {
        return [
            'organization',
            'lastVisit',
            'erpData',
        ];
    }
}
