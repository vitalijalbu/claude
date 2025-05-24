<?php

namespace App\Repositories;

use App\Models\Profile;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\QueryBuilder;

class ProfileRepository
{
    protected array $filters = ['title'];

    protected array $sorters = ['title'];

    public function findAll($params): LengthAwarePaginator
    {
        return QueryBuilder::for(Profile::class)
            ->allowedFilters(['phone_number', 'name'])
            ->allowedSorts($this->sorters)
            ->paginate($params['per_page'] ?? 50)
            ->appends(request()->query());
    }

    // Find a profile by phone number
    public function findByPhone(string $phone_number): ?Profile
    {
        return Profile::where(['phone_number' => $phone_number])
            ->withCount('listings')
            ->with([
                'listings' => function ($query) {
                    $query->latest()->take(5);
                },
                'taxonomies',
                'city',
                'province',
                'category',
            ])
            ->firstOrFail();
    }

    // Update or create a profile
    public function updateOrCreate(array $match, array $data)
    {
        // dd($match, $data);
        return Profile::updateOrCreate($match, $data);
    }

    public function search(?string $query)
    {
        return QueryBuilder::for(Profile::class)
            ->allowedFilters(['name', 'bio'])
            ->when($query, fn ($q) => $q->where('name', 'LIKE', "%{$query}%"))
            ->limit(5)
            ->get()
            ->each->setAttribute('type', 'profile');
    }
}
