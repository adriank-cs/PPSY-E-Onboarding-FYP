@extends('employee-layout')

@section('content')

<div class="container-fluid">
    <h1 class="fw-semibold mb-4">My Modules</h1>

    <!-- Tabs navigation -->
    <ul class="nav nav-tabs" id="moduleTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="in-progress-tab" data-bs-toggle="tab" data-bs-target="#in-progress"
                type="button" role="tab" aria-controls="in-progress" aria-selected="true">In Progress</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button"
                role="tab" aria-controls="completed" aria-selected="false">Completed</button>
        </li>
    </ul>

    <div class="tab-content" id="moduleTabsContent">
        <!-- In Progress Modules -->
        <div class="tab-pane fade show active" id="in-progress" role="tabpanel" aria-labelledby="in-progress-tab">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-4">
                        <div class="input-group mb-3">
                            <span class="input-group-text">
                                <i class="ti ti-search"></i>
                            </span>
                            <input type="text" class="form-control" id="searchFieldInProgress"
                                placeholder="Enter Module Name" aria-label="Enter Module Name"
                                aria-describedby="searchButton">
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row modules-container" id="inProgressModulesContainer">
                @foreach($inProgressModules as $module)
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
                                    <div class="col-md-12">
                                        <a
                                            href="{{ route('employee.check_item_progress', ['moduleId' => $module->id]) }}">
                                            <h5 class="card-title">{{ $module->title }}</h5>
                                        </a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="progress-container">
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                    role="progressbar" style="width: {{ $module->progress }}%;"
                                                    aria-valuenow="{{ $module->progress }}" aria-valuemin="0"
                                                    aria-valuemax="100">{{ round($module->progress) }}%</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Completed Modules -->
        <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-4">
                        <div class="input-group mb-3">
                            <span class="input-group-text">
                                <i class="ti ti-search"></i>
                            </span>
                            <input type="text" class="form-control" id="searchFieldCompleted"
                                placeholder="Enter Module Name" aria-label="Enter Module Name"
                                aria-describedby="searchButton">
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row modules-container" id="completedModulesContainer">
                @foreach($completedModules as $module)
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
                                    <div class="col-md-12">
                                        <a
                                            href="{{ route('employee.check_item_progress', ['moduleId' => $module->id]) }}">
                                            <h5 class="card-title">{{ $module->title }}</h5>
                                        </a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="progress-container">
                                            <div class="progress">
                                                <div class="progress-bar bg-success"
                                                    role="progressbar" style="width: {{ $module->progress }}%;"
                                                    aria-valuenow="{{ $module->progress }}" aria-valuemin="0"
                                                    aria-valuemax="100">{{ round($module->progress) }}%</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        var $inProgressModulesContainer = $('.modules-container').masonry({
            itemSelector: '.module-card',
            columnWidth: '.col-md-4',
            percentPosition: true
        });


        $('#searchFieldInProgress').on('input', function () {
            var searchValue = $(this).val().toLowerCase();
            $('#inProgressModulesContainer .module-card').each(function () {
                var nameText = $(this).find('.card-title').text().toLowerCase();
                if (nameText.includes(searchValue)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });

            $inProgressModulesContainer.masonry('layout');
        });

        var $completedModulesContainer = $('.modules-container').masonry({
            itemSelector: '.module-card',
            columnWidth: '.col-md-4',
            percentPosition: true
        });

        $('#searchFieldCompleted').on('input', function () {
            var searchValue = $(this).val().toLowerCase();
            $('#completedModulesContainer .module-card').each(function () {
                var nameText = $(this).find('.card-title').text().toLowerCase();
                if (nameText.includes(searchValue)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });

            $completedModulesContainer.masonry('layout');
        });

        // Reinitialize Masonry layout when a tab is shown
        $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            if (e.target.id == 'completed-tab') {
                var $searchField = $('#searchFieldCompleted');
                $searchField.val('.');
                $searchField.trigger('input');
                setTimeout(function() {
                    $searchField.val('');
                    $searchField.trigger('input');
                }, 0);
            } else if (e.target.id == 'in-progress-tab') {
                var $searchField = $('#searchFieldInProgress');
                $searchField.val('.');
                $searchField.trigger('input');
                setTimeout(function() {
                    $searchField.val('');
                    $searchField.trigger('input');
                }, 0);
            }
        });

    });

</script>

@endsection
