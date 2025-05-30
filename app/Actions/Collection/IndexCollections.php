<?php

declare(strict_types=1);

namespace App\Actions\Collection;

use Illuminate\Http\Request;
use Lunar\Models\Collection;


class IndexCollections
{
    public function execute(Request $request)
    {
        $data = Collection::query()
            ->with(['thumbnail', 'children'])
            ->when($request->input('search'), function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->input('search')}%");
            })
            ->orderBy('name')
            ->get();

        return $data;
    }
}
