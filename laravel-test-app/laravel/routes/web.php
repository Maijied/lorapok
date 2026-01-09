<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('welcome');
});

Route::get("/lorapok-fast-v2", function () {
    DB::enableQueryLog();
    
    monitor()->start("fast-operation");
    usleep(50000); // 0.05 second
    monitor()->end("fast-operation");
    
    $queries = DB::getQueryLog();
    \Log::info('Fast route queries: ' . count($queries));
    
    return view("lorapok-fast");
});

Route::get("/lorapok-test-v2", function () {
    DB::enableQueryLog();
    
    monitor()->start("normal-operation");
    usleep(300000); // 0.3 seconds
    monitor()->end("normal-operation");
    
    // Execute 1 query
    $result = DB::table("migrations")->get();
    \Log::info('Test route - migrations found: ' . $result->count());
    
    $queries = DB::getQueryLog();
    \Log::info('Test route queries: ' . count($queries));
    
    return view("lorapok-test");
});

Route::get("/lorapok-slow-v2", function () {
    DB::enableQueryLog();
    
    monitor()->start("slow-operation");
    sleep(2); // 2 seconds
    monitor()->end("slow-operation");
    
    // Execute 5 queries
    for ($i = 0; $i < 5; $i++) {
        $count = DB::table("migrations")->count();
        \Log::info('Slow route query ' . ($i+1) . ': migrations count = ' . $count);
    }
    
    $queries = DB::getQueryLog();
    \Log::info('Slow route total queries: ' . count($queries));
    
    return view("lorapok-slow");
});

Route::get("/widget-test", function () {
    return view("widget-test");
});

// Advanced Lab Tests
Route::get('/lorapok/test/many-queries', function () {
    // Execute many queries to trigger high query count alert
    for ($i = 0; $i < 155; $i++) {
        \DB::table("migrations")->count();
    }
    return response()->json(['message' => 'Many queries test completed', 'queries' => 155]);
});

Route::get('/lorapok/test/high-memory', function () {
    // Allocate memory to trigger alert
    $data = [];
    for ($i = 0; $i < 500000; $i++) {
        $data[] = str_repeat('x', 512);
    }
    return response()->json(['message' => 'High memory test completed', 'allocated' => count($data)]);
});

Route::get('/lorapok/test/exception', function () {
    throw new \Exception('This is a test exception from Lorapok Lab 🔥');
});
