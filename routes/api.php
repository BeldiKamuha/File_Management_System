<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\FileController;
use App\Http\Controllers\API\DirectoryController;
use Illuminate\Support\Facades\Log;

// File Routes
Route::get('/files', [FileController::class, 'index']);
Route::get('/files/{id}', [FileController::class, 'show']);
Route::post('/files', [FileController::class, 'store']);
Route::put('/files/{id}', [FileController::class, 'update']);
Route::delete('/files/{id}', [FileController::class, 'destroy']);

// Directory Routes
Route::get('/directories', [DirectoryController::class, 'index']);
Route::get('/directories/{id}/sub-directories', [DirectoryController::class, 'subDirectories']);
Route::get('/directories/{id}/files', [DirectoryController::class, 'files']);
Route::post('/directories', [DirectoryController::class, 'store']);
Route::put('/directories/{id}', [DirectoryController::class, 'update']);
Route::delete('/directories/{id}', [DirectoryController::class, 'destroy']);


Route::get('/test-log', function () {
    Log::error('Test log entry from Laravel');
    return response()->json(['message' => 'Log entry created']);
});