@extends('superadmin-layout')

@section('content')

<div class="container-fluid">

    <div class="card">

        <div class="card-body">

        <form action="{{ route('superadmin.edit_company.post', ['id' => $company->CompanyID]) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Company Name:</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter company name" value="{{ $company->Name }}" required>
                </div>

                <div class="mb-3">
                    <label for="industry" class="form-label">Industry:</label>
                    <select class="form-select" id="industry" name="industry" required>
                        <option value="" disabled>Select Industry</option>

                        @foreach($industries as $industry)
                            <option value="{{ $industry }}" {{ $company->Industry == $industry ? 'selected' : '' }}>
                                {{ $industry }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Address:</label>
                    <textarea class="form-control" id="address" name="address" rows="3" placeholder="Enter company address" required>{{ $company->Address }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="website" class="form-label">Website:</label>
                    <textarea class="form-control" id="website" name="website" rows="3" placeholder="Enter company website" required>{{ $company->Website }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="logo" class="form-label">Company Logo:</label>
                    <input type="file" class="form-control" id="logo" name="logo" >
                    @if($company->company_logo)
                        <img src="{{ Storage::url($company->company_logo) }}" alt="CompanyLogo" style="max-width: 200px; margin-top: 10px;">
                    @endif
                    <!-- <img id="logoPreview" style="max-width: 200px; margin-top: 10px; display: none;"> -->
                </div>

                <!-- <div class="mb-3">
                    <label for="logo" class="form-label">Company Logo:</label>
                    <input type="file" class="form-control" id="logo" name="logo" accept="image/*" onchange="previewImage(event)">
                    @if($company->company_logo)
                        <img src="{{ Storage::url($company->company_logo) }}" alt="CompanyLogo" id="currentLogo" style="max-width: 200px; margin-top: 10px;" onclick="openModal('{{ Storage::url($company->company_logo) }}')">
                    @endif
                </div> -->

                <div class="row">
                    <div class="col-md-6">
                        <label for="sidebar_color" class="form-label">Primary Color:</label>
                        <input type="text" class="form-control" id="sidebar_color" name="sidebar_color" placeholder="Enter color hex code"
                           value="{{ $company->button_color }}" >
                    </div>

                    <div class="col-md-6">
                        <label for="button_color" class="form-label">Secondary Color:</label>
                        <input type="text" class="form-control" id="button_color" name="button_color" placeholder="Enter color hex code"
                           value="{{ $company->sidebar_color }}" >
                    </div>
                </div>
                <br>

                <button type="submit" class="btn btn-primary">Update</button>
            </form>

        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="modalImage" src="" alt="Preview" style="width: 100%;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="selectImage">Select</button>
            </div>
        </div>
    </div>
</div>

<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<script>
    let cropper;
    let selectedFile;

    function previewImage(event) {
        const [file] = event.target.files;
        if (file) {
            selectedFile = file;
            const previewSrc = URL.createObjectURL(file);
            openModal(previewSrc);
        }
    }

    function openModal(src) {
        const modalImage = document.getElementById('modalImage');
        modalImage.src = src;
        const imageModal = new bootstrap.Modal(document.getElementById('imageModal'), {
            keyboard: false
        });
        imageModal.show();
        imageModal._element.addEventListener('shown.bs.modal', function () {
            cropper = new Cropper(modalImage, {
                aspectRatio: 1,
                viewMode: 3
            });
        });
        imageModal._element.addEventListener('hidden.bs.modal', function () {
            cropper.destroy();
        });
    }

    document.getElementById('selectImage').addEventListener('click', function () {
        const canvas = cropper.getCroppedCanvas();
        canvas.toBlob(function (blob) {
            const url = URL.createObjectURL(blob);
            const img = document.getElementById('currentLogo');
            img.src = url;
            img.style.display = 'block';

            // Create a new file input element to replace the old one
            const newFileInput = document.createElement('input');
            newFileInput.type = 'file';
            newFileInput.name = 'logo';
            newFileInput.files = new File([blob], selectedFile.name, {
                type: selectedFile.type,
                lastModified: Date.now()
            });
            newFileInput.style.display = 'none';

            // Replace the old file input with the new one
            const oldFileInput = document.getElementById('logo');
            oldFileInput.parentNode.replaceChild(newFileInput, oldFileInput);
            newFileInput.id = 'logo';

            // Close the modal
            const imageModal = bootstrap.Modal.getInstance(document.getElementById('imageModal'));
            imageModal.hide();
        });
    });
</script> -->


@endsection