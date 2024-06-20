@extends('employee-layout')
@section('title', 'Employee | Discussion')
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
                        <h1> Edit Post </h1>
                    </div>
                </div>
            </div>

            <!-- Include the TinyMCE configuration component -->
            <x-head.tinymce-config/>

            <!-- Entry form for editing the post -->
            <br>
            <form id="editPostForm" action="{{ route('employee.updatePost', ['PostID' => $post->PostID]) }}" method="POST" enctype="multipart/form-data">
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
                <button type="button" class="btn btn-primary float-end" onclick="showConfirmationModal()">Submit</button>
            </form>
        </div>
    </div>
</div>

<!-- Modal for confirmation message -->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirm Submission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you are done with editing the post?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmSubmitButton">Submit</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for alert message -->
<div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="alertModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="alertModalLabel">Alert</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Please fill out both the title and content fields.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<script>
    function showConfirmationModal() {
        const title = document.getElementById('title').value.trim();
        const content = tinymce.get('content').getContent().trim();
        
        if (title && content) {
            var confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'), {
                keyboard: false
            });
            confirmationModal.show();

            document.getElementById('confirmSubmitButton').addEventListener('click', function() {
                document.getElementById('editPostForm').submit();
            });
        } else {
            var alertModal = new bootstrap.Modal(document.getElementById('alertModal'), {
                keyboard: false
            });
            alertModal.show();
        }
    }
</script>

@endsection
