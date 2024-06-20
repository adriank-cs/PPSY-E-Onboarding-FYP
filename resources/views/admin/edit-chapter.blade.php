@extends('admin-layout')

@section('content')

<div class="container-fluid">

    <div style="padding-bottom: 2rem;">
        <h1>Edit Chapter</h1>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form id="editChapterForm" action="{{ route('admin.edit_chapter.post', $chapter->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-md-8">
                <div class="page-title-container">
                    <h5><label for="title" class="form-label page-title">Chapter Title:</label></h5>
                </div>
                <div class="page-content">
                    <input type="text" class="form-control" id="title" name="title" value="{{ $chapter->title }}" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="page-title-container">
                    <h5><label for="description" class="form-label page-title">Chapter Description:</label></h5>
                </div>
                <div class="page-content">
                    <textarea class="form-control" id="description" name="description" rows="10" placeholder="Enter Description">{{ $chapter->description }}</textarea>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-primary float-end marg-btm-cus" data-bs-toggle="modal" data-bs-target="#confirmUpdateModal">Update</button>
    </form>

</div>

<!-- Modal for update confirmation -->
<div class="modal fade confirmation-modal" id="confirmUpdateModal" tabindex="-1" role="dialog" aria-labelledby="confirmUpdateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmUpdateModalLabel">Confirm Update</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to update the chapter?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmUpdateButton">Update</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('confirmUpdateButton').addEventListener('click', function () {
            document.getElementById('editChapterForm').submit();
        });
    });
</script>

@endsection
