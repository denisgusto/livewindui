<?php

declare(strict_types=1);

it('runs the livewind:install command successfully', function () {
    $this->artisan('livewind:install')->assertSuccessful();
});
