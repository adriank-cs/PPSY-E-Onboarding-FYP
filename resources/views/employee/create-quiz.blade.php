<!-- create-quiz.blade.php -->
@extends('employee-layout')

@section('content')
<div class="container-fluid">
<div style="padding-bottom: 2rem;"><h1>Create New Quiz</h1>
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
    <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }} ">
  </div>

  <div class="mb-2">
    <label for="questions" class="form-label" style="font-size: 15px; font-weight: bold;">Quiz Questions:</label>
    <div class="input-group">
      <button type="button" class="btn btn-success "  onclick="addQuestionField()" style="background-color: #6A1043; color: white;">Add Question</button>
    </div>
    <div id="question-fields"></div>
  </div>


  
  <script>
    let questionCount = 0;
    let questions = []; // Declare an empty array to store questions
    let question_types = []; 


    function addQuestionField() {
      // Create a group element to hold the answer input and remove button
  const answerGroup = document.createElement('div');
  answerGroup.classList.add('input-group', 'mt-2');
    // Add one initial answer option input field with questionId
  const answer1 = `<input type="text" class="form-control mt-2" id="answer-${questionCount}-1" name="answers[${questionCount}][]" placeholder="Enter answer option" >`;
  const removeButton1 = `<button type="button" class="btn btn-danger mt-2 short-button" onclick="removeAnswerOption(this)">Remove Option</button>`;
  answerGroup.innerHTML = answer1 + removeButton1;
    const questionField = `
      

      <div class="mb-4 input-group" id="question-${questionCount}">
      <div class="d-flex ">
        <label for="question-${questionCount}" class="form-label mt-4" style="font-size: 15px; font-weight: bold;" >Question ${questionCount + 1}:&nbsp;&nbsp;</label>
      </div>
        <input type="text" class="form-control mt-4" id="question-${questionCount}" name="questions[]" placeholder="Enter question" required style="width: 30%; height: 10%;" >

        <select class="form-control mt-4" id="question_type-${questionCount}" name="question_types[]" onchange="changeQuestionType(this)" required style=" height: 36px;">
          <option value="multiple_choice">Multiple Choice</option>
          <option value="short_answer">Text Field</option>  
          
        </select>
        <button type="button" class="btn btn-danger mt-4" onclick="removeQuestionField(this)" style="background-color: #6A1043; color: white;"> <i class="fas fa-trash"></i></button>
        </div>

      <div class="mb-4 input-group" id="answer-container-${questionCount}" required style="width: 31%">
        <div id="answer-container-${questionCount}"></div>
      </div>`;

      
/*    <div class="dropdown mt-4">
        <button class="btn btn-purple dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-cog"></i>  </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
          <button type="button" class="btn btn-danger " onclick="removeQuestionField(this)" style="background-color: #6A1043; color: white;"> <i class="fas fa-trash"></i></button>
        </ul>
      </div> */
      //<option value="checkboxes">Checkboxes</option>
      document.getElementById("question-fields").innerHTML += questionField;

      // Add an empty input field for answer options (multiple choice) or leave it blank (short answer)
      const questionTypeSelect = document.getElementById(`question_type-${questionCount}`);
      changeQuestionType(questionTypeSelect);

      questionCount++;
      console.log("Question added:", questionCount);
      console.log("Questions array:", questions);
      console.log("Question types array:", question_types);

    }

    function removeQuestionField(button) {
    // Get the question container element (the parent of the button)
    const questionContainer = button.parentNode;

    // Get the answer container element (the next sibling of the question container)
    const answerContainer = questionContainer.nextElementSibling;

    // Remove both the question container and answer container elements
    questionContainer.remove();
    answerContainer.remove();
        questionCount--;
    }

    function changeQuestionType(selectElement) {
      const questionId = selectElement.id.split('-')[1];
      const answerContainer = document.getElementById(`answer-container-${questionId}`);

      answerContainer.innerHTML = ""; // Clear existing content

      if (selectElement.value === 'multiple_choice') {
        // Create container for multiple answer options with "Add Answer Option" button
        const answerOptionContainer = document.createElement('div');
        const addButton = `<button type="button" class="btn btn-success mt-2" onclick="addAnswerOption(this, ${questionId})" style="background-color: #6A1043; color: white;">Add Option</button>`;
        answerOptionContainer.innerHTML = addButton;

        // Create a group element to hold the answer input and remove button
        const answerGroup = document.createElement('div');
        answerGroup.classList.add('input-group', 'mt-2');

        // Add one initial answer option input field with questionId
        const answer1 = `<input type="text" class="form-control mt-2" id="answer-${questionId}-1" name="answers[${questionId}][]" placeholder="Enter answer option" style="width:235px">`;
        const removeButton1 = `<button type="button" class="btn btn-danger mt-2" onclick="removeAnswerOption(this)" style="background-color: #6A1043; color: white;"> <i class="fas fa-trash"></i></button>`; //Default
        //Add the answer option and remove button to the group element
        answerOptionContainer.innerHTML += answer1 + removeButton1; //this is the 1st option displayed
        answerGroup.innerHTML = answer1 + removeButton1; //this is the 2nd option displayed
       
        answerOptionContainer.appendChild(answerGroup);

        answerContainer.appendChild(answerOptionContainer);
      } else if (selectElement.value === 'short_answer') {
        // Leave answerContainer empty for text field questions
      } else if (selectElement.value === 'checkboxes') {
        // Create checkboxes for multiple answer options
        const answerContainer = document.getElementById(`answer-container-${questionId}`);
        const questionLabel = document.getElementById(`question-label-${questionId}`); // Assuming you have a label for the question

        // Add a new paragraph element below the question label to hold checkboxes
        const checkboxContainer = document.createElement('p');
        questionLabel.parentNode.insertBefore(checkboxContainer, questionLabel.nextSibling);

        const checkbox1 = `<input type="checkbox" id="answer-${questionId}-1" name="answers[${questionId}][]" value="option1"> Option 1<br>`;
        const checkbox2 = `<input type="checkbox" id="answer-${questionId}-2" name="answers[${questionId}][]" value="option2"> Option 2<br>`;
        checkboxContainer.innerHTML = checkbox1 + checkbox2;

    // You can add more checkboxes here following the same pattern
  } else {
    // Handle other question types (if any)
    
  }
}

//add answer options
function addAnswerOption(buttonElement, questionId) {
  // Get the answer container element
  const answerContainer = document.getElementById(`answer-container-${questionId}`);

  // Get the current number of answer options (assuming they are named sequentially)
  const existingAnswerCount = answerContainer.querySelectorAll('input[type="text"]').length;

  
  const newAnswerNumber = existingAnswerCount + 1;
  
  // Create a container for the new answer option input field and remove button
  const answerOptionContainer = document.createElement('div');
  const addButton = `<button type="button" class="btn btn-success mt-2" onclick="addAnswerOption(this, ${questionId})">Add Option</button>`;
  answerOptionContainer.innerHTML = addButton;


  // Create a new group element to hold the answer input and remove button
  const answerGroup = document.createElement('div');
  answerGroup.classList.add('input-group', 'mt-2');

  // Create a new answer option input field
  
  const newAnswer = `<input type="text" class="form-control mt-2" id="answer-${questionId}-${newAnswerNumber}" name="answers[${questionId}][]" placeholder="Enter answer option" style="height: 38px;">`;

  // Create a remove button for this answer option
  const removeButton = `<button type="button" class="btn btn-danger mt-2" onclick="removeQuestionField(this)" style="background-color: #6A1043; color: white; height: 38px;"> <i class="fas fa-trash"></i></button>`;

  // Add the answer option and remove button to the group element
  answerGroup.innerHTML = newAnswer + removeButton;


  answerOptionContainer.appendChild(answerGroup);
  
  // Append the group element to the answer container
  answerContainer.appendChild(answerGroup);
  console.log("options:", answerOptionContainer);
}


function removeAnswerOption(buttonElement) {
  // Get the answer group element (the parent of the button)
  const answerGroup = buttonElement.parentNode;

  // Get the answer container element (the parent of the answer group)
  const answerContainer = answerGroup.parentNode;

  // Remove the answer group element (containing the answer input and remove button)
  answerGroup.remove();

    // Check if there are any remaining answer options after removal
  const remainingOptions = answerContainer.querySelectorAll('input[type="text"]').length;
  if (remainingOptions === 0) {
    alert('Please add at least one answer option for multiple choice questions.');
  }
}

  </script>

  <div class="mb-3">
    <button type="submit" class="btn btn-primary" >Create Quiz</button>
  </div>
  
</form>
@endsection
<script src="https://kit.fontawesome.com/9f358c91c6.js" crossorigin="anonymous"></script>