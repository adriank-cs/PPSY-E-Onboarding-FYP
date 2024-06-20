<!-- module-details.blade.php -->
@extends('employee-layout')

@section('content')
<div class="container-fluid">
    <h1>{{ $module->title }}</h1>
    <img src="{{ $module->image_url }}" class="img-fluid mb-3" alt="{{ $module->title }}">
    <h3>Module Questions</h3>

    <div class="modal fade" id="submitConfirmationModal" tabindex="-1" aria-labelledby="submitConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="submitConfirmationModalLabel">Confirm Submission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to submit your answers for this module?
                </div>
               <!--  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                    <!--<button type="submit" class="btn btn-primary">Confirm</button> !-->
<!--                     <a href="{{ route('employee.onboarding-home-page') }}">
                        <button>Confirm Submission</button>
                    </a> -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Confirm Submission</button>
                </div>


                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('modules.submit-answers', $module->id) }}" method="POST">
        @csrf

        @foreach ($module->questions as $question)
            <div class="mb-3">
                @if ($question->type === 'multiple_choice')
                    <label for="question-{{ $question->id }}" class="form-label">{{ $question->question }}</label>
                    @if (isset($userResponses[$question->id]))
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="question-{{ $question->id }}-option1" name="answers[{{ $question->id }}]" value="option1" {{ ($userResponses[$question->id]->answer ?? '') === 'option1' ? 'checked' : '' }} disabled>
                            <label class="form-check-label" for="question-{{ $question->id }}-option1">Option 1</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="question-{{ $question->id }}-option2" name="answers[{{ $question->id }}]" value="option2" {{ ($userResponses[$question->id]->answer ?? '') === 'option2' ? 'checked' : '' }} disabled>
                            <label class="form-check-label" for="question-{{ $question->id }}-option2">Option 2</label>
                        </div>
                    @else
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="question-{{ $question->id }}-option1" name="answers[{{ $question->id }}]" value="option1">
                            <label class="form-check-label" for="question-{{ $question->id }}-option1">Option 1</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="question-{{ $question->id }}-option2" name="answers[{{ $question->id }}]" value="option2">
                            <label class="form-check-label" for="question-{{ $question->id }}-option2">Option 2</label>
                        </div>
                    @endif
                @elseif ($question->type === 'structured')
                    <label for="question-{{ $question->id }}" class="form-label">{{ $question->question }}</label>
                    @if (isset($userResponses[$question->id]))
                        <textarea class="form-control" id="question-{{ $question->id }}" name="answers[{{ $question->id }}]" rows="3" readonly>{{ $userResponses[$question->id]->answer ?? '' }}</textarea>
                    @else
                        <textarea class="form-control" id="question-{{ $question->id }}" name="answers[{{ $question->id }}]" rows="3"></textarea>
                    @endif
                @endif
            </div>
        @endforeach

        <button type="submit" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#submitConfirmationModal">Submit Answers</button>
    </form>

    <div class="mt-4">
        <h2>Progress: {{ $completionPercentage }}%</h2>
        <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: {{ $completionPercentage }}%;" aria-valuenow="{{ $completionPercentage }}" aria-valuemin="0" aria-valuemax="100">
                {{ $completionPercentage }}% Complete
            </div>
        </div>
    </div>

    @if ($completionPercentage === 100)
        <p class="mt-3 text-success">Congratulations! You have completed this module.</p>
    @endif
</div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2K8uLyrgkLjQhpQzLM76PVTawdYWsnCTDXonWtjakJ80Rijt4btk9yyaQGeR" crossorigin="anonymous"></script>

