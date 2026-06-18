<?php

declare(strict_types=1);

namespace LiveWindUi\Facades;

use Illuminate\Support\Facades\Facade;
use LiveWindUi\LiveWindUiManager;

/**
 * @method static void toast(string $message = '', ?string $title = null, string $variant = 'info', ?int $duration = null, ?string $text = null, ?string $heading = null)
 * @method static void success(string $message = '', ?string $title = null, ?int $duration = null)
 * @method static void info(string $message = '', ?string $title = null, ?int $duration = null)
 * @method static void warning(string $message = '', ?string $title = null, ?int $duration = null)
 * @method static void danger(string $message = '', ?string $title = null, ?int $duration = null)
 * @method static void error(string $message = '', ?string $title = null, ?int $duration = null)
 * @method static object modal(string $name = 'default')
 * @method static object modals()
 * @method static array flashedToasts()
 * @method static void dispatch(string $event, array $params = [])
 *
 * @see LiveWindUiManager
 */
class Livewind extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'livewind';
    }
}
