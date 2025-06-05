<?php

declare(strict_types=1);

namespace App\Filters;

class VisitFilters extends BaseFilter
{
    public static function getFilterableFields(): array
    {
        return [
            'id', 'status', 'visit_date', 'score', 'notes', 'created_at', 'updated_at',
        ];
    }

    public static function getFilterableRelations(): array
    {
        return [
            'supplier_name' => ['supplier', 'name'],
            'auditor_name' => ['auditor', 'name'],
        ];
    }

    public static function getSortableFields(): array
    {
        return [
            'id', 'visit_date', 'status', 'score', 'created_at', 'updated_at',
        ];
    }

    public static function getIncludableRelations(): array
    {
        return [
            'supplier', 'auditor', 'findings',
        ];
    }
}
