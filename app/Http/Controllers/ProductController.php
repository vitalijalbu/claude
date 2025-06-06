<?php

namespace App\Http\Controllers;

use App\Actions\Product\IndexProducts;
use App\Actions\Product\ShowProduct;
use Illuminate\Http\Request;
use Lunar\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request, IndexProducts $action)
    {
        $data = $action->execute($request);

        return response()->json($data);
    }

    public function show(Product $product, ShowProduct $action)
    {
        return $action->execute($product);
    }
}
