@extends('admin-layout')
@section('title', 'Admin | Profile Page')
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="d-flex justify-content-end">
                        <img src="{{ $user->profile->profilePictureUrl }}" alt="Employee Photo" class="profile-photo">
                    </div>
                </div>
                <div class="col-md-6" style="margin-top:5%">
                    <h2>Hello, {{ $admin->name }}</h2>
                </div>
            </div>

            <form id="updateProfileForm" method="post" action="{{ route('admin.update-profile') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <fieldset disabled>
                            <div class="mb-3">
                                <label for="employeeid" class="form-label">Employee ID:</label>
                                <input type="text" class="form-control" id="employeeid" placeholder="Employee ID"
                                    value="{{ $admin->employee_id }}">
                            </div>
                        </fieldset>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone No:</label>
                            <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter phone number"
                                value="{{ $admin->phone_no }}">
                        </div>

                        <div class="mb-3">
                            <label for="dob" class="form-label">Date of Birth:</label>
                            <input type="date" class="form-control" id="dob" name="dob" value="{{ $admin->dob }}">
                        </div>

                        <div class="mb-3">
                            <label for="gender" class="form-label">Gender:</label>
                            <select class="form-select" id="gender" name="gender">
                                <option value="{{ $admin->gender }}" selected>{{ $admin->gender }}</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address:</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email address"
                                value="{{ $admin->user->email }}">
                        </div>

                        <div class="mb-3">
                            <label for="mailingAddress" class="form-label">Mailing Address:</label>
                            <textarea class="form-control" id="mailingAddress" name="address" rows="3"
                                placeholder="Enter mailing address">{{ $admin->address }}</textarea>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <fieldset disabled>
                            <div class="mb-3">
                                <label for="department" class="form-label">Department:</label>
                                <input type="text" class="form-control" id="department" placeholder="Enter department"
                                    value="{{ $admin->dept }}">
                            </div>
                        </fieldset>

                        <fieldset disabled>
                            <div class="mb-3">
                                <label for="position" class="form-label">Position:</label>
                                <input type="text" class="form-control" id="position" placeholder="Enter position"
                                    value="{{ $admin->position }}">
                            </div>
                        </fieldset>

                        <fieldset disabled>
                            <div class="mb-3">
                                <label for="age" class="form-label">Age:</label>
                                <input type="number" class="form-control" id="age" value="{{ $admin->age }}">
                            </div>
                        </fieldset>

                        <div class="mb-3">
                            <label for="biography" class="form-label">Biography:</label>
                            <textarea class="form-control" id="biography" name="bio" rows="10"
                                placeholder="Enter biography">{{ $admin->bio }}</textarea>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="user_id" value="{{ $admin->user_id }}">
                <button type="button" class="btn btn-primary float-end" id="saveButton">Save</button>
            </form>
        </div>
    </div>
</div>

<!-- Modal for update confirmation -->
<div class="modal fade" id="updateConfirmationModal" tabindex="-1" aria-labelledby="updateConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateConfirmationModalLabel">Confirm Update</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to update the profile information?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmUpdateButton">Confirm</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('saveButton').addEventListener('click', function() {
        var updateConfirmationModal = new bootstrap.Modal(document.getElementById('updateConfirmationModal'), {
            keyboard: false
        });
        updateConfirmationModal.show();
    });

    document.getElementById('confirmUpdateButton').addEventListener('click', function() {
        document.getElementById('updateProfileForm').submit();
    });
</script>

@endsection
