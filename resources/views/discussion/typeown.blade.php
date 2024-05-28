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
            <form action="{{ route('discussion.createPost') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Title Field -->
                <div class="form-group">
                    <label for="title">Title</label>
                    <textarea id="title" name="title" class="form-control" placeholder="Enter title here, 100 characters max" maxlength="100"></textarea>
                </div>

                <!-- Content Field -->
                <div class="form-group editor-content" id="content-fields">
                    <label for="content">Content</label>
                    <textarea id="content" name="content" class="form-control" placeholder="Enter content here, 1000 characters max" maxlength="1000"></textarea>
                </div>

                <br>
                <!-- Checkbox for anonymity -->
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="anonymous" name="anonymous">
                    <label class="form-check-label" for="anonymous">Tick if you want to be anonymous</label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary float-end">Submit</button>
            </form>
        </div>
    </div>
</div>

@endsection
