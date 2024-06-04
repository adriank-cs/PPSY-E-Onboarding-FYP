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
                        <img src="{{ Storage::url($company->company_logo) }}" alt="Company Logo" style="max-width: 200px; margin-top: 10px;">
                    @endif
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label for="sidebar_color" class="form-label">Primary Color:</label>
                        <input type="text" class="form-control" id="sidebar_color" name="sidebar_color" placeholder="Enter color hex code"
                           value="{{ $company->button_color }}" >
                    </div>

                    <div class="col-md-6">
                        <label for="button_color" class="form-label">Secondary Color:</label>
                        <input type="text" class="form-control" id="button_color" name="button_color" placeholder="Enter color hex code"
                           value="{{ $company->sidebar_color }}" >
                    </div>
                </div>
                <br>

                <button type="submit" class="btn btn-primary">Update</button>
            </form>

        </div>
    </div>
</div>

@endsection