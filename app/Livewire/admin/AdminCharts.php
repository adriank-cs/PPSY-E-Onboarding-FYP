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

    public function render()
    {
        //Currently logged in user
        $user = auth()->user();

        $users = User::all();

        //Days to track
        $days = [
            'Sunday',
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
        ];

        //Get all user sessions of users with same company ID as admin
        $usersessions = UserSession::query()
        ->join('users', 'user_session.UserID', '=', 'users.id')
        ->join('profiles', 'users.id', '=', 'profiles.user_id')
        ->join('companyusers', 'users.id', '=', 'companyusers.UserID')
        ->orderBy('first_activity_at', 'asc')
        ->addSelect(DB::raw('DAYNAME(first_activity_at) as DayOfWeek'))
        ->addSelect(DB::raw('DAYOFWEEK(first_activity_at) as DayNum'))
        ->where('companyusers.CompanyID', '=', $user->companyUser()->first()->CompanyID);

        //Data for Bar Chart
        $barChartData = $usersessions->whereBetween('first_activity_at', [now()->startOfWeek(Carbon::MONDAY), now()->endOfWeek(Carbon::SUNDAY)])
        ->selectRaw('SEC_TO_TIME(AVG(TIME_TO_SEC(duration))) as average_duration')
        ->groupBy('DayOfWeek', 'DayNum')->get();

        //Fill in missing days
        for ($i = 0; $i < count($days); $i++) {
            $day = $days[$i];

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

            Carbon::parse($data['average_duration'])->floatDiffInHours('00:00:00');
            $day = $data['DayOfWeek'];
            $value = round(Carbon::parse($data['average_duration'])->floatDiffInHours('00:00:00'), 2);

            return $barChartModel->addColumn($day, $value, '#ff0000'); //TODO: Set colors

        }, LivewireCharts::columnChartModel()
            ->setTitle('Time Spent (Hours)')
            ->setAnimated($this->firstRun)
            ->withoutLegend()
            ->withoutDataLabels()
        );

        $lineChartModel = $users
        ->reduce(function ($lineChartModel, $data) use ($users) {
            $index = $users->search($data);

            $amountSum = 10;

            // if ($index == 2) {
            //     $lineChartModel->addMarker(3, $amountSum);
            // }

            return $lineChartModel->addPoint($index, rand(0,20), ['id' => $data->id]);
        }, LivewireCharts::lineChartModel()
            ->setAnimated($this->firstRun)
            ->withOnPointClickEvent('onPointClick')
            ->withLegend()
            ->setSmoothCurve()
            ->setXAxisVisible(true)
            ->setDataLabelsEnabled(false)
            ->sparklined()
        );


        return view('livewire.admin.admin-charts')
        ->with([
            'barChartModel' => $barChartModel,
            'lineChartModel' => $lineChartModel,
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
