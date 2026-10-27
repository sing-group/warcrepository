<?php

namespace App\Support\Facades;

use App\Support\Charts\Lavacharts;
use Illuminate\Support\Facades\Facade;

/**
 * @see \App\Support\Charts\Lavacharts
 */
class Lava extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Lavacharts::class;
    }
}
