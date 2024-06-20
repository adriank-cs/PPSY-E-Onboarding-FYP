@extends('employee-layout')
@section('title', 'Employee | Discussion')
@section('content')

<div class="container-fluid">
    <div class="text-left">
        <div class="row">
            <div class="col-md-3">
                <h1>Search Results</h1>
            </div>
        </div>
    </div>

    <!-- Entry box and search button -->
    <br>
    <div class="container-fluid">
        <div class="card">
            <div class="card-body text-center">
                <div class="mb-2">Type to find already asked questions!</div>
                <!-- Search form -->
                <form method="GET" action="{{ route('employee.search') }}">
                    <div class="input-group no-border justify-content-center">
                        <input type="text" id="searchField" name="search" placeholder="Type your questions here!" class="form-control typeahead" style="max-width: 500px;">
                        <button type="submit" class="btn btn-primary" id="search-button">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Matching Questions header -->
    <div class="row mt-3">
        <div class="col-md-12 mb-3">
            <h1>Matching Questions</h1>
            <p>Here are the questions that match your search query.</p>
        </div>
    </div>
    
    <!-- Check if $searchResults exists and contains valid data -->
    <div id="searchResultsContainer">
        @if($searchResults->count() > 0)
            <div class="row">
                @foreach($searchResults as $post)
                    <div class="col-md-6 mb-3 profile-card">
                        <a href="{{ route('employee.postDisplay', ['PostID' => $post->PostID]) }}">
                            <div class="card twoxtwo-gray-card border-gray">
                                <div class="card-body">
                                    @php $userId = $post->UserID; @endphp
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="card-title truncate-text" style="width: 70%;">
                                            <strong>Asked By:</strong> 
                                            @if ($post->is_anonymous)
                                                Your friendly colleague
                                            @else
                                                {{ isset($users[$userId]) ? $users[$userId] : 'Unknown User' }}
                                            @endif
                                        </h5>
                                        <p class="text-muted" style="margin-left: auto;">{{ $post->created_at->format('F d, Y') }}</p>
                                    </div>
                                    <h5 class="card-title">
                                        <strong>Question:</strong>
                                        <span class="card-content">{{ $post->title }}</span>
                                    </h5>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="row">
                <div class="col-md-12">
                    <p>No posts found.</p>
                </div>
            </div>
        @endif
    </div>
</div>

@endsection

@push('styles')
<style>
    .truncate-text {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .twoxtwo-gray-card {
        height: 100%;
        background-color: #f8f9fa;
        border: 1px solid #ccc;
        box-shadow: none;
    }

    .card-content {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        height: 3em;
    }

    .border-gray {
        border-color: #ccc !important;
    }

    .autocomplete-results {
        position: absolute;
        background-color: #fff;
        border: 1px solid #ccc;
        max-height: 200px;
        overflow-y: auto;
        width: calc(100% - 38px);
        margin-top: 10px;
        display: none;
    }

    .autocomplete-item {
        padding: 10px;
        cursor: pointer;
    }

    .autocomplete-item:hover {
        background-color: #f4f4f4;
    }

    .no-border .input-group {
        border: none;
    }

    .d-flex {
        display: flex;
    }

    .align-items-center {
        align-items: center;
    }

    .justify-content-between {
        justify-content: space-between;
    }

    .text-muted {
        font-size: 0.9rem;
        color: #6c757d !important;
    }
</style>
@endpush
