@extends('employee-layout')

@section('content')
<div class="container-fluid">
    <div class="row vh-100">
        <!-- Dashboard Column -->
        <div class="col-8 vh-100 overflow-auto">
            <!-- Column Heading -->
            <h1 class="display-6 py-2">Dashboard</h1>
            <h3>Overview</h1>

            <div class="row py-2">
                <!-- Dashboard Charts -->
                <livewire:employee.employee-charts/>
                <livewire:scripts />
                @livewireChartsScripts
            </div>
            <div class="row py-5">
                <h3>Upcoming Events</h1>

                @livewireCalendarScripts
                <!-- Calendar -->
                <script src="https://cdn.tailwindcss.com"></script>

                <script>
                    tailwind.config = {
                        corePlugins: {
                            preflight: false,
                        },
                    }
                </script>

                <div class="py-3">
                    <livewire:employee.reminder-calendar 
                        :drag-and-drop-enabled="false"
                        calendar-view="vendor.livewire-calendar.calendar"
                        day-view="vendor.livewire-calendar.day"
                        
                    />
                </div>
            </div>

        </div>


        <!-- Timeline Column -->
        <div class="col-4 border-start border-muted vh-100 overflow-auto">
            <!-- Column Heading -->
            <h3>Timeline</h3>

            <!-- Timeline Start -->
            <livewire:employee.dashboard-timeline />
            
        </div>

    </div>


</div>

@endsection