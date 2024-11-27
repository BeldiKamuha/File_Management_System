<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\FileController;
use App\Http\Controllers\API\DirectoryController;
use Illuminate\Support\Facades\Log;

Route::prefix('directories')->group(function () {
    Route::get('/', [DirectoryController::class, 'index']);
    Route::post('/', [DirectoryController::class, 'store']);
    Route::get('/{id}', [DirectoryController::class, 'show']); // Ensure this route exists
    Route::put('/{id}', [DirectoryController::class, 'update']);
    Route::delete('/{id}', [DirectoryController::class, 'destroy']);
    Route::get('/{id}/sub-directories', [DirectoryController::class, 'subDirectories']);
    Route::get('/{id}/files', [DirectoryController::class, 'files']);
});

Route::prefix('files')->group(function () {
    Route::get('/', [FileController::class, 'index']);
    Route::post('/', [FileController::class, 'store']);
    Route::get('/{id}', [FileController::class, 'show']);
    Route::put('/{id}', [FileController::class, 'update']);
    Route::delete('/{id}', [FileController::class, 'destroy']);
    Route::get('/{id}/download', [FileController::class, 'download']);
});


Route::get('/test-log', function () {
    Log::error('Test log entry from Laravel');
    return response()->json(['message' => 'Log entry created']);
});