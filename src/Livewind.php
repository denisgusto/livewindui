<?php

namespace Livewind;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Livewind\LivewindManager
 */
class Livewind extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'livewind';
    }
}
