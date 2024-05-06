<!-- onboarding-quiz.blade.php -->
@extends('employee-layout')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Onboarding Modules Quiz</h1>
    <a href="{{ route('quizzes.create') }}">Create New Quiz</a> <div class="row">
        @foreach ($quizzes as $quiz)
        <div class="col-md-5">
            <div class="card mb-3">
          
                <div class="card-body " >
                    
                    <h5 class="card-title">{{ $quiz->title }}</h5>

                </div>

                 <div class="card-footer">
                  <a href="{{ route('quizzes.show', $quiz->id) }}" class="btn btn-primary" style= "background-color: #6A1043; color:white;">View Quiz</a>
             <a href="{{ route('quizzes.view-responses', $quiz->id) }}" class="btn btn-primary" style= "background-color: #6A1043; color:white;">View Submitted Answer</a> 
                </div>

            </div>
        </div>
        @endforeach
    </div>
</div>
</div>
@endsection

