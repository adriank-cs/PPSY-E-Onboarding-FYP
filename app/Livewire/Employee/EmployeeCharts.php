<?php

namespace App\Livewire\Employee;

use App\Models\UserSession;

use Asantibanez\LivewireCharts\Facades\LivewireCharts;
use Asantibanez\LivewireCharts\Models\RadarChartModel;
use Asantibanez\LivewireCharts\Models\TreeMapChartModel;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class EmployeeCharts extends Component
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

        //Get all current user's sessions
        $usersessions = UserSession::query()
        ->join('users', 'user_session.UserID', '=', 'users.id')
        ->join('profiles', 'users.id', '=', 'profiles.user_id')
        ->join('companyusers', 'users.id', '=', 'companyusers.UserID')
        ->orderBy('first_activity_at', 'asc')
        ->addSelect(DB::raw('DAYNAME(first_activity_at) as DayOfWeek'))
        ->addSelect(DB::raw('DAYOFWEEK(first_activity_at) as DayNum'))
        ->where('users.id', '=', $user->id);

        //Data for Bar Chart
        $barChartData = $usersessions->whereBetween('first_activity_at', [now()->subDays(7)->startOfWeek(Carbon::SUNDAY), now()->subDays(7)->endOfWeek(Carbon::SATURDAY)])
        ->selectRaw('SEC_TO_TIME(SUM(TIME_TO_SEC(duration))) as total_duration')
        ->groupBy('DayOfWeek', 'DayNum')->get();

        //Log::info(json_encode($test));

        //Fill in missing days
        for ($i = 0; $i < count($this->days); $i++) {
            $day = $this->days[$i];

            //Insert to correct position
            if (!$barChartData->contains('DayOfWeek', $day)) {
                $barChartData->push([
                    'DayOfWeek' => $day,
                    'DayNum' => $i + 1,
                    'total_duration' => '00:00:00',
                ]);
            }
        }

        //Time spent on system each day - Bar Chart (Current Week Only)
        $barChartModel = $barChartData->sortBy('DayNum')
        ->reduce(function ($barChartModel, $data) {

            $day = $data['DayOfWeek'];
            $value = round(Carbon::parse($data['total_duration'])->floatDiffInHours('00:00:00'), 2);

            return $barChartModel->addColumn($day, $value, $this->dayColors[$day]);

        }, LivewireCharts::columnChartModel()
            ->setTitle('Time Spent (Hours)')
            ->setAnimated($this->firstRun)
            ->withoutLegend()
            ->withoutDataLabels()
        );

        //Data for Line Chart
        $lineChartData = $usersessions->whereBetween('first_activity_at', [now()->subDays(7)->startOfWeek(Carbon::SUNDAY), now()->subDays(7)->endOfWeek(Carbon::SATURDAY)])
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

        //Average Engagement
        $engagementLevel = 0.0;

        //Average Session Length
        $avgSessionLength = 0.0;

        //Engagement Level - Line Chart (Current Week Only)
        $lineChartModel = $lineChartData->sortBy('DayNum')
        ->reduce(function ($lineChartModel, $data) use (&$engagementLevel, &$avgSessionLength) {
            //Parameter for hours to fulfill each day (How many hours per day should employees engage)
            $hoursPerDay = 4.0;

            $day = $data['DayOfWeek'];

            //Engagement Percentage
            $value = round(Carbon::parse($data['total_duration'])->floatDiffInHours('00:00:00'), 2) / $hoursPerDay * 100;
            $value = round($value, 0);
            
            //Add to total engagement level
            $engagementLevel += $value;

            //Add to average session length
            $avgSessionLength += Carbon::parse($data['total_duration'])->diffInMilliseconds('00:00:00');

            //Add engagement level
            $lineChartModel->addSeriesPoint("Engagement (%)", $day, $value, $this->dayColors[$day]);

            //Add minimum engagement level (Marker)
            return $lineChartModel->addSeriesPoint("Minimum Engagement (%)", $day, round(40.0, 0));
        }, LivewireCharts::multiLineChartModel()
            ->setAnimated($this->firstRun)
            ->withOnPointClickEvent('onPointClick')
            ->setSmoothCurve()
            ->setDataLabelsEnabled(false)
            ->sparklined()
        );

        //Calculate average engagement level
        $engagementLevel = round($engagementLevel / count($this->days), 0);

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
        $avgSessionLength = Carbon::createFromTimestampMs($avgSessionLength, 'UTC')->format('G:i');

        return view('livewire.employee.employee-charts')
        ->with([
            'barChartModel' => $barChartModel,
            'lineChartModel' => $lineChartModel,
            'engagementLevel' => $engagementLevel,
            'avgSessionLength' => $avgSessionLength,
        ]);
    }
}
