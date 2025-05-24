<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Listing;
use App\Models\Profile;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(Request $request)
    {

        $data = [
            'total_categories' => Category::count(),
            'total_profiles' => Profile::count(),
            'total_listings' => Listing::count(),
        ];

        return Inertia::render('index', ['data' => $data]);
    }
}
