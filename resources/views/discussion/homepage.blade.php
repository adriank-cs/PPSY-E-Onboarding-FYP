@extends('employee-layout')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="text-left">
                <div class="row">
                    <div class="col-md-3">
                        <h1> Discussion Test 4 Leon.</h1>
                    </div>
                </div>
            </div>

            <!-- entry box and search button -->
            <br>
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <form class="row align-items-end">
                            <div class="col-md-9 mb-3">
                                <label for="search" class="form-label"></label>
                                <input type="text" id="search" name="search" placeholder="Type your questions here!" class="form-control">
                            </div>
                            <div class="col-md-3 mb-3 text-md-end">
                                <!-- submit button for search -->
                                <button type="submit" class="btn btn-primary btn-sm" style="width: 100%; padding: 6px;">
                                    <i class="bi bi-search"></i> Search
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- type your own question button -->
            <div class="row">
                <div class="col-md-12 mb-3">
                    <button type="submit" class="btn btn-primary btn-sm" style="width: 100%; padding: 10px;">
                        <i class="bi bi-search"></i> Cannot find your questions? Write your own now!
                    </button>
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
                            <div class="card twoxtwo-gray-card">
                                <div class="card-body">
                                    <!-- Card content goes here -->
                                    @php $postId = $randomPosts[$index]->user_id; @endphp
                                    @if(isset($users[$postId]))
                                    <h5 class="card-title">Asked by: {{ $users[$postId] }}</h5>
                                    @else
                                    <h5 class="card-title">Asked by: Unknown User</h5>
                                    @endif

                                    <!-- Additional Card content goes here -->
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $randomPosts[$index]->title }}</h5>
                                            <p class="card-text">{{ $randomPosts[$index]->content }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
