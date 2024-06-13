@extends('admin-layout')

@section('content')

<div class="container-fluid">
    <h1 class="fw-semibold mb-4">Find Colleagues</h1>
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-4">
                <div class="input-group mb-3">
                    <span class="input-group-text">
                        <i class="ti ti-search"></i>
                    </span>
                    <input type="text" class="form-control" id="searchField" placeholder="Enter Colleague's Name"
                        aria-label="Enter Colleague's Name" aria-describedby="searchButton">
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="row colleagues-container">
        @foreach($adminUsers as $user)
            <div class="col-md-4">
                <div class="card colleagues-card position-relative">
                    <a href="{{ route('admin.colleague_details', $user->id) }}"
                        class="stretched-link"></a>
                    <div class="card-body colleagues-card-body">
                        <div class="row colleagues-image">
                            <img src="{{ Storage::url($user->profile_picture) }}" alt="Photo"
                                class="img-fluid rounded-circle profile-picture-colleagues">
                        </div>
                        <div class="row colleagues-title">
                            <div class="col-md-12">
                                <h5 class="card-title">{{ $user->name }}</h5>
                                <p>Colleague ID: {{ $user->employee_id }}</p>
                                <p>Position: {{ $user->position }}</p>
                                <p>Department: {{ $user->dept }}</p>
                                <p class="position-relative" style="z-index: 1;">Email: <a
                                        href="mailto:{{ $user->email }}">{{ $user->email }}</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        @foreach($nonAdminUsers as $user)
            <div class="col-md-4">
                <div class="card colleagues-card position-relative">
                    <a href="{{ route('admin.colleague_details', $user->id) }}"
                        class="stretched-link"></a>
                    <div class="card-body colleagues-card-body">
                        <div class="row colleagues-image">
                            <img src="{{ Storage::url($user->profile_picture) }}" alt="Photo"
                                class="img-fluid rounded-circle profile-picture-colleagues">
                        </div>
                        <div class="row colleagues-title">
                            <div class="col-md-12">
                                <h5 class="card-title">{{ $user->name }}</h5>
                                <p>Colleague ID: {{ $user->employee_id }}</p>
                                <p>Position: {{ $user->position }}</p>
                                <p>Department: {{ $user->dept }}</p>
                                <p class="position-relative" style="z-index: 1;">Email: <a href="mailto:{{ $user->email }}">{{ $user->email }}</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
    $(document).ready(function () {
        var $colleaguesContainer = $('.colleagues-container').masonry({
            itemSelector: '.col-md-4',
            columnWidth: '.col-md-4',
            percentPosition: true
        });

        $('#searchField').on('input', function () {
            var searchValue = $(this).val().toLowerCase();
            $('.colleagues-card').each(function () {
                var nameText = $(this).find('.card-title').text().toLowerCase();
                if (nameText.includes(searchValue)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });

            // Trigger Masonry layout update after hiding/showing elements
            $colleaguesContainer.masonry('layout');
        });
    });

</script>

@endsection
