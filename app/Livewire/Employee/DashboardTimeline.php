<?php

namespace App\Livewire\Employee;

use Livewire\Component;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class DashboardTimeline extends Component
{
    //Array of all relevant activities
    public $activities;

    //Fetch relevant activity log from database and populate data
    public function mount($activities = null)
    {
        //Current user
        $user = auth()->user();

        //Tracked Completion Activities
        $completionActivities = [
            'Flow Completion',
            'Quiz Completion',
        ];

        //Tracked activities
        $events = Arr::collapse([
            $completionActivities,
        ]);
        
        //Assign activities properties
        $this->activities = Activity::query()
        ->select('*')
        ->addSelect('activity_log.created_at as activity_created_at', 'activity_log.updated_at as activity_updated_at')
        ->whereIn('description', $events) //Only select tracked events
        ->where('causer_type', 'App\Models\User') //Must be caused by a user
        ->join('users', 'activity_log.causer_id', '=', 'users.id')
        ->join('profiles', 'users.id', '=', 'profiles.user_id')
        ->join('companyusers', 'users.id', '=', 'companyusers.UserID')
        ->where('companyusers.CompanyID', '=', $user->companyUser()->first()->CompanyID)
        ->orderBy('activity_created_at', 'desc')
        ->limit(15) //Limit to 15 activities
        ->get();
    }

    public function render()
    {
        return view('livewire.employee.dashboard-timeline');
    }
}
