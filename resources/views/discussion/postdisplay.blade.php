@extends('employee-layout')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <!-- Display the post details -->
            @if(isset($post))
            <div class="row">
                <div class="card-body">
                    <h5 class="card-title">Asked by: {{ $post->user->name }}</h5>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $post->title }}</h5>
                            <p class="card-text">{!! $post->content !!}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Display answers related to the post -->
            <div class="row">
                <div class="card-body">
                    <h3>Answers</h3>
                    @foreach($answers as $answer)
                        <div class="card mb-3">
                            <div class="card-body">
                            <p class="card-text">{{ $user->name }}</p>
                                <p class="card-text">{!! $answer->content !!}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Include the TinyMCE configuration component -->
            <x-head.tinymce-config/>

            <!-- Form for submitting answers -->
            <div class="row">
                <div class="card-body">
                    <form action="{{ route('discussion.submitAnswer', ['PostID' => $post->PostID]) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="answer">Your Answer</label>
                            <textarea id="content" name="answer" class="form-control" placeholder="Type your answer here" rows="5"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary float-end">Submit Answer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
