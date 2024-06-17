@extends('superadmin-layout')

@section('content')

<div class="container-fluid">

    <div class="card">

        <div class="card-body">

        <form action="{{ route('superadmin.edit_company.post', ['id' => $company->CompanyID]) }}" method="POST" enctype="multipart/form-data">

                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Company Name:</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter company name" value="{{ $company->Name }}" required>

                </div>

                <div class="mb-3">
                    <label for="industry" class="form-label">Industry:</label>
                    <select class="form-select" id="industry" name="industry" required>
                        <option value="" disabled>Select Industry</option>

                        @foreach($industries as $industry)
                            <option value="{{ $industry }}" {{ $company->Industry == $industry ? 'selected' : '' }}>
                                {{ $industry }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Address:</label>
                       <textarea class="form-control" id="address" name="address" rows="3" placeholder="Enter company address" required>{{ $company->Address }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="website" class="form-label">Website:</label>
                    <textarea class="form-control" id="website" name="website" rows="3" placeholder="Enter company website" required>{{ $company->Website }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="logo" class="form-label">Company Logo:</label>
                    <input type="file" class="form-control" id="logo" name="logo" >
                    @if($company->company_logo)
                        <img src="{{ Storage::url($company->company_logo) }}" alt="CompanyLogo" style="max-width: 200px; margin-top: 10px;">
                    @endif
                </div>



                <div class="row">
                    <div class="col-md-6">
                        <label for="sidebar_color" class="form-label">Primary Color:</label>
                        <input type="text" class="form-control" id="sidebar_color" name="sidebar_color" placeholder="Enter color hex code"
                            value="{{ $company->button_color }}">
                    </div>

                    <div class="col-md-6">
                        <label for="button_color" class="form-label">Secondary Color:</label>
                        <input type="text" class="form-control" id="button_color" name="button_color"
                            placeholder="Enter color hex code" value="{{ $company->button_color }}">
                    </div>
                </div>
                <br>

                <!-- Added by Alda for Subscription -->
                <div class="mb-3">
                    <label for="subscription_status" class="form-label">Subscription Status:</label>
                    <p>
                        @if($company->subscription_ends_at && \Carbon\Carbon::now()->lessThanOrEqualTo(\Carbon\Carbon::parse($company->subscription_ends_at)->addDays(3)))
                            <span class="text-success">Valid</span>
                        @else
                            <span class="text-danger">Invalid</span>
                        @endif
                    </p>
                </div>

                <div class="mb-3">
                    <label for="subscription_starts_at" class="form-label">Subscription Starts At:</label>
                    <input type="date" class="form-control" id="subscription_starts_at" name="subscription_starts_at"
                        value="{{ $company->subscription_starts_at ? \Carbon\Carbon::parse($company->subscription_starts_at)->format('Y-m-d') : '' }}">
                </div>

                <div class="mb-3">
                    <label for="subscription_ends_at" class="form-label">Subscription Ends At:</label>
                    <input type="date" class="form-control" id="subscription_ends_at" name="subscription_ends_at"
                        value="{{ $company->subscription_ends_at ? \Carbon\Carbon::parse($company->subscription_ends_at)->format('Y-m-d') : '' }}">
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
            </form>

        </div>
    </div>
</div>

@endsection