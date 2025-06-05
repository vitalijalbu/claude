<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\GrabberService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

final class GrabberController extends Controller
{
    public function __construct(
        protected GrabberService $grabberService
    ) {}

    public function syncSupplier(): JsonResponse
    {
        try {
            // Fetch singolo supplier
            $supplierData = $this->grabberService->getList(
                'buying/suppliers',
            );

            // Qui puoi processare i dati come vuoi
            // Es: salvare in database, trasformare, etc.

            return response()->json([
                'status' => 'success',
                'message' => 'Supplier fetched successfully.',
                'data' => $supplierData,
            ]);
        } catch (Throwable $e) {
            Log::error('Error fetching supplier: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch supplier.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
