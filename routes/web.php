<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'version' => app()->version(),
    ]);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
