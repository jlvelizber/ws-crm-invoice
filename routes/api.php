<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\WPApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/health-check', [WPApiController::class, 'checkHealth']);
Route::post('/invoices', [WPApiController::class, 'invoices']);
Route::delete('/invoices/{invoice}', [InvoiceController::class, 'destroy']);


Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('plans', PlanController::class);
