@extends('admin-layout')

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
</style>

<div class="custom-container-fluid">

    <div class="row">
        <div class="col-md-12">
            <div class="module-title-page">
                <h5 class="page-title">{{ $module->title }}</h5>
                <i class="ti ti-list-check" id="openSidebar"></i>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="chapter-name">
                <h3>{{ $chapter->title }}</h3>
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
                    <h3>{{ $viewpage->title }}</h3>

                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="viewpage-content">

                @if($quiz)
                    <div class="quiz-questions">
                        @foreach($quizQuestions as $index => $question)
                            <div class="quiz-question">
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
                                                        <input class="form-check-input" type="radio" name="question_{{ $question->id }}" id="option_{{ $question->id }}_{{ $optionIndex }}" value="{{ $optionIndex }}" >
                                                        <label class="form-check-label" for="option_{{ $question->id }}_{{ $optionIndex }}">
                                                            {{ $option }}
                                                        </label>
                                                    </div>
                                                @elseif($question->type == 'checkbox')
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="question_{{ $question->id }}[]" id="option_{{ $question->id }}_{{ $optionIndex }}" value="{{ $optionIndex }}" >
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
                                            <input type="text" class="form-control" name="question_{{ $question->id }}" placeholder="Type your answer here">
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <hr>
                        @endforeach
                    </div>
                @else
                    {!! $viewpage->content !!}

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
                @endif
            </div>
        </div>
    </div>

    <button type="button" class="btn btn-primary fixed-bottom-button" id="nextButton">Next</button>

    <!-- Modal for PDF viewing -->
    <div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pdfModalLabel">PDF Viewer</h5>
                    <button type="button" class="btn btn-primary" id="cancelButton" data-bs-dismiss="modal" aria-label="Close">Close</button>
                </div>
                <div class="modal-body p-0">
                    <iframe id="pdfFrame" src="" style="width: 100%; height: 100vh;" frameborder="0"></iframe>
                </div>
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
                        <div>{{ $pages->has($chapter->id) ? $pages[$chapter->id]->count() : 0 }} page(s)</div>
                    </div>
                    <div class="chapter-box-details">
                        <div class="justify-description">{{ $chapter->description }}</div>
                    </div>
                    <div class="chapter-page-details">
                        @if($pages->has($chapter->id))
                            @foreach($pages[$chapter->id] as $item)
                                <div class="sidebar-custom-page-list">
                                    <span><input type="checkbox" disabled></span>
                                    <span><a href="{{ route('admin.view_page', ['id' => $item->id]) }}">{{ $item->title }}</a></span>
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
    function showErrorModal(message) {
        document.getElementById('errorModalMessage').textContent = message;
        var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
        errorModal.show();
    }

    document.getElementById("openSidebar").onclick = function () {
        document.getElementById("mySidebar").classList.add('open');
    }

    document.getElementById("closeSidebar").onclick = function () {
        document.getElementById("mySidebar").classList.remove('open');
    }

    document.querySelectorAll('.chapter-box-toggle').forEach(toggle => {
        toggle.addEventListener('click', function () {
            const chapterBox = this.closest('.chapter-box');
            const detailsContainer = chapterBox.querySelector('.chapter-details-container');
            const pageDetails = detailsContainer.querySelector('.chapter-page-details');

            if (pageDetails.style.display === 'block') {
                pageDetails.style.display = 'none';
                this.classList.remove('expanded');
            } else {
                document.querySelectorAll('.chapter-page-details').forEach(detail => {
                    detail.style.display = 'none';
                });
                document.querySelectorAll('.chapter-box-toggle').forEach(icon => {
                    icon.classList.remove('expanded');
                });

                pageDetails.style.display = 'block';
                this.classList.add('expanded');
            }
        });
    });

    document.querySelectorAll('.pdf-link').forEach(link => {
        link.addEventListener('click', function () {
            const pdfUrl = this.getAttribute('data-url');
            document.getElementById('pdfFrame').src = pdfUrl;
            $('#pdfModal').modal('show');
        });
    });

    document.getElementById('nextButton').addEventListener('click', function () {
        var itemId = {{ $viewpage->id }};
        fetch('{{ route('admin.next_page', ['itemId' => $viewpage->id]) }}', {
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
                showErrorModal('Failed to get the redirection URL');
            }
        })
        .catch(error => {
            showErrorModal('An error occurred while navigating to the next page');
        });
    });
</script>

@endsection