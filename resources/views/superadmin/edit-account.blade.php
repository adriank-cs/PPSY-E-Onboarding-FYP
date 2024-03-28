@extends('admin-layout')

@section('content')

<div class="container-fluid">

    <div class="card">

        <div class="card-body">

            <form action="{{route('superadmin.edit_account.post', $user->id)}}" method="POST" enctype="multipart/form-data">
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
                            <label for="profilePicture" class="form-label">Profile Picture:</label>
                            <input type="file" class="form-control" id="profilePicture" name="profilePicture">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">


                        <div class="mb-3">
                            <label for="employeeid" class="form-label">Employee ID:</label>
                            <input type="text" class="form-control" id="employeeid" name="employeeID"
                                placeholder="Employee ID" value="{{ $profile->employee_id }}">
                        </div>

                        <div class="mb-3">
                            <label for="department" class="form-label">Department:</label>
                            <input type="text" class="form-control" id="department" name="dept"
                                placeholder="Enter department" value="{{ $profile->dept }}">
                        </div>


                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone No:</label>
                            <input type="tel" class="form-control" id="phone" name="phoneNo"
                                placeholder="Enter phone number" value="{{ $profile->phone_no }}">
                        </div>


                        <div class="mb-3">
                            <label for="dob" class="form-label">Date of Birth:</label>
                            <input type="date" class="form-control" name="dob" id="dob" value="{{ $profile->dob }}">
                        </div>

                        <div class="mb-3">
                            <label for="gender" class="form-label">Gender:</label>
                            <select class="form-select" id="gender" name="gender">
                                <option value="{{ $profile->gender }}" selected>{{ $profile->gender }}</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address:</label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="Enter email address" value="{{ $user->email }}">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password:</label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Enter password">
                        </div>

                        <div class="mb-3">
                            <label for="mailingAddress" class="form-label">Mailing Address:</label>
                            <textarea class="form-control" id="mailingAddress" name="address" rows="3"
                                placeholder="Enter mailing address">{{ $profile->address }} </textarea>
                        </div>
                    </div>

                    <div class="col-md-6">

                        <div class="mb-3">
                            <label for="name" class="form-label">Name: </label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="{{ $profile->name }}">
                        </div>

                        <div class="mb-3">
                            <label for="position" class="form-label">Position:</label>
                            <input type="text" class="form-control" id="position" name="position"
                                placeholder="Enter position" value="{{ $profile->position }}">
                        </div>

                        <div class="mb-3">
                            <label for="age" class="form-label">Age:</label>
                            <input type="number" class="form-control" id="age" name="age" placeholder="Age" value="{{ $profile->age }}">
                        </div>

                        <div class="mb-3">
                            <label for="biography" class="form-label">Biography:</label>
                            <textarea class="form-control" id="biography" name="bio" rows="10"
                                placeholder="Enter biography">{{ $profile->bio }}</textarea>
                        </div>

                        <div class="form-check">
                            <label class="form-check-label custom-label" for="isAdmin">
                                Admin Status
                            </label>
                            <input class="form-check-input" type="checkbox" value="1" id="isAdmin" name="isAdmin" {{ $user->companyUser->isAdmin ? 'checked' : '' }}>

                        </div>


                    </div>
                </div>
                <button type="submit" class="btn btn-primary float-end">Update</button>
            </form>

        </div>
    </div>
</div>

@endsection