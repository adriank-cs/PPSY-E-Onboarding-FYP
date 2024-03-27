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
                        <form class="row align-items-center">
                            <div class="col-md-9 mb-3">
                                <label for="search" class="form-label"></label>
                                <input type="text" id="search" name="search" placeholder="Type your questions here!" class="form-control">
                            </div>
                            <div class="col-md-3 mb-3 text-md-end">
                                <!-- submit button for search -->
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="bi bi-search"></i> Search
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Answered questions header -->
            <div class="row mt-3">
                <div class="col-md-12 mb-3">
                    <h1>Answered Questions</h1>
                </div>
            </div>

            <!-- 2x2 cards for answered questions -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="card twoxtwo-gray-card">
                        <div class="card-body">
                            <!-- Card content goes here -->
                            <h5 class="card-title">Question 1</h5>
                            <p class="card-text">Answer 1</p>

                            <!-- Additional Card content goes here -->
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Nested Question 1</h5>
                                    <p class="card-text">Nested Answer 1 with longer content.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card twoxtwo-gray-card">
                        <div class="card-body">
                            <!-- Card content goes here -->
                            <h5 class="card-title">Question 2</h5>
                            <p class="card-text">Answer 2</p>

                            <!-- Additional Card content goes here -->
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Nested Question 2</h5>
                                    <p class="card-text">Nested Answer 2</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="card twoxtwo-gray-card">
                        <div class="card-body">
                            <!-- Card content goes here -->
                            <h5 class="card-title">Question 3</h5>
                            <p class="card-text">Answer 3</p>

                            <!-- Additional Card content goes here -->
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Nested Question 3</h5>
                                    <p class="card-text">Nested Answer 3</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card twoxtwo-gray-card">
                        <div class="card-body">
                            <!-- Card content goes here -->
                            <h5 class="card-title">Question 4</h5>
                            <p class="card-text">Answer 4</p>

                            <!-- Additional Card content goes here -->
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Nested Question 4</h5>
                                    <p class="card-text">Nested Answer 4</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
