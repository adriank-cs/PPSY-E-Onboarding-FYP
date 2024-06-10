@extends('admin-layout')

@section('content')

<style>


</style>

<div class="custom-container-fluid">

    <div class="row">
        <div class="col-md-12">
            <div class="module-title-page">
                <h5 class="page-title">{{ $module->title }}</h5>
                <i class="ti ti-list-check" id="openSidebar"></i>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="chapter-name">
                <h3>{{ $chapter->title }}</h3>
            </div>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-md-12">
            <div class="viewpage-name">
                <h3>{{ $viewpage->title }}</h3>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="viewpage-content">
                {!!$viewpage->content !!}

                @if(!empty($pdfAttachments))
                    <div class="row">
                        <div class="col-md-12">
                            <h5>PDF Attachments:</h5>
                            <ul>
                                @foreach($pdfAttachments as $pdf)
                                    <li><a class="pdf-link" data-url="{{ $pdf['url'] }}">{{ $pdf['name'] }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <button type="button" class="btn btn-primary fixed-bottom-button">Next</button>

<!-- Modal for PDF viewing -->
<div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pdfModalLabel">PDF Viewer</h5>
                    <button type="button" class="btn btn-secondary" id="cancelButton" data-bs-dismiss="modal" aria-label="Close">Close</button>
                </div>
                <div class="modal-body p-0">
                    <iframe id="pdfFrame" src="" style="width: 100%; height: 100vh;" frameborder="0"></iframe>
                </div>
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
                            {{ $pages->has($chapter->id) ? $pages[$chapter->id]->count() : 0 }}
                            page(s)
                        </div>
                    </div>
                    <div class="chapter-page-details">
                        @if($pages->has($chapter->id))
                            @foreach($pages[$chapter->id] as $item)
                                <div class="sidebar-custom-page-list"><span>
                                        <input type="checkbox"
                                            {{ $item->itemProgress && $item->itemProgress->IsCompleted ? 'checked' : '' }}
                                            disabled></span>
                                    <span><a
                                            href="{{ route('admin.view_page', ['id' => $item->id]) }}">{{ $item->title }}</a></span>
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

    // Open PDF in modal
    document.querySelectorAll('.pdf-link').forEach(link => {
        link.addEventListener('click', function () {
            const pdfUrl = this.getAttribute('data-url');
            document.getElementById('pdfFrame').src = pdfUrl;
            $('#pdfModal').modal('show');
        });
    });

    
</script>

@endsection
