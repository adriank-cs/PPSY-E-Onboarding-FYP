@extends('admin-layout')
@section('title', 'Admin | Manage Discussion')
@section('content')

<style>
    .editor-title {
        font-size: 24px;
        margin-bottom: 20px;
    }
    .editor-content {
        margin-top: 20px;
    }
</style>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="text-left">
                <div class="row">
                    <div class="col-md-3">
                        <h1>Edit Post</h1>
                    </div>
                </div>
            </div>

            <!-- Include the TinyMCE configuration component -->
            <x-head.tinymce-config/>

            <!-- Entry form for editing the post -->
            <br>
            <form id="editPostForm" action="{{ route('admin.updatePost', ['PostID' => $post->PostID]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Title Field -->
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" class="form-control" placeholder="Enter title here, 100 characters max" maxlength="100" value="{{ $post->title }}" required>
                </div>

                <!-- Content Field -->
                <div class="form-group editor-content" id="content-fields">
                    <label for="content">Content</label>
                    <textarea id="content" name="content" class="form-control tinymce" placeholder="Enter content here, 1000 characters max" maxlength="1000" required>{{ $post->content }}</textarea>
                </div>

                <br>
                <!-- Submit Button -->
                <button type="button" class="btn btn-primary float-end" id="submitButton">Submit</button>
            </form>
        </div>
    </div>
</div>

<!-- Modal for update confirmation -->
<div class="modal fade" id="updateConfirmationModal" tabindex="-1" aria-labelledby="updateConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateConfirmationModalLabel">Confirm Update</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you are done with editing the post?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmUpdateButton">Confirm</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('submitButton').addEventListener('click', function() {
        const title = document.getElementById('title').value.trim();
        const content = tinymce.get('content').getContent().trim();
        
        if (title && content) {
            var updateConfirmationModal = new bootstrap.Modal(document.getElementById('updateConfirmationModal'), {
                keyboard: false
            });
            updateConfirmationModal.show();
        } else {
            alert('Please fill out both the title and content fields.');
        }
    });

    document.getElementById('confirmUpdateButton').addEventListener('click', function() {
        document.getElementById('editPostForm').submit();
    });
</script>

@endsection
