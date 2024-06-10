@extends('employee-layout')
@section('content')

<div class="container-fluid">
    <h1><span style="font-size: 2.5rem;">{{ $quiz->title }}</span></h1>
    <h4>Attempt: {{ $attempts }}/{{ $quiz->attempt_limit }}</h4>

    <!-- Add by aifei for quiz attemp -->
    @if(session()->has('error'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
    @endif

    @if(session()->has('success'))
        <div class="alert alert-success mt-4" role="alert">
            {{ session()->get('success') }}
        </div>
    @endif

    @if($attempts >= $quiz->attempt_limit)
        <div class="alert alert-warning">
            You have reached the maximum number of attempts for this quiz.
        </div>
    @endif

<form action="{{ route('quizzes.submit-answers', $quiz->id) }}" method="POST">
        @csrf

        <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel"
            aria-hidden="true">
        <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel"
            aria-hidden="true">
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
                        <button type="submit" class="btn btn-secondary" data-bs-toggle="modal"
                            data-bs-target="#confirmationModal" data-prevent-resubmission>Confirm Submit</button>
                        <button type="submit" class="btn btn-secondary" data-bs-toggle="modal"
                            data-bs-target="#confirmationModal" data-prevent-resubmission>Confirm Submit</button>
                    </div>
                </div>
            </div>
        </div>

        <?php
$questionCount = 1; // Initialize counter for question number
$questionCount = 1; // Initialize counter for question number
        ?>

        @foreach ($quiz->questions as $question)
            <div class="mb-3">
                @if ($question->type === 'multiple_choice')
                    <label class="form-check-label" for="question-{{ $question->id }}" class="form-label"
                        style="margin-top: 2%; margin-bottom: 2%">
                        <strong><span
                                style="font-size: 1.2rem; background-color: #6A1043; color: white; padding: 5px 20px; border-radius: 5px;">{{ $questionCount++ }}
                                : {{ $question->question }}</span></strong>
                    </label>
                    <div>
                        <p>Your answer:</p>
                        @foreach (json_decode($question->answer_options) as $optionIndex => $optionText)
                            <div class="form-check">
                                <input type="radio" class="form-check-input"
                                    id="question-{{ $question->id }}-option{{ $optionIndex + 1 }}"
                                    name="answers[{{ $question->id }}]" value="{{ $optionText }}" {{ $attempts >= $quiz->attempt_limit ? 'readonly' : '' }}
                                    {{ isset($userResponses[$question->id]) && $userResponses[$question->id]->answer === $optionText ? 'checked' : '' }}>
                                <label class="form-check-label"
                                    for="question-{{ $question->id }}-option{{ $optionIndex + 1 }}">{{ $optionText }}</label>
                            </div>
                        @endforeach
                    </div>
                @elseif ($question->type === 'short_answer')
                    <label class="form-check-label" for="question-{{ $question->id }}" class="form-label"
                        style="margin-top: 2%; margin-bottom: 1%; font-size: 1.2rem; background-color: #6A1043; color: white; padding: 5px 20px; border-radius: 5px;">
                        <strong>{{ $questionCount++ }} : {{ $question->question }} </strong>
                    </label>
                    <div>
                        <p>Your answer:</p>
                        <textarea class="form-control" id="question-{{ $question->id }}" name="answers[{{ $question->id }}]"
                            rows="3" {{ $attempts >= $quiz->attempt_limit ? 'readonly' : '' }}>{{ $userResponses[$question->id]->answer ?? '' }}</textarea>
                    </div>
                @elseif ($question->type === 'checkbox')
                    <label class="form-check-label" for="question-{{ $question->id }}" class="form-label"
                        style="margin-top: 2%; margin-bottom: 2%;">
                        <strong><span
                                style="font-size: 1.2rem; background-color: #6A1043; color: white; padding: 5px 20px; border-radius: 5px;">{{ $questionCount++ }}
                                : {{ $question->question }}</span></strong>
                    </label>
                    <div>
                        <p>Your answer:</p>
                        @foreach (json_decode($question->answer_options) as $optionIndex => $optionText)
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input"
                                    id="question-{{ $question->id }}-option{{ $optionIndex + 1 }}"
                                    name="answers[{{ $question->id }}][]" value="{{ $optionText }}" {{ $attempts >= $quiz->attempt_limit ? 'readonly' : '' }} {{ isset($userResponses[$question->id]) && in_array($optionText, $userResponses[$question->id]->answer ?? []) ? 'checked' : '' }}>
                                <label class="form-check-label"
                                    for="question-{{ $question->id }}-option{{ $optionIndex + 1 }}">{{ $optionText }}</label>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach


        @if($attempts < $quiz->attempt_limit)
            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                data-bs-target="#confirmationModal">Submit Answers</button>
        @endif
    </form>
    <br>

</div>

<style>
    .form-check-input {
        transform: scale(1.1);
        /* Adjust scale to make checkbox smaller */
        margin-right: 10px;
        position: relative;
        border: 1px solid #b8bdc2;
        /* Set the checkbox border color */
    }

    .form-check {
        display: flex;
        align-items: center;
    }
</style>

<style>
    .form-check-input {
        transform: scale(1.1);
        /* Adjust scale to make checkbox smaller */
        margin-right: 10px;
        position: relative;
        border: 1px solid #b8bdc2;
        /* Set the checkbox border color */
    }

    .form-check {
        display: flex;
        align-items: center;
    }
</style>

<script>
    $(document).ready(function () {
        $('#submitAnswers').click(function () {
            submitAnswers(); // Call the submitAnswers function here
        });
    });

    function submitAnswers() {
        var userResponses = {};
        $('.user-response').each(function () {
            var questionId = $(this).data('questionId');
            userResponses[questionId] = $(this).find('input[type="radio"]:checked').val() || $(this).find('textarea').val();
        });

        $.ajax({
            url: "{{ route('quizzes.submit-answers', $quiz->id) }}",
            method: "POST",
            data: {
                answers: userResponses
            },
            success: function (response) {
                if (response.success) {
                    updateQuizDisplay(response.data);
                } else {
                    alert('Error submitting quiz!');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error("Error submitting answers:", textStatus, errorThrown);
            }
        });
    }

    function reloadQuizDetails(preventResubmission) {
        $.ajax({
            url: "{{ route('quizzes.get-details', $quiz->id) }}",
            method: "GET",
            success: function (response) {
                console.log("Quiz details retrieved successfully!");
                updateQuizDisplay(response, preventResubmission);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error("Error fetching quiz details:", textStatus, errorThrown);
            }
        });
    }

    function updateQuizDisplay(quizData) {
        $('#quizTitle').text(quizData.title);

        $('.user-response').each(function () {
            var questionId = $(this).data('questionId');
            if (quizData.answers && quizData.answers[questionId]) {
                var answer = quizData.answers[questionId];
                if ($(this).find('input[type="radio"]').length > 0) {
                    $(this).find('input[checked]').prop('checked', false);
                    $(this).find('input[value="' + answer + '"]').prop('checked', true);
                } else if ($(this).find('textarea').length > 0) {
                    $(this).find('textarea').val(answer);
                }
            }
        });
    }
</script>
@endsection
