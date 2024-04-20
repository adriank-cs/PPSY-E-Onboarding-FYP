@extends('superadmin-layout')

@section('content')
<div class="container-fluid">
    <div class="row vh-100">
        <!-- Dashboard Column -->
        <div class="col-8 vh-100 overflow-auto">
            <!-- Column Heading -->
            <h1 class="display-6 py-2">Dashboard</h1> <!-- TODO: Should be separated from the scroll section -->
            <h3>Overview</h1>

            <div class="row">
                <div class="col-xxl-6 py-1">
                HELOO
                    <!-- Dashboard Charts -->
                    <!-- TODO: Figure out how to pass data into json and to data chart USE LIVEWIRE https://livewire.laravel.com -->
                </div>

                <div class="col-xxl-6 py-1">
                    HELOO
                    <!-- Table -->
                    <!-- TODO: https://rappasoft.com/docs/laravel-livewire-tables/v3/columns/anonymous_columns -->
                </div>
            </div>

        </div>


        <!-- Timeline Column -->
        <div class="col-4 border-start border-muted vh-100 overflow-auto">
            <!-- Column Heading -->
            <h3>Timeline</h3>

            <!-- Timeline Start -->
            <!-- TODO: Add looping logic for each event in timeline -->

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
            
        </div>

    </div>


</div>

@endsection