<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class ApiResponse
{
    /**
     * Send a success response.
     */
    public static function success($data = null, int $httpStatus = JsonResponse::HTTP_OK): JsonResponse
    {
        return new JsonResponse([
            'response' => 'success',
            'data' => $data,
        ], $httpStatus, [], JSON_UNESCAPED_SLASHES);
    }

    /**
     * Multiple error response.
     */
    public static function multipleErrors(
        string $message,
        array $errors,
        int $httpStatus = JsonResponse::HTTP_BAD_REQUEST,
        array $meta = [],
    ): JsonResponse {
        return new JsonResponse([
            'response' => 'error',
            'errors' => $errors,
            'message' => $message,
        ] + $meta, $httpStatus, [], JSON_UNESCAPED_SLASHES);
    }

    /**
     * Send an error response.
     *
     * @param  array  $meta  - Additional key to merge to response
     */
    public static function error(
        string $errorKey,
        string $message,
        int $httpStatus = JsonResponse::HTTP_BAD_REQUEST,
        array $meta = []): JsonResponse
    {
        return new JsonResponse([
            'response' => 'error',
            'key' => $errorKey,
            'message' => $message,
        ] + $meta, $httpStatus, [], JSON_UNESCAPED_SLASHES);
    }

    /**
     * Download a string content as file.
     */
    public static function downloadContent(string $content, string $fileName, ?string $contentType = null): Response
    {
        if (! $contentType) {
            $contentType = match (Str::afterLast($fileName, '.')) {
                'csv' => 'text/csv',
                'doc' => 'application/msword',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'jpeg' => 'image/jpeg',
                'jpg' => 'image/jpeg',
                'json' => 'application/json',
                'png' => 'image/png',
                'pdf' => 'application/pdf',
                'svg' => 'image/svg+xml',
                'xml' => 'application/xml',
                'xls' => 'application/vnd.ms-excel',
                'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'zip' => 'application/zip',
                default => 'application/octet-stream',
            };
        }

        $headers = [
            'Content-type' => $contentType,
            'Content-Disposition' => sprintf('attachment; filename="%s"', $fileName),
        ];

        return new Response($content, 200, $headers);
    }
}
