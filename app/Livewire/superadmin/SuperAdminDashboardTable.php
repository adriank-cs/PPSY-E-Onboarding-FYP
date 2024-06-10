<?php

namespace App\Livewire\Superadmin;

use Carbon\Carbon;
use App\Models\Company;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Rappasoft\LaravelLivewireTables\Views\Columns\ComponentColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\MultiSelectFilter;

class SuperAdminDashboardTable extends DataTableComponent
{
    //Query to get users, profiles and user sessions
    public function builder(): Builder
    {
        
        $query = Company::query()
            ->addSelect('CompanyID', 'Name', 'Industry', 'subscription_starts_at', 'subscription_ends_at')
            ->selectRaw('DATEDIFF(subscription_ends_at, subscription_starts_at) as durationDays');

        return $query;
    }

    public function configure(): void
    {
        //General Configuration
        $this->setPrimaryKey('CompanyID');
        $this->setOfflineIndicatorStatus(false);

        //Search Configuration
        $this->setSearchPlaceholder('Search Company...');

        //Filter Configuration
        $this->setFilterLayoutPopover();

        //Default Sort Configuration
        $this->setDefaultSort('subscription_ends_at', 'asc');

    }

    //Columns in the table
    public function columns(): array
    {
        return [
            Column::make("Company ID", "CompanyID")
                ->sortable(),
            Column::make("Name", "Name")
                ->searchable()
                ->sortable(),
            Column::make("Industry", "Industry")
                ->sortable(),
            Column::make("Subscription Start", "subscription_starts_at")
                ->format (fn ($value, $row, Column $column) => Carbon::parse($value)->setTimezone('Asia/Kuala_Lumpur')->toFormattedDateString())
                ->sortable(),
            Column::make("Subscription End", "subscription_ends_at")
                ->format (fn ($value, $row, Column $column) => Carbon::parse($value)->setTimezone('Asia/Kuala_Lumpur')->toFormattedDateString())
                ->sortable(),
            ComponentColumn::make('Subscription Usage', 'subscription_starts_at')
                ->component('table.progress-bar')
                ->attributes(fn ($value, $row, Column $column) => 
                [
                    $percentage = round(now()->diffInDays($row->subscription_starts_at) / $row->durationDays * 100, 0),
                    'progress' => "{$percentage}%",
                ]),
        ];
    }

    //Filters in the table

    public function filters(): array
    {
        //Queries only industries of companies
        $query = Company::query()
        ->select('Industry')
        ->distinct()
        ->orderBy('Industry')
        ->get()
        ->keyBy('Industry')
        ->map(fn ($industry) => $industry->Industry)
        ->toArray();

        return [
            MultiSelectFilter::make('Industry')
                ->options(
                    $query
                )
                ->filter(function(Builder $builder, array $values) {
                    $builder->whereIn('Industry', $values);
                })
                
        ];
    }

}
