@extends('employee-layout')
@section('content')
<div class="container-fluid">
  <div style="padding-bottom: 2rem;">
    <h1>Create New Quiz</h1>
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

  <form action="{{ route('quizzes.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
      <label for="title" class="form-label" style="font-size: 15px; font-weight: bold;">Quiz Title:</label>
      <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}">
    </div>
    <div class="mb-2">
      <label for="questions" class="form-label" style="font-size: 15px; font-weight: bold;">Quiz Questions:</label>
      <div id="question-fields"></div>
    </div>
    <div class="mb-3">
      <button type="button" class="btn btn-success" onclick="addQuestionField()">Add Question</button>
    </div>
    <div class="mb-3">
      <button type="submit" class="confirm-quiz-button">Create Quiz</button>
    </div>
  </form>
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
      transform: scale(1.1); /* Adjust scale to make checkbox smaller */
      margin-right: 10px;
      position: relative;
      border: 1px solid #b8bdc2; /* Set the checkbox border color */
    }

    .input-group {
      align-items: center;
    }
  </style>

  <script>
    let questionCount = 0;

    function addQuestionField() {
      saveFormState();

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
                <button type="button" class="btn btn-danger" onclick="removeQuestionField(this)" style="background-color: #6A1043; color: white; border-radius: 10px; height: 38px;"><i class="fas fa-trash"></i></button>
            </div>
            <div id="answer-container-${currentQuestionCount}"></div>
        </div>`;
      questionFields.innerHTML += questionField;

      const questionTypeSelect = document.getElementById(`question_type-${currentQuestionCount}`);
      changeQuestionType(questionTypeSelect);

      questionCount++;

      restoreFormState();
    }

    function removeQuestionField(button) {
      const questionBlock = button.closest('.question-block');
      questionBlock.remove();
      updateQuestionNumbers();
      saveFormState();
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
      const addOptionButtons = answerContainer.querySelectorAll('.btn-success');
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
      }
    }

    function addAnswerOption(questionId) {
      const answerContainer = document.getElementById(`answer-container-${questionId}`);
      const existingAnswerCount = answerContainer.querySelectorAll('.input-group').length;

      console.log(`Adding option for question ${questionId}, existing options: ${existingAnswerCount}`);
      const newAnswerNumber = existingAnswerCount + 1;
      const questionType = document.getElementById(`question_type-${questionId}`).value;

      let newAnswer;
      if (questionType === 'checkbox') {
        newAnswer = `<div class="input-group mt-2">
            <input type="checkbox" class="form-check-input" id="answer-${questionId}-${newAnswerNumber}" disabled>
            <input type="text" class="form-control consistent-width-input" id="answer_text-${questionId}-${newAnswerNumber}" name="answers[${questionId}][]" placeholder="Enter answer option">
            <button type="button" class="btn btn-danger" onclick="removeAnswerOption(this, ${questionId})" style="background-color: #6A1043; color: white; border-radius: 10px; height: 38px;"><i class="fas fa-trash"></i></button>
        </div>`;
      } else {
        newAnswer = `<div class="input-group mt-2">
            <input type="text" class="form-control consistent-width-input" id="answer_text-${questionId}-${newAnswerNumber}" name="answers[${questionId}][]" placeholder="Enter answer option">
            <button type="button" class="btn btn-danger" onclick="removeAnswerOption(this, ${questionId})" style="background-color: #6A1043; color: white; border-radius: 10px; height: 38px; margin-right: 10px;"><i class="fas fa-trash"></i></button>
        </div>`;
      }

      const answerGroup = document.createElement('div');
      answerGroup.innerHTML = newAnswer;

      // Append the existing "Add Option" button to the new answer group
      const addOptionButton = answerContainer.querySelector('.btn-success.add-option-btn');
      if (addOptionButton) {
        addOptionButton.remove();
        const inputGroup = answerGroup.querySelector('.input-group');
        inputGroup.appendChild(addOptionButton);
      } else {
        const newAddOptionButton = document.createElement('button');
        newAddOptionButton.type = 'button';
        newAddOptionButton.className = 'btn btn-success add-option-btn';
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

      console.log(`Removing option for question ${questionId}, remaining options before removal: ${remainingOptions}`);

      if (remainingOptions <= 2) {
        alert('Please add at least two answer options for multiple choice or checkbox questions.');
      } else {
        const answerGroup = buttonElement.closest('.input-group');
        const addOptionButton = answerGroup.querySelector('.btn-success.add-option-btn');
        answerGroup.remove();

        const updatedRemainingOptions = answerContainer.querySelectorAll('.input-group').length;
        console.log(`Option removed for question ${questionId}, remaining options after removal: ${updatedRemainingOptions}`);

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

    function saveFormState() {
      const questionBlocks = document.querySelectorAll('.question-block');
      const formState = [];

      questionBlocks.forEach((block, index) => {
        const questionInput = block.querySelector('input[id^="question-"]');
        const questionTypeSelect = block.querySelector('select[id^="question_type-"]');
        const answerInputs = block.querySelectorAll('input[id^="answer_text-"]');

        const questionState = {
          question: questionInput.value,
          type: questionTypeSelect.value,
          answers: []
        };

        answerInputs.forEach(answerInput => {
          questionState.answers.push(answerInput.value);
        });

        formState.push(questionState);
      });

      localStorage.setItem('formState', JSON.stringify(formState));
    }

    function restoreFormState() {
      const formState = JSON.parse(localStorage.getItem('formState'));
      if (formState) {
        formState.forEach((questionState, index) => {
          const questionBlock = document.getElementById(`question-block-${index}`);
          if (questionBlock) {
            const questionInput = questionBlock.querySelector('input[id^="question-"]');
            const questionTypeSelect = questionBlock.querySelector('select[id^="question_type-"]');
            const answerContainer = questionBlock.querySelector('div[id^="answer-container"]');

            questionInput.value = questionState.question;
            questionTypeSelect.value = questionState.type;
            changeQuestionType(questionTypeSelect);

            questionState.answers.forEach((answer, answerIndex) => {
              if (answerIndex >= 2) { // If more than 2 answers, add additional options
                addAnswerOption(index);
              }
              const answerInput = answerContainer.querySelectorAll('input[id^="answer_text-"]')[answerIndex];
              if (answerInput) {
                answerInput.value = answer;
              }
            });
          } else {
            addQuestionField();
            const questionBlock = document.getElementById(`question-block-${index}`);
            const questionInput = questionBlock.querySelector('input[id^="question-"]');
            const questionTypeSelect = questionBlock.querySelector('select[id^="question_type-"]');
            const answerContainer = questionBlock.querySelector('div[id^="answer-container"]');

            questionInput.value = questionState.question;
            questionTypeSelect.value = questionState.type;
            changeQuestionType(questionTypeSelect);

            questionState.answers.forEach((answer, answerIndex) => {
              if (answerIndex >= 2) { // If more than 2 answers, add additional options
                addAnswerOption(index);
              }
              const answerInput = answerContainer.querySelectorAll('input[id^="answer_text-"]')[answerIndex];
              if (answerInput) {
                answerInput.value = answer;
              }
            });
          }
        });
      }
    }

    window.addEventListener('beforeunload', function () {
      localStorage.removeItem('formState');
    });

    document.addEventListener('DOMContentLoaded', function () {
      restoreFormState();
    });
  </script>
</div>
@endsection
