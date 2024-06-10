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

    <form action="{{ route('admin.edit_chapter.post', $chapter->id) }}" method="POST" enctype="multipart/form-data">
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

        <button type="submit" class="btn btn-primary float-end marg-btm-cus">Update</button>
    </form>

</div>

@endsection
