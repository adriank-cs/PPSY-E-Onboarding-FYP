@extends('admin-layout')
@section('title', 'Admin | Answer History')
@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <h3>Answer History</h3>
        </div>
    </div>

    @if($answerHistories->isNotEmpty())
        <div id="history-container">
            @foreach($answerHistories as $history)
                <div class="card mb-3">
                    <div class="card-body">
                        <p class="card-text">{!! $history->content !!}</p>
                        <p class="card-text">
                            Answered by:
                            @if($history->is_anonymous)
                                @if(Auth::id() == $history->UserID)
                                    Your Friendly Colleague (You)
                                @else
                                    Your Friendly Colleague
                                @endif
                            @else
                                {{ Auth::id() == $history->UserID ? 'You' : $history->user->name }}
                            @endif
                        </p>
                        <p class="card-text">Edited At: {{ $history->updated_at ? $history->updated_at->format('M d, Y') : '-' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="row mt-4">
            <div class="col-12">
                <hr>
                <p class="text-muted text-center">No answer history</p>
                <hr>
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.text-muted {
    color: #6c757d !important;
}
</style>
@endpush
