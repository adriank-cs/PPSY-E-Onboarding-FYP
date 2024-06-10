@extends('admin-layout')

@section('content')


<div class="container-fluid">

    <!-- <div class="card"> -->

    <!-- <div class="card-body"> -->
    <x-head.tinymce-config />

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
            <div class="col-md-8">
                <div class="page-title-container">
                    <h5><label for="title" class="form-label page-title">Page Title:</label></h5>
                </div>
                <div class="page-content">
                    <input type="text" class="form-control" id="title" name="title" value="{{ $page->title }}"
                        required>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">

                <div class="page-title-container">
                    <h5><label for="description" class="form-label page-title">Page Description:</label></h5>
                </div>
                <div class="page-content">
                    <textarea class="tinymce form-control" id="description" name="description" rows="3"
                        placeholder="Enter description">{{ $page->description }}</textarea>
                </div>

            </div>

        </div>



        <div class="row">
            <div class="col-md-8">

                <div class="page-title-container">
                    <h5><label for="content" class="form-label page-title">Page Content:</label></h5>
                </div>
                <div class="page-content">
                    <textarea class="form-control tinymce" id="content" name="content" rows="10"
                        placeholder="Enter content">{{ $page->content }}</textarea>
                </div>

            </div>
        </div>

        <div class="col-md-12">
            <button type="submit" class="btn btn-primary float-end marg-btm-cus">Update</button>
        </div>
    </form>

    <!-- </div> -->
    <!-- </div> -->
</div>

@endsection
