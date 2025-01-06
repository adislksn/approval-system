<?php

namespace App\Filament\Resources\ReportsResource\Widgets;

use App\Models\Report;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class ReportsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '10s';
    protected function getStats(): array
    {
        if(Auth::user()->hasRole('super_admin')) {
            $reports = Report::all();
        } else {
            $reports = Report::where('user_id', Auth::id())->get();
        }
        return [
            Stat::make('Reports', $reports->count())
            ->url(route('filament.access.resources.reports.create')),
            Stat::make('Pending', $reports->where('status', 'pending')->count())
            ->url(route('filament.access.resources.reports.index', ['tableFilters[status][value]' => 'pending'])),
            Stat::make('Rejected', $reports->where('status', 'rejected')->count())
            ->url(route('filament.access.resources.reports.index', ['tableFilters[status][value]' => 'rejected'])),
            Stat::make('Accepted', $reports->where('status', 'done')->count())
            ->url(route('filament.access.resources.reports.index', ['tableFilters[status][value]' => 'done'])),
        ];
    }
}
