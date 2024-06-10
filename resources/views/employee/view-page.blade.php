@extends('employee-layout')

@section('content')

<div class="custom-container-fluid">

    <div class="row">
        <div class="col-md-12">
            <div class="module-title-page">
                <h5 class="page-title">{{ $item->chapter->module->title }}</h5>
                <i class="ti ti-list-check" id="openSidebar"></i>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="chapter-name">
                <h3>{{ $item->chapter->title }}</h3>
            </div>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-md-12">
            <div class="viewpage-name">
                <h3>{{ $item->title }}</h3>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="viewpage-content">
                {!! $item->content !!}
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div id="mySidebar" class="sidebar-page-custom">
        <a href="javascript:void(0)" class="closebtn" id="closeSidebar">&times;</a>
        @foreach($chapters as $chapter)
            <div class="chapter-box">
                <div class="chapter-box-title">
                    <span>{{ $chapter->title }}</span>
                    <span class="chapter-box-toggle"><i class="ti ti-caret-down"></i></span>
                </div>
                <div class="chapter-details-container">
                    <div class="chapter-box-details">
                        <div>{{ $chapter->description }}</div>
                        <div>
                            {{ $items->has($chapter->id) ? $items[$chapter->id]->count() : 0 }}
                            page(s)
                        </div>
                    </div>
                    <div class="chapter-page-details">
                        @if($items->has($chapter->id))
                            @foreach($items[$chapter->id] as $item)
                                <div class="sidebar-custom-page-list"><span>
                                    <input type="checkbox"
                                        {{ $item->itemProgress && $item->itemProgress->IsCompleted ? 'checked' : '' }}
                                        disabled></span>
                                    <span><a
                                        href="{{ route('employee.view_page', ['itemId' => $item->id]) }}">{{ $item->title }}</a></span>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
    document.getElementById("openSidebar").onclick = function () {
        document.getElementById("mySidebar").classList.add('open');
    }

    document.getElementById("closeSidebar").onclick = function () {
        document.getElementById("mySidebar").classList.remove('open');
    }

    // Add event listener to toggle the visibility of pages under each chapter
    document.querySelectorAll('.chapter-box-toggle').forEach(toggle => {
        toggle.addEventListener('click', function () {
            const chapterBox = this.closest('.chapter-box');
            const detailsContainer = chapterBox.querySelector('.chapter-details-container');
            const pageDetails = detailsContainer.querySelector('.chapter-page-details');

            if (pageDetails.style.display === 'block') {
                pageDetails.style.display = 'none';
                this.classList.remove('expanded');
            } else {
                // Close all other chapter details
                document.querySelectorAll('.chapter-page-details').forEach(detail => {
                    detail.style.display = 'none';
                });
                // Reset all other toggles
                document.querySelectorAll('.chapter-box-toggle').forEach(icon => {
                    icon.classList.remove('expanded');
                });

                pageDetails.style.display = 'block';
                this.classList.add('expanded');
            }
        });
    });

</script>

@endsection