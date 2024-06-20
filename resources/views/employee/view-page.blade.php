@extends('employee-layout')
@section('title', 'Employee | Modules')
@section('content')

<style>
    .quiz-question {
        margin-bottom: 20px;
    }

    .quiz-question h3 {
        font-size: 18px;
        margin-bottom: 10px;
    }

    .answer-options ul {
        list-style-type: none;
        padding: 0;
    }

    .answer-options li {
        margin-bottom: 10px;
    }

    .quiz-feedback {
        font-size: 16px;
        font-weight: bold;
    }

    .quiz-feedback.correct {
        color: green;
    }

    .quiz-feedback.wrong {
        color: red;
    }
</style>

<div class="custom-container-fluid">

    <div class="row">
        <div class="col-md-12">
            <div class="module-title-page">
                <h5 class="page-title">{{ $item->chapter->module->title }}</h5>
                <i class="ti ti-list-check" id="openSidebar"></i>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="chapter-name">
                <h3>{{ $item->chapter->title }}</h3>
            </div>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-md-12">
            <div class="viewpage-name">
                @if($quiz)
                    <h2>Quiz: {{ $quiz->title }}</h2>
                @else
                    <h3>{{ $item->title }}</h3>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="viewpage-content">
                @if($quiz)
                    <form id="quizForm" action="{{ route('employee.submit_quiz', ['quizId' => $quiz->id]) }}" method="POST">
                        @csrf
                        <div class="quiz-questions">
                            @foreach($quizQuestions as $index => $question)
                                <div class="quiz-question" id="question-{{ $question->id }}">
                                    <h3>Question {{ $index + 1 }}:</h3>
                                    <p><strong>{{ $question->question }}</strong></p>
                                    <div class="answer-options">
                                        <ul>
                                            @php
                                                $answerOptions = is_string($question->answer_options) ? json_decode($question->answer_options, true) : $question->answer_options;
                                            @endphp
                                            @foreach($answerOptions as $optionIndex => $option)
                                                <li>
                                                    @if($question->type == 'multiple_choice')
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]" id="option_{{ $question->id }}_{{ $optionIndex }}" value="{{ $optionIndex }}">
                                                            <label class="form-check-label" for="option_{{ $question->id }}_{{ $optionIndex }}">
                                                                {{ $option }}
                                                            </label>
                                                        </div>
                                                    @elseif($question->type == 'checkbox')
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="answers[{{ $question->id }}][]" id="option_{{ $question->id }}_{{ $optionIndex }}" value="{{ $optionIndex }}">
                                                            <label class="form-check-label" for="option_{{ $question->id }}_{{ $optionIndex }}">
                                                                {{ $option }}
                                                            </label>
                                                        </div>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                        @if($question->type == 'short_answer')
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="answers[{{ $question->id }}]" placeholder="Type your answer here">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <hr>
                            @endforeach
                        </div>
                        <button type="submit" class="btn btn-primary fixed-bottom-button">Submit Quiz</button>
                    </form>
                @else
                    {!! $item->content !!}

                    @if(!empty($pdfAttachments))
                        <div class="row">
                            <div class="col-md-12">
                                <h5>PDF Attachments:</h5>
                                <ul>
                                    @foreach($pdfAttachments as $pdf)
                                        <li><a class="pdf-link" data-url="{{ $pdf['url'] }}">{{ $pdf['name'] }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <button type="button" class="btn btn-primary fixed-bottom-button" id="markAsCompletedButton" data-item-id="{{ $item->id }}">Mark As Completed</button>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div id="mySidebar" class="sidebar-page-custom">
        <a href="javascript:void(0)" class="closebtn" id="closeSidebar">&times;</a>
        @foreach($chapters as $chapter)
            <div class="chapter-box">
                <div class="chapter-box-title">
                    <span>{{ $chapter->title }}</span>
                    <span class="chapter-box-toggle"><i class="ti ti-caret-down"></i></span>
                </div>
                <div class="chapter-details-container">
                    <div class="chapter-box-details">
                        <div>{{ $items->has($chapter->id) ? $items[$chapter->id]->count() : 0 }} page(s)</div>
                    </div>
                    <div class="chapter-box-details">
                        <div class="justify-description">{{ $chapter->description }}</div>
                    </div>
                    <div class="chapter-page-details">
                        @if($items->has($chapter->id))
                            @foreach($items[$chapter->id] as $item)
                                <div class="sidebar-custom-page-list">
                                    <span>
                                        <input type="checkbox" {{ $item->itemProgress && $item->itemProgress->IsCompleted ? 'checked' : '' }} disabled>
                                    </span>
                                    <span>
                                        <a href="{{ route('employee.view_page', ['itemId' => $item->id]) }}">{{ $item->title }}</a>
                                    </span>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById("openSidebar").onclick = function () {
            document.getElementById("mySidebar").classList.add('open');
        }

        document.getElementById("closeSidebar").onclick = function () {
            document.getElementById("mySidebar").classList.remove('open');
        }

        // Add event listener to toggle the visibility of pages under each chapter
        document.querySelectorAll('.chapter-box-toggle').forEach(toggle => {
            toggle.addEventListener('click', function () {
                const chapterBox = this.closest('.chapter-box');
                const detailsContainer = chapterBox.querySelector('.chapter-details-container');
                const pageDetails = detailsContainer.querySelector('.chapter-page-details');

                if (pageDetails.style.display === 'block') {
                    pageDetails.style.display = 'none';
                    this.classList.remove('expanded');
                } else {
                    // Close all other chapter details
                    document.querySelectorAll('.chapter-page-details').forEach(detail => {
                        detail.style.display = 'none';
                    });
                    // Reset all other toggles
                    document.querySelectorAll('.chapter-box-toggle').forEach(icon => {
                        icon.classList.remove('expanded');
                    });

                    pageDetails.style.display = 'block';
                    this.classList.add('expanded');
                }
            });
        });

        // Open PDF in modal
        document.querySelectorAll('.pdf-link').forEach(link => {
            link.addEventListener('click', function () {
                const pdfUrl = this.getAttribute('data-url');
                document.getElementById('pdfFrame').src = pdfUrl;
                $('#pdfModal').modal('show');
            });
        });

        // Handle Mark As Completed button click
        var markAsCompletedButton = document.getElementById('markAsCompletedButton');
        if (markAsCompletedButton) {
            markAsCompletedButton.addEventListener('click', function() {
                var itemId = this.getAttribute('data-item-id');
                fetch(`{{ url('employee/mark-completed') }}/${itemId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        alert('Failed to get the redirection URL');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while marking the item as completed');
                });
            });
        }

        // Handle form submission
        var quizForm = document.getElementById('quizForm');
    if (quizForm) {
        quizForm.addEventListener('submit', function(event) {
            event.preventDefault();
            var form = this;
            var formData = new FormData(form);
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                // Hide previous feedback
                document.querySelectorAll('.quiz-question h3').forEach(h3 => {
                    h3.style.color = ''; // Reset color
                    const textContent = h3.textContent;
                    const colonIndex = textContent.indexOf(':');
                    h3.textContent = textContent.slice(0, colonIndex + 1); // Reset text to only "Question X:"
                });

                data.feedback.forEach((feedback, index) => {
                    var questionElement = document.getElementById(`question-${feedback.questionId}`);
                    questionElement.querySelector('h3').style.color = feedback.isCorrect ? 'green' : 'red';
                    questionElement.querySelector('h3').textContent += feedback.isCorrect ? ' - Correct' : ' - Wrong';
                });
                
                if (data.passed && data.redirect) {
                    window.location.href = data.redirect;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while submitting the quiz: ' + (error.error || error.message || 'Unknown error.'));
            });
        });
    }
    });
</script>

@endsection