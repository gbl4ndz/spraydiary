<?php

namespace App\Filament\Pages;

use App\Block;
use App\Chemical;
use App\Shed;
use App\Task;
use App\Time;
use Carbon\Carbon;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Illuminate\Support\HtmlString;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class Timekeeping extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-play-circle';
    protected static ?string $navigationGroup = 'Spray Management';
    protected static ?string $navigationLabel = 'Timekeeping';
    protected static ?int $navigationSort = 1;
    protected static string $view = 'filament.pages.timekeeping';

    public ?array $data = [];
    public ?Time $activeTime = null;

    public function mount(): void
    {
        $this->activeTime = Time::where('sprayed_by', Auth::id())
            ->whereNull('end_time')
            ->with(['task', 'block', 'chemical'])
            ->latest()
            ->first();

        $this->form->fill();
    }

    public function getStartTimestamp(): int
    {
        if (! $this->activeTime) {
            return 0;
        }

        $ts = Carbon::parse(now()->toDateString() . ' ' . $this->activeTime->start_time)->timestamp;

        // Handle sessions that started before midnight (timestamp would be in the future)
        if ($ts > now()->timestamp) {
            $ts = Carbon::parse(now()->subDay()->toDateString() . ' ' . $this->activeTime->start_time)->timestamp;
        }

        return $ts;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('task_id')
                    ->label('Task')
                    ->options(fn () => Auth::user()->tasks()->pluck('description', 'id'))
                    ->searchable()
                    ->required(),
                Select::make('block_id')
                    ->label('Block')
                    ->options(fn () => Block::pluck('block_name', 'id'))
                    ->searchable()
                    ->required(),
                CheckboxList::make('sheds')
                    ->label('Sheds')
                    ->options(fn () => Shed::pluck('shed_name', 'id'))
                    ->columns(3)
                    ->required(),
                Select::make('chemical_id')
                    ->label('Chemical')
                    ->options(fn () => Chemical::pluck('trade_name', 'id'))
                    ->searchable()
                    ->required()
                    ->live(),
                Placeholder::make('chemical_details')
                    ->label('')
                    ->content(function (Get $get): HtmlString {
                        $id = $get('chemical_id');
                        if (! $id) {
                            return new HtmlString('');
                        }

                        $c = Chemical::find($id);
                        if (! $c) {
                            return new HtmlString('');
                        }

                        $rows = collect([
                            'Components'          => $c->components,
                            'Application Rate'    => $c->rates,
                            'Withholding Period'  => $c->withhold_period,
                            'Target Pest/Disease' => $c->pest_disease,
                        ])
                            ->filter()
                            ->map(fn ($val, $label) =>
                                "<div><span class=\"font-semibold text-gray-600 dark:text-gray-400\">{$label}:</span> {$val}</div>"
                            )
                            ->join('');

                        if (! $rows) {
                            return new HtmlString('');
                        }

                        return new HtmlString(
                            "<div class=\"rounded-lg border border-blue-200 bg-blue-50 p-3 text-sm text-gray-700 space-y-1 dark:border-blue-800 dark:bg-blue-950 dark:text-gray-300\">{$rows}</div>"
                        );
                    })
                    ->hidden(fn (Get $get): bool => ! $get('chemical_id')),
                TextInput::make('tank_capacity')
                    ->label('Tank Capacity (L)')
                    ->numeric()
                    ->required(),
                TextInput::make('total_liquid')
                    ->label('Total Liquid Used (L)')
                    ->numeric()
                    ->required(),
                Toggle::make('is_fruiting')
                    ->label('Is Fruiting?')
                    ->default(false),
            ])
            ->statePath('data');
    }

    public function startTimer(): void
    {
        $data = $this->form->getState();

        $sheds = implode(',', array_values((array) $data['sheds']));

        $this->activeTime = Time::create([
            'task_id'       => $data['task_id'],
            'block_id'      => $data['block_id'],
            'sheds'         => $sheds,
            'chemical_id'   => $data['chemical_id'],
            'tank_capacity' => $data['tank_capacity'],
            'total_liquid'  => $data['total_liquid'],
            'is_fruiting'   => $data['is_fruiting'] ? '1' : '0',
            'start_time'    => Carbon::now()->toTimeString(),
            'sprayed_by'    => Auth::id(),
        ]);

        $this->activeTime->load(['task', 'block', 'chemical']);

        $this->form->fill();

        Notification::make()->title('Timer started')->success()->send();
    }

    public function stopTimerAction(): Action
    {
        return Action::make('stopTimer')
            ->label('Stop Timer')
            ->color('danger')
            ->icon('heroicon-m-stop-circle')
            ->size('lg')
            ->requiresConfirmation()
            ->modalHeading('Stop Session')
            ->modalDescription('Are you sure you want to stop the current spray session?')
            ->modalSubmitActionLabel('Yes, stop timer')
            ->action(fn () => $this->stopTimer());
    }

    public function stopTimer(): void
    {
        if (! $this->activeTime) {
            return;
        }

        $start    = Carbon::parse($this->activeTime->start_time);
        $end      = Carbon::now();
        $duration = gmdate('H:i:s', $end->diffInSeconds($start));

        $this->activeTime->update([
            'end_time' => $end->toTimeString(),
            'duration' => $duration,
        ]);

        $this->activeTime = null;

        Notification::make()->title('Timer stopped')->success()->send();
    }

    public function getRecentRecords()
    {
        return Time::with(['task', 'chemical', 'block'])
            ->where('sprayed_by', Auth::id())
            ->whereNotNull('end_time')
            ->latest()
            ->take(15)
            ->get();
    }
}
