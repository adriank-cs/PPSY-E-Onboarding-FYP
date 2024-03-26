@extends('employee-layout')

@section('content')

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="text-left">
                <div class="row">
                    <div class="col-md-3">
                        <h1> Discussion </h1>
                    </div>
                </div>
            </div>

            <!-- Include the TinyMCE configuration component -->
            <x-head.tinymce-config/>

            <!-- entry box and search button -->
            <br>
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <!-- Include the TinyMCE editor form component -->
                        <x-forms.tinymce-editor/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
