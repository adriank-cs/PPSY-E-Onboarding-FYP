@extends('employee-layout')
@section('title', 'Employee | Discussion')
@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <h3>Discussion</h3>
        </div>
    </div>

    <div id="posts-container">
        @foreach($postedQuestions as $post)
            <div class="card mb-3 position-relative discussion-card-shadow">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <a href="{{ route('employee.postDisplay', ['PostID' => $post->PostID]) }}" class="text-decoration-none text-dark">
                            <h5 class="card-title">{{ $post->title }}</h5>
                            <p class="card-text">
                                Asked by:
                                @if($post->is_anonymous)
                                    @if(Auth::id() == $post->UserID)
                                        Your Friendly Colleague (You)
                                    @else
                                        Your Friendly Colleague
                                    @endif
                                @else
                                    {{ Auth::id() == $post->UserID ? 'You' : $users[$post->UserID] ?? 'Unknown' }}
                                @endif
                            </p>
                            <p class="card-text">Number of answers: {{ $post->answers_count }}</p>
                        </a>
                    </div>
                    <div class="d-flex flex-column align-items-end">
                        <div class="dropdown mb-2">
                            <button class="btn btn-link text-dark" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                @if($post->UserID == Auth::id() && is_null($post->deleted_at))
                                    <li><a class="dropdown-item text-dark" href="{{ route('employee.editPost', ['PostID' => $post->PostID]) }}"><i class="fas fa-edit"></i> Edit</a></li>
                                    <li><a class="dropdown-item text-dark" href="#" onclick="confirmDelete('{{ $post->PostID }}', '{{ Auth::id() == $post->UserID ? 'You' : ($post->is_anonymous ? 'Your Friendly Colleague' : $users[$post->UserID] ?? 'Unknown') }}')"><i class="fas fa-trash-alt"></i> Delete</a></li>
                                @endif
                                <li><a class="dropdown-item text-dark" href="{{ route('employee.viewHistory', ['PostID' => $post->PostID]) }}"><i class="fas fa-history"></i> View History</a></li>
                            </ul>
                        </div>
                        <table class="table table-borderless mb-0 text-start small-text" style="margin: 0;">
                            <tr>
                                <td class="text-muted text-end pe-2" style="padding: 0;">Posted At:</td>
                                <td style="padding: 0;">{{ $post->created_at->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted text-end pe-2" style="padding: 0;">Edited At:</td>
                                <td style="padding: 0;">{{ $post->updated_at ? $post->updated_at->format('M d, Y') : '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Modal for delete confirmation -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this question? This action is not reversible and no edits can be made to the post after deletion.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteButton">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
    let postIdToDelete = '';

    function confirmDelete(postId) {
        postIdToDelete = postId;
        var deleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        deleteModal.show();
    }

    document.getElementById('confirmDeleteButton').addEventListener('click', function() {
        window.location.href = '/employee/discussion/delete-post/' + postIdToDelete;
    });
</script>

@endsection

@push('styles')
<style>
    .dropdown-placeholder {
        width: 24px;
        height: 24px;
    }

    .small-text td {
        font-size: 0.875rem; /* This makes the font size smaller */
    }

    .position-relative {
        position: relative;
    }
</style>
@endpush
