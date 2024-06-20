@extends('admin-layout')
@section('title', 'Admin | Discussion')@section('title', 'Admin | Discussion')
@section('content')

<!-- Include the TinyMCE configuration component -->
<x-head.tinymce-config />

<div class="container-fluid">
    <div class="card mt-2">
        <div class="card-body">
            <h5>
                @if($post->is_anonymous)
                    <img src="{{ asset('storage/profile_pictures/anonymous.png') }}" alt="Profile Picture" class="img-fluid rounded-circle profile-picture-small">
                    @if(Auth::id() == $post->UserID)
                        Your Friendly Colleague (You)
                    @else
                        Your Friendly Colleague
                    @endif
                @elseif(Auth::id() == $post->UserID)
                    <img src="{{ Storage::url($profile->profile_picture) }}" alt="Profile Picture" class="img-fluid rounded-circle profile-picture-small">
                    You
                @else
                    <img src="{{ Storage::url($profile->profile_picture) }}" alt="Profile Picture" class="img-fluid rounded-circle profile-picture-small">
                    {{ $user->name }}
                @endif
            </h5>
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h5 class="card-title">{{ $post->title }}</h5>
                    <p class="card-text">{!! $post->content !!}</p>
                    <div class="text-muted mt-2">
                        <span>Posted at: {{ $post->created_at->format('M d, Y') }}</span><br>
                        @if(!is_null($post->deleted_at))
                            <span>Deleted at: {{ $post->deleted_at ? $post->deleted_at->format('M d, Y') : '-' }}</span>
                        @endif
                    </div>
                </div>
                <div class="dropdown" style="padding-right: 20px;">
                    <button class="btn btn-link text-dark" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                        @if(is_null($post->deleted_at))
                            <li><a class="dropdown-item text-dark" href="{{ route('admin.editPost', ['PostID' => $post->PostID]) }}"><i class="fas fa-edit"></i> Edit</a></li>
                            <li><a class="dropdown-item text-dark" href="#" onclick="confirmDeletePost('{{ $post->PostID }}', '{{ Auth::id() == $post->UserID ? 'You' : $user->name }}')"><i class="fas fa-trash-alt"></i> Delete</a></li>
                        @endif
                        <li><a class="dropdown-item text-dark" href="{{ route('admin.viewHistory', ['PostID' => $post->PostID]) }}"><i class="fas fa-history"></i> View History</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Display answers related to the post -->
    @if($answers->isNotEmpty())
        <div class="row mt-4">
            <div class="col-12">
                <h3 style="margin-bottom:20px;">Answers</h3>
                @foreach($answers as $answer)
                    <div class="card card-post-details mb-3 position-relative">
                        <div class="card-body d-flex justify-content-between align-items-start">
                            <div>
                                @if($answer->deleted_at)
                                    <div class="badge bg-secondary deleted-badge">
                                        <i class="fas fa-exclamation-circle"></i> Deleted
                                    </div>
                                @endif
                                <p class="card-text">
                                    <strong>
                                        @if($answer->is_anonymous)
                                            <img src="{{ asset('storage/profile_pictures/anonymous.png') }}" alt="Profile Picture" class="img-fluid rounded-circle profile-picture-small">
                                            @if($answer->UserID == Auth::id())
                                                Your Friendly Colleague (You)
                                            @else
                                                Your Friendly Colleague
                                            @endif
                                        @elseif($answer->UserID == Auth::id())
                                            <img src="{{ Storage::url($profile->profile_picture) }}" alt="Profile Picture" class="img-fluid rounded-circle profile-picture-small">
                                            You
                                        @else
                                            <img src="{{ Storage::url($profile->profile_picture) }}" alt="Profile Picture" class="img-fluid rounded-circle profile-picture-small">
                                            {{ $users[$answer->UserID] ?? 'Unknown' }}
                                        @endif
                                    </strong>
                                </p>
                                <p class="card-text">{!! $answer->content !!}</p>
                                <div class="text-muted mt-2">
                                    <span>Posted at: {{ $answer->created_at->format('M d, Y') }}</span><br>
                                    @if(!is_null($answer->deleted_at))
                                        <span>Deleted at: {{ $answer->deleted_at ? $answer->deleted_at->format('M d, Y') : '-' }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="dropdown" style="padding-right: 20px;">
                                <button class="btn btn-link text-dark" type="button" id="dropdownMenuButton{{ $answer->AnswerID }}" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton{{ $answer->AnswerID }}">
                                    @if(is_null($answer->deleted_at))
                                        <li><a class="dropdown-item text-dark" href="{{ route('admin.editAnswer', ['AnswerID' => $answer->AnswerID]) }}"><i class="fas fa-edit"></i> Edit</a></li>
                                        <li><a class="dropdown-item text-dark" href="#" onclick="confirmDeleteAnswer('{{ $answer->AnswerID }}', '{{ $users[$answer->UserID] ?? 'Unknown' }}')"><i class="fas fa-trash-alt"></i> Delete</a></li>
                                        <li><a class="dropdown-item text-dark" href="{{ route('admin.viewAnswerHistory', ['AnswerID' => $answer->AnswerID]) }}"><i class="fas fa-history"></i> View History</a></li>
                                    @else
                                        <li><a class="dropdown-item text-dark" href="{{ route('admin.viewAnswerHistory', ['AnswerID' => $answer->AnswerID]) }}"><i class="fas fa-history"></i> View History</a></li>
                                    @endif
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
    <hr>
    <!-- Form for submitting answers -->
    @if(is_null($post->deleted_at))
        <div class="row mt-4">
            <div class="col-12">
                <form action="{{ route('admin.submitAnswer', ['PostID' => $post->PostID]) }}" method="POST">
                    @csrf
                    <div class="form-group">
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

<!-- Modal for post delete confirmation -->
<div class="modal fade" id="deletePostConfirmationModal" tabindex="-1" aria-labelledby="deletePostConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deletePostConfirmationModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this question asked by <span id="postUserName"></span>? This action is not reversible and no edits can be made to the post after deletion.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmDeletePostButton">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for answer delete confirmation -->
<div class="modal fade" id="deleteAnswerConfirmationModal" tabindex="-1" aria-labelledby="deleteAnswerConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAnswerConfirmationModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this answer provided by <span id="answerUserName"></span>? This action is not reversible and no edits can be made to the answer after deletion.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmDeleteAnswerButton">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
    let postIdToDelete = '';
    let answerIdToDelete = '';

    function confirmDeletePost(postId, userName) {
        postIdToDelete = postId;
        document.getElementById('postUserName').textContent = userName;
        var deletePostConfirmationModal = new bootstrap.Modal(document.getElementById('deletePostConfirmationModal'), {
            keyboard: false
        });
        deletePostConfirmationModal.show();
    }

    document.getElementById('confirmDeletePostButton').addEventListener('click', function() {
        window.location.href = '/admin/discussion/delete-post/' + postIdToDelete;
    });

    function confirmDeleteAnswer(answerId, userName) {
        answerIdToDelete = answerId;
        document.getElementById('answerUserName').textContent = userName;
        var deleteAnswerConfirmationModal = new bootstrap.Modal(document.getElementById('deleteAnswerConfirmationModal'), {
            keyboard: false
        });
        deleteAnswerConfirmationModal.show();
    }

    document.getElementById('confirmDeleteAnswerButton').addEventListener('click', function() {
        window.location.href = '/admin/discussion/delete-answer/' + answerIdToDelete;
    });
</script>

@endsection
