<?php

declare(strict_types=1);

namespace App\Http\Traits;

trait HasPagination
{
    /**
     * Apply pagination to the given query
     */
    public function paginate($query, $defaultSortField = 'created_at')
    {
        $perPage = $this->input('per_page', 15);
        $sortBy = $this->input('sort_by', $defaultSortField);
        $sortDir = $this->input('sort_dir', 'desc');

        return $query->orderBy($sortBy, $sortDir)
            ->paginate($perPage);
    }

    protected function paginationRules(): array
    {
        return [
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'sort_by' => ['sometimes', 'string'],
            'sort_dir' => ['sometimes', 'in:asc,desc'],
        ];
    }
}
