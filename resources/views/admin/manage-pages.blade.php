@extends('admin-layout')

@section('content')

<div class="container-fluid">
    <h2 class="fw-semibold mb-4">{{$chapter->title}}</h2>
    <hr>
    <h2 class="fw-semibold mb-4">Manage Pages</h2>
    <div class="row align-items-center">
        <div class="col-12 col-md-10">
            <div class="input-group mt-2">
                <span class="input-group-text">
                    <i class="ti ti-search"></i>
                </span>
                <input type="text" class="form-control" id="searchField" placeholder="Enter Page Name"
                    aria-label="Enter Page Name" aria-describedby="searchButton">
            </div>
        </div>
        <div class="col-4 col-md-1 mt-2">
            <button type="button" class="btn btn-primary m-1"
                onclick="window.location.href='{{ route('admin.manage_chapter', ['id' => $moduleId]) }}'">Back</button>
        </div>
        <div class="col-4 col-md-1 mt-2">
            <button type="button" class="btn btn-primary m-1"
                onclick="window.location.href='{{ route('admin.add_page', ['chapterId' => $chapterId]) }}'">Add</button>
        </div>
    </div>

    <br>
    <div id="sortable" class="col-md-12">
        @foreach($pages as $page)
        <div class="col-md-12 profile-card" data-id="{{ $page->id }}">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-9">
                            <h5 class="card-title">{{ $page->title }}</h5>
                        </div>
                        <div class="col-md-3 links-cards">
                            <a href="{{route('admin.view_page', ['id' => $page->id])}}" class="card-link">View</a>
                            <a href="{{ route('admin.edit_page', ['id' => $page->id]) }}" class="card-link">Edit</a>
                            <a href="#" class="card-link" onclick="confirmDelete('{{ route('admin.delete_page', ['id' => $page->id]) }}')">Delete</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Modal for delete confirmation -->
<div class="modal fade confirmation-modal" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this page?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteButton">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for success and error alerts -->
<div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="alertModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="alertModalBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#searchField').on('input', function () {
            var searchValue = $(this).val().toLowerCase();
            $('.profile-card').each(function () {
                var nameText = $(this).find('.card-title').text().toLowerCase();
                if (nameText.includes(searchValue)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        $('#sortable').sortable({
            update: function (event, ui) {
                var order = $(this).sortable('toArray', { attribute: 'data-id' });
                $.ajax({
                    url: '{{ route('admin.update_page_order') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        order: order
                    },
                    success: function (response) {
                        showAlertModal('Success', 'Order updated successfully');
                    },
                    error: function (error) {
                        showAlertModal('Error', 'Error updating order');
                    }
                });
            }
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

    function showAlertModal(title, message) {
        document.getElementById('alertModalLabel').textContent = title;
        document.getElementById('alertModalBody').textContent = message;
        var alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
        alertModal.show();
    }
</script>

@endsection
