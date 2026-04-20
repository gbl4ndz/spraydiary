<?php

namespace App\Filament\Widgets;

use App\Time;
use Carbon\Carbon;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class ActiveTimerWidget extends Widget
{
    protected static string $view = 'filament.widgets.active-timer-widget';
    protected static ?int $sort = 1;
    protected int|string|array $columnSpan = 'full';
    protected static ?string $pollingInterval = '30s';

    public function getActiveTime(): ?Time
    {
        return Time::where('sprayed_by', Auth::id())
            ->whereNull('end_time')
            ->with(['task', 'block', 'chemical'])
            ->latest()
            ->first();
    }

    public function getStartTimestamp(): int
    {
        $active = $this->getActiveTime();
        if (! $active) {
            return 0;
        }

        $ts = Carbon::parse(now()->toDateString() . ' ' . $active->start_time)->timestamp;

        if ($ts > now()->timestamp) {
            $ts = Carbon::parse(now()->subDay()->toDateString() . ' ' . $active->start_time)->timestamp;
        }

        return $ts;
    }
}
