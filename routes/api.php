<?php

use App\Http\Controllers\Api\V1\BukuApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/ping', fn () => response()->json([
        'success' => true,
        'message' => 'SIPUS-Del API v1 aktif.',
    ]));

    Route::get('/bukus', [BukuApiController::class, 'index']);
    Route::get('/bukus/{id}', [BukuApiController::class, 'show']);

    // SEMENTARA tanpa token
    Route::post('/bukus', [BukuApiController::class, 'store']);
    Route::put('/bukus/{id}', [BukuApiController::class, 'update']);
    Route::patch('/bukus/{id}', [BukuApiController::class, 'update']);
    Route::delete('/bukus/{id}', [BukuApiController::class, 'destroy']);
});