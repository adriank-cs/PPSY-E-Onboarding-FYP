@extends('employee-layout')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="text-left">
                <div class="row">
                    <div class="col-md-3">
                        <h1> Discussion </h1>
                    </div>
                </div>
            </div>

            <!-- entry box and search button -->
            <br>
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <!-- Autocomplete search bar -->
                        <form>
                            <div class="input-group">
                                <input type="text" id="search" name="search" placeholder="Type your questions here!" class="form-control">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search"></i> Search
                                </button>
                            </div>
                            <!-- Autocomplete results container -->
                            <div id="autocompleteResults" class="autocomplete-results"></div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- type your own question button -->
            <div class="row">
                <div class="col-md-12 mb-3">
                    <a href="{{ route('discussion.typeown') }}">
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
                                    <a href="{{ route('discussion.postDisplay', ['PostID' => $randomPosts[$index]->PostID]) }}">
                                        <div class="card twoxtwo-gray-card">
                                            <div class="card-body">
                                                <!-- Card content goes here -->
                                                @php $postId = $randomPosts[$index]->user_id; @endphp
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h5 class="card-title truncate-text" style="width: 70%;">Asked by: {{ isset($users[$postId]) ? $users[$postId] : 'Unknown User' }}</h5>
                                                </div>
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h5 class="card-title">{{ $randomPosts[$index]->title }}</h5>
                                                    </div>
                                                </div>
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

    /* Ensure the cards have the same height */
    .twoxtwo-gray-card {
        height: 100%;
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
</style>
@endpush