@extends('admin-layout')

@section('content')

<div class="container-fluid">

    <div class="card">

        <div class="card-body">

<div style="padding-bottom: 2rem;"><h1>Create New Module</h1>
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

<form action="{{ route('admin.add_module.post') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="title" class="form-label">Module Title:</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label for="image" class="form-label">Module Image:</label>
                <input type="file" class="form-control" id="image" name="image" required>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary float-end">Add Module</button>
</form>
@endsection


