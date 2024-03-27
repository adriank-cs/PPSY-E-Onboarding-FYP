<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>

    @include('login-layout')

</head>

	<div class="Container">
		<form action="{{route('login.post')}}" method="POST">
			@csrf
			@if($errors->any())
			<div class="col-12">
				@foreach($errors->all() as $error)
				<div class="alert alert-danger" role="alert">
					{{$error}}
				</div>
				@endforeach
			</div>
			@endif

			@if(session()->has('error'))
			<div class="alert-danger" role="alert">
				{{session('error')}}
			</div>
			@endif

			@if(session()->has('success'))
			<div class="alert-success" role="alert">
				{{session('success')}}
			</div>
			@endif

			<div class="Login">
				<input type="text" class="form-control" id="email" name="email" placeholder="Email">
				<input type="password" class="form-control" id="showPassword" name="password" placeholder="Password">
				<br>
				<input type="submit" value="Login">
			</div>

			<div class="ForgotPassword">
				<a href="{{ route('forgetpass') }}" style="color:black" class="btn">
					Forgot Password
				</a>
			</div>

		</form>

		<div class="GoogleLogin">
			<a href="{{ route('google-auth') }}">
				<button>Continue with Google</button>
			</a>
        </div>

	</div>




