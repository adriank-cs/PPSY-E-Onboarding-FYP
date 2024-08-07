<?php

namespace App\Livewire\Admin;

use App\Models\UserSession;
use App\Models\Profile;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Rappasoft\LaravelLivewireTables\Views\Columns\ComponentColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\MultiSelectFilter;
use App\Models\ItemProgress;

class AdminDashboardTable extends DataTableComponent
{
    //Query to get users, profiles and user sessions
    public function builder(): Builder
    {
        //Currently logged in admin
        $user = auth()->user();

        //Table Query
        $query = UserSession::query()
            ->join('users', 'user_session.UserID', '=', 'users.id')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->join('companyusers', 'users.id', '=', 'companyusers.UserID')
            ->where('companyusers.CompanyID', '=', $user->companyUser()->first()->CompanyID)
            ->whereColumn('users.last_active_session', '=', 'user_session.id')
            ->orderBy('users.last_active_session', 'desc')
            ->limit(10);

        return $query;
    }

    public function configure(): void
    {
        //General Configuration
        $this->setPrimaryKey('id');
        $this->setOfflineIndicatorStatus(false);

        //Search Configuration
        $this->setSearchPlaceholder('Search Names...');

        //Filter Configuration
        $this->setFilterLayoutPopover();

    }

    //Columns in the table
    public function columns(): array
    {
        return [
            Column::make("Employee ID", "user.profile.employee_id")
                ->sortable(),
            Column::make("Name", "user.name")
                ->searchable()
                ->sortable(),
            Column::make("Department", "user.profile.dept")
                ->sortable(),
            ComponentColumn::make('Progress', 'UserID')
                ->component('table.progress-bar')
                ->attributes(fn ($value, $row, Column $column) => [
                    'progress' => round(ItemProgress::where('UserID', $value)->get()->reduce(function ($numberCompleted, $data) {
                        if ($data->IsCompleted) {
                            $numberCompleted++;
                        }

                        return $numberCompleted;
                    }) / (ItemProgress::where('UserID', $value)->get()->count() > 0 ? ItemProgress::where('UserID', $value)->get()->count() : 1) * 100, 0) . '%'
                ]),
            Column::make("Last Activity At", "last_activity_at")
                ->format (fn ($value, $row, Column $column) => $value->setTimezone('Asia/Kuala_Lumpur')->toDayDateTimeString())
                ->sortable(),
        ];
    }

    public function filters(): array
    {
        //Queries only departments in the company
        $query = Profile::query()
        ->join('users', 'profiles.user_id', '=', 'users.id')
        ->join('companyusers', 'users.id', '=', 'companyusers.UserID')
        ->where('companyusers.CompanyID', '=', auth()->user()->companyUser()->first()->CompanyID)
        ->select('dept')
        ->distinct()
        ->orderBy('dept')
        ->get()
        ->keyBy('dept')
        ->map(fn ($dept) => $dept->dept)
        ->toArray();

        return [
            MultiSelectFilter::make('Department')
                ->options(
                    $query
                )
                ->filter(function(Builder $builder, array $values) {
                    //Filter to department
                    //Log::info(print_r($values, true));
                    $builder->whereHas('user.profile', fn($query) => $query->whereIn('dept', $values));
                })
                
        ];
    }
}
