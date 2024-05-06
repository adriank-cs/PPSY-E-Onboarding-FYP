<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class DashboardTimeline extends Component
{
    //Array of all relevant activities
    public $activities;

    //TODO: Add actions for each timeline object if required https://livewire.laravel.com/docs/components#inline-components
    //Below will be placed inside activities array
    //Timestamp
    public $timestamp;

    //Subject (User model of the relevant user for the activity)
    public $subject;

    //Fetch relevant activity log from database and populate data
    public function mount($activities = null)
    {
        //Current user
        $user = auth()->user();

        //Tracked User Model
        $userActivities = [
            'User account updated',
            'User account created',
            'User account deleted',
        ];

        //Tracked Completion Activities
        $completionActivities = [
            'Flow Completion',
            'Quiz Completion',
        ];

        //Tracked activities
        $events = Arr::collapse([
            $userActivities, 
            $completionActivities
        ]);
        
        //Assign activities properties
        $this->activities = Activity::query()
        ->select('*')
        ->whereIn('description', $events) //Only select tracked events
        ->where('causer_type', 'App\Models\User') //Must be caused by a user
        ->join('users', 'activity_log.causer_id', '=', 'users.id')
        ->join('profiles', 'users.id', '=', 'profiles.user_id')
        ->join('companyusers', 'users.id', '=', 'companyusers.UserID')
        ->where('companyusers.CompanyID', '=', $user->companyUser()->first()->CompanyID)
        ->get();

        Log::info($this->activities);
        Log::info(print_r($this->activities, true));
    }

    public function render()
    {
        //TODO: Implement logic to differentiate between different activities
        //$profilepicture;
        
        return view('livewire.admin.dashboard-timeline');
    }


}
