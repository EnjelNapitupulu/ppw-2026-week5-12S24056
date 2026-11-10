<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/ping', fn () => response()->json([
        'success' => true,
        'message' => 'SIPUS-Del API v1 aktif.',
    ]));
});