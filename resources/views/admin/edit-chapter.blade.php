@extends('admin-layout')

@section('content')

<div class="container-fluid">

    <div class="card">

        <div class="card-body">

            <form action="{{route('admin.edit_chapter.post', $chapter->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                @if($errors->any())
                <div class="col-12">
                    @foreach($errors->all() as $error)
                    <div class="alert alert-danger" role="alert">
                        {{$error}}
                    </div>
                    @endforeach
                </div>
                @endif

                @if(session()->has('error'))
                <div class="alert alert-danger" role="alert">
                    {{session('error')}}
                </div>
                @endif

                @if(session()->has('success'))
                <div class="alert alert-success" role="alert">
                    {{session('success')}}
                </div>
                @endif

                <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="title" class="form-label">Chapter Title:</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ $chapter->title }}" required>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label for="description" class="form-label">Chapter Description:</label>
                <textarea class="form-control" id="description" name="description" rows="10" placeholder="Enter Description">{{ $chapter->description }}</textarea>
            </div>
        </div>
    </div>


                    
                
                <button type="submit" class="btn btn-primary float-end">Update</button>
            </form>

        </div>
    </div>
</div>

@endsection