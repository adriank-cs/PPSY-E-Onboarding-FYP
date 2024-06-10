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

		<!-- <div class="GoogleLogin">
			<a href="{{ route('google-auth') }}">
			<button>
				<i class="fab fa-google"></i>Continue with Google
			</button>
			</a>
        </div> -->

		<div class="GoogleLogin">
                <a href="{{ route('google-auth') }}">
                    <button>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="24px" height="24px">
                            <path fill="#4285F4" d="M24 9.5c3.2 0 5.7 1.1 7.5 2.8l5.5-5.5C33.6 3.2 29.1 1 24 1 14.8 1 7.4 6.4 4.1 14.3l6.8 5.3C12.4 13 17.7 9.5 24 9.5z"/>
                            <path fill="#34A853" d="M46.9 24.5c0-1.7-.2-3.3-.5-4.8H24v9.5h13c-.5 2.5-2.1 4.5-4.5 5.9l6.8 5.3c4-3.6 6.6-8.9 6.6-15.9z"/>
                            <path fill="#FBBC05" d="M8.9 28.6C7.7 25.7 7.7 22.3 8.9 19.4L2.1 14.1C.7 17.4 0 21.6 0 24s.7 6.6 2.1 9.9l6.8-5.3z"/>
                            <path fill="#EA4335" d="M24 47c6.1 0 11.2-2 14.9-5.4l-6.8-5.3c-1.9 1.3-4.3 2.1-7 2.1-6.2 0-11.5-4.2-13.4-9.9L2.1 33.9C5.4 41.8 14.8 47 24 47z"/>
                            <path fill="none" d="M0 0h48v48H0z"/>
                        </svg>
                        Continue with Google
                    </button>
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




