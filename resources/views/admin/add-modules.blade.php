@extends('admin-layout')

@section('content')

<style>
    .modal-dialog {
        max-width: 100%;
        margin: 1rem;
    }

    .img-container {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 500px;
        background-color: #f7f7f7;
        overflow: hidden;
    }

</style>

<div class="container-fluid">

    <!-- <div class="card">

        <div class="card-body"> -->

    <div style="padding-bottom: 2rem;">
        <h1>Create New Module</h1>
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

    <form action="{{ route('admin.add_module.post') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-md-8">
                <div class="page-title-container">
                    <h5><label for="title" class="form-label page-title">Module Title:</label></h5>
                </div>
                <div class="page-content">
                    <input type="text" class="form-control" id="title" name="title" placeholder="Enter Module Title" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="page-title-container">
                    <h5><label for="image" class="form-label page-title">Module Image:</label></h5>
                </div>
                <div class="page-content">
                    <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                    <button type="button" class="btn btn-primary" id="clearImageButton">Clear Image</button>
                    <img id="cropped-image-preview" style="display: none; max-width: 100%; margin-top: 10px;">
                    <input type="hidden" id="croppedImage" name="croppedImage">
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary float-end marg-btm-cus">Add Module</button>
    </form>
</div>

<!-- Modal for cropping -->
<div class="modal fade" id="cropModal" tabindex="-1" role="dialog" aria-labelledby="cropModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cropModalLabel">Crop Image</h5>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <img id="image-preview" style="max-width: 100%;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="cancelButton" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="cropButton">Crop</button>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('image-preview');
        const croppedImagePreview = document.getElementById('cropped-image-preview');
        const cropModal = $('#cropModal');
        const croppedImageInput = document.getElementById('croppedImage');
        let cropper;

        imageInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            const reader = new FileReader();

            reader.onload = function (e) {
                imagePreview.src = e.target.result;
                cropModal.modal('show');
            };

            reader.readAsDataURL(file);
        });

        cropModal.on('shown.bs.modal', function () {
            if (cropper) {
                cropper.destroy();
            }

            cropper = new Cropper(imagePreview, {
                dragMode: 'none',
                aspectRatio: 470/ 250,
                autoCropArea: 1,
                restore: false,
                guides: false,
                center: false,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: false,
                toggleDragModeOnDblclick: false,
                scalable: true,
            });
        });

        cropModal.on('hidden.bs.modal', function () {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
        });

        document.getElementById('cropButton').addEventListener('click', function () {
            if (cropper) {
                cropper.getCroppedCanvas({
                    width: 470,
                    height: 250,
                }).toBlob(function (blob) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        croppedImagePreview.src = e.target.result;
                        croppedImagePreview.style.display = 'block';
                        croppedImageInput.value = e.target.result;
                    };
                    reader.readAsDataURL(blob);

                    cropModal.modal('hide');
                });
            }
        });

        document.getElementById('cancelButton').addEventListener('click', function () {
            if (cropper) {
                cropModal.modal('hide');
            }

            document.getElementById('image').value = '';

            // Reset the image preview
            imagePreview.src = '';
            croppedImagePreview.src = '';
            croppedImagePreview.style.display = 'none';

        });

        document.getElementById('clearImageButton').addEventListener('click', function () {
            // Clear the file input by setting its value to an empty string
            document.getElementById('image').value = '';

            // Reset the image preview
            imagePreview.src = '';
            croppedImagePreview.src = '';
            croppedImagePreview.style.display = 'none';

            // Destroy the cropper instance if exists
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }

        });

        
    });

</script>

@endsection
