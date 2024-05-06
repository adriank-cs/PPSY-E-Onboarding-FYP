@extends('admin-layout')

@section('content')

<div class="container-fluid">
    <h1 class="fw-semibold mb-4">Manage Pages</h1>
    <div class="row">
        <div class="col-md-11">
            <div class="col-md-4">
                <div class="input-group mb-3">
                    <span class="input-group-text">
                        <i class="ti ti-search"></i>
                    </span>
                    <input type="text" class="form-control" id="searchField" placeholder="Enter Page Name"
                        aria-label="Enter Page Name" aria-describedby="searchButton">
                </div>
            </div>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-primary m-1"
                onclick="window.location.href='{{ route('admin.add_pages', ['moduleId' => $moduleId]) }}'">Add</button>
        </div>
    </div>
    <br>
    @foreach($pages as $page)
    <div class="col-md-12 profile-card">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-9">
                        <h5 class="card-title">{{ $page->title }}</h5>
                    </div>
                    <div class="col-md-3">
                        <a href="#" class="card-link">Edit</a>
                        <a href="#" class="card-link" onclick="">Delete</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach

</div>

<script>
    $(document).ready(function () {
        $('#searchField').on('input', function () {
            var searchValue = $(this).val().toLowerCase();
            $('.profile-card').each(function () {
                var nameText = $(this).find('.card-title').text().toLowerCase();
                var idText = $(this).find('.card-subtitle').text().toLowerCase();
                if (nameText.includes(searchValue) || idText.includes(searchValue)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });

    function confirmDelete(url) {
        if (confirm('Are you sure you want to delete this account?')) {
            // If the user clicks "OK", redirect to the delete URL
            window.location.href = url;
        }
    }
</script>

@endsection