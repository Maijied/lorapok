<?php
namespace Lorapok\ExecutionMonitor\Facades;

use Illuminate\Support\Facades\Facade;

class Monitor extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'execution-monitor';
    }
}
