@extends('admin-layout')
@section('title', 'Admin | Create Post')
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
                        <h1>Discussion</h1>
                    </div>
                </div>
            </div>

            <!-- Include the TinyMCE configuration component -->
            <x-head.tinymce-config/>

            <!-- Entry form for creating a new post -->
            <br>
            <form id="createPostForm" action="{{ route('admin.createPost') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Title Field -->
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" class="form-control" placeholder="Enter title here, 100 characters max" maxlength="100" required>
                </div>

                <!-- Content Field -->
                <div class="form-group editor-content" id="content-fields">
                    <label for="content">Content</label>
                    <textarea id="content" name="content" class="form-control tinymce" placeholder="Enter content here, 1000 characters max" maxlength="1000" required></textarea>
                </div>

                <br>
                <!-- Checkbox for anonymity -->
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="anonymous" name="is_anonymous">
                    <label class="form-check-label" for="anonymous">Tick if you want to be anonymous</label>
                </div>

                <!-- Submit Button -->
                <button type="button" class="btn btn-primary float-end" onclick="validateAndSubmit()">Submit</button>
            </form>
        </div>
    </div>
</div>

<!-- Modal for validation message -->
<div class="modal fade" id="validationModal" tabindex="-1" aria-labelledby="validationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="validationModalLabel">Validation Error</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Please fill out both the title and content fields.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    function validateAndSubmit() {
        const title = document.getElementById('title').value.trim();
        const content = tinymce.get('content').getContent().trim();
        
        if (title && content) {
            document.getElementById('createPostForm').submit();
        } else {
            var validationModal = new bootstrap.Modal(document.getElementById('validationModal'), {
                keyboard: false
            });
            validationModal.show();
        }
    }
</script>

@endsection
