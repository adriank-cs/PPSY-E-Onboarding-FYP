@extends('admin-layout')
@section('title', 'Admin | Colleague Details')
@section('content')

<div class="container-fluid">
    <div class="row gradient-bg" style="background: linear-gradient(to right, #ff7e5f, #feb47b);">
        <div class="col-md-12 text-center py-5">
            <img src="{{ Storage::url($colleague->profile->profile_picture) }}" alt="Profile Picture" class="img-fluid rounded-circle profile-picture-large">
            <h1 style="color:white;">{{ $colleague->name }}</h1>
            <p ><a
            href="mailto:{{ $colleague->email }}" class="colleague-details-mailink"><i class="ti ti-mail colleague-details-email"></i>{{ $colleague->email }}</a></p>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card mb-4 card-profile-details">
                <div class="card-header">CONTACT DETAILS</div>
                <div class="card-body">
                    <p>Email Address: <a href="mailto:{{ $colleague->email }}">{{ $colleague->email }}</a></p>
                    <p>Phone No: {{ $colleague->profile->phone_no }}</p>
                    <p>Mailing Address: {{ $colleague->profile->address }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card mb-4 card-profile-details">
                <div class="card-header">OFFICE DETAILS</div>
                <div class="card-body">
                    <p>Employee ID: {{ $colleague->profile->employee_id }}</p>
                    <p>Position: {{ $colleague->profile->position }}</p>
                    <p>Department: {{ $colleague->profile->dept }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card mb-4 card-profile-details">
                <div class="card-header">ABOUT ME</div>
                <div class="card-body">
                    <p>Gender: {{ $colleague->profile->gender }}</p>
                    <p>Age: {{ $colleague->profile->age }}</p>
                    <p>Date of Birth: {{ $colleague->profile->dob }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card mb-4 card-profile-details">
                <div class="card-header">BIOGRAPHY</div>
                <div class="card-body">
                    <p>{!! $colleague->profile->bio !!}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var gradients = [
            'linear-gradient(to right, #ff7e5f, #feb47b)',
            'linear-gradient(to right, #4facfe, #00f2fe)',
            'linear-gradient(to right, #43e97b, #38f9d7)',
            'linear-gradient(to right, #fa709a, #fee140)',
            'linear-gradient(to right, #30cfd0, #330867)',
            'linear-gradient(to right, #ff6a88, #ff99ac)'
        ];
        var randomGradient = gradients[Math.floor(Math.random() * gradients.length)];
        document.querySelector('.gradient-bg').style.background = randomGradient;
    });
</script>

@endsection