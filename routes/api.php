<?php

use App\Http\Controllers\AgencyController;
use App\Http\Controllers\AgentsController;
use App\Http\Controllers\RegionsController;
use App\Http\Controllers\ProcessData;
use App\Http\Controllers\ProcessedUrlsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/send-data', [ProcessData::class, 'process']);
Route::get('/test-get-region/{query}', [RegionsController::class, 'check']);
Route::get('/get-next-region', [RegionsController::class, 'getNextRegion']);
Route::post('/update-processed-region/{region}', [RegionsController::class, 'updateProcessedRegion']);
Route::get('/check-agent/{agent}', [AgentsController::class, 'checkAgent']);
Route::post('/update-process', [ProcessedUrlsController::class, 'update']);
Route::post('/check-agency', [AgencyController::class, 'checkAgency']);

