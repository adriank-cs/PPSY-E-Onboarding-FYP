@extends('employee-layout')

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
                        <h1> Discussion </h1>
                    </div>
                </div>
            </div>

            <!-- Include the TinyMCE configuration component -->
            <x-head.tinymce-config/>

            <!-- Entry form for creating a new post -->
            <br>
            <form id="createPostForm" action="{{ route('employee.createPost') }}" method="POST" enctype="multipart/form-data">
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
                <button type="submit" class="btn btn-primary float-end" onclick="validateAndSubmit(event)">Submit</button>
            </form>
        </div>
    </div>
</div>

<script>
    function validateAndSubmit(event) {
        event.preventDefault();
        const title = document.getElementById('title').value.trim();
        const content = tinymce.get('content').getContent().trim();
        
        if (title && content) {
            if (confirm('Are you sure you are done with creating the post?')) {
                document.getElementById('createPostForm').submit();
            }
        } else {
            alert('Please fill out both the title and content fields.');
        }
    }
</script>

@endsection
