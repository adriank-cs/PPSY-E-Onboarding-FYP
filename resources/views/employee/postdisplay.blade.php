@extends('employee-layout')

@section('content')

<!-- Include the TinyMCE configuration component -->
<x-head.tinymce-config/>

<div class="container-fluid">
    <div class="card mb-3">
        <div class="card-body">
            <h5>Asked by: {{ Auth::id() == $post->UserID ? 'You' : ($post->is_anonymous ? 'Your Friendly Colleague' : $user->name) }}</h5>
            <div class="card mt-2" style="padding: 20px;">
                <div class="card-body position-relative">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="card-title">{{ $post->title }}</h5>
                            <p class="card-text">{!! $post->content !!}</p>
                            <div class="text-muted mt-2">
                                <span>Created at: {{ $post->created_at->format('M d, Y') }}</span><br>
                                <span>Deleted at: {{ $post->deleted_at ? $post->deleted_at->format('M d, Y') : '-' }}</span>
                            </div>
                        </div>
                        <div class="dropdown" style="padding-right: 20px; padding-top: 10px;">
                            <button class="btn btn-link text-dark" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                @if($post->UserID == Auth::id() && is_null($post->deleted_at))
                                    <li><a class="dropdown-item text-dark" href="{{ route('employee.editPost', ['PostID' => $post->PostID]) }}"><i class="fas fa-edit"></i> Edit</a></li>
                                    <li><a class="dropdown-item text-dark" href="#" onclick="confirmDeletePost('{{ $post->PostID }}', '{{ Auth::id() == $post->UserID ? 'You' : ($post->is_anonymous ? 'Your Friendly Colleague' : $user->name) }}')"><i class="fas fa-trash-alt"></i> Delete</a></li>
                                @endif
                                <li><a class="dropdown-item text-dark" href="{{ route('employee.viewHistory', ['PostID' => $post->PostID]) }}"><i class="fas fa-history"></i> View History</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Display answers related to the post -->
    @if($answers->isNotEmpty())
    <div class="row mt-4">
        <div class="col-12">
            <h3>Answers</h3>
            @foreach($answers as $answer)
                <div class="card mb-3 position-relative">
                    <div class="card-body d-flex justify-content-between align-items-start">
                        <div>
                            <p class="card-text">
                                <strong>
                                    @if($answer->UserID == Auth::id())
                                        You
                                    @elseif($answer->is_anonymous)
                                        Your Friendly Colleague
                                    @else
                                        {{ $users[$answer->UserID] ?? 'Unknown' }}
                                    @endif
                                </strong>
                            </p>
                            <p class="card-text">{!! $answer->content !!}</p>
                            <div class="text-muted mt-2">
                                <span>Created at: {{ $answer->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                        <div class="dropdown" style="padding-right: 20px; padding-top: 10px;">
                            <button class="btn btn-link text-dark" type="button" id="dropdownMenuButton{{ $answer->AnswerID }}" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton{{ $answer->AnswerID }}">
                                @if($answer->UserID == Auth::id() && is_null($answer->deleted_at))
                                    <li><a class="dropdown-item text-dark" href="{{ route('employee.editAnswer', ['AnswerID' => $answer->AnswerID]) }}"><i class="fas fa-edit"></i> Edit</a></li>
                                    <li><a class="dropdown-item text-dark" href="#" onclick="confirmDeleteAnswer('{{ $answer->AnswerID }}', '{{ $users[$answer->UserID] ?? 'Unknown' }}')"><i class="fas fa-trash-alt"></i> Delete</a></li>
                                @endif
                                <li><a class="dropdown-item text-dark" href="{{ route('employee.viewAnswerHistory', ['AnswerID' => $answer->AnswerID]) }}"><i class="fas fa-history"></i> View History</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="row mt-4">
        <div class="col-12">
            <hr>
            <p class="text-muted text-center">No answers published</p>
            <hr>
        </div>
    </div>
    @endif

    <!-- Form for submitting answers -->
    @if(is_null($post->deleted_at))
    <div class="row mt-4">
        <div class="col-12">
            <form action="{{ route('employee.submitAnswer', ['PostID' => $post->PostID]) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="answer">Your Answer</label>
                    <textarea id="content" name="answer" class="form-control tinymce" placeholder="Type your answer here" rows="5"></textarea>
                </div>
                <!-- Checkbox for anonymity -->
                <div class="form-check mt-2">
                    <input type="checkbox" class="form-check-input" id="anonymous" name="is_anonymous">
                    <label class="form-check-label" for="anonymous">Tick if you want to be anonymous</label>
                </div>
                <button type="submit" class="btn btn-primary float-end mt-3 mb-4">Submit Answer</button>
            </form>
        </div>
    </div>
    @endif
</div>

<script>
function confirmDeletePost(postId, userName) {
    if (confirm(`Are you sure you want to delete this question asked by ${userName}? This action is not reversible and no edits can be made to the post after deletion.`)) {
        window.location.href = '/employee/discussion/delete-post/' + postId;
    }
}

function confirmDeleteAnswer(answerId, userName) {
    if (confirm(`Are you sure you want to delete this answer provided by ${userName}? This action is not reversible and no edits can be made to the answer after deletion.`)) {
        window.location.href = '/employee/discussion/delete-answer/' + answerId;
    }
}
</script>

@endsection

@push('styles')
<style>
    .deleted-badge {
        position: absolute;
        top: -10px;
        left: 10px;
        padding: 5px 10px;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        background-color: #d3d3d3; /* Grey background */
        margin-bottom: 10px; /* Add a small gap */
    }

    .deleted-badge i {
        margin-right: 5px;
    }
</style>
@endpush
