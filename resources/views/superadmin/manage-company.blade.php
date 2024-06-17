@extends('superadmin-layout')

@section('content')

<div class="container-fluid">
    <h1 class="fw-semibold mb-4">Manage Companies</h1>
    <div class="row">
        <div class="col-md-11">
            <div class="col-md-4">
                <div class="input-group mb-3">
                    <span class="input-group-text">
                        <i class="ti ti-search"></i>
                    </span>
                    <input type="text" class="form-control" id="searchField" placeholder="Enter Company Name"
                        aria-label="Enter Company Name" aria-describedby="searchButton">
                </div>
            </div>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-primary m-1"
                onclick="window.location.href='{{ route('superadmin.add_company') }}'">Add</button>
        </div>
    </div>
    <br>
    @foreach($companies as $company)
        <div class="col-md-12 profile-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-10">
                            <h5 class="card-title">{{ $company->Name }}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">{{ $company->Industry }}</h6>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('superadmin.edit_company', ['id' => $company->CompanyID]) }}"
                                class="card-link">Edit</a>
                            <a href="#" class="card-link" onclick="confirmDelete('{{ route('superadmin.delete_company', ['id' => $company->CompanyID]) }}')">Delete</a>
                        </div>
                    </div>

                    <!-- Added by Alda for Subscription -->
                    <div>
                        <p>Subscription Status: 
                            @if($company->subscription_ends_at && Carbon\Carbon::now()->lessThanOrEqualTo(Carbon\Carbon::parse($company->subscription_ends_at)->addDays(3)))
                                <span style="color: green;">Valid</span>
                            @else
                                <span style="color: red;">Invalid</span>
                            @endif
                        </p>
                    </div>


                </div>
            </div>
        </div>
    @endforeach

</div>

<script>
    $(document).ready(function () {
        $('#searchField').on('input', function () {
            var searchValue = $(this).val().toLowerCase();
            $('.profile-card').each(function () {
                var nameText = $(this).find('.card-title').text().toLowerCase();
                var idText = $(this).find('.card-subtitle').text().toLowerCase();
                if (nameText.includes(searchValue) || idText.includes(searchValue)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });

    function confirmDelete(url) {
        if (confirm('Are you sure you want to delete this company?')) {
        // If the user clicks "OK", redirect to the delete URL
            window.location.href = url;
        }
    }
</script>

@endsection
