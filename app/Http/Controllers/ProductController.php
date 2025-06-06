<?php

namespace App\Http\Controllers;

use App\Actions\Product\IndexProducts;
use App\Actions\Product\ShowProduct;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Lunar\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request, IndexProducts $action): AnonymousResourceCollection
    {
        $data = $action->execute($request);

        return ProductResource::collection($data);
    }

    public function show(Product $product, ShowProduct $action): ProductResource
    {
        $data = $action->execute($product);

        return ProductResource::make($data);
    }
}
