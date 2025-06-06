<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;

Route::middleware('api')->group(function () {
    // PDF routes
    Route::post('/upload-pdf', [PDFController::class, 'uploadPDF']);
    Route::post('/process-pdf', [PDFController::class, 'processPDF']);
    
    // Template routes
    Route::post('/templates', [PDFController::class, 'saveTemplate']);
    Route::get('/templates', [PDFController::class, 'getTemplates']);
    Route::get('/templates/{id}', [PDFController::class, 'getTemplate']);
    Route::delete('/templates/{id}', [PDFController::class, 'deleteTemplate']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});