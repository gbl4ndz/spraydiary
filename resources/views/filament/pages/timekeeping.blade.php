<x-filament-panels::page>
    <div class="space-y-6">

        @if($activeTime)
            {{-- Active session card with live timer --}}
            <x-filament::section>
                <x-slot name="heading">
                    <span class="flex items-center gap-2">
                        <span class="inline-block w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse"></span>
                        Active Session
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
                    {{-- Live clock --}}
                    <div class="text-center md:text-left">
                        <p class="mb-1 text-xs font-semibold uppercase tracking-widest text-gray-400">Elapsed Time</p>
                        <span
                            x-text="elapsed"
                            class="font-mono text-5xl font-bold tabular-nums text-green-600 dark:text-green-400"
                        ></span>
                    </div>

                    {{-- Session details --}}
                    <div class="grid flex-1 grid-cols-2 gap-4 text-sm md:grid-cols-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Started</p>
                            <p class="font-medium">{{ $activeTime->start_time }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Task</p>
                            <p class="font-medium">{{ $activeTime->task->description ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Block</p>
                            <p class="font-medium">{{ $activeTime->block->block_name ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Chemical</p>
                            <p class="font-medium">{{ $activeTime->chemical->trade_name ?? '—' }}</p>
                        </div>
                    </div>

                    {{-- Stop button --}}
                    <div>
                        <x-filament::button
                            color="danger"
                            wire:click="stopTimer"
                            wire:loading.attr="disabled"
                            size="lg"
                            icon="heroicon-m-stop-circle"
                        >
                            Stop Timer
                        </x-filament::button>
                    </div>
                </div>
            </x-filament::section>
        @else
            {{-- Start timer form --}}
            <x-filament::section>
                <x-slot name="heading">Start New Session</x-slot>

                <form wire:submit="startTimer">
                    {{ $this->form }}
                    <div class="mt-6">
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

        {{-- Recent records table --}}
        <x-filament::section>
            <x-slot name="heading">My Recent Sessions</x-slot>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="py-2 pr-4 font-semibold">Date</th>
                            <th class="py-2 pr-4 font-semibold">Start</th>
                            <th class="py-2 pr-4 font-semibold">End</th>
                            <th class="py-2 pr-4 font-semibold">Duration</th>
                            <th class="py-2 pr-4 font-semibold">Task</th>
                            <th class="py-2 pr-4 font-semibold">Block</th>
                            <th class="py-2 pr-4 font-semibold">Chemical</th>
                            <th class="py-2 pr-4 font-semibold">Tank (L)</th>
                            <th class="py-2 font-semibold">Used (L)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($this->getRecentRecords() as $record)
                            <tr class="border-b border-gray-100 dark:border-gray-800">
                                <td class="py-2 pr-4 text-gray-400">{{ $record->created_at->format('d M') }}</td>
                                <td class="py-2 pr-4">{{ $record->start_time }}</td>
                                <td class="py-2 pr-4">{{ $record->end_time }}</td>
                                <td class="py-2 pr-4 font-mono font-semibold">{{ $record->duration }}</td>
                                <td class="py-2 pr-4">{{ $record->task->description ?? '—' }}</td>
                                <td class="py-2 pr-4">{{ $record->block->block_name ?? '—' }}</td>
                                <td class="py-2 pr-4">{{ $record->chemical->trade_name ?? '—' }}</td>
                                <td class="py-2 pr-4">{{ $record->tank_capacity }}</td>
                                <td class="py-2">{{ $record->total_liquid }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-4 text-center text-gray-400">No sessions recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-filament::section>

    </div>
</x-filament-panels::page>
