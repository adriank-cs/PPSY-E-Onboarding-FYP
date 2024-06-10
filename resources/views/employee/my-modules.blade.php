@extends('employee-layout')

@section('content')

<div class="container-fluid">
    <h1 class="fw-semibold mb-4">My Modules</h1>
    <div class="row">
        <div class="col-md-12">
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
    </div>
    <br>
    <div class="row modules-container">
        @foreach($modules as $module)

            <div class="col-md-4">

                <div class="card module-card">
                    <div class="card-body module-card-body">
                        <a
                            href="{{ route('employee.check_item_progress', ['moduleId' => $module->id]) }}">
                            <div class="row module-image">
                                <img src="{{ $module->image_url }}" alt="Module Photo">
                            </div>
                        </a>
                        <div class="row module-title">
                            <div class="col-md-12"><a
                                    href="{{ route('employee.check_item_progress', ['moduleId' => $module->id]) }}">
                                    <h5 class="card-title">{{ $module->title }}</h5>
                                </a>
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


</script>

@endsection
