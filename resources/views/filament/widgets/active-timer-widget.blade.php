<x-filament-widgets::widget>
    @php $active = $this->getActiveTime(); @endphp

    @if($active)
        @php $startTs = $this->getStartTimestamp(); @endphp

        <div class="relative overflow-hidden rounded-2xl border border-emerald-200 bg-gradient-to-br from-emerald-50 via-white to-white dark:border-emerald-800/40 dark:from-emerald-950/40 dark:via-gray-900 dark:to-gray-900">
            <div class="pointer-events-none absolute -right-12 -top-12 h-48 w-48 rounded-full bg-emerald-100/60 dark:bg-emerald-900/20"></div>

            <div
                x-data="{
                    elapsed: '00:00:00',
                    startTs: {{ $startTs }},
                    tick() {
                        const diff = Math.floor(Date.now() / 1000) - this.startTs;
                        if (diff < 0) return;
                        const h = String(Math.floor(diff / 3600)).padStart(2, '0');
                        const m = String(Math.floor((diff % 3600) / 60)).padStart(2, '0');
                        const s = String(diff % 60).padStart(2, '0');
                        this.elapsed = h + ':' + m + ':' + s;
                    }
                }"
                x-init="tick(); setInterval(() => tick(), 1000)"
                class="relative p-6"
            >
                {{-- Header row --}}
                <div class="mb-5 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                        </span>
                        <span class="text-xs font-semibold uppercase tracking-widest text-emerald-700 dark:text-emerald-400">Session Active</span>
                    </div>
                    <x-filament::button
                        tag="a"
                        :href="filament()->getUrl() . '/timekeeping'"
                        color="gray"
                        size="xs"
                        icon="heroicon-m-arrow-top-right-on-square"
                    >
                        Open Timer
                    </x-filament::button>
                </div>

                {{-- Timer --}}
                <div class="mb-5">
                    <p class="mb-1 text-xs font-medium uppercase tracking-widest text-slate-400">Elapsed Time</p>
                    <span
                        x-text="elapsed"
                        class="font-mono text-4xl font-bold tabular-nums tracking-tight text-slate-800 dark:text-slate-100"
                    ></span>
                </div>

                {{-- Session details --}}
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                    @foreach([
                        ['Task',     $active->task->description ?? '—'],
                        ['Block',    $active->block->block_name ?? '—'],
                        ['Chemical', $active->chemical->trade_name ?? '—'],
                        ['Started',  $active->start_time],
                    ] as [$label, $value])
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">{{ $label }}</p>
                            <p class="mt-0.5 truncate text-sm font-medium text-slate-700 dark:text-slate-300">{{ $value }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    @else
        <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50/80 p-6 dark:border-slate-700/60 dark:bg-slate-800/40">
            <div class="flex items-center gap-2.5">
                <span class="inline-flex h-2.5 w-2.5 rounded-full bg-slate-300 dark:bg-slate-600"></span>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">No active spray session</p>
            </div>
            <x-filament::button
                tag="a"
                :href="filament()->getUrl() . '/timekeeping'"
                color="success"
                size="sm"
                icon="heroicon-m-play-circle"
            >
                Start Session
            </x-filament::button>
        </div>
    @endif
</x-filament-widgets::widget>
