<div class="row gy-2">
    <!-- Main Chart -->
    <div class="col-lg-6">
        <div class="card shadow border-light rounded h-100">
            <div class="card-body">
                <h6 class="card-title">Average Time Spent on System (All Employees)</h6>
                <h6 class="text-dark text-opacity-50">Previous Week</h6>
                <livewire:livewire-column-chart
                            :column-chart-model="$barChartModel"
                    />
            </div>
        </div> 
    </div>

    <!-- Secondary Charts -->
    <div class="col-lg-6">
        <div class="row">
            <div class="col-sm-6 col-sm-12">
                <!-- Engagement -->
                <div class="card shadow border-light rounded"> 
                    <div class="card-body">
                        <h6 class="card-title">Engagement Level</h6>
                        <h6 class="text-dark text-opacity-50 text-nowrap">Previous Week</h6>
                        @if ($engagementLevel < 40)
                        <h2 class="fw-bold text-nowrap text-danger">{{$engagementLevel}}% <i class="ti ti-alert-triangle"></i></h2>
                        @else
                        <h1 class="display-6 fw-bold text-nowrap text-primary">{{$engagementLevel}}%</h1>
                        @endif
                        <livewire:livewire-line-chart
                                :line-chart-model="$lineChartModel"
                            />
                    </div>
                </div>
                <!-- Average Session -->
            </div>
            <div class="col-sm-6 col-sm-12">

                <div class="card shadow border-light rounded"> 
                    <div class="card-body">
                        <h6 class="card-title">Avg. Session (h:m)</h6>
                        <h6 class="text-dark text-opacity-50">Previous Week</h6>
                        <h2 class="fw-bold">{{$avgSessionLength}}</h2>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>
