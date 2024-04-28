<div class="row" style="height: 20rem;">
    <!-- Main Chart -->
    <div class="col-6">
        <livewire:livewire-pie-chart
                        :pie-chart-model="$pieChartModel"
        />
    </div>

    <!-- Secondary Charts -->
    <div class="col-3 p-2">
        <div class="row">
            <h5>User Count over Month</h5>
        </div>
        <div class="row">
            <livewire:livewire-line-chart
                :line-chart-model="$lineChartModel"
                />
        </div>
    </div>

    <!-- Secondary Charts -->
    <div class="col-3">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title>">Current Engagement</h6>
            </div>
            <div class="card-body">
                <p>{{rand(10,95)}}%</p>
            </div>
        </div>
    </div>

</div>
