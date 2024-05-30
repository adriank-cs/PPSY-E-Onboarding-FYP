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
      width: calc(100% - 60px);
      max-width: 600px;
      display: inline-block;
    }
  </style>

<script>
  let questionCount = 0;

  function addQuestionField() {
    saveCurrentState();

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
          </select>
          <button type="button" class="btn btn-danger" onclick="removeQuestionField(this)" style="background-color: #6A1043; color: white; border-radius: 10px; height: 38px;"><i class="fas fa-trash"></i></button>
        </div>
        <div id="answer-container-${currentQuestionCount}"></div>
      </div>`;
    questionFields.innerHTML += questionField;
    const questionTypeSelect = document.getElementById(`question_type-${currentQuestionCount}`);
    changeQuestionType(questionTypeSelect);

    questionCount++;
    restorePreviousState();
  }

  function removeQuestionField(button) {
    const questionBlock = button.closest('.question-block');
    questionBlock.remove();
    updateQuestionNumbers();
    saveCurrentState();
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
      button.setAttribute('onclick', `addAnswerOption(this, ${questionId})`);
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

    if (selectElement.value === 'multiple_choice') {
      addAnswerOption(null, questionId);
      addAnswerOption(null, questionId);
    }
  }

  function addAnswerOption(buttonElement, questionId) {
    const answerContainer = document.getElementById(`answer-container-${questionId}`);
    const existingAnswerCount = answerContainer.querySelectorAll('input[type="text"]').length;
    const newAnswerNumber = existingAnswerCount + 1;

    const answerGroup = document.createElement('div');
    answerGroup.classList.add('input-group', 'mt-2');

    const newAnswer = `<input type="text" class="form-control consistent-width-input" id="answer-${questionId}-${newAnswerNumber}" name="answers[${questionId}][]" placeholder="Enter answer option">`;
    const removeButton = `<button type="button" class="btn btn-danger" onclick="removeAnswerOption(this, ${questionId})" style="background-color: #6A1043; color: white; border-radius: 10px; height: 38px; margin-right: 10px;"><i class="fas fa-trash"></i></button>`;
    const addOptionButton = `<button type="button" class="btn btn-success" onclick="addAnswerOption(this, ${questionId})" style="margin-left: 10px; border-radius: 10px; height: 38px;">Add Option</button>`;

    answerGroup.innerHTML = newAnswer + removeButton + addOptionButton;

    const existingAddOptionButton = answerContainer.querySelector('.btn-success');
    if (existingAddOptionButton) {
      existingAddOptionButton.remove();
    }

    answerContainer.appendChild(answerGroup);
  }

  function addAnswerOptionWithValue(answerContainer, questionId, value) {
    const existingAnswerCount = answerContainer.querySelectorAll('input[type="text"]').length;
    const newAnswerNumber = existingAnswerCount + 1;

    const answerGroup = document.createElement('div');
    answerGroup.classList.add('input-group', 'mt-2');

    const newAnswer = `<input type="text" class="form-control consistent-width-input" id="answer-${questionId}-${newAnswerNumber}" name="answers[${questionId}][]" placeholder="Enter answer option" value="${value}">`;
    const removeButton = `<button type="button" class="btn btn-danger" onclick="removeAnswerOption(this, ${questionId})" style="background-color: #6A1043; color: white; border-radius: 10px; height: 38px; margin-right: 10px;"><i class="fas fa-trash"></i></button>`;
    const addOptionButton = `<button type="button" class="btn btn-success" onclick="addAnswerOption(this, ${questionId})" style="margin-left: 10px; border-radius: 10px; height: 38px;">Add Option</button>`;

    answerGroup.innerHTML = newAnswer + removeButton + addOptionButton;

    const existingAddOptionButton = answerContainer.querySelector('.btn-success');
    if (existingAddOptionButton) {
      existingAddOptionButton.remove();
    }

    answerContainer.appendChild(answerGroup);
  }

  function removeAnswerOption(buttonElement, questionId) {
    const answerGroup = buttonElement.parentNode;
    const answerContainer = answerGroup.parentNode;

    answerGroup.remove();

    const remainingOptions = answerContainer.querySelectorAll('input[type="text"]').length;
    if (remainingOptions === 0) {
      alert('Please add at least one answer option for multiple choice questions.');
      addAnswerOption(null, questionId);
    } else {
      moveAddOptionButtonToLastRow(answerContainer);
    }
  }

  function moveAddOptionButtonToLastRow(answerContainer) {
    const addOptionButton = answerContainer.querySelector('.btn-success');
    if (addOptionButton) {
      addOptionButton.parentNode.remove();
      const lastAnswerGroup = answerContainer.lastElementChild;
      if (lastAnswerGroup) {
        lastAnswerGroup.appendChild(addOptionButton);
      }
    }
  }

  // Save the current state of the form
  function saveCurrentState() {
    const formState = {};
    document.querySelectorAll('.question-block').forEach((block, index) => {
      const questionInput = block.querySelector('input[id^="question-"]');
      const questionSelect = block.querySelector('select[id^="question_type-"]');
      const answers = Array.from(block.querySelectorAll('input[type="text"][id^="answer-"]')).map(input => input.value);

      formState[index] = {
        question: questionInput ? questionInput.value : '',
        type: questionSelect ? questionSelect.value : '',
        answers: answers
      };
    });
    localStorage.setItem('formState', JSON.stringify(formState));
  }

  // Restore the previous state of the form
  function restorePreviousState() {
    const formState = JSON.parse(localStorage.getItem('formState'));
    if (formState) {
      document.querySelectorAll('.question-block').forEach((block, index) => {
        if (formState[index]) {
          const questionInput = block.querySelector('input[id^="question-"]');
          const questionSelect = block.querySelector('select[id^="question_type-"]');
          const answerContainer = block.querySelector('div[id^="answer-container"]');

          if (questionInput) questionInput.value = formState[index].question;
          if (questionSelect) questionSelect.value = formState[index].type;
          if (answerContainer) answerContainer.innerHTML = '';

          if (formState[index].type === 'multiple_choice' && answerContainer) {
            formState[index].answers.forEach(answer => {
              addAnswerOptionWithValue(answerContainer, index, answer);
            });
          }
        }
      });
      localStorage.removeItem('formState');
    }
  }

  document.addEventListener('DOMContentLoaded', function () {
    restorePreviousState();
  });
</script>








</div>
@endsection

<script src="https://kit.fontawesome.com/9f358c91c6.js" crossorigin="anonymous"></script>