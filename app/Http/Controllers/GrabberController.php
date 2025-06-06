<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Grabber\ProcessGrabberDataAction;
use App\Http\Requests\GrabberRequest;
use App\Models\GrabberLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GrabberController extends Controller
{
    public function __construct()
    {
        // Add API key authentication middleware here if needed
        // $this->middleware('auth.api_key');
    }

    public function process(GrabberRequest $request, ProcessGrabberDataAction $action): JsonResponse
    {
        $result = $action->execute(
            type: $request->validated('type'),
            action: $request->validated('action'),
            externalId: $request->validated('external_id'),
            data: $request->validated('data')
        );

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'Data processed successfully',
                'log_id' => $result['log_id'],
            ]);
        }

        return response()->json([
            'success' => false,
            'error' => $result['error'],
            'log_id' => $result['log_id'],
        ], 400);
    }

    public function batchProcess(Request $request, ProcessGrabberDataAction $action): JsonResponse
    {
        $request->validate([
            'items' => 'required|array|max:100',
            'items.*.type' => 'required|in:product,brand,collection',
            'items.*.action' => 'required|in:create,update',
            'items.*.external_id' => 'required|string',
            'items.*.data' => 'required|array',
        ]);

        $results = [];
        $successCount = 0;
        $failureCount = 0;

        foreach ($request->items as $item) {
            $result = $action->execute(
                type: $item['type'],
                action: $item['action'],
                externalId: $item['external_id'],
                data: $item['data']
            );

            $results[] = [
                'external_id' => $item['external_id'],
                'success' => $result['success'],
                'log_id' => $result['log_id'],
                'error' => $result['error'] ?? null,
            ];

            if ($result['success']) {
                $successCount++;
            } else {
                $failureCount++;
            }
        }

        return response()->json([
            'success' => $failureCount === 0,
            'processed' => count($results),
            'successful' => $successCount,
            'failed' => $failureCount,
            'results' => $results,
        ]);
    }

    public function logs(Request $request): JsonResponse
    {
        $logs = GrabberLog::query()
            ->when($request->type, fn ($q) => $q->where('type', $request->type))
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->external_id, fn ($q) => $q->where('external_id', $request->external_id))
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'data' => $logs->items(),
            'meta' => [
                'current_page' => $logs->currentPage(),
                'last_page' => $logs->lastPage(),
                'per_page' => $logs->perPage(),
                'total' => $logs->total(),
            ],
        ]);
    }
}
