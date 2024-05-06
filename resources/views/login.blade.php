<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <!-- Show password flash eye icon !-->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @include('login-layout')


</head>

	<div class="Container">
		<form action="{{route('login.post')}}" method="POST">
			@csrf
			@if(session()->has('error'))
			<div class="alert-danger" role="alert">
				{{session('error')}}
			</div>
			@endif
			
			<center>
				@if(session()->has('success'))
					<div class="alert-success" role="alert">
						{{session('success')}}
					</div>
				@endif
			</center>

			<div class="Login">
				<input type="text" class="form-control" id="email" name="email" placeholder="Email" required>
				<div style="position: relative;"> 
					<input type="password" class="password-control" id="showPassword" name="password" placeholder="Password" required>
					<i class="fa fa-eye-slash toggle-password" id="toggle-password"></i>
				</div>
				<!--<input type="submit" value="Login" style="background-color: {{ $companies_colors->button_color ?? '#007bff' }}; color: {{ $companies_colors->text_color ?? '#ffffff' }}">!-->
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

	<script>
        const togglePassword = document.getElementById('toggle-password');
        const passwordInput = document.getElementById('showPassword');

        togglePassword.addEventListener('click', () => {
            // Toggle the type attribute
			if (passwordInput.getAttribute('type') === 'password'){
				passwordInput.setAttribute('type', 'text');
			}else{
				passwordInput.setAttribute('type', 'password');
			}

            // Toggle the eye icon
			 togglePassword.classList.toggle('fa-eye-slash');
             togglePassword.classList.toggle('fa-eye');
            
        });
    </script>




