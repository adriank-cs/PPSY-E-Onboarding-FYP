@extends('admin-layout')

@section('content')

<div class="container-fluid">


    <div style="padding-bottom: 2rem;">
        <h1>Unassign Module</h1>
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
        action="{{ route('admin.unassign_module.post') }}"
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

        <input type="hidden" name="module_id" value="{{ $moduleId }}">


        <button type="submit" class="btn btn-primary float-end marg-btm-cus">Unassign</button>
    </form>
</div>
@endsection
