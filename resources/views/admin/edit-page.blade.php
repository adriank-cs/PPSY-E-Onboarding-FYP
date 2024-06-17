@extends('admin-layout')

@section('content')


<div class="container-fluid">

    <!-- <div class="card"> -->

    <!-- <div class="card-body"> -->
    <x-head.tinymce-config />

    <form action="{{ route('admin.edit_page.post', $page->id) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @if($errors->any())
            <div class="col-12">
                @foreach($errors->all() as $error)
                    <div class="alert alert-danger" role="alert">
                        {{ $error }}
                    </div>
                @endforeach
            </div>
        @endif

        @if(session()->has('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @if(session()->has('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif


        <div class="row">
            <div class="col-md-8">
                <div class="page-title-container">
                    <h5><label for="title" class="form-label page-title">Page Title:</label></h5>
                </div>
                <div class="page-content">
                    <input type="text" class="form-control" id="title" name="title" value="{{ $page->title }}"
                        required>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">

                <div class="page-title-container">
                    <h5><label for="description" class="form-label page-title">Page Description:</label></h5>
                </div>
                <div class="page-content">
                    <textarea class="tinymce form-control" id="description" name="description" rows="3"
                        placeholder="Enter description">{{ $page->description }}</textarea>
                </div>

            </div>

        </div>

        <div class="row">
            <div class="col-md-8">

                <div class="page-title-container">
                    <h5><label for="content" class="form-label page-title">Page Content:</label></h5>
                </div>
                <div class="page-content">
                <h5><label for="pdf" class="form-label">Extract Content from PDF: (Optional)</label></h5>
                            <div class="row mb-4">
                                <div class="col">
                                    <div class="input-group">
                                        <input type="file" class="form-control" id="pdf" name="pdf"
                                            accept="application/pdf">
                                        <button type="button" class="btn btn-secondary" id="uploadPdfButton">Upload
                                            PDF</button>
                                    </div>
                                </div>
                            </div>
                    <textarea class="form-control tinymce" id="content" name="content" rows="10"
                        placeholder="Enter content">{{ $page->content }}</textarea>
                </div>

            </div>
        </div>


        <div class="row">
            <div class="col-md-8">
                <div class="page-title-container">
                    <h5><label for="pdfAttachments" class="form-label page-title">PDF Attachments:</label></h5>
                </div>
                <div class="page-content" id="pdfAttachmentsContainer">
                    <!-- Existing PDF Attachments -->
                    @if(!empty($pdfAttachments))
                        @foreach($pdfAttachments as $index => $pdf)
                            <div class="existing-pdf-attachment d-flex align-items-center mb-2">
                                <span>{{ $pdf['name'] }}</span>
                                <button type="button" class="btn btn-danger ms-2 remove-existing-attachment-btn" data-url="{{ $pdf['url'] }}">Remove</button>
                            </div>
                        @endforeach
                    @endif
                    <!-- Add new PDF Attachments -->
                    <div class="pdf-attachment d-flex align-items-center mb-2">
                        <input type="file" class="form-control pdf-attachment-input" name="pdfAttachments[]" accept="application/pdf">
                        <button type="button" class="btn btn-danger ms-2 remove-attachment-btn">Remove</button>
                    </div>
                    <button type="button" class="btn btn-secondary mt-2" id="addAttachmentButton">Add another file</button>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <button type="submit" class="btn btn-primary float-end marg-btm-cus">Update</button>
        </div>
    </form>

    <!-- </div> -->
    <!-- </div> -->
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirm Overwrite</h5>
            </div>
            <div class="modal-body">
                Are you sure you would like to overwrite the content?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmUploadButton">Confirm</button>
            </div>
        </div>
    </div>
</div>

<script>
    function showErrorModal(message) {
        // Update the modal content
        document.getElementById('errorModalMessage').textContent = message;
        // Show the modal
        var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
        errorModal.show();
    }

    document.getElementById('uploadPdfButton').addEventListener('click', function () {
        var fileInput = document.getElementById('pdf');
        var file = fileInput.files[0];

        if (file) {
            // Show the confirmation modal
            var confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            confirmationModal.show();
        } else {
            showErrorModal('Please select a PDF file to upload');
        }
    });

    document.getElementById('confirmUploadButton').addEventListener('click', function () {
        var formData = new FormData();
        var fileInput = document.getElementById('pdf');
        var file = fileInput.files[0];

        if (file) {
            formData.append('pdf', file);

            fetch('{{ route('admin.upload_pdf') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('PDF uploaded');
                        tinymce.get('content').setContent(data.text);
                    } else {
                        showErrorModal('Failed to upload and parse PDF');
                    }

                    // Hide the confirmation modal
                    var confirmationModal = bootstrap.Modal.getInstance(document.getElementById(
                        'confirmationModal'));
                    confirmationModal.hide();
                })
                .catch(error => {
                    console.error('Error:', error);
                    showErrorModal('An error occurred while uploading the PDF');

                    // Hide the confirmation modal even if there was an error
                    var confirmationModal = bootstrap.Modal.getInstance(document.getElementById(
                        'confirmationModal'));
                    confirmationModal.hide();
                });
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('addAttachmentButton').addEventListener('click', function () {
            const container = document.getElementById('pdfAttachmentsContainer');
            const newAttachment = document.createElement('div');
            newAttachment.classList.add('pdf-attachment', 'd-flex', 'align-items-center', 'mb-2');
            newAttachment.innerHTML = `
                <input type="file" class="form-control pdf-attachment-input" name="pdfAttachments[]" accept="application/pdf">
                <button type="button" class="btn btn-danger ms-2 remove-attachment-btn">Remove</button>
            `;
            container.insertBefore(newAttachment, this);
        });

        document.getElementById('pdfAttachmentsContainer').addEventListener('click', function (event) {
            if (event.target.classList.contains('remove-attachment-btn')) {
                const attachmentDiv = event.target.closest('.pdf-attachment');
                attachmentDiv.remove();
            } else if (event.target.classList.contains('remove-existing-attachment-btn')) {
                const url = event.target.getAttribute('data-url');
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'removeExistingFiles[]';
                hiddenInput.value = url;
                document.getElementById('pdfAttachmentsContainer').appendChild(hiddenInput);

                event.target.closest('.existing-pdf-attachment').remove();
            }
        });
    });

</script>

@endsection
