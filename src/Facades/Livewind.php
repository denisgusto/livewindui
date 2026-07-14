<?php

declare(strict_types=1);

namespace Livewind\Facades;

use Illuminate\Support\Facades\Facade;

final class Livewind extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'livewind';
    }
}
