<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Domains\EventIngestion\Controllers\IngestionController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Server-Side Tracking Ingestion Endpoint
Route::middleware('throttle:300,1')->post('/track-event', [IngestionController::class, 'collect']);
