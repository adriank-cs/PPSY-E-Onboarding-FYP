<?php

namespace App\Livewire\Employee;

use Illuminate\Support\Collection;
use Omnia\LivewireCalendar\LivewireCalendar;
use Carbon\Carbon;

class ReminderCalendar extends LivewireCalendar
{
    public function events() : Collection
    {
        return collect([
            [
                'id' => 1,
                'title' => 'Quiz 1 Due 📝',
                'description' => 'Admin Department Onboarding',
                'date' => Carbon::today(),
            ],
            [
                'id' => 2,
                'title' => 'Quiz 2 Due 📝',
                'description' => 'Company History Onboarding',
                'date' => Carbon::tomorrow(),
            ],
            [
                'id' => 3,
                'title' => 'Quiz 2 Due 📝',
                'description' => 'Admin Department Onboarding',
                'date' => Carbon::today(),
            ],
            [
                'id' => 4,
                'title' => 'Module Due 📚',
                'description' => 'Company History Onboarding',
                'date' => Carbon::yesterday(),
            ],
        ]);
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
