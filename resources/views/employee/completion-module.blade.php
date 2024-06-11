@extends('employee-layout')

@section('content')

<div class="custom-container-fluid">
    <div class="text-center mt-5">
        <h1>Congratulations, {{ auth()->user()->name }}!</h1>
        <p class="lead">You have successfully completed the module: "{{ $module->title }}"!</p>
        <img src="{{ asset('images/congratulations.jpg') }}" alt="Congratulations" class="img-fluid mt-4">
    </div>
    <div class="text-center mt-5"> <button type="button" class="btn btn-primary" id="backToModules">Back to Modules</button></div>
   
</div>

<script>
document.getElementById('backToModules').addEventListener('click', function() {
        window.location.href = '{{ route('employee.my_modules') }}';
    });
</script>
@endsection