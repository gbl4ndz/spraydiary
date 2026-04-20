<x-filament-widgets::widget>
    @php $active = $this->getActiveTime(); @endphp

    <x-filament::section>
        @if($active)
            <x-slot name="heading">
                <span class="flex items-center gap-2">
                    <span class="inline-block h-2.5 w-2.5 animate-pulse rounded-full bg-green-500"></span>
                    Active Spray Session
                </span>
            </x-slot>

            @php $startTs = $this->getStartTimestamp(); @endphp

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
                class="flex flex-col gap-6 md:flex-row md:items-center"
            >
                <div class="text-center md:text-left">
                    <p class="mb-1 text-xs font-semibold uppercase tracking-widest text-gray-400">Elapsed</p>
                    <span
                        x-text="elapsed"
                        class="font-mono text-4xl font-bold tabular-nums text-green-600 dark:text-green-400"
                    ></span>
                </div>

                <div class="grid flex-1 grid-cols-2 gap-4 text-sm md:grid-cols-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Task</p>
                        <p class="font-medium">{{ $active->task->description ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Block</p>
                        <p class="font-medium">{{ $active->block->block_name ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Chemical</p>
                        <p class="font-medium">{{ $active->chemical->trade_name ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Started</p>
                        <p class="font-medium">{{ $active->start_time }}</p>
                    </div>
                </div>

                <div class="shrink-0">
                    <x-filament::button
                        tag="a"
                        :href="filament()->getUrl() . '/timekeeping'"
                        color="warning"
                        size="sm"
                        icon="heroicon-m-arrow-top-right-on-square"
                    >
                        Go to Timer
                    </x-filament::button>
                </div>
            </div>
        @else
            <x-slot name="heading">
                <span class="flex items-center gap-2">
                    <span class="inline-block h-2.5 w-2.5 rounded-full bg-gray-300 dark:bg-gray-600"></span>
                    No Active Session
                </span>
            </x-slot>

            <p class="text-sm text-gray-500 dark:text-gray-400">
                No spray session is currently running.
                <x-filament::link :href="filament()->getUrl() . '/timekeeping'">
                    Start one now
                </x-filament::link>
            </p>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
