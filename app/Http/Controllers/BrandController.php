<?php

namespace App\Http\Controllers;

use App\Actions\Brand\IndexBrands;
use App\Actions\Brand\ShowBrand;
use Illuminate\Http\Request;
use Lunar\Models\Brand;

class BrandController extends Controller
{
    public function index(Request $request, IndexBrands $action)
    {
        $data = $action->execute($request);

        return response()->json($data);
    }

    public function show(Brand $brand, ShowBrand $action)
    {
        return $action->execute($brand);
    }
}
