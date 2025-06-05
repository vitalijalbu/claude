<?php

namespace App\Actions\Profile;

use App\Models\Profile;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class IndexProfiles
{
    public function handle(?Request $filters = null): LengthAwarePaginator
    {
        $query = QueryBuilder::for(Profile::class)
            ->allowedFilters(['name', 'phone_number', 'city.name'])
            ->allowedSorts(['name', 'phone_number', 'created_at'])
            ->allowedIncludes(['city', 'listings'])
            ->withCount('listings')
            ->defaultSort('-created_at');

        return $query->paginate(25);
    }
}
