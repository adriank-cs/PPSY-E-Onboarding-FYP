@extends('employee-layout')
@section('title', 'Employee | Discussion')
@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <h3>Post History</h3>
        </div>
    </div>

    @if($postHistories->isNotEmpty())
        <div id="history-container">
            @foreach($postHistories as $history)
                <div class="card card-post-details mb-3">
                    <div class="card-body">
                        <h5 class="card-title">{{ $history->title }}</h5>
                        <p class="card-text">{!! $history->content !!}</p>
                        <p class="card-text">Asked by: {{ Auth::id() == $history->UserID ? 'You' : $history->user->name }}</p>
                        <p class="card-text">Edited At: {{ $history->updated_at ? $history->updated_at->format('M d, Y') : '-' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="row mt-4">
            <div class="col-12">
                <hr>
                <p class="text-muted text-center">No post history</p>
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
