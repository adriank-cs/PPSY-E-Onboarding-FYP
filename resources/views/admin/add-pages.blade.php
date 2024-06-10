@extends('admin-layout')

@section('content')

<div class="container-fluid">

    <!-- <div class="card">

        <div class="card-body"> -->

    <div style="padding-bottom: 2rem;">
        <h1>Create New Page</h1>
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
        action="{{ route('admin.add_page.post', ['chapterId' => $chapterId]) }}"
        method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-md-8">
                <div class="page-title-container">
                    <h5><label for="title" class="form-label page-title">Page Title:</label></h5>
                </div>
                <div class="page-content">
                    <input type="text" class="form-control" id="title" name="title" placeholder="Enter Page Title"
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
                    <textarea class="form-control tinymce" id="description" name="description" rows="10"
                        placeholder="Enter description"></textarea>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="page-title-container">
                    <h5><label for="pdf" class="form-label page-title">Upload PDF:</label></h5>
                </div>
                <div class="page-content">
                    <input type="file" class="form-control" id="pdf" name="pdf" accept="application/pdf">
                    <button type="button" class="btn btn-secondary" id="uploadPdfButton">Upload PDF</button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="page-title-container">
                    <h5><label for="content" class="form-label page-title">Page Content:</label></h5>
                </div>
                <div class="page-content">
                    <textarea class="form-control tinymce" id="content" name="content" rows="10"
                        placeholder="Enter content"></textarea>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="page-title-container">
                    <h5><label for="pdfAttachments" class="form-label page-title">PDF Attachments:</label></h5>
                </div>
                <div class="page-content" id="pdfAttachmentsContainer">
                    <div class="pdf-attachment d-flex align-items-center mb-2">
                        <input type="file" class="form-control pdf-attachment-input" name="pdfAttachments[]" accept="application/pdf">
                        <button type="button" class="btn btn-danger ms-2 remove-attachment-btn">Remove</button>
                    </div>
                    <button type="button" class="btn btn-secondary mt-2" id="addAttachmentButton">Add another file</button>
                </div>
            </div>
        </div>


        <button type="submit" class="btn btn-primary float-end marg-btm-cus">Add Page</button>
    </form>
</div>

<script>
    document.getElementById('uploadPdfButton').addEventListener('click', function () {
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
                        console.log('pdf uploaded');
                        tinymce.get('content').setContent(data.text);
                    } else {
                        alert('Failed to upload and parse PDF');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while uploading the PDF');
                });
        } else {
            alert('Please select a PDF file to upload');
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('addAttachmentButton').addEventListener('click', function() {
            const attachmentContainer = document.getElementById('pdfAttachmentsContainer');
            const attachmentDiv = document.createElement('div');
            attachmentDiv.classList.add('pdf-attachment', 'd-flex', 'align-items-center', 'mb-2');
            attachmentDiv.innerHTML = `
                <input type="file" class="form-control pdf-attachment-input" name="pdfAttachments[]" accept="application/pdf">
                <button type="button" class="btn btn-danger ms-2 remove-attachment-btn">Remove</button>
            `;
            attachmentContainer.insertBefore(attachmentDiv, this);

            // Add event listener to the remove button
            attachmentDiv.querySelector('.remove-attachment-btn').addEventListener('click', function() {
                attachmentDiv.remove();
            });
        });

        document.querySelectorAll('.remove-attachment-btn').forEach(button => {
            button.addEventListener('click', function() {
                this.closest('.pdf-attachment').remove();
            });
        });
    });

</script>
@endsection
