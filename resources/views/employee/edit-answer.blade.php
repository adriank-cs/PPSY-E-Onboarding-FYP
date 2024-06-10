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
                <button type="submit" class="btn btn-primary float-end" onclick="validateAndSubmit(event)">Submit</button>
            </form>
        </div>
    </div>
</div>

<script>
    function validateAndSubmit(event) {
        event.preventDefault();
        const content = tinymce.get('content').getContent().trim();
        
        if (content) {
            if (confirm('Are you sure you are done with editing the answer?')) {
                document.getElementById('editAnswerForm').submit();
            }
        } else {
            alert('Please fill out the content field.');
        }
    }
</script>

@endsection
