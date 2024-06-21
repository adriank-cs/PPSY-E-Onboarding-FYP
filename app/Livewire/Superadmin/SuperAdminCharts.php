<?php

namespace App\Livewire\Superadmin;

use App\Models\UserSession;
use App\Models\Company;

use Asantibanez\LivewireCharts\Facades\LivewireCharts;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class SuperAdminCharts extends Component
{

    public $showDataLabels = false;
    public $firstRun = true;

    //All companies
    public $companies;

    //Color Template
    public $companyColors;

    public function render()
    {
        //Get all companies
        $this->companies = Company::all();

        //Assign colors
        $tempColors = [];

        for ($i = 0; $i < $this->companies->count(); $i++) {
            $tempColors[$i] = sprintf( "#%06X", mt_rand( 0, 0xFFFFFF ));
        }

        //Assign colors to companies
        $this->companyColors = $this->companies->pluck('Name')->combine($tempColors);

        //Get all user sessions of users (Regardless of users)
        $usersessions = UserSession::query()
        ->join('users', 'user_session.UserID', '=', 'users.id')
        ->join('profiles', 'users.id', '=', 'profiles.user_id')
        ->join('companyusers', 'users.id', '=', 'companyusers.UserID')
        ->join('companies', 'companyusers.CompanyID', '=', 'companies.CompanyID')
        ->orderBy('first_activity_at', 'asc')
        ->addSelect(DB::raw('companies.Name as CompanyName'));

        //Data for Bar Chart
        $barChartData = $usersessions->whereBetween('first_activity_at', [now()->subDays(7)->startOfWeek(Carbon::SUNDAY), now()->subDays(7)->endOfWeek(Carbon::SATURDAY)])
        ->selectRaw('SEC_TO_TIME(AVG(TIME_TO_SEC(duration))) as average_duration')
        ->groupBy('CompanyName')->get();

        //Fill in missing companies
        for ($i = 0; $i < $this->companies->count(); $i++) {
            $company = $this->companies->pluck('Name')[$i];

            //Insert to correct position
            if (!$barChartData->contains('CompanyName', $company)) {
                $barChartData->push([
                    'CompanyName' => $company,
                    'average_duration' => '00:00:00',
                ]);
            }
        }

        //Time spent on system each day - Bar Chart (Previous Week Only)
        $barChartModel = $barChartData->sortBy('DayNum')
        ->reduce(function ($barChartModel, $data) {

            $company = $data['CompanyName'];
            $value = round(Carbon::parse($data['average_duration'])->floatDiffInHours('00:00:00'), 2);

            return $barChartModel->addColumn($company, $value, $this->companyColors[$company]);

        }, LivewireCharts::columnChartModel()
            ->setTitle('Time Spent (Hours)')
            ->setAnimated($this->firstRun)
            ->withoutLegend()
            ->withoutDataLabels()
        );

        //Data for Pie Chart
        $pieChartData = $usersessions->whereBetween('first_activity_at', [now()->subDays(7)->startOfWeek(Carbon::SUNDAY), now()->subDays(7)->endOfWeek(Carbon::SATURDAY)])
        ->selectRaw('SUM(TIME_TO_SEC(duration)) as total_duration')
        ->groupBy('CompanyName')->get();

        //Total Seconds from All Companies
        $totalSeconds = $pieChartData->pluck('total_duration')->sum();

        $pieChartModel = $pieChartData
        ->reduce(function ($pieChartModel, $data) use ($totalSeconds) {
            $company = $data['CompanyName'];
            $value = round($data['total_duration'] / $totalSeconds * 100, 0);

            return $pieChartModel->addSlice($company, $value, $this->companyColors[$company]);

        }, LivewireCharts::pieChartModel()
            ->setAnimated($this->firstRun)
            ->asPie()
            ->legendPositionBottom()
            ->legendHorizontallyAlignedCenter()
            ->setDataLabelsEnabled($this->showDataLabels)
            ->setColors($this->companyColors->values()->toArray())
        );

        return view('livewire.superadmin.super-admin-charts')
        ->with([
            'barChartModel' => $barChartModel,
            'pieChartModel' => $pieChartModel,
        ]);
    }
}
