@extends('admin-layout')
@section('title', 'Admin | Manage Module')
@section('content')

<div class="container-fluid">
    <h1 class="fw-semibold mb-4">Manage Modules</h1>
    <div class="row align-items-center">
        <div class="col-12 col-md-11">
            <div class="input-group mt-2">
                <span class="input-group-text">
                    <i class="ti ti-search"></i>
                </span>
                <input type="text" class="form-control" id="searchField" placeholder="Enter Module Name"
                    aria-label="Enter Module Name" aria-describedby="searchButton">
            </div>
        </div>
        <div class="col-4 col-md-1 mt-2">
            <button type="button" class="btn btn-primary w-100"
                onclick="window.location.href='{{ route('admin.add_module') }}'">Add</button>
        </div>
    </div>
    <br>
    <div class="col-12">
        <div class="row modules-container">
            <div class="col-md-4" style="visibility:hidden;"></div>
            @foreach($modules as $module)
                <div class="col-md-4">
                    <div class="card module-card">
                        <div class="card-body module-card-body">
                            <a href="{{ route('admin.manage_chapter', ['id' => $module->id]) }}">
                                <div class="row module-image">
                                    <img src="{{ $module->image_url }}" alt="Module Photo" class="img-fluid">
                                </div>
                            </a>
                            <div class="row module-title">
                                <div class="col-9">
                                    <a href="{{ route('admin.manage_chapter', ['id' => $module->id]) }}">
                                        <h5 class="card-title">{{ $module->title }}</h5>
                                    </a>
                                </div>
                                <div class="col-1 text-center module-buttons">
                                    <div class="dropdown">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="module-box-icons"><i class="ti ti-checklist"></i></span></a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            <li><a class="dropdown-item"
                                                    href="{{ route('admin.assign_module', ['id' => $module->id]) }}">Assign
                                                    Module</a></li>
                                            <li><a class="dropdown-item"
                                                    href="{{ route('admin.unassign_module', ['id' => $module->id]) }}">Unassign
                                                    Module</a></li>
                                            <li><a class="dropdown-item"
                                                    href="{{ route('admin.configure_duedate', ['id' => $module->id]) }}">Configure
                                                    Due Date</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-1 text-center module-buttons">
                                    <a href="{{ route('admin.edit_module', ['id' => $module->id]) }}">
                                        <span class="module-box-icons"><i class="ti ti-pencil"></i></span>
                                    </a>
                                </div>
                                <div class="col-1 text-center module-buttons">
                                    <a href="#"
                                        onclick="confirmDelete('{{ route('admin.delete_module', ['id' => $module->id]) }}')">
                                        <span class="module-box-icons"><i class="ti ti-trash"></i></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Modal for delete confirmation -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this module?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteButton">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        var $modulesContainer = $('.modules-container').masonry({
            itemSelector: '.col-md-4',
            columnWidth: '.col-md-4',
            percentPosition: true
        });

        $('#searchField').on('input', function () {
            var searchValue = $(this).val().toLowerCase();
            $('.module-card').each(function () {
                var nameText = $(this).find('.card-title').text().toLowerCase();
                if (nameText.includes(searchValue)) {
                    $(this).closest('.col-md-4').show();
                } else {
                    $(this).closest('.col-md-4').hide();
                }
            });

            // Trigger Masonry layout update after hiding/showing elements
            $modulesContainer.masonry('layout');
        });
    });

    var deleteUrl = '';

    function confirmDelete(url) {
        deleteUrl = url;
        var deleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        deleteModal.show();
    }

    document.getElementById('confirmDeleteButton').addEventListener('click', function() {
        window.location.href = deleteUrl;
    });
</script>

@endsection
