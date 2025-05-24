<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Listing;
use App\Models\Profile;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GeneralController extends Controller
{
    public function page(Request $request)
    {

        $data = [
            'total_categories' => Category::count(),
            'total_profiles' => Profile::count(),
            'total_listings' => Listing::count(),
        ];

        return Inertia::render('settings/general', ['data' => $data]);
    }
}
