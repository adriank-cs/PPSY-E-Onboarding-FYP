@extends('employee-layout')

@section('content')

<style>
    .editor-title {
        font-size: 24px;
        margin-bottom: 20px;
    }
    .editor-content {
        margin-top: 20px;
    }
</style>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="text-left">
                <div class="row">
                    <div class="col-md-3">
                        <h1>Edit Answer</h1>
                    </div>
                </div>
            </div>

            <!-- Include the TinyMCE configuration component -->
            <x-head.tinymce-config/>

            <!-- Entry form for editing the answer -->
            <br>
            <form id="editAnswerForm" action="{{ route('employee.updateAnswer', ['AnswerID' => $answer->AnswerID]) }}" method="POST">
                @csrf
                <!-- Content Field -->
                <div class="form-group editor-content" id="content-fields">
                    <label for="content">Content</label>
                    <textarea id="content" name="content" class="form-control tinymce" placeholder="Enter content here, 1000 characters max" maxlength="1000" required>{{ $answer->content }}</textarea>
                </div>

                <br>
                <!-- Submit Button -->
                <button type="button" class="btn btn-primary float-end" onclick="showConfirmationModal()">Submit</button>
            </form>
        </div>
    </div>
</div>

<!-- Modal for confirmation message -->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirm Submission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you are done with editing the answer?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmSubmitButton">Submit</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for alert message -->
<div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="alertModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="alertModalLabel">Alert</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Please fill out the content field.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<script>
    function showConfirmationModal() {
        const content = tinymce.get('content').getContent().trim();
        
        if (content) {
            var confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'), {
                keyboard: false
            });
            confirmationModal.show();

            document.getElementById('confirmSubmitButton').addEventListener('click', function() {
                document.getElementById('editAnswerForm').submit();
            });
        } else {
            var alertModal = new bootstrap.Modal(document.getElementById('alertModal'), {
                keyboard: false
            });
            alertModal.show();
        }
    }
</script>

@endsection
