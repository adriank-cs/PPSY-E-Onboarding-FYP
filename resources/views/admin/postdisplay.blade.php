@extends('admin-layout')

@section('content')

<!-- Include the TinyMCE configuration component -->
<x-head.tinymce-config/>

<div class="container-fluid">
    <div class="card mb-3">
        <div class="card-body">
            <h5>Asked by: {{ Auth::id() == $post->UserID ? 'You' : $user->name }}</h5>
            <div class="card mt-2" style="padding: 20px;">
                <div class="card-body position-relative">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="card-title">{{ $post->title }}</h5>
                            <p class="card-text">{!! $post->content !!}</p>
                        </div>
                        <div class="dropdown" style="padding-right: 20px; padding-top: 10px;">
                            <button class="btn btn-link text-dark" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                @if(is_null($post->deleted_at))
                                    <li><a class="dropdown-item text-dark" href="{{ route('admin.editPost', ['PostID' => $post->PostID]) }}"><i class="fas fa-edit"></i> Edit</a></li>
                                    <li><a class="dropdown-item text-dark" href="#" onclick="confirmDelete('{{ $post->PostID }}', '{{ Auth::id() == $post->UserID ? 'You' : $user->name }}')"><i class="fas fa-trash-alt"></i> Delete</a></li>
                                @endif
                                <li><a class="dropdown-item text-dark" href="{{ route('admin.viewHistory', ['PostID' => $post->PostID]) }}"><i class="fas fa-history"></i> View History</a></li>
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
                <div class="card mb-3">
                    <div class="card-body">
                        <p class="card-text">
                            <strong>{{ $answer->is_anonymous ? 'Your Friendly Colleague' : ($users[$answer->UserID] ?? 'Unknown') }}</strong>
                        </p>
                        <p class="card-text">{!! $answer->content !!}</p>
                        <p class="text-muted">{{ $answer->created_at->format('F d, Y') }}</p>
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
            <form action="{{ route('admin.submitAnswer', ['PostID' => $post->PostID]) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="answer">Your Answer</label>
                    <textarea id="content" name="answer" class="form-control tinymce" placeholder="Type your answer here" rows="5"></textarea>
                </div>
                <button type="submit" class="btn btn-primary float-end">Submit Answer</button>
            </form>
        </div>
    </div>
    @endif
</div>

<script>
function confirmDelete(postId, userName) {
    if (confirm(`Are you sure you want to delete this question asked by ${userName}? This action is not reversible and no edits can be made to the post after deletion.`)) {
        window.location.href = '/admin/discussion/delete-post/' + postId;
    }
}
</script>

@endsection

