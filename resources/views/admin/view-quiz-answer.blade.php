@extends('admin-layout')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Answers for "{{ $quiz->title }}" by {{ $employee->name }}</h1>

    @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif

    <h3>Total Result: {{ $totalResult }}/{{ $totalQuestions }}</h3>
    <form id="correct-answers-form" action="{{ route('admin.update-quiz-answer', ['quiz' => $quiz->id, 'employee' => $employee->id]) }}" method="POST">
        @csrf

        @foreach ($quiz->questions as $question)
            <div class="mb-4">
                <label class="form-label" style="margin-top: 2%; margin-bottom: 2%">
                    <strong>{{ $loop->iteration }} : {{ $question->question }}</strong>
                </label>
                <div>
                    <p><strong>Employee's answer:</strong></p>
                    @if ($question->type == 'multiple_choice')
                        @foreach (json_decode($question->answer_options, true) as $optionIndex => $optionText)
                            <div class="form-check d-flex align-items-center mb-2" data-question-id="{{ $question->id }}" data-option-id="{{ json_decode($question->answer_option_id)[$optionIndex] }}">
                                <input type="radio" class="form-check-input me-2"
                                       id="question-{{ $question->id }}-option{{ $optionIndex + 1 }}"
                                       name="answers[{{ $question->id }}]" value="{{ $optionText }}"
                                       {{ isset($userResponses[$question->id]) && $userResponses[$question->id]->answer === $optionText ? 'checked' : '' }} disabled>
                                <label class="form-check-label me-3" for="question-{{ $question->id }}-option{{ $optionIndex + 1 }}">
                                    {{ $optionText }}
                                </label>
                                <input type="checkbox" class="form-check-input ms-2 mark-correct" name="correct_answers[{{ $question->id }}][]" value="{{ json_decode($question->answer_option_id)[$optionIndex] }}" {{ isset($correctAnswers[$question->id]) && in_array(json_decode($question->answer_option_id)[$optionIndex], $correctAnswers[$question->id] ?? []) ? 'checked' : '' }}>
                            </div>
                        @endforeach
                    @elseif ($question->type == 'short_answer')
                        <div class="d-flex align-items-center">
                            <textarea class="form-control mb-2" rows="3" readonly name="employee_answers[{{ $question->id }}]">{{ $userResponses[$question->id]->answer ?? '' }}</textarea>
                            <input type="checkbox" class="form-check-input ms-4 mark-correct-text" data-question-id="{{ $question->id }}" {{ isset($correctAnswers[$question->id]) ? 'checked' : '' }}>
                        </div>
                        <p><strong>Correct answer:</strong></p>
                        <input type="text" class="form-control correct-answer-text" name="correct_answers[{{ $question->id }}]" value="{{ is_array($correctAnswers[$question->id] ?? null) ? implode(", ", $correctAnswers[$question->id]) : '' }}" style="display: {{ isset($correctAnswers[$question->id]) ? 'none' : 'block' }}">
                    @elseif ($question->type == 'checkbox')
                        @foreach (json_decode($question->answer_options, true) as $optionIndex => $optionText)
                            <div class="form-check d-flex align-items-center mb-2" data-question-id="{{ $question->id }}" data-option-id="{{ json_decode($question->answer_option_id)[$optionIndex] }}">
                                <input type="checkbox" class="form-check-input me-2"
                                       id="question-{{ $question->id }}-option{{ $optionIndex + 1 }}"
                                       name="answers[{{ $question->id }}][]" value="{{ $optionText }}"
                                       {{ isset($userResponses[$question->id]) && in_array($optionText, is_string($userResponses[$question->id]->answer) ? json_decode($userResponses[$question->id]->answer, true) : $userResponses[$question->id]->answer ?? []) ? 'checked' : '' }} disabled>
                                <label class="form-check-label me-3" for="question-{{ $question->id }}-option{{ $optionIndex + 1 }}">
                                    {{ $optionText }}
                                </label>
                                <input type="checkbox" class="form-check-input ms-2 mark-correct" name="correct_answers[{{ $question->id }}][]" value="{{ json_decode($question->answer_option_id)[$optionIndex] }}" {{ isset($correctAnswers[$question->id]) && in_array(json_decode($question->answer_option_id)[$optionIndex], $correctAnswers[$question->id] ?? []) ? 'checked' : '' }}>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        @endforeach

        <input type="hidden" name="correct_answers_json" id="correct_answers_json" value="{{ json_encode($correctAnswers) }}">
        <button type="submit" class="btn btn-primary">Save Correct Answers</button>
    </form>
</div>

<style>
    .form-check-input {
        margin-right: 10px;
    }

    .form-check-label {
        margin-right: 15px;
    }

    .mark-correct, .mark-correct-text {
        margin-left: 2cm; /* Adding 2cm space between the answer option and marking checkbox */
    }

    .answer-option {
        display: flex;
        align-items: center;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const correctAnswers = JSON.parse(document.getElementById('correct_answers_json').value) || {};

        // Update the correctAnswers object based on checkbox states for multiple choice questions
        const updateCorrectAnswers = (questionId, optionId, checked) => {
            if (!correctAnswers[questionId]) {
                correctAnswers[questionId] = [];
            }
            if (checked) {
                correctAnswers[questionId].push(optionId);
            } else {
                const index = correctAnswers[questionId].indexOf(optionId);
                if (index !== -1) {
                    correctAnswers[questionId].splice(index, 1);
                }
            }
            document.getElementById('correct_answers_json').value = JSON.stringify(correctAnswers);
        };

        // Add event listener for marking multiple choice answers
        document.querySelectorAll('.mark-correct').forEach(checkbox => {
            const questionId = checkbox.closest('.form-check').getAttribute('data-question-id');
            const optionId = checkbox.getAttribute('value');

            checkbox.addEventListener('change', function() {
                updateCorrectAnswers(questionId, optionId, this.checked);
            });
        });

        // Add event listener for marking text answers
        document.querySelectorAll('.mark-correct-text').forEach(checkbox => {
            const questionId = checkbox.getAttribute('data-question-id');
            const employeeAnswerTextarea = document.querySelector(`textarea[name="employee_answers[${questionId}]"]`);
            const correctAnswerInput = document.querySelector(`input[name="correct_answers[${questionId}]"]`);

            checkbox.addEventListener('change', function() {
                if (checkbox.checked) {
                    correctAnswers[questionId] = [employeeAnswerTextarea.value.trim()];
                    correctAnswerInput.style.display = 'none';
                } else {
                    if (correctAnswers[questionId]) {
                        delete correctAnswers[questionId];
                    }
                    correctAnswerInput.style.display = 'block';
                }
                document.getElementById('correct_answers_json').value = JSON.stringify(correctAnswers);
            });
        });

        // Add event listener for form submission to include text answers
        document.getElementById('correct-answers-form').addEventListener('submit', function() {
            document.querySelectorAll('.correct-answer-text').forEach(input => {
                const questionId = input.name.match(/\d+/)[0];
                if (!correctAnswers[questionId]) {
                    correctAnswers[questionId] = [input.value.trim()];
                }
            });
            document.getElementById('correct_answers_json').value = JSON.stringify(correctAnswers);
        });
    });
</script>
@endsection
