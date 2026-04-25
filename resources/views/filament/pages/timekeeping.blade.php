<x-filament-panels::page>
    <div class="space-y-6">

        @if($activeTime)
            {{-- Active session card --}}
            @php $startTs = $this->getStartTimestamp(); @endphp

            <div class="relative overflow-hidden rounded-2xl border border-emerald-200 bg-gradient-to-br from-emerald-50 via-white to-white dark:border-emerald-800/40 dark:from-emerald-950/40 dark:via-gray-900 dark:to-gray-900">
                {{-- Decorative background ring --}}
                <div class="pointer-events-none absolute -right-20 -top-20 h-72 w-72 rounded-full bg-emerald-100/60 dark:bg-emerald-900/20"></div>

                <div class="relative p-6">
                    {{-- Status badge --}}
                    <div class="mb-6 flex items-center gap-2.5">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                        </span>
                        <span class="text-xs font-semibold uppercase tracking-widest text-emerald-700 dark:text-emerald-400">Session Active</span>
                    </div>

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
                    >
                        {{-- Timer --}}
                        <div class="mb-5 text-center">
                            <p class="mb-1 text-xs font-medium uppercase tracking-widest text-slate-400">Elapsed Time</p>
                            <span
                                x-text="elapsed"
                                class="font-mono text-7xl font-bold tabular-nums tracking-tight text-slate-800 dark:text-slate-100 sm:text-8xl"
                            ></span>
                        </div>

                        {{-- Stop button --}}
                        <div class="mb-6 flex justify-center">
                            {{ $this->stopTimerAction }}
                        </div>

                        {{-- Session details --}}
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                            @foreach([
                                ['Started',  $activeTime->start_time],
                                ['Task',     $activeTime->task->description ?? '—'],
                                ['Block',    $activeTime->block->block_name ?? '—'],
                                ['Chemical', $activeTime->chemical->trade_name ?? '—'],
                            ] as [$label, $value])
                                <div class="rounded-xl border border-slate-100 bg-white/80 px-4 py-3 backdrop-blur-sm dark:border-slate-700/50 dark:bg-slate-800/60">
                                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">{{ $label }}</p>
                                    <p class="mt-0.5 truncate font-medium text-slate-700 dark:text-slate-300">{{ $value }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        @else
            {{-- New session form --}}
            <x-filament::section>
                <x-slot name="heading">Start New Session</x-slot>
                <x-slot name="description">Select a task, location, and chemical, then press Start Timer.</x-slot>

                <form wire:submit="startTimer" class="space-y-6">
                    {{ $this->form }}
                    <div class="pt-2">
                        <x-filament::button
                            type="submit"
                            color="success"
                            wire:loading.attr="disabled"
                            size="lg"
                            icon="heroicon-m-play-circle"
                        >
                            Start Timer
                        </x-filament::button>
                    </div>
                </form>
            </x-filament::section>
        @endif

        {{-- Recent sessions --}}
        <x-filament::section>
            <x-slot name="heading">Recent Sessions</x-slot>

            <div class="-mx-2 overflow-x-auto">
                <table class="w-full min-w-[640px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-800">
                            @foreach(['Date','Start','End','Duration','Task','Block','Chemical','Tank','Used'] as $h)
                                <th class="px-3 py-2.5 text-xs font-semibold uppercase tracking-wide text-slate-400">{{ $h }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800/60">
                        @forelse($this->getRecentRecords() as $r)
                            <tr class="transition-colors hover:bg-slate-50/70 dark:hover:bg-slate-800/30">
                                <td class="px-3 py-3 text-slate-400">{{ $r->created_at->format('d M') }}</td>
                                <td class="px-3 py-3 text-slate-600 dark:text-slate-400">{{ $r->start_time }}</td>
                                <td class="px-3 py-3 text-slate-600 dark:text-slate-400">{{ $r->end_time }}</td>
                                <td class="px-3 py-3">
                                    <span class="font-mono text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $r->duration }}</span>
                                </td>
                                <td class="px-3 py-3 text-slate-600 dark:text-slate-400">{{ $r->task->description ?? '—' }}</td>
                                <td class="px-3 py-3 text-slate-600 dark:text-slate-400">{{ $r->block->block_name ?? '—' }}</td>
                                <td class="px-3 py-3 text-slate-600 dark:text-slate-400">{{ $r->chemical->trade_name ?? '—' }}</td>
                                <td class="px-3 py-3 text-slate-500 dark:text-slate-500">{{ $r->tank_capacity }}L</td>
                                <td class="px-3 py-3 text-slate-500 dark:text-slate-500">{{ $r->total_liquid }}L</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-3 py-12 text-center text-sm text-slate-400">No sessions recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-filament::section>

    </div>
</x-filament-panels::page>
