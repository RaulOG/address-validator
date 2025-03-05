<?php

use App\Http\Controllers\CsvFieldController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CsvUploadController;

Route::middleware('api')->group(function () {
    Route::post('user/upload', [CsvUploadController::class, 'upload'])->name('csv.upload');
    Route::get('fields', [CsvFieldController::class, 'index']);
});
