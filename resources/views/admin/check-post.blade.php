@extends('admin-layout')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h3>Discussion</h3>
                <div>
                    <a href="{{ route('admin.check-post', ['filter' => 'all_posts']) }}" class="btn {{ request('filter') == 'my_questions' ? 'btn-secondary' : 'btn-primary' }}">All Posts</a>
                    <a href="{{ route('admin.check-post', ['filter' => 'my_questions']) }}" class="btn {{ request('filter') == 'my_questions' ? 'btn-primary' : 'btn-secondary' }}">My Questions</a>
                </div>
            </div>
        </div>
    </div>

    <div id="posts-container">
        @foreach($postedQuestions as $post)
            <div class="card mb-3 position-relative">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <a href="{{ route('admin.postDisplay', ['PostID' => $post->PostID]) }}" class="text-decoration-none text-dark">
                            @if($post->deleted_at)
                                <div class="badge bg-secondary deleted-badge">
                                    <i class="fas fa-exclamation-circle"></i> Deleted
                                </div>
                            @endif
                            <h5 class="card-title">{{ $post->title }}</h5>
                            <p class="card-text">Asked by: {{ Auth::id() == $post->UserID ? 'You' : $users[$post->UserID] }}</p>
                            <p class="card-text">Number of answers: {{ $post->answers_count }}</p>
                        </a>
                    </div>
                    <div class="d-flex flex-column align-items-end">
                        <div class="dropdown mb-2">
                            <button class="btn btn-link text-dark" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                @if(is_null($post->deleted_at))
                                    <li><a class="dropdown-item text-dark" href="{{ route('admin.editPost', ['PostID' => $post->PostID]) }}"><i class="fas fa-edit"></i> Edit</a></li>
                                    <li><a class="dropdown-item text-dark" href="#" onclick="confirmDelete('{{ $post->PostID }}', '{{ Auth::id() == $post->UserID ? 'You' : $users[$post->UserID] }}')"><i class="fas fa-trash-alt"></i> Delete</a></li>
                                @endif
                                <li><a class="dropdown-item text-dark" href="{{ route('admin.viewHistory', ['PostID' => $post->PostID]) }}"><i class="fas fa-history"></i> View History</a></li>
                            </ul>
                        </div>
                        <table class="table table-borderless mb-0 text-start small-text" style="margin: 0;">
                            <tr>
                                <td class="text-muted text-end pe-2" style="padding: 0;">Created At:</td>
                                <td style="padding: 0;">{{ $post->created_at->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted text-end pe-2" style="padding: 0;">Deleted At:</td>
                                <td style="padding: 0;">{{ $post->deleted_at ? $post->deleted_at->format('M d, Y') : '-' }}</td>
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

<script>
function confirmDelete(postId, userName) {
    if (confirm(`Are you sure you want to delete this question asked by ${userName}? This action is not reversible and no edits can be made to the post after deletion.`)) {
        window.location.href = '/admin/discussion/delete-post/' + postId;
    }
}
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

    .position-relative {
        position: relative;
    }
</style>
@endpush
