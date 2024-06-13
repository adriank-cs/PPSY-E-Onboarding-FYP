@extends('admin-layout')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Quiz List</h1>

    <div class="row">
        @foreach ($quizzes as $quiz)
            <div class="card mb-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">{{ $quiz->title }}</h5>
                    <a href="{{ route('admin.employee-list', $quiz->id) }}" class="btn btn-primary btn-sm">View Employees</a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
