<?php

namespace App\Repositories;

use App\Models\Listing;

class StoreListing
{
    public function updateOrCreate(array $match, array $data)
    {
        // dd($match, $data);
        return Listing::updateOrCreate($match, $data);
    }
}
