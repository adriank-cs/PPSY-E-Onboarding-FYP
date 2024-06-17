@extends('superadmin-layout')
@section('content')

<head>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/5.0.0/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap JS and dependencies (Popper.js) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.0.0/js/bootstrap.min.js"></script>

    <style>
        #confirmed-logo-preview img {
            max-width: 500px; /* Set a fixed width */
            max-height: 200px; /* Set a fixed height */
            object-fit: contain; /* Ensure the image fits within the specified dimensions */
        }
    </style>
</head>
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h2>Create Company</h2>
            <form action="{{ route('superadmin.add_company.post') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Company Name:</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter company name"
                        required>
                </div>

                <div class="mb-3">
                    <label for="industry" class="form-label">Industry:</label>
                    <select class="form-select" id="industry" name="industry" required>
                        <option value="" selected disabled>Select Industry</option>
                        <option value="IT">Information Technology</option>
                        <option value="Finance">Finance</option>
                        <option value="Healthcare">Healthcare</option>
                        <option value="Education">Education</option>
                        <option value="Manufacturing">Manufacturing</option>
                        <option value="Retail">Retail</option>
                        <option value="Telecommunications">Telecommunications</option>
                        <option value="Transportation">Transportation</option>
                        <option value="Media and Entertainment">Media and Entertainment</option>
                        <option value="Hospitality">Hospitality</option>
                        <option value="Real Estate">Real Estate</option>
                        <option value="Construction">Construction</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Address:</label>
                    <textarea class="form-control" id="address" name="address" rows="3" placeholder="Enter company address"
                        required></textarea>
                </div>

                <div class="mb-3">
                    <label for="website" class="form-label">Website:</label>
                    <textarea class="form-control" id="website" name="website" rows="3" placeholder="Enter company website"
                        required></textarea>
                </div>

                <div class="mb-3">
                    <label for="logo" class="form-label">Company Logo:</label>
                    <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                    <div id="confirmed-logo-preview" class="mt-3" style="display: none;">
                        <img src="" alt="Confirmed Logo" id="confirmed-logo" class="img-fluid">
                    </div>
                </div>

                <!-- Modal for image preview -->
                <div class="modal fade" id="logoPreviewModal" tabindex="-1" aria-labelledby="logoPreviewModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="logoPreviewModalLabel">Logo Preview</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center">
                                <img id="logo-preview" src="" alt="Logo Preview" class="img-fluid">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-success" id="confirm-logo">Confirm</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label for="sidebar_color" class="form-label">Primary Color:</label>
                        <input type="text" class="form-control" id="sidebar_color" name="sidebar_color"
                            placeholder="Enter color hex code">
                    </div>

            <div class="col-md-6">
                <label for="button_color" class="form-label">Secondary Color:</label>
                <input type="text" class="form-control" id="button_color" name="button_color"
                    placeholder="Enter color hex code">
            </div>
        </div>
        <br>
        
        <!-- Added by Alda for Subscription -->
        <div class="mb-3">
            <label for="subscription_starts_at" class="form-label">Subscription Starts At:</label>
            <input type="date" class="form-control" id="subscription_starts_at" name="subscription_starts_at">
        </div>

        <div class="mb-3">
            <label for="subscription_ends_at" class="form-label">Subscription Ends At:</label>
            <input type="date" class="form-control" id="subscription_ends_at" name="subscription_ends_at">
        </div>

        
        <button type="submit" class="btn btn-primary">Add</button>
        </form>

    </div>
</div>

<script>
    document.getElementById('logo').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('logo-preview').src = e.target.result;
                var logoPreviewModal = new bootstrap.Modal(document.getElementById('logoPreviewModal'), {
                    keyboard: false
                });
                logoPreviewModal.show();
            };
            reader.readAsDataURL(file);
        }
    });

    document.getElementById('confirm-logo').addEventListener('click', function() {
        const logoSrc = document.getElementById('logo-preview').src;
        document.getElementById('confirmed-logo').src = logoSrc;
        document.getElementById('confirmed-logo-preview').style.display = 'block';
        var logoPreviewModal = bootstrap.Modal.getInstance(document.getElementById('logoPreviewModal'));
        logoPreviewModal.hide();
    });
</script>

@endsection
