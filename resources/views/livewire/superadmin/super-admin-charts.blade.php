<div class="row">
    <!-- Main Chart -->
    <div class="col-6">
        <div class="card shadow border-light rounded h-100">
            <div class="card-body">
                <h6 class="card-title">Average Time Spent on System (All Companies)</h6>
                <h6 class="text-dark text-opacity-50">Previous Week</h6>
                <livewire:livewire-column-chart
                        key="{{ $barChartModel->reactiveKey() }}"
                        :column-chart-model="$barChartModel"
                    />
            </div>
        </div> 
    </div>

    <!-- Secondary Chart -->
    <div class="col-6">

        <!-- Engagement -->
        <div class="card shadow border-light rounded h-100"> 
            <div class="card-body">
                <h6 class="card-title">Proportion of Time Spent on System (%)</h6>
                <h6 class="text-dark text-opacity-50 text-nowrap">Previous Week</h6>
                <livewire:livewire-pie-chart
                        key="{{ $pieChartModel->reactiveKey() }}"
                        :pie-chart-model="$pieChartModel"
                />
            </div>
        </div>

    </div>

</div>
