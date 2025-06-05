<?php

declare(strict_types=1);

namespace App\Http\Controllers;

final class AWSController extends Controller
{
    /**
     * AWS Health action
     */
    public function health()
    {
        return response()->json(
            'ok'
        );
    }
}
