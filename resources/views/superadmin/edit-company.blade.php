@extends('superadmin-layout')

@section('content')

<div class="container-fluid">

    <div class="card">

        <div class="card-body">

        <form action="{{ route('superadmin.edit_company.post', ['id' => $company->CompanyID]) }}" method="POST">
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

                <button type="submit" class="btn btn-primary">Update</button>
            </form>

        </div>
    </div>
</div>

@endsection