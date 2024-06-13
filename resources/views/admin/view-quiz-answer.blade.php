@extends('admin-layout')

@section('content')
<div class="container-fluid">
    <div style="padding-bottom: 2rem;">
        <h1>Mark Correct Answers for {{ $employee->name }}</h1>
    </div>

    @if (session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
    @endif

    @if (session()->has('error'))
    <div class="alert alert-danger">
        {{ session()->get('error') }}
    </div>
    @endif

    <form action="{{ route('admin.update-quiz-answer', ['quiz' => $quiz->id, 'employee' => $employee->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <h3>Quiz: {{ $quiz->title }}</h3>
            <h4>Attempt: {{ $attempts }}/{{ $quiz->attempt_limit }}</h4>
        </div>

        @foreach ($quiz->questions as $question)
        <div class="mb-3">
            <label for="question-{{ $question->id }}" class="form-label" style="font-size: 1.2rem; font-weight: bold;">
                {{ $loop->iteration }}: {{ $question->question }}
            </label>

            <div>
                @if ($question->type == 'multiple_choice' || $question->type == 'checkbox')
                    @php
                        $answerOptions = json_decode($question->answer_options, true) ?? [];
                        $correctAnswers = json_decode($question->correct_answer, true) ?? [];
                        $employeeAnswer = $userResponses[$question->id]->answer ?? [];
                    @endphp
                    @foreach ($answerOptions as $optionIndex => $optionText)
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="answer-{{ $question->id }}-{{ $optionIndex }}" name="correct_answers[{{ $question->id }}][]" value="{{ $optionText }}" {{ in_array($optionText, $correctAnswers) ? 'checked' : '' }}>
                            <label class="form-check-label" for="answer-{{ $question->id }}-{{ $optionIndex }}">
                                {{ $optionText }}
                                @if(in_array($optionText, (array)$employeeAnswer)) 
                                    <i class="fas fa-check-circle"></i>
                                @endif
                            </label>
                        </div>
                    @endforeach
                @elseif ($question->type == 'short_answer')
                    @php
                        $correctAnswer = is_string($question->correct_answer) ? $question->correct_answer : '';
                        $employeeAnswer = $userResponses[$question->id]->answer ?? '';
                    @endphp
                    <input type="text" class="form-control" id="answer-{{ $question->id }}" name="correct_answers[{{ $question->id }}]" value="{{ $correctAnswer }}">
                    @if($employeeAnswer)
                        <p>Employee Answer: {{ $employeeAnswer }} <i class="fas fa-check-circle"></i></p>
                    @endif
                @endif
            </div>
        </div>
        @endforeach

        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Update Correct Answers</button>
        </div>
    </form>

    <style>
        .form-check-input {
            transform: scale(1.1);
            margin-right: 10px;
            position: relative;
            border: 1px solid #b8bdc2;
        }

        .form-check {
            display: flex;
            align-items: center;
        }

        /* Define icon styles */
        .fas.fa-check-circle {
            color: green;
            margin-left: 5px;
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
                    // Add icon for chosen answer
                    if ($(this).find('.fas.fa-check-circle').length === 0) {
                        $(this).find('.form-check-label').append('<i class="fas fa-check-circle"></i>');
                    }
                }
            });
        }
    </script>
</div>
@endsection
