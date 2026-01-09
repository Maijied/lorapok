<?php
use Illuminate\Support\Facades\Route;
use Lorapok\ExecutionMonitor\Http\Controllers\MonitorApiController;

Route::middleware(['web'])->group(function () {
    Route::get('/execution-monitor/api/data', [MonitorApiController::class, 'getData'])
        ->name('execution-monitor.api.data');
    Route::get('/api/data', [MonitorApiController::class, 'getData']);
    Route::post('/api/settings', [MonitorApiController::class, 'saveSettings']);
    Route::post('/api/settings/test', [MonitorApiController::class, 'testSettings']);
    Route::post('/execution-monitor/api/settings', [MonitorApiController::class, 'saveSettings'])
        ->name('execution-monitor.api.settings');
    
    // Test routes for monitoring scenarios
    Route::get('/lorapok/test/slow-route', function () {
        sleep(2); // Simulate slow route (2 seconds)
        return response()->json(['message' => 'Slow route test completed', 'duration' => '2000ms']);
    });
    
    Route::get('/lorapok/test/slow-query', function () {
        // Simulate slow query by sleeping in a DB::select callback
        \DB::connection()->getPdo()->exec('SELECT SLEEP(0.15)'); // 150ms
        return response()->json(['message' => 'Slow query test completed']);
    });
    
    Route::get('/lorapok/test/many-queries', function () {
        // Execute 150 queries to trigger high query count alert
        for ($i = 0; $i < 150; $i++) {
            \DB::select('SELECT 1');
        }
        return response()->json(['message' => 'Many queries test completed', 'queries' => 150]);
    });
    
    Route::get('/lorapok/test/high-memory', function () {
        // Allocate large array to trigger memory alert
        $data = [];
        for ($i = 0; $i < 1000000; $i++) {
            $data[] = str_repeat('x', 1000);
        }
        return response()->json(['message' => 'High memory test completed', 'allocated' => count($data)]);
    });
    
    Route::get('/lorapok/test/exception', function () {
        throw new \Exception('This is a test exception from Lorapok monitoring');
    });
});
