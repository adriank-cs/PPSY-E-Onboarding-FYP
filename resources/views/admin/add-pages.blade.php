@extends('admin-layout')

@section('content')

<div class="container-fluid">

    <div class="card">

        <div class="card-body">

            <div style="padding-bottom: 2rem;">
                <h1>Create New Page</h1>
            </div>
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif

            <form
                action="{{ route('admin.add_page.post', ['chapterId' => $chapterId]) }}"
                method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="title" class="form-label">Page Title:</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="due_date" class="form-label">Due Date:</label>
                            <input type="date" class="form-control" name="due_date" id="due_date">
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="description" class="form-label">Page Description:</label>
                            <textarea class="form-control" id="description" name="description" rows="10"
                                placeholder="Enter description"></textarea>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="content" class="form-label">Page Content:</label>
                            <textarea class="form-control" id="content" name="content" rows="10"
                                placeholder="Enter content"></textarea>
                        </div>
                    </div>


                </div>


                <button type="submit" class="btn btn-primary float-end">Add Page</button>
            </form>
            @endsection
