<?php

namespace App\Http\Controllers\Api;

use App\Models\Listing;

class FavoriteController extends ApiController
{
    public function favorites()
    {
        $listings = Listing::take(4)->get();

        return view('favorites', compact('listings'));
    }
}
