<div class="row">
    <!-- Main Chart -->
    <div class="col-6">
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
    <div class="col-3">
        <div class="row">
            <div class="col-6 col-sm-12">
                <!-- Engagement -->
                <div class="card shadow border-light rounded"> 
                    <div class="card-body">
                        <h6 class="text-dark text-opacity-50 text-nowrap">Engagement</h6>
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
            <div class="col-6 col-sm-12">

                <div class="card shadow border-light rounded"> 
                    <div class="card-body">
                        <h6 class="text-dark text-opacity-50">Avg. Session (h:m)</h6>
                        <h2 class="fw-bold">{{$avgSessionLength}}</h2>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Secondary Charts TODO: Update charts -->
    <div class="col-3">
        <div class="card shadow border-light rounded">
            <div class="card-header">
                <h6 class="card-title>">Create Activity in Timeline</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.create-activity') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">Create</button>
                </form>
            </div>
        </div>
    </div>

</div>
