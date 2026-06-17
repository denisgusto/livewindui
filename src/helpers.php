<?php

declare(strict_types=1);

if (! function_exists('livewindui')) {
    /**
     * Read a LiveWindUI config value (or the whole config when no key is given).
     */
    function livewindui(?string $key = null, mixed $default = null): mixed
    {
        if (is_null($key)) {
            return config('livewindui');
        }

        return config("livewindui.{$key}", $default);
    }
}
