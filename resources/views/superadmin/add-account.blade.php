@extends('superadmin-layout')
@section('title', 'SuperAdmin | Manage Account')
@section('content')

<div class="container-fluid">

    <div class="card">

        <div class="card-body">

            <form action="{{route('superadmin.add_account.post')}}" method="POST" enctype="multipart/form-data">
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
                            <label for="email" class="form-label">Email Address:</label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="Enter email address">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password:</label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Enter password">
                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="mb-3">
                            <label for="name" class="form-label">Name: </label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                        </div>

                        <div class="mb-3">
                            <label for="company" class="form-label">Company:</label>
                            <select class="form-select" name="company">
                                <option value="" disabled selected>Select Company</option>
                                @foreach($companies as $company)
                                <option value="{{ $company->CompanyID }}">{{ $company->Name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>
                <button type="submit" class="btn btn-primary float-end">Add</button>
            </form>

        </div>
    </div>
</div>

@endsection