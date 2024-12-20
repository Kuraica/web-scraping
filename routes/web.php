<?php

use App\Http\Controllers\AgencyController;
use App\Http\Controllers\AgentsController;
use App\Http\Controllers\ExcelProcessingController;
use App\Http\Controllers\ExcelTestingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('get-agents', [AgentsController::class, 'getAgents']);
Route::get('get-first-agents', [AgentsController::class, 'getFirstAgents']);
Route::get('get-last-agents', [AgentsController::class, 'getLastAgents']);
Route::get('/export-agents', [AgentsController::class, 'export'])->name('export.agents');
Route::get('/send-agents-report', [AgentsController::class, 'sendAgentsReport'])->name('send.agents.report');

Route::get('get-agency', [AgencyController::class, 'getAgency']);

Route::get('/process-excel', [ExcelProcessingController::class, 'process']);
Route::get('/test-excel', [ExcelTestingController::class, 'testRows']);

