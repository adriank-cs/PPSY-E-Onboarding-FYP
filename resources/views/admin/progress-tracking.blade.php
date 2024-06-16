@extends('admin-layout')

@section('title', 'Admin | Progress Tracking')
@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Column Heading -->
        <h1 class="display-6 py-2">Progress Tracking</h1>
        <h3>Overview</h3>
        <div class="row py-2">
            <!-- Table -->
            <livewire:admin.admin-progress-tracking-table theme="bootstrap-5" />
        </div>

    </div>


</div>

@endsection