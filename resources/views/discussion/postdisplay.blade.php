@extends('employee-layout')

@section('content')
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

            <!-- entry box and search button -->
            <!-- Your existing code for search box and buttons -->

            <!-- Existing Questions header -->
            <!-- Your existing code for existing questions -->

            <!-- Check if $post exists and contains valid data -->
            @if(isset($post))
            <div class="row">
                <div class="card-body">
                    <h5 class="card-title">Asked by: {{ $post->user->name }}</h5>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $post->title }}</h5>
                            <p class="card-text">{{ $post->content }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Display the created post -->
            @endif
            <!-- End Check if $post exists and contains valid data -->
        </div>

        <div class="card-body">
            <!-- Include the TinyMCE configuration component -->
            <x-head.tinymce-config/>

            <!-- Entry form for creating a new post -->
            <br>
            <form action="{{ route('discussion.createPost') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Content Field -->
                <div class="form-group editor-title" id="title-field">
                    <!-- Change "Title" to "Write your own answers" -->
                    <label for="title" style="font-size: 24px;">Write your own answers</label>
                    <textarea id="content" name="content" style="font-size: 18px;"></textarea>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary float-end">Submit</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<!-- Your existing styles -->
@endpush
