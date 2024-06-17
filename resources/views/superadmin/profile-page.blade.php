@extends('superadmin-layout')

@section('content')

<div class="container-fluid">
    <div class="card">
        <div class="card-body">

            <div class="row">
                
                <div class="col-md-6">
                    <div class="d-flex justify-content-end">
                        <img src="{{ $user->profile->profilePictureUrl }}" alt="Employee Photo"
                            class="profile-photo">
                    </div>
                </div>
                <div class="col-md-6" style="margin-top:5%">
                    <h2>Hello {{ $profile->name }}</h2>
                </div>

            </div>

            <form>

            <div class="row">
                    <div class="col-md-6">

                        <fieldset disabled>
                            <div class="mb-3">
                                <label for="employeeid" class="form-label">Employee ID:</label>
                                <input type="text" class="form-control" id="employeeid" placeholder="Employee ID"
                                    value="{{ $profile->employee_id}}">
                            </div>
                        </fieldset>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone No:</label>
                            <input type="tel" class="form-control" id="phone" placeholder="Enter phone number"
                                value="{{ $profile->phone_no }}">
                        </div>

                        <div class="mb-3">
                            <label for="dob" class="form-label">Date of Birth:</label>
                            <input type="date" class="form-control" id="dob" value="{{ $profile->dob }}">
                        </div>

                        <div class="mb-3">
                            <label for="gender" class="form-label">Gender:</label>
                            <select class="form-select" id="gender" name="gender">
                                <option value="{{ $profile->gender }}" selected>{{ $profile->gender }}</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address:</label>
                            <input type="email" class="form-control" id="email" placeholder="Enter email address"
                                value="{{ $profile->user->email }}">
                        </div>

                        <div class="mb-3">
                            <label for="mailingAddress" class="form-label">Mailing Address:</label>
                            <textarea class="form-control" id="mailingAddress" rows="3"
                                placeholder="Enter mailing address">{{ $profile->address }}</textarea>
                        </div>
                    </div>

                    <div class="col-md-6">

                        <fieldset disabled>
                            <div class="mb-3">
                                <label for="department" class="form-label">Department:</label>
                                <input type="text" class="form-control" id="department" placeholder="Enter department"
                                    value="{{ $profile->dept }}">
                            </div>
                        </fieldset>

                        <fieldset disabled>
                            <div class="mb-3">
                                <label for="position" class="form-label">Position:</label>
                                <input type="text" class="form-control" id="position" placeholder="Enter position"
                                    value="{{ $profile->position }}">
                            </div>
                        </fieldset>

                        <fieldset disabled>
                            <div class="mb-3">
                                <label for="age" class="form-label">Age:</label>
                                <input type="number" class="form-control" id="age" value="{{ $profile->age }}">
                            </div>
                        </fieldset>

                        <div class="mb-3">
                            <label for="biography" class="form-label">Biography:</label>
                            <textarea class="form-control" id="biography" rows="10"
                                placeholder="Enter biography">{{ $profile->bio }}</textarea>
                        </div>


                    </div>
                </div>
                <input type="hidden" name="user_id" value="{{ $profile->user_id }}">
                <button type="submit" class="btn btn-primary float-end">Save</button>

            </form>
        </div>
    </div>
</div>

@endsection