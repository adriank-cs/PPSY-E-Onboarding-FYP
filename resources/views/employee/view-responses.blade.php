@extends('employee-layout')
<!-- view-responses.blade.phpp -->
@section('content')
<div class="container-fluid">
  <h1>View Quiz Responses</h1>

  @if ($quiz)
    <h2>Quiz: {{ $quiz->title }}</h2>

    @if (count($quiz->questions) > 0)
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Question</th>
            <th>Your Response</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($quiz->questions as $question)
          <tr>
            <td>{{ $question->question }}</td>
            @if (isset($formattedResponses[$question->id]))
              <td>
                @foreach ($formattedResponses[$question->id]['answer'] as $option)
                  {{ $option['text'] }}<br>  @endforeach
              </td>
            @else
              <td>No response submitted yet.</td>
            @endif
          </tr>
          @endforeach
        </tbody>
      </table>
    @else
      <p>There are no questions in this quiz.</p>
    @endif
  @else
    <p>Quiz not found.</p>
  @endif
</div>
@endsection
