<?php
use Illuminate\Support\Facades\Route;
use Lorapok\ExecutionMonitor\Http\Controllers\MonitorApiController;

Route::middleware(['web'])->group(function () {
    Route::get('/execution-monitor/api/data', [MonitorApiController::class, 'getData'])
        ->name('execution-monitor.api.data');
});
