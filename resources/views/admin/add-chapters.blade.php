@extends('admin-layout')
@section('title', 'Admin | Manage Chapter')
@section('content')

<div class="container-fluid">

    <div style="padding-bottom: 2rem;">
        <h1>Create New Chapter</h1>
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

    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif

    <form action="{{ route('admin.add_chapter.post', ['moduleId' => $moduleId]) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-md-8">
                <div class="page-title-container">
                    <h5><label for="title" class="form-label page-title">Chapter Title:</label></h5>
                </div>
                <div class="page-content">
                    <input type="text" class="form-control" id="title" name="title" placeholder="Enter Chapter Title" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="page-title-container">
                    <h5><label for="description" class="form-label page-title">Chapter Description:</label></h5>
                </div>
                <div class="page-content">
                    <textarea class="form-control" id="description" name="description" rows="10" placeholder="Enter description"></textarea>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary float-end marg-btm-cus">Add Chapter</button>
    </form>

</div>

@endsection
