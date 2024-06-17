@extends('admin-layout')

@section('content')

<div class="container-fluid">
    <div style="padding-bottom: 2rem;">
        <h1>Edit Quiz</h1>
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

    <form action="{{ route('admin.update_quiz', ['id' => $quiz->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-md-8">
                <div class="page-title-container">
                    <h5><label for="title" class="form-label page-title">Quiz Title:</label></h5>
                </div>
                <div class="page-content">
                    <input type="text" class="form-control" id="title" name="title" value="{{ $quiz->title }}" placeholder="Enter Title" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="page-title-container">
                    <h5><label for="passing_score" class="form-label page-title">Minimum Passing Score:</label></h5>
                </div>
                <div class="page-content">
                    <input type="number" class="form-control" id="passing_score" name="passing_score" value="{{ $quiz->passing_score }}" placeholder="Enter Passing Score" required>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="page-title-container">
                <h5><label for="content" class="form-label page-title">Quiz Questions:</label></h5>
            </div>
            <div class="page-content">

            <div id="question-fields">
                @foreach($quizQuestions as $index => $question)
                    <div class="mb-4 question-block" id="question-block-{{ $index }}">
                        <div class="input-group mb-2">
                            <label for="question-{{ $index }}" class="form-label" style="font-size: 15px; font-weight: bold;">Question {{ $index + 1 }}:&nbsp;&nbsp;</label>
                            <input type="text" class="form-control" id="question-{{ $index }}" name="questions[]" value="{{ $question->question }}" placeholder="Enter question" required>
                            <select class="form-control" id="question_type-{{ $index }}" name="question_types[]" onchange="changeQuestionType(this)" required>
                                <option value="multiple_choice" {{ $question->type == 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                                <option value="short_answer" {{ $question->type == 'short_answer' ? 'selected' : '' }}>Text Field</option>
                                <option value="checkbox" {{ $question->type == 'checkbox' ? 'selected' : '' }}>Checkbox</option>
                            </select>
                            <button type="button" class="btn btn-danger" onclick="removeQuestionField(this)" style="border-radius: 10px; height: 38px; margin-left: 10px;"><i class="fas fa-trash"></i></button>
                        </div>
                        <div id="answer-container-{{ $index }}">
                            @php
                                $answerOptions = is_string($question->answer_options) ? json_decode($question->answer_options) : $question->answer_options;
                                $correctAnswers = is_string($question->correct_answers) ? json_decode($question->correct_answers) : $question->correct_answers;
                            @endphp
                            @if($question->type == 'multiple_choice' || $question->type == 'checkbox')
                                @foreach($answerOptions as $optIndex => $option)
                                    <div class="input-group mt-2">
                                        @if($question->type == 'checkbox')
                                            <input type="checkbox" class="form-check-input" name="correct_answers[{{ $index }}][]" value="{{ $optIndex }}" {{ in_array($optIndex, $correctAnswers) ? 'checked' : '' }}>
                                        @else
                                            <input type="radio" class="form-check-input" name="correct_answers[{{ $index }}]" value="{{ $optIndex }}" {{ $correctAnswers == $optIndex ? 'checked' : '' }}>
                                        @endif
                                        <input type="text" class="form-control consistent-width-input" id="answer_text-{{ $index }}-{{ $optIndex }}" name="answers[{{ $index }}][]" value="{{ $option }}" placeholder="Enter answer option">
                                        <button type="button" class="btn btn-danger" onclick="removeAnswerOption(this, {{ $index }})" style="border-radius: 10px; height: 38px; margin-left: 10px;"><i class="fas fa-trash"></i></button>
                                    </div>
                                @endforeach
                                <button type="button" class="btn btn-primary add-option-btn" onclick="addAnswerOption({{ $index }})">Add Option</button>
                            @else
                                <div class="input-group mt-2">
                                    <input type="text" class="form-control consistent-width-input" id="answer_text-{{ $index }}" name="correct_answers[{{ $index }}][]" value="{{ $correctAnswers }}" placeholder="Enter correct answer">
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
                </div>
                <button type="button" class="btn btn-primary" onclick="addQuestionField()">Add Question</button>
            </div>
            
        </div>

        <button type="submit" class="confirm-quiz-button float-end marg-btm-cus">Update Quiz</button>
    </form>

</div>

<style>
    .consistent-width-input {
        width: calc(100% - 180px);
        max-width: 540px;
        display: inline-block;
    }

    .input-group .add-option-btn {
        margin-left: 10px !important;
        border-radius: 10px !important;
        height: 38px;
        margin-top:10px !important;
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
    let questionCount = {{ count($quizQuestions) }};

    function addQuestionField() {
        const questionFields = document.getElementById("question-fields");
        const currentQuestionCount = questionFields.children.length;
        const questionField = `
            <div class="mb-4 question-block" id="question-block-${currentQuestionCount}">
                <div class="input-group mb-2">
                    <label for="question-${currentQuestionCount}" class="form-label" style="font-size: 15px; font-weight: bold;">Question ${currentQuestionCount + 1}:&nbsp;&nbsp;</label>
                    <input type="text" class="form-control" id="question-${currentQuestionCount}" name="questions[]" placeholder="Enter question" required>
                    <select class="form-control" id="question_type-${currentQuestionCount}" name="question_types[]" onchange="changeQuestionType(this)" required>
                        <option value="multiple_choice">Multiple Choice</option>
                        <option value="short_answer">Text Field</option>
                        <option value="checkbox">Checkbox</option>
                    </select>

                    <button type="button" class="btn btn-danger" onclick="removeQuestionField(this)" style="border-radius: 10px; height: 38px; margin-left: 10px;"><i class="fas fa-trash"></i></button>
                </div>
                <div id="answer-container-${currentQuestionCount}"></div>
            </div>`;
        questionFields.innerHTML += questionField;
        questionCount++;
    }

    function removeQuestionField(button) {
        const questionBlock = button.closest('.question-block');
        questionBlock.remove();
        updateQuestionNumbers();
    }

    function updateQuestionNumbers() {
        const questionBlocks = document.querySelectorAll('.question-block');
        questionBlocks.forEach((block, index) => {
            const label = block.querySelector('label');
            label.innerHTML = `Question ${index + 1}:&nbsp;&nbsp;`;
            block.id = `question-block-${index}`;
            const questionInput = block.querySelector('input[id^="question-"]');
            questionInput.id = `question-${index}`;
            const questionSelect = block.querySelector('select[id^="question_type-"]');
            questionSelect.id = `question_type-${index}`;
            questionSelect.setAttribute('onchange', `changeQuestionType(this)`);
            const answerContainer = block.querySelector('div[id^="answer-container"]');
            answerContainer.id = `answer-container-${index}`;
            updateAnswerOptionButtons(answerContainer, index);
        });
        questionCount = questionBlocks.length;
    }

    function updateAnswerOptionButtons(answerContainer, questionId) {
        const addOptionButtons = answerContainer.querySelectorAll('.btn-primary');
        addOptionButtons.forEach(button => {
            button.setAttribute('onclick', `addAnswerOption(${questionId})`);
        });
        const removeButtons = answerContainer.querySelectorAll('.btn-danger');
        removeButtons.forEach(button => {
            button.setAttribute('onclick', `removeAnswerOption(this, ${questionId})`);
        });
    }

    function changeQuestionType(selectElement) {
        const questionId = selectElement.id.split('-')[1];
        const answerContainer = document.getElementById(`answer-container-${questionId}`);
        answerContainer.innerHTML = "";

        if (selectElement.value === 'multiple_choice' || selectElement.value === 'checkbox') {
            addAnswerOption(questionId);
            addAnswerOption(questionId);
        } else if (selectElement.value === 'short_answer') {
            answerContainer.innerHTML = `<div class="input-group mt-2">
                <input type="text" class="form-control consistent-width-input" id="answer_text-${questionId}" name="correct_answers[${questionId}][]" placeholder="Enter correct answer">
            </div>`;
        }
    }

    function addAnswerOption(questionId) {
        const answerContainer = document.getElementById(`answer-container-${questionId}`);
        const existingAnswerCount = answerContainer.querySelectorAll('.input-group').length;

        let newAnswer;
        const questionType = document.getElementById(`question_type-${questionId}`).value;
        if (questionType === 'checkbox') {
            newAnswer = `<div class="input-group mt-2">
                <input type="checkbox" class="form-check-input" name="correct_answers[${questionId}][]" value="${existingAnswerCount}">
                <input type="text" class="form-control consistent-width-input" id="answer_text-${questionId}-${existingAnswerCount}" name="answers[${questionId}][]" placeholder="Enter answer option">
                <button type="button" class="btn btn-danger" onclick="removeAnswerOption(this, ${questionId})" style="border-radius: 10px; height: 38px; margin-left: 10px;"><i class="fas fa-trash"></i></button>
            </div>`;
        } else {
            newAnswer = `<div class="input-group mt-2">
                <input type="radio" class="form-check-input" name="correct_answers[${questionId}]" value="${existingAnswerCount}">
                <input type="text" class="form-control consistent-width-input" id="answer_text-${questionId}-${existingAnswerCount}" name="answers[${questionId}][]" placeholder="Enter answer option">
                <button type="button" class="btn btn-danger" onclick="removeAnswerOption(this, ${questionId})" style= "border-radius: 10px; height: 38px; margin-left: 10px;"><i class="fas fa-trash"></i></button>
            </div>`;
        }

        const answerGroup = document.createElement('div');
        answerGroup.innerHTML = newAnswer;

        // Append the existing "Add Option" button to the new answer group
        const addOptionButton = answerContainer.querySelector('.btn-primary.add-option-btn');
        if (addOptionButton) {
            addOptionButton.remove();
            const inputGroup = answerGroup.querySelector('.input-group');
            inputGroup.appendChild(addOptionButton);
        } else {
            const newAddOptionButton = document.createElement('button');
            newAddOptionButton.type = 'button';
            newAddOptionButton.className = 'btn btn-primary add-option-btn';
            newAddOptionButton.textContent = 'Add Option';
            newAddOptionButton.setAttribute('onclick', `addAnswerOption(${questionId})`);
            const inputGroup = answerGroup.querySelector('.input-group');
            inputGroup.appendChild(newAddOptionButton);
        }

        answerContainer.appendChild(answerGroup);
    }

    function removeAnswerOption(buttonElement, questionId) {
        const answerContainer = document.getElementById(`answer-container-${questionId}`);
        const remainingOptions = answerContainer.querySelectorAll('.input-group').length;

        if (remainingOptions <= 2) {
            alert('Please add at least two answer options for multiple choice or checkbox questions.');
        } else {
            const answerGroup = buttonElement.closest('.input-group');
            const addOptionButton = answerGroup.querySelector('.btn-primary.add-option-btn');
            answerGroup.remove();

            const updatedRemainingOptions = answerContainer.querySelectorAll('.input-group').length;

            // Ensure the "Add Option" button is appended to the last input group
            moveAddOptionButtonToLastRow(answerContainer, addOptionButton);
        }
    }

    function moveAddOptionButtonToLastRow(answerContainer, addOptionButton) {
        if (addOptionButton) {
            addOptionButton.remove();
            const lastAnswerGroup = answerContainer.querySelector('.input-group:last-child');
            if (lastAnswerGroup) {
                lastAnswerGroup.querySelector('button.btn-danger').insertAdjacentElement('afterend', addOptionButton);
            } else {
                answerContainer.appendChild(addOptionButton);
            }
        }
    }
</script>
@endsection

