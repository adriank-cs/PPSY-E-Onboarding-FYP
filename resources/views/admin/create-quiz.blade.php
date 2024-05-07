<!-- create-modules.blade.php -->

@extends('employee-layout')

@section('content')
<div class="container-fluid">
<div style="padding-bottom: 2rem;"><h1>Create New Module</h1>
</div>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif

<form action="{{ route('modules.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label for="title" class="form-label">Module Title:</label>
        <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
    </div>

    <div class="mb-3">
        <label for="image" class="form-label">Module Image:</label>
        <input type="file" class="form-control" id="image" name="image" required>
    </div>

    <div class="mb-3">
        <label for="questions" class="form-label">Quiz Questions:</label>
        <div class="input-group">
<!--             <input type="text" class="form-control" id="questions" name="questions[]" placeholder="Enter question" required> -->
            <button type="button" class="btn btn-success" onclick="addQuestionField()">Add Question</button>
        </div>
        <div id="question-fields"></div>
    </div>

    <script>
        let questionCount = 1;

        function addQuestionField() {
            const questionField = `
                <div class="mb-3 input-group">
                    <input type="text" class="form-control" id="questions-${questionCount}" name="questions[]" placeholder="Enter question" required>
                    <button type="button" class="btn btn-danger" onclick="removeQuestionField(this)">Remove Question</button>
                    <label for="question_type-${questionCount}" class="ms-3">Question Type:</label>
                    <select class="form-control ms-2" id="question_type-${questionCount}" name="question_types[]">
                        <option value="multiple_choice">Multiple Choice</option>
                        <option value="structured">Structured</option>
                    </select>
                </div>`;
            questionCount++;
            document.getElementById("question-fields").innerHTML += questionField;
        }

        function removeQuestionField(button) {
            button.parentNode.remove();
        }
    </script>

    <div class="mb-3">
        <button type="submit" class="btn btn-primary">Create Module</button>
    </div>
</form>
@endsection


