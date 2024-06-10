@extends('admin-layout')

@section('content')

<div class="container-fluid">
    <h1 class="fw-semibold mb-4">Manage Modules</h1>
    <div class="row">
        <div class="col-md-11">
            <div class="col-md-4">
                <div class="input-group mb-3">
                    <span class="input-group-text">
                        <i class="ti ti-search"></i>
                    </span>
                    <input type="text" class="form-control" id="searchField" placeholder="Enter Module Name"
                        aria-label="Enter Module Name" aria-describedby="searchButton">
                </div>
            </div>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-primary m-1"
                onclick="window.location.href='{{ route('admin.add_module') }}'">Add</button>
        </div>
    </div>
    <br>
    <div class="row modules-container">
        @foreach($modules as $module)

            <div class="col-md-4">

                <div class="card module-card">
                    <div class="card-body module-card-body">
                        <a href="{{ route('admin.manage_chapter', ['id' => $module->id]) }}">
                            <div class="row module-image">
                                <img src="{{ $module->image_url }}" alt="Module Photo">
                            </div>
                        </a>
                        <div class="row module-title">
                        <div class="col-md-9"><a href="{{ route('admin.manage_chapter', ['id' => $module->id]) }}">
                                <h5 class="card-title">{{ $module->title }}</h5></a>
                            </div>
                            <div class="col-md-1 text-center module-buttons">
                                <a
                                    href="{{ route('admin.assign_module', ['id' => $module->id]) }}"><span
                                        class="module-box-icons"><i class="ti ti-checklist"></i></span></a>
                            </div>
                            <div class="col-md-1 text-center module-buttons">
                                <a
                                    href="{{ route('admin.edit_module', ['id' => $module->id]) }}"><span
                                        class="module-box-icons"><i class="ti ti-pencil"></i></span></a>
                            </div>
                            <div class="col-md-1 text-center module-buttons">
                                <a href="#"
                                    onclick="confirmDelete('{{ route('admin.delete_module', ['id' => $module->id]) }}')"><span
                                        class="module-box-icons"><i class="ti ti-trash"></i></span></a>
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
    var $modulesContainer = $('.modules-container').masonry({
        itemSelector: '.module-card',
        columnWidth: '.col-md-4',
        percentPosition: true
    });

    $('#searchField').on('input', function () {
        var searchValue = $(this).val().toLowerCase();
        $('.module-card').each(function () {
            var nameText = $(this).find('.card-title').text().toLowerCase();
            var idText = $(this).find('.card-subtitle').text().toLowerCase();
            if (nameText.includes(searchValue) || idText.includes(searchValue)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });

        // Trigger Masonry layout update after hiding/showing elements
        $modulesContainer.masonry('layout');
    });
});

    function confirmDelete(url) {
        if (confirm('Are you sure you want to delete this module?')) {
            // If the user clicks "OK", redirect to the delete URL
            window.location.href = url;
        }
    }

</script>

@endsection
