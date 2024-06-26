@extends('superadmin-layout')

@section('title', 'Superadmin | Dashboard')
@section('content')

<!-- AI Chatbot Script -->
<script src='{{ asset('js/chatbot-widget.js') }}'></script>
<!-- AI Chatbot Script End -->

<div class="container-fluid">
    <div class="row vh-100">
        <!-- Dashboard Column -->
        <div class="col-xl-8 vh-100 overflow-auto">
            <!-- Column Heading -->
            <h1 class="display-6 py-2">Dashboard</h1>
            <h3>Overview</h1>

            <div class="row py-2">
                <!-- Dashboard Charts -->
                <livewire:superadmin.super-admin-charts/>
                <livewire:scripts />
                @livewireChartsScripts
            </div>
            <div class="row py-5">
                <h3 class="pb-3">Company Subscriptions</h3>
                <!-- Table -->
                <livewire:superadmin.super-admin-dashboard-table theme="bootstrap-5" /> <!-- https://rappasoft.com/docs/laravel-livewire-tables/v3/introduction -->
            </div>

        </div>


        <!-- Timeline Column -->
        <div class="col-xl-4 border-xl-start border-muted vh-100 overflow-auto">
            <!-- Column Heading -->
            <h3>Timeline</h3>

            <!-- Timeline Start -->
            <livewire:superadmin.dashboard-timeline />
            
        </div>

    </div>


</div>

@endsection