<?php

namespace App\Livewire\Employee;

use Illuminate\Support\Collection;
use Omnia\LivewireCalendar\LivewireCalendar;
use Carbon\Carbon;
use App\Models\AssignedModule;
use App\Models\Module;
use Illuminate\Support\Facades\Log;

class ReminderCalendar extends LivewireCalendar
{
    public function events() : Collection
    {
        //Currently logged in user
        $user = auth()->user();

        //TODO: Join with Quiz Module to get Quiz due date
        return AssignedModule::query()
        ->where('UserID', $user->id)
        ->where('CompanyID', $user->companyUser()->first()->CompanyID)
        ->get()
        ->map(function (AssignedModule $model) {

            $module = Module::find($model->ModuleID);

            return [
                'id' => $model->ModuleID,
                'title' => 'Module Due 📚',
                'description' => $module->title,
                'date' => $model->due_date,
            ];
        });

        // return collect([
        //     [
        //         'id' => 1,
        //         'title' => 'Quiz 1 Due 📝',
        //         'description' => 'Admin Department Onboarding',
        //         'date' => Carbon::today(),
        //     ],
        //     [
        //         'id' => 2,
        //         'title' => 'Quiz 2 Due 📝',
        //         'description' => 'Company History Onboarding',
        //         'date' => Carbon::tomorrow(),
        //     ],
        //     [
        //         'id' => 3,
        //         'title' => 'Quiz 2 Due 📝',
        //         'description' => 'Admin Department Onboarding',
        //         'date' => Carbon::today(),
        //     ],
        //     [
        //         'id' => 4,
        //         'title' => 'Module Due 📚',
        //         'description' => 'Company History Onboarding',
        //         'date' => Carbon::yesterday(),
        //     ],
        // ]);
    }

    //Return Next Month
    public function nextMonth()
    {
        $this->goToNextMonth();
    }

    //Return Previous Month
    public function previousMonth()
    {
        $this->goToPreviousMonth();
    }

    //Back to Current Month
    public function currentMonth()
    {
        $this->goToCurrentMonth();
    }

}
