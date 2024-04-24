@extends('admin-layout')

@section('content')
<div class="container-fluid">
    <div class="row vh-100">
        <!-- Dashboard Column -->
        <div class="col-8 vh-100 overflow-auto">
            <!-- Column Heading -->
            <h1 class="display-6 py-2">Dashboard</h1>
            <h3>Overview</h1>

            <div class="row">
                <!-- Dashboard Charts -->
                <livewire:admin.admin-charts/>
                <livewire:scripts />
                @livewireChartsScripts
            </div>
            <div class="row">
                <!-- Table -->
                <livewire:admin.admin-dashboard-table theme="bootstrap-5" /> <!-- https://rappasoft.com/docs/laravel-livewire-tables/v3/introduction -->
            </div>

        </div>


        <!-- Timeline Column -->
        <div class="col-4 border-start border-muted vh-100 overflow-auto">
            <!-- Column Heading -->
            <h3>Timeline</h3>

            <!-- Timeline Start -->
            <!-- TODO: Add looping logic for each event in timeline -->
            <!-- TODO: Implement activity log https://spatie.be/docs/laravel-activitylog/v4/introduction -->

            <!-- Timeline Card -->
            <div class="card text-bg-light mb-3">
                <div class="card-body">
                    <div class="row"> 
                        <!-- Profile Picture -->
                        <div class="col-lg-3 d-flex align-items-center p-2">
                        <img src="http://127.0.0.1:8000/storage/profile_pictures/leon.png" alt="" class="img-fluid rounded-circle" style="max-height:60px;">
                        </div>

                        <!-- Activity Description -->
                        <div class="col-lg-9">
                            <h5 class="card-title"><b>Employee Leon</b> has completed Quiz 1, Chapter 2.</h5> 
                        </div>
                    </div>
                    <!-- Timestamp and Relevant Info -->
                    <div class="row gx-3 mt-2">
                        <div class="col-lg-8 d-flex align-items-center">
                            <!-- Timestamp -->
                            <p class="card-subtitle">20/11/2023 20:55:19</p>
                        </div>
                        <div class="col-lg-4 d-flex align-items-center">
                            <!-- Info -->
                            <p class="card-subtitle">Admin Department Onboarding</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline Card -->
            <div class="card text-bg-light mb-3">
                <div class="card-body">
                    <div class="row"> 
                        <!-- Profile Picture -->
                        <div class="col-lg-3 d-flex align-items-center p-2">
                        <img src="http://127.0.0.1:8000/storage/profile_pictures/leon.png" alt="" class="img-fluid rounded-circle" style="max-height:60px;">
                        </div>

                        <!-- Activity Description -->
                        <div class="col-lg-9">
                            <h5 class="card-title"><b>Employee Leon</b> has completed Quiz 1, Chapter 2.</h5>
                        </div>
                    </div>
                    <!-- Timestamp and Relevant Info -->
                    <div class="row gx-3 mt-2">
                        <div class="col-lg-8 d-flex align-items-center">
                            <!-- Timestamp -->
                            <p class="card-subtitle">20/11/2023 20:55:19</p>
                        </div>
                        <div class="col-lg-4 d-flex align-items-center">
                            <!-- Info -->
                            <p class="card-subtitle">Admin Department Onboarding</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline Card -->
            <div class="card text-bg-light mb-3">
                <div class="card-body">
                    <div class="row"> 
                        <!-- Profile Picture -->
                        <div class="col-lg-3 d-flex align-items-center p-2">
                        <img src="http://127.0.0.1:8000/storage/profile_pictures/leon.png" alt="" class="img-fluid rounded-circle" style="max-height:60px;">
                        </div>

                        <!-- Activity Description -->
                        <div class="col-lg-9">
                            <h5 class="card-title"><b>Employee Leon</b> has completed Quiz 1, Chapter 2.</h5> <!-- TODO: Input dynamic text and activities according to database changes -->
                        </div>
                    </div>
                    <!-- Timestamp and Relevant Info -->
                    <div class="row gx-3 mt-2">
                        <div class="col-lg-8 d-flex align-items-center">
                            <!-- Timestamp -->
                            <p class="card-subtitle">20/11/2023 20:55:19</p>
                        </div>
                        <div class="col-lg-4 d-flex align-items-center">
                            <!-- Info -->
                            <p class="card-subtitle">Admin Department Onboarding</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Timeline Card -->
            <div class="card text-bg-light mb-3">
                <div class="card-body">
                    <div class="row"> 
                        <!-- Profile Picture -->
                        <div class="col-lg-3 d-flex align-items-center p-2">
                        <img src="http://127.0.0.1:8000/storage/profile_pictures/leon.png" alt="" class="img-fluid rounded-circle" style="max-height:60px;">
                        </div>

                        <!-- Activity Description -->
                        <div class="col-lg-9">
                            <h5 class="card-title"><b>Employee Leon</b> has completed Quiz 1, Chapter 2.</h5>
                        </div>
                    </div>
                    <!-- Timestamp and Relevant Info -->
                    <div class="row gx-3 mt-2">
                        <div class="col-lg-8 d-flex align-items-center">
                            <!-- Timestamp -->
                            <p class="card-subtitle">20/11/2023 20:55:19</p>
                        </div>
                        <div class="col-lg-4 d-flex align-items-center">
                            <!-- Info -->
                            <p class="card-subtitle">Admin Department Onboarding</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline Card -->
            <div class="card text-bg-light mb-3">
                <div class="card-body">
                    <div class="row"> 
                        <!-- Profile Picture -->
                        <div class="col-lg-3 d-flex align-items-center p-2">
                        <img src="http://127.0.0.1:8000/storage/profile_pictures/leon.png" alt="" class="img-fluid rounded-circle" style="max-height:60px;">
                        </div>

                        <!-- Activity Description -->
                        <div class="col-lg-9">
                            <h5 class="card-title"><b>Employee Leon</b> has completed Quiz 1, Chapter 2.</h5>
                        </div>
                    </div>
                    <!-- Timestamp and Relevant Info -->
                    <div class="row gx-3 mt-2">
                        <div class="col-lg-8 d-flex align-items-center">
                            <!-- Timestamp -->
                            <p class="card-subtitle">20/11/2023 20:55:19</p>
                        </div>
                        <div class="col-lg-4 d-flex align-items-center">
                            <!-- Info -->
                            <p class="card-subtitle">Admin Department Onboarding</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline Card -->
            <div class="card text-bg-light mb-3">
                <div class="card-body">
                    <div class="row"> 
                        <!-- Profile Picture -->
                        <div class="col-lg-3 d-flex align-items-center p-2">
                        <img src="http://127.0.0.1:8000/storage/profile_pictures/leon.png" alt="" class="img-fluid rounded-circle" style="max-height:60px;">
                        </div>

                        <!-- Activity Description -->
                        <div class="col-lg-9">
                            <h5 class="card-title"><b>Employee Leon</b> has completed Quiz 1, Chapter 2.</h5> 
                        </div>
                    </div>
                    <!-- Timestamp and Relevant Info -->
                    <div class="row gx-3 mt-2">
                        <div class="col-lg-8 d-flex align-items-center">
                            <!-- Timestamp -->
                            <p class="card-subtitle">20/11/2023 20:55:19</p>
                        </div>
                        <div class="col-lg-4 d-flex align-items-center">
                            <!-- Info -->
                            <p class="card-subtitle">Admin Department Onboarding</p>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>

    </div>


</div>

@endsection