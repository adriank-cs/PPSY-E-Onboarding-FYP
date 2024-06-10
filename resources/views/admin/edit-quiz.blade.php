@extends('admin-layout')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('quizzes.update', $quiz->id) }}" method="POST">
                @csrf
                @method('PUT')

                @if($errors->any())
                    <div class="col-12">
                        @foreach($errors->all() as $error)
                            <div class="alert alert-danger" role="alert">
                                {{$error}}
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

                <div class="mb-3">
                    <label for="title" class="form-label" style="font-size: 15px; font-weight: bold;">Quiz Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ $quiz->title }}" required>
                </div>

                <div class="mb-3">
                    <label for="attempt_limit" class="form-label" style="font-size: 15px; font-weight: bold;">Attempt Limit:</label>
                    <input type="number" class="form-control" id="attempt_limit" name="attempt_limit" value="{{ $quiz->attempt_limit }}">
                </div>

                <div id="questions-container">
                    @foreach ($quiz->questions as $index => $question)
                        <div class="question-item mb-3">
                            <input type="hidden" name="questions[{{ $index }}][id]" value="{{ $question->id }}">
                            <div class="mb-3">
                                <label for="question_{{ $index }}" class="form-label" style="font-size: 15px; font-weight: bold;">Question</label>
                                <input type="text" class="form-control" id="question_{{ $index }}" name="questions[{{ $index }}][question]" value="{{ $question->question }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="type_{{ $index }}" class="form-label" style="font-size: 15px; font-weight: bold;">Type</label>
                                <select class="form-select type-select" id="type_{{ $index }}" name="questions[{{ $index }}][type]" required>
                                    <option value="multiple_choice" {{ $question->type === 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                                    <option value="short_answer" {{ $question->type === 'short_answer' ? 'selected' : '' }}>Short Answer</option>
                                    <option value="checkbox" {{ $question->type === 'checkbox' ? 'selected' : '' }}>Checkbox</option>
                                </select>
                            </div>

                            <div class="mb-3 answer-options" style="{{ in_array($question->type, ['multiple_choice', 'checkbox']) ? '' : 'display:none;' }}">
                                <label for="answer_options_{{ $index }}" class="form-label" style="font-size: 15px; font-weight: bold;">Answer Options</label>
                                <div id="options_{{ $index }}">
                                    @if(in_array($question->type, ['multiple_choice', 'checkbox']))
                                        @foreach(json_decode($question->answer_options) as $optionIndex => $option)
                                            <div class="input-group mb-3">
                                                @if($question->type === 'checkbox')
                                                    <input type="checkbox" class="form-check-input" disabled>
                                                @endif
                                                <input type="text" class="form-control consistent-width-input" name="questions[{{ $index }}][answer_options][]" value="{{ $option }}">
                                                <button class="btn btn-outline-danger delete-option" type="button">Delete</button>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <button type="button" class="btn btn-sm btn-primary add-option" data-index="{{ $index }}">Add Option</button>
                            </div>
                            <button type="button" class="btn btn-danger delete-question" data-question-id="{{ $question->id }}">Delete Question</button>
                        </div>
                    @endforeach
                </div>         

                <button type="button" class="btn btn-secondary" id="add-question">Add Question</button>
                <button type="submit" class="btn btn-primary float-end">Update Quiz</button>
            </form>
        </div>
    </div>
</div>

<style>
    .btn-success {
        background-color: #6A1043 !important;
        color: white !important;
        min-width: 120px;
        text-align: center;
    }

    .btn-danger {
        margin-right: 10px;
    }

    .consistent-width-input {
        width: calc(100% - 180px);
        max-width: 540px;
        display: inline-block;
    }

    .input-group .add-option-btn {
        margin-left: 10px;
        border-radius: 10px;
        height: 38px;
    }

    .form-check-input {
        transform: scale(1.1);
        margin-right: 10px;
        position: relative;
        border: 1px solid #b8bdc2;
    }

    .input-group {
        align-items: center;
    }
</style>

<script>
document.getElementById('add-question').addEventListener('click', function() {
    const questionsContainer = document.getElementById('questions-container');
    const questionCount = questionsContainer.childElementCount;

    const newQuestion = document.createElement('div');
    newQuestion.classList.add('question-item', 'mb-3');
    newQuestion.innerHTML = `
        <div class="mb-3">
            <label for="question_${questionCount}" class="form-label" style="font-size: 15px; font-weight: bold;">Question</label>
            <input type="text" class="form-control" id="question_${questionCount}" name="questions[${questionCount}][question]" required>
        </div>
        <div class="mb-3">
            <label for="type_${questionCount}" class="form-label" style="font-size: 15px; font-weight: bold;">Type</label>
            <select class="form-select type-select" id="type_${questionCount}" name="questions[${questionCount}][type]" required>
                <option value="short_answer" selected>Short Answer</option>
                <option value="multiple_choice">Multiple Choice</option>
                <option value="checkbox">Checkbox</option>
            </select>
        </div>
        <div class="mb-3 answer-options" style="display:none;">
            <label for="answer_options_${questionCount}" class="form-label" style="font-size: 15px; font-weight: bold;">Answer Options</label>
            <div id="options_${questionCount}"></div>
            <button type="button" class="btn btn-sm btn-primary add-option" data-index="${questionCount}">Add Option</button>
        </div>
        <button type="button" class="btn btn-danger delete-question">Delete Question</button>
    `;
    questionsContainer.appendChild(newQuestion);

    attachEventListeners(newQuestion);
});

function attachEventListeners(questionElement) {
    const typeSelect = questionElement.querySelector('.type-select');
    const answerOptions = questionElement.querySelector('.answer-options');
    const addOptionButton = questionElement.querySelector('.add-option');
    const deleteQuestionButton = questionElement.querySelector('.delete-question');

    typeSelect.addEventListener('change', function() {
        if (this.value === 'multiple_choice' || this.value === 'checkbox') {
            answerOptions.style.display = '';
            if (answerOptions.querySelectorAll('.input-group').length < 2) {
                addAnswerOption(addOptionButton, this.value === 'checkbox');
                addAnswerOption(addOptionButton, this.value === 'checkbox');
            }
        } else {
            answerOptions.style.display = 'none';
        }
    });

    addOptionButton.addEventListener('click', function() {
        addAnswerOption(this, typeSelect.value === 'checkbox');
    });

    deleteQuestionButton.addEventListener('click', function() {
        if (confirm('Are you sure you want to delete this question?')) {
            questionElement.remove();
        }
    });
}

function addAnswerOption(button, isCheckbox) {
    const index = button.getAttribute('data-index');
    const optionsContainer = document.getElementById(`options_${index}`);
    const optionCount = optionsContainer.childElementCount;

    const newOption = document.createElement('div');
    newOption.classList.add('input-group', 'mb-3');
    newOption.innerHTML = `
        ${isCheckbox ? '<input type="checkbox" class="form-check-input" disabled>' : ''}
        <input type="text" class="form-control consistent-width-input" name="questions[${index}][answer_options][]" required>
        <button class="btn btn-outline-danger delete-option" type="button">Delete</button>
    `;
    optionsContainer.appendChild(newOption);

    const deleteOptionButton = newOption.querySelector('.delete-option');
    deleteOptionButton.addEventListener('click', function() {
        newOption.remove();
    });
}

document.querySelectorAll('.question-item').forEach(function(questionElement) {
    attachEventListeners(questionElement);
});
</script>
@endsection
