@extends('admin-layout')
@section('title', 'Admin | Login Leaderboard')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <h4>Login Streak Leaderboard</h4>
        <div class="col-md-12">
            <div class="p-4 text-center">
                @if($users->isNotEmpty())
                    <div class="row">
                        <div class="col-md-12">
                            <img src="{{ Storage::url($users[0]->profile->profile_picture) }}" alt="User Image" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                            <h3>{{ $users[0]->name }}</h3>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <p style="font-size:50px;" class="mb-0">🔥</p>
                                    <h5>{{ $users[0]->login_streak }} Day Streak</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <p style="font-size:50px;" class="mb-0">🏆</p>
                                    <h5>Ranked #1</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="row">
                            @foreach($users as $index => $user)
                                @if($index > 0)
                                    <div class="col-md-12">
                                        <div class="card leaderboard-card">
                                            <div class="card-body">
                                                <div>
                                                    <img src="{{ Storage::url($user->profile->profile_picture) }}" alt="Photo" class="img-fluid rounded-circle profile-picture-leaderboard">
                                                </div>
                                                <div>
                                                    <h5 class="card-title">{{ $user->name }}</h5>
                                                </div>
                                                <div>
                                                    <p class="card-title">{{ $user->login_streak }} Day Streak</p>
                                                </div>
                                                <div>
                                                    <p class="card-title">Ranked #{{ $index + 1 }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
