<?php

namespace App\Http\Controllers;

use App\Actions\Collection\IndexCollections;
use App\Actions\Collection\ShowCollection;
use Illuminate\Http\Request;
use Lunar\Models\Collection;

class CollectionController extends Controller
{
    public function index(Request $request, IndexCollections $action)
    {
        $data = $action->execute($request);

        return response()->json($data);
    }

    public function show(Collection $collection, ShowCollection $action)
    {
        return $action->execute($collection);
    }
}
