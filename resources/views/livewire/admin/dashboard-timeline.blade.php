<div>
    <!-- TODO: Implement activity log https://spatie.be/docs/laravel-activitylog/v4/introduction -->

    @use('Illuminate\Support\Str')
    @use('Illuminate\Support\Facades\Storage')
    @use('Illuminate\Support\Arr')
    @use('Carbon\Carbon')
    @use('App\Models\User')
    @use('App\Models\Profile')

    <!-- Use wire:key for foreach loop -->
    @foreach ($activities as $activity)

    <div wire:key="{{ $activity->id }}">

        <!-- User account events -->
        @if(Str::contains($activity->description, 'User account'))

        @php
            $subject = User::all()->find($activity->subject_id);
            $subjectProfile = $subject->profile;
        @endphp
        <!-- Timeline Card -->
        <div class="card text-bg-light mb-3">
            <div class="card-body">
                <div class="row"> 
                    <!-- Profile Picture -->
                    <div class="col-lg-3 d-flex align-items-center p-2">
                    <img src="{{Storage::url($subjectProfile->profile_picture)}}" alt="" class="rounded-circle ratio ratio-1x1" style="max-height:4rem; max-width:4rem;">
                    </div>

                    <!-- Activity Description -->
                    <div class="col-lg-9">
                        <h5 class="card-title"><b>{{$subject->name}}</b> user account {{$activity->event}}.</h5>
                    </div>
                </div>
                <!-- Timestamp and Relevant Info -->
                <div class="row gx-3 mt-2">
                    <div class="col-lg-8 d-flex align-items-center">
                        <!-- Timestamp -->
                        <p class="card-subtitle">{{Carbon::parse($activity->activity_created_at)->setTimezone('Asia/Kuala_Lumpur')->toDayDateTimeString()}}</p>
                    </div>
                    <div class="col-lg-4 d-flex align-items-center">
                        <!-- Info -->
                        <p class="card-subtitle">{{$subjectProfile->dept}}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile events -->
        @elseif(Str::contains($activity->description, 'User profile'))

        @php
            $subject = Profile::all()->find($activity->subject_id);
            $subjectAccount = $subject->user;
        @endphp
        
        <!-- Timeline Card -->
        <div class="card text-bg-light mb-3">
            <div class="card-body">
                <div class="row"> 
                    <!-- Profile Picture -->
                    <div class="col-lg-3 d-flex align-items-center p-2">
                    <img src="{{Storage::url($subject->profile_picture)}}" alt="" class="rounded-circle ratio ratio-1x1" style="max-height:4rem; max-width:4rem;">
                    </div>

                    <!-- Activity Description -->
                    <div class="col-lg-9">
                        <h5 class="card-title"><b>{{$subject->name}}</b> user profile {{$activity->event}}.</h5>
                    </div>
                </div>
                <!-- Timestamp and Relevant Info -->
                <div class="row gx-3 mt-2">
                    <div class="col-lg-8 d-flex align-items-center">
                        <!-- Timestamp -->
                        <p class="card-subtitle">{{Carbon::parse($activity->activity_created_at)->setTimezone('Asia/Kuala_Lumpur')->toDayDateTimeString()}}</p>
                    </div>
                    <div class="col-lg-4 d-flex align-items-center">
                        <!-- Info -->
                        <p class="card-subtitle">{{$subject->dept}}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quiz events -->
        @elseif(Str::contains($activity->description, 'Quiz'))
        <!-- Timeline Card -->
        <div class="card text-bg-light mb-3">
            <div class="card-body">
                <div class="row"> 
                    <!-- Profile Picture -->
                    <div class="col-lg-3 d-flex align-items-center p-2">
                    <img src="{{Storage::url($activity->profile_picture)}}" alt="" class="rounded-circle ratio ratio-1x1" style="max-height:4rem; max-width:4rem;">
                    </div>

                    <!-- Activity Description -->
                    <div class="col-lg-9">
                        <h5 class="card-title"><b>{{$activity->name}}</b> has completed Quiz {{Arr::get($activity->properties,'quiz')}}, Chapter {{Arr::get($activity->properties,'chapter')}}.</h5> 
                    </div>
                </div>
                <!-- Timestamp and Relevant Info -->
                <div class="row gx-3 mt-2">
                    <div class="col-lg-8 d-flex align-items-center">
                        <!-- Timestamp -->
                        <p class="card-subtitle">{{Carbon::parse($activity->activity_created_at)->setTimezone('Asia/Kuala_Lumpur')->toDayDateTimeString()}}</p>
                    </div>
                    <div class="col-lg-4 d-flex align-items-center">
                        <!-- Info -->
                        <p class="card-subtitle">{{Arr::get($activity->properties,'module')}}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Flow events -->
        @elseif(Str::contains($activity->description, 'Flow'))
        <!-- Timeline Card -->
        <div class="card text-bg-light mb-3">
            <div class="card-body">
                <div class="row"> 
                    <!-- Profile Picture -->
                    <div class="col-lg-3 d-flex align-items-center p-2">
                    <img src="{{Storage::url($activity->profile_picture)}}" alt="" class="rounded-circle ratio ratio-1x1" style="max-height:4rem; max-width:4rem;">
                    </div>

                    <!-- Activity Description -->
                    <div class="col-lg-9">
                        <h5 class="card-title"><b>{{$activity->name}}</b> has completed {{Arr::get($activity->properties,'module')}}.</h5> 
                    </div>
                </div>
                <!-- Timestamp and Relevant Info -->
                <div class="row gx-3 mt-2">
                    <div class="col-lg-8 d-flex align-items-center">
                        <!-- Timestamp -->
                        <p class="card-subtitle">{{Carbon::parse($activity->activity_created_at)->setTimezone('Asia/Kuala_Lumpur')->toDayDateTimeString()}}</p>
                    </div>
                    <div class="col-lg-4 d-flex align-items-center">
                        <!-- Info -->
                        <p class="card-subtitle">{{Arr::get($activity->properties,'module')}}</p>
                    </div>
                </div>
            </div>
        </div>

        @endif

    </div>

    @endforeach

</div>
