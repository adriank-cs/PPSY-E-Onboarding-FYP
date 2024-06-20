<?php

namespace App\Livewire\Admin;

use App\Models\AssignedModule;
use App\Models\Module;
use App\Models\UserSession;
use App\Models\Profile;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Rappasoft\LaravelLivewireTables\Views\Columns\ComponentColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\MultiSelectFilter;
use App\Models\ItemProgress;

class AdminProgressTrackingTable extends DataTableComponent
{
    //Query to get users, profiles and user sessions
    public function builder(): Builder
    {
        //Currently logged in admin
        $user = auth()->user();

        //Table Query
        $query = AssignedModule::query()
            ->join('users', 'assigned_module.UserID', '=', 'users.id')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->join('companyusers', 'users.id', '=', 'companyusers.UserID')
            ->join('modules', 'assigned_module.ModuleID', '=', 'modules.id')
            ->where('companyusers.CompanyID', '=', $user->companyUser()->first()->CompanyID);

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

        //Row URL
        // $this->setTableRowUrl(function($row) {
        //     return route('admin.colleague_details', $row->UserID);
        // })
        // ->setTableRowUrlTarget(function($row) {
        //     return '_blank';
        // });
    }

    public function columns(): array
    {
        return [
            Column::make("Employee ID", "user.profile.employee_id")
                ->sortable(),
            Column::make("Name", "user.name")
                ->sortable(),
            Column::make("Department", "user.profile.dept")
                ->sortable(),
            Column::make("Postion", "user.profile.position")
                ->sortable(),
            Column::make("Module", "ModuleID")
                ->format (fn ($value, $row, Column $column) => Module::find($value)->title)
                ->sortable(),
            ComponentColumn::make('Progress', 'UserID')
                ->component('table.progress-bar')
                ->attributes(fn ($value, $row, Column $column) => [
                    'progress' => round(ItemProgress::where('UserID', $value)->where('ModuleID', $row->ModuleID)->get()->reduce(function ($numberCompleted, $data) use ($row) {
                        if ($data->IsCompleted) {
                            $numberCompleted++;
                        }

                        return $numberCompleted;
                    }) / (ItemProgress::where('UserID', $value)->where('ModuleID', $row->ModuleID)->get()->count() > 0 ? ItemProgress::where('UserID', $value)->where('ModuleID', $row->ModuleID)->get()->count() : 1) * 100, 0) . '%'
                ]),
            Column::make('Items Completed', 'UserID')
            ->format(fn ($value, $row, Column $column) => ItemProgress::where('UserID', $value)->where('ModuleID', $row->ModuleID)->get()->reduce(function ($numberCompleted, $data) use ($row) {
                if ($data->IsCompleted) {
                    $numberCompleted++;
                }

                return $numberCompleted;
            }) . " of " . ItemProgress::where('UserID', $value)->where('ModuleID', $row->ModuleID)->get()->count()
            ),
        ];
    }
}
