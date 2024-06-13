<!-- view employee name for answer the quiz -->
@extends('admin-layout')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Answered "{{ $quiz->title }}"</h1>

    <div class="row">
        @foreach ($employees as $employee)
            <div class="card mb-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <h5 class="card-title">{{ $employee->name }}</h5>
                    <a href="{{ route('admin.view-quiz-answer', ['quiz' => $quiz->id, 'employee' => $employee->id]) }}" class="btn btn-primary btn-sm">View Answers</a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
