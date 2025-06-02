<?php

namespace App\Actions\Profile;

use App\DTO\Profile\ProfileFilterDTO;
use App\Models\Profile;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\QueryBuilder;

class IndexProfiles
{
    public function handle(?ProfileFilterDTO $filters = null): LengthAwarePaginator
    {
        $query = QueryBuilder::for(Profile::class)
            ->allowedFilters(['name', 'phone_number', 'city.name'])
            ->allowedSorts(['name', 'phone_number', 'created_at'])
            ->allowedIncludes(['city', 'listings'])
            ->withCount('listings');

        if ($filters) {
            if ($filters->search) {
                $query->where(function ($q) use ($filters) {
                    $q->where('name', 'LIKE', "%{$filters->search}%")
                        ->orWhere('phone_number', 'LIKE', "%{$filters->search}%");
                });
            }

            return $query
                ->orderBy($filters->sort, $filters->direction)
                ->paginate($filters->per_page, ['*'], 'page', $filters->page);
        }

        return $query->defaultSort('-created_at')->paginate(25);
    }
}
