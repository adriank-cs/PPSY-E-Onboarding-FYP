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
                <livewire:admin.admin-dashboard-table theme="bootstrap-5" />
            </div>

        </div>


        <!-- Timeline Column -->
        <div class="col-4 border-start border-muted vh-100 overflow-auto">
            <!-- Column Heading -->
            <h3>Timeline</h3>

            <!-- Timeline Start -->
            <livewire:admin.dashboard-timeline />

        </div>

    </div>


</div>

@endsection