<!-- onboarding-home-page.blade.php -->
@extends('employee-layout')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Onboarding Modules</h1>
    <a href="{{ route('modules.create') }}">Create New Module</a> <div class="row">
       
        @foreach ($modules as $module)
        <div class="col-md-6">
            <div class="card mb-4">
            
                <img src="{{ $module->image_url }}" class="card-img-top" alt="{{ $module->title }}">
                <!-- <img src="{{ asset('storage/app/modules/' . $module->image_path) }}" alt="{{ $module->title }}"> -->
                

                <div class="card-body">
                    <h5 class="card-title">{{ $module->title }}</h5>
                    <div class="progress-container">
                        <progress class="progress" value="{{ $module->completion_percentage }}" max="100"></progress>
                        <span class="progress-percentage">{{ $module->completion_percentage }}%</span>
                    </div>
                </div>

                 <div class="card-footer">
                  <a href="{{ route('modules.show', $module->id) }}" class="btn btn-primary">View Module</a>
                  
                 </div>

            </div>
        </div>
        @endforeach
    </div>
</div>
</div>
@endsection

