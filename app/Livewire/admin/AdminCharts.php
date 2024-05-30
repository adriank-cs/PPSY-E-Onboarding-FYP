<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\UserSession;

use Asantibanez\LivewireCharts\Facades\LivewireCharts;
use Asantibanez\LivewireCharts\Models\RadarChartModel;
use Asantibanez\LivewireCharts\Models\TreeMapChartModel;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class AdminCharts extends Component
{

    public $showDataLabels = false;
    public $firstRun = true;

    //Days to track
    public $days = [
        'Sunday',
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday',
    ];

    //Day Colors
    public $dayColors = [
        'Sunday' => '#9E0031',
        'Monday' => '#8E0045',
        'Tuesday' => '#770058',
        'Wednesday' => '#600047',
        'Thursday' => '#44001A',
        'Friday' => '#55172F',
        'Saturday' => '#642C42',
    ];

    public function render()
    {
        //Currently logged in user
        $user = auth()->user();

        $users = User::all();

        //Get all user sessions of users with same company ID as admin
        $usersessions = UserSession::query()
        ->join('users', 'user_session.UserID', '=', 'users.id')
        ->join('profiles', 'users.id', '=', 'profiles.user_id')
        ->join('companyusers', 'users.id', '=', 'companyusers.UserID')
        ->orderBy('first_activity_at', 'asc')
        ->addSelect(DB::raw('DAYNAME(first_activity_at) as DayOfWeek'))
        ->addSelect(DB::raw('DAYOFWEEK(first_activity_at) as DayNum'))
        ->where('companyusers.CompanyID', '=', $user->companyUser()->first()->CompanyID)
        ->where('companyusers.isAdmin', '=', 0); //Exclude admins

        //Data for Bar Chart
        $barChartData = $usersessions->whereBetween('first_activity_at', [now()->startOfWeek(Carbon::MONDAY), now()->endOfWeek(Carbon::SUNDAY)])
        ->selectRaw('SEC_TO_TIME(AVG(TIME_TO_SEC(duration))) as average_duration')
        ->groupBy('DayOfWeek', 'DayNum')->get();

        //Fill in missing days
        for ($i = 0; $i < count($this->days); $i++) {
            $day = $this->days[$i];

            //Insert to correct position
            if (!$barChartData->contains('DayOfWeek', $day)) {
                $barChartData->push([
                    'DayOfWeek' => $day,
                    'DayNum' => $i + 1,
                    'average_duration' => '00:00:00',
                ]);
            }
        }

        //Time spent on system each day - Bar Chart (Current Week Only)
        $barChartModel = $barChartData->sortBy('DayNum')
        ->reduce(function ($barChartModel, $data) {

            $day = $data['DayOfWeek'];
            $value = round(Carbon::parse($data['average_duration'])->floatDiffInHours('00:00:00'), 2);

            return $barChartModel->addColumn($day, $value, $this->dayColors[$day]);

        }, LivewireCharts::columnChartModel()
            ->setTitle('Time Spent (Hours)')
            ->setAnimated($this->firstRun)
            ->withoutLegend()
            ->withoutDataLabels()
        );

        //Data for Line Chart
        $lineChartData = $usersessions->whereBetween('first_activity_at', [now()->startOfWeek(Carbon::MONDAY), now()->endOfWeek(Carbon::SUNDAY)])
        ->selectRaw('SEC_TO_TIME(SUM(TIME_TO_SEC(duration))) as total_duration')
        ->groupBy('DayOfWeek', 'DayNum')->get();

        //Fill in missing days
        for ($i = 0; $i < count($this->days); $i++) {
            $day = $this->days[$i];

            //Insert to correct position
            if (!$lineChartData->contains('DayOfWeek', $day)) {
                $lineChartData->push([
                    'DayOfWeek' => $day,
                    'DayNum' => $i + 1,
                    'total_duration' => '00:00:00',
                ]);
            }
        }

        //Count number of employees in the same company
        $numberOfEmployees = UserSession::query()
        ->join('users', 'user_session.UserID', '=', 'users.id')
        ->join('profiles', 'users.id', '=', 'profiles.user_id')
        ->join('companyusers', 'users.id', '=', 'companyusers.UserID')
        ->addSelect('users.id')
        ->where('companyusers.CompanyID', '=', $user->companyUser()->first()->CompanyID)
        ->where('companyusers.isAdmin', '=', 0)
        ->distinct('users.id')
        ->count();

        //Average Engagement
        $engagementLevel = 0.0;

        //Average Session Length
        $avgSessionLength = 0.0;

        //Engagement Level - Line Chart (Current Week Only)
        $lineChartModel = $lineChartData->sortBy('DayNum')
        ->reduce(function ($lineChartModel, $data) use ($numberOfEmployees, &$engagementLevel, &$avgSessionLength) {
            //Parameter for hours to fulfill each day (How many hours per day should employees engage)
            $hoursPerDay = 4.0;
            $totalHours = $hoursPerDay * $numberOfEmployees;

            $day = $data['DayOfWeek'];

            //Engagement Percentage
            $value = round(Carbon::parse($data['total_duration'])->floatDiffInHours('00:00:00'), 2) / $totalHours * 100;
            $value = round($value, 0);
            
            //Add to total engagement level
            $engagementLevel += $value;

            //Add to average session length
            $avgSessionLength += Carbon::parse($data['total_duration'])->diffInMilliseconds('00:00:00') / $numberOfEmployees;

            //Add engagement level
            $lineChartModel->addSeriesPoint("Engagement (%)", $day, $value, $this->dayColors[$day]);

            //Add minimum engagement level (Marker)
            return $lineChartModel->addSeriesPoint("Minimum Engagement (%)", $day, round(40.0, 0));;
        }, LivewireCharts::multiLineChartModel()
            ->setAnimated($this->firstRun)
            ->withOnPointClickEvent('onPointClick')
            ->setSmoothCurve()
            ->setDataLabelsEnabled(false)
            ->sparklined()
        );

        //Calculate average engagement level
        $engagementLevel = round($engagementLevel / count($this->days), 0);

        //TODO: For testing purpose only
        //$engagementLevel = 35;

        //Set colors based on engagement level
        if ($engagementLevel < 40) {
            $lineChartModel
            ->setColors(['#FF0505', '#5A6A85']);
        }
        else {
            $lineChartModel
            ->setColors(['#6A1043', '#5A6A85']);
        }

        //Calculate average session length
        $avgSessionLength = round($avgSessionLength / count($this->days), 0);
        $avgSessionLength = Carbon::createFromTimestampMs($avgSessionLength)->format('G:i');

        return view('livewire.admin.admin-charts')
        ->with([
            'barChartModel' => $barChartModel,
            'lineChartModel' => $lineChartModel,
            'engagementLevel' => $engagementLevel,
            'avgSessionLength' => $avgSessionLength,
        ]);

        // $lineChartModel = $users
        // ->reduce(function ($lineChartModel, $data) use ($users) {
        //     $index = $users->search($data);

        //     $amountSum = 10;

        //     // if ($index == 2) {
        //     //     $lineChartModel->addMarker(3, $amountSum);
        //     // }

        //     return $lineChartModel->addPoint($index, rand(0,20), ['id' => $data->id]);
        // }, LivewireCharts::lineChartModel()
        //     ->setAnimated($this->firstRun)
        //     ->withOnPointClickEvent('onPointClick')
        //     ->withLegend()
        //     ->setSmoothCurve()
        //     ->setXAxisVisible(true)
        //     ->setDataLabelsEnabled(false)
        //     ->sparklined()
        // );

        // return view('livewire.dashboard')
        // ->with([
        //     'columnChartModel' => $columnChartModel,
        //     'pieChartModel' => $pieChartModel,
        //     'lineChartModel' => $lineChartModel,
        //     'areaChartModel' => $areaChartModel,
        //     'multiLineChartModel' => $multiLineChartModel,
        //     'multiColumnChartModel' => $multiColumnChartModel,
        //     'radarChartModel' => $radarChartModel,
        //     'treeChartModel' => $treeChartModel,
        // ]);
    }
}
