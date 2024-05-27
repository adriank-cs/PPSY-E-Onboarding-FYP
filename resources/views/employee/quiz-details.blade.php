<!-- quiz-details.blade.php -->
@extends('employee-layout')

@section('content')


<div class="container-fluid">
  
    <h1><span style="font-size: 2.5rem; " >{{ $quiz->title }}</span></h1>

    <form action="{{ route('quizzes.submit-answers', $quiz->id) }}" method="POST">
        @csrf

    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModal">Confirm Submission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to submit your answers for this quiz?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confirmationModal" data-prevent-resubmission>Confirm Submit</button>
                </div>
            </div>
        </div>
    </div>

        <?php
    $questionCount = 1; // Initialize counter for question number
    ?>
    
@foreach ($quiz->questions as $question)
  <div class="mb-3">

    @if ($question->type === 'multiple_choice')
      <label class="form-check-label "  for="question-{{ $question->id }}" class="form-label"  style= "margin-top: 2%; margin-bottom: 2%"><strong><span style="font-size: 1.2rem; background-color: #6A1043; color: white; padding: 5px 20px; border-radius: 5px; " >{{ $questionCount++ }} : {{ $question->question }}</span></strong></label>
      @if (isset($userResponses[$question->id]))
        <?php
            $answerOptions = json_decode($userResponses[$question->id]->answer); // Decode if JSON encoded
        ?>
        @foreach ($answerOptions as $optionIndex => $optionText)
          <div class="form-check" >
            <input type="radio" class="form-check-input" id="question-{{ $question->id }}-option{{ $optionIndex + 1 }}" name="answers[{{ $question->id }}]" value="{{ $optionText }}">
            <label class="form-check-label" for="question-{{ $question->id }}-option{{ $optionIndex + 1 }}">{{ $optionText }}</label>
          </div>
        @endforeach
      @else
        <?php
            // Access answer options from the question object if user response doesn't exist (assuming it's stored directly)
            $answerOptions = json_decode($question->answer_options); // Decode if JSON encoded
        ?> 
        @foreach ($answerOptions as $optionIndex => $optionText)
          <div class="form-check">
            <input type="radio" class="form-check-input" id="question-{{ $question->id }}-option{{ $optionIndex + 1 }}" name="answers[{{ $question->id }}]" value="{{ $optionText }}">
            <label class="form-check-label" for="question-{{ $question->id }}-option{{ $optionIndex + 1 }}">{{ $optionText }}</label>
          </div>
        @endforeach
      @endif
    @elseif ($question->type === 'short_answer')
      <strong><label class="form-check-label " for="question-{{ $question->id }}" class="form-label" style= "margin-top: 2%; margin-bottom: 1%; font-size: 1.2rem; background-color: #6A1043; color: white; padding: 5px 20px; border-radius: 5px; ">{{ $questionCount++ }} : {{ $question->question }} </strong></label>
      @if (isset($userResponses[$question->id]))
        <textarea class="form-control" id="question-{{ $question->id }}" name="answers[{{ $question->id }}]" rows="3" readonly>{{ $userResponses[$question->id]->answer ?? '' }}</textarea>
      @else
        <textarea class="form-control" id="question-{{ $question->id }}" name="answers[{{ $question->id }}]" rows="3"></textarea>
      @endif
    @endif
  </div>
@endforeach


        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confirmationModal" style= "background-color: #6A1043; color:white;">Submit Answers</button>
    </form>

<!--     <div class="mt-4">
        <h2>Progress: {{ $completionPercentage }}%</h2>
        <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: {{ $completionPercentage }}%;" aria-valuenow="{{ $completionPercentage }}" aria-valuemin="0" aria-valuemax="100">
                {{ $completionPercentage }}% Complete
            </div>
        </div>
    </div> -->

<!--     @if ($completionPercentage > 20)
        <p class="mt-3 text-success">Congratulations! You have completed this quiz.</p>
    @endif -->
  @if(session()->has('success'))
    <div class="alert alert-success mt-4 "  role="alert">
      {{ session()->get('success') }}
    </div>
  @endif

</div>

<script>
$(document).ready(function() {
    $('#submitAnswers').click(function() {
        submitAnswers(); // Call the submitAnswers function here
    });
});
function submitAnswers() {
  // Collect user responses from form elements
  var userResponses = {}; // Object to store responses (key: question ID, value: answer)
  $('.user-response').each(function() {
    var questionId = $(this).data('questionId');
    // Extract answer based on question type (radio buttons, textarea)
    userResponses[questionId] = $(this).find('input[type="radio"]:checked').val() || $(this).find('textarea').val();
  });

  $.ajax({
    url: "{{ route('quizzes.submit-answers', $quiz->id) }}", // Replace with your details route
    method: "POST",
    data: { answers: userResponses }, // Send user responses as data
    success: function(response) {
      if (response.success) {
        // Update quiz display here
        updateQuizDisplay(response.data); // Call function with preventResubmission=true
      } else {
        // Handle submission error (optional)
        alert('Error submitting quiz!');
      }
    },
    error: function(jqXHR, textStatus, errorThrown) {
      console.error("Error submitting answers:", textStatus, errorThrown);
      // Handle errors (optional)
    }
  });
}

function reloadQuizDetails(preventResubmission) {
  $.ajax({
    url: "{{ route('quizzes.get-details', $quiz->id) }}", // Replace with your details route
    method: "GET",
    success: function(response) {
      // Update quiz details on success (explained below)
      console.log("Quiz details retrieved successfully!");
      
      updateQuizDisplay(response, preventResubmission); // Call function to update display
    },
    error: function(jqXHR, textStatus, errorThrown) {
      console.error("Error fetching quiz details:", textStatus, errorThrown);
      // Handle errors (optional)
    }
  });


  function updateQuizDisplay(quizData) {
    // Update quiz title (optional)
    $('#quizTitle').text(quizData.title);

    // Update user responses (iterate and modify elements)
 $('.user-response').each(function() {
    var questionId = $(this).data('questionId');

    // Check if user response exists for this question in quizData.answers
    if (quizData.answers && quizData.answers[questionId]) {
      var answer = quizData.answers[questionId];

      if ($(this).find('input[type="radio"]').length > 0) {
        $(this).find('input[checked]').prop('checked', false); // Uncheck all radio buttons first
        $(this).find('input[value="' + answer + '"]').prop('checked', true);
      } else if ($(this).find('textarea').length > 0) {
        $(this).find('textarea').val(answer);
      }
    }
  });
}

    // Update completion status (optional)
/*     if (quizData.completionPercentage === 100) {
      $('#completionMessage').text('Congratulations! You have completed this quiz.');
    }
 */

  }
  </script>
@endsection

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

