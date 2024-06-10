<!-- onboarding-quiz.blade.php -->

@extends('admin-layout')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Onboarding Modules Quiz</h1>
    <!-- made the create new quiz as button -->
    <a href="{{ route('quizzes.create') }}" class="btn btn-primary mb-4">Create New Quiz</a>
    <div class="row">
        @foreach ($quizzes as $quiz)
        <div class="col-md-5">
            <div class="card mb-3">
          
                <div class="card-body " >
                    
                    <h5 class="card-title">{{ $quiz->title }}</h5>

                </div>

                <div class="card-footer">
                  <a href="{{ route('quizzes.edit', $quiz->id) }}" class="btn btn-primary btn-sm">Edit Quiz</a>

                  <!-- Button to trigger deletion -->
                  <form action="{{ route('quizzes.delete', $quiz->id) }}" method="post" class="d-inline">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this quiz?')">Delete Quiz</button>
                  </form>
                </div>

            </div>
        </div>
        @endforeach
    </div>
</div>
</div>
@endsection
