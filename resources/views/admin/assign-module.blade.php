@extends('admin-layout')

@section('content')

<div class="container-fluid">


    <div style="padding-bottom: 2rem;">
        <h1>Assign Module</h1>
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

    <x-head.tinymce-config />

    <form
        action="{{ route('admin.assign_module.post') }}"
        method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-md-8">
                <div class="page-title-container">
                    <h5><label for="title" class="form-label page-title">Select User:</label></h5>
                </div>
                <div class="page-content">
                <select class="form-select" name="user">
                    <option value="" disabled selected>Select User</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="page-title-container">
                    <h5><label for="due_date" class="form-label page-title">Due Date:</label></h5>
                </div>
                <div class="page-content">
                <input type="date" class="form-control" name="due_date" id="due_date">
                <input type="hidden" name="module_id" value="{{ $moduleId }}">
                </div>
            </div>
        </div>


        <button type="submit" class="btn btn-primary float-end marg-btm-cus">Assign</button>
    </form>
</div>
@endsection
