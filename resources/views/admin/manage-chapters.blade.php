@extends('admin-layout')
@section('title', 'Admin | Manage Module')
@section('content')

<div class="container-fluid">
<h2 class="fw-semibold mb-4">{{$module->title}}</h2>
<hr>
    <h2 class="fw-semibold mb-4">Manage Chapters</h2>

    <div class="row align-items-center">
        <div class="col-12 col-md-10">
            <div class="input-group mt-2">
                <span class="input-group-text">
                    <i class="ti ti-search"></i>
                </span>
                <input type="text" class="form-control" id="searchField" placeholder="Enter Chapter Name"
                    aria-label="Enter Chapter Name" aria-describedby="searchButton">
            </div>
        </div>
        <div class="col-4 col-md-1 mt-2">
            <button type="button" class="btn btn-primary m-1"
                onclick="window.location.href='{{ route('admin.manage_modules') }}'">Back</button>
        </div>
        <div class="col-4 col-md-1 mt-2">
            <button type="button" class="btn btn-primary m-1"
                onclick="window.location.href='{{ route('admin.add_chapter', ['moduleId' => $moduleId]) }}'">Add</button>
        </div>
    </div>
    <br>
    @foreach($chapters as $chapter)
        <div class="col-md-12 profile-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-9">
                            <h5 class="card-title">{{ $chapter->title }}</h5>
                        </div>
                        <div class="col-md-3 links-cards">
                            <a href="{{ route('admin.manage_page', ['id' => $chapter->id]) }}"
                                class="card-link">Configure</a>
                            <a href="{{ route('admin.edit_chapter', ['id' => $chapter->id]) }}"
                                class="card-link">Edit</a>
                            <a href="#" class="card-link"
                                onclick="confirmDelete('{{ route('admin.delete_chapter', ['id' => $chapter->id]) }}')">Delete</a>
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
        if (confirm('Are you sure you want to delete this chapter?')) {
            // If the user clicks "OK", redirect to the delete URL
            window.location.href = url;
        }
    }

</script>

@endsection
