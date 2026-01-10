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
    usleep(50000); 
    monitor()->end("fast-operation");
    return view("lorapok-fast");
});

Route::get("/lorapok-test-v2", function () {
    DB::enableQueryLog();
    monitor()->start("normal-operation");
    usleep(300000);
    monitor()->end("normal-operation");
    DB::table("migrations")->get();
    return view("lorapok-test");
});

Route::get("/lorapok-slow-v2", function () {
    DB::enableQueryLog();
    monitor()->start("slow-operation");
    sleep(2);
    monitor()->end("slow-operation");
    for ($i = 0; $i < 5; $i++) DB::table("migrations")->count();
    return view("lorapok-slow");
});

Route::get("/widget-test", function () {
    return view("widget-test");
});

// Advanced Lab Tests
Route::get('/lorapok/test/many-queries', function () {
    for ($i = 0; $i < 155; $i++) \DB::table("migrations")->count();
    return response()->json(['message' => 'Many queries test completed', 'queries' => 155]);
});

Route::get('/lorapok/test/high-memory', function () {
    $data = [];
    for ($i = 0; $i < 500000; $i++) $data[] = str_repeat('x', 512);
    return response()->json(['message' => 'High memory test completed', 'allocated' => count($data)]);
});

Route::get('/lorapok/test/exception', function () {
    throw new \Exception('This is a test exception from Lorapok Lab 🔥');
});

Route::get('/lorapok/test/middleware', function () {
    usleep(100000);
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
        usleep(200000);
        return response()->json(['type' => 'Processing', 'detail' => 'Background task simulated', 'duration' => '200ms']);
    });
    Route::get('/meta', function() {
        return response()->json(['type' => 'Metadata', 'detail' => 'System environment verified', 'env' => app()->environment()]);
    });
    Route::get('/view', function() {
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

    Route::get('/expensive-query', function() {
        $count = \DB::table('migrations as m1')->crossJoin('migrations as m2')->selectRaw('count(*) as total')->first();
        return response()->json(['type' => 'Stateful', 'detail' => 'Heavy Cross-Join Completed', 'rows' => $count->total]);
    });

    Route::post('/state-update', function() {
        usleep(500000);
        cache()->put('lab_state', now()->toDateTimeString(), 60);
        return response()->json(['type' => 'Stateful', 'detail' => 'System State Updated']);
    });

    Route::get('/heavy-io', function() {
        for($i=0; $i<50; $i++) {
            $path = storage_path("logs/test-io-{$i}.tmp");
            file_put_contents($path, str_repeat('x', 1000));
            unlink($path);
        }
        return response()->json(['type' => 'IO Stress', 'detail' => '50 File Cycles Finished']);
    });

    Route::get('/recursive-loop', function() {
        $fact = function($n, $f) { return $n <= 1 ? 1 : $n * $f($n - 1, $f); };
        $res = $fact(50, $fact);
        return response()->json(['type' => 'Logic', 'detail' => 'Factorial 50 calculated']);
    });

    Route::get('/heavy-auth', function() {
        for($i=0; $i<10; $i++) password_hash("lorapok-test-hash-string-{$i}", PASSWORD_BCRYPT, ['cost' => 10]);
        return response()->json(['type' => 'Security', 'detail' => '10 Heavy BCRYPT Hashes']);
    });

    Route::get('/cache-flood', function() {
        for($i=0; $i<100; $i++) cache()->put("flood_{$i}", str()->random(100), 5);
        return response()->json(['type' => 'Cache', 'detail' => '100 Key Cache Flood']);
    });

    Route::get('/db-flood', function() {
        for($i=0; $i<500; $i++) \DB::table('migrations')->count();
        return response()->json(['success' => true]);
    });

    Route::get('/db-lock', function() {
        \DB::transaction(function() {
            \DB::table('migrations')->lockForUpdate()->get();
            usleep(500000);
        });
        return response()->json(['success' => true]);
    });

    Route::get('/heavy-render', function() {
        return view('welcome')->render();
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
    Route::get('/roi-test', function() {
        usleep(650000); 
        \DB::table('migrations')->whereRaw('1=1')->get();
        return response()->json(['message' => 'Expensive route executed', 'duration' => '650ms']);
    });
});