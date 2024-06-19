
<div class="row gy-2">
    <!-- Main Chart -->
    <div class="col-lg-6">
        <div class="card shadow border-light rounded h-100">
            <div class="card-body pb-5" style="height: 40vh;">
                <h6 class="card-title">Average Time Spent on System (All Companies)</h6>
                <h6 class="text-dark text-opacity-50">Previous Week</h6>
                <livewire:livewire-column-chart
                        :column-chart-model="$barChartModel"
                    />
            </div>
        </div> 
    </div>

    <!-- Secondary Chart -->
    <div class="col-lg-6">

        <!-- Engagement -->
        <div class="card shadow border-light rounded h-100"> 
            <div class="card-body">
                <h6 class="card-title">Proportion of Time Spent on System (%)</h6>
                <h6 class="text-dark text-opacity-50 text-nowrap">Previous Week</h6>
                <livewire:livewire-pie-chart
                        :pie-chart-model="$pieChartModel"
                />
            </div>
        </div>

    </div>
</div>

