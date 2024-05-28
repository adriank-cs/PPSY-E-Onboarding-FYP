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
        <div class="card shadow border-light rounded">  
                <div class="card-body">
                    <h6 class="text-dark text-opacity-50 text-nowrap">Engagement</h6>
                    <h1 class="display-6 fw-bold text-nowrap">50%</h1>
                    <div style="height: 3rem;">
                        <livewire:livewire-line-chart
                            :line-chart-model="$lineChartModel"
                        />
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
