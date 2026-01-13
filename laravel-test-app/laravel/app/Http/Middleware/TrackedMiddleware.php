<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Lorapok\ExecutionMonitor\Middleware\MeasuresMiddleware;

class TrackedMiddleware
{
    use MeasuresMiddleware;
}
