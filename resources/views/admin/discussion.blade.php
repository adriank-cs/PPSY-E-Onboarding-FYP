@extends('admin-layout')
@section('title', 'Admin | Discussion')
@section('content')

<div class="container-fluid">
    <div class="text-left">
        <div class="row">
            <div class="col-md-3">
                <h1> Discussion </h1>
            </div>
        </div>
    </div>

    <!-- Entry box and search button -->
    <br>
    <div class="container-fluid">
        <div class="card">
            <div class="card-body text-center"> <!-- Center the search bar -->
                <!-- Text above the search bar -->
                <div class="mb-2">Type to find already asked questions!</div>
                <!-- Autocomplete search bar -->
                <form method="GET" action="{{ route('admin.search') }}">
                    <div class="input-group no-border justify-content-center">
                        <input type="text" id="search" name="search" placeholder="Type your questions here!" class="form-control typeahead" style="max-width: 500px;">
                        <button type="submit" class="btn btn-primary" id="search-button">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </div>
                    <!-- Autocomplete results container -->
                    <div id="autocompleteResults" class="autocomplete-results"></div>
                </form>
            </div>
        </div>
    </div>

    <!-- Type your own question button -->
    <div class="row">
        <div class="col-md-12 mb-3">
            <a href="{{ route('admin.create-post') }}">
                <button type="submit" class="btn btn-primary btn-sm" style="width: 100%; padding: 10px;">
                    <i class="bi bi-search"></i> Cannot find your questions? Write your own now!
                </button>
            </a>
        </div>
    </div>

    <!-- Existing Questions header -->
    <div class="row mt-3">
        <div class="col-md-12 mb-3">
            <h1>Existing Questions</h1>
            <p>Check out the existing questions that were asked by our fellow colleagues.</p>
        </div>
    </div>
    
    <!-- Check if $randomPosts exists and contains valid data -->
    @if($randomPosts->count() > 0)
        <!-- 2x2 cards for questions -->
        @for ($i = 0; $i < 2; $i++)
            <div class="row">
                @for ($j = 0; $j < 2; $j++)
                    @php $index = $i * 2 + $j; @endphp
                    @if(isset($randomPosts[$index]))
                        <div class="col-md-6 mb-3">
                            <!-- Wrap each card in an anchor tag -->
                            <a href="{{ route('admin.postDisplay', ['PostID' => $randomPosts[$index]->PostID]) }}">
                                <div class="card twoxtwo-gray-card border-gray discussion-card-shadow">
                                    <div class="card-body">
                                        <!-- Card content goes here -->
                                        @php $userId = $randomPosts[$index]->UserID; @endphp
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="card-title truncate-text" style="width: 70%;">
                                                <strong>Asked By:</strong> 
                                                @if ($randomPosts[$index]->is_anonymous)
                                                    Your friendly colleague
                                                @else
                                                    {{ isset($users[$userId]) ? $users[$userId] : 'Unknown User' }}
                                                @endif
                                            </h5>
                                            <p class="text-muted" style="margin-left: auto;">{{ $randomPosts[$index]->created_at->format('F d, Y') }}</p>
                                        </div>
                                        <h5 class="card-title">
                                            <strong>Question:</strong>
                                            <span class="card-content">{{ $randomPosts[$index]->title }}</span>
                                        </h5>
                                    </div>
                                </div>
                            </a> <!-- Close anchor tag -->
                        </div>
                    @endif
                @endfor
            </div>
        @endfor
    @else
        <div class="row">
            <div class="col-md-12">
                <p>No posts found.</p>
            </div>
        </div>
    @endif
</div>

@endsection

@push('styles')
<style>
    .truncate-text {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Ensure the cards have the same height */
    .twoxtwo-gray-card {
        height: 100%;
        background-color: #f8f9fa; /* Light grey background */
        border: 1px solid #ccc; /* Grey border */
        box-shadow: none; /* Remove any box shadow */
    }

    /* Set a fixed height for card content and truncate with ellipsis if content overflows */
    .card-content {
        display: -webkit-box;
        -webkit-line-clamp: 2; /* number of lines to show */
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        height: 3em; /* Adjust this to limit the height */
    }

    /* Remove the pink/purple border */
    .border-gray {
        border-color: #ccc !important;
    }

    /* Style for autocomplete results */
    .autocomplete-results {
        position: absolute;
        background-color: #fff;
        border: 1px solid #ccc;
        max-height: 200px;
        overflow-y: auto;
        width: calc(100% - 38px); /* Adjust width according to your design */
        margin-top: 10px; /* Adjust margin according to your design */
        display: none; /* Initially hide autocomplete results */
    }

    .autocomplete-item {
        padding: 10px;
        cursor: pointer;
    }

    .autocomplete-item:hover {
        background-color: #f4f4f4;
    }

    /* Remove outer border of input group */
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
