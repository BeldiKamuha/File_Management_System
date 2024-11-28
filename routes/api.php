<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\DirectoryController;
use App\Http\Controllers\API\FileController;

// Directories routes
Route::prefix('directories')->group(function () {
    Route::get('/', [DirectoryController::class, 'index']);
    Route::post('/', [DirectoryController::class, 'store']);
    Route::get('/{id}', [DirectoryController::class, 'show']);
    Route::put('/{id}', [DirectoryController::class, 'update']);
    Route::delete('/{id}', [DirectoryController::class, 'destroy']);
    Route::get('/{id}/sub-directories', [DirectoryController::class, 'subDirectories']);
    Route::get('/{id}/files', [DirectoryController::class, 'files']);
});

// Files routes
Route::prefix('files')->group(function () {
    Route::get('/', [FileController::class, 'index']);
    Route::post('/', [FileController::class, 'store']);
    Route::get('/{id}', [FileController::class, 'show']);
    Route::put('/{id}', [FileController::class, 'update']);
    Route::put('/{id}/rename', [FileController::class, 'rename']);
    Route::delete('/{id}', [FileController::class, 'destroy']);
    Route::get('/{id}/download', [FileController::class, 'download']);
});