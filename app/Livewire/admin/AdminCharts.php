<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Asantibanez\LivewireCharts\Facades\LivewireCharts;
use Asantibanez\LivewireCharts\Models\RadarChartModel;
use Asantibanez\LivewireCharts\Models\TreeMapChartModel;
use Livewire\Component;
use Illuminate\Support\Facades\Log;


class AdminCharts extends Component
{

    public $colors = [
        'Leon Jensen Tan' => '#ff0000',
        'Lee Jong Suk' => '#0000ff',
        'Bae Suzy' => '#90cdf4',
        'Lee Dong Wook' => '#66DA26',
        'Super Admin' => '#cbd5e0',
        'Testing Employee' => '#cbd5e0',
        'Test' => '#cbd5e0',
    ];
    public $showDataLabels = false;
    public $firstRun = true;

    public function render()
    {
        $users = User::all('id','name');

        //Pie Chart
        $pieChartModel = $users
        ->reduce(function ($pieChartModel, $data) {
            $name = $data->name;
            $value = 1;
            return $pieChartModel->addSlice($name, $value, '#0000ff'); //TODO: Reprogram for actual purpose $this->colors[$name]
        }, LivewireCharts::pieChartModel()
            //->setTitle('Expenses by Type')
            ->setAnimated($this->firstRun)
            ->setType('donut')
            ->withOnSliceClickEvent('onSliceClick')
            //->withoutLegend()
            ->legendPositionBottom()
            ->legendHorizontallyAlignedCenter()
            ->setDataLabelsEnabled($this->showDataLabels)
            ->setColors($this->colors)
        );

        //Line Chart
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
            'pieChartModel' => $pieChartModel,
            'lineChartModel' => $lineChartModel,
        ]);

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
