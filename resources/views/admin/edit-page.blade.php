@extends('admin-layout')

@section('content')

<div class="container-fluid">

    <div class="card">

        <div class="card-body">

            <form action="{{ route('admin.edit_page.post', $page->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @if($errors->any())
                    <div class="col-12">
                        @foreach($errors->all() as $error)
                            <div class="alert alert-danger" role="alert">
                                {{ $error }}
                            </div>
                        @endforeach
                    </div>
                @endif

                @if(session()->has('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                @if(session()->has('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="title" class="form-label">Page Title:</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ $page->title }}"
                                required>
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="due_date" class="form-label">Due Date:</label>
                            <!-- <input type="date" class="form-control" name="due_date" id="due_date"
                                value="{{ $page->due_date }}"> -->
                                <input type="date" class="form-control" name="due_date" id="due_date" value="{{ $page->due_date ? \Carbon\Carbon::parse($page->due_date)->format('Y-m-d') : '' }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="description" class="form-label">Page Description:</label>
                            <textarea class="form-control" id="description" name="description" rows="10"
                                placeholder="Enter description">{{ $page->description }}</textarea>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="content" class="form-label">Page Content:</label>
                            <textarea class="form-control" id="content" name="content" rows="10"
                                placeholder="Enter content">{{ $page->content }}</textarea>
                        </div>
                    </div>
                </div>




                <button type="submit" class="btn btn-primary float-end">Update</button>
            </form>

        </div>
    </div>
</div>

@endsection