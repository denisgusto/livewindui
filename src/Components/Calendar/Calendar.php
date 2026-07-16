<?php

declare(strict_types=1);

namespace Livewind\Components\Calendar;

use Illuminate\View\Component;
use Illuminate\View\View;

class Calendar extends Component
{
    /**
     * @param  string|null  $model  Livewire property to bind (stores 'YYYY-MM-DD')
     * @param  string|null  $value  Initial selected date ('YYYY-MM-DD')
     */
    public function __construct(
        public ?string $model = null,
        public ?string $value = null,
    ) {}

    public function render(): View
    {
        return view('livewind::calendar');
    }

    public function id(): string
    {
        return 'livewind-calendar-'.md5((string) ($this->model ?? 'calendar'));
    }
}
