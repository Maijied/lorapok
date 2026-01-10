<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Middleware\TrackedMiddleware;

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

Route::get('/lorapok/test/middleware', function () {
    usleep(100000); // 0.1s simulate work
    return response()->json(['message' => 'Middleware tracking test completed']);
})->middleware(TrackedMiddleware::class);

// AJAX Storm Endpoints
Route::prefix('lorapok/lab/ajax')->group(function() {
    Route::get('/queries', function() {
        $count = \DB::table('migrations')->count();
        return response()->json(['type' => 'Database', 'detail' => "Counted {$count} migrations", 'queries' => 1]);
    });
    Route::get('/logs', function() {
        \Log::info('AJAX Storm: Triggering a server-side log entry');
        return response()->json(['type' => 'Logging', 'detail' => 'Server log entry generated', 'logs' => 1]);
    });
    Route::get('/process', function() {
        usleep(200000); // 0.2s
        return response()->json(['type' => 'Processing', 'detail' => 'Background task simulated', 'duration' => '200ms']);
    });
    Route::get('/meta', function() {
        return response()->json(['type' => 'Metadata', 'detail' => 'System environment verified', 'env' => app()->environment()]);
    });
    Route::get('/view', function() {
        // This endpoint renders a small fragment to test view path tracking via AJAX
        return view('welcome')->render(); 
    });
});

// Extra Advanced Lab Scenarios
Route::prefix('lorapok/lab/advanced')->group(function() {
    Route::get('/heavy-json', function() {
        $data = [];
        for($i=0; $i<5000; $i++) $data[] = ['id' => $i, 'uuid' => str()->uuid(), 'content' => str()->random(50)];
        return response()->json($data);
    });

    Route::get('/cache-test', function() {
        $hit = cache()->has('storm_hit');
        cache()->remember('storm_hit', 60, fn() => 'Premium Data');
        \DB::table('migrations')->count();
        return response()->json(['type' => 'Cache', 'status' => $hit ? 'HIT' : 'MISS']);
    });

    Route::get('/redirect-loop', function() {
        if (request()->has('final')) return response()->json(['message' => 'Loop Finished']);
        return redirect('/lorapok/lab/advanced/redirect-loop?final=1');
    });

    Route::get('/batch-db', function() {
        \DB::transaction(function() {
            for($i=0; $i<10; $i++) \DB::table('migrations')->where('id', 1)->update(['batch' => \DB::raw('batch')]);
        });
        return response()->json(['message' => 'Batch Update Finished']);
    });

    // Hard/Stateful Scenarios
    Route::get('/expensive-query', function() {
        // Run a heavy recursive or cross-join simulation
        $count = \DB::table('migrations as m1')
            ->crossJoin('migrations as m2')
            ->crossJoin('migrations as m3')
            ->selectRaw('count(*) as total')
            ->first();
        
        \Log::warning("Hard Test: Heavy cross-join completed. Scanned virtual rows: {$count->total}");
        return response()->json(['type' => 'Stateful', 'detail' => 'Heavy Cross-Join Completed', 'rows' => $count->total]);
    });

    Route::post('/state-update', function() {
        // Simulate a stateful multi-service operation
        usleep(500000); // 0.5s
        cache()->put('lab_state', now()->toDateTimeString(), 60);
        return response()->json(['type' => 'Stateful', 'detail' => 'System State Updated', 'timestamp' => now()->toDateTimeString()]);
    });

    Route::get('/heavy-io', function() {
        // Run multiple file system operations
        for($i=0; $i<50; $i++) {
            $path = storage_path("logs/test-io-{$i}.tmp");
            file_put_contents($path, str_repeat('x', 1000));
            unlink($path);
        }
        return response()->json(['type' => 'IO Stress', 'detail' => '50 File Write/Delete Cycles Finished']);
    });

    Route::get('/recursive-loop', function() {
        // Simulate deep recursive call chain
        $fact = function($n, $f) { return $n <= 1 ? 1 : $n * $f($n - 1, $f); };
        $res = $fact(50, $fact);
        return response()->json(['type' => 'Logic', 'detail' => 'Factorial 50 calculated recursively']);
    });

    Route::get('/heavy-auth', function() {
        // Simulate a heavy hashing or authentication verification process
        for($i=0; $i<10; $i++) password_hash("lorapok-test-hash-string-{$i}", PASSWORD_BCRYPT, ['cost' => 10]);
        return response()->json(['type' => 'Security', 'detail' => '10 Heavy BCRYPT Hashes Generated']);
    });

    Route::get('/cache-flood', function() {
        // Rapid fire cache operations
        for($i=0; $i<100; $i++) cache()->put("flood_{$i}", str()->random(100), 5);
        return response()->json(['type' => 'Cache', 'detail' => '100 Key Cache Flood Completed']);
    });

    // Extreme Stress Scenarios
    Route::get('/db-flood', function() {
        for($i=0; $i<500; $i++) \DB::table('migrations')->count();
        return response()->json(['success' => true]);
    });

    Route::get('/db-lock', function() {
        \DB::transaction(function() {
            \DB::table('migrations')->lockForUpdate()->get();
            usleep(500000); // Hold lock for 0.5s
        });
        return response()->json(['success' => true]);
    });

    Route::get('/heavy-render', function() {
        return view('lab.fragment', ['level' => 1, 'max' => 15])->render();
    });
});

// Architecture v1.4 Test Scenarios
Route::prefix('lorapok/test/v1-4')->group(function() {
    Route::get('/cache', function() {
        cache()->put('test_key', 'Lorapok Value', 10);
        $val = cache()->get('test_key');
        $missing = cache()->get('missing_key');
        return response()->json(['message' => 'Cache test', 'hit' => $val, 'miss' => $missing]);
    });
    Route::get('/queue', function() {
        dispatch(function() { logger()->info('Lorapok Async Job'); });
        return response()->json(['message' => 'Job dispatched']);
    });
    Route::get('/rate-limit', function() {
        $executed = RateLimiter::remaining('test-limiter', 5) > 0;
        if ($executed) RateLimiter::hit('test-limiter', 60);
        $remaining = RateLimiter::remaining('test-limiter', 5);
        if (app()->bound('execution-monitor')) {
            app('execution-monitor')->logRateLimit('test-limiter', $executed, $remaining);
        }
        return response()->json(['message' => $executed ? 'Allowed' : 'Throttled', 'remaining' => $remaining]);
    });
});

    // Cache ROI Detector Test
    Route::get('/roi-test', function() {
        usleep(650000); // 0.65s delay
        \DB::table('migrations')->whereRaw('1=1')->get(); // Just a query
        return response()->json(['message' => 'Expensive route executed', 'duration' => '650ms']);
    });
